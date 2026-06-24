<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
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
     * Upgrade the user's subscription plan.
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
                'ends_at' => now()->addMonth(), // Assuming monthly billing
                'status' => 'active',
            ]);

            // Update user subscription_plan
            $user->subscription_plan = $newPlan->slug;
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
