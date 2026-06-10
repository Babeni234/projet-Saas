<?php

namespace Nangue\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;

class ContractController extends Controller
{
    public function index(): Response
    {
        $contracts = [
            [
                'id' => 1,
                'tenant_name' => 'Marie Dupont',
                'tenant_email' => 'marie.dupont@email.com',
                'tenant_avatar' => null,
                'property_name' => 'Appartement T3 centre-ville',
                'property_address' => '15 Rue de la République, Lyon',
                'lease_type' => 'Bail habitation',
                'duration' => '12 mois',
                'rent' => 1200,
                'charges' => 150,
                'status' => 'active',
                'start_date' => '01/01/2024',
                'end_date' => '31/12/2024',
            ],
            [
                'id' => 2,
                'tenant_name' => 'Pierre Martin',
                'tenant_email' => 'pierre.martin@email.com',
                'tenant_avatar' => null,
                'property_name' => 'Studio moderne',
                'property_address' => '42 Avenue Jean Jaurès, Lyon',
                'lease_type' => 'Bail meublé',
                'duration' => '6 mois',
                'rent' => 850,
                'charges' => 100,
                'status' => 'pending',
                'start_date' => '15/06/2024',
                'end_date' => '15/12/2024',
            ],
            [
                'id' => 3,
                'tenant_name' => 'Sophie Bernard',
                'tenant_email' => 'sophie.bernard@email.com',
                'tenant_avatar' => null,
                'property_name' => 'Maison avec jardin',
                'property_address' => '8 Rue des Fleurs, Villeurbanne',
                'lease_type' => 'Bail habitation',
                'duration' => '24 mois',
                'rent' => 1800,
                'charges' => 200,
                'status' => 'active',
                'start_date' => '01/03/2024',
                'end_date' => '28/02/2026',
            ],
        ];

        return Inertia::render('Nangue/Landlord/Contracts', [
            'contracts' => $contracts,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Nangue/Landlord/CreateContract');
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

        // Logique de stockage du contrat
        // Contract::create($validated);

        return redirect()->route('landlord.contracts.index')->with('success', 'Contrat créé avec succès');
    }

    public function show($id): Response
    {
        return Inertia::render('Nangue/Landlord/ContractDetail', [
            'contract' => [],
        ]);
    }

    public function edit($id): Response
    {
        return Inertia::render('Nangue/Landlord/EditContract', [
            'contract' => [],
        ]);
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('landlord.contracts.index')->with('success', 'Contrat mis à jour');
    }

    public function destroy($id)
    {
        return redirect()->route('landlord.contracts.index')->with('success', 'Contrat supprimé');
    }
}
