<?php

namespace Nangue\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Nangue\Models\Contract;
use Nangue\Models\Tenant;
use Nangue\Models\Property;

class ContractController extends Controller
{
    public function index(): Response
    {
        $contracts = Contract::where('landlord_id', auth()->id())
            ->with(['tenant', 'property'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn ($c) => [
                'id' => $c->id,
                'tenant_name' => $c->tenant->name,
                'tenant_email' => $c->tenant->email ?? '',
                'tenant_avatar' => null,
                'property_name' => $c->property->title,
                'property_address' => $c->property->address . ', ' . $c->property->city,
                'lease_type' => $c->lease_type,
                'duration' => $c->duration,
                'rent' => $c->rent,
                'charges' => $c->charges,
                'status' => $c->status,
                'start_date' => $c->start_date->format('d/m/Y'),
                'end_date' => $c->end_date->format('d/m/Y'),
            ]);

        return Inertia::render('Nangue/Landlord/Contracts', [
            'contracts' => $contracts,
        ]);
    }

    public function create(): Response
    {
        $tenants = Tenant::orderBy('name')->get();
        $properties = Property::forUser(auth()->id())->active()->get();

        return Inertia::render('Nangue/Landlord/CreateContract', [
            'tenants' => $tenants,
            'properties' => $properties,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'property_id' => 'required|exists:properties,id',
            'lease_type' => 'required|string',
            'duration' => 'required|string',
            'rent' => 'required|numeric|min:0',
            'charges' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $validated['landlord_id'] = auth()->id();
        $validated['status'] = 'pending';

        Contract::create($validated);

        return redirect()->route('landlord.contracts.index')->with('success', 'Contrat créé avec succès');
    }

    public function show($id): Response
    {
        $contract = Contract::where('landlord_id', auth()->id())
            ->with(['tenant', 'property'])
            ->findOrFail($id);

        return Inertia::render('Nangue/Landlord/ContractDetail', [
            'contract' => [
                'id' => $contract->id,
                'tenant_name' => $contract->tenant->name,
                'tenant_email' => $contract->tenant->email,
                'property_name' => $contract->property->title,
                'property_address' => $contract->property->address,
                'lease_type' => $contract->lease_type,
                'duration' => $contract->duration,
                'rent' => $contract->rent,
                'charges' => $contract->charges,
                'deposit' => $contract->deposit,
                'status' => $contract->status,
                'start_date' => $contract->start_date->format('d/m/Y'),
                'end_date' => $contract->end_date->format('d/m/Y'),
            ],
        ]);
    }

    public function edit($id): Response
    {
        $contract = Contract::where('landlord_id', auth()->id())
            ->with(['tenant', 'property'])
            ->findOrFail($id);

        $tenants = Tenant::orderBy('name')->get();
        $properties = Property::forUser(auth()->id())->get();

        return Inertia::render('Nangue/Landlord/EditContract', [
            'contract' => $contract,
            'tenants' => $tenants,
            'properties' => $properties,
        ]);
    }

    public function update(Request $request, $id)
    {
        $contract = Contract::where('landlord_id', auth()->id())->findOrFail($id);

        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'property_id' => 'required|exists:properties,id',
            'lease_type' => 'required|string',
            'duration' => 'required|string',
            'rent' => 'required|numeric|min:0',
            'charges' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $contract->update($validated);

        return redirect()->route('landlord.contracts.index')->with('success', 'Contrat mis à jour');
    }

    public function destroy($id)
    {
        $contract = Contract::where('landlord_id', auth()->id())->findOrFail($id);
        $contract->delete();

        return redirect()->route('landlord.contracts.index')->with('success', 'Contrat supprimé');
    }
}
