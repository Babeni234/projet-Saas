<?php

namespace App\Services\Ai;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class DocumentVerificationService
{
    /**
     * Map of 150+ ISO 3166-1 alpha-2 country codes to French names.
     */
    private const COUNTRIES = [
        'af' => 'Afghanistan',
        'za' => 'Afrique du Sud',
        'al' => 'Albanie',
        'dz' => 'Algérie',
        'de' => 'Allemagne',
        'ad' => 'Andorre',
        'ao' => 'Angola',
        'sa' => 'Arabie Saoudite',
        'ar' => 'Argentine',
        'am' => 'Arménie',
        'au' => 'Australie',
        'at' => 'Autriche',
        'az' => 'Azerbaïdjan',
        'bs' => 'Bahamas',
        'bd' => 'Bangladesh',
        'be' => 'Belgique',
        'bz' => 'Belize',
        'bj' => 'Bénin',
        'by' => 'Biélorussie',
        'mm' => 'Myanmar',
        'bo' => 'Bolivie',
        'ba' => 'Bosnie-Herzégovine',
        'bw' => 'Botswana',
        'br' => 'Brésil',
        'bg' => 'Bulgarie',
        'bf' => 'Burkina Faso',
        'bi' => 'Burundi',
        'kh' => 'Cambodge',
        'cm' => 'Cameroun',
        'ca' => 'Canada',
        'cv' => 'Cap-Vert',
        'cf' => 'République Centrafricaine',
        'cl' => 'Chili',
        'cn' => 'Chine',
        'cy' => 'Chypre',
        'co' => 'Colombie',
        'km' => 'Comores',
        'cg' => 'Congo-Brazzaville',
        'cd' => 'Congo-Kinshasa (RDC)',
        'kp' => 'Corée du Nord',
        'kr' => 'Corée du Sud',
        'cr' => 'Costa Rica',
        'ci' => 'Côte d\'Ivoire',
        'hr' => 'Croatie',
        'cu' => 'Cuba',
        'dk' => 'Danemark',
        'dj' => 'Djibouti',
        'do' => 'République Dominicaine',
        'dm' => 'Dominique',
        'eg' => 'Égypte',
        'ae' => 'Émirats Arabes Unis',
        'ec' => 'Équateur',
        'er' => 'Érythrée',
        'es' => 'Espagne',
        'ee' => 'Estonie',
        'us' => 'États-Unis',
        'et' => 'Éthiopie',
        'fj' => 'Fidji',
        'fi' => 'Finlande',
        'fr' => 'France',
        'ga' => 'Gabon',
        'gm' => 'Gambie',
        'ge' => 'Géorgie',
        'gh' => 'Ghana',
        'gr' => 'Grèce',
        'gt' => 'Guatemala',
        'gn' => 'Guinée',
        'gq' => 'Guinée Équatoriale',
        'gw' => 'Guinée-Bissau',
        'gy' => 'Guyana',
        'ht' => 'Haïti',
        'hn' => 'Honduras',
        'hu' => 'Hongrie',
        'in' => 'Inde',
        'id' => 'Indonésie',
        'ir' => 'Iran',
        'iq' => 'Irak',
        'ie' => 'Irlande',
        'is' => 'Islande',
        'il' => 'Israël',
        'it' => 'Italie',
        'jm' => 'Jamaïque',
        'jp' => 'Japon',
        'jo' => 'Jordanie',
        'kz' => 'Kazakhstan',
        'ke' => 'Kenya',
        'kg' => 'Kirghizistan',
        'ki' => 'Kiribati',
        'kw' => 'Koweït',
        'la' => 'Laos',
        'ls' => 'Lesotho',
        'lv' => 'Lettonie',
        'lb' => 'Liban',
        'lr' => 'Liberia',
        'ly' => 'Libye',
        'li' => 'Liechtenstein',
        'lt' => 'Lituanie',
        'lu' => 'Luxembourg',
        'mk' => 'Macédoine du Nord',
        'mg' => 'Madagascar',
        'my' => 'Malaisie',
        'mw' => 'Malawi',
        'mv' => 'Maldives',
        'ml' => 'Mali',
        'mt' => 'Malte',
        'ma' => 'Maroc',
        'mu' => 'Maurice',
        'mr' => 'Mauritanie',
        'mx' => 'Mexique',
        'md' => 'Moldavie',
        'mc' => 'Monaco',
        'mn' => 'Mongolie',
        'me' => 'Monténégro',
        'mz' => 'Mozambique',
        'na' => 'Namibie',
        'np' => 'Népal',
        'ni' => 'Nicaragua',
        'ne' => 'Niger',
        'ng' => 'Nigeria',
        'no' => 'Norvège',
        'nz' => 'Nouvelle-Zélande',
        'om' => 'Oman',
        'ug' => 'Ouganda',
        'uz' => 'Ouzbékistan',
        'pk' => 'Pakistan',
        'pa' => 'Panama',
        'pg' => 'Papouasie-Nouvelle-Guinée',
        'py' => 'Paraguay',
        'nl' => 'Pays-Bas',
        'pe' => 'Pérou',
        'ph' => 'Philippines',
        'pl' => 'Pologne',
        'pt' => 'Portugal',
        'qa' => 'Qatar',
        'cz' => 'République Tchèque',
        'ro' => 'Roumanie',
        'gb' => 'Royaume-Uni',
        'ru' => 'Russie',
        'rw' => 'Rwanda',
        'kn' => 'Saint-Christophe-et-Niévès',
        'sm' => 'Saint-Marin',
        'vc' => 'Saint-Vincent-et-les-Grenadines',
        'lc' => 'Sainte-Lucie',
        'ws' => 'Samoa',
        'st' => 'Sao Tomé-et-Principe',
        'sn' => 'Sénégal',
        'rs' => 'Serbie',
        'sc' => 'Seychelles',
        'sl' => 'Sierra Leone',
        'sg' => 'Singapour',
        'sk' => 'Slovaquie',
        'si' => 'Slovénie',
        'so' => 'Somalie',
        'sd' => 'Soudan',
        'ss' => 'Soudan du Sud',
        'lk' => 'Sri Lanka',
        'se' => 'Suède',
        'ch' => 'Suisse',
        'sr' => 'Suriname',
        'sz' => 'Eswatini',
        'sy' => 'Syrie',
        'tj' => 'Tadjikistan',
        'tz' => 'Tanzanie',
        'td' => 'Tchad',
        'th' => 'Thaïlande',
        'tl' => 'Timor oriental',
        'tg' => 'Togo',
        'to' => 'Tonga',
        'tt' => 'Trinité-et-Tobago',
        'tn' => 'Tunisie',
        'tm' => 'Turkménistan',
        'tr' => 'Turquie',
        'tu' => 'Tuvalu',
        'ua' => 'Ukraine',
        'uy' => 'Uruguay',
        'vu' => 'Vanuatu',
        've' => 'Venezuela',
        'vn' => 'Viêt Nam',
        'ye' => 'Yémen',
        'zm' => 'Zambie',
        'zw' => 'Zimbabwe',
    ];

