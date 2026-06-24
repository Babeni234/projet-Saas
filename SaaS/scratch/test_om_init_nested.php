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

$channelUser = '655743248'; // 9 digits
$subscriber = '659633396'; // 9 digits
$orderId = 'T_NEST_' . time();

echo "--- TESTING NESTED DATA BODY ---\n";
$response = Http::withHeaders([
    'Authorization' => 'Bearer ' . $accessToken,
    'X-Auth-Token' => $xAuthToken,
    'Content-Type' => 'application/json'
])->post('https://api-s1.orange.cm/omcoreapis/1.0.2/mp/init', [
    'data' => [
        'channelUserMsisdn' => $channelUser,
        'pin' => '1990',
        'subscriberMsisdn' => $subscriber,
        'amount' => 100,
        'orderId' => $orderId,
        'description' => 'Test Nested',
        'payToken' => ''
    ]
]);

echo "Init Status: " . $response->status() . "\n";
echo "Init Body: " . $response->body() . "\n";

$data = $response->json();
$payToken = $data['payToken'] ?? ($data['data']['payToken'] ?? null);

if (!$payToken) {
    echo "No payToken returned.\n";
    exit(1);
}

// Check status
$statusResponse = Http::withHeaders([
    'Authorization' => 'Bearer ' . $accessToken,
    'X-Auth-Token' => $xAuthToken,
    'Content-Type' => 'application/json'
])->get('https://api-s1.orange.cm/omcoreapis/1.0.2/mp/paymentstatus/' . $payToken);

echo "Status Body: " . $statusResponse->body() . "\n";

// Call Push
$pushResponse = Http::withHeaders([
    'Authorization' => 'Bearer ' . $accessToken,
    'X-Auth-Token' => $xAuthToken,
    'Content-Type' => 'application/json'
])->get('https://api-s1.orange.cm/omcoreapis/1.0.2/mp/push/' . $payToken);

echo "Push Status: " . $pushResponse->status() . "\n";
echo "Push Body: " . $pushResponse->body() . "\n";
