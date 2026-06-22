<?php

namespace Nangue\Http\Controllers;

use App\Http\Controllers\Controller;
use Nangue\Models\PublicVisitSlot;
use Nangue\Models\PublicVisitBooking;
use Inertia\Inertia;
use Illuminate\Http\Request;

class PublicVisitSlotController extends Controller
{
    public function index()
    {
        $slots = PublicVisitSlot::with(['property', 'bookings'])
            ->whereHas('property', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->latest()->paginate(15);

        return Inertia::render('Nangue/Landlord/VisitSlots/Index', [
            'slots' => $slots,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'date' => 'required|date|after:today',
            'start_time' => 'required|string',
            'end_time' => 'required|string|after:start_time',
            'max_visitors' => 'required|integer|min:1|max:20',
        ]);

        $validated['status'] = 'disponible';
        $validated['booked_count'] = 0;

        PublicVisitSlot::create($validated);

        return redirect()->route('landlord.visit-slots.index')->with('success', 'Créneau de visite créé.');
    }

    public function update(Request $request, PublicVisitSlot $slot)
    {
        $validated = $request->validate([
            'status' => 'string|in:disponible,complet,annule',
        ]);

        $slot->update($validated);

        return redirect()->back()->with('success', 'Créneau mis à jour.');
    }

    public function destroy(PublicVisitSlot $slot)
    {
        $slot->bookings()->delete();
        $slot->delete();

        return redirect()->back()->with('success', 'Créneau supprimé.');
    }
}
