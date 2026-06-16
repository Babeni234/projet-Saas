<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Agency;
use App\Models\Employee;
use App\Models\Batiment;
use App\Models\Logement;
use App\Models\Contrat;
use App\Models\Tresorerie;
use App\Models\Depense;
use App\Models\Facture;
use Carbon\Carbon;

class DashboardStatsController extends Controller
{
    public function getStats(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->company_profile_id) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $companyId = $user->company_profile_id;
        $agencyId = null;

        // If the user belongs to an agency, scope data strictly to that agency
        if ($user->employee && $user->employee->agency_id !== null) {
            $agencyId = $user->employee->agency_id;
        }

        // 1. Counts for Banner / KPIs
        if ($agencyId) {
            // Agency mode: count employees/collaborators in this agency
            $countAgenciesOrEmployees = Employee::where('company_profile_id', $companyId)
                ->where('agency_id', $agencyId)
                ->count();
        } else {
            // Enterprise mode: count total agencies
            $countAgenciesOrEmployees = Agency::where('company_profile_id', $companyId)
                ->where('deleted', false)
                ->count();
        }

        $buildingsQuery = Batiment::where('company_profile_id', $companyId)->where('deleted', false);
        if ($agencyId) {
            $buildingsQuery->where('agency_id', $agencyId);
        }
        $countBuildings = $buildingsQuery->count();

        $contractsQuery = Contrat::where('company_profile_id', $companyId)
            ->where('deleted', false)
            ->where('statut', 'Actif');
        if ($agencyId) {
            $contractsQuery->where('agency_id', $agencyId);
        }
        $countContracts = $contractsQuery->count();

        // 2. Chiffre d'Affaires Global (Total Revenue from positive Tresorerie)
        $totalRevQuery = Tresorerie::where('company_profile_id', $companyId)
            ->where('deleted', false)
            ->where('montant', '>', 0);
        if ($agencyId) {
            $totalRevQuery->where('agency_id', $agencyId);
        }
        $totalRevenue = (float) $totalRevQuery->sum('montant');

        // 3. Current & Last Month Revenue (Actual vs Expected)
        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->endOfMonth()->toDateString();
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth()->toDateString();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth()->toDateString();

        $actualMonthlyQuery = Tresorerie::where('company_profile_id', $companyId)
            ->where('deleted', false)
            ->where('montant', '>', 0)
            ->whereBetween('date_transaction', [$startOfMonth, $endOfMonth]);
        if ($agencyId) {
            $actualMonthlyQuery->where('agency_id', $agencyId);
        }
        $revenueActual = (float) $actualMonthlyQuery->sum('montant');

        $lastMonthRevQuery = Tresorerie::where('company_profile_id', $companyId)
            ->where('deleted', false)
            ->where('montant', '>', 0)
            ->whereBetween('date_transaction', [$startOfLastMonth, $endOfLastMonth]);
        if ($agencyId) {
            $lastMonthRevQuery->where('agency_id', $agencyId);
        }
        $revenueLastMonth = (float) $lastMonthRevQuery->sum('montant');

        $revenueChangePercent = 0.0;
        if ($revenueLastMonth > 0) {
            $revenueChangePercent = round((($revenueActual - $revenueLastMonth) / $revenueLastMonth) * 100, 1);
        }

        $expectedMonthlyQuery = Contrat::where('company_profile_id', $companyId)
            ->where('deleted', false)
            ->where('statut', 'Actif');
        if ($agencyId) {
            $expectedMonthlyQuery->where('agency_id', $agencyId);
        }
        $revenueExpected = (float) $expectedMonthlyQuery->sum('loyer');

        // 4. Current & Last Month Expenses
        $actualMonthlyExpensesQuery = Depense::where('company_profile_id', $companyId)
            ->where('deleted', false)
            ->where('statut', 'Payé')
            ->whereBetween('date_depense', [$startOfMonth, $endOfMonth]);
        if ($agencyId) {
            $actualMonthlyExpensesQuery->where('agency_id', $agencyId);
        }
        $expensesActual = (float) $actualMonthlyExpensesQuery->sum('montant');

