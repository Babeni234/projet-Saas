<?php

namespace App\Http\Controllers;

use App\Models\ImmotokClient;
use App\Models\ImmotokMessage;
use App\Models\CompanyProfile;
use App\Models\Batiment;
use App\Models\Logement;
use App\Models\Agency;
use Illuminate\Http\Request;
use Gemini\Laravel\Facades\Gemini;

class ImmotokChatController extends Controller
{
    protected function getClient()
    {
        if (session()->has('immotok_client_id')) {
            return ImmotokClient::find(session()->get('immotok_client_id'));
        }
        return null;
    }

    public function getMessages($companyProfileId)
    {
        $client = $this->getClient();
        if (!$client) {
            return response()->json(['success' => false, 'message' => 'Non authentifié.'], 401);
        }

        $messages = ImmotokMessage::where('immotok_client_id', $client->id)
            ->where('company_profile_id', $companyProfileId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    public function sendMessage(Request $request, $companyProfileId)
    {
        $client = $this->getClient();
        if (!$client) {
            return response()->json(['success' => false, 'message' => 'Non authentifié.'], 401);
        }

        $request->validate([
            'message' => 'required|string|max:1000',
            'agency_id' => 'nullable|exists:agencies,id',
        ]);

        // Save client message
        $clientMsg = ImmotokMessage::create([
            'immotok_client_id' => $client->id,
            'company_profile_id' => $companyProfileId,
            'agency_id' => $request->agency_id,
            'sender' => 'client',
            'message' => $request->message,
        ]);

        // Check if AI is enabled for this company or agency
        $aiEnabled = false;
        $entityName = 'l\'agence';
        
        if ($request->filled('agency_id')) {
            $agency = Agency::find($request->agency_id);
            if ($agency) {
                $aiEnabled = (bool) $agency->immotok_ai_enabled;
                $entityName = $agency->name;
            }
        } else {
            $company = CompanyProfile::find($companyProfileId);
            if ($company) {
                $aiEnabled = (bool) $company->immotok_ai_enabled;
                $entityName = $company->legal_name;
            }
        }

        $aiMsg = null;
        if ($aiEnabled) {
            try {
                $aiMsgText = $this->generateAiReply($client->id, $companyProfileId, $request->agency_id, $request->message, $entityName);
                
                // Save AI message
                $aiMsg = ImmotokMessage::create([
                    'immotok_client_id' => $client->id,
                    'company_profile_id' => $companyProfileId,
                    'agency_id' => $request->agency_id,
                    'sender' => $request->agency_id ? 'agency' : 'company',
                    'message' => $aiMsgText,
                    'is_ai_reply' => true,
                ]);
            } catch (\Exception $e) {
                logger()->error("ImmoTok AI Chatbot Error: " . $e->getMessage());
                // Fallback reply
                $aiMsg = ImmotokMessage::create([
                    'immotok_client_id' => $client->id,
                    'company_profile_id' => $companyProfileId,
                    'agency_id' => $request->agency_id,
                    'sender' => $request->agency_id ? 'agency' : 'company',
                    'message' => "Bonjour ! Nous avons bien reçu votre message et nos conseillers immobiliers vous répondront dans les plus brefs délais.",
                    'is_ai_reply' => true,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'client_message' => $clientMsg,
            'ai_message' => $aiMsg,
        ]);
    }

    private function generateAiReply($clientId, $companyProfileId, $agencyId, $latestClientMessage, $entityName)
    {
        $company = CompanyProfile::find($companyProfileId);
        
        // Query catalogs
        $batQuery = Batiment::where('company_profile_id', $companyProfileId)->where('deleted', 0);
        if ($agencyId) {
            $batQuery->where('agency_id', $agencyId);
        }
        $batiments = $batQuery->get(['nom', 'ville', 'quartier', 'description']);

        $logQuery = Logement::where('company_profile_id', $companyProfileId)->where('deleted', 0)->with('categorie');
        if ($agencyId) {
            $logQuery->where('agency_id', $agencyId);
        }
        $logements = $logQuery->get();

        // Build details context
        $catalog = "";
        if ($batiments->count() > 0) {
            $catalog .= "Bâtiments gérés :\n";
            foreach ($batiments as $bat) {
                $catalog .= "- Bâtiment '{$bat->nom}' à {$bat->ville} ({$bat->quartier}) : {$bat->description}\n";
            }
        }
        if ($logements->count() > 0) {
            $catalog .= "Logements gérés :\n";
            foreach ($logements as $log) {
                $catNom = $log->categorie->nom ?? 'Logement';
                $catalog .= "- {$catNom} (Réf: {$log->reference}), loyer de " . number_format($log->loyer, 0, ',', ' ') . " FCFA/mois, statut: {$log->statut}, surface: {$log->surface}m²\n";
            }
        }

        // Fetch recent messages for conversational context
        $history = ImmotokMessage::where('immotok_client_id', $clientId)
            ->where('company_profile_id', $companyProfileId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->reverse();

        $chatHistoryStr = "";
        foreach ($history as $msg) {
            $role = ($msg->sender === 'client') ? 'Client' : 'Assistant';
            $chatHistoryStr .= "{$role} : {$msg->message}\n";
        }

        // Call Gemini
        $systemPrompt = "Tu es l'assistant IA officiel de '{$entityName}' (une agence immobilière ou entreprise de gestion).
Tu parles au nom de l'entreprise immobilière '{$entityName}'. Sois extrêmement professionnel, courtois et chaleureux.
Réponds exclusivement en français. Si le client te demande des détails sur les biens ou les loyers, réfère-toi aux données réelles de notre catalogue ci-dessous.
Si le bien ou le service n'est pas dans le catalogue, réponds poliment que nous allons nous renseigner et l'inviter à laisser son numéro pour qu'un agent humain le rappelle.

Voici le catalogue de nos biens disponibles :
{$catalog}

Détails de l'entreprise :
- Nom : {$company->legal_name}
- Téléphone : {$company->phone}
- Ville : {$company->city}

Historique de la conversation :
{$chatHistoryStr}
Client : {$latestClientMessage}

Rédige une réponse courte (2-4 phrases maximum), engageante et directe sans préambule ni formules meta.";

        $apiKey = config('gemini.api_key');
        if (empty($apiKey)) {
            throw new \Exception("Clé API Gemini non configurée.");
        }

        $response = Gemini::generativeModel(model: config('ai.gemini_model', 'gemini-2.0-flash'))
            ->generateContent([$systemPrompt]);

        return trim($response->text());
    }
}
