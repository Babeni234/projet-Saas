<?php

namespace App\Services;

use App\Models\AgentConversation;
use App\Models\User;
use App\Services\AiAgent\ToolRegistry;
use Illuminate\Support\Facades\Log;

class AiAgentService
{
    private User $user;
    private array $dashboardData;
    private ToolRegistry $registry;

    public function __construct(User $user, array $dashboardData = [])
    {
        $this->user = $user;
        $this->dashboardData = $dashboardData;
        $this->registry = new ToolRegistry($user, $dashboardData);
    }

    public function processMessage(string $message, ?int $conversationId = null): array
    {
        $conversation = $this->getOrCreateConversation($conversationId);
        $this->addMessage($conversation, 'user', $message);

        $allActions = [];
        $autoExecute = false;

        $response = $this->callLlm($conversation);

        if ($response['type'] === 'function_call') {
            foreach ($response['tool_calls'] as $call) {
                $result = $this->registry->execute($call['name'], $call['arguments']);
                $this->addToolResult($conversation, $call['name'], $result);

                // Collecte les frontend_actions depuis le résultat du tool
                if (!empty($result['frontend_actions'])) {
                    $allActions = array_merge($allActions, $result['frontend_actions']);
                    $autoExecute = true;
                }

                if (!$result['success']) {
                    $this->addMessage($conversation, 'assistant', "Erreur lors de l'exécution de '{$call['name']}': {$result['error']}");
                    return $this->buildResponse($conversation, $allActions, 'text', $autoExecute);
                }
            }

            $followUp = $this->callLlm($conversation);
            $followActions = $followUp['frontend_actions'] ?? [];
            if (!empty($followActions)) {
                $allActions = array_merge($allActions, $followActions);
                $autoExecute = true;
            }

            return $this->buildResponse($conversation, $allActions, 'action', $autoExecute);
        }

        $type = $response['type'] ?? 'text';
        $autoExecute = $response['auto_execute'] ?? false;
        $actions = $response['frontend_actions'] ?? [];

        return $this->buildResponse($conversation, $actions, $type, $autoExecute);
    }

    private function callLlm(AgentConversation $conversation): array
    {
        $apiKey = config('openai.api_key');

        if ($apiKey) {
            return $this->callOpenAi($conversation);
        }

        return $this->localFallback($conversation);
    }

    private function callOpenAi(AgentConversation $conversation): array
    {
        try {
            $apiKey = config('openai.api_key');
            $baseUrl = config('openai.base_url');
            $model = config('openai.model');

            $systemPrompt = $this->buildSystemPrompt();
            $tools = $this->registry->toOpenAiFunctions();

            $messages = [
                ['role' => 'system', 'content' => $systemPrompt],
            ];

            $history = $conversation->messages;
            foreach ($history as $msg) {
                $role = $msg['role'] ?? 'user';
                if ($role === 'tool') {
                    $messages[] = [
                        'role' => 'tool',
                        'tool_call_id' => $msg['tool_call_id'] ?? 'call_1',
                        'content' => json_encode($msg['result'] ?? []),
                    ];
                } elseif ($role === 'assistant' && isset($msg['tool_calls'])) {
                    $messages[] = [
                        'role' => 'assistant',
                        'content' => $msg['content'] ?? '',
                        'tool_calls' => $msg['tool_calls'],
                    ];
                } else {
                    $messages[] = ['role' => $role === 'user' ? 'user' : 'assistant', 'content' => $msg['content'] ?? ''];
                }
            }

            $client = \OpenAI::factory()
                ->withApiKey($apiKey)
                ->withBaseUri($baseUrl)
                ->make();
            $response = $client->chat()->create([
                'model' => $model,
                'messages' => $messages,
                'tools' => $tools,
                'tool_choice' => 'auto',
                'temperature' => 0.7,
                'max_tokens' => 1000,
            ]);

            $choice = $response->choices[0]->message;
            $toolCalls = $choice->toolCalls ?? [];

            if (!empty($toolCalls)) {
                $tc = [];
                $ft = [];
                foreach ($toolCalls as $call) {
                    $tc[] = [
                        'id' => $call->id,
                        'type' => 'function',
                        'function' => [
                            'name' => $call->function->name,
                            'arguments' => json_decode($call->function->arguments, true) ?? [],
                        ],
                    ];
                    $ft[] = [
                        'name' => $call->function->name,
                        'arguments' => json_decode($call->function->arguments, true) ?? [],
                    ];
                }

                $this->addMessage($conversation, 'assistant', $choice->content ?? '', $tc);

                return [
                    'type' => 'function_call',
                    'tool_calls' => $ft,
                    'frontend_actions' => [],
                ];
            }

            $text = $choice->content ?? 'Je n\'ai pas pu traiter votre demande.';
            $this->addMessage($conversation, 'assistant', $text);

            return [
                'type' => 'text',
                'text' => $text,
                'frontend_actions' => $this->detectFrontendActions($text),
            ];
        } catch (\Throwable $e) {
            Log::error('OpenAI call failed: ' . $e->getMessage());
            return $this->localFallback($conversation);
        }
    }

