<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Prism\Prism\Facades\Prism;
use Prism\Prism\Enums\Provider;
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
        \App\Helpers\SubscriptionHelper::checkOrAbort('ai');
        $request->validate([
            'locataire' => 'nullable|string',
            'locataire_id' => 'nullable|integer',
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
            'numero' => 'nullable|string',
        ]);

        $user = Auth::user();
        $companyProfileId = $user ? $user->company_profile_id : null;
        $companyName = ($user && $user->company) ? $user->company->legal_name : 'Votre Entreprise';

        $locataireId = $request->input('locataire_id');
        $locataire = $request->input('locataire');
        if ($locataireId) {
            $loc = \App\Models\Locataire::with('user')->find($locataireId);
            if ($loc && $loc->user) {
                $locataire = $loc->user->name;
            }
        }

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

        // Determine contract number
        $numero = $request->input('numero');
        if (empty($numero) && $companyProfileId) {
            $count = \App\Models\Contrat::where('company_profile_id', $companyProfileId)->count();
            $numero = 'CTR-' . str_pad($count + 1, 5, '0', STR_PAD_LEFT);
        }

        // If template is empty, try to load it from the database based on type of contract
        if (empty($template) && $locataireId && $companyProfileId) {
            // Find active assignment
            $aff = \App\Models\Affectation::where('locataire_id', $locataireId)
                                          ->where('statut', 'Actif')
                                          ->first();
            if ($aff && $aff->type_contrat_id) {
                $tplRecord = \App\Models\ContratTemplate::where('company_profile_id', $companyProfileId)
                                                        ->where('type_contrat_id', $aff->type_contrat_id)
                                                        ->first();
                if ($tplRecord) {
                    $template = $tplRecord->content;
                }
            }
        }

        // Construct prompt for Gemini
        $prompt = "Vous êtes un expert juridique spécialisé en droit immobilier et en rédaction de contrats de bail.\n";
        $prompt .= "Rédigez un contrat de bail professionnel, complet et juridiquement structuré en français en utilisant les informations suivantes :\n\n";
        $prompt .= "- **Numéro du Contrat** : {$numero}\n";
        $prompt .= "- **Preneur (Locataire)** : {$locataire}\n";
        $prompt .= "- **Bailleur** : {$companyName}\n";
        $prompt .= "- **Désignation du bien** : Logement {$reference} situé dans le bâtiment '{$batiment}'\n";
        $prompt .= "- **Type de Bail** : {$typeBail}\n";
        $prompt .= "- **Durée du bail** : {$duree}\n";
        $prompt .= "- **Date d'effet (Début)** : {$debut}\n";
        $prompt .= "- **Date de fin** : {$fin}\n";
        $prompt .= "- **Montant du Loyer mensuel** : {$loyer} €\n";
        $prompt .= "- **Dépôt de Garantie (Caution)** : {$caution} €\n\n";
        $prompt .= "Consigne TRÈS IMPORTANTE : Vous devez obligatoirement mentionner le numéro de contrat \"{$numero}\" et l'entreprise bailleur \"{$companyName}\" dans le corps du contrat.\n\n";

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
            $model = config('ai.groq_model', 'llama-3.3-70b-versatile');
            $response = Prism::text()
                ->using(Provider::Groq, $model)
                ->withPrompt($prompt)
                ->asText();
            $generatedText = $response->text;
        } catch (Exception $e) {
            Log::warning('Groq API call failed, falling back to local generation. Error: ' . $e->getMessage());
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
                $template ?: "CONTRAT DE BAIL\n\nNuméro: {$numero}\nBailleur: {$companyName}\nLocataire: [Locataire]",
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
            'numero' => $numero,
            'fallback' => !$generateViaIA,
        ]);
    }

    /**
     * Local fallback contract generator using templates.
     */
    private function generateLocalFallback($locataire, $loyer, $caution, $debut, $fin, $reference, $batiment, $duree, $typeBail, $template, $instructions, $apiError)
    {
        $tpl = $template;
        $user = Auth::user();
        $companyName = ($user && $user->company) ? $user->company->legal_name : 'Votre Entreprise';
        if (empty($tpl)) {
            $tpl = "<h3>CONTRAT DE BAIL</h3>
            <p><strong>Bailleur :</strong> {$companyName}<br>
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
        $banner .= "<strong>Note de simulation :</strong> Ce contrat a été généré via notre moteur de secours local (Limite d'API Groq atteinte/Quota dépassé). Le modèle de contrat de référence a été appliqué avec succès.\n";
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
        \App\Helpers\SubscriptionHelper::checkOrAbort('ai');
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

        $user = Auth::user();
        $companyName = ($user && $user->company) ? $user->company->legal_name : 'Votre Entreprise';

        // Construct prompt for Gemini
        $prompt = "Vous êtes un expert juridique spécialisé en droit immobilier et en rédaction d'actes d'engagement et de conventions.\n";
        $prompt .= "Rédigez un document d'engagement de type '{$type}' professionnel, complet et juridiquement structuré en français en utilisant les informations suivantes :\n\n";
        $prompt .= "- **Type d'acte** : {$type}\n";
        $prompt .= "- **Bailleur / Émetteur** : {$companyName}\n";
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
            $model = config('ai.groq_model', 'llama-3.3-70b-versatile');
            $response = Prism::text()
                ->using(Provider::Groq, $model)
                ->withPrompt($prompt)
                ->asText();
            $generatedText = $response->text;
        } catch (\Exception $e) {
            \Log::warning('Groq API call failed for Engagement, falling back to local generation. Error: ' . $e->getMessage());
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
        $user = Auth::user();
        $companyName = ($user && $user->company) ? $user->company->legal_name : 'Votre Entreprise';
        if (empty($tpl)) {
            $tpl = "<h3>ACTE D'ENGAGEMENT ({Type})</h3>
            <p><strong>Émetteur :</strong> {$companyName}<br>
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
        $banner .= "<strong>Note de simulation :</strong> Cet engagement a été généré via notre moteur de secours local (Limite d'API Groq atteinte/Quota dépassé). Le canevas de référence a été appliqué avec succès.\n";
        $banner .= "</div>\n\n";

        return $banner . $tpl;
    }

    /**
     * Generate an inspection document (État des lieux) using Gemini, with local fallback.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateEtatDesLieux(Request $request)
    {
        \App\Helpers\SubscriptionHelper::checkOrAbort('ai');
        $request->validate([
            'locataire' => 'required|string',
            'logement' => 'required|string',
            'type' => 'required|string',
            'date' => 'required|string',
            'template' => 'nullable|string',
            'instructions' => 'nullable|string',
        ]);

        $locataire = $request->input('locataire');
        $logement = $request->input('logement');
        $type = $request->input('type');
        $date = $request->input('date');
        $template = $request->input('template');
        $instructions = $request->input('instructions');

        $user = Auth::user();
        $companyName = ($user && $user->company) ? $user->company->legal_name : 'Votre Entreprise';

        // Construct prompt for Gemini
        $prompt = "Vous êtes un expert juridique spécialisé en droit immobilier et en rédaction d'états des lieux (procès-verbaux d'entrée et de sortie).\n";
        $prompt .= "Rédigez un document d'état des lieux de type '{$type}' professionnel, complet et rigoureux en français en utilisant les informations suivantes :\n\n";
        $prompt .= "- **Type d'état des lieux** : {$type}\n";
        $prompt .= "- **Bailleur / Propriétaire** : {$companyName}\n";
        $prompt .= "- **Locataire** : {$locataire}\n";
        $prompt .= "- **Logement concerné** : {$logement}\n";
        $prompt .= "- **Date de l'état des lieux** : {$date}\n\n";

        if (!empty($template)) {
            $prompt .= "Consigne TRÈS IMPORTANTE : Vous DEVEZ vous baser sur la structure et le style du modèle de canevas suivant pour rédiger l'état des lieux, tout en injectant les données ci-dessus :\n";
            $prompt .= "\"\"\"\n{$template}\n\"\"\"\n\n";
        } else {
            $prompt .= "Comme aucun modèle spécifique n'est fourni, générez un procès-verbal d'état des lieux standard officiel contenant les sections appropriées pour ce type de document (Désignation des parties et du logement, Relevé des compteurs d'eau/électricité/gaz, Inventaire des clés, État détaillé pièce par pièce, Observations, Date et Signatures).\n\n";
        }

        if (!empty($instructions)) {
            $prompt .= "Consigne supplémentaire de guidage de l'utilisateur (observations ou consignes spécifiques) :\n";
            $prompt .= "\"\"\"\n{$instructions}\n\"\"\"\n\n";
        }

        $prompt .= "Format de retour : Rédigez le rapport au format HTML propre (uniquement les balises structurelles comme <h1>, <h2>, <p>, <ul>, <li>, <strong>, <table>, <tr>, <td>). Ne renvoyez aucune balise de document complet comme <html> ou <body>. Ne mettez aucun bloc de code markdown (comme ```html). Votre réponse doit débuter directement par le contenu HTML.";

        $generateViaIA = true;
        $apiError = null;
        $generatedText = '';

        try {
            $model = config('ai.groq_model', 'llama-3.3-70b-versatile');
            $response = Prism::text()
                ->using(Provider::Groq, $model)
                ->withPrompt("Message de l'administrateur : Générez l'état des lieux.\n\nRépondez en respectant les consignes système.")
                ->withSystemPrompt($prompt)
                ->asText();
            $generatedText = $response->text;
        } catch (\Exception $e) {
            \Log::warning('Groq API call failed for Etat des lieux, falling back to local generation. Error: ' . $e->getMessage());
            $apiError = $e->getMessage();
            $generateViaIA = false;
        }

        if (!$generateViaIA) {
            // Fallback local
            $generatedText = $this->generateEtatDesLieuxLocalFallback(
                $locataire,
                $logement,
                $type,
                $date,
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
            'etat' => $generatedText,
            'fallback' => !$generateViaIA,
        ]);
    }

    /**
     * Local fallback inspection generator using templates.
     */
    private function generateEtatDesLieuxLocalFallback($locataire, $logement, $type, $date, $template, $instructions, $apiError)
    {
        $tpl = $template;
        $user = Auth::user();
        $companyName = ($user && $user->company) ? $user->company->legal_name : 'Votre Entreprise';
        if (empty($tpl)) {
            $tpl = "<h3>PROCES-VERBAL D'ETAT DES LIEUX ({Type})</h3>
            <p><strong>Bailleur :</strong> {$companyName}<br>
            <strong>Locataire :</strong> [Locataire]<br>
            <strong>Logement :</strong> [Logement]<br>
            <strong>Type :</strong> [Type]<br>
            <strong>Date :</strong> [Date]</p>
            <h4>ETAT DES PIECES (SYNTHESE)</h4>
            <p>Toutes les pièces (Entrée, Séjour, Cuisine, Salle de bain, Chambres) sont déclarées en état d'usage général sauf mention contraire ci-dessous.</p>
            <h4>CLEFS ET COMPTEURS</h4>
            <p>Clés remises : Oui<br>
            Index Eau / Électricité : Conformes aux relevés initiaux.</p>
            <h4>OBSERVATIONS PARTICULIERES</h4>
            <p>[Instructions]</p>
            <p>Fait et signé le [Date].</p>";
        }

        if (!preg_match('/<[a-z][\s\S]*>/i', $tpl)) {
            $tpl = nl2br(e($tpl));
            $tpl = "<h3>ETAT DES LIEUX</h3>\n<p>" . $tpl . "</p>";
        }

        $dateF = $this->formatDateFrench($date);

        $tpl = str_replace(['[Locataire]', '[locataire]'], $locataire, $tpl);
        $tpl = str_replace(['[Logement]', '[logement]'], $logement, $tpl);
        $tpl = str_replace(['[Type]', '[type]', '{Type}'], $type, $tpl);
        $tpl = str_replace(['[Date]', '[date]'], $dateF, $tpl);

        if (!empty($instructions)) {
            $tpl .= "\n\n<hr>\n<h4>ANNEXE - CONDITIONS & OBSERVATIONS (IA Fallback)</h4>\n";
            $tpl .= "<p>Conformément aux observations et instructions formulées :<br>";
            $tpl .= "<em>" . nl2br(e($instructions)) . "</em></p>";
        }

        $banner = "<div style='background-color: #fef3c7; border: 1px solid #f59e0b; padding: 12px; border-radius: 8px; color: #92400e; margin-bottom: 20px; font-family: sans-serif; font-size: 13px;'>\n";
        $banner .= "<strong>Note de simulation :</strong> Cet état des lieux a été généré via notre moteur de secours local (Limite d'API Groq de secours/Quota dépassé). Le canevas de référence a été appliqué avec succès.\n";
        $banner .= "</div>\n\n";

        return $banner . $tpl;
    }

    /**
     * Chat with the management assistant.
     */
    public function assistantChat(Request $request)
    {
        \App\Helpers\SubscriptionHelper::checkOrAbort('ai');
        $request->validate([
            'message' => 'required|string',
            'context' => 'nullable|array',
            'mode' => 'nullable|string',
        ]);

        $message = $request->input('message');
        $context = $request->input('context');
        $mode = $request->input('mode') ?: 'chat';

        $user = Auth::user();
        $companyProfileId = $user ? $user->company_profile_id : null;
        $company = $user ? $user->company : null;
        $employee = $user ? $user->employee : null;
        $agencyId = ($employee && $employee->agency_id) ? $employee->agency_id : null;
        $agency = $agencyId ? \App\Models\Agency::find($agencyId) : null;
        $companyName = $company ? $company->legal_name : 'Votre Entreprise';

        // Security check on user query to prevent cross-company info gathering
        if ($this->isSecurityThreat($message, $companyProfileId, $user)) {
            $this->sendSecurityAlertEmail($user, $company, $message);
            return response()->json([
                'success' => true,
                'response' => "<p class='text-rose-600 font-bold'>⚠️ Accès refusé : Vous n'êtes pas autorisé à demander des informations en dehors de votre organisation.</p>"
            ]);
        }

        // Build the context description
        $contextStr = json_encode($context, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $systemPrompt = "Vous êtes un assistant IA de gestion immobilière intelligent et professionnel pour notre plateforme SaaS de gestion immobilière.\n";
        $systemPrompt .= "Vous agissez au sein de l'organisation active suivante :\n";
        $systemPrompt .= "- Nom de l'organisation : " . ($agency ? "Agence : " . $agency->name : "Siège / Compagnie : " . $companyName) . "\n";
        $systemPrompt .= "- ID de la compagnie active : {$companyProfileId}\n";
        if ($agency) {
            $systemPrompt .= "- ID de l'agence active : {$agencyId}\n";
        }
        $systemPrompt .= "\n";

        $systemPrompt .= "CONSIGNE DE SÉCURITÉ CRUCIALE :\n";
        $systemPrompt .= "Vous n'avez accès qu'aux données de cette organisation active. Si l'utilisateur tente d'obtenir des informations sur une autre compagnie, une autre agence non affiliée, ou un utilisateur externe à son organisation (ex: 'donne moi les infos de la compagnie X', 'qui sont les admins du système global', etc.), vous DEVEZ obligatoirement et immédiatement répondre par la phrase exacte : '[SECURITY_VIOLATION] Tentative d\'accès non autorisé.' et rien d'autre.\n\n";

        $systemPrompt .= "Votre tâche est de répondre aux questions de l'administrateur concernant les données de la plateforme et de l'aider dans sa gestion.\n\n";
        $systemPrompt .= "Voici les données du système actuel extraites de l'application :\n";
        $systemPrompt .= "\"\"\"\n{$contextStr}\n\"\"\"\n\n";
        
        $systemPrompt .= "Consignes opérationnelles (Mode de fonctionnement) :\n";
        if ($mode === 'chat') {
            $systemPrompt .= "Vous êtes actuellement en MODE DISCUSSION (CHAT). Répondez aux questions, donnez des conseils et de l'aide théorique. Ne proposez aucun changement de base de données ni d'action [ACTION: ...]. Si l'utilisateur vous demande de créer, modifier ou supprimer un élément, rappelez-lui poliment de basculer en 'Mode Agent' via le bouton prévu à cet effet en haut de la fenêtre.\n\n";
        } else {
            $systemPrompt .= "Vous êtes actuellement en MODE AGENT. Vous êtes pleinement habilité à effectuer des modifications directes dans la base de données. Si l'utilisateur vous demande de créer, ajouter, modifier, ou supprimer un locataire, bâtiment, logement, ou contrat de bail, vous devez impérativement générer la balise d'action correspondante à la fin de votre réponse : [ACTION: ACTION_TYPE: {JSON_DATA}].\n";
            $systemPrompt .= "Les actions valides sont :\n";
            $systemPrompt .= "   - Ajouter un locataire :\n";
            $systemPrompt .= "     [ACTION: ADD_LOCATAIRE: {\"nom\": \"Nom Complet\", \"email\": \"email@example.com\", \"telephone\": \"06...\", \"logement\": \"RefLogement ou Aucun\", \"garantie\": LoyerDeGarantie, \"statut\": \"Actif\"}]\n";
            $systemPrompt .= "   - Supprimer un locataire :\n";
            $systemPrompt .= "     [ACTION: DELETE_LOCATAIRE: {\"nom\": \"Nom Complet\"}]\n";
            $systemPrompt .= "   - Ajouter un logement :\n";
            $systemPrompt .= "     [ACTION: ADD_LOGEMENT: {\"reference\": \"RéfUnique\", \"batiment\": \"NomBatiment\", \"categorie\": \"Appartement|Bureau|Studio|Duplex\", \"etage\": NumeroEtage, \"surface\": SurfaceM2, \"loyer\": MontantLoyer, \"statut\": \"Libre\"}]\n";
            $systemPrompt .= "   - Supprimer un logement :\n";
            $systemPrompt .= "     [ACTION: DELETE_LOGEMENT: {\"reference\": \"RéfUnique\"}]\n";
            $systemPrompt .= "   - Ajouter un bâtiment :\n";
            $systemPrompt .= "     [ACTION: ADD_BATIMENT: {\"nom\": \"Nom Immeuble\", \"ville\": \"Ville\"}]\n";
            $systemPrompt .= "   - Supprimer un bâtiment :\n";
            $systemPrompt .= "     [ACTION: DELETE_BATIMENT: {\"nom\": \"Nom Immeuble\"}]\n";
            $systemPrompt .= "   - Ajouter un contrat de bail :\n";
            $systemPrompt .= "     [ACTION: ADD_CONTRAT: {\"locataire\": \"Nom Locataire\", \"reference\": \"RéfLogement\", \"loyer\": Loyer, \"caution\": Caution, \"debut\": \"YYYY-MM-DD\", \"fin\": \"YYYY-MM-DD\", \"statut\": \"Actif\"}]\n";
            $systemPrompt .= "   - Supprimer un contrat :\n";
            $systemPrompt .= "     [ACTION: DELETE_CONTRAT: {\"numero\": \"CTR-...\"}]\n\n";
            $systemPrompt .= "Renvoyez TOUJOURS la balise d'action au format EXACT décrit ci-dessus, collée à la fin de votre texte.\n\n";
        }

        $systemPrompt .= "Consignes importantes :\n";
        $systemPrompt .= "1. Répondez de manière professionnelle, précise et en français.\n";
        $systemPrompt .= "2. Si l'administrateur vous pose des questions spécifiques (ex: 'Qui n'a pas payé ?', 'Quel est le loyer moyen ?', etc.), analysez le JSON pour donner des chiffres réels et des noms exacts issus du contexte.\n";
        $systemPrompt .= "3. Si l'utilisateur exprime l'intention d'aller sur une page ou demande une redirection, vous devez ajouter à la toute fin de votre réponse textuelle la balise spéciale [NAVIGATE: /path/to/page] pour déclencher la redirection automatique côté client.\n";
        $systemPrompt .= "Voici les routes valides :\n";
        $systemPrompt .= "   - Tableau de bord principal : /dashboard/master\n";
        $systemPrompt .= "   - Gestion des Bâtiments : /dashboard/immobilier/batiments\n";
        $systemPrompt .= "   - Gestion des Logements : /dashboard/immobilier/logements\n";
        $systemPrompt .= "   - Affectation des logements : /dashboard/immobilier/affectations\n";
        $systemPrompt .= "   - Contrats de bail : /dashboard/immobilier/contrats\n";
        $systemPrompt .= "   - Liste des Locataires : /dashboard/immobilier/locataires\n";
        $systemPrompt .= "   - Factures / Facturation : /dashboard/immobilier/factures\n";
        $systemPrompt .= "   - Paiements de loyer : /dashboard/immobilier/paiements\n";
        $systemPrompt .= "   - Renouvellements de bail : /dashboard/immobilier/renouvellements\n";
        $systemPrompt .= "   - Actes d'Engagement : /dashboard/immobilier/engagements\n";
        $systemPrompt .= "   - États des lieux : /dashboard/immobilier/etats-des-lieux\n";
        $systemPrompt .= "   - Historique d'activité : /dashboard/immobilier/historique\n\n";

        $generatedText = '';
        $generateViaIA = true;

        try {
            $model = config('ai.groq_model', 'llama-3.3-70b-versatile');
            $response = Prism::text()
                ->using(Provider::Groq, $model)
                ->withPrompt("Message de l'administrateur : {$message}\n\nRépondez en respectant les consignes système.")
                ->withSystemPrompt($systemPrompt)
                ->asText();
            $generatedText = $response->text;
        } catch (Exception $e) {
            Log::warning('Groq API call failed for Assistant Chat, falling back to local generation. Error: ' . $e->getMessage());
            $generateViaIA = false;
        }

        if (!$generateViaIA) {
            // Local fallback logic
            $generatedText = $this->assistantLocalFallback($message, $context);
        }

        // Post-generation security validation check on LLM response
        if (str_contains($generatedText, '[SECURITY_VIOLATION]')) {
            $this->sendSecurityAlertEmail($user, $company, $message);
            return response()->json([
                'success' => true,
                'response' => "<p class='text-rose-600 font-bold'>⚠️ Accès refusé : Vous n'êtes pas autorisé à demander des informations en dehors de votre organisation.</p>"
            ]);
        }

        // Parse and execute database actions if in Agent mode
        if ($mode === 'agent' && preg_match('/\[ACTION:\s*([A-Z_]+):\s*({[\s\S]*?})\]/i', $generatedText, $actionMatch)) {
            $actionType = strtoupper($actionMatch[1]);
            $actionData = json_decode($actionMatch[2], true);
            
            if ($actionData) {
                try {
                    $executionResult = $this->executeDatabaseAction($actionType, $actionData, $companyProfileId, $agencyId);
                    // Remove action tag from final text and append execution note
                    $generatedText = preg_replace('/\[ACTION:[\s\S]*?\]/i', '', $generatedText);
                    $generatedText .= "\n\n⚡ **[Action Exécutée sur la BD]** : " . $executionResult;
                } catch (Exception $actionEx) {
                    $generatedText = preg_replace('/\[ACTION:[\s\S]*?\]/i', '', $generatedText);
                    $generatedText .= "\n\n❌ **[Échec de l'action]** : " . $actionEx->getMessage();
                }
            }
        }

        return response()->json([
            'success' => true,
            'response' => $generatedText,
        ]);
    }

    /**
     * Helper to detect security threat or cross-company queries
     */
    private function isSecurityThreat($message, $companyProfileId, $user)
    {
        $messageLower = strtolower($message);
        
        $forbiddenKeywords = [
            'autre entreprise', 'autres entreprises', 'other company', 'other companies',
            'tous les utilisateurs', 'toutes les entreprises', 'toutes les compagnies',
            'company_profiles', 'users table', 'admin data', 'database passwords',
            'immosaas237', 'immosaas'
        ];
        
        foreach ($forbiddenKeywords as $keyword) {
            if (str_contains($messageLower, $keyword)) {
                return true;
            }
        }
        
        preg_match_all('/\bcompany_profile_id\b|\bcompany\b/i', $message, $matches);
        if (count($matches[0]) > 0 && preg_match('/\b[0-9]+\b/', $message, $numMatch)) {
            $id = intval($numMatch[0]);
            if ($id !== intval($companyProfileId)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Send email notification to immosaas237@gmail.com on unauthorized action
     */
    private function sendSecurityAlertEmail($user, $company, $message)
    {
        try {
            \Illuminate\Support\Facades\Mail::raw(
                "Alerte de sécurité : Tentative d'accès non autorisé détectée dans l'assistant IA.\n\n" .
                "Détails de la session :\n" .
                "- Utilisateur : " . ($user ? $user->name . " (" . $user->email . ")" : 'Non identifié') . "\n" .
                "- Entreprise : " . ($company ? $company->legal_name . " (ID: " . $company->id . ")" : 'N/A') . "\n" .
                "- Horodatage : " . date('Y-m-d H:i:s') . "\n\n" .
                "Message de l'utilisateur ayant déclenché l'alerte :\n" .
                "\"\"\"\n{$message}\n\"\"\"",
                function ($mail) {
                    $mail->to('immosaas237@gmail.com')
                         ->subject('Alerte de Sécurité IA - ImmoSaaS');
                }
            );
        } catch (\Exception $e) {
            Log::error("Failed to send security alert email: " . $e->getMessage());
        }
    }

    /**
     * Execute SQL database changes directly on Eloquent models in backend Agent mode
     */
    private function executeDatabaseAction($type, $data, $companyProfileId, $agencyId)
    {
        switch ($type) {
            case 'ADD_LOCATAIRE':
                if (empty($data['nom'])) {
                    throw new Exception("Le nom du locataire est obligatoire.");
                }
                
                $userRecord = new \App\Models\User();
                $userRecord->name = $data['nom'];
                $userRecord->email = $data['email'] ?? (strtolower(str_replace(' ', '', $data['nom'])) . '@example.com');
                if (\App\Models\User::where('email', $userRecord->email)->exists()) {
                    $userRecord->email = 'loc_' . time() . '_' . $userRecord->email;
                }
                $userRecord->password = \Illuminate\Support\Facades\Hash::make('password123');
                $userRecord->account_type = 'client';
                $userRecord->company_profile_id = $companyProfileId;
                $userRecord->save();

                $locataire = new \App\Models\Locataire();
                $locataire->user_id = $userRecord->id;
                $locataire->company_profile_id = $companyProfileId;
                $locataire->agency_id = $agencyId;
                $locataire->telephone = $data['telephone'] ?? '';
                $locataire->statut = $data['statut'] ?? 'Actif';
                $locataire->save();

                return "Le locataire '" . $data['nom'] . "' a été créé avec succès (ID: " . $locataire->id . ").";

            case 'DELETE_LOCATAIRE':
                if (empty($data['nom'])) {
                    throw new Exception("Le nom du locataire est requis.");
                }
                $query = \App\Models\Locataire::where('company_profile_id', $companyProfileId);
                if ($agencyId) {
                    $query->where('agency_id', $agencyId);
                }
                $loc = $query->whereHas('user', function($q) use ($data) {
                    $q->where('name', 'like', '%' . $data['nom'] . '%');
                })->first();

                if (!$loc) {
                    throw new Exception("Locataire '" . $data['nom'] . "' introuvable.");
                }
                $nom = $loc->user ? $loc->user->name : $data['nom'];
                $u = $loc->user;
                $loc->delete();
                if ($u) {
                    $u->delete();
                }
                return "Le locataire '" . $nom . "' et son compte utilisateur associé ont été supprimés.";

            case 'ADD_BATIMENT':
                if (empty($data['nom'])) {
                    throw new Exception("Le nom du bâtiment est obligatoire.");
                }
                $bat = new \App\Models\Batiment();
                $bat->company_profile_id = $companyProfileId;
                $bat->agency_id = $agencyId;
                $bat->nom = $data['nom'];
                $bat->ville = $data['ville'] ?? '';
                $bat->save();
                return "Le bâtiment '" . $data['nom'] . "' a été créé avec succès.";

            case 'DELETE_BATIMENT':
                if (empty($data['nom'])) {
                    throw new Exception("Le nom du bâtiment est requis.");
                }
                $query = \App\Models\Batiment::where('company_profile_id', $companyProfileId);
                if ($agencyId) {
                    $query->where('agency_id', $agencyId);
                }
                $bat = $query->where('nom', 'like', '%' . $data['nom'] . '%')->first();
                if (!$bat) {
                    throw new Exception("Bâtiment '" . $data['nom'] . "' introuvable.");
                }
                $nom = $bat->nom;
                $bat->delete();
                return "Le bâtiment '" . $nom . "' a été supprimé.";

            case 'ADD_LOGEMENT':
                if (empty($data['reference'])) {
                    throw new Exception("La référence du logement est obligatoire.");
                }
                $bat = null;
                if (!empty($data['batiment'])) {
                    $bat = \App\Models\Batiment::where('company_profile_id', $companyProfileId)
                        ->where('nom', 'like', '%' . $data['batiment'] . '%')
                        ->first();
                }
                $cat = null;
                if (!empty($data['categorie'])) {
                    $cat = \App\Models\Categorie::where('nom', 'like', '%' . $data['categorie'] . '%')->first();
                }
                if (!$cat) {
                    $cat = \App\Models\Categorie::first();
                }

                $log = new \App\Models\Logement();
                $log->company_profile_id = $companyProfileId;
                $log->agency_id = $agencyId;
                $log->batiment_id = $bat ? $bat->id : null;
                $log->categorie_id = $cat ? $cat->id : null;
                $log->reference = $data['reference'];
                $log->etage = $data['etage'] ?? 1;
                $log->surface = $data['surface'] ?? 50;
                $log->loyer = $data['loyer'] ?? 0;
                $log->statut = $data['statut'] ?? 'Libre';
                $log->save();
                return "Le logement '" . $data['reference'] . "' a été créé avec succès.";

            case 'DELETE_LOGEMENT':
                if (empty($data['reference'])) {
                    throw new Exception("La référence du logement est requise.");
                }
                $query = \App\Models\Logement::where('company_profile_id', $companyProfileId);
                if ($agencyId) {
                    $query->where('agency_id', $agencyId);
                }
                $log = $query->where('reference', 'like', '%' . $data['reference'] . '%')->first();
                if (!$log) {
                    throw new Exception("Logement '" . $data['reference'] . "' introuvable.");
                }
                $ref = $log->reference;
                $log->delete();
                return "Le logement '" . $ref . "' a été supprimé.";

            case 'ADD_CONTRAT':
                $loc = null;
                if (!empty($data['locataire'])) {
                    $loc = \App\Models\Locataire::where('company_profile_id', $companyProfileId)
                        ->whereHas('user', function($q) use ($data) {
                            $q->where('name', 'like', '%' . $data['locataire'] . '%');
                        })->first();
                }
                $log = null;
                if (!empty($data['reference'])) {
                    $log = \App\Models\Logement::where('company_profile_id', $companyProfileId)
                        ->where('reference', 'like', '%' . $data['reference'] . '%')
                        ->first();
                }
                $tc = \App\Models\TypeContrat::where('company_profile_id', $companyProfileId)->first();

                $contrat = new \App\Models\Contrat();
                $contrat->company_profile_id = $companyProfileId;
                $contrat->agency_id = $agencyId;
                $contrat->locataire_id = $loc ? $loc->id : null;
                $contrat->logement_id = $log ? $log->id : null;
                $contrat->type_contrat_id = $tc ? $tc->id : null;
                $contrat->loyer = $data['loyer'] ?? ($log ? $log->loyer : 0);
                $contrat->caution = $data['caution'] ?? (($log ? $log->loyer : 0) * 2);
                $contrat->debut = $data['debut'] ?? date('Y-m-d');
                $contrat->fin = $data['fin'] ?? date('Y-m-d', strtotime('+1 year'));
                $contrat->content = $data['content'] ?? '<h3>Contrat de bail</h3>';
                $contrat->statut = $data['statut'] ?? 'Actif';
                $contrat->save();
                return "Le contrat de bail '" . $contrat->numero . "' a été créé.";

            case 'DELETE_CONTRAT':
                if (empty($data['numero'])) {
                    throw new Exception("Le numéro de contrat est requis.");
                }
                $query = \App\Models\Contrat::where('company_profile_id', $companyProfileId);
                if ($agencyId) {
                    $query->where('agency_id', $agencyId);
                }
                $contrat = $query->where('numero', 'like', '%' . $data['numero'] . '%')->first();
                if (!$contrat) {
                    throw new Exception("Contrat '" . $data['numero'] . "' introuvable.");
                }
                $num = $contrat->numero;
                $contrat->delete();
                return "Le contrat '" . $num . "' a été supprimé.";

            default:
                throw new Exception("Action non prise en charge : " . $type);
        }
    }

    /**
     * Local fallback response generator for assistant chat.
     */
    private function assistantLocalFallback($message, $context)
    {
        $messageLower = strtolower($message);
        $res = "<div style='background-color: #fef3c7; border: 1px solid #f59e0b; padding: 10px; border-radius: 8px; color: #92400e; margin-bottom: 12px; font-size: 12px;'><strong>Mode de secours local :</strong> La connexion à l'IA (Groq) a échoué. Voici une réponse basée sur l'analyse heuristique des données locales.</div>";

        if (str_contains($messageLower, 'paye') || str_contains($messageLower, 'payé') || str_contains($messageLower, 'facture') || str_contains($messageLower, 'loyer')) {
            // Check unpaid invoices
            $factures = isset($context['factures']) ? $context['factures'] : [];
            $unpaid = array_filter($factures, function($f) {
                return isset($f['statut']) && ($f['statut'] === 'Impayé' || $f['statut'] === 'En attente' || str_contains(strtolower($f['statut']), 'impay'));
            });

            if (empty($unpaid)) {
                $res .= "<p>Selon les données actuelles, tous les locataires sont à jour dans leurs paiements de factures et loyers.</p>";
            } else {
                $res .= "<p>Voici la liste des locataires ayant des factures/loyers impayés ou en attente :</p><ul>";
                foreach ($unpaid as $u) {
                    $loc = isset($u['locataire']) ? $u['locataire'] : 'Inconnu';
                    $montant = isset($u['montant']) ? $u['montant'] : '0';
                    $ref = isset($u['reference']) ? $u['reference'] : '';
                    $date = isset($u['date']) ? $u['date'] : '';
                    $statut = isset($u['statut']) ? $u['statut'] : 'Impayé';
                    $res .= "<li><strong>{$loc}</strong> - Montant : <strong>{$montant} €</strong> (Date : {$date}, Statut : {$statut})</li>";
                }
                $res .= "</ul>";
            }

            if (str_contains($messageLower, 'va') || str_contains($messageLower, 'redirige') || str_contains($messageLower, 'page') || str_contains($messageLower, 'affiche')) {
                if (str_contains($messageLower, 'facture')) {
                    $res .= "<p>Je vous redirige vers l'historique de facturation. [NAVIGATE: /dashboard/immobilier/factures]</p>";
                } else {
                    $res .= "<p>Je vous redirige vers le tableau des paiements. [NAVIGATE: /dashboard/immobilier/paiements]</p>";
                }
            }
            return $res;
        }

        if (str_contains($messageLower, 'locataire')) {
            $locataires = isset($context['locataires']) ? $context['locataires'] : [];
            $res .= "<p>Vous avez actuellement <strong>" . count($locataires) . "</strong> locataires enregistrés dans le système.</p>";
            if (!empty($locataires)) {
                $res .= "<ul>";
                foreach (array_slice($locataires, 0, 5) as $l) {
                    $nom = isset($l['nom']) ? $l['nom'] : 'N/A';
                    $statut = isset($l['statut']) ? $l['statut'] : 'N/A';
                    $res .= "<li><strong>{$nom}</strong> (Statut : {$statut})</li>";
                }
                if (count($locataires) > 5) {
                    $res .= "<li>... et " . (count($locataires) - 5) . " autres.</li>";
                }
                $res .= "</ul>";
            }
            $res .= "<p>Je vous redirige vers la liste des locataires. [NAVIGATE: /dashboard/immobilier/locataires]</p>";
            return $res;
        }

        if (str_contains($messageLower, 'contrat') || str_contains($messageLower, 'bail')) {
            $contrats = isset($context['contrats']) ? $context['contrats'] : [];
            $res .= "<p>Il y a <strong>" . count($contrats) . "</strong> contrats de bail enregistrés.</p>";
            $res .= "<p>Je vous redirige vers les contrats. [NAVIGATE: /dashboard/immobilier/contrats]</p>";
            return $res;
        }

        // Generic redirection help
        if (str_contains($messageLower, 'bâtiment') || str_contains($messageLower, 'batiment')) {
            $res .= "<p>Je vous redirige vers les bâtiments. [NAVIGATE: /dashboard/immobilier/batiments]</p>";
            return $res;
        }
        if (str_contains($messageLower, 'logement')) {
            $res .= "<p>Je vous redirige vers les logements. [NAVIGATE: /dashboard/immobilier/logements]</p>";
            return $res;
        }
        if (str_contains($messageLower, 'renouvellement')) {
            $res .= "<p>Je vous redirige vers les renouvellements de bail. [NAVIGATE: /dashboard/immobilier/renouvellements]</p>";
            return $res;
        }
        if (str_contains($messageLower, 'engagement')) {
            $res .= "<p>Je vous redirige vers les actes d'engagement. [NAVIGATE: /dashboard/immobilier/engagements]</p>";
            return $res;
        }
        if (str_contains($messageLower, 'etat') || str_contains($messageLower, 'état')) {
            $res .= "<p>Je vous redirige vers les états des lieux. [NAVIGATE: /dashboard/immobilier/etats-des-lieux]</p>";
            return $res;
        }

        $res .= "<p>Je suis votre assistant de gestion immobilière. Posez-moi des questions sur vos locataires, vos loyers, les impayés ou demandez-moi de vous diriger vers une page spécifique !</p>";
        return $res;
    }

    /**
     * Generate a polite rejection motif using Groq.
     */
    public function generateRejectionMotif(Request $request)
    {
        \App\Helpers\SubscriptionHelper::checkOrAbort('ai');
        $request->validate([
            'locataire_name' => 'required|string',
            'reference_logement' => 'required|string',
            'nouveau_loyer' => 'required|numeric',
            'motif_demande' => 'nullable|string',
        ]);
        
        $locataireName = $request->input('locataire_name');
        $referenceLogement = $request->input('reference_logement');
        $nouveauLoyer = $request->input('nouveau_loyer');
        $motifDemande = $request->input('motif_demande') ?: 'Non spécifié';

        $prompt = "Vous êtes un gestionnaire immobilier professionnel.\n";
        $prompt .= "Rédigez un motif de rejet professionnel, courtois et argumenté pour refuser une demande de renouvellement de bail formulée par le locataire {$locataireName} pour le logement {$referenceLogement}.\n";
        $prompt .= "Informations de la demande :\n";
        $prompt .= "- Nouveau loyer proposé par le locataire : {$nouveauLoyer} €\n";
        $prompt .= "- Motif/Instructions du locataire : {$motifDemande}\n\n";
        $prompt .= "Générez un motif de rejet solide et poli en français (environ 2-4 phrases). Le motif doit expliquer gentiment pourquoi nous ne pouvons pas accepter cette demande de renouvellement sous ces conditions (par exemple, parce que le nouveau loyer proposé est trop bas par rapport au marché, ou pour des raisons opérationnelles de rénovation/récupération du logement). Votre réponse doit être prête à être envoyée au locataire. Ne renvoyez aucune introduction ni conclusion, commencez directement par le motif de refus.";

        $generatedText = '';
        $generateViaIA = true;
        try {
            $model = config('ai.groq_model', 'llama-3.3-70b-versatile');
            $response = Prism::text()
                ->using(Provider::Groq, $model)
                ->withPrompt($prompt)
                ->asText();
            $generatedText = $response->text;
        } catch (\Exception $e) {
            \Log::warning('Groq API call failed for Rejection Motif, falling back. Error: ' . $e->getMessage());
            $generateViaIA = false;
        }

        if (!$generateViaIA) {
            $generatedText = "Nous regrettons de vous informer que votre demande de renouvellement pour le logement {$referenceLogement} n'a pas pu être acceptée. Le loyer proposé de {$nouveauLoyer} € ne correspond pas aux conditions actuelles du marché ou aux critères de gestion de notre entreprise.";
        }

        return response()->json([
            'success' => true,
            'motif' => trim($generatedText),
        ]);
    }
}