    /**
     * Predominant language classifications per country code.
     */
    private const COUNTRY_LANGUAGES = [
        'cm' => 'fr', 'fr' => 'fr', 'ci' => 'fr', 'sn' => 'fr', 'be' => 'fr', 'ch' => 'fr', 'lu' => 'fr', 
        'mc' => 'fr', 'ma' => 'fr', 'dz' => 'fr', 'tn' => 'fr', 'ga' => 'fr', 'cg' => 'fr', 'cd' => 'fr', 
        'gn' => 'fr', 'ml' => 'fr', 'bf' => 'fr', 'ne' => 'fr', 'tg' => 'fr', 'bj' => 'fr', 'td' => 'fr', 
        'cf' => 'fr', 'gq' => 'fr', 'mg' => 'fr', 'bi' => 'fr', 'rw' => 'fr', 'dj' => 'fr', 'km' => 'fr', 
        'ht' => 'fr', 'ca' => 'fr',

        'es' => 'es', 'mx' => 'es', 'ar' => 'es', 'cl' => 'es', 'pe' => 'es', 've' => 'es', 
        'ec' => 'es', 'bo' => 'es', 'py' => 'es', 'uy' => 'es', 'cr' => 'es', 'pa' => 'es', 'ni' => 'es', 
        'hn' => 'es', 'sv' => 'es', 'gt' => 'es', 'cu' => 'es', 'do' => 'es', 'co' => 'es',

        'de' => 'de', 'at' => 'de', 'li' => 'de',

        'pt' => 'pt', 'br' => 'pt', 'ao' => 'pt', 'mz' => 'pt', 'cv' => 'pt', 'gw' => 'pt', 'st' => 'pt', 
        'tl' => 'pt',
    ];

