<?php

require 'c:/xampp/htdocs/projet_SAAS/projet-Saas/SaaS/vendor/autoload.php';
$app = require_once 'c:/xampp/htdocs/projet_SAAS/projet-Saas/SaaS/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Transaction;

$transactions = Transaction::orderBy('id', 'desc')->take(10)->get();

foreach ($transactions as $t) {
    echo "ID: {$t->id} | User: {$t->user_id} | Plan: {$t->plan_slug} | Ref: {$t->payment_ref} | Phone: {$t->phone_number} | Status: {$t->status} | Created: {$t->created_at}\n";
    if ($t->metadata) {
        echo "   Metadata: " . json_encode($t->metadata) . "\n";
    }
}
