<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrangeMoneyService
{
    protected $consumerKey;
    protected $consumerSecret;
    protected $apiUsername;
    protected $apiPassword;
    protected $channelUserMsisdn;
    protected $pin;
    protected $initUrl = 'https://api-s1.orange.cm/omcoreapis/1.0.2/mp/init';
    protected $tokenUrl = 'https://api-s1.orange.cm/token'; // standard Orange token endpoint

    public function __construct()
    {
        $this->consumerKey = env('ORANGE_MONEY_CONSUMER_KEY', 'yMaymHkvDfGmg_g7hUzgn1SYrUwa');
        $this->consumerSecret = env('ORANGE_MONEY_CONSUMER_SECRET', 'Cq3go3r1hYUSH4sEqf4ELf8IdaAa');
        $this->apiUsername = env('ORANGE_MONEY_API_USERNAME', 'MULTIBUSINESSSARL@OMAPI');
        $this->apiPassword = env('ORANGE_MONEY_API_PASSWORD', 'MULTIBUSINESSSARL@OMAPI@2025');
        $this->channelUserMsisdn = env('ORANGE_MONEY_CHANNEL_USER_MSISDN', '655743248');
        $this->pin = env('ORANGE_MONEY_PIN', '1990');
    }

    /**
     * Get OAuth Access Token from Orange API.
     */
    public function getAccessToken()
    {
        try {
            $response = Http::asForm()
                ->withHeaders([
                    'Authorization' => 'Basic ' . base64_encode($this->consumerKey . ':' . $this->consumerSecret)
                ])
                ->post($this->tokenUrl, [
                    'grant_type' => 'client_credentials'
                ]);

            if ($response->successful()) {
                return $response->json()['access_token'] ?? null;
            }

            Log::error('Orange Money Access Token Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Orange Money Access Token Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Initiate a merchant payment.
     */
    public function initiatePayment($subscriberMsisdn, $amount, $orderId, $description = 'Abonnement Property AI')
    {
        // Normalize subscriber phone number to local 9-digit format (6xxxxxxxx) for Cameroon
        $subscriberMsisdn = preg_replace('/\D/', '', $subscriberMsisdn);
        if (strlen($subscriberMsisdn) === 12 && str_starts_with($subscriberMsisdn, '237')) {
            $subscriberMsisdn = substr($subscriberMsisdn, 3);
        }

        // Normalize channelUserMsisdn (merchant number) to local 9-digit format as well
        $channelUserMsisdn = preg_replace('/\D/', '', $this->channelUserMsisdn);
        if (strlen($channelUserMsisdn) === 12 && str_starts_with($channelUserMsisdn, '237')) {
            $channelUserMsisdn = substr($channelUserMsisdn, 3);
        }

        // Support Simulation Mode for Staging/Demo/Tests
        $isSimulationNumber = str_starts_with($subscriberMsisdn, 'SIM_') || 
                              $subscriberMsisdn === '655743248' || 
                              str_starts_with($subscriberMsisdn, '600');
        $forceSimulation = env('ORANGE_MONEY_SIMULATION', false);

        if ($isSimulationNumber || $forceSimulation) {
            Log::info("Orange Money Simulation Mode activated for order: {$orderId}");
            return [
                'success' => true,
                'is_simulation' => true,
                'pay_token' => 'SIM_' . uniqid(),
                'message' => 'Paiement simulé initié avec succès.',
            ];
        }

        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return [
                'success' => false,
                'error' => 'Impossible de s\'authentifier auprès des serveurs Orange Money.'
            ];
        }

        $xAuthToken = base64_encode($this->apiUsername . ':' . $this->apiPassword);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'X-Auth-Token' => $xAuthToken,
                'Content-Type' => 'application/json'
            ])->post($this->initUrl, [
                'channelUserMsisdn' => $channelUserMsisdn,
                'pin' => $this->pin,
                'subscriberMsisdn' => $subscriberMsisdn,
                'amount' => (int) $amount,
                'orderId' => $orderId,
                'description' => $description,
                'payToken' => ''
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $payToken = $data['payToken'] ?? ($data['data']['payToken'] ?? null);

                // Call the POST /mp/pay endpoint to execute payment and trigger the USSD push prompt on the customer's phone
                if ($payToken) {
                    try {
                        $payResponse = Http::withHeaders([
                            'Authorization' => 'Bearer ' . $accessToken,
                            'X-Auth-Token' => $xAuthToken,
                            'Content-Type' => 'application/json'
                        ])->post('https://api-s1.orange.cm/omcoreapis/1.0.2/mp/pay', [
                            'channelUserMsisdn' => $channelUserMsisdn,
                            'pin' => $this->pin,
                            'subscriberMsisdn' => $subscriberMsisdn,
                            'amount' => (int) $amount,
                            'orderId' => $orderId,
                            'description' => $description,
                            'payToken' => $payToken,
                            'notifUrl' => env('APP_URL', 'http://localhost') . '/api/orange-money/webhook',
                            'NotifUrl' => env('APP_URL', 'http://localhost') . '/api/orange-money/webhook'
                        ]);

                        Log::info("Orange Money Pay execution response for token {$payToken}. Status: " . $payResponse->status() . " Body: " . $payResponse->body());

                        // If the pay execution returns a validation or business error (e.g. 417 for insufficient funds)
                        if ($payResponse->status() !== 200 && $payResponse->status() !== 201) {
                            $payData = $payResponse->json();
                            $errorMessage = $payData['message'] ?? ($payData['data']['inittxnmessage'] ?? null);
                            if ($errorMessage) {
                                // Clean up prefix code if present, e.g. "60019 :: Le solde..."
                                if (preg_match('/^\d+\s*::\s*(.*)$/', $errorMessage, $matches)) {
                                    $errorMessage = trim($matches[1]);
                                }
                                return [
                                    'success' => false,
                                    'error' => 'Rejet Orange Money : ' . $errorMessage
                                ];
                            }
                        }
                    } catch (\Exception $payEx) {
                        Log::error("Orange Money Pay execution failed for token {$payToken}: " . $payEx->getMessage());
                    }
                }

                return [
                    'success' => true,
                    'is_simulation' => false,
                    'pay_token' => $payToken,
                    'raw' => $data
                ];
            }

            Log::error('Orange Money Payment Init Failure: ' . $response->body());
            return [
                'success' => false,
                'error' => 'La transaction a été rejetée par Orange Money : ' . ($response->json()['message'] ?? 'Erreur inconnue')
            ];
        } catch (\Exception $e) {
            Log::error('Orange Money Payment Init Exception: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Erreur technique lors de l\'initialisation du paiement.'
            ];
        }
    }

    /**
     * Check payment status on Orange Money servers.
     */
    public function verifyPaymentStatus($payToken)
    {
        if (str_starts_with($payToken, 'SIM_') || env('ORANGE_MONEY_SIMULATION', false)) {
            return [
                'success' => true,
                'status' => 'success',
                'message' => 'Paiement simulé réussi.'
            ];
        }

        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return [
                'success' => false,
                'status' => 'pending',
                'error' => 'Impossible de s\'authentifier auprès d\'Orange Money.'
            ];
        }

        $xAuthToken = base64_encode($this->apiUsername . ':' . $this->apiPassword);
        $statusUrl = 'https://api-s1.orange.cm/omcoreapis/1.0.2/mp/paymentstatus/' . $payToken;

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'X-Auth-Token' => $xAuthToken,
                'Content-Type' => 'application/json'
            ])->get($statusUrl);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Orange Money Payment Status check: ', $data);
                
                $statusStr = $data['status'] ?? ($data['data']['status'] ?? null);
                
                // standardizing status string to lowercase: success, pending, failed
                $normalizedStatus = 'pending';
                if ($statusStr) {
                    $statusUpper = strtoupper($statusStr);
                    if (in_array($statusUpper, ['SUCCESSFUL', 'SUCCESS', 'COMPLETED'])) {
                        $normalizedStatus = 'success';
                    } elseif (in_array($statusUpper, ['FAILED', 'EXPIRED', 'REJECTED'])) {
                        $normalizedStatus = 'failed';
                    }
                }

                return [
                    'success' => true,
                    'status' => $normalizedStatus,
                    'raw_status' => $statusStr,
                    'raw' => $data
                ];
            }

            Log::error('Orange Money Status Check Failure: ' . $response->body());
            return [
                'success' => false,
                'status' => 'pending',
                'error' => 'Erreur lors de la vérification du statut auprès d\'Orange : ' . ($response->json()['message'] ?? 'Erreur inconnue')
            ];
        } catch (\Exception $e) {
            Log::error('Orange Money Status Check Exception: ' . $e->getMessage());
            return [
                'success' => false,
                'status' => 'pending',
                'error' => 'Erreur technique lors de la vérification du statut.'
            ];
        }
    }
}