    private function localFallback(AgentConversation $conversation): array
    {
        $lastUserMsg = '';
        $history = $conversation->messages;
        for ($i = count($history) - 1; $i >= 0; $i--) {
            if (($history[$i]['role'] ?? '') === 'user') {
                $lastUserMsg = $history[$i]['content'] ?? '';
                break;
            }
        }

        $result = $this->smartMatch($lastUserMsg);

        if ($result['type'] === 'function_call') {
            foreach ($result['tool_calls'] as $call) {
                $toolResult = $this->registry->execute($call['name'], $call['arguments'] ?? []);
                $this->addToolResult($conversation, $call['name'], $toolResult);

                if (!$toolResult['success']) continue;

                $this->addMessage($conversation, 'assistant', $this->formatToolResultForHuman($call['name'], $toolResult));
            }

            $lastMsg = $conversation->messages[count($conversation->messages) - 1] ?? [];
            $text = $lastMsg['content'] ?? 'Informations récupérées.';

            return [
                'type' => $result['type'] ?? 'text',
                'text' => $text,
                'frontend_actions' => $result['frontend_actions'] ?? [],
                'auto_execute' => $result['auto_execute'] ?? false,
            ];
        }

        $text = $result['text'] ?? 'Je n\'ai pas compris. Pouvez-vous reformuler ?';
        $this->addMessage($conversation, 'assistant', $text);

        $responseType = $result['type'] ?? 'text';
        if ($responseType === 'function_call') $responseType = 'text';

        return [
            'type' => $responseType,
            'text' => $text,
            'frontend_actions' => $result['frontend_actions'] ?? [],
            'auto_execute' => $result['auto_execute'] ?? false,
        ];
    }

