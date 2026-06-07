<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Ai\DocumentVerificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RegisterDocumentAnalysisController extends Controller
{
    private const DOCUMENT_FIELDS = [
        'certificate_of_incorporation',
        'tax_registration_document',
        'representative_id_document',
        'proof_of_address',
    ];

    public function __construct(
        private DocumentVerificationService $verificationService,
    ) {}

    public function analyze(Request $request): JsonResponse
    {
        $request->validate([
            'certificate_of_incorporation' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'tax_registration_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'representative_id_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'proof_of_address' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'company_logo' => 'nullable|file|mimes:jpg,jpeg,png,svg,webp|max:2048',
            'legal_name' => 'nullable|string|max:255',
            'registration_number' => 'nullable|string|max:100',
            'tax_id' => 'nullable|string|max:100',
            'legal_representative_name' => 'nullable|string|max:255',
            'business_type' => 'nullable|string|in:real_estate,hotel',
            'country' => 'nullable|string|max:2',
        ]);

        $documents = [];
        foreach (self::DOCUMENT_FIELDS as $field) {
            if ($request->hasFile($field)) {
                $documents[$field] = $request->file($field);
            }
        }

        $companyContext = array_filter([
            'legal_name' => $request->input('legal_name'),
            'registration_number' => $request->input('registration_number'),
            'tax_id' => $request->input('tax_id'),
            'legal_representative_name' => $request->input('legal_representative_name'),
            'business_type' => $request->input('business_type'),
            'country' => $request->input('country'),
        ]);

        try {
            $result = $this->verificationService->verify($documents, $companyContext);

            if (! ($result['success'] ?? false)) {
                return response()->json($result, 503);
            }

            return response()->json($result);
        } catch (\Throwable $exception) {
            Log::error('Document AI verification failed', [
                'error' => $exception->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'AI verification failed. Please try again or contact support.',
                'error_code' => 'verification_failed',
            ], 500);
        }
    }
}
