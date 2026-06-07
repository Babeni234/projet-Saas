<?php

namespace App\Services\Ai;

use Illuminate\Http\UploadedFile;

class DocumentVerificationService
{
    public function __construct(
        private GeminiDocumentVerifier $gemini,
        private OpenAiDocumentVerifier $openai,
    ) {}

    /**
     * @param  array<string, UploadedFile>  $documents
     * @param  array<string, mixed>  $companyContext
     * @return array<string, mixed>
     */
    public function verify(array $documents, array $companyContext): array
    {
        if (! $this->gemini->isConfigured()) {
            return [
                'success' => false,
                'message' => 'Gemini API key is missing. Add GEMINI_API_KEY to your .env file.',
                'error_code' => 'gemini_not_configured',
            ];
        }

        $geminiResults = $this->gemini->verifyDocuments($documents, $companyContext);

        $openAiResult = null;
        if ($this->openai->isConfigured()) {
            $openAiResult = $this->openai->crossValidate($geminiResults, $companyContext);
        }

        $overallStatus = $this->resolveOverallStatus($geminiResults, $openAiResult);
        $confidence = $this->resolveConfidence($geminiResults, $openAiResult);
        $authenticityScore = $this->averageAuthenticity($geminiResults);
        $suggestions = $this->buildSuggestions($geminiResults, $companyContext);
        $issues = $this->collectIssues($geminiResults, $openAiResult);

        return [
            'success' => true,
            'overall_status' => $overallStatus,
            'confidence' => $confidence,
            'authenticity_score' => $authenticityScore,
            'documents' => $geminiResults,
            'suggestions' => $suggestions,
            'issues' => $issues,
            'summary' => $this->buildSummary($overallStatus, $openAiResult, $geminiResults),
            'message' => $this->buildMessage($overallStatus),
            'ai_providers' => [
                'gemini' => [
                    'used' => true,
                    'model' => config('ai.gemini_model'),
                ],
                'openai' => $openAiResult ?? [
                    'used' => false,
                    'model' => config('ai.openai_model'),
                ],
            ],
        ];
    }

    /**
     * @param  array<int, array<string, mixed>>  $geminiResults
     * @param  array<string, mixed>|null  $openAiResult
     */
    private function resolveOverallStatus(array $geminiResults, ?array $openAiResult): string
    {
        $hasRejected = collect($geminiResults)->contains(fn ($doc) => ($doc['status'] ?? '') === 'rejected');
        $hasReview = collect($geminiResults)->contains(fn ($doc) => ($doc['status'] ?? '') === 'needs_review');

        if ($openAiResult && ($openAiResult['overall_recommendation'] ?? '') === 'reject') {
            return 'rejected';
        }

        if ($hasRejected) {
            return 'rejected';
        }

        if ($hasReview || ($openAiResult && ($openAiResult['overall_recommendation'] ?? '') === 'review')) {
            return 'review_required';
        }

        return 'compliant';
    }

    /**
     * @param  array<int, array<string, mixed>>  $geminiResults
     * @param  array<string, mixed>|null  $openAiResult
     */
    private function resolveConfidence(array $geminiResults, ?array $openAiResult): int
    {
        $geminiAvg = (int) collect($geminiResults)->avg(fn ($doc) => $doc['confidence'] ?? 0);

        if ($openAiResult && ($openAiResult['used'] ?? false)) {
            return (int) round(($geminiAvg + ($openAiResult['confidence'] ?? 0)) / 2);
        }

        return $geminiAvg;
    }

    /**
     * @param  array<int, array<string, mixed>>  $geminiResults
     */
    private function averageAuthenticity(array $geminiResults): int
    {
        if ($geminiResults === []) {
            return 0;
        }

        return (int) round(collect($geminiResults)->avg(fn ($doc) => $doc['authenticity_score'] ?? 0));
    }

    /**
     * @param  array<int, array<string, mixed>>  $geminiResults
     * @param  array<string, mixed>  $companyContext
     * @return array<string, string|null>
     */
    private function buildSuggestions(array $geminiResults, array $companyContext): array
    {
        $extracted = [];

        foreach ($geminiResults as $document) {
            foreach (($document['extracted_data'] ?? []) as $key => $value) {
                if (filled($value) && ! isset($extracted[$key])) {
                    $extracted[$key] = $value;
                }
            }
        }

        return array_filter([
            'legal_name' => $extracted['legal_name'] ?? $companyContext['legal_name'] ?? null,
            'registration_number' => $extracted['registration_number'] ?? $companyContext['registration_number'] ?? null,
            'tax_id' => $extracted['tax_id'] ?? $companyContext['tax_id'] ?? null,
            'legal_representative_name' => $extracted['representative_name'] ?? $companyContext['legal_representative_name'] ?? null,
        ]);
    }

    /**
     * @param  array<int, array<string, mixed>>  $geminiResults
     * @param  array<string, mixed>|null  $openAiResult
     * @return array<int, string>
     */
    private function collectIssues(array $geminiResults, ?array $openAiResult): array
    {
        $issues = collect($geminiResults)
            ->flatMap(fn ($doc) => $doc['issues'] ?? [])
            ->filter()
            ->values()
            ->all();

        if ($openAiResult) {
            $issues = array_merge($issues, $openAiResult['risk_flags'] ?? []);
        }

        return array_values(array_unique($issues));
    }

    /**
     * @param  array<int, array<string, mixed>>  $geminiResults
     */
    private function buildSummary(string $overallStatus, ?array $openAiResult, array $geminiResults): string
    {
        if ($openAiResult && filled($openAiResult['summary'] ?? null)) {
            return $openAiResult['summary'];
        }

        $summaries = collect($geminiResults)->pluck('summary')->filter()->implode(' ');

        return $summaries ?: match ($overallStatus) {
            'compliant' => 'All submitted documents passed authenticity screening.',
            'rejected' => 'One or more documents failed authenticity checks.',
            default => 'Documents require manual compliance review.',
        };
    }

    private function buildMessage(string $overallStatus): string
    {
        return match ($overallStatus) {
            'compliant' => 'All documents passed AI compliance screening.',
            'rejected' => 'Document authenticity could not be confirmed. Please upload valid documents.',
            default => 'Documents received. Some items require manual review.',
        };
    }
}
