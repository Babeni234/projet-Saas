<?php

namespace App\Services\AiAgent\Tools;

class ExecuteAction extends BaseTool
{
    private const ACTIONS = [
        'navigate_overview' => ['label' => 'Accueil', 'desc' => 'Afficher la vue d\'ensemble du tableau de bord'],
        'navigate_contract' => ['label' => 'Contrat', 'desc' => 'Afficher le contrat de bail et les documents'],
        'navigate_loyer' => ['label' => 'Loyer', 'desc' => 'Aller dans la section loyer et finances'],
        'navigate_payment' => ['label' => 'Paiement', 'desc' => 'Ouvrir la modale de paiement du loyer'],
        'navigate_support' => ['label' => 'Support', 'desc' => 'Afficher le support et les tickets'],
        'navigate_profile' => ['label' => 'Profil', 'desc' => 'Afficher le profil utilisateur'],
        'navigate_utilities' => ['label' => 'Factures', 'desc' => 'Afficher les factures d\'eau et d\'électricité'],
        'navigate_receipts' => ['label' => 'Quittances', 'desc' => 'Afficher les quittances de loyer'],
        'navigate_recharge' => ['label' => 'Recharge', 'desc' => 'Ouvrir la modale de recharge du portefeuille'],
        'navigate_notifications' => ['label' => 'Notifications', 'desc' => 'Afficher les notifications'],
        'toggle_theme' => ['label' => 'Thème', 'desc' => 'Basculer entre le mode sombre et le mode clair'],
        'pay_selected_rent' => ['label' => 'Payer loyer', 'desc' => 'Ouvrir la confirmation de paiement du loyer sélectionné'],
        'process_rent_payment' => ['label' => 'Paiement automatique', 'desc' => 'Payer automatiquement les loyers impayés (1 mois par défaut, ou spécifier le nombre de mois)'],
        'process_utility_payment' => ['label' => 'Paiement factures', 'desc' => 'Payer automatiquement les factures d\'eau et/ou d\'électricité en attente'],
        'open_recharge' => ['label' => 'Recharger', 'desc' => 'Ouvrir la modale de recharge du portefeuille'],
        'open_transfer' => ['label' => 'Transfert', 'desc' => 'Afficher le formulaire de transfert de fonds'],
        'open_new_ticket' => ['label' => 'Ticket', 'desc' => 'Ouvrir un nouveau ticket de support'],
        'open_receipts' => ['label' => 'Reçus', 'desc' => 'Afficher l\'aperçu des reçus'],
        'open_contract_fee' => ['label' => 'Frais contrat', 'desc' => 'Ouvrir le paiement des frais de contrat'],
    ];

    public function name(): string
    {
        return 'execute_action';
    }

    public function description(): string
    {
        return 'Exécute immédiatement une action dans le tableau de bord (navigation, ouverture de modale, changement de thème, paiement). Appelle cette fonction quand l\'utilisateur demande une action à effectuer.';
    }

    public function parameters(): array
    {
        $actions = [];
        foreach (self::ACTIONS as $key => $info) {
            $actions[$key] = $info['label'] . ' — ' . $info['desc'];
        }
        $actionsList = implode("\n", array_map(fn($k, $v) => "- {$k} : {$v}", array_keys($actions), $actions));

        return [
            'type' => 'object',
            'properties' => [
                'action' => [
                    'type' => 'string',
                    'description' => "L'action à exécuter. Actions disponibles :\n{$actionsList}",
                ],
                'params' => [
                    'type' => 'object',
                    'description' => 'Paramètres optionnels. Pour process_rent_payment: { "months_count": 1 }. Pour process_utility_payment: { "utility_type": "all"|"water"|"electric", "months_count": 1 }',
                    'properties' => [
                        'months_count' => [
                            'type' => 'integer',
                            'description' => 'Nombre de mois à payer (1 par défaut, 999 = tous les impayés)',
                        ],
                        'utility_type' => [
                            'type' => 'string',
                            'enum' => ['all', 'water', 'electric'],
                            'description' => 'Type de facture à payer: "all" (toutes), "water" (eau), "electric" (électricité)',
                        ],
                    ],
                ],
            ],
            'required' => ['action'],
        ];
    }

    public function execute(array $params = []): array
    {
        $action = $params['action'] ?? '';

        if (!isset(self::ACTIONS[$action])) {
            return [
                'success' => false,
                'error' => "Action '{$action}' inconnue. Actions disponibles : " . implode(', ', array_keys(self::ACTIONS)),
            ];
        }

        $info = self::ACTIONS[$action];

        $frontendAction = ['action' => $action, 'label' => $info['label']];

        if ($action === 'process_rent_payment') {
            $monthsCount = $params['params']['months_count'] ?? 1;
            $frontendAction['months_count'] = $monthsCount;
        }

        if ($action === 'process_utility_payment') {
            $utilityType = $params['params']['utility_type'] ?? 'all';
            $frontendAction['utility_type'] = $utilityType;
            $monthsCount = $params['params']['months_count'] ?? null;
            if ($monthsCount) $frontendAction['months_count'] = (int)$monthsCount;
        }

        return [
            'success' => true,
            'data' => [
                'action' => $action,
                'label' => $info['label'],
                'description' => $info['desc'],
            ],
            'frontend_actions' => [$frontendAction],
        ];
    }

    public static function getAllActions(): array
    {
        return self::ACTIONS;
    }
}
