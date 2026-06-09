<?php

namespace Nangue\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;

class FavoriteController extends Controller
{
    public function index(): Response
    {
        $favorites = [
            [
                'id' => 1,
                'title' => 'Appartement T4 avec balcon - Quartier Croix-Rousse',
                'address' => '25 Rue Montée de la Grande Côte',
                'city' => 'Lyon',
                'price' => 1450,
                'surface' => 90,
                'rooms' => 4,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'property_type' => 'apartment',
                'transaction_type' => 'rent',
                'image' => null,
                'badge' => 'Nouveau',
                'url' => route('immo.property.show', 1),
                'added_at' => 'il y a 2 jours',
                'views' => 156,
            ],
            [
                'id' => 2,
                'title' => 'Loft atypique avec terrasse',
                'address' => '8 Rue de la Martinière',
                'city' => 'Lyon',
                'price' => 2200,
                'surface' => 110,
                'rooms' => 3,
                'bedrooms' => 2,
                'bathrooms' => 2,
                'property_type' => 'loft',
                'transaction_type' => 'rent',
                'image' => null,
                'badge' => 'Populaire',
                'url' => route('immo.property.show', 2),
                'added_at' => 'il y a 5 jours',
                'views' => 423,
            ],
            [
                'id' => 3,
                'title' => 'Studio meublé proche université',
                'address' => '12 Avenue Rockefeller',
                'city' => 'Lyon',
                'price' => 750,
                'surface' => 25,
                'rooms' => 1,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'property_type' => 'studio',
                'transaction_type' => 'rent',
                'image' => null,
                'badge' => 'Récent',
                'url' => route('immo.property.show', 3),
                'added_at' => 'il y a 1 semaine',
                'views' => 89,
            ],
            [
                'id' => 4,
                'title' => 'Villa contemporaine avec piscine',
                'address' => '45 Chemin des Pins',
                'city' => 'Saint-Fons',
                'price' => 3500,
                'surface' => 180,
                'rooms' => 6,
                'bedrooms' => 4,
                'bathrooms' => 3,
                'property_type' => 'villa',
                'transaction_type' => 'rent',
                'image' => null,
                'badge' => null,
                'url' => route('immo.property.show', 4),
                'added_at' => 'il y a 2 semaines',
                'views' => 234,
            ],
        ];

        return Inertia::render('Nangue/User/Favorites', [
            'favorites' => $favorites,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
        ]);

        // Logique d'ajout aux favoris
        // Favorite::firstOrCreate([
        //     'user_id' => auth()->id(),
        //     'property_id' => $validated['property_id'],
        // ]);

        return back();
    }

    public function destroy($id)
    {
        // Logique de suppression des favoris
        // Favorite::where('user_id', auth()->id())
        //     ->where('property_id', $id)
        //     ->delete();

        return back();
    }

    public function export()
    {
        // Logique d'export des favoris
        return back()->with('success', 'Export généré avec succès');
    }
}
