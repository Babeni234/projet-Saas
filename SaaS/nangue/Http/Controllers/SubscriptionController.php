<?php

namespace Nangue\Http\Controllers;

use App\Http\Controllers\Controller;
use Nangue\Models\Subscription;
use Nangue\Models\PaymentMethod;
use Inertia\Inertia;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with(['contract.property', 'paymentMethod', 'transactions'])
            ->whereHas('contract', function ($q) {
                $q->where('landlord_id', auth()->id());
            })
            ->latest()->paginate(15);

        return Inertia::render('Landlord/Subscriptions/Index', [
            'subscriptions' => $subscriptions,
        ]);
    }

    public function create()
    {
        $contracts = auth()->user()->contracts;
        $paymentMethods = PaymentMethod::where('user_id', auth()->id())->get();

        return Inertia::render('Landlord/Subscriptions/Create', [
            'contracts' => $contracts,
            'paymentMethods' => $paymentMethods,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'amount' => 'required|numeric|min:0',
            'frequency' => 'required|string|in:mensuel,trimestriel,semestriel,annuel',
            'day_of_month' => 'required|integer|between:1,31',
        ]);

        $validated['status'] = 'actif';

        Subscription::create($validated);

        return redirect()->route('landlord.subscriptions.index')->with('success', 'Prélèvement créé.');
    }

    public function cancel(Subscription $subscription)
    {
        $subscription->update([
            'status' => 'annule',
            'cancelled_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Prélèvement annulé.');
    }
}