    private function smartMatch(string $input): array
    {
        $n = mb_strtolower(trim($input));

        // ══ ACTIONS EXPLICITES ══

        // Action: Payer les factures d'eau/électricité (doit être AVANT loyer pour éviter conflit avec "mois")
        if (preg_match('/\b(paye?|paie|règle|regle|payer|régler|regler|effectuer|effectue|fais|lance).*(facture|factures|eau|électricité|electricite|elec|consommation|compteur)\b/iu', $n)) {
            $utilityType = 'all';
            if (preg_match('/\b(eau|water)\b/iu', $n)) $utilityType = 'water';
            elseif (preg_match('/\b(électricité|electricite|elec)\b/iu', $n)) $utilityType = 'electric';

            $monthsCount = null;
            if (preg_match('/(\d+)\s*mois\b/iu', $n, $m)) {
                $monthsCount = (int)$m[1];
            } elseif (preg_match('/\b(tout|tous|toutes|intégralité|integralite)\b/iu', $n)) {
                $monthsCount = 999;
            }

            return [
                'type' => 'action',
                'text' => $monthsCount
                    ? "Je lance le paiement de {$monthsCount} mois de factures…"
                    : "Je lance le paiement de vos factures…",
                'frontend_actions' => [[
                    'action' => 'process_utility_payment',
                    'label' => $monthsCount ? "Payer {$monthsCount} mois de factures" : 'Payer les factures',
                    'utility_type' => $utilityType,
                    'months_count' => $monthsCount,
                ]],
                'auto_execute' => true,
            ];
        }

        // Action: Payer le loyer (with month count support)
        if ((preg_match('/(?:paye|paie|règle|regle|payer|régler|regler|effectuer|effectue|fais).*(?:loyer|mois)/iu', $n) &&
             !preg_match('/\b(facture|eau|électricité|electricite|elec|consommation)\b/iu', $n)) ||
            preg_match('/\b(paye.*loyer|paie.*loyer|payer.*loyer|régler.*loyer|regler.*loyer|loyer.*maintenant|effectuer.*paiement|je veux payer|j aimerais payer)\b/iu', $n)) {

            $monthsCount = 1;

            if (preg_match('/(\d+)\s*mois\b/iu', $n, $m)) {
                $monthsCount = (int)$m[1];
            } elseif (preg_match('/\b(tout|tous|toutes|intégralité|integralite|totalité|totalite|complete|complète|rembourse|solde)\b/iu', $n)) {
                $monthsCount = 999;
            }

            return [
                'type' => 'action',
                'text' => $monthsCount > 1
                    ? "Je lance le paiement de vos {$monthsCount} mois de loyer…"
                    : "Je lance le paiement de votre loyer…",
                'frontend_actions' => [[
                    'action' => 'process_rent_payment',
                    'label' => $monthsCount > 1 ? "Payer {$monthsCount} mois" : "Payer le loyer",
                    'months_count' => $monthsCount,
                ]],
                'auto_execute' => true,
            ];
        }

        // Action: Mode sombre / clair
        if (preg_match('/\b(mode sombre|mode clair|thème sombre|theme sombre|thème clair|theme clair|dark mode|light mode|passe.*sombre|passe.*clair|bascule.*sombre|bascule.*clair|active.*sombre|active.*clair|obscurcir|éclairer|eclairer)\b/iu', $n)) {
            $toDark = preg_match('/\b(sombre|dark|obscur)\b/iu', $n);
            return [
                'type' => 'action',
                'text' => $toDark ? 'Basculement en mode sombre…' : 'Basculement en mode clair…',
                'frontend_actions' => [['action' => 'toggle_theme', 'label' => 'Basculer le thème']],
                'auto_execute' => true,
            ];
        }

        // Action: Recharger le wallet
        if (preg_match('/\b(recharge.*wallet|recharge.*portefeuille|recharge.*solde|recharger.*mon|ajouter.*argent|créditer.*wallet|crediter.*wallet|je veux recharger|approvisionner)\b/iu', $n)) {
            return [
                'type' => 'action',
                'text' => "J'ouvre la recharge de votre portefeuille…",
                'frontend_actions' => [['action' => 'navigate_recharge', 'label' => 'Recharger le wallet']],
                'auto_execute' => true,
            ];
        }

        // Action: Navigation explicite
        if (preg_match('/\b(va.*dans|va.*sur|affiche.*page|montre.*section|ouvre.*section|navigue.*vers|redirect.*vers|emmène.*moi)\b/iu', $n)) {
            return $this->handleNavigateRequestAction($n);
        }

        // ══ REQUETES INFORMATIVES (text + buttons) ══

        // Overview / résumé
        if (preg_match('/\b(résumé|resume|aperçu|apercu|vue d ensemble|overview|synthèse|synthese|récap|recap|situation|dashboard)\b/u', $n)) {
            $r = $this->registry->execute('get_overview');
            return $this->toolResultToResponse('get_overview', $r);
        }

        // Paiement loyer
        if (preg_match('/\b(payer|paiement|paye|régler|regler|montant|combien.*dois|impayé|impaye|je dois|facture.*loyer|loyer.*payer|loyer.*en.*retard)\b/u', $n)) {
            $r = $this->registry->execute('get_rent_status');
            return $this->toolResultToResponse('get_rent_status', $r, [
                ['action' => 'navigate_payment', 'label' => 'Payer maintenant'],
            ]);
        }

        // Pénalités
        if (preg_match('/\b(pénalité|penalite|amende|majoration|retard|bareme|taux|sursis)\b/u', $n)) {
            $r = $this->registry->execute('get_penalty_info');
            return $this->toolResultToResponse('get_penalty_info', $r);
        }

        // Contrat / Bail
        if (preg_match('/\b(contrat|bail|propriété|propriete|logement|appartement|location|bien|property)\b/u', $n)) {
            $r = $this->registry->execute('get_contract_info');
            return $this->toolResultToResponse('get_contract_info', $r, [
                ['action' => 'navigate_contract', 'label' => 'Voir le contrat'],
            ]);
        }

        // Portefeuille / Solde
        if (preg_match('/\b(portefeuille|solde|wallet|recharge|recharger|argent|balance|monnaie|crédit|credit)\b/u', $n)) {
            $r = $this->registry->execute('get_wallet_balance');
            return $this->toolResultToResponse('get_wallet_balance', $r, [
                ['action' => 'navigate_recharge', 'label' => 'Recharger'],
            ]);
        }

        // Action: Payer les factures d'eau/électricité
        if (preg_match('/\b(paye?|paie|règle|regle|payer|régler|regler|effectuer|effectue|fais|lance).*(facture|factures|eau|électricité|electricite|elec|consommation|compteur)\b/iu', $n)) {
            $type = 'all';
            if (preg_match('/\b(eau|water)\b/iu', $n)) $type = 'water';
            elseif (preg_match('/\b(électricité|electricite|elec)\b/iu', $n)) $type = 'electric';
            return [
                'type' => 'action',
                'text' => "Je lance le paiement de vos factures…",
                'frontend_actions' => [[
                    'action' => 'process_utility_payment',
                    'label' => 'Payer les factures',
                    'utility_type' => $type,
                ]],
                'auto_execute' => true,
            ];
        }

        // Factures (général)
        if (preg_match('/\b(facture|factures|quittance|quittances|reçu|reçus|recu|recus)\b/u', $n)) {
            $type = 'all';
            if (preg_match('/\b(eau|water)\b/u', $n)) $type = 'water';
            elseif (preg_match('/\b(électricité|electricite|elec)\b/u', $n)) $type = 'electric';
            $r = $this->registry->execute('get_invoices', ['type' => $type]);
            return $this->toolResultToResponse('get_invoices', $r);
        }

        // Eau / Électricité
        if (preg_match('/\b(eau|électricité|electricite|elec|consommation|compteur|index)\b/u', $n)) {
            $r = $this->registry->execute('get_utilities');
            return $this->toolResultToResponse('get_utilities', $r, [
                ['action' => 'navigate_utilities', 'label' => 'Voir les factures'],
            ]);
        }

        // Profil
        if (preg_match('/\b(profil|profile|mot de passe|identité|identite|personnel|information|mon nom|mon email)\b/u', $n)) {
            $r = $this->registry->execute('get_profile');
            return $this->toolResultToResponse('get_profile', $r, [
                ['action' => 'navigate_profile', 'label' => 'Modifier mon profil'],
            ]);
        }

        // Navigation (style "montre-moi")
        if (preg_match('/\b(va à|va dans|affiche|montre|montre-moi|montre moi|ouvre|naviguer|section|page|accueil|emmène|va sur)\b/u', $n)) {
            return $this->handleNavigateRequestAction($n);
        }

        // Aide
        if (preg_match('/\b(bonjour|salut|bonsoir|hello|hi|coucou|aide|help|s il vous plait|besoin|merci|bonsoir)\b/u', $n)) {
            return [
                'type' => 'text',
                'text' => $this->getGreeting(),
                'frontend_actions' => [],
            ];
        }

        // Commandes explicites
        if (preg_match('/\b(exécute|execute|fais|fait|effectue|lance|applique|paie|paye)\b/u', $n)) {
            if (preg_match('/\b(paye?|paiement|règle|regle|payer)\b/u', $n)) {
                $monthsCount = 1;
                if (preg_match('/(\d+)\s*mois\b/iu', $n, $m)) {
                    $monthsCount = (int)$m[1];
                } elseif (preg_match('/\b(tout|tous|toutes|intégralité|integralite|solde)\b/iu', $n)) {
                    $monthsCount = 999;
                }
                return [
                    'type' => 'action',
                    'text' => $monthsCount > 1 ? "Je lance le paiement de {$monthsCount} mois…" : 'Je lance le paiement…',
                    'frontend_actions' => [[
                        'action' => 'process_rent_payment',
                        'label' => $monthsCount > 1 ? "Payer {$monthsCount} mois" : 'Payer le loyer',
                        'months_count' => $monthsCount,
                    ]],
                    'auto_execute' => true,
                ];
            }
        }

        return [
            'type' => 'text',
            'text' => "Je n'ai pas bien compris. Voici ce que je peux faire :\n- Consulter votre solde et loyer\n- Vérifier les pénalités\n- Afficher votre contrat\n- Payer votre loyer\n- Voir vos factures d'eau/électricité\n- Naviguer dans le tableau de bord\n\nQue souhaitez-vous ?",
            'frontend_actions' => [],
        ];
    }

