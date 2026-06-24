<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use App\Models\Transaction;
use App\Services\OrangeMoneyService;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    protected $orangeMoneyService;

    public function __construct(OrangeMoneyService $orangeMoneyService)
    {
        $this->orangeMoneyService = $orangeMoneyService;
    }

    /**
     * Get all company subscription plans.
     */
    public function getPlans()
    {
        $plans = SubscriptionPlan::where('account_type', 'company')
            ->orderBy('price', 'asc')
            ->get();

        return response()->json($plans);
    }

    /**
     * Initiate Orange Money Payment.
     */
    public function initiatePayment(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Non autorisé.'], 401);
        }

        $request->validate([
            'plan' => 'required|string|exists:subscription_plans,slug',
            'billing_cycle' => 'required|string|in:monthly,yearly',
            'payment_method' => 'required|string|in:orange_money,mtn_money,paypal,visa',
            'phone_number' => 'required_if:payment_method,orange_money,mtn_money|string',
        ]);

        $plan = SubscriptionPlan::where('slug', $request->plan)->first();
        if (!$plan || $plan->account_type !== 'company') {
            return response()->json(['error' => 'Plan de souscription invalide.'], 400);
        }

        // Determine price based on billing cycle
        $amount = $plan->price;
        if ($request->billing_cycle === 'yearly') {
            $amount = $plan->price_yearly ?? ($plan->price * 12 * 0.8);
        }

        $orderId = 'OM_' . time() . '_' . $user->id;
        $description = 'Abonnement Property AI - ' . $plan->name . ' (' . ($request->billing_cycle === 'yearly' ? 'Annuel' : 'Mensuel') . ')';

        // Execute payment initiation (simulation fallback is embedded inside service)
        $paymentResult = $this->orangeMoneyService->initiatePayment(
            $request->phone_number,
            $amount,
            $orderId,
            $description
        );

        if (!$paymentResult['success']) {
            return response()->json(['error' => $paymentResult['error']], 400);
        }

        // Record the transaction as pending in the database
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'company_profile_id' => $user->company_profile_id,
            'plan_slug' => $plan->slug,
            'amount' => $amount,
            'billing_cycle' => $request->billing_cycle,
            'payment_method' => $request->payment_method,
            'payment_ref' => $paymentResult['pay_token'],
            'phone_number' => $request->phone_number,
            'status' => 'pending',
            'metadata' => $paymentResult,
        ]);

        return response()->json([
            'success' => true,
            'transaction_id' => $transaction->id,
            'pay_token' => $paymentResult['pay_token'],
            'is_simulation' => $paymentResult['is_simulation'] ?? false,
            'message' => 'Paiement initié. Veuillez valider le paiement sur votre mobile.',
        ]);
    }

    /**
     * Check payment transaction status and activate subscription upon success.
     */
    public function checkPaymentStatus($id)
    {
        $user = auth()->user();
        $transaction = Transaction::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$transaction) {
            return response()->json(['error' => 'Transaction introuvable.'], 404);
        }

        if ($transaction->status === 'success') {
            return response()->json([
                'success' => true,
                'status' => 'success',
                'message' => 'Abonnement déjà validé et actif.',
            ]);
        }

        // Check if transaction is a simulation
        $isSimulation = str_starts_with($transaction->payment_ref, 'SIM_') || env('ORANGE_MONEY_SIMULATION', false);

        if ($isSimulation) {
            $this->activateUserSubscription($user, $transaction);

            return response()->json([
                'success' => true,
                'status' => 'success',
                'message' => 'Félicitations ! Votre paiement simulé a été validé et votre abonnement est actif.',
            ]);
        }

        // Query status from real Orange Money API
        $verifyResult = $this->orangeMoneyService->verifyPaymentStatus($transaction->payment_ref);

        if ($verifyResult['success'] && $verifyResult['status'] === 'success') {
            $this->activateUserSubscription($user, $transaction);

            return response()->json([
                'success' => true,
                'status' => 'success',
                'message' => 'Félicitations ! Votre paiement a été validé et votre abonnement est actif.',
            ]);
        } elseif ($verifyResult['success'] && $verifyResult['status'] === 'failed') {
            $transaction->status = 'failed';
            $transaction->save();

            return response()->json([
                'success' => false,
                'status' => 'failed',
                'message' => 'Le paiement a échoué ou a été rejeté par l\'opérateur.',
            ]);
        }

        return response()->json([
            'success' => false,
            'status' => 'pending',
            'message' => $verifyResult['error'] ?? 'Paiement en attente de validation sur votre téléphone. Veuillez patienter.',
        ]);
    }

    /**
     * Activate the user subscription in a database transaction.
     */
    private function activateUserSubscription($user, $transaction)
    {
        DB::transaction(function () use ($user, $transaction) {
            // Update transaction status
            $transaction->status = 'success';
            $transaction->save();

            // Deactivate previous active subscriptions for this user
            UserSubscription::where('user_id', $user->id)
                ->where('status', 'active')
                ->update([
                    'status' => 'inactive',
                    'ends_at' => now(),
                ]);

            // Create new active subscription record
            UserSubscription::create([
                'user_id' => $user->id,
                'plan_slug' => $transaction->plan_slug,
                'price' => $transaction->amount,
                'starts_at' => now(),
                'ends_at' => $transaction->billing_cycle === 'yearly' ? now()->addYear() : now()->addMonth(),
                'status' => 'active',
            ]);

            // Update user subscription plan
            $user->subscription_plan = $transaction->plan_slug;
            // Extend/clear trial restrictions
            $user->trial_ends_at = null; 
            $user->save();
        });
    }

    /**
     * Upgrade the user's subscription plan (Legacy Direct Upgrade - no payment).
     */
    public function upgradePlan(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Non autorisé.'], 401);
        }

        $request->validate([
            'plan' => 'required|string|exists:subscription_plans,slug',
        ]);

        $newPlan = SubscriptionPlan::where('slug', $request->plan)->first();

        if (!$newPlan || $newPlan->account_type !== 'company') {
            return response()->json(['error' => 'Plan invalide.'], 400);
        }

        // Get current active plan price
        $currentPlanSlug = $user->subscription_plan;
        $currentPlan = SubscriptionPlan::where('slug', $currentPlanSlug)->first();
        $currentPrice = $currentPlan ? $currentPlan->price : 0;

        // Verify it is an upgrade
        if ($newPlan->price <= $currentPrice) {
            return response()->json([
                'error' => 'Vous ne pouvez choisir qu\'un plan supérieur à votre plan actuel.'
            ], 400);
        }

        DB::transaction(function () use ($user, $newPlan) {
            // Deactivate previous active subscriptions for this user
            UserSubscription::where('user_id', $user->id)
                ->where('status', 'active')
                ->update([
                    'status' => 'inactive',
                    'ends_at' => now(),
                ]);

            // Create new active subscription record
            UserSubscription::create([
                'user_id' => $user->id,
                'plan_slug' => $newPlan->slug,
                'price' => $newPlan->price,
                'starts_at' => now(),
                'ends_at' => now()->addMonth(),
                'status' => 'active',
            ]);

            // Update user subscription_plan
            $user->subscription_plan = $newPlan->slug;
            $user->trial_ends_at = null;
            $user->save();
        });

        // Load the relationship before returning
        $user->load('planRelation');

        return response()->json([
            'success' => true,
            'message' => 'Votre abonnement a été mis à niveau avec succès vers ' . $newPlan->name . '.',
            'user' => $user,
        ]);
    }
}
