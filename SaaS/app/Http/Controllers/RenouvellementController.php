<?php

namespace App\Http\Controllers;

use App\Models\Renouvellement;
use App\Models\Contrat;
use App\Models\Affectation;
use App\Models\Logement;
use App\Models\FraisContrat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\RenouvellementCreated;
use App\Mail\RenouvellementApproved;
use App\Mail\RenouvellementRejected;
use App\Mail\RenouvellementConfirmed;
use App\Mail\RenouvellementDeleted;

class RenouvellementController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Renouvellement::with(['locataire', 'contrat', 'agency', 'company'])->orderBy('created_at', 'desc');

        if ($user->employee && $user->employee->agency_id !== null) {
            $query->where('agency_id', $user->employee->agency_id);
        } else {
            $query->where('company_profile_id', $user->company_profile_id);
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'locataire_id' => 'required|exists:locataires,id',
            'contrat_id' => 'required|exists:contrats,id',
            'nouveau_loyer' => 'required|numeric',
            'cycle_paiement' => 'required|string',
            'duree' => 'required|string',
            'frais_contrat' => 'nullable|numeric',
            'motif_demande' => 'nullable|string',
            'agency_id' => 'nullable|exists:agencies,id',
        ]);

        $allowMultiple = filter_var($request->input('allow_multiple'), FILTER_VALIDATE_BOOLEAN);
        if (!$allowMultiple) {
            $locataire = \App\Models\Locataire::find($validated['locataire_id']);
            if ($locataire && strtoupper($locataire->statut) === 'ACTIF') {
                $hasActive = Renouvellement::where('locataire_id', $locataire->id)
                    ->whereIn('statut', ['En attente', 'A venir', 'En cours'])
                    ->exists();
                if ($hasActive) {
                    return response()->json([
                        'message' => "Ce locataire a déjà une demande de renouvellement en cours ou en attente. Vous ne pouvez pas créer un autre renouvellement simultané sauf si vous cochez l'option de contournement."
                    ], 422);
                }
            }
        }

        $validated['company_profile_id'] = $user->company_profile_id;
        if ($user->employee && $user->employee->agency_id !== null) {
            $validated['agency_id'] = $user->employee->agency_id;
        } elseif (empty($validated['agency_id'])) {
            $contrat = Contrat::find($validated['contrat_id']);
            if ($contrat) {
                $validated['agency_id'] = $contrat->agency_id;
            }
        }
        $validated['statut'] = 'En attente';

        $renouvellement = Renouvellement::create($validated);

        // Envoyer les emails de création
        $companyEmail = $renouvellement->company && $renouvellement->company->user ? $renouvellement->company->user->email : null;
        $agencyEmail = $renouvellement->agency ? $renouvellement->agency->email : null;
        
        $this->sendMailSafe($companyEmail, new RenouvellementCreated($renouvellement));
        $this->sendMailSafe($agencyEmail, new RenouvellementCreated($renouvellement));

        return response()->json([
            'message' => 'Demande de renouvellement créée avec succès',
            'renouvellement' => $renouvellement->load(['locataire', 'contrat', 'agency'])
        ], 201);
    }

    public function update(Request $request, Renouvellement $renouvellement)
    {
        $validated = $request->validate([
            'nouveau_loyer' => 'required|numeric',
            'cycle_paiement' => 'required|string',
            'duree' => 'required|string',
            'frais_contrat' => 'nullable|numeric',
            'motif_demande' => 'nullable|string',
        ]);

        $renouvellement->update($validated);

        return response()->json([
            'message' => 'Renouvellement mis à jour avec succès',
            'renouvellement' => $renouvellement->load(['locataire', 'contrat', 'agency'])
        ]);
    }

    public function approuver(Request $request, Renouvellement $renouvellement)
    {
        DB::beginTransaction();
        try {
            $renouvellement->update(['statut' => 'A venir']);

            $this->applyRenewalUpdates($renouvellement);

            DB::commit();

            $tenantEmail = $renouvellement->locataire && $renouvellement->locataire->user ? $renouvellement->locataire->user->email : null;
            $this->sendMailSafe($tenantEmail, new RenouvellementApproved($renouvellement));

            return response()->json([
                'message' => 'Renouvellement approuvé. Le locataire a été notifié et les informations ont été mises à jour.',
                'renouvellement' => $renouvellement->load(['locataire', 'contrat', 'agency'])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erreur lors de l\'approbation.', 'error' => $e->getMessage()], 500);
        }
    }

    public function rejeter(Request $request, Renouvellement $renouvellement)
    {
        $validated = $request->validate([
            'motif_rejet' => 'required|string',
        ]);

        $renouvellement->update([
            'statut' => 'Rejeté',
            'motif_rejet' => $validated['motif_rejet']
        ]);

        $tenantEmail = $renouvellement->locataire && $renouvellement->locataire->user ? $renouvellement->locataire->user->email : null;
        $this->sendMailSafe($tenantEmail, new RenouvellementRejected($renouvellement));

        return response()->json([
            'message' => 'Renouvellement rejeté. Le locataire a été notifié.',
            'renouvellement' => $renouvellement->load(['locataire', 'contrat', 'agency'])
        ]);
    }

    public function confirmer(Request $request, Renouvellement $renouvellement)
    {
        if ($renouvellement->statut === 'Complete') {
            return response()->json(['message' => 'Ce renouvellement est déjà complété.'], 400);
        }

        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $renouvellement->update(['statut' => 'Complete']);

            $this->applyRenewalUpdates($renouvellement);

            $contrat = $renouvellement->contrat;
            if ($contrat) {
                $contrat->update([
                    'content' => $validated['content'],
                ]);
            }

            DB::commit();

            // Notifier la compagnie
            $companyEmail = $renouvellement->company && $renouvellement->company->user ? $renouvellement->company->user->email : null;
            $this->sendMailSafe($companyEmail, new RenouvellementConfirmed($renouvellement));

            return response()->json([
                'message' => 'Renouvellement confirmé avec succès. Les informations ont été mises à jour.',
                'renouvellement' => $renouvellement->load(['locataire', 'contrat', 'agency'])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erreur lors de la confirmation.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Applique les mises à jour de loyer, cycle, durée et frais de contrat sur les tables concernées.
     */
    private function applyRenewalUpdates(Renouvellement $renouvellement)
    {
        $contrat = $renouvellement->contrat;
        if (!$contrat) {
            return;
        }

        // Calculer la nouvelle date de fin basée sur la durée
        $newEndDate = $contrat->fin ? \Carbon\Carbon::parse($contrat->fin) : \Carbon\Carbon::now();
        $duree = strtolower($renouvellement->duree);
        $value = (int) filter_var($duree, FILTER_SANITIZE_NUMBER_INT);
        if ($value <= 0) {
            $value = 1;
        }

        if (str_contains($duree, 'an') || str_contains($duree, 'ans')) {
            $newEndDate = $newEndDate->addYears($value);
        } else {
            $newEndDate = $newEndDate->addMonths($value);
        }

        // Mettre à jour le Contrat existant
        $contrat->update([
            'fin' => $newEndDate,
            'loyer' => $renouvellement->nouveau_loyer,
        ]);

        // Mettre à jour l'Affectation active
        $affectation = Affectation::where('locataire_id', $renouvellement->locataire_id)
            ->where('logement_id', $contrat->logement_id)
            ->where('statut', 'Actif')
            ->first();

        if ($affectation) {
            $affectation->update([
                'loyer' => $renouvellement->nouveau_loyer,
                'cycle_paiement' => $renouvellement->cycle_paiement,
                'duree' => $renouvellement->duree,
                'frais_de_contrat' => $renouvellement->frais_contrat,
                'date_fin' => $newEndDate
            ]);
        }

        // Mettre à jour le loyer du logement
        $logement = Logement::find($contrat->logement_id);
        if ($logement) {
            $logement->update(['loyer' => $renouvellement->nouveau_loyer]);
        }

        // Enregistrer ou mettre à jour dans frais_contrats
        if ($renouvellement->frais_contrat > 0) {
            FraisContrat::updateOrCreate(
                ['renouvellement_id' => $renouvellement->id],
                [
                    'company_profile_id' => $renouvellement->company_profile_id,
                    'agency_id' => $renouvellement->agency_id,
                    'montant' => $renouvellement->frais_contrat,
                    'date_paiement' => now()
                ]
            );
        }
    }

    public function destroy(Renouvellement $renouvellement)
    {
        if ($renouvellement->statut !== 'Rejeté') {
            return response()->json(['message' => 'Seuls les renouvellements rejetés peuvent être supprimés.'], 403);
        }

        $user = auth()->user();
        if ($user->employee && $user->employee->agency_id !== null) {
            $companyEmail = $renouvellement->company && $renouvellement->company->user ? $renouvellement->company->user->email : null;
            $this->sendMailSafe($companyEmail, new RenouvellementDeleted($renouvellement));
        }

        $renouvellement->delete();
        return response()->json(['message' => 'Renouvellement supprimé.']);
    }

    /**
     * Envoie un email de manière sécurisée en capturant les exceptions.
     */
    private function sendMailSafe($to, $mailable)
    {
        if (empty($to)) return;
        try {
            Mail::to($to)->send($mailable);
        } catch (\Exception $e) {
            \Log::error("Erreur d'envoi d'email à {$to} : " . $e->getMessage());
        }
    }
}