    private function handleNavigateRequestAction(string $n): array
    {
        $map = [
            'overview' => ['accueil', 'tableau de bord', 'overview', 'résumé', 'resume', 'aperçu', 'apercu', 'dashboard'],
            'contract' => ['contrat', 'bail', 'propriété', 'propriete'],
            'loyer' => ['loyer', 'paiement', 'payer', 'finances', 'facture'],
            'support' => ['support', 'ticket', 'message', 'messagerie', 'assistance', 'chat'],
            'profile' => ['profil', 'paramètre', 'parametre', 'compte', 'settings'],
            'utilities' => ['facture eau', 'eau', 'électricité', 'electricite', 'utilité', 'utilite'],
            'receipts' => ['quittance', 'reçu', 'recu', 'quittances'],
        ];

        foreach ($map as $tab => $keys) {
            if (preg_match('/\b(' . implode('|', $keys) . ')\b/u', $n)) {
                return [
                    'type' => 'action',
                    'text' => "Je vous redirige vers la section « {$tab} »…",
                    'frontend_actions' => [
                        ['action' => 'navigate_' . $tab, 'label' => $tab],
                    ],
                    'auto_execute' => true,
                ];
            }
        }

        return [
            'type' => 'text',
            'text' => 'Sections disponibles : Accueil, Contrat, Loyer, Support, Profil, Factures, Quittances.',
            'frontend_actions' => [],
        ];
    }

