<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\Log;
use Exception;

class ContractGenerationController extends Controller
{
    /**
     * Generate a lease contract using Gemini, with a local template fallback.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generate(Request $request)
    {
        $request->validate([
            'locataire' => 'required|string',
            'loyer' => 'required',
            'caution' => 'required',
            'debut' => 'required|string',
            'fin' => 'required|string',
            'reference' => 'required|string',
            'batiment' => 'required|string',
            'duree' => 'required|string',
            'typeBail' => 'required|string',
            'template' => 'nullable|string',
            'instructions' => 'nullable|string',
        ]);

        $locataire = $request->input('locataire');
        $loyer = $request->input('loyer');
        $caution = $request->input('caution');
        $debut = $request->input('debut');
        $fin = $request->input('fin');
        $reference = $request->input('reference');
        $batiment = $request->input('batiment');
        $duree = $request->input('duree');
        $typeBail = $request->input('typeBail');
        $template = $request->input('template');
        $instructions = $request->input('instructions');

        // Construct prompt for Gemini
        $prompt = "Vous êtes un expert juridique spécialisé en droit immobilier et en rédaction de contrats de bail.\n";
        $prompt .= "Rédigez un contrat de bail professionnel, complet et juridiquement structuré en français en utilisant les informations suivantes :\n\n";
        $prompt .= "- **Preneur (Locataire)** : {$locataire}\n";
        $prompt .= "- **Bailleur** : Enterprise Property Corp (Service Immobilier)\n";
        $prompt .= "- **Désignation du bien** : Logement {$reference} situé dans le bâtiment '{$batiment}'\n";
        $prompt .= "- **Type de Bail** : {$typeBail}\n";
        $prompt .= "- **Durée du bail** : {$duree}\n";
        $prompt .= "- **Date d'effet (Début)** : {$debut}\n";
        $prompt .= "- **Date de fin** : {$fin}\n";
        $prompt .= "- **Montant du Loyer mensuel** : {$loyer} €\n";
        $prompt .= "- **Dépôt de Garantie (Caution)** : {$caution} €\n\n";

        if (!empty($template)) {
            $prompt .= "Consigne TRÈS IMPORTANTE : Vous DEVEZ vous baser sur la structure et le style du modèle de contrat de référence suivant pour rédiger le contrat final, tout en injectant les données ci-dessus :\n";
            $prompt .= "\"\"\"\n{$template}\n\"\"\"\n\n";
        } else {
            $prompt .= "Comme aucun modèle spécifique n'est fourni, générez un contrat de bail standard officiel contenant les sections habituelles (Désignation des locaux, Destination, Durée, Loyer et Charges, Dépôt de garantie, Obligations des parties, Clause résolutoire, Élection de domicile, Signatures).\n\n";
        }

        if (!empty($instructions)) {
            $prompt .= "Consigne supplémentaire de guidage de l'utilisateur :\n";
            $prompt .= "\"\"\"\n{$instructions}\n\"\"\"\n\n";
        }

        $prompt .= "Format de retour : Rédigez le contrat au format HTML propre (uniquement les balises structurelles comme <h1>, <h2>, <p>, <ul>, <li>, <strong>, <table>, <tr>, <td>). Ne renvoyez aucune balise de document complet comme <html> ou <body>. Ne mettez aucun bloc de code markdown (comme ```html). Votre réponse doit débuter directement par le contenu HTML du contrat.";

        $generateViaIA = true;
        $apiError = null;
        $generatedText = '';

        try {
            $model = config('ai.gemini_model', 'gemini-2.0-flash');
            $response = Gemini::generativeModel(model: $model)->generateContent($prompt);
            $generatedText = $response->text();
        } catch (Exception $e) {
            Log::warning('Gemini API call failed, falling back to local generation. Error: ' . $e->getMessage());
            $apiError = $e->getMessage();
            $generateViaIA = false;
        }

        if (!$generateViaIA) {
            // Graceful template-based fallback
            $generatedText = $this->generateLocalFallback(
                $locataire,
                $loyer,
                $caution,
                $debut,
                $fin,
                $reference,
                $batiment,
                $duree,
                $typeBail,
                $template,
                $instructions,
                $apiError
            );
        }

        // Clean up any potential markdown code blocks returned by the model
        $generatedText = preg_replace('/^```html\s*/i', '', $generatedText);
        $generatedText = preg_replace('/```$/i', '', $generatedText);
        $generatedText = trim($generatedText);

