<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Gemini\Laravel\Facades\Gemini;
use App\Models\Country;

class ReglesVigueurController extends Controller
{
    /**
     * Get the property laws and lease contract regulations in force for the company's country.
     */
    public function getRegles(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Non autorisé.'], 401);
        }

        // Load company and country relation
        $company = $user->company;
        $countryCode = $company ? ($company->country ?? 'FR') : 'FR';
        $countryCode = strtoupper($countryCode);

        // Fetch country name
        $countryRecord = Country::where('code', $countryCode)->first();
        $countryName = $countryRecord ? $countryRecord->name : $countryCode;

        $forceRefresh = $request->boolean('force_refresh', false);
        $cacheKey = "regles_vigueur_v1_" . $countryCode;

        if ($forceRefresh) {
            Cache::forget($cacheKey);
        }

        $regulations = Cache::remember($cacheKey, now()->addDays(30), function () use ($countryCode, $countryName) {
            $apiKey = config('gemini.api_key');
            $openaiKey = config('openai.api_key');

            $prompt = "Tu es un expert juridique spécialisé en droit de l'immobilier et en gestion des contrats de bail.\n";
            $prompt .= "Rédige un guide complet, structuré et extrêmement détaillé des lois et réglementations en vigueur concernant la gestion immobilière et les contrats de bail pour le pays suivant : {$countryName} (Code pays: {$countryCode}).\n";
            $prompt .= "Ton guide doit être formaté en Markdown de manière professionnelle et comprendre :\n";
            $prompt .= "1. Les textes de référence (codes, lois, décrets avec numéros d'articles précis en vigueur).\n";
            $prompt .= "2. Les règles encadrant la conclusion du bail (obligation d'écrit, enregistrement fiscal, diagnostics obligatoires).\n";
            $prompt .= "3. Le dépôt de garantie et la révision du loyer (seuils, calculs, indices ou usages).\n";
            $prompt .= "4. Les conditions de congé, de préavis et de résiliation de bail.\n";
            $prompt .= "5. Les obligations respectives du bailleur (propriétaire) et du locataire.\n\n";
            $prompt .= "Rédige ce guide en français, de manière formelle, claire, précise et structurée en citant les articles de loi. N'inclus aucun texte explicatif en dehors du guide Markdown.";

            // 1. Try Gemini
            if (!empty($apiKey)) {
                try {
                    $response = Gemini::generativeModel(model: config('ai.gemini_model', 'gemini-2.0-flash'))
                        ->generateContent([$prompt]);
                    if ($response && method_exists($response, 'text') && filled($response->text())) {
                        return [
                            'content' => $response->text(),
                            'source' => 'Gemini AI',
                            'generated_at' => now()->format('d/m/Y H:i'),
                        ];
                    }
                } catch (\Exception $e) {
                    logger()->error("Gemini legal rules generation error: " . $e->getMessage());
                }
            }

            // 2. Try OpenAI
            if (!empty($openaiKey)) {
                try {
                    $client = \OpenAI::factory()->withApiKey($openaiKey)->make();
                    $response = $client->chat()->create([
                        'model' => config('ai.openai_model', 'gpt-4o-mini'),
                        'messages' => [['role' => 'user', 'content' => $prompt]],
                    ]);
                    $content = $response->choices[0]->message->content;
                    if (filled($content)) {
                        return [
                            'content' => $content,
                            'source' => 'OpenAI GPT',
                            'generated_at' => now()->format('d/m/Y H:i'),
                        ];
                    }
                } catch (\Exception $e) {
                    logger()->error("OpenAI legal rules generation error: " . $e->getMessage());
                }
            }

            // 3. Local Fallbacks (CM & FR)
            return [
                'content' => $this->getLocalFallback($countryCode, $countryName),
                'source' => 'Base de connaissances locale',
                'generated_at' => now()->format('d/m/Y H:i'),
            ];
        });