    private function toolResultToResponse(string $toolName, array $result, array $extraActions = []): array
    {
        $text = $this->formatToolResultForHuman($toolName, $result);
        $actions = [];

        if ($extraActions) {
            foreach ($extraActions as $a) {
                $actions[] = $a;
            }
        }

        return [
            'type' => 'text',
            'text' => $text,
            'frontend_actions' => $actions,
        ];
    }

    private function formatToolResultForHuman(string $toolName, array $result): string
    {
        if (!$result['success']) return "Erreur : {$result['error']}";
        $d = $result['data'] ?? [];

        return match ($toolName) {
            'get_overview' => sprintf(
                "📊 **Résumé du tableau de bord**\n- Bien : %s\n- Loyer mensuel : %s XAF\n- Solde portefeuille : %s XAF\n- Impayés : %d mois\n- Pénalité : %s\n- Total dû : %s XAF",
                $d['property_name'] ?? 'N/A',
                number_format($d['monthly_rent'] ?? 0, 0, ',', ' '),
                number_format($d['wallet_balance'] ?? 0, 0, ',', ' '),
                $d['unpaid_months'] ?? 0,
                $d['penalty_label'] ?? 'Aucune',
                number_format($d['total_due'] ?? 0, 0, ',', ' ')
            ),
            'get_rent_status' => $this->formatRentStatus($d),
            'get_contract_info' => sprintf(
                "📋 **Contrat : %s**\n- Loyer : %s XAF/mois\n- Caution : %s XAF\n- Charges : %s XAF\n- Début : %s\n- Fin : %s\n- Propriétaire : %s",
                $d['property_name'] ?? 'N/A',
                number_format($d['rent'] ?? 0, 0, ',', ' '),
                number_format($d['deposit'] ?? 0, 0, ',', ' '),
                number_format($d['charges'] ?? 0, 0, ',', ' '),
                $d['start_date'] ?? 'N/A',
                $d['end_date'] ?? 'N/A',
                $d['owner_name'] ?? 'N/A'
            ),
            'get_wallet_balance' => sprintf(
                "💰 **Portefeuille**\nSolde actuel : **%s XAF**",
                number_format($d['balance'] ?? 0, 0, ',', ' ')
            ),
            'get_invoices' => $this->formatInvoices($d),
            'get_penalty_info' => $this->formatPenalties($d),
            'get_utilities' => $this->formatUtilities($d),
            'get_profile' => sprintf(
                "👤 **Profil**\n- Nom : %s\n- Email : %s\n- Téléphone : %s",
                $d['name'] ?? 'N/A',
                $d['email'] ?? 'N/A',
                $d['phone'] ?? 'N/A'
            ),
            default => 'Informations récupérées.',
        };
    }

