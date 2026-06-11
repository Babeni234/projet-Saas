<?php

namespace App\Http\Controllers;

use App\Models\Batiment;
use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BatimentController extends Controller
{
    /**
     * Retourne la liste des bâtiments (JSON API).
     * Filtre automatiquement par compagnie connectée.
     * Si l'utilisateur est lié à une agence, filtre aussi par agence.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $companyProfileId = $user->company_profile_id;

        $query = Batiment::where('company_profile_id', $companyProfileId)
                         ->where('deleted', false)
                         ->with(['proprietaire', 'agency']);

        // Filtre agence si l'utilisateur est un agent
        $isAgent = $user->employee && $user->employee->agency_id !== null;
        if ($isAgent) {
            $query->where('agency_id', $user->employee->agency_id);
        }

        $batiments = $query->orderBy('nom')->get()->map(fn($b) => $this->formatBatiment($b));

        return response()->json($batiments);
    }

    /**
     * Crée un nouveau bâtiment.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nom'                => 'required|string|max:255',
            'agency_id'          => 'nullable|integer|exists:agencies,id',
            'proprietaire_id'    => 'nullable|integer|exists:proprietaires,id',
            'pays'               => 'required|string|max:10',
            'ville'              => 'required|string|max:100',
            'quartier'           => 'nullable|string|max:100',
            'adresse'            => 'required|string|max:255',
            'code_postal'        => 'nullable|string|max:20',
            'latitude'           => 'nullable|numeric',
            'longitude'          => 'nullable|numeric',
            'etages'             => 'nullable|integer|min:0',
            'appartements'       => 'nullable|integer|min:0',
            'surface_totale'     => 'nullable|numeric|min:0',
            'annee_construction' => 'nullable|integer|min:1800|max:2100',
            'type_batiment'      => 'nullable|string|max:100',
            'type_structure'     => 'nullable|string|max:100',
            'nombre_parkings'    => 'nullable|integer|min:0',
            'ascenseur'          => 'nullable|boolean',
            'gardiennage'        => 'nullable|boolean',
            'piscine'            => 'nullable|boolean',
            'generatrice'        => 'nullable|boolean',
            'statut'             => 'nullable|string|in:Actif,Maintenance,Fermé,En construction',
            'description'        => 'nullable|string',
            'notes_internes'     => 'nullable|string',
        ]);

        // La compagnie est toujours celle de l'utilisateur connecté
        $validated['company_profile_id'] = $user->company_profile_id;

        // Si agency_id fourni, on valide que cette agence appartient bien à la compagnie
        if (!empty($validated['agency_id'])) {
            $agency = Agency::where('id', $validated['agency_id'])
                            ->where('company_profile_id', $user->company_profile_id)
                            ->firstOrFail();
        }

        $batiment = Batiment::create($validated);
        $batiment->load(['proprietaire', 'agency']);

        return response()->json($this->formatBatiment($batiment), 201);
    }

    /**
     * Met à jour un bâtiment.
     */
    public function update(Request $request, Batiment $batiment)
    {
        $this->authorizeCompany($batiment);

        $validated = $request->validate([
            'nom'                => 'sometimes|required|string|max:255',
            'agency_id'          => 'nullable|integer|exists:agencies,id',
            'proprietaire_id'    => 'nullable|integer|exists:proprietaires,id',
            'pays'               => 'sometimes|required|string|max:10',
            'ville'              => 'sometimes|required|string|max:100',
            'quartier'           => 'nullable|string|max:100',
            'adresse'            => 'sometimes|required|string|max:255',
            'code_postal'        => 'nullable|string|max:20',
            'latitude'           => 'nullable|numeric',
            'longitude'          => 'nullable|numeric',
            'etages'             => 'nullable|integer|min:0',
            'appartements'       => 'nullable|integer|min:0',
            'surface_totale'     => 'nullable|numeric|min:0',
            'annee_construction' => 'nullable|integer|min:1800|max:2100',
            'type_batiment'      => 'nullable|string|max:100',
            'type_structure'     => 'nullable|string|max:100',
            'nombre_parkings'    => 'nullable|integer|min:0',
            'ascenseur'          => 'nullable|boolean',
            'gardiennage'        => 'nullable|boolean',
            'piscine'            => 'nullable|boolean',
            'generatrice'        => 'nullable|boolean',
            'statut'             => 'nullable|string|in:Actif,Maintenance,Fermé,En construction',
            'description'        => 'nullable|string',
            'notes_internes'     => 'nullable|string',
        ]);

        $batiment->update($validated);
        $batiment->load(['proprietaire', 'agency']);

        return response()->json($this->formatBatiment($batiment->fresh()));
    }

    /**
     * Supprime (soft-delete) un bâtiment.
     */
    public function destroy(Batiment $batiment)
    {
        $this->authorizeCompany($batiment);

        $batiment->update(['deleted' => true]);
        $batiment->delete();

        return response()->json(['message' => 'Bâtiment supprimé avec succès.']);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function formatBatiment(Batiment $b): array
    {
        return [
            'id'                 => $b->id,
            'uuid'               => $b->uuid,
            'nom'                => $b->nom,
            'reference'          => $b->reference,
            'company_profile_id' => $b->company_profile_id,
            'agency_id'          => $b->agency_id,
            'agency_name'        => $b->agency?->name,
            'proprietaire_id'    => $b->proprietaire_id,
            'proprietaire_nom'   => $b->proprietaire?->nom_complet,
            'pays'               => $b->pays,
            'ville'              => $b->ville,
            'quartier'           => $b->quartier,
            'adresse'            => $b->adresse,
            'code_postal'        => $b->code_postal,
            'latitude'           => $b->latitude,
            'longitude'          => $b->longitude,
            'etages'             => $b->etages ?? 0,
            'appartements'       => $b->appartements ?? 0,
            'surface_totale'     => $b->surface_totale,
            'annee_construction' => $b->annee_construction,
            'type_batiment'      => $b->type_batiment,
            'type_structure'     => $b->type_structure,
            'nombre_parkings'    => $b->nombre_parkings ?? 0,
            'ascenseur'          => $b->ascenseur,
            'gardiennage'        => $b->gardiennage,
            'piscine'            => $b->piscine,
            'generatrice'        => $b->generatrice,
            'statut'             => $b->statut,
            'description'        => $b->description,
            'notes_internes'     => $b->notes_internes,
            'created_at'         => $b->created_at?->toDateString(),
        ];
    }

    private function authorizeCompany(Batiment $batiment): void
    {
        $user = Auth::user();
        abort_if(
            $batiment->company_profile_id !== $user->company_profile_id,
            403,
            'Accès non autorisé.'
        );
    }
}
