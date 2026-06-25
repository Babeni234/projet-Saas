<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Property;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContractController extends Controller
{
    public function index()
    {
        $contracts = Contract::with(['property', 'tenant'])
            ->whereHas('property', fn($q) => $q->where('user_id', auth()->id()))
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return Inertia::render('Landlord/Contracts/Index', [
            'contracts' => $contracts,
        ]);
    }

    public function create()
    {
        $properties = Property::where('user_id', auth()->id())->where('status', '!=', 'rented')->get();
        $tenants = Tenant::where('user_id', auth()->id())->get();

        return Inertia::render('Landlord/Contracts/Create', [
            'properties' => $properties,
            'tenants' => $tenants,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'tenant_id' => 'required|exists:tenants,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'rent_amount' => 'required|numeric|min:0',
            'charges' => 'nullable|numeric|min:0',
            'deposit' => 'nullable|numeric|min:0',
            'status' => 'required|string',
        ]);

        $property = Property::findOrFail($data['property_id']);
        if ($property->user_id !== auth()->id()) abort(403);

        $contract = Contract::create($data);

        $property->update(['status' => 'rented']);

        return redirect()->route('landlord.contracts.index')
            ->with('success', 'Contrat créé avec succès.');
    }

    public function show(Contract $contract)
    {
        if ($contract->property->user_id !== auth()->id()) abort(403);
        $contract->load(['property', 'tenant']);
        return Inertia::render('Landlord/Contracts/Show', ['contract' => $contract]);
    }

    public function edit(Contract $contract)
    {
        if ($contract->property->user_id !== auth()->id()) abort(403);
        $properties = Property::where('user_id', auth()->id())->get();
        $tenants = Tenant::where('user_id', auth()->id())->get();
        $contract->load(['property', 'tenant']);

        return Inertia::render('Landlord/Contracts/Edit', [
            'contract' => $contract,
            'properties' => $properties,
            'tenants' => $tenants,
        ]);
    }

    public function update(Request $request, Contract $contract)
    {
        if ($contract->property->user_id !== auth()->id()) abort(403);

        $data = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'tenant_id' => 'required|exists:tenants,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'rent_amount' => 'required|numeric|min:0',
            'charges' => 'nullable|numeric|min:0',
            'deposit' => 'nullable|numeric|min:0',
            'status' => 'required|string',
        ]);

        $contract->update($data);

        return redirect()->route('landlord.contracts.index')
            ->with('success', 'Contrat mis à jour.');
    }

    public function destroy(Contract $contract)
    {
        if ($contract->property->user_id !== auth()->id()) abort(403);

        $contract->property->update(['status' => 'active']);
        $contract->delete();

        return redirect()->route('landlord.contracts.index')
            ->with('success', 'Contrat résilié.');
    }
}
