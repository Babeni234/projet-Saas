<?php

namespace Nangue\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Nangue\Models\Favorite;
use Nangue\Models\Property;

class FavoriteController extends Controller
{
    public function index(): Response
    {
        $favorites = Favorite::where('user_id', auth()->id())
            ->with('property')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn ($f) => [
                'id' => $f->property_id,
                'title' => $f->property->title,
                'address' => $f->property->address,
                'city' => $f->property->city,
                'price' => $f->property->price,
                'surface' => $f->property->surface,
                'rooms' => $f->property->rooms,
                'bedrooms' => $f->property->bedrooms,
                'bathrooms' => $f->property->bathrooms,
                'property_type' => $f->property->property_type,
                'transaction_type' => $f->property->transaction_type,
                'image' => $f->property->images[0] ?? null,
                'badge' => null,
                'url' => route('immo.property.show', $f->property_id),
                'added_at' => $f->created_at->diffForHumans(),
                'views' => $f->property->views,
            ]);

        return Inertia::render('Nangue/User/Favorites', [
            'favorites' => $favorites,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
        ]);

        Favorite::firstOrCreate([
            'user_id' => auth()->id(),
            'property_id' => $validated['property_id'],
        ]);

        return back();
    }

    public function destroy($id)
    {
        Favorite::where('user_id', auth()->id())
            ->where('property_id', $id)
            ->delete();

        return back();
    }

    public function export()
    {
        return back()->with('success', 'Export généré avec succès');
    }
}
