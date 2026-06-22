<?php

namespace Nangue\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Nangue\Models\Visit;
use Nangue\Models\Property;

class VisitController extends Controller
{
    public function index(): Response
    {
        $visits = Visit::where('landlord_id', auth()->id())
            ->with('property')
            ->orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->get()
            ->map(fn ($v) => [
                'id' => $v->id,
                'date' => $v->date->format('d/m/Y'),
                'time' => $v->time,
                'property_name' => $v->property->title,
                'property_address' => $v->property->address . ', ' . $v->property->city,
                'visitor_name' => $v->visitor_name,
                'visitor_phone' => $v->visitor_phone,
                'visitor_avatar' => null,
                'status' => $v->status,
                'notes' => $v->notes ?? '',
            ]);

        $todayVisits = $visits->filter(fn ($v) => $v['date'] === now()->format('d/m/Y'))->values();

        return Inertia::render('Nangue/Landlord/VisitsCalendar', [
            'visits' => $visits,
            'todayVisits' => $todayVisits,
        ]);
    }

    public function create(): Response
    {
        $properties = Property::forUser(auth()->id())->active()->get();

        return Inertia::render('Nangue/Landlord/CreateVisit', [
            'properties' => $properties,
        ]);
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

        $validated['landlord_id'] = auth()->id();
        $validated['status'] = 'scheduled';

        Visit::create($validated);

        return redirect()->route('landlord.calendar.index')->with('success', 'Visite planifiée avec succès');
    }

    public function show($id): Response
    {
        $visit = Visit::where('landlord_id', auth()->id())
            ->with('property')
            ->findOrFail($id);

        return Inertia::render('Nangue/Landlord/VisitDetail', [
            'visit' => [
                'id' => $visit->id,
                'property_name' => $visit->property->title,
                'property_address' => $visit->property->address . ', ' . $visit->property->city,
                'visitor_name' => $visit->visitor_name,
                'visitor_phone' => $visit->visitor_phone,
                'visitor_email' => $visit->visitor_email,
                'date' => $visit->date->format('d/m/Y'),
                'time' => $visit->time,
                'status' => $visit->status,
                'notes' => $visit->notes,
            ],
        ]);
    }

    public function edit($id): Response
    {
        $visit = Visit::where('landlord_id', auth()->id())
            ->with('property')
            ->findOrFail($id);

        $properties = Property::forUser(auth()->id())->get();

        return Inertia::render('Nangue/Landlord/EditVisit', [
            'visit' => $visit,
            'properties' => $properties,
        ]);
    }

    public function update(Request $request, $id)
    {
        $visit = Visit::where('landlord_id', auth()->id())->findOrFail($id);

        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'visitor_name' => 'required|string',
            'visitor_phone' => 'required|string',
            'date' => 'required|date',
            'time' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $visit->update($validated);

        return redirect()->route('landlord.calendar.index')->with('success', 'Visite mise à jour');
    }

    public function cancel($id)
    {
        $visit = Visit::where('landlord_id', auth()->id())->findOrFail($id);
        $visit->update(['status' => 'cancelled']);

        return redirect()->route('landlord.calendar.index')->with('success', 'Visite annulée');
    }

    public function destroy($id)
    {
        $visit = Visit::where('landlord_id', auth()->id())->findOrFail($id);
        $visit->delete();

        return redirect()->route('landlord.calendar.index')->with('success', 'Visite supprimée');
    }
}
