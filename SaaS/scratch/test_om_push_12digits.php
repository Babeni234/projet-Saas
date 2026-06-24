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

$channelUser = '237655743248'; // 12 digits
$subscriber = '237659633396'; // 12 digits
$orderId = 'T_PUSH_12_' . time();

echo "1. INITIATING PAYMENT (Subscriber: {$subscriber}, ChannelUser: {$channelUser})\n";

// Let's try up to 3 times in case of timeouts
$response = null;
for ($attempt = 1; $attempt <= 3; $attempt++) {
    try {
        echo "Attempt {$attempt}...\n";
        $response = Http::timeout(15)->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'X-Auth-Token' => $xAuthToken,
            'Content-Type' => 'application/json'
        ])->post('https://api-s1.orange.cm/omcoreapis/1.0.2/mp/init', [
            'channelUserMsisdn' => $channelUser,
            'pin' => '1990',
            'subscriberMsisdn' => $subscriber,
            'amount' => 100,
            'orderId' => $orderId,
            'description' => 'Test Push 12 digits',
            'payToken' => ''
        ]);
        break;
    } catch (\Exception $e) {
        echo "Attempt {$attempt} failed: " . $e->getMessage() . "\n";
        if ($attempt == 3) {
            exit(1);
        }
        sleep(2);
    }
}

echo "Init Status: " . $response->status() . "\n";
echo "Init Body: " . $response->body() . "\n";

$data = $response->json();
$payToken = $data['payToken'] ?? ($data['data']['payToken'] ?? null);

if (!$payToken) {
    echo "FAILED: No payToken returned.\n";
    exit(1);
}

echo "2. CHECKING STATUS\n";
$statusResponse = Http::withHeaders([
    'Authorization' => 'Bearer ' . $accessToken,
    'X-Auth-Token' => $xAuthToken,
    'Content-Type' => 'application/json'
])->get('https://api-s1.orange.cm/omcoreapis/1.0.2/mp/paymentstatus/' . $payToken);

echo "Status Body: " . $statusResponse->body() . "\n";

echo "3. CALLING PUSH ENDPOINT\n";
$pushResponse = Http::withHeaders([
    'Authorization' => 'Bearer ' . $accessToken,
    'X-Auth-Token' => $xAuthToken,
    'Content-Type' => 'application/json'
])->get('https://api-s1.orange.cm/omcoreapis/1.0.2/mp/push/' . $payToken);

echo "Push Status: " . $pushResponse->status() . "\n";
echo "Push Body: " . $pushResponse->body() . "\n";
