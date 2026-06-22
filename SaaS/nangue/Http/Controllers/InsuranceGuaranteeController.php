<?php

namespace Nangue\Http\Controllers;

use App\Http\Controllers\Controller;
use Nangue\Models\InsuranceGuarantee;
use Inertia\Inertia;
use Illuminate\Http\Request;

class InsuranceGuaranteeController extends Controller
{
    public function index()
    {
        $guarantees = InsuranceGuarantee::with(['contract.property', 'tenant'])
            ->whereHas('contract', function ($q) {
                $q->where('landlord_id', auth()->id());
            })
            ->latest()->paginate(15);

        return Inertia::render('Landlord/InsuranceGuarantees/Index', [
            'guarantees' => $guarantees,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'tenant_id' => 'required|exists:tenants,id',
            'type' => 'required|string|in:visale,garant,assurance,caution_solidaire',
            'provider' => 'nullable|string|max:255',
            'reference_number' => 'nullable|string|max:255',
            'covered_amount' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'document_path' => 'nullable|string',
        ]);

        $validated['status'] = 'actif';

        InsuranceGuarantee::create($validated);

        return redirect()->route('landlord.insurance-guarantees.index')->with('success', 'Garantie enregistrée.');
    }

    public function update(Request $request, InsuranceGuarantee $guarantee)
    {
        $validated = $request->validate([
            'status' => 'string|in:actif,expire,resilie',
            'end_date' => 'nullable|date',
        ]);

        $guarantee->update($validated);

        return redirect()->back()->with('success', 'Garantie mise à jour.');
    }
}
