<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Agency;
use App\Models\Tresorerie;
use App\Models\Depense;
use Carbon\Carbon;

class FinanceStatsController extends Controller
{
    public function getStats(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->company_profile_id) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $companyId = $user->company_profile_id;
        $year = (int) $request->input('year', Carbon::now()->year);

        // 1. Calculate global KPIs
        $revenue = (float) Tresorerie::where('company_profile_id', $companyId)
            ->where('deleted', false)
            ->where('montant', '>', 0)
            ->whereYear('date_transaction', $year)
            ->sum('montant');

        $expenses = (float) Depense::where('company_profile_id', $companyId)
            ->where('deleted', false)
            ->where('statut', 'Payé')
            ->whereYear('date_depense', $year)
            ->sum('montant');

        $netCash = $revenue - $expenses;
        $profitMargin = $revenue > 0 ? round(($netCash / $revenue) * 100, 1) : 0.0;

        // 2. Monthly cashflows (inflows vs outflows) for the year
        $monthlyInflows = array_fill(0, 12, 0.0);
        $monthlyOutflows = array_fill(0, 12, 0.0);

        Tresorerie::where('company_profile_id', $companyId)
            ->where('deleted', false)
            ->where('montant', '>', 0)
            ->whereYear('date_transaction', $year)
            ->get()
            ->each(function ($item) use (&$monthlyInflows) {
                $month = Carbon::parse($item->date_transaction)->month;
                $monthlyInflows[$month - 1] += (float) $item->montant;
            });

        Depense::where('company_profile_id', $companyId)
            ->where('deleted', false)
            ->where('statut', 'Payé')
            ->whereYear('date_depense', $year)
            ->get()
            ->each(function ($item) use (&$monthlyOutflows) {
                $month = Carbon::parse($item->date_depense)->month;
                $monthlyOutflows[$month - 1] += (float) $item->montant;
            });

        // 3. Structure of revenues by source table origin
        $rawRevenues = Tresorerie::where('company_profile_id', $companyId)
            ->where('deleted', false)
            ->where('montant', '>', 0)
            ->whereYear('date_transaction', $year)
            ->get();

        $structure = [
            'loyers' => 0.0,
            'factures' => 0.0,
            'entrees_fonds' => 0.0,
            'frais_contrats' => 0.0,
            'autres' => 0.0,
        ];

        $rawRevenues->each(function ($item) use (&$structure) {
            $type = $item->source_type;
            $amount = (float) $item->montant;
            if ($type === 'App\Models\PaiementLoyer') {
                $structure['loyers'] += $amount;
            } elseif ($type === 'App\Models\Facture') {
                $structure['factures'] += $amount;
            } elseif ($type === 'App\Models\EntreeFonds') {
                $structure['entrees_fonds'] += $amount;
            } elseif ($type === 'App\Models\FraisContrat') {
                $structure['frais_contrats'] += $amount;
            } else {
                $structure['autres'] += $amount;
            }
        });

        // 4. Performance by Entity (Headquarters vs Agencies)
        $agencies = Agency::where('company_profile_id', $companyId)->get();

        $yearInflows = Tresorerie::where('company_profile_id', $companyId)
            ->where('deleted', false)
            ->where('montant', '>', 0)
            ->whereYear('date_transaction', $year)
            ->get();

        $yearOutflows = Depense::where('company_profile_id', $companyId)
            ->where('deleted', false)
            ->where('statut', 'Payé')
            ->whereYear('date_depense', $year)
            ->get();

        // Calculate for Siège (agency_id = null)
        $siegeInflows = $yearInflows->filter(fn($x) => is_null($x->agency_id));
        $siegeLoyers = $siegeInflows->filter(fn($x) => in_array($x->source_type, ['App\Models\PaiementLoyer', 'App\Models\Facture']))->sum('montant');
        $siegeDivers = $siegeInflows->filter(fn($x) => !in_array($x->source_type, ['App\Models\PaiementLoyer', 'App\Models\Facture']))->sum('montant');
        $siegeDepenses = $yearOutflows->filter(fn($x) => is_null($x->agency_id))->sum('montant');

        $entitiesData = [];
        $entitiesData[] = [
            'nom' => 'Siège Social',
            'type' => 'Siège',
            'loyers' => (float)$siegeLoyers,
            'divers' => (float)$siegeDivers,
            'depenses' => (float)$siegeDepenses,
            'solde' => (float)($siegeLoyers + $siegeDivers - $siegeDepenses),
        ];

        foreach ($agencies as $agency) {
            $agencyInflows = $yearInflows->filter(fn($x) => $x->agency_id == $agency->id);
            $agencyLoyers = $agencyInflows->filter(fn($x) => in_array($x->source_type, ['App\Models\PaiementLoyer', 'App\Models\Facture']))->sum('montant');
            $agencyDivers = $agencyInflows->filter(fn($x) => !in_array($x->source_type, ['App\Models\PaiementLoyer', 'App\Models\Facture']))->sum('montant');
            $agencyDepenses = $yearOutflows->filter(fn($x) => $x->agency_id == $agency->id)->sum('montant');

            $entitiesData[] = [
                'nom' => $agency->name,
                'type' => 'Agence',
                'loyers' => (float)$agencyLoyers,
                'divers' => (float)$agencyDivers,
                'depenses' => (float)$agencyDepenses,
                'solde' => (float)($agencyLoyers + $agencyDivers - $agencyDepenses),
            ];
        }

        return response()->json([
            'year' => $year,
            'kpis' => [
                'revenue' => $revenue,
                'expenses' => $expenses,
                'netCash' => $netCash,
                'profitMargin' => $profitMargin,
            ],
            'chart_monthly' => [
                'inflows' => $monthlyInflows,
                'outflows' => $monthlyOutflows,
            ],
            'chart_structure' => [
                'loyers' => $structure['loyers'],
                'factures' => $structure['factures'],
                'entrees_fonds' => $structure['entrees_fonds'],
                'frais_contrats' => $structure['frais_contrats'],
                'autres' => $structure['autres'],
            ],
            'entities' => $entitiesData,
        ]);
    }
}
