<?php

namespace Nangue\Http\Controllers;

use App\Http\Controllers\Controller;
use Nangue\Models\RentRevision;
use Inertia\Inertia;
use Illuminate\Http\Request;

class RentRevisionController extends Controller
{
    public function index()
    {
        $revisions = RentRevision::with(['contract.property'])
            ->where('landlord_id', auth()->id())
            ->latest()->paginate(15);

        $contracts = auth()->user()->contracts()->with('property')->get();

        return Inertia::render('Landlord/RentRevisions/Index', [
            'revisions' => $revisions,
            'contracts' => $contracts,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'previous_rent' => 'required|numeric|min:0',
            'new_rent' => 'required|numeric|min:0',
            'index_name' => 'required|string|max:255',
            'index_value_old' => 'required|numeric',
            'index_value_new' => 'required|numeric',
            'effective_date' => 'required|date',
        ]);

        $validated['landlord_id'] = auth()->id();
        $validated['percentage_change'] = round(($validated['new_rent'] - $validated['previous_rent']) / $validated['previous_rent'] * 100, 2);
        $validated['status'] = 'projet';

        RentRevision::create($validated);

        return redirect()->route('landlord.rent-revisions.index')->with('success', 'Révision créée.');
    }

    public function apply(RentRevision $revision)
    {
        $revision->update([
            'status' => 'appliquee',
            'applied_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Révision appliquée.');
    }
}
