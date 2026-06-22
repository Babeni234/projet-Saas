<?php

namespace Nangue\Http\Controllers;

use App\Http\Controllers\Controller;
use Nangue\Models\Inspection;
use Inertia\Inertia;
use Illuminate\Http\Request;

class InspectionController extends Controller
{
    public function index()
    {
        $inspections = Inspection::with(['property', 'contract', 'tenant'])
            ->where('landlord_id', auth()->id())
            ->latest()->paginate(15);

        return Inertia::render('Nangue/Landlord/Inspections/Index', [
            'inspections' => $inspections,
        ]);
    }

    public function create()
    {
        $properties = auth()->user()->properties;
        $contracts = auth()->user()->contracts()->with('property')->get();

        return Inertia::render('Nangue/Landlord/Inspections/Create', [
            'properties' => $properties,
            'contracts' => $contracts,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'contract_id' => 'required|exists:contracts,id',
            'type' => 'required|string|in:entree,sortie,periodique',
            'inspection_date' => 'required|date',
            'rooms' => 'nullable|array',
        ]);

        $validated['landlord_id'] = auth()->id();
        $validated['status'] = 'planifie';

        Inspection::create($validated);

        return redirect()->route('landlord.inspections.index')->with('success', 'État des lieux créé.');
    }

    public function show(Inspection $inspection)
    {
        $inspection->load(['property', 'contract', 'tenant', 'items']);

        return Inertia::render('Nangue/Landlord/Inspections/Show', [
            'inspection' => $inspection,
        ]);
    }

    public function update(Request $request, Inspection $inspection)
    {
        $validated = $request->validate([
            'status' => 'string|in:planifie,en_cours,termine',
            'notes' => 'nullable|string',
        ]);

        $inspection->update($validated);

        return redirect()->back()->with('success', 'État des lieux mis à jour.');
    }

    public function destroy(Inspection $inspection)
    {
        $inspection->items()->delete();
        $inspection->delete();

        return redirect()->route('landlord.inspections.index')->with('success', 'État des lieux supprimé.');
    }
}
