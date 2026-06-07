<?php

namespace App\Services\Ai;

use Gemini\Data\Blob;
use Gemini\Data\GenerationConfig;
use Gemini\Data\Schema;
use Gemini\Enums\DataType;
use Gemini\Enums\MimeType;
use Gemini\Enums\ResponseMimeType;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class GeminiDocumentVerifier
{
    private const DOCUMENT_DEFINITIONS = [
        'certificate_of_incorporation' => [
            'type' => 'certificate',
            'label' => 'Certificate of incorporation (Kbis / CRN)',
        ],
        'tax_registration_document' => [
            'type' => 'tax',
            'label' => 'Tax registration certificate (ACF / VAT / TIN)',
        ],
        'representative_id_document' => [
            'type' => 'representative_id',
            'label' => 'Legal representative ID (CNI / Passport)',
        ],
        'proof_of_address' => [
            'type' => 'proof_of_address',
            'label' => 'Proof of registered address',
        ],
    ];

    public function isConfigured(): bool
    {
        return filled(config('gemini.api_key'));
    }

    /**
     * @param  array<string, UploadedFile>  $documents
     * @param  array<string, mixed>  $companyContext
     * @return array<int, array<string, mixed>>
     */
    public function verifyDocuments(array $documents, array $companyContext): array
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('GEMINI_API_KEY is not configured.');
        }

        $results = [];

        foreach ($documents as $field => $file) {
            $definition = self::DOCUMENT_DEFINITIONS[$field] ?? [
                'type' => $field,
                'label' => $field,
            ];

            try {
                $results[] = $this->verifySingleDocument($file, $definition, $companyContext);
            } catch (\Throwable $exception) {
                Log::error('Gemini document verification failed', [
                    'document' => $field,
                    'error' => $exception->getMessage(),
                ]);

                $results[] = [
                    'type' => $definition['type'],
                    'filename' => $file->getClientOriginalName(),
                    'status' => 'needs_review',
                    'confidence' => 0,
                    'authenticity_score' => 0,
                    'is_authentic' => false,
                    'issues' => ['AI analysis could not be completed for this document.'],
                    'summary' => 'Manual review required.',
                    'extracted_data' => [],
                    'provider' => 'gemini',
                ];
            }
        }

        return $results;
    }

    /**
     * @param  array{type: string, label: string}  $definition
     * @param  array<string, mixed>  $companyContext
     * @return array<string, mixed>
     */
    private function verifySingleDocument(UploadedFile $file, array $definition, array $companyContext): array
    {
        $prompt = $this->buildPrompt($definition['label'], $companyContext);

        $response = Gemini::generativeModel(model: config('ai.gemini_model'))
            ->withGenerationConfig(new GenerationConfig(
                responseMimeType: ResponseMimeType::APPLICATION_JSON,
                responseSchema: new Schema(
                    type: DataType::OBJECT,
                    properties: [
                        'status' => new Schema(type: DataType::STRING),
                        'confidence' => new Schema(type: DataType::INTEGER),
                        'authenticity_score' => new Schema(type: DataType::INTEGER),
                        'is_authentic' => new Schema(type: DataType::BOOLEAN),
                        'issues' => new Schema(
                            type: DataType::ARRAY,
                            items: new Schema(type: DataType::STRING),
                        ),
                        'summary' => new Schema(type: DataType::STRING),
                        'extracted_data' => new Schema(
                            type: DataType::OBJECT,
                            properties: [
                                'legal_name' => new Schema(type: DataType::STRING),
                                'registration_number' => new Schema(type: DataType::STRING),
                                'tax_id' => new Schema(type: DataType::STRING),
                                'representative_name' => new Schema(type: DataType::STRING),
                                'representative_id' => new Schema(type: DataType::STRING),
                                'address' => new Schema(type: DataType::STRING),
                                'expiry_date' => new Schema(type: DataType::STRING),
                            ],
                        ),
                    ],
                    required: ['status', 'confidence', 'authenticity_score', 'is_authentic', 'issues', 'summary'],
                ),
            ))
            ->generateContent([
                $prompt,
                new Blob(
                    mimeType: $this->resolveMimeType($file),
                    data: base64_encode($file->get()),
                ),
            ]);

        $analysis = $response->json();

        $status = in_array($analysis['status'] ?? '', ['verified', 'needs_review', 'rejected'], true)
            ? $analysis['status']
            : 'needs_review';

        return [
            'type' => $definition['type'],
            'filename' => $file->getClientOriginalName(),
            'status' => $status,
            'confidence' => max(0, min(100, (int) ($analysis['confidence'] ?? 0))),
            'authenticity_score' => max(0, min(100, (int) ($analysis['authenticity_score'] ?? 0))),
            'is_authentic' => (bool) ($analysis['is_authentic'] ?? false),
            'issues' => array_values($analysis['issues'] ?? []),
            'summary' => (string) ($analysis['summary'] ?? ''),
            'extracted_data' => (array) ($analysis['extracted_data'] ?? []),
            'provider' => 'gemini',
        ];
    }

    /**
     * @param  array<string, mixed>  $companyContext
     */
    private function buildPrompt(string $documentLabel, array $companyContext): string
    {
        $context = json_encode(array_filter($companyContext), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        return <<<PROMPT
You are a KYB/KYC compliance expert. Analyze the uploaded document for authenticity and legal validity.

Expected document type: {$documentLabel}

Company data submitted by the applicant (cross-check when possible):
{$context}

Tasks:
1. Confirm the document type matches what is expected.
2. Assess authenticity (signatures, stamps, format, tampering indicators).
3. Detect inconsistencies with the submitted company data.
4. Extract key legal fields when readable.

Return JSON only with:
- status: "verified" if authentic and consistent, "needs_review" if uncertain, "rejected" if clearly invalid or fraudulent
- confidence: 0-100
- authenticity_score: 0-100
- is_authentic: boolean
- issues: array of specific findings (empty if none)
- summary: one concise sentence in English
- extracted_data: object with any readable fields
PROMPT;
    }

    private function resolveMimeType(UploadedFile $file): MimeType
    {
        return match ($file->getMimeType()) {
            'application/pdf' => MimeType::APPLICATION_PDF,
            'image/png' => MimeType::IMAGE_PNG,
            'image/jpeg', 'image/jpg' => MimeType::IMAGE_JPEG,
            default => throw new RuntimeException('Unsupported file type: '.$file->getMimeType()),
        };
    }
}
