<?php

namespace Nangue\Http\Controllers;

use App\Http\Controllers\Controller;
use Nangue\Models\RentControlZone;
use Nangue\Models\RentControlCompliance;
use Inertia\Inertia;
use Illuminate\Http\Request;

class RentControlController extends Controller
{
    public function index()
    {
        $zones = RentControlZone::all();

        $compliance = RentControlCompliance::with('property')
            ->whereHas('property', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->get();

        return Inertia::render('Landlord/RentControl/Index', [
            'zones' => $zones,
            'compliance' => $compliance,
        ]);
    }

    public function storeZone(Request $request)
    {
        $validated = $request->validate([
            'city' => 'required|string|max:255',
            'zone' => 'required|string|max:10',
            'max_price_per_sqm' => 'required|numeric|min:0',
            'reference_rent' => 'nullable|numeric|min:0',
            'reference_rent_plus' => 'nullable|numeric|min:0',
            'reference_rent_minus' => 'nullable|numeric|min:0',
        ]);

        RentControlZone::create($validated);

        return redirect()->back()->with('success', 'Zone ajoutée.');
    }

    public function checkCompliance(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'zone_id' => 'required|exists:rent_control_zones,id',
            'current_rent_per_sqm' => 'required|numeric|min:0',
            'max_allowed_rent_per_sqm' => 'required|numeric|min:0',
        ]);

        $validated['is_compliant'] = $validated['current_rent_per_sqm'] <= $validated['max_allowed_rent_per_sqm'];
        $validated['excess_amount'] = max(0, $validated['current_rent_per_sqm'] - $validated['max_allowed_rent_per_sqm']);

        RentControlCompliance::updateOrCreate(
            ['property_id' => $validated['property_id']],
            $validated
        );

        return redirect()->back()->with('success', 'Conformité vérifiée.');
    }
}
