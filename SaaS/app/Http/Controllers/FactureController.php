<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\Contrat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FactureController extends Controller
{
    /**
     * Display a listing of the invoices.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $companyProfileId = $user->company_profile_id;

        $query = Facture::where('company_profile_id', $companyProfileId)
            ->where('deleted', false)
            ->with(['locataire.user', 'contrat.logement.batiment', 'typeFacture', 'agency', 'company']);

        // Scope by agency if the user is an agency employee
        $isAgent = $user->employee && $user->employee->agency_id !== null;
        if ($isAgent) {
            $query->where('agency_id', $user->employee->agency_id);
        }

        $factures = $query->orderBy('created_at', 'desc')->get()->map(fn($f) => $this->formatFacture($f));

        return response()->json($factures);
    }

    /**
     * Store a newly created invoice in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $companyProfileId = $user->company_profile_id;

        $request->validate([
            'locataire_id'    => 'required|integer|exists:locataires,id',
            'contrat_id'      => 'required|integer|exists:contrats,id',
            'type_facture_id' => 'required|integer|exists:type_factures,id',
            'periode'         => 'nullable|string',
            'date_emission'   => 'required|date',
            'date_echeance'   => 'required|date|after_or_equal:date_emission',
            'items'           => 'required|array',
            'total'           => 'required|numeric|min:0',
        ]);

        $contrat = Contrat::where('id', $request->input('contrat_id'))
            ->where('company_profile_id', $companyProfileId)
            ->firstOrFail();

        // Determine agency ID from the contract, otherwise fallback to agent user agency
        $agencyId = $contrat->agency_id;
        $isAgent = $user->employee && $user->employee->agency_id !== null;
        if ($isAgent) {
            $agencyId = $user->employee->agency_id;
        }

        $facture = Facture::create([
            'company_profile_id' => $companyProfileId,
            'agency_id'          => $agencyId,
            'locataire_id'       => $request->input('locataire_id'),
            'contrat_id'         => $request->input('contrat_id'),
            'type_facture_id'    => $request->input('type_facture_id'),
            'periode'            => $request->input('periode'),
            'date_emission'      => $request->input('date_emission'),
            'date_echeance'      => $request->input('date_echeance'),
            'items'              => $request->input('items'),
            'total'              => $request->input('total'),
            'montant_paye'       => 0.00,
            'statut'             => 'Impayé',
        ]);

        $facture->load(['locataire.user', 'contrat.logement.batiment', 'typeFacture', 'agency', 'company']);

        return response()->json($this->formatFacture($facture), 201);
    }

    /**
     * Settle / pay an invoice.
     */
    public function regler(Request $request, Facture $facture)
    {
        $this->authorizeCompany($facture);

        $request->validate([
            'mode_reglement' => 'required|string|in:cash,wallet',
        ]);

        $mode = strtolower($request->input('mode_reglement'));
        if ($mode === 'wallet') {
            $locataire = $facture->locataire;
            if (!$locataire) {
                return response()->json(['message' => 'Profil locataire introuvable.'], 404);
            }
            $wallet = $locataire->wallet;
            if (!$wallet) {
                return response()->json(['message' => 'Ce locataire ne possède pas de portefeuille électronique actif.'], 422);
            }
            if ($wallet->solde < $facture->total) {
                return response()->json(['message' => 'Le solde du portefeuille électronique du locataire est insuffisant (Requis : ' . number_format($facture->total, 2, ',', ' ') . ' €).'], 422);
            }

            $token = \Illuminate\Support\Str::random(40);
            $pending = \App\Models\PendingWalletPayment::create([
                'company_profile_id' => $facture->company_profile_id,
                'agency_id'          => $facture->agency_id,
                'locataire_id'       => $locataire->id,
                'type'               => 'facture',
                'target_id'          => $facture->id,
                'amount'             => $facture->total,
                'token'              => $token,
                'data'               => array_merge($request->all(), ['invoice_num' => $facture->numero]),
                'status'             => 'pending',
            ]);

            if ($locataire->user && $locataire->user->email) {
                try {
                    \Illuminate\Support\Facades\Mail::to($locataire->user->email)->send(new \App\Mail\WalletPaymentValidationMail($pending, $locataire));
                } catch (\Exception $e) {
                    logger()->error("Mail error validation wallet payment: " . $e->getMessage());
                }
            }

            return response()->json([
                'message' => 'Une demande d\'autorisation a été envoyée par e-mail au locataire. Le paiement sera enregistré dès qu\'il l\'aura autorisé avec son code secret.',
                'pending' => true
            ], 200);
        }

        $facture->update([
            'statut'         => 'Payé',
            'montant_paye'   => $facture->total,
            'mode_reglement' => $request->input('mode_reglement'),
        ]);

        // Sync to Tresorerie
        $facture->load(['locataire.user']);
        $locName = $facture->locataire?->user?->name ?? 'Inconnu';
        \App\Models\Tresorerie::enregistrer(
            $facture,
            (float) $facture->total,
            "Règlement de la facture {$facture->numero} par {$locName}",
            now()->toDateString()
        );

        return response()->json($this->formatFacture($facture->fresh(['locataire.user', 'contrat.logement.batiment', 'typeFacture', 'agency', 'company'])));
    }

    /**
     * Remove the specified invoice from storage (soft-delete).
     */
    public function destroy(Facture $facture)
    {
        $this->authorizeCompany($facture);

        $user = Auth::user();
        $isAgent = $user->employee && $user->employee->agency_id !== null;
        abort_if($isAgent, 403, 'Les agences n\'ont pas le droit de supprimer une facture.');

        $facture->update(['deleted' => true]);
        $facture->delete();

        // Sync to Tresorerie
        \App\Models\Tresorerie::where('source_type', Facture::class)
            ->where('source_id', $facture->id)
            ->update(['deleted' => true]);

        \App\Models\Tresorerie::where('source_type', Facture::class)
            ->where('source_id', $facture->id)
            ->delete();

        return response()->json(['message' => 'Facture supprimée avec succès.']);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function formatFacture(Facture $f): array
    {
        return [
            'id'               => $f->id,
            'uuid'             => $f->uuid,
            'numero'           => $f->numero,
            'company_name'     => $f->company?->legal_name ?? 'Siège Social',
            'agency_id'        => $f->agency_id,
            'agency_name'      => $f->agency?->name ?? 'Siège Social',
            'locataire'        => $f->locataire?->user?->name ?? 'Locataire Supprimé',
            'locataire_id'     => $f->locataire_id,
            'logement'         => $f->contrat?->logement?->reference ?? 'Logement Supprimé',
            'batiment'         => $f->contrat?->logement?->batiment?->nom ?? 'Sans bâtiment',
            'contrat_id'       => $f->contrat_id,
            'contrat_numero'   => $f->contrat?->numero ?? 'N/A',
            'type_facture_id'  => $f->type_facture_id,
            'type_facture_nom' => $f->typeFacture?->nom ?? 'Autre',
            'periode'          => $f->periode,
            'dateEmission'     => $f->date_emission?->toDateString(),
            'dateEcheance'     => $f->date_echeance?->toDateString(),
            'items'            => $f->items,
            'total'            => (float)$f->total,
            'montantPaye'      => (float)$f->montant_paye,
            'statut'           => $f->statut,
            'modeReglement'    => $f->mode_reglement,
            'created_at'       => $f->created_at?->toDateString(),
        ];
    }

    private function authorizeCompany(Facture $facture): void
    {
        $user = Auth::user();
        abort_if(
            $facture->company_profile_id !== $user->company_profile_id,
            403,
            'Accès non autorisé.'
        );
    }
}