        return response()->json([
            'success' => true,
            'contract' => $generatedText,
            'fallback' => !$generateViaIA,
        ]);
    }

    /**
     * Local fallback contract generator using templates.
     */
    private function generateLocalFallback($locataire, $loyer, $caution, $debut, $fin, $reference, $batiment, $duree, $typeBail, $template, $instructions, $apiError)
    {
        $tpl = $template;
        if (empty($tpl)) {
            $tpl = "<h3>CONTRAT DE BAIL</h3>
            <p><strong>Bailleur :</strong> Enterprise Property Corp<br>
            <strong>Preneur :</strong> [Locataire]<br>
            <strong>Bien Loué :</strong> Logement [Logement] situé dans le bâtiment [Bâtiment]<br>
            <strong>Type de Bail :</strong> [TypeBail]<br>
            <strong>Durée :</strong> [Durée] à compter du [Date Début] jusqu'au [Date Fin]</p>
            <h4>CONDITIONS FINANCIÈRES</h4>
            <p>Le loyer mensuel est fixé à [Loyer] hors charges.<br>
            Le dépôt de garantie (caution) est de [Caution].</p>
            <h4>CLAUSES PARTICULIÈRES</h4>
            <p>[Instructions]</p>
            <p>Fait en double exemplaire, le [Date].</p>";
        }

        // Convert template text to HTML if it's not HTML already
        if (!preg_match('/<[a-z][\s\S]*>/i', $tpl)) {
            $tpl = nl2br(e($tpl));
            // wrap in paragraphs or basic format
            $tpl = "<h3>CONTRAT DE BAIL</h3>\n<p>" . $tpl . "</p>";
        }

        // Format dates
        $dateDebut = $this->formatDateFrench($debut);
        $dateFin = $this->formatDateFrench($fin);
        $dateSignature = date('d/m/Y');

        // Replace placeholders
        $tpl = str_replace(['[Locataire]', '[locataire]'], $locataire, $tpl);
        $tpl = str_replace(['[Logement]', '[logement]'], $reference, $tpl);
        $tpl = str_replace(['[Bâtiment]', '[bâtiment]', '[Bâtiment]'], $batiment, $tpl);
        $tpl = str_replace(['[Loyer]', '[loyer]'], $loyer . ' €', $tpl);
        $tpl = str_replace(['[Caution]', '[caution]'], $caution . ' €', $tpl);
        $tpl = str_replace(['[Durée]', '[durée]', '[Durée]'], $duree, $tpl);
        $tpl = str_replace(['[TypeBail]', '[typeBail]'], $typeBail, $tpl);
        $tpl = str_replace(['[Date Début]', '[date début]', '[Date Début]'], $dateDebut, $tpl);
        $tpl = str_replace(['[Date Fin]', '[date fin]', '[Date Fin]'], $dateFin, $tpl);
        $tpl = str_replace(['[Date]', '[date]'], $dateSignature, $tpl);

        // Append custom guidelines if provided
        if (!empty($instructions)) {
            $tpl .= "\n\n<hr>\n<h4>ANNEXE - CONDITIONS PARTICULIÈRES (IA Fallback)</h4>\n";
            $tpl .= "<p>Conformément aux instructions formulées, les clauses suivantes sont ajoutées :<br>";
            $tpl .= "<em>" . nl2br(e($instructions)) . "</em></p>";
        }

        // Add notice banner at the top
        $banner = "<div style='background-color: #fef3c7; border: 1px solid #f59e0b; padding: 12px; border-radius: 8px; color: #92400e; margin-bottom: 20px; font-family: sans-serif; font-size: 13px;'>\n";
        $banner .= "<strong>Note de simulation :</strong> Ce contrat a été généré via notre moteur de secours local (Limite d'API Gemini atteinte/Quota dépassé). Le modèle de contrat de référence a été appliqué avec succès.\n";
        $banner .= "</div>\n\n";

        return $banner . $tpl;
    }

    private function formatDateFrench($dateStr)
    {
        if (empty($dateStr)) return 'Non spécifiée';
        $time = strtotime($dateStr);
        return $time ? date('d/m/Y', $time) : $dateStr;
    }

    /**
     * Generate an engagement or convention document using Gemini, with local fallback.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateEngagement(Request $request)
    {
        $request->validate([
            'partie' => 'required|string',
            'montant' => 'nullable',
            'type' => 'required|string',
            'dateDebut' => 'required|string',
            'dateFin' => 'required|string',
            'template' => 'nullable|string',
            'instructions' => 'nullable|string',
        ]);

        $partie = $request->input('partie');
        $montant = $request->input('montant') ?: 'Non spécifié';
        $type = $request->input('type');
        $dateDebut = $request->input('dateDebut');
        $dateFin = $request->input('dateFin');
        $template = $request->input('template');
        $instructions = $request->input('instructions');

        // Construct prompt for Gemini
        $prompt = "Vous êtes un expert juridique spécialisé en droit immobilier et en rédaction d'actes d'engagement et de conventions.\n";
        $prompt .= "Rédigez un document d'engagement de type '{$type}' professionnel, complet et juridiquement structuré en français en utilisant les informations suivantes :\n\n";
        $prompt .= "- **Type d'acte** : {$type}\n";
        $prompt .= "- **Bailleur / Émetteur** : Enterprise Property Corp\n";
        $prompt .= "- **Contrepartie (Partie concernée)** : {$partie}\n";
        $prompt .= "- **Date de début de l'engagement** : {$dateDebut}\n";
        $prompt .= "- **Date de fin de l'engagement** : {$dateFin}\n";
        $prompt .= "- **Montant associé** : {$montant} €\n\n";

        if (!empty($template)) {
            $prompt .= "Consigne TRÈS IMPORTANTE : Vous DEVEZ vous baser sur la structure et le style du modèle de canevas suivant pour rédiger l'acte, tout en injectant les données ci-dessus :\n";
            $prompt .= "\"\"\"\n{$template}\n\"\"\"\n\n";
        } else {
            $prompt .= "Comme aucun modèle spécifique n'est fourni, générez un acte d'engagement standard officiel contenant les sections appropriées pour ce type d'acte (Exposé des motifs, Objet de l'engagement, Obligations de l'émetteur, Obligations de la partie concernée, Durée, Résolution, Signatures).\n\n";
        }

        if (!empty($instructions)) {
            $prompt .= "Consigne supplémentaire de guidage de l'utilisateur :\n";
            $prompt .= "\"\"\"\n{$instructions}\n\"\"\"\n\n";
        }

        $prompt .= "Format de retour : Rédigez le contrat au format HTML propre (uniquement les balises structurelles comme <h1>, <h2>, <p>, <ul>, <li>, <strong>, <table>, <tr>, <td>). Ne renvoyez aucune balise de document complet comme <html> ou <body>. Ne mettez aucun bloc de code markdown (comme ```html). Votre réponse doit débuter directement par le contenu HTML.";

        $generateViaIA = true;
        $apiError = null;
        $generatedText = '';

        try {
            $model = config('ai.gemini_model', 'gemini-2.0-flash');
            $response = \Gemini\Laravel\Facades\Gemini::generativeModel(model: $model)->generateContent($prompt);
            $generatedText = $response->text();
        } catch (\Exception $e) {
            \Log::warning('Gemini API call failed for Engagement, falling back to local generation. Error: ' . $e->getMessage());
            $apiError = $e->getMessage();
            $generateViaIA = false;
        }

        if (!$generateViaIA) {
            // Fallback local
            $generatedText = $this->generateEngagementLocalFallback(
                $partie,
                $montant,
                $type,
                $dateDebut,
                $dateFin,
                $template,
                $instructions,
                $apiError
            );
        }

        $generatedText = preg_replace('/^```html\s*/i', '', $generatedText);
        $generatedText = preg_replace('/```$/i', '', $generatedText);
        $generatedText = trim($generatedText);

        return response()->json([
            'success' => true,
            'engagement' => $generatedText,
            'fallback' => !$generateViaIA,
        ]);
    }

    /**
     * Local fallback engagement generator using templates.
     */
    private function generateEngagementLocalFallback($partie, $montant, $type, $dateDebut, $dateFin, $template, $instructions, $apiError)
    {
        $tpl = $template;
        if (empty($tpl)) {
            $tpl = "<h3>ACTE D'ENGAGEMENT ({Type})</h3>
            <p><strong>Émetteur :</strong> Enterprise Property Corp<br>
            <strong>Partie Concernée :</strong> [Partie]<br>
            <strong>Type d'Engagement :</strong> [Type]<br>
            <strong>Durée :</strong> à compter du [Date Début] jusqu'au [Date Fin]</p>
            <h4>CONDITIONS FINANCIÈRES</h4>
            <p>Le montant de l'engagement est fixé à [Montant].</p>
            <h4>CLAUSES PARTICULIÈRES</h4>
            <p>[Instructions]</p>
            <p>Fait en double exemplaire, le [Date].</p>";
        }

        if (!preg_match('/<[a-z][\s\S]*>/i', $tpl)) {
            $tpl = nl2br(e($tpl));
            $tpl = "<h3>ACTE D'ENGAGEMENT</h3>\n<p>" . $tpl . "</p>";
        }

        $dateDeb = $this->formatDateFrench($dateDebut);
        $dateF = $this->formatDateFrench($dateFin);
        $dateSignature = date('d/m/Y');

        $tpl = str_replace(['[Partie]', '[partie]'], $partie, $tpl);
        $tpl = str_replace(['[Montant]', '[montant]'], $montant . ' €', $tpl);
        $tpl = str_replace(['[Type]', '[type]', '{Type}'], $type, $tpl);
        $tpl = str_replace(['[Date Début]', '[date début]'], $dateDeb, $tpl);
        $tpl = str_replace(['[Date Fin]', '[date fin]'], $dateF, $tpl);
        $tpl = str_replace(['[Date]', '[date]'], $dateSignature, $tpl);

        if (!empty($instructions)) {
            $tpl .= "\n\n<hr>\n<h4>ANNEXE - CONDITIONS PARTICULIÈRES (IA Fallback)</h4>\n";
            $tpl .= "<p>Conformément aux instructions formulées, les clauses suivantes sont ajoutées :<br>";
            $tpl .= "<em>" . nl2br(e($instructions)) . "</em></p>";
        }

        $banner = "<div style='background-color: #fef3c7; border: 1px solid #f59e0b; padding: 12px; border-radius: 8px; color: #92400e; margin-bottom: 20px; font-family: sans-serif; font-size: 13px;'>\n";
        $banner .= "<strong>Note de simulation :</strong> Cet engagement a été généré via notre moteur de secours local (Limite d'API Gemini atteinte/Quota dépassé). Le canevas de référence a été appliqué avec succès.\n";
        $banner .= "</div>\n\n";

        return $banner . $tpl;
    }
}
