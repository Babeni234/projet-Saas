<?php

namespace App\Http\Controllers\Landlord\Pro;

use App\Http\Controllers\Controller;
use App\Models\AutomationLog;
use App\Models\AutomationRule;
use App\Models\Contract;
use App\Models\Incident;
use App\Models\Portfolio;
use App\Models\Property;
use App\Models\Receipt;
use App\Models\TeamMember;
use App\Models\Visit;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Pro KPIs
        $totalPortfolios = Portfolio::where('user_id', $userId)->count();
        $totalProperties = Property::where('user_id', $userId)->count();
        $activeContracts = Contract::whereHas('property', fn($q) => $q->where('user_id', $userId))
            ->where('status', 'active')->count();

        $monthlyRevenue = Receipt::whereHas('contract.property', fn($q) => $q->where('user_id', $userId))
            ->where('status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->sum('total');

        $yearlyRevenue = Receipt::whereHas('contract.property', fn($q) => $q->where('user_id', $userId))
            ->where('status', 'paid')
            ->whereYear('created_at', now()->year)
            ->sum('total');

        $overdueTotal = Receipt::whereHas('contract.property', fn($q) => $q->where('user_id', $userId))
            ->where('status', 'overdue')
            ->sum('total');

        $visitUpcoming = Visit::where('landlord_id', $userId)
            ->where('date', '>=', now()->format('Y-m-d'))
            ->where('status', 'scheduled')
            ->count();

        $openIncidents = Incident::where('landlord_id', $userId)
            ->whereIn('status', ['reported', 'in_progress'])
            ->count();

        // Team stats
        $teamCount = TeamMember::where('user_id', $userId)->count();

        // Automation stats
        $activeAutomations = AutomationRule::where('user_id', $userId)->where('is_active', true)->count();
        $recentLogs = AutomationLog::whereHas('rule', fn($q) => $q->where('user_id', $userId))
            ->latest('created_at')->take(5)->get();

        // Monthly revenue trend (last 6 months)
        $revenueTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $rev = Receipt::whereHas('contract.property', fn($q) => $q->where('user_id', $userId))
                ->where('status', 'paid')
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->sum('total');
            $revenueTrend[] = [
                'month' => $month->format('M Y'),
                'revenue' => (float) $rev,
            ];
        }

        // Properties by type
        $propertiesByType = Property::where('user_id', $userId)
            ->selectRaw('property_type, count(*) as count')
            ->groupBy('property_type')
            ->pluck('count', 'property_type');

        return Inertia::render('Landlord/Pro/Dashboard', [
            'kpi' => [
                'portfolios' => $totalPortfolios,
                'properties' => $totalProperties,
                'active_contracts' => $activeContracts,
                'monthly_revenue' => (float) $monthlyRevenue,
                'yearly_revenue' => (float) $yearlyRevenue,
                'overdue_total' => (float) $overdueTotal,
                'visits_upcoming' => $visitUpcoming,
                'open_incidents' => $openIncidents,
                'team_members' => $teamCount,
                'active_automations' => $activeAutomations,
            ],
            'revenue_trend' => $revenueTrend,
            'properties_by_type' => $propertiesByType,
            'recent_automation_logs' => $recentLogs,
            'is_pro' => auth()->user()->is_pro,
        ]);
    }
}
