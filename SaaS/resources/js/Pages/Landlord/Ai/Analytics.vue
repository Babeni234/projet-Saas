<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    overview: Object,
    finances: Object,
    alerts: Object,
    report: String,
});

const p = props.overview?.properties;
const t = props.overview?.tenants;
const c = props.overview?.contracts;
const f = props.overview?.finances ?? props.finances;
const v = props.overview?.visits;
const i = props.overview?.incidents;
const a = props.overview?.alerts ?? props.alerts;

function formatPrice(val) {
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(val || 0);
}

function occRate() {
    if (!p) return 0;
    return p.total > 0 ? Math.round((p.rented / p.total) * 100) : 0;
}

function formatReport(text) {
    if (!text) return '';
    return text
        .replace(/### /g, '<h3 class="text-base font-semibold text-gray-900 mt-5 mb-2">')
        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
        .replace(/- /g, '<span class="block ml-4">• </span>')
        .replace(/\n/g, '<br>');
}
</script>

<template>
    <AppLayout>
        <template #header>
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Analytics & Rapports IA</h2>
                <p class="text-sm text-gray-500">Analyse intelligente de votre patrimoine immobilier</p>
            </div>
        </template>

        <div class="space-y-6">
            <!-- KPI Cards -->
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                <div class="rounded-xl border border-gray-200 bg-white p-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Biens</p>
                            <p class="text-2xl font-bold text-gray-900">{{ p?.total ?? 0 }}</p>
                            <p class="text-xs" :class="occRate() >= 70 ? 'text-green-600' : 'text-amber-600'">
                                {{ p?.rented ?? 0 }} loués ({{ occRate() }}%)
                            </p>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Revenu mensuel</p>
                            <p class="text-2xl font-bold text-gray-900">{{ formatPrice(c?.monthly_revenue) }}</p>
                            <p class="text-xs text-green-600">+{{ formatPrice(f?.monthly_paid ?? 0) }} payé</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Impayés</p>
                            <p class="text-2xl font-bold text-red-600">{{ f?.overdue_count ?? 0 }}</p>
                            <p class="text-xs text-red-500">{{ formatPrice(f?.overdue_total) }}</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-rose-50 text-rose-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Urgences</p>
                            <p class="text-2xl font-bold text-gray-900">{{ i?.urgent ?? 0 }}</p>
                            <p class="text-xs text-gray-500">{{ i?.open ?? 0 }} incidents ouverts</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alerts -->
            <div v-if="a?.alerts?.length" class="rounded-xl border border-gray-200 bg-white p-5">
                <h3 class="mb-3 text-sm font-semibold text-gray-900">Alertes & Actions recommandées</h3>
                <div class="space-y-2">
                    <div v-for="(alert, idx) in a.alerts" :key="idx"
                        class="flex items-start gap-3 rounded-lg p-3 text-sm"
                        :class="alert.severity === 'urgent' ? 'bg-red-50 text-red-800' : alert.severity === 'high' ? 'bg-amber-50 text-amber-800' : alert.severity === 'medium' ? 'bg-yellow-50 text-yellow-800' : 'bg-blue-50 text-blue-800'">
                        <span class="mt-0.5 text-lg">{{ alert.severity === 'urgent' ? '🔴' : alert.severity === 'high' ? '🟠' : alert.severity === 'medium' ? '🟡' : '🔵' }}</span>
                        <span>{{ alert.message }}</span>
                    </div>
                </div>
            </div>

            <!-- Finances Detail -->
            <div v-if="f" class="rounded-xl border border-gray-200 bg-white p-5">
                <h3 class="mb-3 text-sm font-semibold text-gray-900">Détail financier</h3>
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                    <div class="rounded-lg bg-gray-50 p-3">
                        <p class="text-xs text-gray-500">Payé ce mois</p>
                        <p class="text-lg font-semibold text-green-700">{{ formatPrice(f.monthly_paid) }}</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-3">
                        <p class="text-xs text-gray-500">Payé cette année</p>
                        <p class="text-lg font-semibold text-gray-900">{{ formatPrice(f.yearly_paid) }}</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-3">
                        <p class="text-xs text-gray-500">En attente</p>
                        <p class="text-lg font-semibold text-amber-700">{{ formatPrice(f.pending_total) }} ({{ f.pending_count }})</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-3">
                        <p class="text-xs text-gray-500">En retard</p>
                        <p class="text-lg font-semibold text-red-700">{{ formatPrice(f.overdue_total) }} ({{ f.overdue_count }})</p>
                    </div>
                </div>

                <div v-if="f.overdue_list?.length" class="mt-4">
                    <p class="mb-2 text-xs font-semibold text-gray-700">Détail des impayés</p>
                    <div class="space-y-1.5">
                        <div v-for="r in f.overdue_list" :key="r.id"
                            class="flex items-center justify-between rounded-lg bg-red-50 px-3 py-2 text-sm">
                            <div>
                                <span class="font-medium text-red-800">{{ r.property }}</span>
                                <span class="ml-2 text-xs text-red-600">{{ r.period }}</span>
                            </div>
                            <span class="font-semibold text-red-700">{{ formatPrice(r.total) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rapport IA -->
            <div v-if="report" class="rounded-xl border border-gray-200 bg-white p-6">
                <div class="mb-4 flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 text-xs font-bold text-white">AI</div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">Rapport Intelligent</h3>
                        <p class="text-xs text-gray-500">Généré automatiquement par l'assistant IA</p>
                    </div>
                </div>
                <div class="prose prose-sm max-w-none text-gray-700" v-html="formatReport(report)" />
            </div>
        </div>
    </AppLayout>
</template>
