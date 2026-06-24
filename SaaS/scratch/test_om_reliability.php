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

function runSingle($channelUser, $subscriber, $accessToken, $xAuthToken) {
    $orderId = 'T_' . time() . '_' . rand(100, 999);
    $start = microtime(true);
    try {
        $response = Http::timeout(10)->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'X-Auth-Token' => $xAuthToken,
            'Content-Type' => 'application/json'
        ])->post('https://api-s1.orange.cm/omcoreapis/1.0.2/mp/init', [
            'channelUserMsisdn' => $channelUser,
            'pin' => '1990',
            'subscriberMsisdn' => $subscriber,
            'amount' => 100,
            'orderId' => $orderId,
            'description' => 'Test',
            'payToken' => ''
        ]);
        $duration = microtime(true) - $start;
        return [
            'success' => true,
            'status' => $response->status(),
            'body' => $response->body(),
            'duration' => $duration
        ];
    } catch (\Exception $e) {
        $duration = microtime(true) - $start;
        return [
            'success' => false,
            'error' => $e->getMessage(),
            'duration' => $duration
        ];
    }
}

echo "--- TESTING 9 DIGITS (x3) ---\n";
for ($i = 1; $i <= 3; $i++) {
    $res = runSingle('655743248', '655743248', $accessToken, $xAuthToken);
    if ($res['success']) {
        echo "Run {$i} (9 digits): Success in " . round($res['duration'], 2) . "s. Status: " . $res['status'] . "\n";
    } else {
        echo "Run {$i} (9 digits): Failed in " . round($res['duration'], 2) . "s. Error: " . $res['error'] . "\n";
    }
    sleep(1);
}

echo "\n--- TESTING 12 DIGITS (x3) ---\n";
for ($i = 1; $i <= 3; $i++) {
    $res = runSingle('237655743248', '237655743248', $accessToken, $xAuthToken);
    if ($res['success']) {
        echo "Run {$i} (12 digits): Success in " . round($res['duration'], 2) . "s. Status: " . $res['status'] . "\n";
    } else {
        echo "Run {$i} (12 digits): Failed in " . round($res['duration'], 2) . "s. Error: " . $res['error'] . "\n";
    }
    sleep(1);
}