        return response()->json([
            'success' => true,
            'country_code' => $countryCode,
            'country_name' => $countryName,
            'regulations' => $regulations['content'],
            'source' => $regulations['source'],
            'generated_at' => $regulations['generated_at'],
        ]);
    }

    /**
     * Get detailed static markdown fallback regulations.
     */
    private function getLocalFallback($countryCode, $countryName)
    {
        switch ($countryCode) {
            case 'CM':
                return <<<'MARKDOWN'
# Cadre Juridique de la Gestion Immobilière au Cameroun

La gestion immobilière et les contrats de bail au Cameroun sont principalement régis par deux grands ensembles de textes : le droit communautaire **OHADA** (pour les baux à usage professionnel) et le droit national camerounais (pour les baux d'habitation).

---

## 1. Textes de Référence et Codes Applicables

### A. Le Bail d'Habitation
- **Le Code Civil Camerounais** : Spécifiquement les **Articles 1713 et suivants** relatifs au louage de choses.
- **La Loi n° 2015/018 du 21 décembre 2015** régissant l'activité de promotion immobilière au Cameroun.
- **Le Décret n° 2007/1138/PM du 03 septembre 2007** : Fixant les modalités d'application de la loi n° 2001/020 du 18 décembre 2001 portant organisation de la profession d'agent immobilier au Cameroun.

### B. Le Bail Commercial / Professionnel
- **L'Acte Uniforme OHADA portant sur le Droit Commercial Général** : Plus particulièrement les **Articles 101 à 134** qui régissent de manière impérative le bail à usage professionnel (applicable aux commerçants, artisans, professions libérales et personnes morales).

---

## 2. Conclusion du Contrat de Bail

- **Exigence d'un Écrit** : Bien que le bail verbal soit juridiquement possible en droit civil, l'**Article 104 de l'Acte Uniforme OHADA** et la pratique administrative camerounaise exigent un contrat écrit pour des raisons de preuve et d'enregistrement.
- **Enregistrement Fiscal** : Le contrat de bail doit être enregistré auprès du Centre des Impôts compétent dans les **3 mois** suivant sa signature. Les droits d'enregistrement s'élèvent généralement à **10%** du montant annuel du loyer cumulé, à la charge fiscale du preneur.
- **Diagnostics requis** : Contrairement à l'Europe, il n'existe pas de diagnostics techniques obligatoires (DPE, amiante), mais le bailleur doit délivrer un logement sain et en bon état de réparations de toute espèce.

---

## 3. Dépôt de Garantie et Révision du Loyer

- **Dépôt de Garantie (Caution)** :
  - **Bail d'habitation** : La coutume et la jurisprudence limitent généralement le dépôt de garantie à **2 mois de loyer**. La pratique courante au Cameroun consiste également à demander des loyers d'avance (souvent 6 à 12 mois), bien que cette pratique soit de plus en plus encadrée pour protéger les locataires.
  - **Bail professionnel (OHADA)** : Les parties fixent librement le montant du dépôt de garantie au contrat.
- **Révision du Loyer** :
  - **Bail d'habitation** : La révision doit être prévue dans le contrat de bail (clause d'indexation) ou acceptée d'accord partie.
  - **Bail professionnel (OHADA)** : L'**Article 115** de l'Acte Uniforme OHADA prévoit que le loyer peut être révisé à la demande de l'une des parties sous conditions de notification préalable et de respect des indices de référence locaux.

---

## 4. Congé, Préavis et Résiliation

### A. Bail d'Habitation
- **Préavis de congé par le locataire** : Doit être notifié au moins **3 mois** à l'avance par écrit (lettre recommandée avec accusé de réception ou par voie d'huissier).
- **Préavis de congé par le bailleur** : Le bailleur ne peut donner congé qu'à l'expiration du bail avec un préavis de **3 mois** et pour un motif légitime (reprise pour y habiter, travaux majeurs, non-respect des obligations par le locataire).

### B. Bail Professionnel (OHADA)
- **Droit au renouvellement** : L'**Article 123** de l'OHADA consacre le droit au renouvellement du bail pour le locataire qui a exploité l'activité dans les lieux pendant au moins **2 ans**.
- **Résiliation pour défaut de paiement** : Requiert une mise en demeure préalable par huissier de justice de payer sous **30 jours** (Article 133). Si le locataire ne paye pas, le bailleur peut saisir le juge compétent pour constater la résiliation.

---

## 5. Obligations Respectives

### Le Bailleur doit :
1. Délivrer la chose louée en bon état d'usage et de réparations (Article 1719 du Code Civil).
2. Garantir au locataire une jouissance paisible des lieux pendant la durée du bail.
3. Effectuer les grosses réparations (toiture, murs porteurs, etc.).

### Le Locataire doit :
1. Payer le loyer aux termes convenus (Article 1728 du Code Civil).
2. User de la chose louée "en bon père de famille" et suivant la destination prévue au contrat.
3. Répondre des dégradations et pertes qui surviennent pendant sa jouissance, à moins de prouver un cas de force majeure ou d'usure normale.
4. Effectuer les menues réparations d'entretien courant.
MARKDOWN;

            case 'FR':
                return <<<'MARKDOWN'
# Cadre Juridique de la Gestion Immobilière en France

La gestion immobilière et les rapports locatifs en France sont strictement encadrés par la loi pour protéger les deux parties, avec un fort accent sur la protection du locataire.

---

## 1. Textes de Référence et Codes Applicables

### A. Le Bail d'Habitation (Résidence Principale)
- **La Loi n° 89-462 du 6 juillet 1989** : Loi d'ordre public régissant les rapports locatifs pour les logements nus ou meublés à usage de résidence principale.
- **La Loi ALUR du 24 mars 2014** : Encadrement des loyers, plafonnement des frais d'agences, instauration du contrat de bail type et du dossier de diagnostics techniques.
- **La Loi ELAN du 23 novembre 2018** : Introduction du bail mobilité et sanctions renforcées contre les logements indécents.

### B. Le Code Civil
- **Articles 1708 et suivants** : Droit commun des contrats de louage.

---

## 2. Conclusion du Contrat de Bail

- **Contrat Type** : Le contrat doit obligatoirement respecter le modèle type défini par décret (décret n° 2015-587), contenant des mentions obligatoires (surface habitable, loyer de référence, équipements).
- **Diagnostics Techniques Obligatoires (DDT)** : Le bailleur doit obligatoirement annexer au bail :
  - Le Diagnostic de Performance Énergétique (**DPE**).
  - L'état de l'installation d'électricité et de gaz (si de plus de 15 ans).
  - Le constat de risque d'exposition au plomb (**CREP**).
  - L'état des risques et pollutions (**ERP**).
- **Enregistrement** : En France, il n'y a pas d'obligation d'enregistrement fiscal du bail d'habitation auprès des impôts.

---

## 3. Dépôt de Garantie et Révision du Loyer

- **Dépôt de Garantie** :
  - **Location nue** : Limité strictement à **1 mois de loyer hors charges** (Article 22 de la Loi du 6 juillet 1989).
  - **Location meublée** : Limité à **2 mois de loyer hors charges**.
  - Restitution sous **1 mois** si l'état des lieux de sortie est conforme à l'entrée, sinon sous **2 mois**.
- **Révision du Loyer** :
  - Annuelle et basée uniquement sur la variation de l'Indice de Référence des Loyers (**IRL**) publié par l'INSEE.
  - Doit être expressément prévue dans le bail.

---

## 4. Congé, Préavis et Résiliation

- **Congé par le Locataire** :
  - Peut donner congé à tout moment.
  - Préavis standard de **3 mois** pour une location nue, réduit à **1 mois** en zone tendue ou sous conditions spécifiques (mutation, premier emploi, RSA).
  - Préavis de **1 mois** pour une location meublée.
- **Congé par le Bailleur** :
  - Ne peut donner congé qu'à l'échéance du bail.
  - Préavis de **6 mois** (location nue) ou **3 mois** (location meublée).
  - Doit être justifié par l'un des 3 motifs légaux : reprise pour habiter (soi-même ou un proche), vente du logement, ou motif légitime et sérieux (ex: impayés répétés).

---

## 5. Obligations Respectives

### Le Bailleur doit :
1. Délivrer un logement décent ne laissant pas apparaître de risques manifestes pour la sécurité physique ou la santé, et doté des équipements de confort de base.
2. Assurer au locataire la jouissance paisible du logement.
3. Effectuer toutes les réparations autres que locatives (remplacement chaudière, toiture, travaux de copropriété).

### Le Locataire doit :
1. Payer le loyer et les charges récupérables aux termes convenus.
2. User paisiblement des locaux loués selon la destination prévue.
3. Prendre à sa charge l'entretien courant du logement et des équipements mentionnés au contrat, ainsi que les menues réparations (remplacement de joints, ampoules, entretien de chaudière annuel).
4. S'assurer contre les risques locatifs (assurance habitation obligatoire).
MARKDOWN;

            default:
                return "## Cadre Juridique de la Gestion Immobilière - " . $countryName . "\n\n" .
                    "La gestion immobilière et les contrats de bail pour le pays " . $countryName . " (Code: " . $countryCode . ") sont encadrés par la législation locale.\n\n" .
                    "### 1. Recommandations de base :\n" .
                    "- **Rédaction écrite** : Il est fortement recommandé d'établir un contrat de bail écrit détaillant les identités des parties, la description du bien, le loyer et sa périodicité, le dépôt de garantie, et les conditions de résiliation.\n" .
                    "- **Dépôt de garantie** : Le montant du dépôt de garantie (ou caution) est généralement fixé à un ou deux mois de loyer selon les usages locaux.\n" .
                    "- **Obligations du Bailleur** : Assurer la livraison d'un bien en bon état et garantir la jouissance paisible des locaux.\n" .
                    "- **Obligations du Locataire** : Payer le loyer convenu à temps et entretenir le logement en bon père de famille.\n\n" .
                    "Pour obtenir des détails réglementaires précis et à jour, veuillez activer les clés de configuration IA (Gemini ou OpenAI) ou consulter un conseiller juridique local spécialisé.";
        }
    }
}
