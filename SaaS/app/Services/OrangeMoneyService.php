<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrangeMoneyService
{
    protected $consumerKey = 'yMaymHkvDfGmg_g7hUzgn1SYrUwa';
    protected $consumerSecret = 'Cq3go3r1hYUSH4sEqf4ELf8IdaAa';
    protected $apiUsername = 'MULTIBUSINESSSARL@OMAPI';
    protected $apiPassword = 'MULTIBUSINESSSARL@OMAPI@2025';
    protected $channelUserMsisdn = '655743248';
    protected $pin = '1990';
    protected $initUrl = 'https://api-s1.orange.cm/omcoreapis/1.0.2/mp/init';
    protected $tokenUrl = 'https://api-s1.orange.cm/token'; // standard Orange token endpoint

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
        // Normalize phone number (must be Cameroon number format: e.g. 6xxxxxxxx)
        $subscriberMsisdn = preg_replace('/\D/', '', $subscriberMsisdn);
        if (strlen($subscriberMsisdn) === 9 && str_starts_with($subscriberMsisdn, '6')) {
            // Cameroon local format
        } elseif (strlen($subscriberMsisdn) === 12 && str_starts_with($subscriberMsisdn, '237')) {
            $subscriberMsisdn = substr($subscriberMsisdn, 3);
        }

        // Support Simulation Mode for Staging/Demo/Tests
        if (
            str_starts_with($subscriberMsisdn, '600') || 
            $subscriberMsisdn === '655743248' || 
            config('app.env') !== 'production'
        ) {
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
                'channelUserMsisdn' => $this->channelUserMsisdn,
                'pin' => $this->pin,
                'subscriberMsisdn' => $subscriberMsisdn,
                'amount' => (int) $amount,
                'orderId' => $orderId,
                'description' => $description,
                'payToken' => ''
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'is_simulation' => false,
                    'pay_token' => $data['payToken'] ?? ($data['data']['payToken'] ?? null),
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
}
