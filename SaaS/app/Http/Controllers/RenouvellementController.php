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

        // TODO: Send emails to agency and company
        // Mail::to(...)->send(new RenouvellementCreated($renouvellement));

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
        $renouvellement->update(['statut' => 'A venir']);

        // TODO: Send email to locataire
        // Mail::to($renouvellement->locataire->email)->send(new RenouvellementApproved($renouvellement));

        return response()->json([
            'message' => 'Renouvellement approuvé. Le locataire a été notifié.',
            'renouvellement' => $renouvellement->load(['locataire', 'contrat', 'agency'])
        ]);
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

        // TODO: Send email to locataire
        // Mail::to($renouvellement->locataire->email)->send(new RenouvellementRejected($renouvellement));

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

        DB::beginTransaction();
        try {
            // Update the status
            $renouvellement->update(['statut' => 'Complete']);

            $contrat = $renouvellement->contrat;
            $affectation = Affectation::where('locataire_id', $renouvellement->locataire_id)
                ->where('logement_id', $contrat->logement_id)
                ->where('statut', 'Actif')
                ->first();

            if ($affectation) {
                // Determine new end date based on duration
                $newEndDate = $affectation->date_fin ? \Carbon\Carbon::parse($affectation->date_fin) : \Carbon\Carbon::now();
                $monthsToAdd = (int) filter_var($renouvellement->duree, FILTER_SANITIZE_NUMBER_INT);
                if ($monthsToAdd > 0) {
                    $newEndDate = $newEndDate->addMonths($monthsToAdd);
                }

                $affectation->update([
                    'loyer' => $renouvellement->nouveau_loyer,
                    'cycle_paiement' => $renouvellement->cycle_paiement,
                    'duree' => $renouvellement->duree,
                    'frais_de_contrat' => $renouvellement->frais_contrat,
                    'date_fin' => $newEndDate
                ]);
            }

            // Update logement rent
            $logement = Logement::find($contrat->logement_id);
            if ($logement) {
                $logement->update(['loyer' => $renouvellement->nouveau_loyer]);
            }

            // Insert frais_contrat if any
            if ($renouvellement->frais_contrat > 0) {
                FraisContrat::create([
                    'company_profile_id' => $renouvellement->company_profile_id,
                    'agency_id' => $renouvellement->agency_id,
                    'renouvellement_id' => $renouvellement->id,
                    'montant' => $renouvellement->frais_contrat,
                    'date_paiement' => now()
                ]);
            }

            DB::commit();

            // TODO: Send email to company
            // Mail::to($renouvellement->company->email)->send(new RenouvellementConfirmed($renouvellement));

            return response()->json([
                'message' => 'Renouvellement confirmé avec succès. Les informations ont été mises à jour.',
                'renouvellement' => $renouvellement->load(['locataire', 'contrat', 'agency'])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erreur lors de la confirmation.', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Renouvellement $renouvellement)
    {
        if ($renouvellement->statut !== 'Rejeté') {
            return response()->json(['message' => 'Seuls les renouvellements rejetés peuvent être supprimés.'], 403);
        }

        $user = auth()->user();
        if ($user->employee && $user->employee->agency_id !== null) {
            // TODO: Send email to company alerting deletion
            // Mail::to($renouvellement->company->email)->send(new RenouvellementDeleted($renouvellement));
        }

        $renouvellement->delete();
        return response()->json(['message' => 'Renouvellement supprimé.']);
    }
}