        $lastMonthExpensesQuery = Depense::where('company_profile_id', $companyId)
            ->where('deleted', false)
            ->where('statut', 'Payé')
            ->whereBetween('date_depense', [$startOfLastMonth, $endOfLastMonth]);
        if ($agencyId) {
            $lastMonthExpensesQuery->where('agency_id', $agencyId);
        }
        $expensesLastMonth = (float) $lastMonthExpensesQuery->sum('montant');

        $expensesChangePercent = 0.0;
        if ($expensesLastMonth > 0) {
            $expensesChangePercent = round((($expensesActual - $expensesLastMonth) / $expensesLastMonth) * 100, 1);
        }

        // 5. Occupancy Rates (Logements)
        $totalLogementsQuery = Logement::where('company_profile_id', $companyId)->where('deleted', false);
        $occupiedLogementsQuery = Logement::where('company_profile_id', $companyId)
            ->where('deleted', false)
            ->where('statut', 'Occupé');
        if ($agencyId) {
            $totalLogementsQuery->where('agency_id', $agencyId);
            $occupiedLogementsQuery->where('agency_id', $agencyId);
        }
        $totalLogementsCount = $totalLogementsQuery->count();
        $occupiedLogementsCount = $occupiedLogementsQuery->count();
        $vacantLogementsCount = $totalLogementsCount - $occupiedLogementsCount;
        $occupancyRate = $totalLogementsCount > 0 ? round(($occupiedLogementsCount / $totalLogementsCount) * 100, 1) : 0.0;
        $vacancyRate = $totalLogementsCount > 0 ? round(($vacantLogementsCount / $totalLogementsCount) * 100, 1) : 0.0;

        // For display: calculate total realized expenses (depenses table with Payé)
        $totalExpensesQuery = Depense::where('company_profile_id', $companyId)
            ->where('deleted', false)
            ->where('statut', 'Payé');
        if ($agencyId) {
            $totalExpensesQuery->where('agency_id', $agencyId);
        }
        $totalExpenses = (float) $totalExpensesQuery->sum('montant');

        // 6. Net Cashflow (Revenues - Expenses)
        $cashflowNet = $totalRevenue - $totalExpenses;

        // 7. Unpaid Invoices
        $unpaidInvoicesQuery = Facture::with(['locataire.user', 'typeFacture', 'agency'])
            ->where('company_profile_id', $companyId)
            ->where('deleted', false)
            ->where('statut', '!=', 'Payé');
        if ($agencyId) {
            $unpaidInvoicesQuery->where('agency_id', $agencyId);
        }
        $unpaidInvoices = $unpaidInvoicesQuery->orderBy('date_emission', 'desc')->get();

        $unpaidInvoicesCount = $unpaidInvoices->count();
        $unpaidInvoicesTotal = 0.0;
        $unpaidInvoicesOverdueCount = 0;
        $thirtyDaysAgo = Carbon::now()->subDays(30)->toDateString();

        $unpaidInvoices->each(function ($facture) use (&$unpaidInvoicesTotal, &$unpaidInvoicesOverdueCount, $thirtyDaysAgo) {
            $unpaidInvoicesTotal += ((float)$facture->total - (float)$facture->montant_paye);
            if ($facture->date_echeance && $facture->date_echeance->toDateString() < $thirtyDaysAgo) {
                $unpaidInvoicesOverdueCount++;
            }
        });

