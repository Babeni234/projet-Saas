<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            // Company Plans
            [
                'name' => 'Pro Entreprise',
                'slug' => 'pro_entreprise',
                'account_type' => 'company',
                'price' => 150000,
                'max_logements' => 100,
                'max_locataires' => 100,
                'max_employees' => 5,
                'max_agencies' => 1,
                'max_buildings' => 5,
                'has_ai' => false,
                'billing_cycle' => 'monthly',
                'features' => [
                    "Jusqu'à 100 logements",
                    "5 collaborateurs inclus",
                    "Relances et facturations automatisées",
                    "Rapports financiers standard",
                    "Support prioritaire par e-mail et chat"
                ],
                'popular' => false,
                'color' => 'from-slate-500 to-slate-600',
            ],
            [
                'name' => 'Business Entreprise',
                'slug' => 'business_entreprise',
                'account_type' => 'company',
                'price' => 350000,
                'max_logements' => 500,
                'max_locataires' => 500,
                'max_employees' => -1,
                'max_agencies' => -1,
                'max_buildings' => -1,
                'has_ai' => true,
                'billing_cycle' => 'monthly',
                'features' => [
                    "Jusqu'à 500 logements",
                    "Collaborateurs illimités",
                    "Intégration d'API de paiement locales (Orange Money, MTN, Wave)",
                    "Contrats & baux intelligents générés par IA",
                    "Rapports analytiques et prévisions de trésorerie",
                    "Support prioritaire H24"
                ],
                'popular' => true,
                'color' => 'from-amber-500 to-amber-600',
            ],
            [
                'name' => 'Corporate',
                'slug' => 'corporate',
                'account_type' => 'company',
                'price' => 750000,
                'max_logements' => -1,
                'max_locataires' => -1,
                'max_employees' => -1,
                'max_agencies' => -1,
                'max_buildings' => -1,
                'has_ai' => true,
                'billing_cycle' => 'monthly',
                'features' => [
                    "Logements & portefeuilles illimités",
                    "Solution marque blanche (votre logo & domaine)",
                    "Intégrations sur-mesure & API dédiée",
                    "Assistant IA et Agents Autonomes dédiés",
                    "Formation des équipes incluse",
                    "Gestionnaire de compte dédié 7j/7"
                ],
                'popular' => false,
                'color' => 'from-violet-500 to-violet-600',
            ],

            // Individual Plans
            [
                'name' => 'Standard Particulier',
                'slug' => 'standard_particulier',
                'account_type' => 'individual',
                'price' => 15000,
                'max_logements' => 5,
                'max_locataires' => 5,
                'max_employees' => 0,
                'max_agencies' => 0,
                'max_buildings' => 1,
                'has_ai' => false,
                'billing_cycle' => 'monthly',
                'features' => [
                    "Gestion jusqu'à 5 logements",
                    "Création de fiches locataires",
                    "Génération de quittances automatisée",
                    "Suivi simple des paiements",
                    "Support par email standard"
                ],
                'popular' => false,
                'color' => 'from-slate-500 to-slate-600',
            ],
            [
                'name' => 'Premium Particulier',
                'slug' => 'premium_particulier',
                'account_type' => 'individual',
                'price' => 45000,
                'max_logements' => 25,
                'max_locataires' => 25,
                'max_employees' => 0,
                'max_agencies' => 0,
                'max_buildings' => 3,
                'has_ai' => true,
                'billing_cycle' => 'monthly',
                'features' => [
                    "Gestion jusqu'à 25 logements",
                    "Relances automatiques (SMS et E-mail)",
                    "Modèles de baux de location conformes",
                    "Rapports de revenus et dépenses mensuels",
                    "Assistant IA de gestion immobilière basique",
                    "Support prioritaire"
                ],
                'popular' => true,
                'color' => 'from-amber-500 to-amber-600',
            ],
            [
                'name' => 'Expert Particulier',
                'slug' => 'expert_particulier',
                'account_type' => 'individual',
                'price' => 95000,
                'max_logements' => -1,
                'max_locataires' => -1,
                'max_employees' => 0,
                'max_agencies' => 0,
                'max_buildings' => -1,
                'has_ai' => true,
                'billing_cycle' => 'monthly',
                'features' => [
                    "Logements illimités",
                    "Génération de contrats personnalisés par IA",
                    "Analyse financière et rapports fiscaux avancés",
                    "Support dédié par e-mail et téléphone",
                    "Gestion multi-comptes propriétaires"
                ],
                'popular' => false,
                'color' => 'from-violet-500 to-violet-600',
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
