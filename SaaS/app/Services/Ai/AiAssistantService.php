<?php

namespace App\Services\Ai;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiAssistantService
{
    protected int $userId;
    protected string $role;
    protected AiTools $tools;

    public function __construct(int $userId, string $role = 'landlord')
    {
        $this->userId = $userId;
        $this->role = $role;
        $this->tools = new AiTools($userId);
    }

    public function chat(string $message, array $history = []): array
    {
        $apiKey = config('ai.api_key');

        if ($apiKey) {
            return $this->callLlm($message, $history);
        }

        return $this->localFallback($message);
    }

    protected function callLlm(string $message, array $history): array
    {
        $systemPrompt = $this->buildSystemPrompt();

        $messages = [['role' => 'system', 'content' => $systemPrompt]];
        foreach ($history as $h) {
            $messages[] = ['role' => $h['role'], 'content' => $h['content']];
        }
        $messages[] = ['role' => 'user', 'content' => $message];

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
                    'max_tokens' => 1500,
                ]);

            if (!$response->successful()) {
                Log::warning('Groq API error: ' . $response->body());
                return $this->localFallback($message);
            }

            $data = $response->json();
            $choice = $data['choices'][0]['message'] ?? [];
            $toolCalls = $choice['tool_calls'] ?? [];

            if (!empty($toolCalls)) {
                $results = [];
                foreach ($toolCalls as $call) {
                    $name = $call['function']['name'];
                    $args = json_decode($call['function']['arguments'], true) ?? [];
                    $result = $this->tools->execute($name, $args);
                    $results[] = "$name: " . json_encode($result);
                }

                $toolResultStr = implode("\n", $results);
                $messages[] = ['role' => 'assistant', 'content' => $choice['content'] ?? ''];
                $messages[] = ['role' => 'tool', 'content' => $toolResultStr];

                $finalResponse = Http::timeout(config('ai.request_timeout', 30))
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . config('ai.api_key'),
                        'Content-Type' => 'application/json',
                    ])
                    ->post(rtrim(config('ai.base_url'), '/') . '/chat/completions', [
                        'model' => config('ai.model', 'llama-3.1-70b-versatile'),
                        'messages' => $messages,
                        'temperature' => 0.7,
                        'max_tokens' => 1500,
                    ]);

                if ($finalResponse->successful()) {
                    $finalText = $finalResponse->json()['choices'][0]['message']['content'] ?? '';
                    return ['text' => $finalText, 'source' => 'groq'];
                }
            }

            return [
                'text' => $choice['content'] ?? 'Je n\'ai pas pu traiter votre demande.',
                'source' => 'groq',
            ];
        } catch (\Throwable $e) {
            Log::error('AI call failed: ' . $e->getMessage());
            return $this->localFallback($message);
        }
    }

    protected function localFallback(string $message): array
    {
        $normalized = mb_strtolower(trim($message));

        // Intent detection
        $intents = [
            'overview|résumé|resume|aperçu|apercu|dashboard|situation' => 'overview',
            'bien|propriété|propriete|appartement|maison|logement|immobilier' => 'properties',
            'locataire|tenants|habitants' => 'tenants',
            'contrat|bail|engagement' => 'contracts',
            'finance|revenu|chiffre|argent|impayé|impaye|quittance|loyer|recette|paiement' => 'finances',
            'visite|rendez-vous|rdv|visitor|visiteur' => 'visits',
            'incident|intervention|panne|urgence|sinistre|ticket|demande|problème' => 'incidents',
            'alerte|urgent|important|relance|rappel' => 'alerts',
            'description|annonce|publier|publie|rédiger|rediger' => 'generate_desc',
        ];

        $matchedIntent = null;
        foreach ($intents as $pattern => $intent) {
            if (preg_match("/\b($pattern)\b/iu", $normalized)) {
                $matchedIntent = $intent;
                break;
            }
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
                . "⚠️ **Alertes** — impayés, urgences\n\n"
                . "Que souhaitez-vous savoir ?",
            'source' => 'local',
        ];
    }

    protected function generateDescription(?int $propertyId): array
    {
        if (!$propertyId) {
            return ['text' => 'Pour générer une description, précisez l\'ID du bien (ex: "génère une annonce pour le bien 3").', 'source' => 'local'];
        }

        $property = \App\Models\Property::find($propertyId);
        if (!$property || $property->user_id !== $this->userId) {
            return ['text' => "Bien #$propertyId introuvable.", 'source' => 'local'];
        }

        $desc = "**" . $property->title . "** — " . ($property->city ?? 'Ville') . "\n\n";
        $desc .= $property->description ?? "Magnifique " . ($property->type ?? 'bien') . " à louer.\n\n";
        $desc .= "📍 " . ($property->address ?? 'Adresse à préciser') . "\n";
        if ($property->surface) $desc .= "📐 {$property->surface} m²\n";
        if ($property->rooms) $desc .= "🛏 {$property->rooms} pièce(s)\n";
        if ($property->rent_amount) $desc .= "💰 {$property->rent_amount} € / mois\n";
        if ($property->charges) $desc .= "🔹 Charges : {$property->charges} €\n";
        $desc .= "\n📞 Contactez-nous pour une visite !";

        return ['text' => $desc, 'source' => 'local'];
    }

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
            $text .= "  {$p['rent']} €/mois — {$p['status']}\n";
        }
        return $text;
    }

    protected function formatTenants(array $d): string
    {
        $text = "👥 **Locataires** ({$d['total']})\n";
        foreach ($d['list'] as $t) {
            $text .= "• **{$t['name']}** — {$t['property']} ({$t['contract_status']})\n";
        }
        return $text;
    }

    protected function formatContracts(array $d): string
    {
        $text = "📄 **Contrats** — {$d['active']} actifs / {$d['total']} total\n";
        $text .= "💰 Revenus mensuels : " . number_format($d['monthly_revenue'], 0, ',', ' ') . " €\n";
        if ($d['ending_soon'] > 0) $text .= "⚠️ {$d['ending_soon']} contrat(s) se terminent bientôt\n\n";
        foreach ($d['list'] as $c) {
            $text .= "• {$c['property']} → {$c['tenant']} : {$c['total']} €/mois ({$c['status']})\n";
        }
        return $text;
    }

    protected function formatFinances(array $d): string
    {
        $text = "💰 **Finances**\n";
        $text .= "• Payé ce mois : " . number_format($d['monthly_paid'], 0, ',', ' ') . " €\n";
        $text .= "• En attente : {$d['pending_count']} (" . number_format($d['pending_total'], 0, ',', ' ') . " €)\n";
        $text .= "• En retard : {$d['overdue_count']} (" . number_format($d['overdue_total'], 0, ',', ' ') . " €)\n";
        if (!empty($d['overdue_list'])) {
            $text .= "\nImpayés :\n";
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
            $text .= "• {$v['date']} à {$v['time']} — {$v['property']} — {$v['visitor']}\n";
        }
        return $text ?: "Aucune visite à venir.";
    }

    protected function formatIncidents(array $d): string
    {
        $text = "🔧 **Incidents**\n";
        $text .= "• En cours : {$d['open']} | Résolus : {$d['resolved']}\n";
        if ($d['urgent'] > 0) $text .= "🚨 {$d['urgent']} urgence(s) !\n\n";
        foreach ($d['open_list'] as $i) {
            $text .= "• [{$i['urgency']}] {$i['title']} — {$i['property']} ({$i['status']})\n";
        }
        return $text ?: "Aucun incident en cours.";
    }

    protected function formatAlerts(array $d): string
    {
        if ($d['alert_count'] === 0) return "✅ Tout va bien, aucune alerte.";
        $text = "⚠️ **Alertes** ({$d['alert_count']})\n";
        foreach ($d['alerts'] as $a) {
            $text .= "• $a\n";
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
            . ($a['alert_count'] > 0 ? "\n⚠️ **{$a['alert_count']} alerte(s)**" : "\n✅ Aucune alerte");
    }

    public function getToolDefinitions(): array
    {
        return $this->tools->listTools();
    }
}
