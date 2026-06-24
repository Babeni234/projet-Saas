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
$orderId = 'T_PAY_' . time();
$amount = 100;

echo "1. INITIATING (POST /mp/init)\n";
$initResponse = Http::withHeaders([
    'Authorization' => 'Bearer ' . $accessToken,
    'X-Auth-Token' => $xAuthToken,
    'Content-Type' => 'application/json'
])->post('https://api-s1.orange.cm/omcoreapis/1.0.2/mp/init', [
    'channelUserMsisdn' => $channelUser,
    'pin' => '1990',
    'subscriberMsisdn' => $subscriber,
    'amount' => $amount,
    'orderId' => $orderId,
    'description' => 'Test Full Pay',
    'payToken' => ''
]);

echo "Init Status: " . $initResponse->status() . "\n";
echo "Init Body: " . $initResponse->body() . "\n";

$data = $initResponse->json();
$payToken = $data['payToken'] ?? ($data['data']['payToken'] ?? null);

if (!$payToken) {
    echo "No payToken returned.\n";
    exit(1);
}

echo "\n2. EXECUTING (POST /mp/pay with version 1.0.2)\n";
$payResponse = Http::withHeaders([
    'Authorization' => 'Bearer ' . $accessToken,
    'X-Auth-Token' => $xAuthToken,
    'Content-Type' => 'application/json'
])->post('https://api-s1.orange.cm/omcoreapis/1.0.2/mp/pay', [
    'channelUserMsisdn' => $channelUser,
    'pin' => '1990',
    'subscriberMsisdn' => $subscriber,
    'amount' => $amount,
    'orderId' => $orderId,
    'description' => 'Test Full Pay',
    'payToken' => $payToken
]);

echo "Pay 1.0.2 Status: " . $payResponse->status() . "\n";
echo "Pay 1.0.2 Body: " . $payResponse->body() . "\n";

echo "\n3. EXECUTING (POST /mp/pay with version 1.0.1 as fallback if 1.0.2 fails)\n";
if ($payResponse->status() !== 200) {
    $payResponse101 = Http::withHeaders([
        'Authorization' => 'Bearer ' . $accessToken,
        'X-Auth-Token' => $xAuthToken,
        'Content-Type' => 'application/json'
    ])->post('https://api-s1.orange.cm/omcoreapis/1.0.1/mp/pay', [
        'channelUserMsisdn' => $channelUser,
        'pin' => '1990',
        'subscriberMsisdn' => $subscriber,
        'amount' => $amount,
        'orderId' => $orderId,
        'description' => 'Test Full Pay',
        'payToken' => $payToken
    ]);

    echo "Pay 1.0.1 Status: " . $payResponse101->status() . "\n";
    echo "Pay 1.0.1 Body: " . $payResponse101->body() . "\n";
}

echo "\n4. CHECKING STATUS\n";
$statusResponse = Http::withHeaders([
    'Authorization' => 'Bearer ' . $accessToken,
    'X-Auth-Token' => $xAuthToken,
    'Content-Type' => 'application/json'
])->get('https://api-s1.orange.cm/omcoreapis/1.0.2/mp/paymentstatus/' . $payToken);

echo "Status Body: " . $statusResponse->body() . "\n";
