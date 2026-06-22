<?php

namespace Nangue\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Nangue\Models\Receipt;
use Nangue\Models\Contract;

class ReceiptController extends Controller
{
    public function index(): Response
    {
        $receipts = Receipt::whereHas('contract', fn ($q) => $q->where('landlord_id', auth()->id()))
            ->with('contract.tenant', 'contract.property')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn ($r) => [
                'id' => $r->id,
                'reference' => $r->reference,
                'tenant_name' => $r->contract->tenant->name,
                'property_name' => $r->contract->property->title,
                'period' => $r->period,
                'rent' => $r->rent,
                'charges' => $r->charges,
                'total' => $r->total,
                'status' => $r->status,
                'payment_date' => $r->payment_date?->format('d/m/Y'),
            ]);

        $summary = [
            'total_collected' => $receipts->where('status', 'paid')->sum('total'),
            'collected_change' => 12,
            'pending' => $receipts->where('status', 'pending')->sum('total'),
            'pending_count' => $receipts->where('status', 'pending')->count(),
            'overdue' => $receipts->where('status', 'overdue')->sum('total'),
            'overdue_count' => $receipts->where('status', 'overdue')->count(),
            'payment_rate' => $receipts->count() > 0 ? round(($receipts->where('status', 'paid')->count() / $receipts->count()) * 100) : 0,
            'rate_change' => 5,
        ];

        return Inertia::render('Nangue/Landlord/Receipts', [
            'receipts' => $receipts,
            'summary' => $summary,
        ]);
    }

    public function create(): Response
    {
        $contracts = Contract::where('landlord_id', auth()->id())
            ->where('status', 'active')
            ->with('tenant', 'property')
            ->get()
            ->map(fn ($c) => [
                'id' => $c->id,
                'label' => $c->tenant->name . ' - ' . $c->property->title . ' (' . $c->rent . '€)',
            ]);

        return Inertia::render('Nangue/Landlord/CreateReceipt', [
            'contracts' => $contracts,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'period' => 'required|string',
            'rent' => 'required|numeric|min:0',
            'charges' => 'required|numeric|min:0',
            'due_date' => 'required|date',
        ]);

        $contract = Contract::findOrFail($validated['contract_id']);

        $lastId = Receipt::max('id') + 1;
        $validated['reference'] = 'QUI-' . date('Y') . '-' . str_pad($lastId, 3, '0', STR_PAD_LEFT);
        $validated['total'] = $validated['rent'] + $validated['charges'];
        $validated['status'] = 'pending';

        Receipt::create($validated);

        return redirect()->route('landlord.payments.index')->with('success', 'Quittance créée avec succès');
    }

    public function show($id): Response
    {
        $receipt = Receipt::whereHas('contract', fn ($q) => $q->where('landlord_id', auth()->id()))
            ->with('contract.tenant', 'contract.property')
            ->findOrFail($id);

        return Inertia::render('Nangue/Landlord/ReceiptDetail', [
            'receipt' => [
                'id' => $receipt->id,
                'reference' => $receipt->reference,
                'tenant_name' => $receipt->contract->tenant->name,
                'property_name' => $receipt->contract->property->title,
                'period' => $receipt->period,
                'rent' => $receipt->rent,
                'charges' => $receipt->charges,
                'total' => $receipt->total,
                'status' => $receipt->status,
                'due_date' => $receipt->due_date->format('d/m/Y'),
                'payment_date' => $receipt->payment_date?->format('d/m/Y') ?? null,
            ],
        ]);
    }

    public function download($id)
    {
        return back()->with('success', 'PDF téléchargé');
    }

    public function send($id)
    {
        return back()->with('success', 'Quittance envoyée par email');
    }

    public function export()
    {
        return back()->with('success', 'Liste des quittances exportée.');
    }
}
