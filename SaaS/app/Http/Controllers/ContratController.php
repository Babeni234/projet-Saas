<?php

namespace App\Http\Controllers;

use App\Models\Contrat;
use App\Models\Locataire;
use App\Models\Logement;
use App\Models\Affectation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContratActivatedMail;

class ContratController extends Controller
{
    /**
     * Liste des contrats.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $companyProfileId = $user->company_profile_id;

        $query = Contrat::where('company_profile_id', $companyProfileId)
                        ->where('deleted', false)
                        ->with(['locataire.user', 'logement.batiment', 'typeContrat']);

        // Filtrage agence si l'utilisateur est un agent
        $isAgent = $user->employee && $user->employee->agency_id !== null;
        if ($isAgent) {
            $query->where('agency_id', $user->employee->agency_id);
        }

        $contrats = $query->orderBy('created_at', 'desc')->get()->map(fn($c) => $this->formatContrat($c));

        return response()->json($contrats);
    }

    /**
     * Crée et active un contrat.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $companyProfileId = $user->company_profile_id;

        $request->validate([
            'locataire_id'    => 'required|integer|exists:locataires,id',
            'logement_id'     => 'required|integer|exists:logements,id',
            'type_contrat_id' => 'required|integer|exists:type_contrats,id',
            'loyer'           => 'required|numeric|min:0',
            'caution'         => 'required|numeric|min:0',
            'debut'           => 'required|date',
            'fin'             => 'required|date|after_or_equal:debut',
            'content'         => 'required|string',
            'numero'          => 'nullable|string|max:100',
        ]);

        $locataireId = $request->input('locataire_id');
        $logementId = $request->input('logement_id');

        // Check if locataire has an active contract already
        $hasActiveContract = Contrat::where('locataire_id', $locataireId)
                                    ->where('statut', 'Actif')
                                    ->where('deleted', false)
                                    ->exists();

        if ($hasActiveContract) {
            return response()->json([
                'error' => 'Ce locataire possède déjà un contrat de bail actif.'
            ], 422);
        }

        // Fetch locataire and logement to check rights
        $locataire = Locataire::where('id', $locataireId)
                              ->where('company_profile_id', $companyProfileId)
                              ->firstOrFail();

        $logement = Logement::where('id', $logementId)
                            ->where('company_profile_id', $companyProfileId)
                            ->firstOrFail();

        // Check if there is an active assignment for this locataire/logement
        $affectation = Affectation::where('locataire_id', $locataireId)
                                  ->where('logement_id', $logementId)
                                  ->where('statut', 'Actif')
                                  ->first();

        if (!$affectation) {
            // Fallback to check if there is an assignment "En cours d'exécution" (just in case)
            $affectation = Affectation::where('locataire_id', $locataireId)
                                      ->where('logement_id', $logementId)
                                      ->where('statut', "En cours d'exécution")
                                      ->first();
        }

        // Determine agency
        $agencyId = $logement->agency_id;
        $isAgent = $user->employee && $user->employee->agency_id !== null;
        if ($isAgent) {
            $agencyId = $user->employee->agency_id;
        }

        $contrat = DB::transaction(function () use ($request, $companyProfileId, $agencyId, $locataire, $logement, $affectation) {
            // 1. Create contract
            $c = Contrat::create([
                'company_profile_id' => $companyProfileId,
                'agency_id'          => $agencyId,
                'locataire_id'       => $locataire->id,
                'logement_id'        => $logement->id,
                'type_contrat_id'    => $request->input('type_contrat_id'),
                'loyer'              => $request->input('loyer'),
                'caution'            => $request->input('caution'),
                'debut'              => $request->input('debut'),
                'fin'                => $request->input('fin'),
                'content'            => $request->input('content'),
                'statut'             => 'Actif',
                'numero'             => $request->input('numero'),
            ]);

            // 2. Update affectation status to "En cours d'exécution"
            if ($affectation) {
                $affectation->update(['statut' => "En cours d'exécution"]);
            }

            // 3. Update locataire status to "Actif"
            $locataire->update(['statut' => 'actif']);
            $locataire->user->update(['status' => 'active']);

            // 4. Update logement status to "Occupé"
            $logement->update(['statut' => 'Occupé']);

            return $c;
        });

        $contrat->load(['locataire.user', 'logement.batiment', 'typeContrat']);

        // Envoyer le mail d'activation au locataire
        try {
            $duree = $affectation?->duree ?? '1 an';
            Mail::to($contrat->locataire->user->email)->send(new ContratActivatedMail($contrat, $duree));
        } catch (\Exception $e) {
            logger()->error("Erreur lors de l'envoi de mail d'activation de contrat : " . $e->getMessage());
        }

        return response()->json($this->formatContrat($contrat), 201);
    }

    /**
     * Modifie un contrat.
     */
    public function update(Request $request, Contrat $contrat)
    {
        $this->authorizeCompany($contrat);

        $request->validate([
            'loyer'   => 'sometimes|required|numeric|min:0',
            'caution' => 'sometimes|required|numeric|min:0',
            'debut'   => 'sometimes|required|date',
            'fin'     => 'sometimes|required|date|after_or_equal:debut',
            'content' => 'sometimes|required|string',
            'numero'  => 'sometimes|required|string|max:100',
            'statut'  => 'sometimes|required|string|in:Actif,Expiré,Résilié',
        ]);

        $contrat->update($request->all());

        return response()->json($this->formatContrat($contrat->fresh(['locataire.user', 'logement.batiment', 'typeContrat'])));
    }