    public function __construct(
        private TesseractOcrService $tesseract,
    ) {}

    /**
     * Verify document authenticity using a local, rule-based Tesseract engine.
     * Supports both strict country checks and a generic fallback for any country in the world.
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

        $companyCountryCode = mb_strtolower($companyContext['country'] ?? '');
        $countryName = self::COUNTRIES[$companyCountryCode] ?? 'Inconnu';

        foreach ($documents as $field => $file) {
            $ocrText = $ocrTexts[$field] ?? '';
            $ocrTextLower = mb_strtolower($ocrText);
            
            $status = 'needs_review';
            $confidence = 50;
            $authenticityScore = 50;
            $issues = [];
            $summary = '';
            $extractedData = [];

            // Detect target country name and code for this specific document
            $docCountryCode = $companyCountryCode;
            $docCountryName = $countryName;

            if ($docCountryCode === '' || $docCountryName === 'Inconnu') {
                foreach (self::COUNTRIES as $code => $name) {
                    if (str_contains($ocrTextLower, mb_strtolower($name))) {
                        $docCountryCode = $code;
                        $docCountryName = $name;
                        break;
                    }
                }
            }

            // Retrieve dynamically generated keywords for the target country's languages
            $keywords = $this->getKeywordsForCountry($docCountryCode);

            // Match values from company context to increase scores and extract data
            $matchedContextFields = 0;
            $totalContextFields = 0;

            if (!empty($companyContext['legal_name'])) {
                $totalContextFields++;
                $normalizedLegalName = $this->normalizeForMatch($companyContext['legal_name']);
                if (str_contains($this->normalizeForMatch($ocrText), $normalizedLegalName)) {
                    $matchedContextFields++;
                    $extractedData['legal_name'] = $companyContext['legal_name'];
                }
            }
            if (!empty($companyContext['registration_number'])) {
                $totalContextFields++;
                $normalizedRegNum = $this->normalizeForMatch($companyContext['registration_number']);
                if (str_contains($this->normalizeForMatch($ocrText), $normalizedRegNum)) {
                    $matchedContextFields++;
                    $extractedData['registration_number'] = $companyContext['registration_number'];
                }
            }
            if (!empty($companyContext['tax_id'])) {
                $totalContextFields++;
                $normalizedTaxId = $this->normalizeForMatch($companyContext['tax_id']);
                if (str_contains($this->normalizeForMatch($ocrText), $normalizedTaxId)) {
                    $matchedContextFields++;
                    $extractedData['tax_id'] = $companyContext['tax_id'];
                }
            }
            if (!empty($companyContext['legal_representative_name'])) {
                $totalContextFields++;
                $normalizedRepName = $this->normalizeForMatch($companyContext['legal_representative_name']);
                if (str_contains($this->normalizeForMatch($ocrText), $normalizedRepName)) {
                    $matchedContextFields++;
                    $extractedData['representative_name'] = $companyContext['legal_representative_name'];
                }
            }

            // Process based on document field type
            if ($field === 'certificate_of_incorporation') {
                $hasKeywords = false;
                foreach ($keywords['certificate_of_incorporation'] as $kw) {
                    if (str_contains($ocrTextLower, $kw)) {
                        $hasKeywords = true;
                        break;
                    }
                }

                // Country-specific validations
                $specificPass = true;
                if ($docCountryCode === 'cm') {
                    $hasRccm = str_contains($ocrTextLower, 'rccm') || str_contains($ocrTextLower, 'rc/') || str_contains($ocrTextLower, 'registre du commerce') || str_contains($ocrTextLower, 'ohada');
                    if (!$hasRccm) {
                        $specificPass = false;
                        $issues[] = "Absence de mention RCCM ou d'immatriculation au registre du commerce pour le Cameroun.";
                    }
                } elseif ($docCountryCode === 'fr') {
                    $hasKbis = str_contains($ocrTextLower, 'kbis') || str_contains($ocrTextLower, 'siren') || str_contains($ocrTextLower, 'siret') || str_contains($ocrTextLower, 'rcs') || str_contains($ocrTextLower, 'greffe');
                    if (!$hasKbis) {
                        $specificPass = false;
                        $issues[] = "Absence de mentions KBIS, SIREN, SIRET ou RCS pour la France.";
                    }
                } elseif ($docCountryCode === 'ci') {
                    $hasCiRccm = str_contains($ocrTextLower, 'rccm') || str_contains($ocrTextLower, 'ohada') || str_contains($ocrTextLower, 'ci-abj');
                    if (!$hasCiRccm) {
                        $specificPass = false;
                        $issues[] = "Absence d'immatriculation RCCM ou de mention du tribunal de commerce de Côte d'Ivoire.";
                    }
                } elseif ($docCountryCode === 'sn') {
                    $hasSnRccm = str_contains($ocrTextLower, 'rccm') || str_contains($ocrTextLower, 'ohada') || str_contains($ocrTextLower, 'sn-dkr');
                    if (!$hasSnRccm) {
                        $specificPass = false;
                        $issues[] = "Absence d'immatriculation RCCM ou de mention du tribunal de commerce du Sénégal.";
                    }
                }

                if (!$hasKeywords || !$specificPass) {
                    $status = 'rejected';
                    $confidence = 95;
                    $authenticityScore = 20;
                    if (empty($issues)) {
                        $issues[] = "Aucun terme d'enregistrement d'entreprise (ex: Registre, Incorporation, KBIS, Registro) détecté pour : {$docCountryName}.";
                    }
                    $summary = "Document d'enregistrement d'entreprise non valide ou non reconnu.";
                } else {
                    $status = 'verified';
                    $confidence = 90;
                    $authenticityScore = 85;
                    $summary = "Document de registre/incorporation validé avec succès pour : {$docCountryName}.";

                    // Match registration number
                    if (!empty($companyContext['registration_number'])) {
                        $normContextNum = $this->normalizeForMatch($companyContext['registration_number']);
                        if (!str_contains($this->normalizeForMatch($ocrText), $normContextNum)) {
                            $status = 'rejected';
                            $confidence = 98;
                            $authenticityScore = 30;
                            $issues[] = "Le numéro d'immatriculation extrait ne correspond pas au numéro attendu : '{$companyContext['registration_number']}'.";
                        }
                    }

                    // Match company legal name
                    if (!empty($companyContext['legal_name'])) {
                        $normLegal = $this->normalizeForMatch($companyContext['legal_name']);
                        if (!str_contains($this->normalizeForMatch($ocrText), $normLegal)) {
                            $status = 'needs_review';
                            $issues[] = "Le nom légal '{$companyContext['legal_name']}' n'apparaît pas clairement sur le document.";
                            $confidence = max(40, $confidence - 20);
                        }
                    }
                }
            } 
            elseif ($field === 'tax_registration_document') {
                $hasKeywords = false;
                foreach ($keywords['tax_registration_document'] as $kw) {
                    if (str_contains($ocrTextLower, $kw)) {
                        $hasKeywords = true;
                        break;
                    }
                }

                // Country-specific checks
                $specificPass = true;
                if ($docCountryCode === 'cm') {
                    $hasTaxId = str_contains($ocrTextLower, 'niu') || str_contains($ocrTextLower, 'nui') || str_contains($ocrTextLower, 'identifiant unique') || str_contains($ocrTextLower, 'contribuable') || str_contains($ocrTextLower, 'impots') || str_contains($ocrTextLower, 'dgi');
                    $hasValidNiuFormat = preg_match('/\b[mM]\d{10,12}[A-Za-z0-9]\b/', $ocrText);
                    
                    if (!$hasTaxId) {
                        $specificPass = false;
                        $issues[] = "Aucun identifiant fiscal (NIU/NUI) détecté pour le Cameroun.";
                    } elseif (!$hasValidNiuFormat) {
                        $status = 'needs_review';
                        $confidence = 70;
                        $authenticityScore = 50;
                        $issues[] = "Le numéro d'identifiant unique (NIU) détecté ne respecte pas le format standard camerounais (M + 12 caractères).";
                    }
                } elseif ($docCountryCode === 'fr') {
                    $hasFrTax = str_contains($ocrTextLower, 'tva') || str_contains($ocrTextLower, 'impot') || str_contains($ocrTextLower, 'fiscal') || str_contains($ocrTextLower, 'siren') || str_contains($ocrTextLower, 'siret');
                    if (!$hasFrTax) {
                        $specificPass = false;
                        $issues[] = "Aucune mention de TVA, SIRET, Impôts ou identifiant fiscal français détecté.";
                    }
                } elseif ($docCountryCode === 'ci') {
                    $hasCiTax = str_contains($ocrTextLower, 'ncc') || str_contains($ocrTextLower, 'contribuable') || str_contains($ocrTextLower, 'fiscal') || str_contains($ocrTextLower, 'dgi');
                    if (!$hasCiTax) {
                        $specificPass = false;
                        $issues[] = "Absence de numéro de Compte Contribuable (NCC) de Côte d'Ivoire.";
                    }
                } elseif ($docCountryCode === 'sn') {
                    $hasSnTax = str_contains($ocrTextLower, 'ninea') || str_contains($ocrTextLower, 'fiscal') || str_contains($ocrTextLower, 'dgi');
                    if (!$hasSnTax) {
                        $specificPass = false;
                        $issues[] = "Absence de numéro fiscal NINEA du Sénégal.";
                    }
                }

                if (!$hasKeywords || !$specificPass) {
                    $status = 'rejected';
                    $confidence = 95;
                    $authenticityScore = 20;
                    if (empty($issues)) {
                        $issues[] = "Aucun identifiant fiscal ou terme fiscal (ex: Tax ID, TVA, TIN, NIF, Impôts) détecté pour : {$docCountryName}.";
                    }
                    $summary = "Attestation d'immatriculation fiscale non valide.";
                } else {
                    if ($status === 'needs_review' && count($issues) === 0) {
                        $status = 'verified';
                        $confidence = 92;
                        $authenticityScore = 90;
                    }
                    $summary = "Attestation fiscale validée pour : {$docCountryName}.";

                    // Match tax ID
                    if (!empty($companyContext['tax_id'])) {
                        $normContextTax = $this->normalizeForMatch($companyContext['tax_id']);
                        if (!str_contains($this->normalizeForMatch($ocrText), $normContextTax)) {
                            $status = 'rejected';
                            $confidence = 98;
                            $authenticityScore = 25;
                            $issues[] = "Le numéro d'identifiant fiscal ne correspond pas au numéro attendu : '{$companyContext['tax_id']}'.";
                        }
                    }

                    // Match company name
                    if (!empty($companyContext['legal_name'])) {
                        $normLegal = $this->normalizeForMatch($companyContext['legal_name']);
                        if (!str_contains($this->normalizeForMatch($ocrText), $normLegal)) {
                            $status = 'needs_review';
                            $issues[] = "Le nom de l'entreprise '{$companyContext['legal_name']}' est introuvable sur le document fiscal.";
                            $confidence = max(40, $confidence - 15);
                        }
                    }
                }
            } 
            elseif ($field === 'representative_id_document') {
                $hasKeywords = false;
                foreach ($keywords['representative_id_document'] as $kw) {
                    if (str_contains($ocrTextLower, $kw)) {
                        $hasKeywords = true;
                        break;
                    }
                }

                if (!$hasKeywords) {
                    $status = 'rejected';
                    $confidence = 95;
                    $authenticityScore = 15;
                    $issues[] = "Aucune mention de pièce d'identité officielle (ex: Carte Nationale d'Identité, Passeport, Permis de conduire, CNI, Identity Card, Ausweis) lisible.";
                    $summary = "Pièce d'identité non reconnue.";
                } else {
                    $status = 'verified';
                    $confidence = 90;
                    $authenticityScore = 88;
                    $summary = "Pièce d'identité vérifiée avec succès pour : {$docCountryName}.";

                    // Match representative name chunks
                    if (!empty($companyContext['legal_representative_name'])) {
                        $repName = mb_strtolower($companyContext['legal_representative_name']);
                        $normalizedRepName = $this->normalizeForMatch($repName);
                        
                        $chunks = explode(' ', preg_replace('/[^a-z0-9 ]/', ' ', $repName));
                        $chunks = array_filter($chunks, fn($chunk) => strlen($chunk) > 2);
                        
                        $matchedChunks = 0;
                        foreach ($chunks as $chunk) {
                            if (str_contains($ocrTextLower, $chunk)) {
                                $matchedChunks++;
                            }
                        }

                        if ($matchedChunks === 0 && !str_contains($this->normalizeForMatch($ocrText), $normalizedRepName)) {
                            $status = 'rejected';
                            $confidence = 98;
                            $authenticityScore = 30;
                            $issues[] = "Le nom du représentant légal sur la pièce d'identité ne correspond pas à : '{$companyContext['legal_representative_name']}'.";
                        } else {
                            if ($matchedChunks > 0 && $matchedChunks < count($chunks)) {
                                $issues[] = "Correspondance partielle du nom du représentant légal ('{$companyContext['legal_representative_name']}').";
                                $confidence = max(50, $confidence - 10);
                            }
                        }
                    }
                }
            } 
            elseif ($field === 'proof_of_address') {
                $hasKeywords = false;
                foreach ($keywords['proof_of_address'] as $kw) {
                    if (str_contains($ocrTextLower, $kw)) {
                        $hasKeywords = true;
                        break;
                    }
                }

                // Country-specific warnings/checks for utility bills
                if ($docCountryCode === 'cm') {
                    $isEneo = str_contains($ocrTextLower, 'eneo');
                    $isCamwater = str_contains($ocrTextLower, 'camwater');
                    if (!$isEneo && !$isCamwater && $hasKeywords) {
                        $issues[] = "Le justificatif de domicile pour le Cameroun devrait idéalement être une facture Eneo ou Camwater.";
                    }
                } elseif ($docCountryCode === 'fr') {
                    $isFrProvider = str_contains($ocrTextLower, 'edf') || str_contains($ocrTextLower, 'engie') || str_contains($ocrTextLower, 'totalenergies') || str_contains($ocrTextLower, 'orange') || str_contains($ocrTextLower, 'sfr') || str_contains($ocrTextLower, 'free') || str_contains($ocrTextLower, 'bouygues');
                    if (!$isFrProvider && $hasKeywords) {
                        $issues[] = "Fournisseur de service public non reconnu pour la France (EDF, Engie, Orange, etc. attendus).";
                    }
                } elseif ($docCountryCode === 'ci') {
                    $isCiProvider = str_contains($ocrTextLower, 'cie') || str_contains($ocrTextLower, 'sodeci');
                    if (!$isCiProvider && $hasKeywords) {
                        $issues[] = "Fournisseur de service public non reconnu pour la Côte d'Ivoire (CIE, SODECI attendus).";
                    }
                } elseif ($docCountryCode === 'sn') {
                    $isSnProvider = str_contains($ocrTextLower, 'senelec');
                    if (!$isSnProvider && $hasKeywords) {
                        $issues[] = "Fournisseur de service public non reconnu pour le Sénégal (SENELEC attendu).";
                    }
                }

                if (!$hasKeywords) {
                    $status = 'rejected';
                    $confidence = 95;
                    $authenticityScore = 20;
                    $issues[] = "Aucun justificatif de domicile ou facture de service public valide détecté pour : {$docCountryName}.";
                    $summary = "Justificatif de domicile non conforme.";
                } else {
                    $status = 'verified';
                    $confidence = 90;
                    $authenticityScore = 85;
                    $summary = "Justificatif de domicile validé pour : {$docCountryName}.";

                    // Match company legal name or representative name
                    $hasCompanyMatch = false;
                    if (!empty($companyContext['legal_name'])) {
                        $normLegal = $this->normalizeForMatch($companyContext['legal_name']);
                        if (str_contains($this->normalizeForMatch($ocrText), $normLegal)) {
                            $hasCompanyMatch = true;
                        }
                    }

                    $hasRepMatch = false;
                    if (!empty($companyContext['legal_representative_name'])) {
                        $normRep = $this->normalizeForMatch($companyContext['legal_representative_name']);
                        if (str_contains($this->normalizeForMatch($ocrText), $normRep)) {
                            $hasRepMatch = true;
                        }
                    }

                    if (!$hasCompanyMatch && !$hasRepMatch) {
                        $status = 'needs_review';
                        $confidence = 60;
                        $authenticityScore = 55;
                        $issues[] = "Le justificatif de domicile ne mentionne ni l'entreprise ('{$companyContext['legal_name']}') ni le représentant légal ('{$companyContext['legal_representative_name']}').";
                    }
                }
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
            $message = 'Certains documents officiels n\'ont pas pu être authentifiés ou présentent des écarts.';
        } elseif ($hasReview) {
            $overallStatus = 'review_required';
            $message = 'Des vérifications manuelles sont requises pour les documents suspects, incomplets ou hors formats standard.';
        } else {
            $overallStatus = 'compliant';
            $message = 'Tous les documents officiels ont été authentifiés avec succès.';
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
     * Helper to retrieve keywords for a given country code, localized based on language.
     * Fallback to searching all languages if country code is unknown.
     *
     * @param  string  $countryCode
     * @return array<string, array<int, string>>
     */
    private function getKeywordsForCountry(string $countryCode): array
    {
        $primaryLang = self::COUNTRY_LANGUAGES[$countryCode] ?? 'en';
        
        $keywords = [
            'certificate_of_incorporation' => [],
            'tax_registration_document' => [],
            'representative_id_document' => [],
            'proof_of_address' => [],
        ];

        $languages = ['en'];
        if ($primaryLang !== 'en') {
            $languages[] = $primaryLang;
        }

        if (!isset(self::COUNTRY_LANGUAGES[$countryCode])) {
            $languages = ['en', 'fr', 'es', 'de', 'pt'];
        }

        foreach ($languages as $lang) {
            $langKeywords = $this->getKeywordsByLanguage($lang);
            foreach ($keywords as $type => &$list) {
                $list = array_unique(array_merge($list, $langKeywords[$type] ?? []));
            }
        }

        return $keywords;
    }

