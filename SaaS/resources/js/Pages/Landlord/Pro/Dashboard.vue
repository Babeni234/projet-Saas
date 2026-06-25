<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    kpi: Object,
    revenue_trend: Array,
    properties_by_type: Object,
    recent_automation_logs: Array,
    is_pro: Boolean,
});

function fm(v) {
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR', minimumFractionDigits: 0 }).format(v || 0);
}

function occRate() {
    if (!props.kpi.properties) return 0;
    return props.kpi.properties > 0
        ? Math.round((props.kpi.active_contracts / props.kpi.properties) * 100)
        : 0;
}

function maxRevenue() {
    if (!props.revenue_trend?.length) return 1;
    return Math.max(...props.revenue_trend.map(r => r.revenue), 1);
}
</script>

<template>
    <Head title="Bailleur Pro" />

    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Tableau de bord Pro</h2>
                    <p class="text-sm text-gray-500">Analyses avancées et vue d'ensemble de votre patrimoine</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 px-3 py-1 text-xs font-semibold text-white">PRO</span>
                    <Link v-if="!is_pro" :href="route('landlord.pro.dashboard')"
                        class="rounded-lg bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                        Activer le mode Pro
                    </Link>
                </div>
            </div>
        </template>

        <!-- KPI Grid -->
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
            <div class="rounded-xl border border-gray-200 bg-white p-4">
                <p class="text-xs text-gray-500">Portfolios</p>
                <p class="mt-1 text-2xl font-bold text-gray-900">{{ kpi.portfolios }}</p>
                <p class="text-xs text-gray-400">Groupes de biens</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4">
                <p class="text-xs text-gray-500">Biens</p>
                <p class="mt-1 text-2xl font-bold text-gray-900">{{ kpi.properties }}</p>
                <p class="text-xs text-green-600">{{ occRate() }}% occupés</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4">
                <p class="text-xs text-gray-500">Revenu mensuel</p>
                <p class="mt-1 text-2xl font-bold text-emerald-700">{{ fm(kpi.monthly_revenue) }}</p>
                <p class="text-xs text-gray-400">Annuel : {{ fm(kpi.yearly_revenue) }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4">
                <p class="text-xs text-gray-500">Impayés</p>
                <p class="mt-1 text-2xl font-bold text-red-600">{{ fm(kpi.overdue_total) }}</p>
                <p class="text-xs text-gray-400">{{ kpi.visits_upcoming }} visites à venir</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4">
                <p class="text-xs text-gray-500">Équipe</p>
                <p class="mt-1 text-2xl font-bold text-gray-900">{{ kpi.team_members }}</p>
                <p class="text-xs text-indigo-600">{{ kpi.active_automations }} automatisations actives</p>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Revenue Trend Chart -->
            <div class="rounded-xl border border-gray-200 bg-white p-5 lg:col-span-2">
                <h3 class="mb-4 text-sm font-semibold text-gray-900">Tendance des revenus (6 mois)</h3>
                <div class="flex items-end gap-3" style="height:160px">
                    <div v-for="(item, idx) in revenue_trend" :key="idx" class="flex flex-1 flex-col items-center gap-1">
                        <div class="relative w-full rounded-t-md transition-all duration-500"
                            :style="{ height: (item.revenue / maxRevenue()) * 140 + 'px', backgroundColor: idx === revenue_trend.length - 1 ? '#6366f1' : '#c7d2fe' }">
                            <span class="absolute -top-5 left-1/2 -translate-x-1/2 text-[10px] font-medium text-gray-600 whitespace-nowrap">
                                {{ fm(item.revenue) }}
                            </span>
                        </div>
                        <span class="text-[10px] text-gray-400">{{ item.month }}</span>
                    </div>
                </div>
            </div>

            <!-- Properties by type -->
            <div class="rounded-xl border border-gray-200 bg-white p-5">
                <h3 class="mb-4 text-sm font-semibold text-gray-900">Biens par type</h3>
                <div class="space-y-3">
                    <div v-for="(count, type) in properties_by_type" :key="type"
                        class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 capitalize">{{ type }}</span>
                        <div class="flex items-center gap-2">
                            <div class="h-2 rounded-full bg-indigo-100" :style="{ width: (count / Math.max(...Object.values(properties_by_type), 1)) * 100 + 'px' }">
                                <div class="h-full rounded-full bg-indigo-600" :style="{ width: (count / Math.max(...Object.values(properties_by_type), 1)) * 100 + '%' }" />
                            </div>
                            <span class="text-sm font-medium text-gray-900 w-6 text-right">{{ count }}</span>
                        </div>
                    </div>
                    <p v-if="!Object.keys(properties_by_type).length" class="text-sm text-gray-400">Aucun bien</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-4">
            <Link :href="route('landlord.pro.portfolios.index')"
                class="flex items-center gap-3 rounded-xl border border-gray-200 bg-white p-4 hover:border-indigo-200 hover:bg-indigo-50/30 transition">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Portfolios</p>
                    <p class="text-xs text-gray-500">Gérer les groupes</p>
                </div>
            </Link>
            <Link :href="route('landlord.pro.team.index')"
                class="flex items-center gap-3 rounded-xl border border-gray-200 bg-white p-4 hover:border-indigo-200 hover:bg-indigo-50/30 transition">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" /></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Équipe</p>
                    <p class="text-xs text-gray-500">Inviter des agents</p>
                </div>
            </Link>
            <Link :href="route('landlord.pro.automation.index')"
                class="flex items-center gap-3 rounded-xl border border-gray-200 bg-white p-4 hover:border-indigo-200 hover:bg-indigo-50/30 transition">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Automatisations</p>
                    <p class="text-xs text-gray-500">Règles et workflows</p>
                </div>
            </Link>
            <Link :href="route('landlord.properties.create')"
                class="flex items-center gap-3 rounded-xl border border-gray-200 bg-white p-4 hover:border-indigo-200 hover:bg-indigo-50/30 transition">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-rose-50 text-rose-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" /></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Nouveau bien</p>
                    <p class="text-xs text-gray-500">Ajouter un bien</p>
                </div>
            </Link>
        </div>

        <!-- Recent automation logs -->
        <div v-if="recent_automation_logs?.length" class="mt-6 rounded-xl border border-gray-200 bg-white p-5">
            <h3 class="mb-3 text-sm font-semibold text-gray-900">Dernières exécutions d'automatisation</h3>
            <div class="space-y-2">
                <div v-for="log in recent_automation_logs" :key="log.id"
                    class="flex items-center justify-between rounded-lg px-3 py-2 text-sm"
                    :class="log.status === 'success' ? 'bg-emerald-50 text-emerald-800' : log.status === 'failed' ? 'bg-red-50 text-red-800' : 'bg-gray-50 text-gray-600'">
                    <div class="flex items-center gap-2">
                        <span>{{ log.status === 'success' ? '✅' : log.status === 'failed' ? '❌' : '⏭️' }}</span>
                        <span>{{ log.message }}</span>
                    </div>
                    <span class="text-xs opacity-60">{{ log.created_at }}</span>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
