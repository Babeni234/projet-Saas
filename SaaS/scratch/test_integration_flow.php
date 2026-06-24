<?php

require 'c:/xampp/htdocs/projet_SAAS/projet-Saas/SaaS/vendor/autoload.php';
$app = require_once 'c:/xampp/htdocs/projet_SAAS/projet-Saas/SaaS/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\OrangeMoneyService;

$om = new OrangeMoneyService();

$subscriber = '659633396'; // customer number
$amount = 100;
$orderId = 'T_' . rand(100, 999) . '_' . substr(time(), -6);

echo "Executing initiatePayment() on OrangeMoneyService...\n";
$res = $om->initiatePayment($subscriber, $amount, $orderId, 'Integration Test Description');

echo "Result:\n";
print_r($res);
