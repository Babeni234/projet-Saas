<?php

namespace App\Services\Ai;

use App\Models\Contract;
use App\Models\Incident;
use App\Models\Property;
use App\Models\Receipt;
use App\Models\Visit;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiAssistantService
{
    protected int $userId;
    protected string $role;
    protected AiTools $tools;
    protected const MAX_CHAIN_DEPTH = 5;

    public function __construct(int $userId, string $role = 'landlord')
    {
        $this->userId = $userId;
        $this->role = $role;
        $this->tools = new AiTools($userId);
    }

    // ── Standard (non-streaming) chat ──
    public function chat(string $message, array $history = []): array
    {
        $apiKey = config('ai.api_key');
        return $apiKey
            ? $this->callLlm($message, $history)
            : $this->localFallback($message);
    }

    // ── Streaming chat ──
    public function chatStream(string $message, array $history, callable $onChunk, callable $onToolResult = null): void
    {
        $apiKey = config('ai.api_key');
        if ($apiKey) {
            $this->callLlmStream($message, $history, $onChunk, $onToolResult);
        } else {
            // Fallback: immediately yield the full response
            $result = $this->localFallback($message);
            $onChunk(['type' => 'text', 'content' => $result['text']]);
            $onChunk(['type' => 'done', 'source' => 'local']);
        }
    }

    // ════════════════════════════════════
    //  LLM (Groq/OpenAI) - non-streaming
    // ════════════════════════════════════

    protected function callLlm(string $message, array $history): array
    {
        $messages = $this->buildMessages($message, $history);
        $result = $this->llmRequest($messages);

        if (!$result['success']) {
            return $this->localFallback($message);
        }

        $choice = $result['data'];
        return $this->handleToolCalls($choice, $messages, 0);
    }

    protected function llmRequest(array $messages): array
    {
        try {
            $response = Http::timeout(config('ai.request_timeout', 30))
                ->withHeaders([
                    'Authorization' => 'Bearer ' . config('ai.api_key'),
                    'Content-Type' => 'application/json',
                ])
                ->post(rtrim(config('ai.base_url'), '/') . '/chat/completions', [
                    'model' => config('ai.model', 'llama-3.1-70b-versatile'),
                    'messages' => $messages,
                    'tools' => $this->tools->listTools(),
                    'tool_choice' => 'auto',
                    'temperature' => 0.7,
                    'max_tokens' => 2000,
                ]);

            if (!$response->successful()) {
                Log::warning('LLM API error: ' . $response->body());
                return ['success' => false];
            }

            $data = $response->json();
            return ['success' => true, 'data' => $data['choices'][0]['message'] ?? []];
        } catch (\Throwable $e) {
            Log::error('LLM request failed: ' . $e->getMessage());
            return ['success' => false];
        }
    }

    // ════════════════════════════════════
    //  LLM - STREAMING
    // ════════════════════════════════════

    protected function callLlmStream(string $message, array $history, callable $onChunk, callable $onToolResult = null): void
    {
        $messages = $this->buildMessages($message, $history);
        $this->streamRequest($messages, $onChunk, $onToolResult, 0);
    }

    protected function streamRequest(array $messages, callable $onChunk, ?callable $onToolResult, int $depth): void
    {
        if ($depth >= self::MAX_CHAIN_DEPTH) {
            $onChunk(['type' => 'text', 'content' => "\n\n*J'ai atteint la limite d'actions pour cette requête.*"]);
            $onChunk(['type' => 'done']);
            return;
        }

        $buffer = '';
        $toolCallBuffer = null; // ['name' => ..., 'arguments' => ...]

        try {
            $response = Http::timeout(60)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . config('ai.api_key'),
                    'Content-Type' => 'application/json',
                    'Accept' => 'text/event-stream',
                ])
                ->post(rtrim(config('ai.base_url'), '/') . '/chat/completions', [
                    'model' => config('ai.model', 'llama-3.1-70b-versatile'),
                    'messages' => $messages,
                    'tools' => $this->tools->listTools(),
                    'tool_choice' => 'auto',
                    'temperature' => 0.7,
                    'max_tokens' => 2000,
                    'stream' => true,
                ]);

            if (!$response->successful()) {
                $fallback = $this->localFallback($messages[count($messages)-1]['content'] ?? $messages[0]['content'] ?? '');
                $onChunk(['type' => 'text', 'content' => $fallback['text']]);
                $onChunk(['type' => 'done']);
                return;
            }

            $body = $response->getBody();
            $assistantContent = '';
            $toolCalls = [];

            while (!$body->eof()) {
                $line = $body->read(1024);
                $lines = explode("\n", $line);

                foreach ($lines as $l) {
                    $l = trim($l);
                    if (empty($l) || $l === 'data: [DONE]') continue;
                    if (!str_starts_with($l, 'data: ')) continue;

                    $json = json_decode(substr($l, 6), true);
                    if (!$json) continue;

                    $delta = $json['choices'][0]['delta'] ?? [];
                    $finish = $json['choices'][0]['finish_reason'] ?? null;

                    // Content chunk
                    if (!empty($delta['content'])) {
                        $chunk = $delta['content'];
                        $assistantContent .= $chunk;
                        $onChunk(['type' => 'text', 'content' => $chunk]);
                    }

                    // Tool call delta
                    if (!empty($delta['tool_calls'])) {
                        foreach ($delta['tool_calls'] as $tc) {
                            $idx = $tc['index'] ?? 0;
                            if (!isset($toolCalls[$idx])) {
                                $toolCalls[$idx] = ['name' => '', 'arguments' => ''];
                            }
                            if (!empty($tc['function']['name'])) {
                                $toolCalls[$idx]['name'] .= $tc['function']['name'];
                            }
                            if (!empty($tc['function']['arguments'])) {
                                $toolCalls[$idx]['arguments'] .= $tc['function']['arguments'];
                            }
                        }
                    }

                    if ($finish === 'tool_calls') {
                        // We have tool calls to execute
                        $onChunk(['type' => 'tool_start']);

                        $messages[] = ['role' => 'assistant', 'content' => $assistantContent, 'tool_calls' => []];

                        foreach ($toolCalls as $tc) {
                            $name = $tc['name'];
                            $args = json_decode($tc['arguments'], true) ?? [];

                            $onChunk(['type' => 'tool_call', 'name' => $name, 'args' => $args]);

                            $result = $this->tools->execute($name, $args);

                            if ($onToolResult) {
                                $onToolResult($name, $args, $result);
                            }

                            $messages[] = [
                                'role' => 'tool',
                                'tool_call_id' => 'call_' . uniqid(),
                                'content' => json_encode($result),
                            ];

                            $onChunk(['type' => 'tool_result', 'name' => $name, 'success' => $result['success'] ?? false]);
                        }

                        $onChunk(['type' => 'tool_end']);

                        // Continue chain
                        $this->streamRequest($messages, $onChunk, $onToolResult, $depth + 1);
                        return;
                    }
                }
            }

            $onChunk(['type' => 'done', 'source' => 'groq']);
        } catch (\Throwable $e) {
            Log::error('Stream request failed: ' . $e->getMessage());
            $fallback = $this->localFallback($messages[count($messages)-1]['content'] ?? '');
            $onChunk(['type' => 'text', 'content' => $fallback['text']]);
            $onChunk(['type' => 'done', 'source' => 'fallback']);
        }
    }

    // ════════════════════════════════════
    //  TOOL CHAIN (non-streaming)
    // ════════════════════════════════════

    protected function handleToolCalls(array $choice, array $messages, int $depth): array
    {
        $toolCalls = $choice['tool_calls'] ?? [];

        if (empty($toolCalls)) {
            return [
                'text' => $choice['content'] ?? 'Je n\'ai pas pu traiter votre demande.',
                'source' => 'groq',
            ];
        }

        if ($depth >= self::MAX_CHAIN_DEPTH) {
            return ['text' => 'J\'ai atteint la limite d\'actions.', 'source' => 'groq'];
        }

        $messages[] = ['role' => 'assistant', 'content' => $choice['content'] ?? '', 'tool_calls' => $toolCalls];

        $toolTexts = [];
        foreach ($toolCalls as $call) {
            $name = $call['function']['name'];
            $args = json_decode($call['function']['arguments'], true) ?? [];
            $result = $this->tools->execute($name, $args);
            $toolTexts[] = "$name: " . json_encode($result);
            $messages[] = ['role' => 'tool', 'tool_call_id' => $call['id'] ?? 'call_1', 'content' => json_encode($result)];
        }

        $nextResult = $this->llmRequest($messages);
        if (!$nextResult['success']) {
            $text = "Voici ce que j'ai trouvé :\n\n" . implode("\n", $toolTexts);
            return ['text' => $text, 'source' => 'groq'];
        }

        return $this->handleToolCalls($nextResult['data'], $messages, $depth + 1);
    }

    // ════════════════════════════════════
    //  MESSAGE BUILDER
    // ════════════════════════════════════

    protected function buildMessages(string $message, array $history): array
    {
        $messages = [['role' => 'system', 'content' => $this->buildSystemPrompt()]];
        foreach ($history as $h) {
            if (isset($h['role']) && isset($h['content'])) {
                $messages[] = ['role' => $h['role'], 'content' => $h['content']];
            }
        }
        $messages[] = ['role' => 'user', 'content' => $message];
        return $messages;
    }

    protected function buildSystemPrompt(): string
    {
        $now = now()->format('d/m/Y');
        $userName = '';
        try { $userName = \App\Models\User::find($this->userId)?->name ?? ''; } catch (\Throwable $e) {}

        if ($this->role === 'tenant') {
            return <<<PROMPT
Tu es un assistant immobilier amical et professionnel pour le portail locataire d'ImmoSaas.
Aujourd'hui : $now.

Tu aides le locataire à :
- Consulter son contrat et ses quittances de loyer
- Créer et suivre des demandes d'intervention
- Poser des questions sur son logement
- Comprendre les documents mis à disposition

Pour le portail locataire, tu peux uniquement :
- `get_incidents` — lister les incidents/demandes
- `get_contracts` — infos sur le contrat  
- `get_finances` — quittances et paiements
- `search_documents` — chercher dans les documents
- `create_incident` (à faire par le locataire lui-même via le formulaire dédié)

Réponds en français, avec des emojis pour la lisibilité. Sois concis (3-4 phrases max) et oriente vers la section appropriée du portail si nécessaire. Si le locataire veut créer une demande, redirige-le vers "Mes demandes > Nouvelle demande".
PROMPT;
        }

        return <<<PROMPT
Tu es un assistant IA expert en gestion immobilière pour ImmoSaas, conçu pour aider les propriétaires bailleurs.
Aujourd'hui : $now.
Propriétaire : $userName.

Tu disposes d'outils puissants pour :
1. **Consulter** : biens, locataires, contrats, finances, visites, incidents, alertes, documents
2. **Agir** : créer un bien, modifier le statut d'un incident (+ commentaire), marquer une quittance payée
3. **Analyser** : générer des rapports complets avec tendances et recommandations
4. **Chercher** : recherche intelligente dans tous les éléments

RÈGLES IMPORTANTES :
- Tu peux enchaîner plusieurs actions automatiquement. Par exemple : "marque l'incident 3 comme résolu ET ajoute un commentaire, puis fais un rapport"
- Quand on te demande une action (créer, modifier, marquer), EXÉCUTE-LA directement sans demander de confirmation sauf si ambigu
- Pour les impayés, propose activement d'envoyer une relance ou de marquer la quittance comme payée
- Si l'utilisateur demande "qu'est-ce qui nécessite mon attention", exécute `get_alerts` et propose des actions
- Pour les rapports, exécute `generate_report` avec le focus approprié

Réponds en français, de façon chaleureuse et professionnelle. Utilise des emojis 🏠👥💰📅🔧📊 pour structurer.
Sois précis, cite les montants, dates, et IDs pertinents.
Quand une action est effectuée, confirme-la clairement.
PROMPT;
    }

    // ════════════════════════════════════
    //  FALLBACK LOCAL
    // ════════════════════════════════════

    protected function localFallback(string $message): array
    {
        $normalized = mb_strtolower(trim($message));

        $intents = [
            'overview|résumé|resume|aperçu|apercu|dashboard|situation' => 'overview',
            'bien|propriété|propriete|appartement|maison|logement|immobilier' => 'properties',
            'locataire|tenants|habitants' => 'tenants',
            'contrat|bail|engagement' => 'contracts',
            'finance|revenu|chiffre|argent|impayé|impaye|quittance|loyer|recette|paiement' => 'finances',
            'visite|rendez-vous|rdv|visitor|visiteur' => 'visits',
            'incident|intervention|panne|urgence|sinistre|ticket|demande|problème' => 'incidents',
            'alerte|urgent|important|relance|rappel|attention' => 'alerts',
            'description|annonce|publier|publie|rédiger|rediger|générer|generer' => 'generate_desc',
            'rapport|report|analyse|analytique|tendance|recommandation' => 'report',
        ];

        $matchedIntent = null;
        foreach ($intents as $pattern => $intent) {
            if (preg_match("/\b($pattern)\b/iu", $normalized)) {
                $matchedIntent = $intent;
                break;
            }
        }

        // Special: search_documents
        if (preg_match('/(?:cherche|trouve|recherche|document|doc)\s+(.+)/iu', $message, $m) && stripos($message, 'document') !== false) {
            $result = $this->tools->execute('search_documents', ['query' => trim($m[1])]);
            if ($result['success']) {
                return ['text' => $this->formatSearchDocs($result['data']), 'source' => 'local'];
            }
        }

        if ($matchedIntent === 'report') {
            $focus = 'global';
            if (preg_match('/\b(?:finance|financier|argent|revenu)\b/iu', $message)) $focus = 'finances';
            $result = $this->tools->execute('generate_report', ['focus' => $focus]);
            return ['text' => $result['data']['report'] ?? $result['error'] ?? 'Rapport indisponible.', 'source' => 'local'];
        }

        if ($matchedIntent === 'generate_desc') {
            preg_match('/\b(?:du|de la|pour|bien)\s+(\d+)\b/iu', $message, $m);
            $propertyId = isset($m[1]) ? (int)$m[1] : null;
            return $this->generateDescription($propertyId);
        }

        if ($matchedIntent && $matchedIntent !== 'overview') {
            $result = $this->tools->execute("get_{$matchedIntent}");
            if ($result['success']) {
                return ['text' => $this->formatData($matchedIntent, $result['data']), 'source' => 'local'];
            }
        }

        if (!$matchedIntent || $matchedIntent === 'overview') {
            $result = $this->tools->execute('get_all_overview');
            if ($result['success']) {
                return ['text' => $this->formatOverview($result['data']), 'source' => 'local'];
            }
        }

        return [
            'text' => "Je suis votre assistant ImmoSaas. Je peux vous renseigner sur :\n\n"
                . "🏠 **Biens** — liste, statut, occupation\n"
                . "👥 **Locataires** — infos, contrats\n"
                . "📄 **Contrats** — actifs, échéances\n"
                . "💰 **Finances** — revenus, impayés\n"
                . "📅 **Visites** — planning\n"
                . "🔧 **Incidents** — demandes en cours\n"
                . "📊 **Rapports** — analyses et tendances\n"
                . "⚠️ **Alertes** — impayés, urgences\n"
                . "📄 **Documents** — recherche\n\n"
                . "Que souhaitez-vous savoir ?",
            'source' => 'local',
        ];
    }

    protected function generateDescription(?int $propertyId): array
    {
        if (!$propertyId) {
            return ['text' => 'Pour générer une description, précisez l\'ID du bien (ex: "génère une annonce pour le bien 3").', 'source' => 'local'];
        }
        $result = $this->tools->execute('generate_description', ['property_id' => $propertyId]);
        if ($result['success']) {
            return ['text' => $result['data']['description'], 'source' => 'local'];
        }
        return ['text' => "Bien #$propertyId introuvable.", 'source' => 'local'];
    }

    // ════════════════════════════════════
    //  FORMATTERS
    // ════════════════════════════════════

    protected function formatData(string $type, array $data): string
    {
        return match ($type) {
            'properties' => $this->formatProperties($data),
            'tenants' => $this->formatTenants($data),
            'contracts' => $this->formatContracts($data),
            'finances' => $this->formatFinances($data),
            'visits' => $this->formatVisits($data),
            'incidents' => $this->formatIncidents($data),
            'alerts' => $this->formatAlerts($data),
            default => json_encode($data),
        };
    }

    protected function formatProperties(array $d): string
    {
        $text = "🏠 **Vos biens** ({$d['total']})\n";
        $text .= "• Loués : {$d['rented']} | Libres : {$d['available']}\n\n";
        foreach ($d['list'] as $p) {
            $text .= "• **{$p['title']}** — {$p['city']} ({$p['type']})\n";
            $text .= "  Loyer {$p['rent']} €/mois — {$p['status']}\n";
        }
        return $text;
    }

    protected function formatTenants(array $d): string
    {
        $text = "👥 **Locataires** ({$d['total']})\n";
        foreach ($d['list'] as $t) {
            $text .= "• **{$t['name']}** — {$t['property']} ({$t['contract_status']})\n";
            if ($t['phone']) $text .= "  📞 {$t['phone']} | ✉️ {$t['email']}\n";
        }
        return $text;
    }

    protected function formatContracts(array $d): string
    {
        $text = "📄 **Contrats** — {$d['active']} actifs / {$d['total']} total\n";
        $text .= "💰 Revenus mensuels : " . number_format($d['monthly_revenue'], 0, ',', ' ') . " €\n";
        if ($d['ending_soon'] > 0) $text .= "⚠️ {$d['ending_soon']} contrat(s) se terminent bientôt\n\n";
        foreach ($d['list'] as $c) {
            $text .= "• **{$c['property']}** → {$c['tenant']} : {$c['total']} €/mois ({$c['status']})\n";
        }
        return $text;
    }

    protected function formatFinances(array $d): string
    {
        $text = "💰 **Finances**\n";
        $text .= "• Payé ce mois : " . number_format($d['monthly_paid'], 0, ',', ' ') . " €\n";
        $text .= "• Payé cette année : " . number_format($d['yearly_paid'], 0, ',', ' ') . " €\n";
        $text .= "• En attente : {$d['pending_count']} (" . number_format($d['pending_total'], 0, ',', ' ') . " €)\n";
        $text .= "• En retard : {$d['overdue_count']} (" . number_format($d['overdue_total'], 0, ',', ' ') . " €)\n";
        if (!empty($d['overdue_list'])) {
            $text .= "\n🔴 **Impayés :**\n";
            foreach ($d['overdue_list'] as $r) {
                $text .= "• {$r['reference']} — {$r['period']} — {$r['property']} — " . number_format($r['total'], 0, ',', ' ') . " €\n";
            }
        }
        return $text;
    }

    protected function formatVisits(array $d): string
    {
        $text = "📅 **Visites**\n";
        $text .= "• À venir : {$d['upcoming']} | Effectuées : {$d['completed']} | Annulées : {$d['cancelled']}\n\n";
        foreach ($d['upcoming_list'] as $v) {
            $text .= "• **{$v['date']}** à {$v['time']} — {$v['property']} — {$v['visitor']}\n";
        }
        return $text ?: "Aucune visite à venir.";
    }

    protected function formatIncidents(array $d): string
    {
        $text = "🔧 **Incidents**\n";
        $text .= "• En cours : {$d['open']} | Résolus : {$d['resolved']}\n";
        if ($d['urgent'] > 0) $text .= "🚨 {$d['urgent']} urgence(s) !\n\n";
        foreach ($d['open_list'] as $i) {
            $text .= "• [{$i['urgency']}] **{$i['title']}** — {$i['property']} ({$i['status']})\n";
        }
        return $text ?: "Aucun incident en cours.";
    }

    protected function formatAlerts(array $d): string
    {
        if ($d['count'] === 0) return "✅ Tout va bien, aucune alerte.";
        $text = "⚠️ **Alertes** ({$d['count']})\n\n";
        foreach ($d['alerts'] as $a) {
            $icon = $a['severity'] === 'urgent' ? '🔴' : ($a['severity'] === 'high' ? '🟠' : ($a['severity'] === 'medium' ? '🟡' : '🔵'));
            $text .= "$icon {$a['message']}\n";
        }
        return $text;
    }

    protected function formatSearchDocs(array $d): string
    {
        if ($d['count'] === 0) return "📄 Aucun document trouvé.";
        $text = "📄 **Documents trouvés** ({$d['count']})\n\n";
        foreach ($d['results'] as $doc) {
            $text .= "• **{$doc['name']}** ({$doc['category']})\n";
            if ($doc['expires_at']) $text .= "  Expire le : {$doc['expires_at']}\n";
        }
        return $text;
    }

    protected function formatOverview(array $d): string
    {
        $p = $d['properties'];
        $t = $d['tenants'];
        $c = $d['contracts'];
        $f = $d['finances'];
        $v = $d['visits'];
        $i = $d['incidents'];
        $a = $d['alerts'];

        return "📊 **Tableau de bord — Résumé**\n\n"
            . "🏠 **Biens** : {$p['total']} ({$p['rented']} loués, {$p['available']} libres)\n"
            . "👥 **Locataires** : {$t['active']} actifs sur {$t['total']}\n"
            . "📄 **Contrats actifs** : {$c['active']} — Revenu mensuel : " . number_format($c['monthly_revenue'], 0, ',', ' ') . " €\n"
            . "💰 **Finances** : " . number_format($f['monthly_paid'], 0, ',', ' ') . " € payés ce mois"
            . ($f['overdue_count'] > 0 ? " | ⚠️ {$f['overdue_count']} impayé(s)" : "")
            . "\n"
            . "📅 **Visites** : {$v['upcoming']} à venir\n"
            . "🔧 **Incidents** : {$i['open']} en cours" . ($i['urgent'] > 0 ? " 🚨 {$i['urgent']} urgence(s)" : "")
            . ($a['count'] > 0 ? "\n⚠️ **{$a['count']} alerte(s)**" : "\n✅ Aucune alerte");
    }

    public function getToolDefinitions(): array
    {
        return $this->tools->listTools();
    }
}
