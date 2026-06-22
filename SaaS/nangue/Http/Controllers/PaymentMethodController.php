<?php

namespace Nangue\Http\Controllers;

use App\Http\Controllers\Controller;
use Nangue\Models\PaymentMethod;
use Inertia\Inertia;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $methods = PaymentMethod::where('user_id', auth()->id())->get();

        return Inertia::render('Landlord/PaymentMethods/Index', [
            'methods' => $methods,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:card,sepa',
            'provider' => 'required|string',
            'provider_id' => 'required|string',
            'last_four' => 'nullable|string|size:4',
            'brand' => 'nullable|string',
            'iban_last_four' => 'nullable|string|size:4',
            'mandate_id' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['is_active'] = true;

        if (!($validated['is_default'] ?? false)) {
            $validated['is_default'] = PaymentMethod::where('user_id', auth()->id())->count() === 0;
        }

        PaymentMethod::create($validated);

        return redirect()->back()->with('success', 'Moyen de paiement ajouté.');
    }

    public function setDefault(PaymentMethod $paymentMethod)
    {
        PaymentMethod::where('user_id', auth()->id())->update(['is_default' => false]);
        $paymentMethod->update(['is_default' => true]);

        return redirect()->back()->with('success', 'Moyen de paiement par défaut mis à jour.');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();

        return redirect()->back()->with('success', 'Moyen de paiement supprimé.');
    }
}
