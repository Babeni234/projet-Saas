<?php

namespace App\Http\Controllers;

use App\Models\Engagement;
use App\Models\TypeEngagement;
use App\Models\Agency;
use App\Models\Batiment;
use App\Models\Contrat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EngagementController extends Controller
{
    /**
     * Display a listing of engagements.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $companyProfileId = $user->company_profile_id;
        $today = now()->format('Y-m-d');

        // 1. Dynamic status updates for this company before query
        Engagement::where('company_profile_id', $companyProfileId)
            ->where('statut', 'actif')
            ->where('date_debut', '<=', $today)
            ->update(['statut' => 'en cours']);

        Engagement::where('company_profile_id', $companyProfileId)
            ->whereIn('statut', ['actif', 'en cours'])
            ->where('date_fin', '<', $today)
            ->update([
                'statut' => 'expirer',
                'statut_honneur' => 'non honner'
            ]);

        // 2. Fetch engagements
        $query = Engagement::with(['typeEngagement', 'locataire.user', 'contrat', 'batiment', 'agency'])
            ->where('company_profile_id', $companyProfileId);

        // Agency scope
        if ($user->employee && $user->employee->agency_id !== null) {
            $query->where('agency_id', $user->employee->agency_id);
        }

        $engagements = $query->orderBy('created_at', 'desc')->get();

        // 3. Fetch related lists for cascade and dropdowns
        $typeEngagements = TypeEngagement::where('company_profile_id', $companyProfileId)->orderBy('nom')->get();
        
        $agenciesQuery = Agency::where('company_profile_id', $companyProfileId);
        $batimentsQuery = Batiment::where('company_profile_id', $companyProfileId);
        $contratsQuery = Contrat::where('company_profile_id', $companyProfileId)->where('statut', 'Actif');

        if ($user->employee && $user->employee->agency_id !== null) {
            $agenciesQuery->where('id', $user->employee->agency_id);
            $batimentsQuery->where('agency_id', $user->employee->agency_id);
            $contratsQuery->whereHas('logement.batiment', function ($q) use ($user) {
                $q->where('agency_id', $user->employee->agency_id);
            });
        }

        $agencies = $agenciesQuery->get();
        $batiments = $batimentsQuery->get();
        $contrats = $contratsQuery->with(['locataire.user', 'logement'])->get()->map(function ($c) {
            return [
                'id' => $c->id,
                'numero' => $c->numero,
                'reference' => $c->reference,
                'locataire' => $c->locataire ? $c->locataire->nom : 'N/A',
                'locataire_id' => $c->locataire_id,
                'batiment_id' => $c->logement ? $c->logement->batiment_id : null,
                'loyer' => $c->loyer,
            ];
        });

        return response()->json([
            'engagements' => $engagements,
            'typeEngagements' => $typeEngagements,
            'agencies' => $agencies,
            'batiments' => $batiments,
            'contrats' => $contrats,
        ]);
    }

    /**
     * Store a newly created engagement.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $companyProfileId = $user->company_profile_id;

        $validated = $request->validate([
            'reference'          => 'required|string|max:255',
            'type_engagement_id' => 'required|exists:type_engagements,id',
            'locataire_id'       => 'required|exists:locataires,id',
            'contrat_id'         => 'required|exists:contrats,id',
            'batiment_id'        => 'required|exists:batiments,id',
            'agency_id'          => 'nullable|exists:agencies,id',
            'date_debut'         => 'required|date',
            'date_fin'           => 'required|date|after_or_equal:date_debut',
            'montant'            => 'nullable|numeric|min:0',
            'instructions'       => 'nullable|string',
            'content'            => 'nullable|string',
        ]);

        if ($user->employee && $user->employee->agency_id !== null) {
            $validated['agency_id'] = $user->employee->agency_id;
        }

        $today = now()->format('Y-m-d');
        $statut = 'actif';
        if ($validated['date_debut'] <= $today) {
            $statut = 'en cours';
        }
        if ($validated['date_fin'] < $today) {
            $statut = 'expirer';
        }

        $engagement = Engagement::create([
            ...$validated,
            'company_profile_id' => $companyProfileId,
            'statut'             => $statut,
            'statut_honneur'     => ($statut === 'expirer') ? 'non honner' : null,
        ]);

        return response()->json($engagement->load(['typeEngagement', 'locataire', 'contrat', 'batiment', 'agency']), 201);
    }

    /**
     * Update the specified engagement.
     */
    public function update(Request $request, Engagement $engagement)
    {
        $user = Auth::user();
        $this->authorizeCompany($engagement);

        $validated = $request->validate([
            'reference'          => 'sometimes|required|string|max:255',
            'type_engagement_id' => 'sometimes|required|exists:type_engagements,id',
            'locataire_id'       => 'sometimes|required|exists:locataires,id',
            'contrat_id'         => 'sometimes|required|exists:contrats,id',
            'batiment_id'        => 'sometimes|required|exists:batiments,id',
            'agency_id'          => 'nullable|exists:agencies,id',
            'date_debut'         => 'sometimes|required|date',
            'date_fin'           => 'sometimes|required|date|after_or_equal:date_debut',
            'montant'            => 'nullable|numeric|min:0',
            'instructions'       => 'nullable|string',
            'content'            => 'nullable|string',
        ]);

        if ($user->employee && $user->employee->agency_id !== null) {
            $validated['agency_id'] = $user->employee->agency_id;
        }

        if ($engagement->statut !== 'valider') {
            $today = now()->format('Y-m-d');
            $dateDebut = $validated['date_debut'] ?? $engagement->date_debut;
            $dateFin = $validated['date_fin'] ?? $engagement->date_fin;

            $statut = 'actif';
            if ($dateDebut <= $today) {
                $statut = 'en cours';
            }
            if ($dateFin < $today) {
                $statut = 'expirer';
            }

            $validated['statut'] = $statut;
            $validated['statut_honneur'] = ($statut === 'expirer') ? 'non honner' : null;
        }

        $engagement->update($validated);

        return response()->json($engagement->fresh(['typeEngagement', 'locataire', 'contrat', 'batiment', 'agency']));
    }

    /**
     * Confirm that the engagement has been honored.
     */
    public function confirmHonor(Engagement $engagement)
    {
        $this->authorizeCompany($engagement);

        $engagement->update([
            'statut' => 'valider',
            'statut_honneur' => 'honner'
        ]);

        return response()->json($engagement->fresh(['typeEngagement', 'locataire', 'contrat', 'batiment', 'agency']));
    }

    /**
     * Delete the specified engagement.
     */
    public function destroy(Engagement $engagement)
    {
        $this->authorizeCompany($engagement);

        $user = Auth::user();
        if ($user->employee && $user->employee->agency_id !== null) {
            return response()->json(['message' => 'Action non autorisée. Seul le siège peut supprimer un engagement.'], 403);
        }

        $engagement->delete();

        return response()->json(['message' => 'Engagement supprimé avec succès.']);
    }

    /**
     * Authorize that the engagement belongs to the logged-in user's company.
     */
    private function authorizeCompany(Engagement $engagement): void
    {
        $user = Auth::user();
        abort_if(
            $engagement->company_profile_id !== $user->company_profile_id,
            403,
            'Accès non autorisé.'
        );
        if ($user->employee && $user->employee->agency_id !== null) {
            abort_if(
                $engagement->agency_id !== $user->employee->agency_id,
                403,
                'Accès non autorisé.'
            );
        }
    }
}
