<?php

namespace Database\Seeders;

use App\Models\WorkflowTemplate;
use Illuminate\Database\Seeder;

class WorkflowTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Relance impayés automatique',
                'description' => 'Détecte les quittances en retard, attend 3 jours, puis notifie le locataire et crée une tâche de suivi',
                'category' => 'financial',
                'is_built_in' => true,
                'config' => [
                    'trigger_type' => 'receipt_overdue',
                    'trigger_config' => [],
                    'steps' => [
                        ['name' => 'Vérifier impayé', 'description' => 'Identifier la quittance en retard', 'step_type' => 'condition', 'config' => ['field' => 'payment_status', 'operator' => 'equals', 'value' => 'overdue'], 'order' => 1],
                        ['name' => 'Attendre 3 jours', 'description' => 'Période de grâce avant relance', 'step_type' => 'delay', 'config' => ['days' => 3], 'order' => 2],
                        ['name' => 'Notifier le locataire', 'description' => 'Envoyer un email de relance', 'step_type' => 'notification', 'config' => ['channel' => 'email', 'to' => 'tenant', 'template' => 'overdue_reminder'], 'order' => 3],
                        ['name' => 'Créer tâche de suivi', 'description' => 'Pour le gestionnaire', 'step_type' => 'action', 'config' => ['action' => 'create_task', 'task_name' => 'Suivi impayé'], 'order' => 4],
                    ],
                    'connections' => [
                        ['from_step_id' => 0, 'to_step_id' => 1, 'condition_label' => null],
                        ['from_step_id' => 1, 'to_step_id' => 2, 'condition_label' => null],
                        ['from_step_id' => 2, 'to_step_id' => 3, 'condition_label' => null],
                    ],
                ],
            ],
            [
                'name' => 'Gestion des incidents urgents',
                'description' => 'Quand un incident urgent est signalé, notifie le propriétaire, crée une intervention et planifie une visite de contrôle',
                'category' => 'incident',
                'is_built_in' => true,
                'config' => [
                    'trigger_type' => 'incident_reported',
                    'trigger_config' => [],
                    'steps' => [
                        ['name' => 'Vérifier urgence', 'description' => 'L\'incident est-il urgent ?', 'step_type' => 'condition', 'config' => ['field' => 'urgency', 'operator' => 'equals', 'value' => 'emergency'], 'order' => 1],
                        ['name' => 'Notifier propriétaire', 'description' => 'Alerter le bailleur', 'step_type' => 'notification', 'config' => ['channel' => 'both', 'to' => 'owner', 'template' => 'emergency_alert'], 'order' => 2],
                        ['name' => 'Créer intervention', 'description' => 'Marquer incident en cours', 'step_type' => 'action', 'config' => ['action' => 'update_status', 'status' => 'in_progress'], 'order' => 3],
                        ['name' => 'Approbation propriétaire', 'description' => 'Valider les travaux', 'step_type' => 'approval', 'config' => ['assigned_to' => 'owner'], 'order' => 4],
                    ],
                    'connections' => [
                        ['from_step_id' => 0, 'to_step_id' => 1, 'condition_label' => 'yes'],
                        ['from_step_id' => 1, 'to_step_id' => 2, 'condition_label' => null],
                        ['from_step_id' => 2, 'to_step_id' => 3, 'condition_label' => null],
                    ],
                ],
            ],
            [
                'name' => 'Rappel visite + suivi',
                'description' => '24h avant une visite, envoie un rappel au visiteur et prépare un suivi post-visite',
                'category' => 'visit',
                'is_built_in' => true,
                'config' => [
                    'trigger_type' => 'visit_reminder',
                    'trigger_config' => [],
                    'steps' => [
                        ['name' => 'Notifier le visiteur', 'description' => 'Rappel de la visite', 'step_type' => 'notification', 'config' => ['channel' => 'email', 'to' => 'tenant', 'template' => 'visit_reminder'], 'order' => 1],
                        ['name' => 'Attendre la visite', 'description' => '1 jour après la visite', 'step_type' => 'delay', 'config' => ['days' => 1], 'order' => 2],
                        ['name' => 'Demander retour visiteur', 'description' => 'Email de suivi post-visite', 'step_type' => 'notification', 'config' => ['channel' => 'email', 'to' => 'tenant', 'template' => 'visit_feedback'], 'order' => 3],
                    ],
                    'connections' => [
                        ['from_step_id' => 0, 'to_step_id' => 1, 'condition_label' => null],
                        ['from_step_id' => 1, 'to_step_id' => 2, 'condition_label' => null],
                    ],
                ],
            ],
            [
                'name' => 'Renouvellement contrat',
                'description' => '60 jours avant la fin d\'un contrat, notifie le bailleur, attend la décision, puis génère le nouveau bail',
                'category' => 'contract',
                'is_built_in' => true,
                'config' => [
                    'trigger_type' => 'contract_ending',
                    'trigger_config' => ['days' => 60],
                    'steps' => [
                        ['name' => 'Notifier renouvellement', 'description' => 'Prévenir le propriétaire', 'step_type' => 'notification', 'config' => ['channel' => 'email', 'to' => 'owner', 'template' => 'renewal_reminder'], 'order' => 1],
                        ['name' => 'Approbation renouvellement', 'description' => 'Le propriétaire valide-t-il ?', 'step_type' => 'approval', 'config' => ['assigned_to' => 'owner'], 'order' => 2],
                        ['name' => 'Générer nouveau contrat', 'description' => 'Préparer le bail', 'step_type' => 'action', 'config' => ['action' => 'log'], 'order' => 3],
                    ],
                    'connections' => [
                        ['from_step_id' => 0, 'to_step_id' => 1, 'condition_label' => null],
                        ['from_step_id' => 1, 'to_step_id' => 2, 'condition_label' => null],
                    ],
                ],
            ],
            [
                'name' => 'Pipeline onboarding locataire',
                'description' => 'À la signature d\'un contrat, envoie les accès portail, planifie l\'état des lieux et crée la première quittance',
                'category' => 'general',
                'is_built_in' => true,
                'config' => [
                    'trigger_type' => 'manual',
                    'trigger_config' => [],
                    'steps' => [
                        ['name' => 'Envoyer accès portail', 'description' => 'Invitation au portail locataire', 'step_type' => 'notification', 'config' => ['channel' => 'email', 'to' => 'tenant', 'template' => 'portal_welcome'], 'order' => 1],
                        ['name' => 'Planifier état des lieux', 'description' => 'Créer une visite', 'step_type' => 'action', 'config' => ['action' => 'create_task', 'task_name' => 'État des lieux entrée'], 'order' => 2],
                        ['name' => 'Générer première quittance', 'description' => 'Créer la quittance du 1er mois', 'step_type' => 'action', 'config' => ['action' => 'log'], 'order' => 3],
                        ['name' => 'Notifier le propriétaire', 'description' => 'Confirmation onboarding', 'step_type' => 'notification', 'config' => ['channel' => 'email', 'to' => 'owner', 'template' => 'onboarding_done'], 'order' => 4],
                    ],
                    'connections' => [
                        ['from_step_id' => 0, 'to_step_id' => 1, 'condition_label' => null],
                        ['from_step_id' => 1, 'to_step_id' => 2, 'condition_label' => null],
                        ['from_step_id' => 2, 'to_step_id' => 3, 'condition_label' => null],
                    ],
                ],
            ],
        ];

        foreach ($templates as $tpl) {
            WorkflowTemplate::firstOrCreate(
                ['name' => $tpl['name']],
                $tpl
            );
        }

        $this->command->info('5 workflow templates créés.');
    }
}