        // Unpaid Ageing Categories for Unpaid Chart (30j, 60j, 90j, 120j, 150j+)
        $unpaidPeriodData = [0.0, 0.0, 0.0, 0.0, 0.0];
        $nowDate = Carbon::now();
        $unpaidInvoices->each(function ($facture) use (&$unpaidPeriodData, $nowDate) {
            $amount = (float)$facture->total - (float)$facture->montant_paye;
            if ($amount <= 0) return;

            if ($facture->date_echeance) {
                $dueDate = Carbon::parse($facture->date_echeance);
                $daysOverdue = $nowDate->diffInDays($dueDate, false);

                if ($daysOverdue < 0) {
                    $days = abs($daysOverdue);
                    if ($days <= 30) {
                        $unpaidPeriodData[0] += $amount;
                    } elseif ($days <= 60) {
                        $unpaidPeriodData[1] += $amount;
                    } elseif ($days <= 90) {
                        $unpaidPeriodData[2] += $amount;
                    } elseif ($days <= 120) {
                        $unpaidPeriodData[3] += $amount;
                    } else {
                        $unpaidPeriodData[4] += $amount;
                    }
                } else {
                    $unpaidPeriodData[0] += $amount;
                }
            } else {
                $unpaidPeriodData[0] += $amount;
            }
        });

        // Unpaid Rate
        $unpaidRate = ($totalRevenue + $unpaidInvoicesTotal) > 0 
            ? round(($unpaidInvoicesTotal / ($totalRevenue + $unpaidInvoicesTotal)) * 100, 1) 
            : 0.0;

        // 8. Expenses Pending Validation (Dépenses en attente)
        $pendingExpensesQuery = Depense::with(['typeDepense', 'agency'])
            ->where('company_profile_id', $companyId)
            ->where('deleted', false)
            ->where('statut', 'En attente');
        if ($agencyId) {
            $pendingExpensesQuery->where('agency_id', $agencyId);
        }
        $pendingExpenses = $pendingExpensesQuery->orderBy('date_depense', 'desc')->get();

        // 9. Recent Transactions
        $recentTransactionsQuery = Tresorerie::with(['agency'])
            ->where('company_profile_id', $companyId)
            ->where('deleted', false)
            ->orderBy('date_transaction', 'desc')
            ->orderBy('id', 'desc')
            ->limit(10);
        if ($agencyId) {
            $recentTransactionsQuery->where('agency_id', $agencyId);
        }
        $recentTransactions = $recentTransactionsQuery->get();

        // 10. Chart: Revenue vs Expenses (Current Year, Month-by-month Jan-Dec)
        $currentYear = Carbon::now()->year;
        $monthlyRevenues = array_fill(0, 12, 0.0);
        $monthlyExpenses = array_fill(0, 12, 0.0);

        // Fetch revenues (montant > 0 in Tresorerie) for current year
        $yearRevenuesQuery = Tresorerie::where('company_profile_id', $companyId)
            ->where('deleted', false)
            ->where('montant', '>', 0)
            ->whereYear('date_transaction', $currentYear);
        if ($agencyId) {
            $yearRevenuesQuery->where('agency_id', $agencyId);
        }
        $yearRevenuesQuery->get()->each(function ($item) use (&$monthlyRevenues) {
            $month = Carbon::parse($item->date_transaction)->month;
            $monthlyRevenues[$month - 1] += (float) $item->montant;
        });

        // Fetch expenses (montant in Depense where statut = Payé) for current year
        $yearExpensesQuery = Depense::where('company_profile_id', $companyId)
            ->where('deleted', false)
            ->where('statut', 'Payé')
            ->whereYear('date_depense', $currentYear);
        if ($agencyId) {
            $yearExpensesQuery->where('agency_id', $agencyId);
        }
        $yearExpensesQuery->get()->each(function ($item) use (&$monthlyExpenses) {
            $month = Carbon::parse($item->date_depense)->month;
            $monthlyExpenses[$month - 1] += (float) $item->montant;
        });

        // 11. Chart: Expenses breakdown by Type
        $expensesByTypeQuery = Depense::with('typeDepense')
            ->where('company_profile_id', $companyId)
            ->where('deleted', false)
            ->where('statut', 'Payé');
        if ($agencyId) {
            $expensesByTypeQuery->where('agency_id', $agencyId);
        }