    private function formatRentStatus(array $d): string
    {
        $text = "💳 **Situation des loyers**\n";
        if ($d['pending_count'] > 0) {
            $text .= "- {$d['pending_count']} mois en attente\n";
            foreach ($d['pending_invoices'] as $inv) {
                $text .= "  • {$inv['period']} : " . number_format($inv['amount'], 0, ',', ' ') . " XAF\n";
            }
        } else {
            $text .= "- Tous les loyers sont à jour ✓\n";
        }
        if ($d['is_overdue']) {
            $text .= "- **{$d['unpaid_months']} mois impayés**\n";
            $text .= "- Pénalité : {$d['penalty_rate']}\n";
            $text .= "- Total pénalités : " . number_format($d['total_penalties'], 0, ',', ' ') . " XAF\n";
            $text .= "- **Total dû : " . number_format($d['total_due'], 0, ',', ' ') . " XAF**\n";
        } else {
            $text .= "- Pas de pénalité en cours ✓\n";
        }
        if ($d['last_paid']) {
            $text .= "- Dernier paiement : {$d['last_paid']['period']} (" . number_format($d['last_paid']['amount'], 0, ',', ' ') . " XAF)";
        }
        return $text;
    }

    private function formatInvoices(array $d): string
    {
        if (empty($d)) return 'Aucune facture trouvée.';
        $text = "📄 **Factures**\n";
        foreach ($d as $inv) {
            $status = match ($inv['status']) { 'paid' => '✓ Payée', 'pending' => '⏳ En attente', default => $inv['status'] };
            $text .= "- {$inv['period']} | {$inv['type']} | " . number_format($inv['amount'], 0, ',', ' ') . " XAF | {$status}\n";
        }
        return $text;
    }

    private function formatPenalties(array $d): string
    {
        if (!$d['is_overdue']) {
            return "✅ **Aucune pénalité en cours.**\n\nBarème : 5% du 11 au 15, 10% du 16 au 20, 15% à partir du 21.";
        }
        $text = "⚠️ **Pénalités**\n- {$d['unpaid_count']} mois impayés\n- Taux actuel : {$d['current_rate_label']}\n- Total pénalités : " . number_format($d['total_penalties'], 0, ',', ' ') . " XAF\n- **Total dû : " . number_format($d['total_due'], 0, ',', ' ') . " XAF**\n";
        if (!empty($d['details'])) {
            $text .= "\nDétail :\n";
            foreach ($d['details'] as $det) {
                $text .= "- {$det['period']} : " . number_format($det['penalty'] ?? 0, 0, ',', ' ') . " XAF\n";
            }
        }
        return $text;
    }

