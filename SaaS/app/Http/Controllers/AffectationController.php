<?php

namespace App\Http\Controllers;

use App\Models\Affectation;
use App\Models\Locataire;
use App\Models\Logement;
use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AffectationController extends Controller
{
    /**
     * Liste des affectations.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $companyProfileId = $user->company_profile_id;

        $query = Affectation::where('company_profile_id', $companyProfileId)
                            ->where('deleted', false)
                            ->with(['locataire.user', 'logement.batiment']);

        // Filtrage par agence si l'utilisateur connecté est un agent
        $isAgent = $user->employee && $user->employee->agency_id !== null;
        if ($isAgent) {
            $query->where('agency_id', $user->employee->agency_id);
        }

        $affectations = $query->orderBy('created_at', 'desc')->get()->map(fn($a) => $this->formatAffectation($a));

        return response()->json($affectations);
    }

    /**
     * Crée une nouvelle affectation (bail).
     */
    public function store(Request $request)
    {
        $currentUser = Auth::user();
        $companyProfileId = $currentUser->company_profile_id;

        $request->validate([
            'locataire_id'   => 'required|integer|exists:locataires,id',
            'logement_id'    => 'required|integer|exists:logements,id',
            'loyer'          => 'required|numeric|min:0',
            'caution'        => 'required|numeric|min:0',
            'date_debut'     => 'required|date',
            'date_fin'       => 'nullable|date|after_or_equal:date_debut',
            'type_bail'      => 'required|string|max:100',
            'duree'          => 'required|string|max:100',
            'cycle_paiement' => 'required|string|max:100',
        ]);

        $locataire = Locataire::where('id', $request->input('locataire_id'))
                              ->where('company_profile_id', $companyProfileId)
                              ->firstOrFail();

        $logement = Logement::where('id', $request->input('logement_id'))
                            ->where('company_profile_id', $companyProfileId)
                            ->firstOrFail();

        // Si le logement n'est pas libre, renvoyer une erreur
        if ($logement->statut !== 'Libre') {
            return response()->json(['error' => 'Le logement sélectionné n\'est pas vacant.'], 422);
        }

        // L'agence associée à l'affectation est celle du logement
        $agencyId = $logement->agency_id;
        $isAgent = $currentUser->employee && $currentUser->employee->agency_id !== null;
        if ($isAgent) {
            $agencyId = $currentUser->employee->agency_id;
        }

        $affectation = DB::transaction(function () use ($request, $companyProfileId, $agencyId, $locataire, $logement) {
            // 1. Créer l'affectation
            $aff = Affectation::create([
                'company_profile_id' => $companyProfileId,
                'agency_id'          => $agencyId,
                'locataire_id'       => $locataire->id,
                'logement_id'        => $logement->id,
                'loyer'              => $request->input('loyer'),
                'caution'            => $request->input('caution'),
                'date_debut'         => $request->input('date_debut'),
                'date_fin'           => $request->input('date_fin'),
                'type_bail'          => $request->input('type_bail'),
                'duree'              => $request->input('duree'),
                'cycle_paiement'     => $request->input('cycle_paiement'),
                'statut'             => 'Actif',
            ]);

            // 2. Mettre à jour le statut du logement à 'Réservé'
            $logement->update(['statut' => 'Réservé']);

            // 3. Mettre à jour le statut du locataire à 'affecté'
            $locataire->update(['statut' => 'affecté']);
            $locataire->user->update(['status' => 'active']);

            return $aff;
        });

        $affectation->load(['locataire.user', 'logement.batiment']);

        return response()->json($this->formatAffectation($affectation), 201);
    }

    /**
     * Termine un bail (résiliation).
     */
    public function terminate(Request $request, Affectation $affectation)
    {
        $this->authorizeCompany($affectation);

        if ($affectation->statut === 'Terminé') {
            return response()->json(['error' => 'Cette affectation est déjà terminée.'], 422);
        }

        DB::transaction(function () use ($affectation) {
            $affectation->update([
                'statut'   => 'Terminé',
                'date_fin' => now()->toDateString(),
            ]);

            // Remettre le logement en statut 'Libre'
            if ($affectation->logement) {
                $affectation->logement->update(['statut' => 'Libre']);
            }

            // Remettre le locataire en statut 'inactif' (ou 'actif' si disponible pour réaffectation)
            if ($affectation->locataire) {
                $affectation->locataire->update(['statut' => 'inactif']);
                $affectation->locataire->user->update(['status' => 'inactive']);
            }
        });

        $affectation->load(['locataire.user', 'logement.batiment']);

        return response()->json($this->formatAffectation($affectation));
    }

    /**
     * Supprime (soft-delete) une affectation.
     */
    public function destroy(Affectation $affectation)
    {
        $this->authorizeCompany($affectation);

        DB::transaction(function () use ($affectation) {
            // Si l'affectation était active, libérer les ressources
            if ($affectation->statut === 'Actif') {
                if ($affectation->logement) {
                    $affectation->logement->update(['statut' => 'Libre']);
                }
                if ($affectation->locataire) {
                    $affectation->locataire->update(['statut' => 'inactif']);
                    $affectation->locataire->user->update(['status' => 'inactive']);
                }
            }

            $affectation->update(['deleted' => true]);
            $affectation->delete();
        });

        return response()->json(['message' => 'Affectation supprimée avec succès.']);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function formatAffectation(Affectation $a): array
    {
        return [
            'id'             => $a->id,
            'uuid'           => $a->uuid,
            'reference'      => $a->logement?->reference ?? 'Bien Supprimé',
            'batiment'       => $a->logement?->batiment?->nom ?? 'Sans bâtiment',
            'locataire'      => $a->locataire?->user?->name ?? 'Locataire Supprimé',
            'locataire_id'   => $a->locataire_id,
            'logement_id'    => $a->logement_id,
            'loyer'          => (float)$a->loyer,
            'caution'        => (float)$a->caution,
            'dateEffet'      => $a->date_debut?->toDateString(),
            'dateFin'        => $a->date_fin?->toDateString(),
            'duree'          => $a->duree,
            'typeBail'       => $a->type_bail,
            'cyclePaiement'  => $a->cycle_paiement,
            'statut'         => $a->statut, // Actif, Terminé
            'created_at'     => $a->created_at?->toDateString(),
        ];
    }

    private function authorizeCompany(Affectation $affectation): void
    {
        $user = Auth::user();
        abort_if(
            $affectation->company_profile_id !== $user->company_profile_id,
            403,
            'Accès non autorisé.'
        );
    }
}