        $expensesByType = $expensesByTypeQuery->get()
            ->groupBy(function ($depense) {
                return $depense->typeDepense ? $depense->typeDepense->nom : ($depense->categorie ?: 'Autre');
            })
            ->map(function ($group) {
                return (float) $group->sum('montant');
            });

        // 12. Active Contracts for Real-estate Dashboard (ordered by end date fin ascending)
        $expiringContractsQuery = Contrat::with(['locataire.user', 'logement.batiment'])
            ->where('company_profile_id', $companyId)
            ->where('deleted', false)
            ->where('statut', 'Actif')
            ->orderBy('fin', 'asc')
            ->limit(5);
        if ($agencyId) {
            $expiringContractsQuery->where('agency_id', $agencyId);
        }
        $expiringContracts = $expiringContractsQuery->get()->map(function ($contrat) {
            return [
                'id' => $contrat->id,
                'locataire' => $contrat->locataire && $contrat->locataire->user ? $contrat->locataire->user->name : 'Locataire Inconnu',
                'email' => $contrat->locataire && $contrat->locataire->user ? $contrat->locataire->user->email : '',
                'batiment' => $contrat->logement && $contrat->logement->batiment ? $contrat->logement->batiment->nom : 'Non défini',
                'logement' => $contrat->logement ? $contrat->logement->reference : 'N/A',
                'loyer' => (float) $contrat->loyer,
                'dateEcheance' => $contrat->fin ? $contrat->fin->toDateString() : 'N/A',
                'statut' => $contrat->statut,
            ];
        });

        return response()->json([
            'is_agency' => !empty($agencyId),
            'current_year' => $currentYear,
            'kpis' => [
                'count_agencies_or_employees' => $countAgenciesOrEmployees,
                'count_buildings' => $countBuildings,
                'count_contracts' => $countContracts,
                'count_logements' => $totalLogementsCount,
                'total_revenue' => $totalRevenue,
                'total_expenses' => $totalExpenses,
                
                'revenue_actual' => $revenueActual,
                'revenue_last_month' => $revenueLastMonth,
                'revenue_change_percent' => $revenueChangePercent,
                
                'expenses_actual' => $expensesActual,
                'expenses_last_month' => $expensesLastMonth,
                'expenses_change_percent' => $expensesChangePercent,
                
                'profit_actual' => $revenueActual - $expensesActual,
                'profit_margin' => $revenueActual > 0 ? round((($revenueActual - $expensesActual) / $revenueActual) * 100, 1) : 0.0,
                
                'unpaid_invoices_total' => $unpaidInvoicesTotal,
                'unpaid_invoices_count' => $unpaidInvoicesCount,
                'unpaid_invoices_overdue_count' => $unpaidInvoicesOverdueCount,
                
                'occupancy_rate' => $occupancyRate,
                'vacancy_rate' => $vacancyRate,
                'occupied_count' => $occupiedLogementsCount,
                'vacant_count' => $vacantLogementsCount,
                
                'revenue_expected' => $revenueExpected,
                'unpaid_rate' => $unpaidRate,
                
                'charges_total' => $countContracts * 75,
                'charges_recovered' => ($countContracts * 75) * 0.95,
                'charges_recovery_rate' => $countContracts > 0 ? 95.0 : 0.0,
                
                'cashflow_net' => $cashflowNet,
            ],
            'unpaid_invoices' => $unpaidInvoices,
            'pending_expenses' => $pendingExpenses,
            'recent_transactions' => $recentTransactions,
            'chart_revenue_expenses' => [
                'revenues' => $monthlyRevenues,
                'expenses' => $monthlyExpenses,
            ],
            'chart_expenses_by_type' => $expensesByType,
            'active_contracts' => $expiringContracts,
            'unpaid_period_data' => $unpaidPeriodData,
        ]);
    }
}
