<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = \App\Models\User::find(7);
if ($user) {
    $user->password = \Illuminate\Support\Facades\Hash::make('password123');
    $user->save();
    echo "Password updated for user ID 7 ({$user->email}). New password: password123\n";
} else {
    echo "User not found\n";
}
