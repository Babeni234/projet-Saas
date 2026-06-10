<script setup>
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';
import BarChart from '@nangue/Components/BarChart.vue';

const props = defineProps({
    stats: { type: Object, default: () => ({}) },
    propertyPerformance: { type: Array, default: () => [] },
    revenueChart: { type: Array, default: () => [] },
    occupancyChart: { type: Array, default: () => [] },
});

const selectedPeriod = ref('month');
const selectedMetric = ref('revenue');

const periods = [
    { value: 'week', label: 'Cette semaine' },
    { value: 'month', label: 'Ce mois' },
    { value: 'quarter', label: 'Ce trimestre' },
    { value: 'year', label: 'Cette année' },
];

const metrics = [
    { value: 'revenue', label: 'Revenus' },
    { value: 'occupancy', label: 'Taux d\'occupation' },
    { value: 'views', label: 'Vues' },
    { value: 'inquiries', label: 'Demandes' },
];
</script>

<template>
    <Head title="Analytiques" />

    <PropertyLayout
        role="bailleur"
        title="Analytiques"
        subtitle="Performance et statistiques de vos biens"
    >
        <template #actions>
            <button class="imo-btn-secondary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Exporter le rapport
            </button>
        </template>

        <div class="imo-page">
            <!-- Filtres -->
            <div class="imo-filters-bar">
                <div class="flex flex-1 items-center gap-4">
                    <select v-model="selectedPeriod" class="imo-form-select-sm">
                        <option v-for="period in periods" :key="period.value" :value="period.value">
                            {{ period.label }}
                        </option>
                    </select>

                    <select v-model="selectedMetric" class="imo-form-select-sm">
                        <option v-for="metric in metrics" :key="metric.value" :value="metric.value">
                            {{ metric.label }}
                        </option>
                    </select>
                </div>
            </div>

            <!-- KPIs principaux -->
            <div class="imo-stats-grid-4">
                <div class="imo-stat-card">
                    <p class="imo-stat-label">Revenus totaux</p>
                    <p class="imo-stat-value">{{ stats.total_revenue }}€</p>
                    <p class="imo-stat-change imo-stat-change-positive">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                        </svg>
                        +{{ stats.revenue_change }}%
                    </p>
                </div>
                <div class="imo-stat-card">
                    <p class="imo-stat-label">Taux d'occupation</p>
                    <p class="imo-stat-value">{{ stats.occupancy_rate }}%</p>
                    <p class="imo-stat-change imo-stat-change-positive">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                        </svg>
                        +{{ stats.occupancy_change }}%
                    </p>
                </div>
                <div class="imo-stat-card">
                    <p class="imo-stat-label">Vues totales</p>
                    <p class="imo-stat-value">{{ stats.total_views }}</p>
                    <p class="imo-stat-change imo-stat-change-positive">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                        </svg>
                        +{{ stats.views_change }}%
                    </p>
                </div>
                <div class="imo-stat-card">
                    <p class="imo-stat-label">Demandes</p>
                    <p class="imo-stat-value">{{ stats.total_inquiries }}</p>
                    <p class="imo-stat-change imo-stat-change-neutral">
                        {{ stats.inquiries_change }}%
                    </p>
                </div>
            </div>

            <!-- Graphiques -->
            <div class="grid gap-6 lg:grid-cols-2">
                <!-- Revenus mensuels -->
                <div class="rounded-xl border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-800">
                    <h3 class="mb-4 text-lg font-semibold text-slate-900 dark:text-white">Revenus mensuels</h3>
                    <BarChart :data="revenueChart" labelKey="label" valueKey="percent" color="#10b981" label="Revenus" />
                </div>

                <!-- Taux d'occupation -->
                <div class="rounded-xl border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-800">
                    <h3 class="mb-4 text-lg font-semibold text-slate-900 dark:text-white">Taux d'occupation</h3>
                    <BarChart :data="occupancyChart" labelKey="label" valueKey="percent" color="#3b82f6" label="Occupation" />
                </div>
            </div>

            <!-- Performance par bien -->
            <div class="mt-6">
                <h3 class="mb-4 text-lg font-semibold text-slate-900">Performance par bien</h3>
                <div class="imo-table-wrap">
                    <div class="overflow-x-auto">
                        <table class="imo-table w-full min-w-[1000px]">
                            <thead>
                                <tr>
                                    <th>Bien</th>
                                    <th>Revenus</th>
                                    <th>Taux occupation</th>
                                    <th>Vues</th>
                                    <th>Demandes</th>
                                    <th>Taux conversion</th>
                                    <th>Note moyenne</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="property in propertyPerformance"
                                    :key="property.id"
                                    class="hover:bg-slate-50"
                                >
                                    <td>
                                        <p class="font-semibold text-slate-900">{{ property.name }}</p>
                                        <p class="text-xs text-slate-500">{{ property.address }}</p>
                                    </td>
                                    <td>
                                        <p class="font-semibold text-slate-900">{{ property.revenue }}€</p>
                                        <p class="text-xs text-slate-500">{{ property.revenue_change }}%</p>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <div class="h-2 w-24 rounded-full bg-slate-200">
                                                <div
                                                    class="h-2 rounded-full bg-emerald-600"
                                                    :style="{ width: `${property.occupancy}%` }"
                                                />
                                            </div>
                                            <span class="text-sm font-medium text-slate-900">{{ property.occupancy }}%</span>
                                        </div>
                                    </td>
                                    <td class="text-sm text-slate-600">{{ property.views }}</td>
                                    <td class="text-sm text-slate-600">{{ property.inquiries }}</td>
                                    <td>
                                        <span
                                            class="rounded-full px-2 py-1 text-xs font-semibold"
                                            :class="{
                                                'bg-emerald-100 text-emerald-800': property.conversion_rate >= 10,
                                                'bg-amber-100 text-amber-800': property.conversion_rate >= 5 && property.conversion_rate < 10,
                                                'bg-red-100 text-red-800': property.conversion_rate < 5,
                                            }"
                                        >
                                            {{ property.conversion_rate }}%
                                        </span>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-1">
                                            <svg class="h-4 w-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            <span class="text-sm font-medium text-slate-900">{{ property.rating }}</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- État vide -->
            <div v-if="!propertyPerformance.length" class="imo-empty-state-lg">
                <svg class="mx-auto h-16 w-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <h3 class="mt-4 text-lg font-semibold text-slate-900">Aucune donnée</h3>
                <p class="mt-2 text-sm text-slate-500">Les statistiques apparaîtront une fois vos biens publiés et visités.</p>
            </div>
        </div>
    </PropertyLayout>
</template>
