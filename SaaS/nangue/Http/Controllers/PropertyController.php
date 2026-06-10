<?php

namespace Nangue\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;

class PropertyController extends Controller
{
    protected function demoProperties(): array
    {
        return [
            [
                'id' => 1,
                'title' => 'Appartement T3 lumineux centre-ville',
                'address' => '15 Rue de la République',
                'city' => 'Lyon',
                'price' => 1200,
                'surface' => 75,
                'rooms' => 3,
                'bedrooms' => 2,
                'bathrooms' => 1,
                'property_type' => 'apartment',
                'transaction_type' => 'rent',
                'status' => 'active',
                'image' => null,
                'views' => 234,
                'inquiries' => 12,
                'created_at' => '15 mai 2024',
            ],
            [
                'id' => 2,
                'title' => 'Studio moderne proche métro',
                'address' => '42 Avenue Jean Jaurès',
                'city' => 'Lyon',
                'price' => 850,
                'surface' => 30,
                'rooms' => 1,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'property_type' => 'studio',
                'transaction_type' => 'rent',
                'status' => 'rented',
                'image' => null,
                'views' => 456,
                'inquiries' => 28,
                'created_at' => '2 avril 2024',
            ],
            [
                'id' => 3,
                'title' => 'Maison avec jardin',
                'address' => '8 Rue des Fleurs',
                'city' => 'Villeurbanne',
                'price' => 1800,
                'surface' => 120,
                'rooms' => 5,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'property_type' => 'house',
                'transaction_type' => 'rent',
                'status' => 'pending',
                'image' => null,
                'views' => 89,
                'inquiries' => 5,
                'created_at' => '1 juin 2024',
            ],
        ];
    }

    public function index(): Response
    {
        return Inertia::render('Nangue/User/Properties', [
            'properties' => $this->demoProperties(),
            'filters' => [],
        ]);
    }

    public function publications(): Response
    {
        return Inertia::render('Nangue/User/Publications', [
            'properties' => $this->demoProperties(),
            'filters' => [],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Nangue/User/CreateProperty');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'property_type' => 'required|in:apartment,house,studio,loft,villa',
            'transaction_type' => 'required|in:rent,sale,both',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'price' => 'required|numeric|min:0',
            'surface' => 'required|numeric|min:0',
            'rooms' => 'required|integer|min:1',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'furnished' => 'boolean',
            'amenities' => 'array',
            'images' => 'array',
            'available_from' => 'nullable|date',
            'charges_included' => 'boolean',
            'deposit' => 'nullable|numeric|min:0',
            'min_lease_duration' => 'nullable|integer|min:1',
        ]);

        // Logique de stockage du bien
        // Property::create($validated);

        return redirect()->route('immo.properties')->with('success', 'Annonce créée avec succès');
    }

    public function show($id): Response
    {
        // Logique pour afficher un bien spécifique
        return Inertia::render('Nangue/User/PropertyDetail', [
            'property' => [],
        ]);
    }

    public function edit($id): Response
    {
        // Logique pour éditer un bien
        return Inertia::render('Nangue/User/EditProperty', [
            'property' => [],
        ]);
    }

    public function update(Request $request, $id)
    {
        // Logique de mise à jour
        return redirect()->route('immo.properties')->with('success', 'Bien mis à jour');
    }

    public function destroy($id)
    {
        // Logique de suppression
        return redirect()->route('immo.properties')->with('success', 'Bien supprimé');
    }
}
