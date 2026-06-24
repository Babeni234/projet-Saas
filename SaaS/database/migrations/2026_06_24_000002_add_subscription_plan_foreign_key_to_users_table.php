<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Seed fallback plans (starter, professional, enterprise) to prevent key violations
        $fallbackPlans = [
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'account_type' => 'individual',
                'price' => 0.0,
                'billing_cycle' => 'monthly',
                'features' => json_encode(['Accès gratuit de base']),
                'popular' => false,
                'color' => 'from-slate-400 to-slate-500',
            ],
            [
                'name' => 'Professional',
                'slug' => 'professional',
                'account_type' => 'company',
                'price' => 0.0,
                'billing_cycle' => 'monthly',
                'features' => json_encode(['Accès professionnel']),
                'popular' => false,
                'color' => 'from-indigo-400 to-indigo-500',
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'account_type' => 'company',
                'price' => 0.0,
                'billing_cycle' => 'monthly',
                'features' => json_encode(['Accès entreprise complet']),
                'popular' => false,
                'color' => 'from-violet-400 to-violet-500',
            ],
        ];

        foreach ($fallbackPlans as $p) {
            DB::table('subscription_plans')->updateOrInsert(
                ['slug' => $p['slug']],
                [
                    'name' => $p['name'],
                    'account_type' => $p['account_type'],
                    'price' => $p['price'],
                    'billing_cycle' => $p['billing_cycle'],
                    'features' => $p['features'],
                    'popular' => $p['popular'],
                    'color' => $p['color'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // 2. Ensure all users have a valid plan (fallback to 'starter' if null/invalid)
        $validSlugs = DB::table('subscription_plans')->pluck('slug')->toArray();
        DB::table('users')
            ->whereNotIn('subscription_plan', $validSlugs)
            ->orWhereNull('subscription_plan')
            ->update(['subscription_plan' => 'starter']);

        // 3. Add foreign key constraint
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('subscription_plan')->references('slug')->on('subscription_plans')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['subscription_plan']);
        });
    }
};
