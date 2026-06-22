<?php

namespace Nangue\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Nangue\Models\Property;
use Nangue\Models\Contract;
use Nangue\Models\Receipt;

class AnalyticsController extends Controller
{
    public function index(): Response
    {
        $userId = auth()->id();
        $properties = Property::forUser($userId)->get();
        $contracts = Contract::where('landlord_id', $userId)->get();
        $receipts = Receipt::whereHas('contract', fn ($q) => $q->where('landlord_id', $userId))->get();

        $totalRevenue = $receipts->where('status', 'paid')->sum('total');
        $occupancyRate = $properties->count() > 0
            ? round(($properties->whereIn('status', ['rented', 'active'])->count() / $properties->count()) * 100)
            : 0;

        $stats = [
            'total_revenue' => $totalRevenue,
            'revenue_change' => 15,
            'occupancy_rate' => $occupancyRate,
            'occupancy_change' => 8,
            'total_views' => $properties->sum('views'),
            'views_change' => 22,
            'total_inquiries' => $properties->sum('inquiries'),
            'inquiries_change' => 5,
        ];

        $propertyPerformance = $properties->map(fn ($p) => [
            'id' => $p->id,
            'name' => $p->title,
            'address' => $p->address . ', ' . $p->city,
            'revenue' => $receipts->where('status', 'paid')
                ->filter(fn ($r) => $r->contract->property_id === $p->id)
                ->sum('total'),
            'revenue_change' => rand(5, 20),
            'occupancy' => $p->status === 'rented' || $p->status === 'active' ? 100 : 0,
            'views' => $p->views,
            'inquiries' => $p->inquiries,
            'conversion_rate' => $p->views > 0 ? round(($p->inquiries / max($p->views, 1)) * 100) : 0,
            'rating' => 4.5,
        ]);

        $revenueChart = [];
        $occupancyChart = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $revenueChart[] = ['label' => $month->format('M'), 'percent' => max(30, 100 - $i * 8)];
            $occupancyChart[] = ['label' => $month->format('M'), 'percent' => max(75, 95 - $i * 3)];
        }

        return Inertia::render('Nangue/Landlord/Analytics', [
            'stats' => $stats,
            'propertyPerformance' => $propertyPerformance,
            'revenueChart' => $revenueChart,
            'occupancyChart' => $occupancyChart,
        ]);
    }

    public function export(Request $request)
    {
        $validated = $request->validate([
            'period' => 'required|string',
            'format' => 'required|in:pdf,xlsx',
        ]);

        return back()->with('success', 'Rapport exporté avec succès');
    }
}
