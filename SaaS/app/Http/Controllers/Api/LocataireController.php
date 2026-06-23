<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LocataireController extends Controller
{
    /**
     * Get locataire dashboard data.
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();
        $locataire = $user->locataire;

        if (!$locataire) {
            return response()->json(['error' => 'Profil locataire non trouvé'], 404);
        }

        // Get wallet data
        $wallet = $locataire->wallet;
        $walletData = null;
        if ($wallet) {
            $walletData = [
                'id' => $wallet->id,
                'solde' => (float) $wallet->solde,
                'transactions' => $wallet->transactions()
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get()
                    ->map(function ($transaction) {
                        return [
                            'id' => $transaction->id,
                            'type' => $transaction->type,
                            'montant' => (float) $transaction->amount,
                            'description' => $transaction->description,
                            'date' => $transaction->created_at->format('Y-m-d H:i:s'),
                        ];
                    }),
            ];
        }

        // Get contracts
        $contracts = $locataire->contrats()
            ->with(['logement', 'typeContrat'])
            ->where('statut', 'actif')
            ->get()
            ->map(function ($contrat) {
                return [
                    'id' => $contrat->id,
                    'numero' => $contrat->numero,
                    'loyer' => (float) $contrat->loyer,
                    'caution' => (float) $contrat->caution,
                    'debut' => $contrat->debut->format('Y-m-d'),
                    'fin' => $contrat->fin->format('Y-m-d'),
                    'rent' => (float) $contrat->loyer, // For mobile compatibility
                    'deposit' => (float) $contrat->caution, // For mobile compatibility
                    'start_date' => $contrat->debut->format('Y-m-d'), // For mobile compatibility
                    'end_date' => $contrat->fin->format('Y-m-d'), // For mobile compatibility
                    'contrat_numero' => $contrat->numero, // For mobile compatibility
                    'type' => $contrat->typeContrat ? $contrat->typeContrat->nom : 'Bail d\'habitation',
                    'logement' => $contrat->logement ? [
                        'id' => $contrat->logement->id,
                        'adresse' => $contrat->logement->adresse,
                        'ville' => $contrat->logement->ville,
                        'name' => 'Logement ' . $contrat->logement->numero,
                        'address' => $contrat->logement->adresse,
                        'specs' => [
                            ['label' => 'Surface', 'value' => $contrat->logement->surface . ' m²'],
                            ['label' => 'Étage', 'value' => $contrat->logement->etage],
                            ['label' => 'Référence', 'value' => $contrat->logement->reference],
                            ['label' => 'Bâtiment', 'value' => $contrat->logement->batiment],
                            ['label' => 'Ville', 'value' => $contrat->logement->ville],
                        ],
                        'equipment' => ['WiFi', 'Chauffage', 'Eau chaude'],
                    ] : null,
                ];
            });

        // Get invoices (factures)
        $invoices = $locataire->factures()
            ->with(['typeFacture'])
            ->orderBy('date_echeance', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($facture) {
                return [
                    'id' => $facture->id,
                    'numero' => $facture->numero,
                    'reference' => $facture->numero,
                    'total' => (float) $facture->total,
                    'amount' => (float) $facture->total,
                    'montant_paye' => (float) $facture->montant_paye,
                    'statut' => $facture->statut,
                    'status' => $facture->statut,
                    'date_echeance' => $facture->date_echeance->format('Y-m-d'),
                    'period' => $facture->date_echeance->format('F Y'),
                    'type' => $facture->typeFacture ? $facture->typeFacture->nom : 'Général',
                ];
            });

        // Get receipts (paiements)
        $receipts = $locataire->factures()
            ->where('statut', 'payée')
            ->where('montant_paye', '>', 0)
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($facture) {
                $type = $facture->typeFacture ? strtolower($facture->typeFacture->nom) : 'rent';
                return [
                    'id' => $facture->id,
                    'numero' => $facture->numero,
                    'reference' => $facture->numero,
                    'montant' => (float) $facture->montant_paye,
                    'amount' => (float) $facture->montant_paye,
                    'date' => $facture->updated_at->format('Y-m-d'),
                    'paid_at' => $facture->updated_at->format('Y-m-d'),
                    'period' => $facture->date_echeance->format('F Y'),
                    'type' => $type,
                    'title' => $facture->typeFacture ? $facture->typeFacture->nom : 'Paiement',
                ];
            });

        // Get support tickets (placeholder - implement based on your ticket system)
        $tickets = []; // TODO: Implement when ticket system is ready

        // Get contract fees (placeholder - implement based on your fee system)
        $contractFees = []; // TODO: Implement when fee system is ready

        // Calculate summary stats
        $totalDue = $invoices->where('statut', '!=', 'payée')->sum('amount');
        $openTicketsCount = count($tickets);

        // Calculate rent months with penalties
        $rentMonths = [];
        if ($contracts->isNotEmpty()) {
            $contract = $contracts->first();
            $startDate = \Carbon\Carbon::parse($contract['start_date']);
            $endDate = \Carbon\Carbon::parse($contract['end_date']);
            $currentDate = \Carbon\Carbon::now();
            
            $current = $startDate->copy();
            while ($current->lte($endDate) && $current->lte($currentDate)) {
                $monthKey = $current->format('Y-m');
                $monthLabel = $current->locale('fr')->translatedFormat('F Y');
                
                // Check if this month is paid
                $isPaid = $invoices->contains(function ($invoice) use ($monthKey) {
                    return \Carbon\Carbon::parse($invoice['date_echeance'])->format('Y-m') === $monthKey && $invoice['statut'] === 'payée';
                });
                
                // Calculate penalty if overdue (simplified logic)
                $penaltyRate = 0;
                $penaltyAmount = 0;
                if (!$isPaid && $current->copy()->addDays(10)->lt($currentDate)) {
                    $daysOverdue = $current->copy()->addDays(10)->diffInDays($currentDate);
                    if ($daysOverdue > 0) {
                        $penaltyRate = min(15, floor($daysOverdue / 5) * 5);
                        $penaltyAmount = ($contract['loyer'] * $penaltyRate) / 100;
                    }
                }
                
                $rentMonths[] = [
                    'key' => $monthKey,
                    'label' => $monthLabel,
                    'amount' => $contract['loyer'],
                    'status' => $isPaid ? 'paid' : 'unpaid',
                    'penalty_rate' => $penaltyRate,
                    'penalty_amount' => $penaltyAmount,
                ];
                
                $current->addMonth();
            }
        }

        // Get company/agency info
        $company = null;
        $agency = null;
        if ($locataire->company) {
            $company = [
                'id' => $locataire->company->id,
                'nom' => $locataire->company->nom,
                'name' => $locataire->company->nom,
                'logo_url' => $locataire->company->logo_url,
            ];
        }
        if ($locataire->agency) {
            $agency = [
                'id' => $locataire->agency->id,
                'nom' => $locataire->agency->nom,
                'name' => $locataire->agency->nom,
            ];
        }

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'telephone' => $locataire->telephone,
                'first_name' => '', // Locataire model doesn't have separate first/last name
                'last_name' => $user->name,
            ],
            'company' => $company,
            'agency' => $agency,
            'wallet' => $walletData,
            'contracts' => $contracts,
            'invoices' => $invoices,
            'receipts' => $receipts,
            'tickets' => $tickets,
            'contract_fees' => $contractFees,
            'rent_months' => $rentMonths,
            'summary' => [
                'total_due' => $totalDue,
                'open_tickets_count' => $openTicketsCount,
            ],
        ]);
    }

    /**
     * Create wallet for locataire.
     */
    public function createWallet(Request $request)
    {
        $request->validate([
            'pin' => 'required|string|min:4|max:6',
        ]);

        $user = $request->user();
        $locataire = $user->locataire;

        if (!$locataire) {
            return response()->json(['error' => 'Profil locataire non trouvé'], 404);
        }

        if ($locataire->wallet) {
            return response()->json(['error' => 'Wallet existe déjà'], 400);
        }

        $wallet = \App\Models\Wallet::create([
            'locataire_id' => $locataire->id,
            'company_profile_id' => $locataire->company_profile_id,
            'agency_id' => $locataire->agency_id,
            'solde' => 0,
            'password' => Hash::make($request->pin),
        ]);

        return response()->json(['message' => 'Wallet créé avec succès']);
    }

    /**
     * Recharge wallet.
     */
    public function rechargeWallet(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = $request->user();
        $locataire = $user->locataire;

        if (!$locataire || !$locataire->wallet) {
            return response()->json(['error' => 'Wallet non trouvé'], 404);
        }

        $wallet = $locataire->wallet;
        $amount = (float) $request->amount;

        // Add transaction
        \App\Models\TransacWallet::create([
            'wallet_id' => $wallet->id,
            'type' => 'recharge',
            'amount' => $amount,
            'description' => 'Recharge wallet',
        ]);

        // Update balance
        $wallet->solde += $amount;
        $wallet->save();

        return response()->json(['message' => 'Recharge réussie']);
    }

    /**
     * Pay rent from wallet.
     */
    public function payRent(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = $request->user();
        $locataire = $user->locataire;

        if (!$locataire || !$locataire->wallet) {
            return response()->json(['error' => 'Wallet non trouvé'], 404);
        }

        $wallet = $locataire->wallet;
        $amount = (float) $request->amount;

        if ($wallet->solde < $amount) {
            return response()->json(['error' => 'Solde insuffisant'], 400);
        }

        // Add transaction
        \App\Models\TransacWallet::create([
            'wallet_id' => $wallet->id,
            'type' => 'paiement_loyer',
            'amount' => -$amount,
            'description' => 'Paiement loyer',
        ]);

        // Update balance
        $wallet->solde -= $amount;
        $wallet->save();

        return response()->json(['message' => 'Paiement loyer réussi']);
    }

    /**
     * Pay utility from wallet.
     */
    public function payUtility(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'type' => 'required|string',
        ]);

        $user = $request->user();
        $locataire = $user->locataire;

        if (!$locataire || !$locataire->wallet) {
            return response()->json(['error' => 'Wallet non trouvé'], 404);
        }

        $wallet = $locataire->wallet;
        $amount = (float) $request->amount;

        if ($wallet->solde < $amount) {
            return response()->json(['error' => 'Solde insuffisant'], 400);
        }

        // Add transaction
        \App\Models\TransacWallet::create([
            'wallet_id' => $wallet->id,
            'type' => 'paiement_utility',
            'amount' => -$amount,
            'description' => 'Paiement ' . $request->type,
        ]);

        // Update balance
        $wallet->solde -= $amount;
        $wallet->save();

        return response()->json(['message' => 'Paiement utilité réussi']);
    }

    /**
     * Create support ticket.
     */
    public function createTicket(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'required|string',
        ]);

        $user = $request->user();
        $locataire = $user->locataire;

        if (!$locataire) {
            return response()->json(['error' => 'Profil locataire non trouvé'], 404);
        }

        // Placeholder - implement when ticket system is ready
        return response()->json(['message' => 'Ticket créé avec succès (placeholder)']);
    }

    /**
     * Transfer wallet funds.
     */
    public function transferFunds(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'memo' => 'nullable|string',
        ]);

        $user = $request->user();
        $locataire = $user->locataire;

        if (!$locataire || !$locataire->wallet) {
            return response()->json(['error' => 'Wallet non trouvé'], 404);
        }

        $wallet = $locataire->wallet;
        $amount = (float) $request->amount;

        if ($wallet->solde < $amount) {
            return response()->json(['error' => 'Solde insuffisant'], 400);
        }

        // Add transaction
        \App\Models\TransacWallet::create([
            'wallet_id' => $wallet->id,
            'type' => 'transfert',
            'amount' => -$amount,
            'description' => $request->memo ?? 'Transfert de fonds',
        ]);

        // Update balance
        $wallet->solde -= $amount;
        $wallet->save();

        return response()->json(['message' => 'Transfert effectué avec succès']);
    }

    /**
     * Pay contract fee.
     */
    public function payContractFee(Request $request)
    {
        $request->validate([
            'fee_id' => 'required|integer',
            'amount' => 'required|numeric|min:1',
        ]);

        $user = $request->user();
        $locataire = $user->locataire;

        if (!$locataire || !$locataire->wallet) {
            return response()->json(['error' => 'Wallet non trouvé'], 404);
        }

        $wallet = $locataire->wallet;
        $amount = (float) $request->amount;

        if ($wallet->solde < $amount) {
            return response()->json(['error' => 'Solde insuffisant'], 400);
        }

        // Add transaction
        \App\Models\TransacWallet::create([
            'wallet_id' => $wallet->id,
            'type' => 'paiement_frais_contrat',
            'amount' => -$amount,
            'description' => 'Paiement frais de contrat #' . $request->fee_id,
        ]);

        // Update balance
        $wallet->solde -= $amount;
        $wallet->save();

        return response()->json(['message' => 'Paiement frais de contrat réussi']);
    }
}
