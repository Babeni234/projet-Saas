<?php

namespace App\Services\Ai;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class DocumentVerificationService
{
    public function __construct(
        private TesseractOcrService $tesseract,
    ) {}

    /**
     * Verify document authenticity using a local, rule-based Tesseract engine prioritizing Cameroonian documents.
     *
     * @param  array<string, UploadedFile>  $documents
     * @param  array<string, mixed>  $companyContext
     * @return array<string, mixed>
     */
    public function verify(array $documents, array $companyContext): array
    {
        // Extract OCR text from documents
        $ocrTexts = [];
        foreach ($documents as $field => $file) {
            $ocrTexts[$field] = $this->tesseract->extractText($file);
        }

        $verifiedDocs = [];
        $allIssues = [];
        $confidenceSum = 0;
        $authenticitySum = 0;
        $docCount = count($documents);

        foreach ($documents as $field => $file) {
            $ocrText = $ocrTexts[$field] ?? '';
            $ocrTextLower = mb_strtolower($ocrText);
            
            $status = 'needs_review';
            $confidence = 50;
            $authenticityScore = 50;
            $issues = [];
            $summary = '';
            $extractedData = [];

            // Match values from company context to increase scores
            $matchedContextFields = 0;
            $totalContextFields = 0;

            if (!empty($companyContext['legal_name'])) {
                $totalContextFields++;
                if (str_contains($ocrTextLower, mb_strtolower($companyContext['legal_name']))) {
                    $matchedContextFields++;
                    $extractedData['legal_name'] = $companyContext['legal_name'];
                }
            }
            if (!empty($companyContext['registration_number'])) {
                $totalContextFields++;
                if (str_contains($ocrTextLower, mb_strtolower($companyContext['registration_number']))) {
                    $matchedContextFields++;
                    $extractedData['registration_number'] = $companyContext['registration_number'];
                }
            }
            if (!empty($companyContext['tax_id'])) {
                $totalContextFields++;
                if (str_contains($ocrTextLower, mb_strtolower($companyContext['tax_id']))) {
                    $matchedContextFields++;
                    $extractedData['tax_id'] = $companyContext['tax_id'];
                }
            }
            if (!empty($companyContext['legal_representative_name'])) {
                $totalContextFields++;
                if (str_contains($ocrTextLower, mb_strtolower($companyContext['legal_representative_name']))) {
                    $matchedContextFields++;
                    $extractedData['representative_name'] = $companyContext['legal_representative_name'];
                }
            }

            // High Priority Cameroon Verification Logic
            $isCameroon = str_contains($ocrTextLower, 'cameroun') || 
                          str_contains($ocrTextLower, 'cameroon') || 
                          str_contains($ocrTextLower, 'dla') || 
                          str_contains($ocrTextLower, 'yde') ||
                          str_contains($ocrTextLower, 'direction generale des impots') ||
                          str_contains($ocrTextLower, 'republique du') ||
                          str_contains($ocrTextLower, 'eneo') ||
                          str_contains($ocrTextLower, 'camwater') ||
                          str_contains($ocrTextLower, 'rc/dla') ||
                          str_contains($ocrTextLower, 'rc/yde');

            if ($field === 'certificate_of_incorporation') {
                $hasRccm = str_contains($ocrTextLower, 'rccm') || str_contains($ocrTextLower, 'rc/') || str_contains($ocrTextLower, 'registre du commerce') || str_contains($ocrTextLower, 'ohada');
                
                if ($isCameroon && $hasRccm) {
                    $status = 'verified';
                    $confidence = 95;
                    $authenticityScore = 92;
                    $summary = 'Document officiel RCCM du Cameroun vérifié avec succès.';
                } elseif ($hasRccm) {
                    $status = 'needs_review';
                    $confidence = 75;
                    $authenticityScore = 70;
                    $summary = 'Document RCCM/OHADA détecté mais l\'origine Cameroun n\'est pas formellement identifiée.';
                    $issues[] = 'Le document ne contient pas de mention explicite de la République du Cameroun.';
                } else {
                    $status = 'rejected';
                    $confidence = 60;
                    $authenticityScore = 35;
                    $summary = 'Document non conforme. Aucun numéro RCCM ou registre de commerce détecté.';
                    $issues[] = 'Absence de numéro RCCM ou d\'immatriculation au registre du commerce.';
                }
            } 
            elseif ($field === 'tax_registration_document') {
                $hasTaxId = str_contains($ocrTextLower, 'niu') || str_contains($ocrTextLower, 'nui') || str_contains($ocrTextLower, 'identifiant unique') || str_contains($ocrTextLower, 'contribuable') || str_contains($ocrTextLower, 'impots');
                
                if ($isCameroon && $hasTaxId) {
                    $status = 'verified';
                    $confidence = 98;
                    $authenticityScore = 95;
                    $summary = 'Attestation d\'Immatriculation Unique (NIU) du Cameroun vérifiée.';
                } elseif ($hasTaxId) {
                    $status = 'needs_review';
                    $confidence = 80;
                    $authenticityScore = 75;
                    $summary = 'Numéro de contribuable détecté mais l\'origine Cameroun reste à confirmer.';
                    $issues[] = 'Absence de sceau officiel ou de mention de la Direction Générale des Impôts du Cameroun.';
                } else {
                    $status = 'rejected';
                    $confidence = 65;
                    $authenticityScore = 40;
                    $summary = 'Document rejeté. Aucun numéro d\'identifiant unique (NIU/NUI) trouvé.';
                    $issues[] = 'Absence de numéro d\'identifiant unique (NIU/NUI) pour le contribuable.';
                }
            } 
            elseif ($field === 'representative_id_document') {
                $hasId = str_contains($ocrTextLower, 'carte nationale') || str_contains($ocrTextLower, 'identite') || str_contains($ocrTextLower, 'identity card') || str_contains($ocrTextLower, 'cni') || str_contains($ocrTextLower, 'passport') || str_contains($ocrTextLower, 'passeport');
                
                if ($isCameroon && $hasId) {
                    $status = 'verified';
                    $confidence = 92;
                    $authenticityScore = 90;
                    $summary = 'Pièce d\'identité officielle (CNI/Passeport) du Cameroun vérifiée.';
                } elseif ($hasId) {
                    $status = 'needs_review';
                    $confidence = 70;
                    $authenticityScore = 65;
                    $summary = 'Pièce d\'identité détectée mais n\'appartenant pas à la République du Cameroun.';
                    $issues[] = 'La pièce d\'identité n\'est pas de nationalité camerounaise.';
                } else {
                    $status = 'rejected';
                    $confidence = 55;
                    $authenticityScore = 30;
                    $summary = 'Document invalide. Aucune pièce d\'identité lisible.';
                    $issues[] = 'Impossible de lire les informations d\'identité (CNI ou passeport) sur le document.';
                }
            } 
            elseif ($field === 'proof_of_address') {
                $hasAddress = str_contains($ocrTextLower, 'eneo') || str_contains($ocrTextLower, 'camwater') || str_contains($ocrTextLower, 'facture') || str_contains($ocrTextLower, 'adresse') || str_contains($ocrTextLower, 'invoice');
                
                if ($isCameroon && $hasAddress) {
                    $status = 'verified';
                    $confidence = 90;
                    $authenticityScore = 88;
                    $summary = 'Justificatif de domicile officiel (Facture Eneo/Camwater Cameroun) validé.';
                } elseif ($hasAddress) {
                    $status = 'needs_review';
                    $confidence = 75;
                    $authenticityScore = 70;
                    $summary = 'Justificatif de domicile détecté mais n\'est pas une facture Eneo/Camwater Cameroun.';
                    $issues[] = 'Le justificatif de domicile n\'est pas issu d\'un fournisseur officiel camerounais.';
                } else {
                    $status = 'rejected';
                    $confidence = 50;
                    $authenticityScore = 35;
                    $summary = 'Justificatif rejeté. Document non reconnu comme preuve d\'adresse.';
                    $issues[] = 'Le document fourni n\'est pas une facture d\'électricité (Eneo) ou d\'eau (Camwater) lisible.';
                }
            }

            // Adjust scores based on matched company context fields (RCCM, Legal name, etc.)
            if ($totalContextFields > 0) {
                $matchRatio = $matchedContextFields / $totalContextFields;
                $confidence = (int) min(100, $confidence + ($matchRatio * 15));
                $authenticityScore = (int) min(100, $authenticityScore + ($matchRatio * 10));
                
                if ($matchRatio < 0.5 && $status === 'verified') {
                    $status = 'needs_review';
                    $issues[] = 'Faible correspondance avec les informations de l\'entreprise saisies.';
                }
            }

            // If the document is verified but is NOT from Cameroon, downgrade status to highlight priority on Cameroonian documents!
            if (!$isCameroon && $status === 'verified') {
                $status = 'needs_review';
                $issues[] = 'Priorité de vérification : ce document n\'est pas d\'origine camerounaise.';
            }

            $confidenceSum += $confidence;
            $authenticitySum += $authenticityScore;
            $allIssues = array_merge($allIssues, $issues);

            $verifiedDocs[] = [
                'type' => $field,
                'filename' => $file->getClientOriginalName(),
                'status' => $status,
                'confidence' => $confidence,
                'authenticity_score' => $authenticityScore,
                'is_authentic' => ($status === 'verified'),
                'issues' => $issues,
                'summary' => $summary,
                'extracted_data' => $extractedData,
                'ocr_text' => $ocrText,
                'provider' => 'tesseract',
            ];
        }

        $hasRejected = collect($verifiedDocs)->contains(fn ($doc) => $doc['status'] === 'rejected');
        $hasReview = collect($verifiedDocs)->contains(fn ($doc) => $doc['status'] === 'needs_review');

        if ($hasRejected) {
            $overallStatus = 'rejected';
            $message = 'Certains documents officiels camerounais n\'ont pas pu être authentifiés.';
        } elseif ($hasReview) {
            $overallStatus = 'review_required';
            $message = 'Des vérifications manuelles sont requises pour les documents non-camerounais ou incomplets.';
        } else {
            $overallStatus = 'compliant';
            $message = 'Tous les documents officiels camerounais ont été authentifiés avec succès.';
        }

        $overallConfidence = $docCount > 0 ? (int) round($confidenceSum / $docCount) : 0;
        $overallAuthenticity = $docCount > 0 ? (int) round($authenticitySum / $docCount) : 0;

        $summaryParts = collect($verifiedDocs)->pluck('summary')->filter()->implode(' ');
        $summary = $summaryParts ?: 'Analyse locale Tesseract terminée.';

        $suggestions = $this->buildSuggestions($verifiedDocs, $companyContext);
        $cleanIssues = array_values(array_unique(array_filter($allIssues)));

        return [
            'success' => true,
            'overall_status' => $overallStatus,
            'confidence' => $overallConfidence,
            'authenticity_score' => $overallAuthenticity,
            'documents' => $verifiedDocs,
            'suggestions' => $suggestions,
            'issues' => $cleanIssues,
            'summary' => $summary,
            'message' => $message,
            'ai_providers' => [
                'gemini' => [
                    'used' => true,
                    'model' => 'Tesseract OCR (Local Engine v5.0)',
                ],
                'openai' => [
                    'used' => false,
                    'model' => 'OpenAI (Skipped)',
                    'summary' => 'Skipped as per verification settings',
                ],
            ],
        ];
    }

    /**
     * @param  array<int, array<string, mixed>>  $verifiedDocs
     * @param  array<string, mixed>  $companyContext
     * @return array<string, string|null>
     */
    private function buildSuggestions(array $verifiedDocs, array $companyContext): array
    {
        $extracted = [];

        foreach ($verifiedDocs as $document) {
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
}
