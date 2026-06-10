<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    accountType: {
        type: String,
        default: 'individual',
    },
});

const locale = ref('fr');

const plans = computed(() => {
    if (props.accountType === 'company') {
        return [
            {
                id: 'pro_entreprise',
                name: 'Pro Entreprise',
                price: '150 000',
                period: 'mois',
                features: [
                    'Jusqu\'à 100 logements',
                    '5 collaborateurs inclus',
                    'Relances et facturations automatisées',
                    'Rapports financiers standard',
                    'Support prioritaire par e-mail et chat',
                ],
                popular: false,
                color: 'from-slate-500 to-slate-600',
            },
            {
                id: 'business_entreprise',
                name: 'Business Entreprise',
                price: '350 000',
                period: 'mois',
                features: [
                    'Jusqu\'à 500 logements',
                    'Collaborateurs illimités',
                    'Intégration d\'API de paiement locales (Orange Money, MTN, Wave)',
                    'Contrats & baux intelligents générés par IA',
                    'Rapports analytiques et prévisions de trésorerie',
                    'Support prioritaire H24',
                ],
                popular: true,
                color: 'from-amber-500 to-amber-600',
            },
            {
                id: 'corporate',
                name: 'Corporate',
                price: '750 000',
                period: 'mois',
                features: [
                    'Logements & portefeuilles illimités',
                    'Solution marque blanche (votre logo & domaine)',
                    'Intégrations sur-mesure & API dédiée',
                    'Assistant IA et Agents Autonomes dédiés',
                    'Formation des équipes incluse',
                    'Gestionnaire de compte dédié 7j/7',
                ],
                popular: false,
                color: 'from-violet-500 to-violet-600',
            },
        ];
    } else {
        return [
            {
                id: 'standard_particulier',
                name: 'Standard Particulier',
                price: '15 000',
                period: 'mois',
                features: [
                    'Gestion jusqu\'à 5 logements',
                    'Création de fiches locataires',
                    'Génération de quittances automatisée',
                    'Suivi simple des paiements',
                    'Support par email standard',
                ],
                popular: false,
                color: 'from-slate-500 to-slate-600',
            },
            {
                id: 'premium_particulier',
                name: 'Premium Particulier',
                price: '45 000',
                period: 'mois',
                features: [
                    'Gestion jusqu\'à 25 logements',
                    'Relances automatiques (SMS et E-mail)',
                    'Modèles de baux de location conformes',
                    'Rapports de revenus et dépenses mensuels',
                    'Assistant IA de gestion immobilière basique',
                    'Support prioritaire',
                ],
                popular: true,
                color: 'from-amber-500 to-amber-600',
            },
            {
                id: 'expert_particulier',
                name: 'Expert Particulier',
                price: '95 000',
                period: 'mois',
                features: [
                    'Logements illimités',
                    'Génération de contrats personnalisés par IA',
                    'Analyse financière et rapports fiscaux avancés',
                    'Support dédié par e-mail et téléphone',
                    'Gestion multi-comptes propriétaires',
                ],
                popular: false,
                color: 'from-violet-500 to-violet-600',
            },
        ];
    }
});

const selectedPlan = ref(null);

const translations = {
    fr: {
        title: 'Choisissez votre forfait',
        subtitle: 'Sélectionnez l\'offre la plus adaptée à vos besoins de gestion immobilière.',
        features: 'Fonctionnalités incluses',
        select: 'Souscrire à ce plan',
        cancel: 'Annuler',
        continue: 'Continuer',
        monthly: 'par mois',
        free: 'Gratuit',
        trial: 'Essai gratuit de 14 jours',
    },
    en: {
        title: 'Choose your plan',
        subtitle: 'Select the offer best suited to your property management needs.',
        features: 'Features included',
        select: 'Subscribe to this plan',
        cancel: 'Cancel',
        continue: 'Continue',
        monthly: 'per month',
        free: 'Free',
        trial: '14-day free trial',
    },
};

const t = computed(() => translations[locale.value]);

function selectPlan(plan) {
    selectedPlan.value = plan;
    router.post(route('subscription.save'), {
        plan: plan.id,
    });
}
</script>

<template>
    <GuestLayout :title="t.title" :subtitle="t.subtitle" scrollable>
        <Head :title="t.title" />

        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-50 border border-amber-200 text-xs font-semibold text-amber-800 mb-4 shadow-sm">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                    </span>
                    Profil : {{ accountType === 'company' ? 'Entreprise / Professionnel' : 'Propriétaire Particulier' }}
                </div>
                <h1 class="text-3xl font-bold text-slate-900 mb-3">{{ t.title }}</h1>
                <p class="text-slate-600">{{ t.subtitle }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div
                    v-for="plan in plans"
                    :key="plan.id"
                    class="relative rounded-2xl border transition-all duration-300 hover:scale-105"
                    :class="plan.popular ? 'border-amber-400 shadow-xl shadow-amber-200/50' : 'border-slate-200 shadow-lg'"
                >
                    <div
                        v-if="plan.popular"
                        class="absolute -top-3 left-1/2 -translate-x-1/2 bg-gradient-to-r from-amber-500 to-amber-600 text-white text-xs font-semibold px-4 py-1 rounded-full shadow-md"
                    >
                        Populaire
                    </div>

                    <div class="p-6">
                        <div class="text-center mb-6">
                            <h3 class="text-xl font-bold text-slate-900 mb-2">{{ plan.name }}</h3>
                            <div class="flex items-baseline justify-center gap-1">
                                <span class="text-3xl font-extrabold text-amber-600">
                                    {{ plan.price }}
                                </span>
                                <span class="text-xs font-bold text-slate-500 ml-1">FCFA / {{ plan.period }}</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1.5">{{ t.trial }}</p>
                        </div>

                        <ul class="space-y-3 mb-6">
                            <li
                                v-for="feature in plan.features"
                                :key="feature"
                                class="flex items-start gap-3 text-sm text-slate-600"
                            >
                                <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                {{ feature }}
                            </li>
                        </ul>

                        <button
                            @click="selectPlan(plan)"
                            class="w-full py-3 px-4 rounded-xl font-semibold text-white transition-all duration-300 hover:shadow-lg"
                            :class="`bg-gradient-to-r ${plan.color} hover:shadow-${plan.color.split('-')[1]}-500/30`"
                        >
                            {{ t.select }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="text-center mt-8">
                <p class="text-sm text-slate-500">
                    Vous pouvez changer d'abonnement à tout moment. Aucune carte bancaire requise pour l'essai.
                </p>
            </div>
        </div>
    </GuestLayout>
</template>
