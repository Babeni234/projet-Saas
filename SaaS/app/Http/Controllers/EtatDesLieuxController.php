<?php

namespace App\Http\Controllers;

use App\Models\EtatDesLieux;
use App\Models\TypeEtatDesLieux;
use App\Models\Agency;
use App\Models\Batiment;
use App\Models\Locataire;
use App\Models\Contrat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EtatDesLieuxController extends Controller
{
    /**
     * Display a listing of inspections.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $companyProfileId = $user->company_profile_id;

        // 1. Fetch inspections (États des lieux)
        $query = EtatDesLieux::with(['typeEtatDesLieux', 'locataire.user', 'contrat', 'logement', 'batiment', 'agency'])
            ->where('company_profile_id', $companyProfileId);

        // Agency scope
        if ($user->employee && $user->employee->agency_id !== null) {
            $query->where('agency_id', $user->employee->agency_id);
        }

        $etats = $query->orderBy('created_at', 'desc')->get();

        // 2. Fetch related lists for cascade and dropdowns
        $types = TypeEtatDesLieux::where('company_profile_id', $companyProfileId)->orderBy('nom')->get();

        $agenciesQuery = Agency::where('company_profile_id', $companyProfileId);
        $batimentsQuery = Batiment::where('company_profile_id', $companyProfileId);

        if ($user->employee && $user->employee->agency_id !== null) {
            $agenciesQuery->where('id', $user->employee->agency_id);
            $batimentsQuery->where('agency_id', $user->employee->agency_id);
        }

        $agencies = $agenciesQuery->get();
        $batiments = $batimentsQuery->get();

        // 3. Fetch locataires having either an active contract, or a terminated/expired contract and no active contracts
        $locatairesQuery = Locataire::with(['user', 'contrats' => function($q) use ($user, $companyProfileId) {
            $q->where('company_profile_id', $companyProfileId);
            if ($user->employee && $user->employee->agency_id !== null) {
                $q->where('agency_id', $user->employee->agency_id);
            }
            $q->with('logement');
        }])
        ->where('company_profile_id', $companyProfileId)
        ->where(function ($q) {
            $q->whereHas('contrats', function ($sub) {
                $sub->where('statut', 'Actif');
            })
            ->orWhere(function ($sub) {
                $sub->whereHas('contrats', function ($sub2) {
                    $sub2->whereIn('statut', ['Terminé', 'Expiré']);
                })
                ->whereDoesntHave('contrats', function ($sub2) {
                    $sub2->where('statut', 'Actif');
                });
            });
        });

        if ($user->employee && $user->employee->agency_id !== null) {
            $locatairesQuery->where('agency_id', $user->employee->agency_id);
        }

        $locataires = $locatairesQuery->get();

        return response()->json([
            'etats' => $etats,
            'typeEtatDesLieux' => $types,
            'agencies' => $agencies,
            'batiments' => $batiments,
            'locataires' => $locataires,
        ]);
    }

    /**
     * Store a newly created inspection.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $companyProfileId = $user->company_profile_id;

        $validated = $request->validate([
            'reference'              => 'required|string|max:255',
            'type_etat_des_lieux_id' => 'required|exists:type_etat_des_lieux,id',
            'locataire_id'           => 'required|exists:locataires,id',
            'contrat_id'             => 'required|exists:contrats,id',
            'logement_id'            => 'required|exists:logements,id',
            'batiment_id'            => 'nullable|exists:batiments,id',
            'agency_id'              => 'nullable|exists:agencies,id',
            'date'                   => 'required|date',
            'statut'                 => 'required|string|in:Brouillon,En attente,Validé',
            'instructions'           => 'nullable|string',
            'content'                => 'nullable|string',
        ]);

        if ($user->employee && $user->employee->agency_id !== null) {
            $validated['agency_id'] = $user->employee->agency_id;
        }

        // Auto-fill batiment and agency from logement if empty
        if (empty($validated['batiment_id']) && !empty($validated['logement_id'])) {
            $logement = \App\Models\Logement::find($validated['logement_id']);
            if ($logement) {
                $validated['batiment_id'] = $logement->batiment_id;
                if (empty($validated['agency_id'])) {
                    $validated['agency_id'] = $logement->agency_id;
                }
            }
        }

        $etat = EtatDesLieux::create([
            ...$validated,
            'company_profile_id' => $companyProfileId,
        ]);

        return response()->json($etat->load(['typeEtatDesLieux', 'locataire.user', 'contrat', 'logement', 'batiment', 'agency']), 201);
    }

    /**
     * Update the specified inspection.
     */
    public function update(Request $request, EtatDesLieux $etatDesLieux)
    {
        $user = Auth::user();
        $this->authorizeCompany($etatDesLieux);

        $validated = $request->validate([
            'reference'              => 'sometimes|required|string|max:255',
            'type_etat_des_lieux_id' => 'sometimes|required|exists:type_etat_des_lieux,id',
            'locataire_id'           => 'sometimes|required|exists:locataires,id',
            'contrat_id'             => 'sometimes|required|exists:contrats,id',
            'logement_id'            => 'sometimes|required|exists:logements,id',
            'batiment_id'            => 'nullable|exists:batiments,id',
            'agency_id'              => 'nullable|exists:agencies,id',
            'date'                   => 'sometimes|required|date',
            'statut'                 => 'sometimes|required|string|in:Brouillon,En attente,Validé',
            'instructions'           => 'nullable|string',
            'content'                => 'nullable|string',
        ]);

        if ($user->employee && $user->employee->agency_id !== null) {
            $validated['agency_id'] = $user->employee->agency_id;
        }

        // Auto-fill batiment and agency from logement if empty
        if (empty($validated['batiment_id']) && !empty($validated['logement_id'])) {
            $logement = \App\Models\Logement::find($validated['logement_id']);
            if ($logement) {
                $validated['batiment_id'] = $logement->batiment_id;
                if (empty($validated['agency_id'])) {
                    $validated['agency_id'] = $logement->agency_id;
                }
            }
        }

        $etatDesLieux->update($validated);

        return response()->json($etatDesLieux->fresh(['typeEtatDesLieux', 'locataire.user', 'contrat', 'logement', 'batiment', 'agency']));
    }

    /**
     * Delete the specified inspection.
     */
    public function destroy(EtatDesLieux $etatDesLieux)
    {
        $this->authorizeCompany($etatDesLieux);

        $user = Auth::user();
        // Restrict delete to seat/enterprise only
        if ($user->employee && $user->employee->agency_id !== null) {
            return response()->json(['message' => 'Action non autorisée. Seul le siège peut supprimer un état des lieux.'], 403);
        }

        $etatDesLieux->delete();

        return response()->json(['message' => 'État des lieux supprimé avec succès.']);
    }

    /**
     * Authorize that the inspection belongs to the logged-in user's company.
     */
    private function authorizeCompany(EtatDesLieux $etatDesLieux): void
    {
        $user = Auth::user();
        abort_if(
            $etatDesLieux->company_profile_id !== $user->company_profile_id,
            403,
            'Accès non autorisé.'
        );
        if ($user->employee && $user->employee->agency_id !== null) {
            abort_if(
                $etatDesLieux->agency_id !== $user->employee->agency_id,
                403,
                'Accès non autorisé.'
            );
        }
    }
}