    /**
     * Supprime (soft-delete) un contrat et remet à jour les statuts.
     */
    public function destroy(Contrat $contrat)
    {
        $this->authorizeCompany($contrat);

        DB::transaction(function () use ($contrat) {
            // Si le contrat était actif, on libère le logement et le locataire
            if ($contrat->statut === 'Actif') {
                if ($contrat->logement) {
                    $contrat->logement->update(['statut' => 'Libre']);
                }
                if ($contrat->locataire) {
                    $contrat->locataire->update(['statut' => 'inactif']);
                    $contrat->locataire->user->update(['status' => 'inactive']);
                }

                // Remettre l'affectation en statut 'Terminé' ou 'Actif' ?
                // Si on détruit le contrat, l'affectation repasse en 'Actif' (prête à être à nouveau contractée) ou est supprimée ?
                // Repasser en 'Actif' est logique pour recréer un contrat, sinon 'Terminé'. Mettons 'Actif'.
                $affectation = Affectation::where('locataire_id', $contrat->locataire_id)
                                          ->where('logement_id', $contrat->logement_id)
                                          ->where('statut', "En cours d'exécution")
                                          ->first();
                if ($affectation) {
                    $affectation->update(['statut' => 'Actif']);
                }
            }

            $contrat->update(['deleted' => true]);
            $contrat->delete();
        });

        return response()->json(['message' => 'Contrat supprimé avec succès.']);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function formatContrat(Contrat $c): array
    {
        // On récupère l'affectation correspondante pour obtenir la durée et le cycle de paiement
        $aff = Affectation::where('locataire_id', $c->locataire_id)
                          ->where('logement_id', $c->logement_id)
                          ->first();

        return [
            'id'               => $c->id,
            'uuid'             => $c->uuid,
            'numero'           => $c->numero,
            'locataire'        => $c->locataire?->user?->name ?? 'Locataire Supprimé',
            'locataire_id'     => $c->locataire_id,
            'logement_id'      => $c->logement_id,
            'type_contrat_id'  => $c->type_contrat_id,
            'type_contrat_nom' => $c->typeContrat?->nom ?? 'Standard',
            'loyer'            => (float)$c->loyer,
            'caution'          => (float)$c->caution,
            'debut'            => $c->debut?->toDateString(),
            'fin'              => $c->fin?->toDateString(),
            'statut'           => $c->statut,
            'reference'        => $c->logement?->reference ?? 'Logement Supprimé',
            'batiment'         => $c->logement?->batiment?->nom ?? 'Sans bâtiment',
            'duree'            => $aff?->duree ?? '1 an',
            'typeBail'         => $c->typeContrat?->nom ?? 'Habitation',
            'content'          => $c->content,
            'created_at'       => $c->created_at?->toDateString(),
        ];
    }

    private function authorizeCompany(Contrat $contrat): void
    {
        $user = Auth::user();
        abort_if(
            $contrat->company_profile_id !== $user->company_profile_id,
            403,
            'Accès non autorisé.'
        );
    }
}
