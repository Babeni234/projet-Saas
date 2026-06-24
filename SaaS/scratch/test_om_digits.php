<?php

require 'c:/xampp/htdocs/projet_SAAS/projet-Saas/SaaS/vendor/autoload.php';
$app = require_once 'c:/xampp/htdocs/projet_SAAS/projet-Saas/SaaS/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\OrangeMoneyService;
use Illuminate\Support\Facades\Http;

$om = new OrangeMoneyService();
$token = $om->getAccessToken();
if (!$token) {
    echo "FAILED TO GET ACCESS TOKEN\n";
    exit(1);
}

$accessToken = $token;
$xAuthToken = base64_encode('MULTIBUSINESSSARL@OMAPI:MULTIBUSINESSSARL@OMAPI@2025');

function runTest($channelUser, $subscriber, $accessToken, $xAuthToken, $label) {
    echo "\n=== RUNNING TEST: {$label} ===\n";
    $orderId = 'T_' . time() . '_' . rand(100, 999);
    
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $accessToken,
        'X-Auth-Token' => $xAuthToken,
        'Content-Type' => 'application/json'
    ])->post('https://api-s1.orange.cm/omcoreapis/1.0.2/mp/init', [
        'channelUserMsisdn' => $channelUser,
        'pin' => '1990',
        'subscriberMsisdn' => $subscriber,
        'amount' => 100,
        'orderId' => $orderId,
        'description' => 'Test ' . $label,
        'payToken' => ''
    ]);

    echo "Init Status: " . $response->status() . "\n";
    echo "Init Body: " . $response->body() . "\n";

    $data = $response->json();
    $payToken = $data['payToken'] ?? ($data['data']['payToken'] ?? null);

    if (!$payToken) {
        echo "No pay token returned!\n";
        return;
    }

    // Check status
    $statusResponse = Http::withHeaders([
        'Authorization' => 'Bearer ' . $accessToken,
        'X-Auth-Token' => $xAuthToken,
        'Content-Type' => 'application/json'
    ])->get('https://api-s1.orange.cm/omcoreapis/1.0.2/mp/paymentstatus/' . $payToken);

    echo "Status check: " . $statusResponse->body() . "\n";

    // Call Push
    $pushResponse = Http::withHeaders([
        'Authorization' => 'Bearer ' . $accessToken,
        'X-Auth-Token' => $xAuthToken,
        'Content-Type' => 'application/json'
    ])->get('https://api-s1.orange.cm/omcoreapis/1.0.2/mp/push/' . $payToken);

    echo "Push response: " . $pushResponse->body() . "\n";
}

// Test 1: 9 digits
runTest('655743248', '655743248', $accessToken, $xAuthToken, '9-Digits-Self');

// Test 2: 12 digits
runTest('237655743248', '237655743248', $accessToken, $xAuthToken, '12-Digits-Self');
