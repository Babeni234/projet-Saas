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
$subscriber = '659633396'; // 9 digits (a real customer number entered by the user)
$orderId = 'T_' . rand(100, 999) . '_' . substr(time(), -6); // 11-12 chars (well below 20 limit)
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
    'description' => 'Test Pay Notif',
    'payToken' => ''
]);

echo "Init Body: " . $initResponse->body() . "\n";

$data = $initResponse->json();
$payToken = $data['payToken'] ?? ($data['data']['payToken'] ?? null);

if (!$payToken) {
    echo "No payToken returned.\n";
    exit(1);
}

echo "\n2. EXECUTING (POST /mp/pay with NotifUrl)\n";
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
    'description' => 'Test Pay Notif',
    'payToken' => $payToken,
    'notifUrl' => 'https://propertyai.cm/api/om-webhook',
    'NotifUrl' => 'https://propertyai.cm/api/om-webhook'
]);

echo "Pay Status: " . $payResponse->status() . "\n";
echo "Pay Body: " . $payResponse->body() . "\n";

echo "\n3. CHECKING STATUS\n";
$statusResponse = Http::withHeaders([
    'Authorization' => 'Bearer ' . $accessToken,
    'X-Auth-Token' => $xAuthToken,
    'Content-Type' => 'application/json'
])->get('https://api-s1.orange.cm/omcoreapis/1.0.2/mp/paymentstatus/' . $payToken);

echo "Status Body: " . $statusResponse->body() . "\n";
