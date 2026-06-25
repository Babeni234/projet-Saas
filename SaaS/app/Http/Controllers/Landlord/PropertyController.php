<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return Inertia::render('Landlord/Properties/Index', [
            'properties' => $properties,
        ]);
    }

    public function create()
    {
        return Inertia::render('Landlord/Properties/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'property_type' => 'required|string',
            'transaction_type' => 'required|string',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'price' => 'required|numeric|min:0',
            'surface' => 'nullable|numeric|min:0',
            'rooms' => 'required|integer|min:0',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'furnished' => 'boolean',
            'amenities' => 'nullable|array',
            'available_from' => 'nullable|date',
            'charges_included' => 'boolean',
            'deposit' => 'nullable|numeric|min:0',
            'min_lease_duration' => 'nullable|integer|min:0',
            'status' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $data['user_id'] = auth()->id();
        $data['reference'] = 'IMMO-' . strtoupper(\Illuminate\Support\Str::random(8));

        Property::create($data);

        return redirect()->route('landlord.properties.index')
            ->with('success', 'Bien créé avec succès.');
    }

    public function show(Property $property)
    {
        if ($property->user_id !== auth()->id()) {
            abort(403);
        }

        return Inertia::render('Landlord/Properties/Show', [
            'property' => $property,
        ]);
    }

    public function edit(Property $property)
    {
        if ($property->user_id !== auth()->id()) {
            abort(403);
        }

        return Inertia::render('Landlord/Properties/Edit', [
            'property' => $property,
        ]);
    }

    public function update(Request $request, Property $property)
    {
        if ($property->user_id !== auth()->id()) {
            abort(403);
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'property_type' => 'required|string',
            'transaction_type' => 'required|string',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'price' => 'required|numeric|min:0',
            'surface' => 'nullable|numeric|min:0',
            'rooms' => 'required|integer|min:0',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'furnished' => 'boolean',
            'amenities' => 'nullable|array',
            'available_from' => 'nullable|date',
            'charges_included' => 'boolean',
            'deposit' => 'nullable|numeric|min:0',
            'min_lease_duration' => 'nullable|integer|min:0',
            'status' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $property->update($data);

        return redirect()->route('landlord.properties.index')
            ->with('success', 'Bien mis à jour avec succès.');
    }

    public function destroy(Property $property)
    {
        if ($property->user_id !== auth()->id()) {
            abort(403);
        }

        $property->delete();

        return redirect()->route('landlord.properties.index')
            ->with('success', 'Bien supprimé avec succès.');
    }
}