    /**
     * Localized dictionary keywords lists.
     *
     * @param  string  $lang
     * @return array<string, array<int, string>>
     */
    private function getKeywordsByLanguage(string $lang): array
    {
        switch ($lang) {
            case 'fr':
                return [
                    'certificate_of_incorporation' => [
                        'incorporation', 'enregistrement', 'registre', 'kbis', 'constitution', 'statuts', 
                        'commercial', 'greffe', 'immatriculation', 'rc/', 'rccm', 'ninea', 'siren', 'siret', 
                        'societe', 'immatriculee', 'rcs'
                    ],
                    'tax_registration_document' => [
                        'taxe', 'tva', 'tin', 'impot', 'fiscal', 'contribuable', 'identifiant unique', 'niu', 
                        'nui', 'ninea', 'ncc', 'dgi', 'declaration', 'd\'immatriculation', 'fiscalite'
                    ],
                    'representative_id_document' => [
                        'carte d\'identite', 'carte nationale', 'identite', 'passport', 'passeport', 
                        'permis de conduire', 'cni', 'identity card', 'nationalite', 'signature'
                    ],
                    'proof_of_address' => [
                        'facture', 'electricite', 'eau', 'gaz', 'telephone', 'internet', 'adresse', 'domicile', 
                        'quittance', 'loyer', 'eneo', 'camwater', 'cie', 'sodeci', 'senelec', 'edf', 'engie', 
                        'orange', 'sfr', 'bouygues', 'free', 'sorelies', 'totalenergies'
                    ],
                ];
            case 'es':
                return [
                    'certificate_of_incorporation' => [
                        'incorporacion', 'registro', 'mercantil', 'constitucion', 'estatutos', 'comercio', 
                        'empresa', 'sociedad', 'inscripcion', 'certidao', 'mercantil'
                    ],
                    'tax_registration_document' => [
                        'impuesto', 'tributario', 'nit', 'rut', 'rfc', 'cuit', 'cuil', 'nif', 'cif', 'iva', 
                        'identificacion fiscal', 'hacienda'
                    ],
                    'representative_id_document' => [
                        'identificacion', 'documento nacional', 'dni', 'pasaporte', 'licencia', 'conducir', 
                        'cedula', 'identidad'
                    ],
                    'proof_of_address' => [
                        'factura', 'electricidad', 'agua', 'gas', 'telefono', 'internet', 'direccion', 
                        'domicilio', 'recibo', 'luz', 'iberdrola', 'endesa', 'telefonica', 'movistar'
                    ],
                ];
            case 'de':
                return [
                    'certificate_of_incorporation' => [
                        'handelsregister', 'eintragung', 'grundung', 'satzung', 'gesellschaft', 'firmenbuch', 
                        'gewerbe', 'amtsgericht'
                    ],
                    'tax_registration_document' => [
                        'steuer', 'umsatzsteuer', 'ust-idnr', 'steuernummer', 'finanzamt', 'steuerbescheid'
                    ],
                    'representative_id_document' => [
                        'ausweis', 'personalausweis', 'reisepass', 'fuehrerschein', 'identitaet', 'pass'
                    ],
                    'proof_of_address' => [
                        'rechnung', 'strom', 'wasser', 'gas', 'telefon', 'internet', 'adresse', 'wohnsitz', 
                        'meldebescheinigung', 'nebenkosten'
                    ],
                ];
            case 'pt':
                return [
                    'certificate_of_incorporation' => [
                        'incorporacao', 'registro', 'constituicao', 'estatutos', 'comercio', 'empresa', 
                        'sociedade', 'inscricao', 'certidao'
                    ],
                    'tax_registration_document' => [
                        'imposto', 'tributario', 'nif', 'cnpj', 'cpf', 'iva', 'identificacao fiscal', 'receita'
                    ],
                    'representative_id_document' => [
                        'identidade', 'documento nacional', 'passaporte', 'carta de conducao', 'carteira de motorista', 
                        'cedula'
                    ],
                    'proof_of_address' => [
                        'fatura', 'eletricidade', 'agua', 'gas', 'telefone', 'internet', 'endereco', 
                        'domicilio', 'recibo', 'luz', 'edp', 'meo', 'nos'
                    ],
                ];
            case 'en':
            default:
                return [
                    'certificate_of_incorporation' => [
                        'incorporation', 'registration', 'registry', 'certificate', 'charter', 'constitution', 
                        'business registration', 'company house', 'chamber of commerce', 'corporate', 'companies house'
                    ],
                    'tax_registration_document' => [
                        'tax', 'vat', 'tin', 'ein', 'abn', 'tva', 'fiscal', 'taxpayer', 'revenue', 'irs', 'hmrc'
                    ],
                    'representative_id_document' => [
                        'identity', 'passport', 'card', 'driver', 'license', 'permit', 'id document'
                    ],
                    'proof_of_address' => [
                        'bill', 'invoice', 'statement', 'address', 'utility', 'electricity', 'water', 'gas', 
                        'telecom', 'internet', 'council tax', 'british gas', 'comcast', 'att', 'verizon'
                    ],
                ];
        }
    }

    /**
     * Normalize a string by converting it to lowercase, removing accents and punctuation,
     * and stripping non-alphanumeric characters to avoid false negatives on details matching.
     *
     * @param  string  $text
     * @return string
     */
    private function normalizeForMatch(string $text): string
    {
        $text = mb_strtolower($text);
        
        // Remove accents
        if (class_exists('Transliterator')) {
            $transliterator = \Transliterator::create('Any-Latin; Latin-ASCII');
            if ($transliterator) {
                $text = $transliterator->transliterate($text);
            } else {
                $text = $this->fallbackRemoveAccents($text);
            }
        } else {
            $text = $this->fallbackRemoveAccents($text);
        }
        
        // Keep only alphanumeric characters
        return preg_replace('/[^a-z0-9]/', '', $text);
    }

    private function fallbackRemoveAccents(string $text): string
    {
        return str_replace(
            ['à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ'],
            ['a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y'],
            $text
        );
    }

    /**
     * Extract suggestions from the verified documents context.
     *
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
