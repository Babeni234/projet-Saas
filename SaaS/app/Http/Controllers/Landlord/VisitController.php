<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Models\Visit;
use App\Models\Property;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VisitController extends Controller
{
    public function index()
    {
        $visits = Visit::with('property')
            ->where('landlord_id', auth()->id())
            ->orderBy('date', 'desc')
            ->paginate(12);

        return Inertia::render('Landlord/Visits/Index', ['visits' => $visits]);
    }

    public function create()
    {
        $properties = Property::where('user_id', auth()->id())->get();
        return Inertia::render('Landlord/Visits/Create', ['properties' => $properties]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'visitor_name' => 'required|string|max:255',
            'visitor_phone' => 'required|string|max:50',
            'visitor_email' => 'nullable|email|max:255',
            'date' => 'required|date',
            'time' => 'required|string',
            'notes' => 'nullable|string',
            'status' => 'required|string',
        ]);

        $property = Property::findOrFail($data['property_id']);
        if ($property->user_id !== auth()->id()) abort(403);

        $data['landlord_id'] = auth()->id();
        Visit::create($data);

        return redirect()->route('landlord.visits.index')
            ->with('success', 'Visite planifiée.');
    }

    public function edit(Visit $visit)
    {
        if ($visit->landlord_id !== auth()->id()) abort(403);
        $properties = Property::where('user_id', auth()->id())->get();
        return Inertia::render('Landlord/Visits/Edit', [
            'visit' => $visit,
            'properties' => $properties,
        ]);
    }

    public function update(Request $request, Visit $visit)
    {
        if ($visit->landlord_id !== auth()->id()) abort(403);

        $data = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'visitor_name' => 'required|string|max:255',
            'visitor_phone' => 'required|string|max:50',
            'visitor_email' => 'nullable|email|max:255',
            'date' => 'required|date',
            'time' => 'required|string',
            'notes' => 'nullable|string',
            'status' => 'required|string',
        ]);

        $visit->update($data);
        return redirect()->route('landlord.visits.index')
            ->with('success', 'Visite mise à jour.');
    }

    public function destroy(Visit $visit)
    {
        if ($visit->landlord_id !== auth()->id()) abort(403);
        $visit->delete();
        return redirect()->route('landlord.visits.index')
            ->with('success', 'Visite annulée.');
    }
}
