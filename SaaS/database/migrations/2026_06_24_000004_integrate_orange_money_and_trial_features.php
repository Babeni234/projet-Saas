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
        // 1. Add trial columns to users table
        if (!Schema::hasColumn('users', 'trial_ends_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('trial_ends_at')->nullable()->after('subscription_plan');
                $table->timestamp('trial_started_at')->nullable()->after('trial_ends_at');
            });
        }

        // 2. Add price_yearly to subscription_plans table
        if (!Schema::hasColumn('subscription_plans', 'price_yearly')) {
            Schema::table('subscription_plans', function (Blueprint $table) {
                $table->double('price_yearly')->nullable()->after('price');
            });
        }

        // Populate price_yearly with some defaults based on monthly price
        DB::table('subscription_plans')->get()->each(function ($plan) {
            DB::table('subscription_plans')
                ->where('id', $plan->id)
                ->update(['price_yearly' => $plan->price * 12 * 0.8]); // 20% discount for yearly
        });

        // 3. Create transactions table
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('company_profile_id')->constrained('company_profiles')->onDelete('cascade');
            $table->string('plan_slug');
            $table->double('amount');
            $table->string('billing_cycle'); // monthly or yearly
            $table->string('payment_method'); // orange_money, mtn_money, paypal, visa
            $table->string('payment_ref')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('status')->default('pending'); // pending, success, failed
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        // 4. Create trial_settings table
        Schema::create('trial_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('value');
            $table->timestamps();
        });

        // Seed default blocked features
        DB::table('trial_settings')->insert([
            'key' => 'blocked_features',
            'value' => json_encode(['hotel', 'accounting', 'maintenance']),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trial_settings');
        Schema::dropIfExists('transactions');
        
        if (Schema::hasColumn('subscription_plans', 'price_yearly')) {
            Schema::table('subscription_plans', function (Blueprint $table) {
                $table->dropColumn('price_yearly');
            });
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['trial_ends_at', 'trial_started_at']);
        });
    }
};
