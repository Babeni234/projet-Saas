<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Models\Receipt;
use App\Models\Contract;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReceiptController extends Controller
{
    public function index()
    {
        $receipts = Receipt::with('contract.property', 'contract.tenant')
            ->whereHas('contract.property', fn($q) => $q->where('user_id', auth()->id()))
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return Inertia::render('Landlord/Receipts/Index', ['receipts' => $receipts]);
    }

    public function create()
    {
        $contracts = Contract::with('property', 'tenant')
            ->whereHas('property', fn($q) => $q->where('user_id', auth()->id()))
            ->where('status', 'active')
            ->get();

        return Inertia::render('Landlord/Receipts/Create', ['contracts' => $contracts]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'period' => 'required|string|max:50',
            'rent' => 'required|numeric|min:0',
            'charges' => 'nullable|numeric|min:0',
            'due_date' => 'required|date',
            'status' => 'required|string',
        ]);

        $contract = Contract::findOrFail($data['contract_id']);
        if ($contract->property->user_id !== auth()->id()) abort(403);

        $data['charges'] ??= 0;
        $data['total'] = $data['rent'] + $data['charges'];
        $data['reference'] = 'QUIT-' . strtoupper(\Illuminate\Support\Str::random(10));

        Receipt::create($data);

        return redirect()->route('landlord.receipts.index')
            ->with('success', 'Quittance créée.');
    }

    public function edit(Receipt $receipt)
    {
        if ($receipt->contract->property->user_id !== auth()->id()) abort(403);

        $contracts = Contract::with('property', 'tenant')
            ->whereHas('property', fn($q) => $q->where('user_id', auth()->id()))
            ->get();

        return Inertia::render('Landlord/Receipts/Edit', [
            'receipt' => $receipt->load('contract.property', 'contract.tenant'),
            'contracts' => $contracts,
        ]);
    }

    public function update(Request $request, Receipt $receipt)
    {
        if ($receipt->contract->property->user_id !== auth()->id()) abort(403);

        $data = $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'period' => 'required|string|max:50',
            'rent' => 'required|numeric|min:0',
            'charges' => 'nullable|numeric|min:0',
            'due_date' => 'required|date',
            'payment_date' => 'nullable|date',
            'status' => 'required|string',
        ]);

        $data['charges'] ??= 0;
        $data['total'] = $data['rent'] + $data['charges'];
        $receipt->update($data);

        return redirect()->route('landlord.receipts.index')
            ->with('success', 'Quittance mise à jour.');
    }

    public function destroy(Receipt $receipt)
    {
        if ($receipt->contract->property->user_id !== auth()->id()) abort(403);
        $receipt->delete();
        return redirect()->route('landlord.receipts.index')
            ->with('success', 'Quittance supprimée.');
    }
}
