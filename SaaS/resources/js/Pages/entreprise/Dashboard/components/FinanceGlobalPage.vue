<template>
    <div class="flex flex-col gap-8 p-6">
        <!-- Header -->
        <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Finance Globale</h1>
                <p class="text-sm text-slate-500">Vue consolidée et analytique des flux de trésorerie de votre entreprise.</p>
            </div>
            <div class="flex items-center gap-3">
                <!-- Date Filter -->
                <select 
                    v-model="selectedYear" 
                    class="rounded-xl border-slate-200 text-sm font-semibold text-slate-700 bg-white shadow-sm focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 cursor-pointer"
                >
                    <option value="2026">Exercice 2026</option>
                    <option value="2025">Exercice 2025</option>
                </select>
                <!-- Export button -->
                <button
                    @click="exportFinancialReport"
                    class="inline-flex items-center gap-2 rounded-xl bg-white border-2 border-slate-200 px-4 py-2 text-sm font-bold text-slate-700 shadow-sm transition-all hover:bg-slate-50 focus:ring-2 focus:ring-slate-300"
                >
                    <svg class="h-4.5 w-4.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Exporter
                </button>
            </div>
        </div>

        <!-- Banner -->
        <div class="overflow-hidden rounded-2xl border border-violet-100 bg-gradient-to-r from-violet-600 via-indigo-600 to-indigo-700 p-6 text-white shadow-md relative">
            <div class="relative z-10 flex flex-col justify-between gap-4 md:flex-row md:items-center">
                <div class="space-y-1.5">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-2.5 py-0.5 text-xs font-semibold text-violet-100 backdrop-blur-md">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                        Consolidation Multi-Agences
                    </span>
                    <h2 class="text-xl font-bold">Rapport Analytique de Trésorerie</h2>
                    <p class="text-sm text-violet-100/90 max-w-2xl">
                        Visualisez et comparez la performance financière globale, incluant les loyers collectés par vos agences, les subventions d'entrées diverses, ainsi que l'ensemble des dépenses d'exploitation.
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="hidden lg:flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10 text-white backdrop-blur-md">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                        </svg>
                    </div>
                </div>
            </div>
            <!-- Decorative circles -->
            <div class="absolute -right-12 -top-12 h-44 w-44 rounded-full bg-violet-400/20 blur-3xl pointer-events-none"></div>
            <div class="absolute -left-12 -bottom-12 h-44 w-44 rounded-full bg-indigo-400/20 blur-3xl pointer-events-none"></div>
        </div>

        <!-- KPIs -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Revenue -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="px-2 py-0.5 bg-emerald-55 text-emerald-700 rounded-full text-xs font-bold font-mono">+12.4%</span>
                </div>
                <div class="text-2xl font-bold text-slate-900 mb-1">{{ formatCurrency(kpis.revenue) }}</div>
                <div class="text-sm font-semibold text-slate-500">Revenus Consolidés</div>
            </div>

            <!-- Total Expenses -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 bg-rose-50 rounded-xl flex items-center justify-center text-rose-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                        </svg>
                    </div>
                    <span class="px-2 py-0.5 bg-rose-55 text-rose-700 rounded-full text-xs font-bold font-mono">-4.2%</span>
                </div>
                <div class="text-2xl font-bold text-slate-900 mb-1">{{ formatCurrency(kpis.expenses) }}</div>
                <div class="text-sm font-semibold text-slate-500">Dépenses Exploitation</div>
            </div>

            <!-- Net Cash -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 bg-violet-50 rounded-xl flex items-center justify-center text-violet-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z" />
                        </svg>
                    </div>
                    <span class="px-2 py-0.5 bg-indigo-55 text-indigo-700 rounded-full text-xs font-bold font-mono">+23.1%</span>
                </div>
                <div class="text-2xl font-bold text-slate-900 mb-1">{{ formatCurrency(kpis.netCash) }}</div>
                <div class="text-sm font-semibold text-slate-500">Flux Net de Trésorerie</div>
            </div>

            <!-- Profit Rate -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                    <span class="px-2 py-0.5 bg-amber-100 text-amber-800 rounded-full text-xs font-bold font-mono">Premium</span>
                </div>
                <div class="text-2xl font-bold text-slate-900 mb-1">{{ kpis.profitMargin }}%</div>
                <div class="text-sm font-semibold text-slate-500">Marge Bénéficiaire Net</div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Financial Evolution Curve -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-slate-800">Flux de Trésorerie Mensuels</h3>
                    <div class="flex gap-2">
                        <span class="inline-flex items-center gap-1 text-xs text-slate-500">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span> Entrées
                        </span>
                        <span class="inline-flex items-center gap-1 text-xs text-slate-500">
                            <span class="h-2 w-2 rounded-full bg-rose-500"></span> Sorties
                        </span>
                    </div>
                </div>
                <div class="h-72">
                    <canvas id="financeEvolutionChart"></canvas>
                </div>
            </div>

            <!-- Revenue Structure -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-slate-800">Structure des Revenus</h3>
                    <p class="text-xs text-slate-500">Répartition par canal</p>
                </div>
                <div class="h-72">
                    <canvas id="financeStructureChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Agency Breakdown & General Headquarters -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
            <div class="flex flex-col gap-2 mb-6">
                <h3 class="text-lg font-bold text-slate-800">Performance Financière par Entité</h3>
                <p class="text-sm text-slate-500">Détail consolidé des revenus et charges par agence et siège social.</p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-4">Nom de l'Entité</th>
                            <th class="px-6 py-4">Type</th>
                            <th class="px-6 py-4 text-right">Loyers / Recettes Directes</th>
                            <th class="px-6 py-4 text-right">Recettes Diverses</th>
                            <th class="px-6 py-4 text-right">Dépenses de Fonctionnement</th>
                            <th class="px-6 py-4 text-right font-bold text-slate-900">Solde Net</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        <tr v-for="entite in tableData" :key="entite.nom" class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-900">{{ entite.nom }}</td>
                            <td class="px-6 py-4">
                                <span 
                                    class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold"
                                    :class="entite.type === 'Siège' ? 'bg-violet-100 text-violet-800' : 'bg-blue-100 text-blue-800'"
                                >
                                    {{ entite.type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-medium text-slate-700">{{ formatCurrency(entite.loyers) }}</td>
                            <td class="px-6 py-4 text-right font-medium text-slate-700">{{ formatCurrency(entite.divers) }}</td>
                            <td class="px-6 py-4 text-right font-medium text-slate-700 text-rose-600">-{{ formatCurrency(entite.depenses) }}</td>
                            <td class="px-6 py-4 text-right font-bold" :class="entite.solde >= 0 ? 'text-emerald-600' : 'text-rose-600'">
                                {{ formatCurrency(entite.solde) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Chart } from 'chart.js/auto';

const selectedYear = ref('2026');

// Mock data configuration for initialization
const kpis = ref({
    revenue: 56985000,
    expenses: 24320000,
    netCash: 32665000,
    profitMargin: 57.3,
});

const tableData = ref([
    { nom: 'Siège Général (Gouvernance)', type: 'Siège', loyers: 0, divers: 14500000, depenses: 8400000, solde: 6100000 },
    { nom: 'Agence Yaoundé (Centre)', type: 'Agence', loyers: 22400000, divers: 2100000, depenses: 7200000, solde: 17300000 },
    { nom: 'Agence Douala (Littoral)', type: 'Agence', loyers: 16800000, divers: 1185000, depenses: 8720000, solde: 9265000 },
]);

let evolutionChartInstance = null;
let structureChartInstance = null;

const formatCurrency = (value) => {
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'XAF' }).format(value);
};

const exportFinancialReport = () => {
    alert("Le rapport consolidé de l'exercice " + selectedYear.value + " a été généré et sera téléchargé sous peu.");
};

onMounted(() => {
    // 1. Chart - Financial Evolution (Line/Area)
    const evolutionCtx = document.getElementById('financeEvolutionChart');
    if (evolutionCtx) {
        const gradIn = evolutionCtx.getContext('2d').createLinearGradient(0, 0, 0, 300);
        gradIn.addColorStop(0, 'rgba(16, 185, 129, 0.4)');
        gradIn.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

        const gradOut = evolutionCtx.getContext('2d').createLinearGradient(0, 0, 0, 300);
        gradOut.addColorStop(0, 'rgba(244, 63, 94, 0.4)');
        gradOut.addColorStop(1, 'rgba(244, 63, 94, 0.0)');

        evolutionChartInstance = new Chart(evolutionCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
                datasets: [
                    {
                        label: 'Recettes',
                        data: [6200000, 7500000, 9100000, 8900000, 11400000, 13885000],
                        borderColor: '#10b981',
                        backgroundColor: gradIn,
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 4,
                    },
                    {
                        label: 'Dépenses',
                        data: [3100000, 4200000, 3900000, 4100000, 4800000, 4220000],
                        borderColor: '#f43f5e',
                        backgroundColor: gradOut,
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        cornerRadius: 12,
                        padding: 12,
                        backgroundColor: '#1e293b',
                    }
                },
                scales: {
                    x: {
                        grid: { display: false }
                    },
                    y: {
                        ticks: {
                            callback: function(val) {
                                return (val / 1000000) + 'M XAF';
                            }
                        },
                        grid: { color: 'rgba(226, 232, 240, 0.6)' }
                    }
                }
            }
        });
    }

    // 2. Chart - Structure/Pie Chart
    const structureCtx = document.getElementById('financeStructureChart');
    if (structureCtx) {
        structureChartInstance = new Chart(structureCtx, {
            type: 'doughnut',
            data: {
                labels: ['Loyers Locatifs', 'Subventions', 'Apports Associés', 'Services Hôtellerie', 'Autres Entrées'],
                datasets: [{
                    data: [39200000, 8500000, 6000000, 0, 3285000],
                    backgroundColor: [
                        '#6366f1', // Indigo
                        '#10b981', // Emerald
                        '#f59e0b', // Amber
                        '#3b82f6', // Blue
                        '#64748b'  // Slate
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 15,
                            font: { size: 12 }
                        }
                    },
                    tooltip: {
                        cornerRadius: 12,
                        padding: 12,
                        backgroundColor: '#1e293b',
                        callbacks: {
                            label: function(context) {
                                const val = context.raw;
                                return context.label + ': ' + formatCurrency(val);
                            }
                        }
                    }
                },
                cutout: '70%',
            }
        });
    }
});

onUnmounted(() => {
    if (evolutionChartInstance) {
        evolutionChartInstance.destroy();
    }
    if (structureChartInstance) {
        structureChartInstance.destroy();
    }
});
</script>

<style scoped>
/* Focus borders override styling */
select:focus {
    outline: none;
}
</style>
