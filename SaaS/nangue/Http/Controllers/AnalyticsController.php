<?php

namespace Nangue\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;

class AnalyticsController extends Controller
{
    public function index(): Response
    {
        $stats = [
            'total_revenue' => 45000,
            'revenue_change' => 15,
            'occupancy_rate' => 92,
            'occupancy_change' => 8,
            'total_views' => 12500,
            'views_change' => 22,
            'total_inquiries' => 156,
            'inquiries_change' => 5,
        ];

        $propertyPerformance = [
            [
                'id' => 1,
                'name' => 'Appartement T3 centre-ville',
                'address' => '15 Rue de la République, Lyon',
                'revenue' => 18000,
                'revenue_change' => 12,
                'occupancy' => 95,
                'views' => 4500,
                'inquiries' => 45,
                'conversion_rate' => 10,
                'rating' => 4.8,
            ],
            [
                'id' => 2,
                'name' => 'Studio moderne',
                'address' => '42 Avenue Jean Jaurès, Lyon',
                'revenue' => 12000,
                'revenue_change' => 18,
                'occupancy' => 88,
                'views' => 3800,
                'inquiries' => 38,
                'conversion_rate' => 10,
                'rating' => 4.5,
            ],
            [
                'id' => 3,
                'name' => 'Maison avec jardin',
                'address' => '8 Rue des Fleurs, Villeurbanne',
                'revenue' => 15000,
                'revenue_change' => 20,
                'occupancy' => 92,
                'views' => 4200,
                'inquiries' => 73,
                'conversion_rate' => 17,
                'rating' => 4.9,
            ],
        ];

        $revenueChart = [
            ['label' => 'Jan', 'percent' => 65],
            ['label' => 'Fév', 'percent' => 72],
            ['label' => 'Mar', 'percent' => 78],
            ['label' => 'Avr', 'percent' => 85],
            ['label' => 'Mai', 'percent' => 90],
            ['label' => 'Juin', 'percent' => 95],
        ];

        $occupancyChart = [
            ['label' => 'Jan', 'percent' => 85],
            ['label' => 'Fév', 'percent' => 88],
            ['label' => 'Mar', 'percent' => 90],
            ['label' => 'Avr', 'percent' => 92],
            ['label' => 'Mai', 'percent' => 91],
            ['label' => 'Juin', 'percent' => 92],
        ];

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

        // Logique d'export
        return back()->with('success', 'Rapport exporté avec succès');
    }
}
