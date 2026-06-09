<?php

namespace Nangue\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;

class ReceiptController extends Controller
{
    public function index(): Response
    {
        $receipts = [
            [
                'id' => 1,
                'reference' => 'QUI-2024-001',
                'tenant_name' => 'Marie Dupont',
                'property_name' => 'Appartement T3 centre-ville',
                'period' => 'Janvier 2024',
                'rent' => 1200,
                'charges' => 150,
                'total' => 1350,
                'status' => 'paid',
                'payment_date' => '05/01/2024',
            ],
            [
                'id' => 2,
                'reference' => 'QUI-2024-002',
                'tenant_name' => 'Marie Dupont',
                'property_name' => 'Appartement T3 centre-ville',
                'period' => 'Février 2024',
                'rent' => 1200,
                'charges' => 150,
                'total' => 1350,
                'status' => 'paid',
                'payment_date' => '05/02/2024',
            ],
            [
                'id' => 3,
                'reference' => 'QUI-2024-003',
                'tenant_name' => 'Marie Dupont',
                'property_name' => 'Appartement T3 centre-ville',
                'period' => 'Mars 2024',
                'rent' => 1200,
                'charges' => 150,
                'total' => 1350,
                'status' => 'pending',
                'payment_date' => null,
            ],
            [
                'id' => 4,
                'reference' => 'QUI-2024-004',
                'tenant_name' => 'Sophie Bernard',
                'property_name' => 'Maison avec jardin',
                'period' => 'Mars 2024',
                'rent' => 1800,
                'charges' => 200,
                'total' => 2000,
                'status' => 'overdue',
                'payment_date' => null,
            ],
        ];

        $summary = [
            'total_collected' => 2700,
            'collected_change' => 12,
            'pending' => 1350,
            'pending_count' => 1,
            'overdue' => 2000,
            'overdue_count' => 1,
            'payment_rate' => 87,
            'rate_change' => 5,
        ];

        return Inertia::render('Nangue/Landlord/Receipts', [
            'receipts' => $receipts,
            'summary' => $summary,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Nangue/Landlord/CreateReceipt');
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

        // Logique de stockage de la quittance
        // Receipt::create($validated);

        return redirect()->route('landlord.payments.index')->with('success', 'Quittance créée avec succès');
    }

    public function show($id): Response
    {
        return Inertia::render('Nangue/Landlord/ReceiptDetail', [
            'receipt' => [],
        ]);
    }

    public function download($id)
    {
        // Logique de téléchargement PDF
        return back()->with('success', 'PDF téléchargé');
    }

    public function send($id)
    {
        // Logique d'envoi par email
        return back()->with('success', 'Quittance envoyée par email');
    }

    public function export()
    {
        // Logique d'export des quittances
        return back()->with('success', 'Liste des quittances exportée.');
    }
}
