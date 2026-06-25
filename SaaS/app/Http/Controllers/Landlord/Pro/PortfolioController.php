<?php

namespace App\Http\Controllers\Landlord\Pro;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\Property;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::where('user_id', auth()->id())
            ->withCount('properties')
            ->orderBy('name')
            ->get();

        return Inertia::render('Landlord/Pro/Portfolios/Index', [
            'portfolios' => $portfolios,
        ]);
    }

    public function create()
    {
        $properties = Property::where('user_id', auth()->id())
            ->select('id', 'title', 'city', 'property_type')
            ->orderBy('title')
            ->get();

        return Inertia::render('Landlord/Pro/Portfolios/Create', [
            'properties' => $properties,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:50',
            'property_ids' => 'nullable|array',
            'property_ids.*' => 'exists:properties,id',
        ]);

        $data['user_id'] = auth()->id();
        $data['color'] = $data['color'] ?? '#6366f1';

        $portfolio = Portfolio::create($data);

        if (!empty($data['property_ids'])) {
            $portfolio->properties()->sync($data['property_ids']);
        }

        return redirect()->route('landlord.pro.portfolios.index')
            ->with('success', 'Portfolio créé avec succès.');
    }

    public function show(Portfolio $portfolio)
    {
        if ($portfolio->user_id !== auth()->id()) abort(403);

        $portfolio->load(['properties' => function ($q) {
            $q->withCount(['contracts as active_contracts' => fn($q) => $q->where('status', 'active')]);
        }]);

        $stats = [
            'total_properties' => $portfolio->properties->count(),
            'rented' => $portfolio->properties->filter(fn($p) => $p->active_contracts > 0)->count(),
            'available' => $portfolio->properties->filter(fn($p) => $p->active_contracts === 0)->count(),
            'monthly_revenue' => $portfolio->properties->sum('price'),
        ];

        return Inertia::render('Landlord/Pro/Portfolios/Show', [
            'portfolio' => $portfolio,
            'stats' => $stats,
        ]);
    }

    public function edit(Portfolio $portfolio)
    {
        if ($portfolio->user_id !== auth()->id()) abort(403);

        $properties = Property::where('user_id', auth()->id())
            ->select('id', 'title', 'city', 'property_type')
            ->orderBy('title')
            ->get();

        $selectedIds = $portfolio->properties->pluck('id');

        return Inertia::render('Landlord/Pro/Portfolios/Create', [
            'portfolio' => $portfolio,
            'properties' => $properties,
            'selected_ids' => $selectedIds,
        ]);
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        if ($portfolio->user_id !== auth()->id()) abort(403);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:50',
            'property_ids' => 'nullable|array',
            'property_ids.*' => 'exists:properties,id',
        ]);

        $portfolio->update($data);

        if (isset($data['property_ids'])) {
            $portfolio->properties()->sync($data['property_ids']);
        }

        return redirect()->route('landlord.pro.portfolios.index')
            ->with('success', 'Portfolio mis à jour.');
    }

    public function destroy(Portfolio $portfolio)
    {
        if ($portfolio->user_id !== auth()->id()) abort(403);

        $portfolio->properties()->detach();
        $portfolio->delete();

        return redirect()->route('landlord.pro.portfolios.index')
            ->with('success', 'Portfolio supprimé.');
    }
}
