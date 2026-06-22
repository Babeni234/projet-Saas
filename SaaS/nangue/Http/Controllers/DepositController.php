<?php

namespace Nangue\Http\Controllers;

use App\Http\Controllers\Controller;
use Nangue\Models\Deposit;
use Inertia\Inertia;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function index()
    {
        $deposits = Deposit::with(['contract.property'])
            ->whereHas('contract', function ($q) {
                $q->where('landlord_id', auth()->id());
            })
            ->latest()->paginate(15);

        $contracts = auth()->user()->contracts()->with('property')->get();

        return Inertia::render('Landlord/Deposits/Index', [
            'deposits' => $deposits,
            'contracts' => $contracts,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'amount' => 'required|numeric|min:0',
            'received_at' => 'required|date',
        ]);

        $validated['status'] = 'retenu';

        Deposit::create($validated);

        return redirect()->route('landlord.deposits.index')->with('success', 'Caution enregistrée.');
    }

    public function returnDeposit(Request $request, Deposit $deposit)
    {
        $validated = $request->validate([
            'returned_amount' => 'required|numeric|min:0',
            'deduction_notes' => 'nullable|string',
            'deductions' => 'nullable|array',
        ]);

        $validated['status'] = 'restitué';
        $validated['returned_at'] = now();

        $deposit->update($validated);

        return redirect()->back()->with('success', 'Caution restituée.');
    }
}
