<?php

namespace Nangue\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;

class VisitController extends Controller
{
    public function index(): Response
    {
        $todayVisits = [
            [
                'id' => 1,
                'date' => '04/06/2024',
                'time' => '14:00',
                'property_name' => 'Appartement T3 centre-ville',
                'property_address' => '15 Rue de la République, Lyon',
                'visitor_name' => 'Jean Durand',
                'visitor_phone' => '06 12 34 56 78',
                'visitor_avatar' => null,
                'status' => 'scheduled',
                'notes' => 'Première visite',
            ],
            [
                'id' => 2,
                'date' => '04/06/2024',
                'time' => '16:30',
                'property_name' => 'Studio moderne',
                'property_address' => '42 Avenue Jean Jaurès, Lyon',
                'visitor_name' => 'Claire Petit',
                'visitor_phone' => '06 98 76 54 32',
                'visitor_avatar' => null,
                'status' => 'scheduled',
                'notes' => '',
            ],
        ];

        $visits = [
            [
                'id' => 1,
                'date' => '04/06/2024',
                'time' => '14:00',
                'property_name' => 'Appartement T3 centre-ville',
                'property_address' => '15 Rue de la République, Lyon',
                'visitor_name' => 'Jean Durand',
                'visitor_phone' => '06 12 34 56 78',
                'visitor_avatar' => null,
                'status' => 'scheduled',
                'notes' => 'Première visite',
            ],
            [
                'id' => 2,
                'date' => '04/06/2024',
                'time' => '16:30',
                'property_name' => 'Studio moderne',
                'property_address' => '42 Avenue Jean Jaurès, Lyon',
                'visitor_name' => 'Claire Petit',
                'visitor_phone' => '06 98 76 54 32',
                'visitor_avatar' => null,
                'status' => 'scheduled',
                'notes' => '',
            ],
            [
                'id' => 3,
                'date' => '03/06/2024',
                'time' => '10:00',
                'property_name' => 'Maison avec jardin',
                'property_address' => '8 Rue des Fleurs, Villeurbanne',
                'visitor_name' => 'Marc Leroy',
                'visitor_phone' => '06 11 22 33 44',
                'visitor_avatar' => null,
                'status' => 'completed',
                'notes' => 'Visite satisfaisante',
            ],
            [
                'id' => 4,
                'date' => '02/06/2024',
                'time' => '15:00',
                'property_name' => 'Appartement T3 centre-ville',
                'property_address' => '15 Rue de la République, Lyon',
                'visitor_name' => 'Laura Moreau',
                'visitor_phone' => '06 55 66 77 88',
                'visitor_avatar' => null,
                'status' => 'cancelled',
                'notes' => 'Annulée par le visiteur',
            ],
        ];

        return Inertia::render('Nangue/Landlord/VisitsCalendar', [
            'visits' => $visits,
            'todayVisits' => $todayVisits,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Nangue/Landlord/CreateVisit');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'visitor_name' => 'required|string|max:255',
            'visitor_phone' => 'required|string|max:20',
            'visitor_email' => 'nullable|email',
            'date' => 'required|date',
            'time' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        // Logique de stockage de la visite
        // Visit::create($validated);

        return redirect()->route('landlord.calendar.index')->with('success', 'Visite planifiée avec succès');
    }

    public function show($id): Response
    {
        return Inertia::render('Nangue/Landlord/VisitDetail', [
            'visit' => [],
        ]);
    }

    public function edit($id): Response
    {
        return Inertia::render('Nangue/Landlord/EditVisit', [
            'visit' => [],
        ]);
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('landlord.calendar.index')->with('success', 'Visite mise à jour');
    }

    public function cancel($id)
    {
        // Logique d'annulation
        return redirect()->route('landlord.calendar.index')->with('success', 'Visite annulée');
    }

    public function destroy($id)
    {
        return redirect()->route('landlord.calendar.index')->with('success', 'Visite supprimée');
    }
}