    private function formatUtilities(array $d): string
    {
        $text = "⚡ **Eau & Électricité**\n";
        if (isset($d['water'])) {
            $w = $d['water'];
            $text .= "💧 Eau : {$w['consumption']} {$w['unit']} - " . number_format($w['cost'], 0, ',', ' ') . " XAF ({$w['status']})\n";
        }
        if (isset($d['electricity'])) {
            $e = $d['electricity'];
            $text .= "⚡ Électricité : {$e['consumption']} {$e['unit']} - " . number_format($e['cost'], 0, ',', ' ') . " XAF ({$e['status']})\n";
        }
        if (!isset($d['water']) && !isset($d['electricity'])) {
            $text .= "Aucune donnée de consommation disponible.\n";
        }
        return $text;
    }

    private function detectFrontendActions(string $text): array
    {
        $actions = [];
        if (preg_match('/\b(payer|paiement|paye|règle|regle)\b/iu', $text)) {
            $actions[] = ['action' => 'navigate_payment', 'label' => 'Payer'];
        }
        if (preg_match('/\b(recharge|recharger|solde)\b/iu', $text)) {
            $actions[] = ['action' => 'navigate_recharge', 'label' => 'Recharger'];
        }
        if (preg_match('/\b(contrat|bail)\b/iu', $text)) {
            $actions[] = ['action' => 'navigate_contract', 'label' => 'Voir le contrat'];
        }
        if (preg_match('/\b(facture.*eau|facture.*élec|utilité|utilite)\b/iu', $text)) {
            $actions[] = ['action' => 'navigate_utilities', 'label' => 'Voir factures'];
        }
        if (preg_match('/\b(support|ticket|message)\b/iu', $text)) {
            $actions[] = ['action' => 'navigate_support', 'label' => 'Support'];
        }
        return $actions;
    }

    private function getGreeting(): string
    {
        $h = (int)now()->format('H');
        $period = $h < 12 ? 'matin' : ($h < 18 ? 'après-midi' : 'soirée');
        $name = $this->user->name;
        $prefix = ($period === 'soirée') ? 'Bon' : 'Bon';
        $greeting = ($period === 'soirée') ? 'soir' : 'jour';

        return "{$prefix}{$greeting} {$name} ! Je suis votre assistant HABITATUM. Comment puis-je vous aider ?\n\nJe peux :\n• Consulter votre solde et situation locative\n• Vérifier les pénalités de retard\n• Afficher votre contrat\n• Vous aider à payer votre loyer\n• Voir vos factures d'eau/électricitén\n• Naviguer dans le tableau de bord";
    }

