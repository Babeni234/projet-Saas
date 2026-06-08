<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const locale = ref('fr');

const plans = [
    {
        id: 'starter',
        name: 'Starter',
        price: 0,
        period: 'mois',
        features: [
            'Gestion immobilière de base',
            '5 logements maximum',
            'Support par email',
            'Rapports mensuels',
            '1 utilisateur',
        ],
        popular: false,
        color: 'from-slate-500 to-slate-600',
    },
    {
        id: 'professional',
        name: 'Professional',
        price: 49,
        period: 'mois',
        features: [
            'Gestion immobilière complète',
            '50 logements maximum',
            'Support prioritaire',
            'Rapports en temps réel',
            '5 utilisateurs',
            'Intégration API',
            'Export PDF',
        ],
        popular: true,
        color: 'from-amber-500 to-amber-600',
    },
    {
        id: 'enterprise',
        name: 'Enterprise',
        price: 149,
        period: 'mois',
        features: [
            'Gestion multi-portfolios',
            'Logements illimités',
            'Support dédié 24/7',
            'Rapports personnalisés',
            'Utilisateurs illimités',
            'Intégration API avancée',
            'IA pour prédictions',
            'Formation incluse',
        ],
        popular: false,
        color: 'from-violet-500 to-violet-600',
    },
];

const selectedPlan = ref(null);

const translations = {
    fr: {
        title: 'Choisissez votre abonnement',
        subtitle: 'Commencez votre essai gratuit de 14 jours',
        features: 'Caractéristiques',
        select: 'Choisir ce plan',
        cancel: 'Annuler',
        continue: 'Continuer',
        monthly: 'par mois',
        free: 'Gratuit',
        trial: 'Essai gratuit 14 jours',
    },
    en: {
        title: 'Choose your subscription',
        subtitle: 'Start your 14-day free trial',
        features: 'Features',
        select: 'Select this plan',
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
    router.visit(route('register'), {
        method: 'get',
        data: { plan: plan.id },
    });
}
</script>

<template>
    <GuestLayout :title="t.title" :subtitle="t.subtitle" scrollable>
        <Head :title="t.title" />

        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
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
                                <span class="text-4xl font-bold" :class="plan.price === 0 ? 'text-slate-900' : 'text-amber-600'">
                                    {{ plan.price === 0 ? t.free : plan.price }}
                                </span>
                                <span v-if="plan.price > 0" class="text-slate-600">{{ t.monthly }}</span>
                            </div>
                            <p class="text-sm text-slate-500 mt-1">{{ t.trial }}</p>
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
