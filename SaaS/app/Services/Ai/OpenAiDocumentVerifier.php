<?php

namespace App\Services\Ai;

use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;
use RuntimeException;

class OpenAiDocumentVerifier
{
    public function isConfigured(): bool
    {
        return filled(config('openai.api_key'));
    }

    /**
     * Cross-validate Gemini results and produce a compliance summary.
     *
     * @param  array<int, array<string, mixed>>  $geminiResults
     * @param  array<string, mixed>  $companyContext
     * @return array<string, mixed>
     */
    public function crossValidate(array $geminiResults, array $companyContext): array
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('OPENAI_API_KEY is not configured.');
        }

        $payload = json_encode([
            'company' => $companyContext,
            'gemini_analysis' => $geminiResults,
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        try {
            $response = OpenAI::chat()->create([
                'model' => config('ai.openai_model'),
                'response_format' => ['type' => 'json_object'],
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a senior compliance officer specialized in Cameroonian business regulations and document verification. Your expertise covers: Cameroonian commercial law, OHADA (Organisation pour l\'Harmonisation en Afrique du Droit des Affaires) regulations, Cameroon tax regulations (DGFiP), Ministry of Commerce requirements, and local business registration procedures (RCCM). Review AI document verification results and produce a final assessment. Respond in JSON with keys: overall_recommendation (approve|review|reject), confidence (0-100), summary (string in English), risk_flags (array of strings), openai_verdict (string).',
                    ],
                    [
                        'role' => 'user',
                        'content' => "Review these document verification results and company context in the Cameroonian business environment:\n\n{$payload}",
                    ],
                ],
            ]);

            $content = $response->choices[0]->message->content ?? '{}';
            $parsed = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

            return [
                'used' => true,
                'model' => config('ai.openai_model'),
                'overall_recommendation' => $parsed['overall_recommendation'] ?? 'review',
                'confidence' => max(0, min(100, (int) ($parsed['confidence'] ?? 0))),
                'summary' => (string) ($parsed['summary'] ?? ''),
                'risk_flags' => array_values($parsed['risk_flags'] ?? []),
                'verdict' => (string) ($parsed['openai_verdict'] ?? ''),
            ];
        } catch (\Throwable $exception) {
            Log::warning('OpenAI cross-validation failed', ['error' => $exception->getMessage()]);

            return [
                'used' => false,
                'model' => config('ai.openai_model'),
                'summary' => 'OpenAI cross-validation unavailable.',
                'risk_flags' => [],
                'verdict' => '',
            ];
        }
    }
}
