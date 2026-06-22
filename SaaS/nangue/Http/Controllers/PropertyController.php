<?php

namespace Nangue\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Nangue\Models\Property;

class PropertyController extends Controller
{
    public function index(): Response
    {
        $properties = Property::forUser(auth()->id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'title' => $p->title,
                'address' => $p->address,
                'city' => $p->city,
                'price' => $p->price,
                'surface' => $p->surface,
                'rooms' => $p->rooms,
                'bedrooms' => $p->bedrooms,
                'bathrooms' => $p->bathrooms,
                'property_type' => $p->property_type,
                'transaction_type' => $p->transaction_type,
                'status' => $p->status,
                'image' => $p->images[0] ?? null,
                'views' => $p->views,
                'inquiries' => $p->inquiries,
                'created_at' => $p->created_at->format('d M Y'),
            ]);

        return Inertia::render('Nangue/User/Properties', [
            'properties' => $properties,
            'filters' => [],
        ]);
    }

    public function publications(): Response
    {
        $properties = Property::forUser(auth()->id())
            ->whereIn('status', ['active', 'pending'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'title' => $p->title,
                'address' => $p->address,
                'city' => $p->city,
                'price' => $p->price,
                'surface' => $p->surface,
                'rooms' => $p->rooms,
                'bedrooms' => $p->bedrooms,
                'bathrooms' => $p->bathrooms,
                'property_type' => $p->property_type,
                'transaction_type' => $p->transaction_type,
                'status' => $p->status,
                'image' => $p->images[0] ?? null,
                'views' => $p->views,
                'inquiries' => $p->inquiries,
                'created_at' => $p->created_at->format('d M Y'),
            ]);

        return Inertia::render('Nangue/User/Publications', [
            'properties' => $properties,
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

        $validated['user_id'] = auth()->id();
        $validated['status'] = $validated['status'] ?? 'draft';
        $validated['reference'] = 'ANN-' . date('Y') . '-' . str_pad(Property::max('id') + 1, 4, '0', STR_PAD_LEFT);
        $validated['furnished'] = $request->boolean('furnished');
        $validated['charges_included'] = $request->boolean('charges_included');

        Property::create($validated);

        return redirect()->route('immo.properties')->with('success', 'Annonce créée avec succès');
    }

    public function show($id): Response
    {
        $property = Property::forUser(auth()->id())->findOrFail($id);

        return Inertia::render('Nangue/User/PropertyDetail', [
            'property' => $property,
        ]);
    }

    public function edit($id): Response
    {
        $property = Property::forUser(auth()->id())->findOrFail($id);

        return Inertia::render('Nangue/User/EditProperty', [
            'property' => $property,
        ]);
    }

    public function update(Request $request, $id)
    {
        $property = Property::forUser(auth()->id())->findOrFail($id);

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
            'status' => 'required|string',
        ]);

        $property->update($validated);

        return redirect()->route('immo.properties')->with('success', 'Bien mis à jour');
    }

    public function destroy($id)
    {
        $property = Property::forUser(auth()->id())->findOrFail($id);
        $property->delete();

        return redirect()->route('immo.properties')->with('success', 'Bien supprimé');
    }
}
