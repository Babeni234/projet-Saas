<?php

namespace App\Http\Controllers;

use App\Models\Logement;
use App\Models\Batiment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogementController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $companyProfileId = $user->company_profile_id;

        $query = Logement::where('company_profile_id', $companyProfileId)
            ->where('deleted', false)
            ->with(['batiment', 'categorie.typeContrat', 'agency']);

        // Filter by agency if the user is an agent
        if ($user->employee && $user->employee->agency_id !== null) {
            $query->where('agency_id', $user->employee->agency_id);
        }

        $logements = $query->orderBy('reference', 'desc')->get()->map(fn($l) => $this->formatLogement($l));

        return response()->json($logements);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $companyProfileId = $user->company_profile_id;

        $validated = $request->validate([
            'batiment_id'  => 'nullable|integer|exists:batiments,id',
            'categorie_id' => 'required|integer|exists:categories,id',
            'etage'        => 'nullable|integer',
            'surface'      => 'nullable|integer',
            'loyer'        => 'required|numeric|min:0',
            'statut'       => 'nullable|string|in:Libre,Occupé,Réservé',
        ]);

        // Auto-assign company_profile_id
        $validated['company_profile_id'] = $companyProfileId;

        // Auto-assign agency_id
        $agencyId = null;
        if ($user->employee && $user->employee->agency_id !== null) {
            $agencyId = $user->employee->agency_id;
        }

        if (!empty($validated['batiment_id'])) {
            $batiment = Batiment::where('id', $validated['batiment_id'])
                ->where('company_profile_id', $companyProfileId)
                ->firstOrFail();
            if ($batiment->agency_id !== null) {
                $agencyId = $batiment->agency_id;
            }
        }
        $validated['agency_id'] = $agencyId;

        $logement = Logement::create($validated);
        $logement->load(['batiment', 'categorie', 'agency']);

        return response()->json($this->formatLogement($logement), 201);
    }

    public function update(Request $request, Logement $logement)
    {
        $this->authorizeCompany($logement);
        $user = Auth::user();
        $companyProfileId = $user->company_profile_id;

        $validated = $request->validate([
            'batiment_id'  => 'nullable|integer|exists:batiments,id',
            'categorie_id' => 'sometimes|required|integer|exists:categories,id',
            'etage'        => 'nullable|integer',
            'surface'      => 'nullable|integer',
            'loyer'        => 'sometimes|required|numeric|min:0',
            'statut'       => 'nullable|string|in:Libre,Occupé,Réservé',
        ]);

        if (array_key_exists('batiment_id', $validated)) {
            $agencyId = null;
            if ($user->employee && $user->employee->agency_id !== null) {
                $agencyId = $user->employee->agency_id;
            }

            if (!empty($validated['batiment_id'])) {
                $batiment = Batiment::where('id', $validated['batiment_id'])
                    ->where('company_profile_id', $companyProfileId)
                    ->firstOrFail();
                if ($batiment->agency_id !== null) {
                    $agencyId = $batiment->agency_id;
                }
            }
            $validated['agency_id'] = $agencyId;
        }

        $logement->update($validated);
        $logement->load(['batiment', 'categorie', 'agency']);

        return response()->json($this->formatLogement($logement->fresh()));
    }

    public function destroy(Logement $logement)
    {
        $this->authorizeCompany($logement);

        $logement->update(['deleted' => true]);
        $logement->delete();

        return response()->json(['message' => 'Bien immobilier supprimé avec succès.']);
    }

    private function formatLogement(Logement $l): array
    {
        return [
            'id'                 => $l->id,
            'uuid'               => $l->uuid,
            'reference'          => $l->reference,
            'company_profile_id' => $l->company_profile_id,
            'agency_id'          => $l->agency_id,
            'agency_name'        => $l->agency?->name,
            'batiment_id'        => $l->batiment_id,
            'batiment'           => $l->batiment?->nom,
            'categorie_id'       => $l->categorie_id,
            'categorie'          => $l->categorie?->nom,
            'type_contrat_id'    => $l->categorie?->type_contrat_id,
            'type_contrat_nom'   => $l->categorie?->typeContrat?->nom,
            'etage'              => $l->etage,
            'surface'            => $l->surface,
            'loyer'              => $l->loyer,
            'statut'             => $l->statut,
            'created_at'         => $l->created_at?->toDateString(),
        ];
    }

    private function authorizeCompany(Logement $logement): void
    {
        $user = Auth::user();
        abort_if(
            $logement->company_profile_id !== $user->company_profile_id,
            403,
            'Accès non autorisé.'
        );
    }
}
