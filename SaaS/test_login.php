<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Test login logic
$email = 'dongmoduval269@icloud.cmo';
$password = 'password123';

echo "Testing login for: $email\n";

$user = \App\Models\User::where('email', $email)->first();

if (!$user) {
    echo "ERROR: User not found\n";
    exit(1);
}

echo "User found: {$user->name} (ID: {$user->id})\n";
echo "Account type: {$user->account_type}\n";

if ($user->account_type !== 'Locataire') {
    echo "ERROR: User is not a Locataire\n";
    exit(1);
}

if (\Illuminate\Support\Facades\Hash::check($password, $user->password)) {
    echo "SUCCESS: Password matches\n";
    echo "User can login\n";
} else {
    echo "ERROR: Password does not match\n";
    echo "Stored hash: {$user->password}\n";
}
