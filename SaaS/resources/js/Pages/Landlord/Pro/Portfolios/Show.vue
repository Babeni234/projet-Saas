<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    portfolio: Object,
    stats: Object,
});

const colorMap = {
    '#6366f1': 'bg-indigo-500',
    '#059669': 'bg-emerald-500',
    '#d97706': 'bg-amber-500',
    '#dc2626': 'bg-red-500',
    '#7c3aed': 'bg-violet-500',
    '#0891b2': 'bg-cyan-500',
};

function fm(v) {
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR', minimumFractionDigits: 0 }).format(v || 0);
}
</script>

<template>
    <Head :title="portfolio.name" />
    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <Link :href="route('landlord.pro.portfolios.index')" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </Link>
                    <div>
                        <div class="flex items-center gap-2">
                            <div :class="colorMap[portfolio.color] || 'bg-indigo-500'" class="h-3 w-3 rounded-full" />
                            <h2 class="text-lg font-semibold text-gray-900">{{ portfolio.name }}</h2>
                        </div>
                        <p class="text-sm text-gray-500">{{ portfolio.description || 'Portfolio' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <Link :href="route('landlord.pro.portfolios.edit', portfolio.id)" class="rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Modifier</Link>
                </div>
            </div>
        </template>

        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
            <div class="rounded-xl border border-gray-200 bg-white p-4">
                <p class="text-xs text-gray-500">Biens</p>
                <p class="text-2xl font-bold text-gray-900">{{ stats.total_properties }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4">
                <p class="text-xs text-gray-500">Loués</p>
                <p class="text-2xl font-bold text-emerald-700">{{ stats.rented }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4">
                <p class="text-xs text-gray-500">Disponibles</p>
                <p class="text-2xl font-bold text-amber-600">{{ stats.available }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4">
                <p class="text-xs text-gray-500">Revenu mensuel</p>
                <p class="text-2xl font-bold text-indigo-700">{{ fm(stats.monthly_revenue) }}</p>
            </div>
        </div>

        <div class="mt-6">
            <h3 class="mb-4 text-sm font-semibold text-gray-900">Biens ({{ portfolio.properties.length }})</h3>
            <div v-if="portfolio.properties.length" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="p in portfolio.properties" :key="p.id"
                    class="rounded-xl border border-gray-200 bg-white p-4 transition hover:shadow-md">
                    <div class="flex items-start justify-between">
                        <div>
                            <h4 class="font-medium text-gray-900">{{ p.title }}</h4>
                            <p class="text-xs text-gray-500">{{ p.city }}</p>
                        </div>
                        <span class="rounded-full bg-indigo-50 px-2 py-0.5 text-xs font-medium text-indigo-700">{{ p.property_type }}</span>
                    </div>
                    <div class="mt-3 flex items-center justify-between">
                        <p class="text-sm font-semibold text-gray-900">{{ p.price?.toLocaleString() }} €<span v-if="p.transaction_type === 'rent'" class="text-xs font-normal text-gray-500">/mois</span></p>
                        <span class="text-xs" :class="p.active_contracts > 0 ? 'text-emerald-600' : 'text-amber-600'">
                            {{ p.active_contracts > 0 ? 'Loué' : 'Libre' }}
                        </span>
                    </div>
                </div>
            </div>
            <p v-else class="text-sm text-gray-400">Aucun bien dans ce portfolio.</p>
        </div>
    </AppLayout>
</template>
