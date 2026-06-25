<?php

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\TrialSetting;
use Illuminate\Support\Facades\DB;

echo "=== Running Automated Verification for Trial settings ===\n";

DB::transaction(function() {
    // 1. Save a new trial duration of 8 days
    TrialSetting::updateOrCreate(
        ['key' => 'trial_duration_days'],
        ['value' => 8]
    );
    echo "Saved trial_duration_days = 8 in trial_settings table.\n";

    // 2. Create a company user
    $tempEmail = 'temp_company_test_' . time() . '@test.com';
    $user = User::create([
        'name' => 'Temp Company User',
        'email' => $tempEmail,
        'password' => bcrypt('password123'),
        'account_type' => 'company',
        'subscription_plan' => 'starter',
    ]);
    
    echo "Created user with email: {$tempEmail}\n";
    echo "User trial_started_at: " . ($user->trial_started_at ? $user->trial_started_at->toDateTimeString() : 'NULL') . "\n";
    echo "User trial_ends_at: " . ($user->trial_ends_at ? $user->trial_ends_at->toDateTimeString() : 'NULL') . "\n";

    // Calculate diff in days
    if ($user->trial_started_at && $user->trial_ends_at) {
        $diff = $user->trial_started_at->diffInDays($user->trial_ends_at);
        echo "Trial duration calculated: {$diff} days\n";
        if ($diff === 8) {
            echo "✅ SUCCESS: Trial duration matches the database configuration!\n";
        } else {
            echo "❌ FAILURE: Expected 8 days, got {$diff} days\n";
        }
    } else {
        echo "❌ FAILURE: Trial dates were not initialized!\n";
    }

    // Rollback changes to keep db clean
    throw new \Exception("Rollback to keep database clean.");
});
