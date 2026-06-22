<?php

namespace Nangue\Http\Controllers;

use App\Http\Controllers\Controller;
use Nangue\Models\FiscalYear;
use Nangue\Models\FiscalExpense;
use Inertia\Inertia;
use Illuminate\Http\Request;

class FiscalController extends Controller
{
    public function index()
    {
        $fiscalYears = FiscalYear::with('expenses')
            ->where('user_id', auth()->id())
            ->latest()->paginate(10);

        return Inertia::render('Landlord/Fiscal/Index', [
            'fiscalYears' => $fiscalYears,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:2020|max:2100',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['total_revenue'] = 0;
        $validated['total_expenses'] = 0;
        $validated['net_income'] = 0;
        $validated['status'] = 'brouillon';

        FiscalYear::create($validated);

        return redirect()->route('landlord.fiscal.index')->with('success', 'Année fiscale créée.');
    }

    public function show(FiscalYear $fiscalYear)
    {
        $fiscalYear->load('expenses.property');

        return Inertia::render('Landlord/Fiscal/Show', [
            'fiscalYear' => $fiscalYear,
        ]);
    }

    public function addExpense(Request $request, FiscalYear $fiscalYear)
    {
        $validated = $request->validate([
            'property_id' => 'nullable|exists:properties,id',
            'category' => 'required|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'deductible' => 'boolean',
        ]);

        $validated['fiscal_year_id'] = $fiscalYear->id;

        FiscalExpense::create($validated);

        $fiscalYear->recalculate();

        return redirect()->back()->with('success', 'Dépense ajoutée.');
    }

    public function finalize(FiscalYear $fiscalYear)
    {
        $fiscalYear->update(['status' => 'finalise']);

        return redirect()->back()->with('success', 'Année fiscale finalisée.');
    }
}
