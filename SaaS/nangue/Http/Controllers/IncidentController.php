<?php

namespace Nangue\Http\Controllers;

use App\Http\Controllers\Controller;
use Nangue\Models\Incident;
use Inertia\Inertia;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    public function index()
    {
        $incidents = Incident::with(['property', 'tenant', 'contract', 'assignee'])
            ->where('landlord_id', auth()->id())
            ->latest()->paginate(15);

        return Inertia::render('Nangue/Landlord/Incidents/Index', [
            'incidents' => $incidents,
        ]);
    }

    public function create()
    {
        $properties = auth()->user()->properties;
        $tenants = auth()->user()->tenants;

        return Inertia::render('Nangue/Landlord/Incidents/Create', [
            'properties' => $properties,
            'tenants' => $tenants,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'contract_id' => 'nullable|exists:contracts,id',
            'tenant_id' => 'nullable|exists:tenants,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|in:plomberie,electricite,chauffage,toiture,fenetres,murs,sols,autre',
            'urgency' => 'required|string|in:basse,moyenne,haute,urgente',
        ]);

        $validated['landlord_id'] = auth()->id();
        $validated['status'] = 'ouvert';

        Incident::create($validated);

        return redirect()->route('landlord.incidents.index')->with('success', 'Incident créé avec succès.');
    }

    public function show(Incident $incident)
    {
        $incident->load(['property', 'tenant', 'contract', 'assignee', 'comments.user']);

        return Inertia::render('Nangue/Landlord/Incidents/Show', [
            'incident' => $incident,
        ]);
    }

    public function update(Request $request, Incident $incident)
    {
        $validated = $request->validate([
            'status' => 'string|in:ouvert,en_cours,resolu,ferme',
            'resolution_notes' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'cost' => 'nullable|numeric|min:0',
        ]);

        if (($validated['status'] ?? null) === 'resolu') {
            $validated['resolved_at'] = now();
        }

        $incident->update($validated);

        return redirect()->back()->with('success', 'Incident mis à jour.');
    }

    public function destroy(Incident $incident)
    {
        $incident->delete();

        return redirect()->route('landlord.incidents.index')->with('success', 'Incident supprimé.');
    }
}
