<?php

namespace App\Http\Controllers;

use App\Models\Locataire;
use App\Models\Wallet;
use App\Models\TransacWallet;
use App\Models\Renouvellement;
use App\Models\Contrat;
use App\Models\Affectation;
use App\Models\FraisContrat;
use App\Models\Tresorerie;
use App\Models\Facture;
use App\Models\PaiementLoyer;
use App\Models\MoisPaye;
use App\Mail\WalletCreatedForTenantMail;
use App\Mail\WalletCreatedNotificationMail;
use App\Mail\RenouvellementCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class LocataireWalletController extends Controller
{
    /**
     * Crée le wallet du locataire connecté.
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $locataire = $user->locataire;

        if (!$locataire) {
            return response()->json(['message' => 'Profil locataire introuvable.'], 403);
        }

        if ($locataire->wallet) {
            return response()->json(['message' => 'Vous possédez déjà un portefeuille électronique.'], 422);
        }

        $request->validate([
            'pin' => 'required|string|size:4|regex:/^[0-9]+$/',
        ]);

        $wallet = DB::transaction(function () use ($locataire, $request) {
            return Wallet::create([
                'company_profile_id' => $locataire->company_profile_id,
                'agency_id'          => $locataire->agency_id,
                'locataire_id'       => $locataire->id,
                'solde'              => 0.00,
                'password'           => Hash::make($request->input('pin')),
            ]);
        });

        // Envoyer les emails de notification
        $companyEmail = $locataire->company?->user?->email;
        $agencyEmail = $locataire->agency?->email;

        if ($companyEmail) {
            try {
                Mail::to($companyEmail)->send(new WalletCreatedNotificationMail($wallet));
            } catch (\Exception $e) {
                logger()->error("Mail error to company: " . $e->getMessage());
            }
        }

        if ($agencyEmail) {
            try {
                Mail::to($agencyEmail)->send(new WalletCreatedNotificationMail($wallet));
            } catch (\Exception $e) {
                logger()->error("Mail error to agency: " . $e->getMessage());
            }
        }

        return response()->json([
            'message' => 'Votre portefeuille électronique a été créé avec succès.',
            'wallet' => [
                'id' => $wallet->id,
                'solde' => (float) $wallet->solde,
            ]
        ], 201);
    }

    /**
     * Modifie le code PIN du wallet.
     */
    public function changePin(Request $request)
    {
        $user = Auth::user();
        $locataire = $user->locataire;

        if (!$locataire || !$locataire->wallet) {
            return response()->json(['message' => 'Portefeuille introuvable.'], 403);
        }

        $request->validate([
            'current_pin' => 'required|string',
            'new_pin'     => 'required|string|size:4|regex:/^[0-9]+$/',
        ]);

        $wallet = $locataire->wallet;

        if (!Hash::check($request->input('current_pin'), $wallet->password)) {
            return response()->json(['message' => 'Le code secret actuel est incorrect.'], 422);
        }

        $wallet->update([
            'password' => Hash::make($request->input('new_pin')),
        ]);

        return response()->json(['message' => 'Code secret du portefeuille modifié avec succès.']);
    }

    /**
     * Simule une recharge de fonds dans le wallet.
     */
    public function recharge(Request $request)
    {
        $user = Auth::user();
        $locataire = $user->locataire;

        if (!$locataire || !$locataire->wallet) {
            return response()->json(['message' => 'Portefeuille introuvable.'], 403);
        }

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'method' => 'required|string|in:Orange Money,MTN MoMo,Carte Bancaire,PayPal',
        ]);

        $wallet = $locataire->wallet;
        $amount = (float) $request->input('amount');
        $method = $request->input('method');

        DB::transaction(function () use ($wallet, $amount, $method) {
            $wallet->increment('solde', $amount);

            TransacWallet::create([
                'wallet_id'    => $wallet->id,
                'type'         => 'recharge',
                'amount'       => $amount,
                'description'  => 'Crédit portefeuille via ' . $method,
                'reference_tx' => 'RCG-' . strtoupper(Str::random(10)),
            ]);
        });

        return response()->json([
            'message' => 'Recharge effectuée avec succès.',
            'solde' => (float) $wallet->fresh()->solde,
        ]);
    }

    /**
     * Règle un ou plusieurs mois de loyer.
     */
    public function payRent(Request $request)
    {
        $user = Auth::user();
        $locataire = $user->locataire;

        if (!$locataire || !$locataire->wallet) {
            return response()->json(['message' => 'Portefeuille introuvable.'], 403);
        }

        $request->validate([
            'months' => 'required|array|min:1',
            'months.*.key' => 'required|string',
            'months.*.amount' => 'required|numeric',
            'months.*.penaltyAmount' => 'required|numeric',
            'months.*.totalDue' => 'required|numeric',
            'pin' => 'required|string',
        ]);

        $wallet = $locataire->wallet;

        if (!Hash::check($request->input('pin'), $wallet->password)) {
            return response()->json(['message' => 'Code secret du portefeuille incorrect.'], 422);
        }

        $months = $request->input('months');
        $totalAmount = array_reduce($months, fn($sum, $m) => $sum + (float) $m['totalDue'], 0.0);

        if ($wallet->solde < $totalAmount) {
            return response()->json(['message' => 'Solde insuffisant dans votre portefeuille.'], 422);
        }

        // Trouver le contrat actif
        $contrat = $locataire->contrats()
            ->where('deleted', false)
            ->whereIn('statut', ['actif', 'Actif', 'en_cours', 'signé', 'signe'])
            ->first();

        if (!$contrat) {
            return response()->json(['message' => 'Aucun contrat de bail actif trouvé.'], 422);
        }

        DB::beginTransaction();
        try {
            // Débiter le wallet
            $wallet->decrement('solde', $totalAmount);

            // Créer la transaction wallet
            $tx = TransacWallet::create([
                'wallet_id'    => $wallet->id,
                'type'         => 'debit',
                'amount'       => $totalAmount,
                'description'  => 'Paiement loyer(s) : ' . implode(', ', array_map(fn($m) => $m['key'], $months)),
                'reference_tx' => 'RENT-' . strtoupper(Str::random(10)),
            ]);

            // Créer le PaiementLoyer
            $paiement = PaiementLoyer::create([
                'company_profile_id' => $locataire->company_profile_id,
                'agency_id'          => $locataire->agency_id,
                'locataire_id'       => $locataire->id,
                'contrat_id'         => $contrat->id,
                'date_reglement'     => now(),
                'montant_total'      => $totalAmount,
                'mode_reglement'     => 'Wallet',
                'reference_tx'       => $tx->reference_tx,
            ]);

            // Créer les MoisPayes et solder les factures correspondantes
            foreach ($months as $m) {
                MoisPaye::create([
                    'paiement_loyer_id' => $paiement->id,
                    'periode'           => $m['key'],
                    'loyer_de_base'     => (float) $m['amount'],
                    'penalite'          => (float) $m['penaltyAmount'],
                    'total_paye'        => (float) $m['totalDue'],
                ]);

                // Trouver et solder la facture loyer correspondante
                // On essaie de faire correspondre avec la période
                Facture::where('locataire_id', $locataire->id)
                    ->where('type_facture_id', function($query) {
                        $query->select('id')->from('type_factures')->where('nom', 'Loyer')->limit(1);
                    })
                    ->where('periode', 'like', '%' . $m['key'] . '%')
                    ->where(function($q) {
                        $q->where('statut', '!=', 'payée')
                          ->where('statut', '!=', 'Payé');
                    })
                    ->update([
                        'statut'         => 'Payé',
                        'montant_paye'   => DB::raw('total'),
                        'mode_reglement' => 'wallet',
                    ]);
            }

            // Enregistrer dans la trésorerie globale
            Tresorerie::enregistrer(
                $paiement,
                (float) $paiement->montant_total,
                "Règlement loyer pour {$locataire->nom} (Réf #{$paiement->reference})",
                now()->toDateString()
            );

            DB::commit();

            // Send email to agency (or company if none)
            $agencyEmail = $locataire->agency?->email;
            $companyEmail = $locataire->company?->user?->email;
            $recipient = $agencyEmail ?? $companyEmail;
            
            if ($recipient) {
                try {
                    Mail::to($recipient)->send(new \App\Mail\RentPaidNotificationMail($paiement, $locataire, $months));
                } catch (\Exception $mailEx) {
                    logger()->error("Erreur d'envoi de mail de notification de paiement loyer : " . $mailEx->getMessage());
                }
            }

            return response()->json([
                'message' => 'Paiement effectué avec succès !',
                'solde' => (float) $wallet->fresh()->solde,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erreur lors du paiement du loyer.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Règle une facture d'eau ou d'électricité.
     */
    public function payUtility(Request $request)
    {
        $user = Auth::user();
        $locataire = $user->locataire;

        if (!$locataire || !$locataire->wallet) {
            return response()->json(['message' => 'Portefeuille introuvable.'], 403);
        }

        $request->validate([
            'invoice_id' => 'required|exists:factures,id',
            'pin'        => 'required|string',
        ]);

        $wallet = $locataire->wallet;

        if (!Hash::check($request->input('pin'), $wallet->password)) {
            return response()->json(['message' => 'Code secret du portefeuille incorrect.'], 422);
        }

        $invoice = Facture::where('id', $request->input('invoice_id'))
            ->where('locataire_id', $locataire->id)
            ->firstOrFail();

        if (in_array($invoice->statut, ['payée', 'Payé'])) {
            return response()->json(['message' => 'Cette facture est déjà réglée.'], 422);
        }

        if ($wallet->solde < $invoice->total) {
            return response()->json(['message' => 'Solde insuffisant dans votre portefeuille.'], 422);
        }

        DB::beginTransaction();
        try {
            // Débiter le wallet
            $wallet->decrement('solde', $invoice->total);

            // Créer la transaction wallet
            $tx = TransacWallet::create([
                'wallet_id'    => $wallet->id,
                'type'         => 'debit',
                'amount'       => $invoice->total,
                'description'  => "Règlement Facture {$invoice->numero} ({$invoice->typeFacture?->nom})",
                'reference_tx' => 'UTL-' . strtoupper(Str::random(10)),
            ]);

            // Mettre à jour la facture
            $invoice->update([
                'statut'         => 'Payé',
                'montant_paye'   => $invoice->total,
                'mode_reglement' => 'wallet',
            ]);

            // Enregistrer dans la trésorerie globale
            Tresorerie::enregistrer(
                $invoice,
                (float) $invoice->total,
                "Règlement facture {$invoice->numero} pour {$locataire->nom}",
                now()->toDateString()
            );

            DB::commit();

            // Send email to agency (or company if none)
            $agencyEmail = $locataire->agency?->email;
            $companyEmail = $locataire->company?->user?->email;
            $recipient = $agencyEmail ?? $companyEmail;
            
            if ($recipient) {
                try {
                    Mail::to($recipient)->send(new \App\Mail\InvoicePaidNotificationMail($invoice, $locataire));
                } catch (\Exception $mailEx) {
                    logger()->error("Erreur d'envoi de mail de notification de paiement facture : " . $mailEx->getMessage());
                }
            }

            return response()->json([
                'message' => 'Facture réglée avec succès !',
                'solde' => (float) $wallet->fresh()->solde,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erreur lors du paiement de la facture.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Payer les frais de contrat et soumettre la demande de renouvellement.
     */
    public function payContractFee(Request $request)
    {
        $user = Auth::user();
        $locataire = $user->locataire;

        if (!$locataire || !$locataire->wallet) {
            return response()->json(['message' => 'Portefeuille introuvable.'], 403);
        }

        $request->validate([
            'contrat_id'    => 'required|exists:contrats,id',
            'frais_contrat' => 'required|numeric|min:0',
            'motif_demande' => 'nullable|string',
            'pin'           => 'required|string',
        ]);

        $wallet = $locataire->wallet;

        if (!Hash::check($request->input('pin'), $wallet->password)) {
            return response()->json(['message' => 'Code secret du portefeuille incorrect.'], 422);
        }

        $contrat = Contrat::where('id', $request->input('contrat_id'))
            ->where('locataire_id', $locataire->id)
            ->firstOrFail();

        $frais = (float) $request->input('frais_contrat');

        if ($wallet->solde < $frais) {
            return response()->json(['message' => 'Solde insuffisant dans votre portefeuille pour régler les frais de contrat.'], 422);
        }

        DB::beginTransaction();
        try {
            // Débiter le wallet si frais > 0
            if ($frais > 0) {
                $wallet->decrement('solde', $frais);
            }

            // Créer la demande de renouvellement
            $renouvellement = Renouvellement::create([
                'company_profile_id' => $locataire->company_profile_id,
                'agency_id'          => $locataire->agency_id,
                'locataire_id'       => $locataire->id,
                'contrat_id'         => $contrat->id,
                'nouveau_loyer'      => $contrat->loyer, // même loyer
                'cycle_paiement'     => $contrat->affectation?->cycle_paiement ?? 'mensuel',
                'duree'              => $contrat->affectation?->duree ?? '12 mois',
                'frais_contrat'      => $frais,
                'motif_demande'      => $request->input('motif_demande'),
                'statut'             => 'En attente',
            ]);

            // Créer la transaction wallet si frais > 0
            if ($frais > 0) {
                $tx = TransacWallet::create([
                    'wallet_id'    => $wallet->id,
                    'type'         => 'debit',
                    'amount'       => $frais,
                    'description'  => "Règlement frais de contrat - Renouvellement #{$renouvellement->reference}",
                    'reference_tx' => 'CTR-' . strtoupper(Str::random(10)),
                ]);

                // Créer le FraisContrat
                $fraisContrat = FraisContrat::create([
                    'renouvellement_id'  => $renouvellement->id,
                    'company_profile_id' => $locataire->company_profile_id,
                    'agency_id'          => $locataire->agency_id,
                    'montant'            => $frais,
                    'date_paiement'      => now(),
                ]);

                // Enregistrer dans la trésorerie globale
                Tresorerie::enregistrer(
                    $fraisContrat,
                    $frais,
                    "Frais de renouvellement contrat pour {$locataire->nom} (#{$renouvellement->reference})",
                    now()->toDateString()
                );
            }

            DB::commit();

            // Envoyer les emails
            $companyEmail = $locataire->company?->user?->email;
            $agencyEmail = $locataire->agency?->email;

            if ($companyEmail) {
                try { Mail::to($companyEmail)->send(new RenouvellementCreated($renouvellement)); } catch (\Exception $e) {}
            }
            if ($agencyEmail) {
                try { Mail::to($agencyEmail)->send(new RenouvellementCreated($renouvellement)); } catch (\Exception $e) {}
            }

            return response()->json([
                'message' => 'Demande de renouvellement soumise et frais réglés avec succès !',
                'solde' => (float) $wallet->fresh()->solde,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erreur lors de la soumission du renouvellement.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Crée automatiquement un wallet pour le locataire (depuis l'espace agence/entreprise).
     */
    public function createWalletForTenant(Request $request, Locataire $locataire)
    {
        $currentUser = Auth::user();
        if ($locataire->company_profile_id !== $currentUser->company_profile_id) {
            return response()->json(['message' => 'Action non autorisée.'], 403);
        }

        if ($locataire->wallet) {
            return response()->json(['message' => 'Ce locataire possède déjà un portefeuille électronique.'], 422);
        }

        // Générer un code PIN temporaire aléatoire de 4 chiffres
        $randomPin = strval(rand(1000, 9999));

        $wallet = DB::transaction(function () use ($locataire, $randomPin) {
            return Wallet::create([
                'company_profile_id' => $locataire->company_profile_id,
                'agency_id'          => $locataire->agency_id,
                'locataire_id'       => $locataire->id,
                'solde'              => 0.00,
                'password'           => Hash::make($randomPin),
            ]);
        });

        // Envoyer l'email au locataire avec le PIN
        try {
            Mail::to($locataire->user->email)->send(new WalletCreatedForTenantMail($wallet, $randomPin));
        } catch (\Exception $e) {
            logger()->error("Erreur d'envoi mail activation wallet locataire: " . $e->getMessage());
        }

        return response()->json([
            'message' => 'Le portefeuille électronique a été activé pour le locataire et un email lui a été envoyé.',
            'wallet_status' => 'activated'
        ]);
    }

    /**
     * Alimente le wallet d'un locataire (depuis l'espace agence/entreprise).
     */
    public function rechargeTenantWalletFromAdmin(Request $request, Locataire $locataire)
    {
        $currentUser = Auth::user();
        if ($locataire->company_profile_id !== $currentUser->company_profile_id) {
            return response()->json(['message' => 'Action non autorisée.'], 403);
        }

        $wallet = $locataire->wallet;
        if (!$wallet) {
            return response()->json(['message' => 'Ce locataire ne possède pas de portefeuille électronique actif.'], 422);
        }

        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $amount = (float) $request->input('amount');
        $refTx = 'ADM-RCG-' . strtoupper(Str::random(10));

        DB::transaction(function () use ($wallet, $amount, $refTx) {
            $wallet->increment('solde', $amount);

            TransacWallet::create([
                'wallet_id'    => $wallet->id,
                'type'         => 'recharge',
                'amount'       => $amount,
                'description'  => 'Recharge manuelle par le bailleur / agence',
                'reference_tx' => $refTx,
            ]);
        });

        // Envoyer l'email au locataire avec les détails de la recharge
        try {
            Mail::to($locataire->user->email)->send(new \App\Mail\WalletRechargedForTenantMail($wallet->fresh(), $amount, (float) $wallet->solde, $refTx));
        } catch (\Exception $e) {
            logger()->error("Erreur d'envoi mail recharge wallet locataire: " . $e->getMessage());
        }

        return response()->json([
            'message' => 'Le portefeuille électronique a été alimenté avec succès et un email de confirmation a été envoyé au locataire.',
            'solde' => (float) $wallet->solde,
        ]);
    }

    /**
     * Affiche la page de validation publique du paiement par wallet.
     */
    public function showValidationPage($token)
    {
        $pending = \App\Models\PendingWalletPayment::where('token', $token)
            ->where('status', 'pending')
            ->with('locataire.user')
            ->first();

        if (!$pending) {
            return \Inertia\Inertia::render('Locataire/WalletValidationPage', [
                'error' => 'Cette demande de paiement est introuvable, déjà validée ou expirée.'
            ]);
        }

        $locataire = $pending->locataire;
        $companyName = $locataire->company ? ($locataire->company->legal_name ?? 'PropertyAI') : 'PropertyAI';
        $agencyName = $locataire->agency ? $locataire->agency->name : 'N/A';

        if ($pending->type === 'loyer') {
            $months = data_get($pending->data, 'months', []);
            $periodNames = array_map(function($m) {
                $parts = explode('-', $m['periode']);
                if (count($parts) >= 2) {
                    $monthsList = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
                    return $monthsList[intval($parts[1]) - 1] . ' ' . $parts[0];
                }
                return $m['periode'];
            }, $months);
            $description = 'Règlement de loyer pour : ' . implode(', ', $periodNames);
        } else {
            $invoiceNum = data_get($pending->data, 'invoice_num', 'N/A');
            $description = 'Règlement de la facture N° ' . $invoiceNum;
        }

        return \Inertia\Inertia::render('Locataire/WalletValidationPage', [
            'pendingPayment' => [
                'token' => $pending->token,
                'amount' => (float)$pending->amount,
                'type' => $pending->type,
                'description' => $description,
                'locataire_nom' => $locataire->nom,
                'company_name' => $companyName,
                'agency_name' => $agencyName,
            ]
        ]);
    }

    /**
     * Valide et exécute le paiement par wallet en attente.
     */
    public function validatePendingPayment(Request $request, $token)
    {
        $pending = \App\Models\PendingWalletPayment::where('token', $token)
            ->where('status', 'pending')
            ->first();

        if (!$pending) {
            return response()->json(['message' => 'Cette demande de paiement est introuvable, déjà validée ou expirée.'], 422);
        }

        $request->validate([
            'pin' => 'required|string|size:4',
        ]);

        $locataire = $pending->locataire;
        $wallet = $locataire->wallet;

        if (!$wallet) {
            return response()->json(['message' => 'Portefeuille électronique introuvable.'], 422);
        }

        if (!Hash::check($request->input('pin'), $wallet->password)) {
            return response()->json(['message' => 'Le code secret est incorrect.'], 422);
        }

        if ($wallet->solde < $pending->amount) {
            return response()->json(['message' => 'Le solde de votre portefeuille est insuffisant.'], 422);
        }

        DB::beginTransaction();
        try {
            // Débiter le wallet
            $wallet->decrement('solde', $pending->amount);

            $txType = $pending->type === 'loyer' ? 'RENT' : 'UTL';
            $txRef = $txType . '-VAL-' . strtoupper(Str::random(10));

            // Créer la transaction wallet
            TransacWallet::create([
                'wallet_id'    => $wallet->id,
                'type'         => 'debit',
                'amount'       => $pending->amount,
                'description'  => $pending->type === 'loyer' ? 'Règlement Loyer (autorisation de paiement)' : 'Règlement Facture (autorisation de paiement)',
                'reference_tx' => $txRef,
            ]);

            if ($pending->type === 'loyer') {
                // Créer le PaiementLoyer
                $p = PaiementLoyer::create([
                    'company_profile_id' => $pending->company_profile_id,
                    'agency_id'          => $pending->agency_id,
                    'locataire_id'       => $pending->locataire_id,
                    'contrat_id'         => $pending->target_id,
                    'date_reglement'     => now(),
                    'montant_total'      => $pending->amount,
                    'mode_reglement'     => 'Wallet',
                    'reference_tx'       => $txRef,
                ]);

                // Créer les MoisPayes et solder les factures correspondantes
                $months = data_get($pending->data, 'months', []);
                foreach ($months as $m) {
                    MoisPaye::create([
                        'paiement_loyer_id' => $p->id,
                        'periode'           => $m['periode'],
                        'loyer_de_base'     => (float) $m['loyer_de_base'],
                        'penalite'          => (float) $m['penalite'],
                        'total_paye'        => (float) $m['total_paye'],
                    ]);

                    Facture::where('locataire_id', $pending->locataire_id)
                        ->where('type_facture_id', function($query) {
                            $query->select('id')->from('type_factures')->where('nom', 'Loyer')->limit(1);
                        })
                        ->where('periode', 'like', '%' . $m['periode'] . '%')
                        ->where(function($q) {
                            $q->where('statut', '!=', 'payée')
                              ->where('statut', '!=', 'Payé');
                        })
                        ->update([
                            'statut'         => 'Payé',
                            'montant_paye'   => DB::raw('total'),
                            'mode_reglement' => 'wallet',
                        ]);
                }

                // Trésorerie
                $contrat = $p->contrat;
                $contratNum = $contrat?->numero ?? 'N/A';
                Tresorerie::enregistrer(
                    $p,
                    (float) $pending->amount,
                    "Enregistrement de paiement de loyer de {$locataire->nom} pour le contrat {$contratNum} (Réf: {$p->reference})",
                    now()->toDateString()
                );
            } else {
                // Facture
                $invoice = Facture::findOrFail($pending->target_id);
                $invoice->update([
                    'statut'         => 'Payé',
                    'montant_paye'   => $invoice->total,
                    'mode_reglement' => 'wallet',
                ]);

                // Trésorerie
                Tresorerie::enregistrer(
                    $invoice,
                    (float) $invoice->total,
                    "Règlement de la facture {$invoice->numero} par {$locataire->nom}",
                    now()->toDateString()
                );

                // Notification e-mail
                $agencyEmail = $locataire->agency?->email;
                $companyEmail = $locataire->company?->user?->email;
                $recipient = $agencyEmail ?? $companyEmail;
                if ($recipient) {
                    try {
                        Mail::to($recipient)->send(new \App\Mail\InvoicePaidNotificationMail($invoice, $locataire));
                    } catch (\Exception $e) {
                        logger()->error("Mail error invoice paid notification: " . $e->getMessage());
                    }
                }
            }

            // Mettre à jour la demande en validée
            $pending->update(['status' => 'validated']);

            DB::commit();

            return response()->json(['message' => 'Le paiement a été validé et enregistré avec succès.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Une erreur est survenue lors de la validation du paiement.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Retourne les détails de la demande de paiement en attente sous format JSON.
     */
    public function getPendingPaymentDetailsJson($token)
    {
        $pending = \App\Models\PendingWalletPayment::where('token', $token)
            ->where('status', 'pending')
            ->with('locataire.user')
            ->first();

        if (!$pending) {
            return response()->json(['message' => 'Cette demande de paiement est introuvable, déjà validée ou expirée.'], 404);
        }

        $locataire = $pending->locataire;
        $companyName = $locataire->company ? ($locataire->company->legal_name ?? 'PropertyAI') : 'PropertyAI';
        $agencyName = $locataire->agency ? $locataire->agency->name : 'N/A';

        if ($pending->type === 'loyer') {
            $months = data_get($pending->data, 'months', []);
            $periodNames = array_map(function($m) {
                $parts = explode('-', $m['periode']);
                if (count($parts) >= 2) {
                    $monthsList = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
                    return $monthsList[intval($parts[1]) - 1] . ' ' . $parts[0];
                }
                return $m['periode'];
            }, $months);
            $description = 'Règlement de loyer pour : ' . implode(', ', $periodNames);
        } else {
            $invoiceNum = data_get($pending->data, 'invoice_num', 'N/A');
            $description = 'Règlement de la facture N° ' . $invoiceNum;
        }

        return response()->json([
            'pendingPayment' => [
                'token' => $pending->token,
                'amount' => (float)$pending->amount,
                'type' => $pending->type,
                'description' => $description,
                'locataire_nom' => $locataire->nom,
                'company_name' => $companyName,
                'agency_name' => $agencyName,
            ]
        ]);
    }
}