    private function buildSystemPrompt(): string
    {
        $now = now();
        $actionsList = '';
        foreach (\App\Services\AiAgent\Tools\ExecuteAction::getAllActions() as $key => $info) {
            $actionsList .= "- `{$key}` : {$info['desc']}\n";
        }

        return <<<PROMPT
Tu es **HABITATUM**, un agent IA premium intégré au tableau de bord locataire. Tu contrôles TOUT le dashboard.

## 🧠 Capacités
- **Consulter** n'importe quelle information en temps réel via les outils de données (get_overview, get_rent_status, get_contract_info, get_wallet_balance, get_invoices, get_penalty_info, get_utilities, get_profile)
- **Exécuter des actions** immédiates dans le dashboard via l'outil `execute_action`
- Quand l'utilisateur te **demande une action**, utilise `execute_action` sans hésiter. Ne te contente PAS de suggérer l'action — **exécute-la**.

## 📋 Actions disponibles (via execute_action)
{$actionsList}
## ⚡ Règles d'exécution des actions
1. Si l'utilisateur dit "paye mon loyer" → appelle `execute_action(action: "process_rent_payment")` avec `params: { months_count: 1 }`
2. Si l'utilisateur dit "paye X mois de loyer" ou "paye pour X mois" → appelle `execute_action(action: "process_rent_payment")` avec `params: { months_count: X }`
3. Si l'utilisateur dit "paye tout" ou "paye tous mes loyers" → appelle `execute_action(action: "process_rent_payment")` avec `params: { months_count: 999 }`
4. Si l'utilisateur dit "paye ma facture d'eau" → appelle `execute_action(action: "process_utility_payment")` avec `params: { utility_type: "water" }`
5. Si l'utilisateur dit "paye ma facture d'électricité" → appelle `execute_action(action: "process_utility_payment")` avec `params: { utility_type: "electric" }`
6. Si l'utilisateur dit "paye mes factures" → appelle `execute_action(action: "process_utility_payment")` avec `params: { utility_type: "all" }`
7. Si l'utilisateur dit "paye X mois de factures" → appelle `execute_action(action: "process_utility_payment")` avec `params: { utility_type: "all", months_count: X }`
8. Si l'utilisateur dit "paye tout" ou "paye toutes mes factures" → appelle `execute_action(action: "process_utility_payment")` avec `params: { utility_type: "all", months_count: 999 }`
9. Si l'utilisateur dit "passe en mode sombre/clair" → appelle `execute_action(action: "toggle_theme")`
10. Si l'utilisateur dit "recharge mon wallet/portefeuille" → appelle `execute_action(action: "navigate_recharge")`
11. Si l'utilisateur dit "va dans/sur [section]" → appelle `execute_action(action: "navigate_[section]")`
12. Si l'utilisateur demande des informations → appelle l'outil de données approprié
13. **Ne renvoie JAMAIS l'utilisateur vers un lien ou une action manuelle** — utilise `execute_action` pour tout faire automatiquement.
14. Tu exécutes TOUJOURS les actions demandées, tu ne les suggères pas.

## 📍 Règles générales
1. Réponds en français, concis et professionnel.
2. Montants en XAF formatés (ex: 50 000 XAF).
3. Tu es proactif : détecte les impayés et propose automatiquement d'aider.
4. Ne communique JAMAIS les mots de passe ou codes PIN.
5. Utilise le prénom de l'utilisateur pour personnaliser.

Date : {$now->format('d/m/Y')}
Utilisateur : {$this->user->name}
PROMPT;
    }

    private function getOrCreateConversation(?int $conversationId): AgentConversation
    {
        if ($conversationId) {
            $conv = AgentConversation::find($conversationId);
            if ($conv && $conv->user_id === $this->user->id) return $conv;
        }

        $conv = AgentConversation::where('user_id', $this->user->id)
            ->latest()
            ->first();

        if (!$conv) {
            $conv = AgentConversation::create([
                'user_id' => $this->user->id,
                'title' => 'Conversation HABITATUM',
                'messages' => [],
                'context' => ['user_agent' => request()->userAgent() ?? 'unknown'],
            ]);
        }

        return $conv;
    }

    private function addMessage(AgentConversation $conv, string $role, string $content, ?array $toolCalls = null): void
    {
        $messages = $conv->messages;
        $msg = ['role' => $role, 'content' => $content, 'timestamp' => now()->toIso8601String()];
        if ($toolCalls) {
            $msg['tool_calls'] = $toolCalls;
        }
        $messages[] = $msg;

        // Keep last 50 messages for context
        if (count($messages) > 50) {
            $messages = array_slice($messages, -50);
        }

        $conv->update(['messages' => $messages]);
    }

    private function addToolResult(AgentConversation $conv, string $toolName, array $result): void
    {
        $messages = $conv->messages;
        $messages[] = [
            'role' => 'tool',
            'tool_call_id' => 'call_' . uniqid(),
            'name' => $toolName,
            'content' => json_encode($result),
            'timestamp' => now()->toIso8601String(),
        ];
        $conv->update(['messages' => $messages]);
    }

    private function buildResponse(AgentConversation $conv, array $frontendActions = [], string $type = 'text', bool $autoExecute = false): array
    {
        $history = $conv->messages;
        $lastMsg = count($history) > 0 ? $history[count($history) - 1] : ['content' => ''];

        return [
            'conversation_id' => $conv->id,
            'type' => $type,
            'text' => $lastMsg['content'] ?? '',
            'frontend_actions' => $frontendActions,
            'auto_execute' => $autoExecute,
        ];
    }
}
