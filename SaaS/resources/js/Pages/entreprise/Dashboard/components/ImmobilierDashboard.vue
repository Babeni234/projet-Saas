<template>
    <div class="flex flex-col gap-8 select-none">
        
        <!-- Dashboard Premium Header -->
        <div class="bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-950 rounded-3xl p-8 border border-slate-800 shadow-2xl text-white relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-48 h-48 rounded-full bg-indigo-500/10 blur-2xl pointer-events-none"></div>
            <div class="absolute -left-10 -bottom-10 w-48 h-48 rounded-full bg-blue-500/10 blur-2xl pointer-events-none"></div>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative z-10">
                <div>
                    <span class="text-indigo-400 text-xs font-bold tracking-widest uppercase bg-indigo-500/20 px-3 py-1 rounded-full border border-indigo-500/30">Vue d'ensemble</span>
                    <h1 class="text-3xl font-extrabold bg-gradient-to-r from-white via-slate-100 to-slate-300 bg-clip-text text-transparent mt-2">Tableau de Bord Immobilier</h1>
                    <p class="text-slate-400 text-sm mt-1">Pilotage des performances locatives, du taux de vacance et de la trésorerie immobilière.</p>
                </div>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Vacancy Card -->
            <div class="bg-gradient-to-br from-white to-red-50/10 rounded-3xl p-6 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-red-200/40 transition-all duration-500 hover:-translate-y-1 border border-slate-150 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-red-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-rose-600 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/30">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-rose-50 text-rose-700 border border-rose-100 rounded-full text-xs font-bold shadow-sm">
                        Taux vacance
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-slate-800 mb-1 tracking-tight">{{ vacancyRate }}%</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Taux de Vacance</div>
                <div class="text-xs text-slate-500 mt-4 border-t border-slate-100 pt-3 flex justify-between">
                    <span>Inoccupés : <strong class="text-slate-800">{{ vacantCount }}</strong></span>
                    <span>Total : {{ totalLogementsCount }}</span>
                </div>
            </div>

            <!-- Revenue Card -->
            <div class="bg-gradient-to-br from-white to-blue-50/10 rounded-3xl p-6 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-blue-200/40 transition-all duration-500 hover:-translate-y-1 border border-slate-150 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-blue-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-full text-xs font-bold shadow-sm">
                        Ce mois
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-slate-800 mb-1 tracking-tight">{{ formatCurrency(monthlyRevenueActual) }}</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Revenus Locatifs</div>
                <div class="text-xs text-slate-500 mt-4 border-t border-slate-100 pt-3 flex justify-between">
                    <span>Attendu : <strong class="text-slate-800">{{ formatCurrency(monthlyRevenueExpected) }}</strong></span>
                </div>
            </div>

            <!-- Unpaid Card -->
            <div class="bg-gradient-to-br from-white to-amber-50/10 rounded-3xl p-6 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-amber-200/40 transition-all duration-500 hover:-translate-y-1 border border-slate-150 border-l-4 border-l-amber-500 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-amber-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/30">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-red-50 text-red-700 border border-red-100 rounded-full text-xs font-bold shadow-sm">
                        Impayés
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-slate-800 mb-1 tracking-tight">{{ unpaidRate }}%</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Taux d'Impayés</div>
                <div class="text-xs text-slate-500 mt-4 border-t border-slate-100 pt-3 flex justify-between">
                    <span>Cumulé : <strong class="text-rose-600">{{ formatCurrency(unpaidTotal) }}</strong></span>
                </div>
            </div>

            <!-- Charges Card -->
            <div class="bg-gradient-to-br from-white to-emerald-50/10 rounded-3xl p-6 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-emerald-200/40 transition-all duration-500 hover:-translate-y-1 border border-slate-150 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-emerald-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-full text-xs font-bold shadow-sm">
                        Charges
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-slate-800 mb-1 tracking-tight">{{ chargesRecoveryRate }}%</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Récupération Charges</div>
                <div class="text-xs text-slate-500 mt-4 border-t border-slate-100 pt-3 flex justify-between">
                    <span>Recouvré : <strong class="text-slate-800">{{ formatCurrency(chargesRecovered) }}</strong> / {{ formatCurrency(chargesTotal) }}</span>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 gap-6">
            <!-- Unpaid Chart -->
            <div class="bg-gradient-to-br from-white to-slate-50 rounded-3xl p-6 shadow-xl shadow-slate-200/50 border border-slate-150 col-span-full">
                <div class="mb-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-slate-850">Impayés par Période</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Distribution chronologique de l'encours locatif (30j, 60j, 90j, 120j, 150j+)</p>
                    </div>
                </div>
                <div class="h-72">
                    <canvas id="unpaidChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Lease Management -->
        <div class="bg-gradient-to-br from-white to-slate-50 rounded-3xl p-6 shadow-xl shadow-slate-200/50 border border-slate-150">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-850">Gestion des Baux et Renouvellements</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Suivi des baux actifs et alertes sur les échéances proches</p>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200/50 bg-slate-50/50">
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Locataire</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Propriété / Immeuble</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Loyer Mensuel</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Fin de Bail</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-150">
                        <tr v-for="c in contratsActifsList" :key="c.id" class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div :class="['w-10 h-10 rounded-xl flex items-center justify-center text-white font-extrabold text-sm shadow-sm bg-gradient-to-br', getAvatarGradient(c.locataire)]">
                                        {{ getInitials(c.locataire) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800 text-sm">{{ c.locataire }}</div>
                                        <div class="text-xs text-slate-500" v-if="c.email">{{ c.email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700 font-semibold">{{ c.logement }} ({{ c.batiment }})</td>
                            <td class="px-6 py-4 text-sm text-slate-700 font-bold">{{ formatCurrency(c.loyer) }}</td>
                            <td class="px-6 py-4 text-sm font-extrabold text-indigo-600">{{ formatDate(c.dateEcheance) }}</td>
                            <td class="px-6 py-4">
                                <span :class="[
                                    'inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold',
                                    c.statut === 'Actif' ? 'bg-emerald-50 text-emerald-700 border border-emerald-250' : 'bg-amber-50 text-amber-700 border border-amber-250'
                                ]">
                                    <span class="w-1.5 h-1.5 rounded-full" :class="c.statut === 'Actif' ? 'bg-emerald-500' : 'bg-amber-500'"></span>
                                    {{ c.statut }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <button @click="contactTenant(c)" class="px-4 py-2 bg-white border-2 border-slate-200 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 hover:border-slate-350 transition-all">Contacter</button>
                            </td>
                        </tr>
                        <tr v-if="contratsActifsList.length === 0">
                            <td colspan="6" class="px-6 py-8 text-center text-slate-400 italic">Aucun bail actif à afficher</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import { Chart } from 'chart.js/auto';

// KPIs reactive refs
const vacancyRate = ref(0);
const vacantCount = ref(0);
const totalLogementsCount = ref(0);

const monthlyRevenueActual = ref(0);
const monthlyRevenueExpected = ref(0);

const unpaidRate = ref(0);
const unpaidTotal = ref(0);

const chargesRecoveryRate = ref(0);
const chargesRecovered = ref(0);
const chargesTotal = ref(0);

const contratsActifsList = ref([]);

// Chart instance
let unpaidChartInstance = null;

const getInitials = (name) => {
    if (!name) return 'JD';
    const parts = name.trim().split(' ');
    if (parts.length >= 2) {
        return (parts[0][0] + parts[1][0]).toUpperCase();
    }
    return name.substring(0, 2).toUpperCase();
};

const getAvatarGradient = (name) => {
    if (!name) return 'from-blue-500 to-indigo-600';
    const colors = [
        'from-blue-550 to-indigo-600',
        'from-emerald-500 to-teal-600',
        'from-violet-500 to-purple-600',
        'from-amber-550 to-orange-600',
        'from-rose-500 to-red-650',
        'from-cyan-500 to-blue-600',
        'from-pink-500 to-rose-600'
    ];
    let hash = 0;
    for (let i = 0; i < name.length; i++) {
        hash = name.charCodeAt(i) + ((hash << 5) - hash);
    }
    const index = Math.abs(hash) % colors.length;
    return colors[index];
};

const formatCurrency = (val) => {
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR', maximumFractionDigits: 0 }).format(val);
};

const formatDate = (dateStr) => {
    if (!dateStr || dateStr === 'N/A') return 'N/A';
    const date = new Date(dateStr);
    return date.toLocaleDateString('fr-FR', { day: 'numeric', month: 'long', year: 'numeric' });
};

const contactTenant = (contrat) => {
    alert(`Contacter le locataire ${contrat.locataire} à l'adresse : ${contrat.email || 'Non renseignée'}`);
};

const renderCharts = (unpaidPeriodData) => {
    if (unpaidChartInstance) unpaidChartInstance.destroy();

    const unpaidCtx = document.getElementById('unpaidChart');
    if (unpaidCtx) {
        const gradientGreen = unpaidCtx.getContext('2d').createLinearGradient(0, 0, 0, 300);
        gradientGreen.addColorStop(0, 'rgba(16, 185, 129, 0.85)');
        gradientGreen.addColorStop(1, 'rgba(16, 185, 129, 0.25)');

        const gradientAmber = unpaidCtx.getContext('2d').createLinearGradient(0, 0, 0, 300);
        gradientAmber.addColorStop(0, 'rgba(245, 158, 11, 0.85)');
        gradientAmber.addColorStop(1, 'rgba(245, 158, 11, 0.25)');

        const gradientRed = unpaidCtx.getContext('2d').createLinearGradient(0, 0, 0, 300);
        gradientRed.addColorStop(0, 'rgba(239, 68, 68, 0.85)');
        gradientRed.addColorStop(1, 'rgba(239, 68, 68, 0.25)');

        unpaidChartInstance = new Chart(unpaidCtx, {
            type: 'bar',
            data: {
                labels: ['30j', '60j', '90j', '120j', '150j+'],
                datasets: [{
                    label: 'Montant Impayé',
                    data: unpaidPeriodData || [0, 0, 0, 0, 0],
                    backgroundColor: [
                        gradientGreen,
                        gradientGreen,
                        gradientAmber,
                        gradientAmber,
                        gradientRed
                    ],
                    borderColor: [
                        '#10b981',
                        '#10b981',
                        '#f59e0b',
                        '#f59e0b',
                        '#ef4444'
                    ],
                    borderWidth: 1.5,
                    borderRadius: 12,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.95)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(148, 163, 184, 0.15)',
                        borderWidth: 1,
                        cornerRadius: 12,
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.dataset.label + ' : €' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(148, 163, 184, 0.08)',
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                return '€' + (value / 1000) + 'k';
                            },
                            color: '#94a3b8',
                            font: { size: 10 }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: '#94a3b8',
                            font: { size: 10 }
                        }
                    }
                }
            }
        });
    }
};

const fetchStats = async () => {
    try {
        const response = await axios.get('/api/dashboard/stats');
        const data = response.data;

        vacancyRate.value = data.kpis.vacancy_rate;
        vacantCount.value = data.kpis.vacant_count;
        totalLogementsCount.value = data.kpis.count_logements;

        monthlyRevenueActual.value = data.kpis.revenue_actual;
        monthlyRevenueExpected.value = data.kpis.revenue_expected;

        unpaidRate.value = data.kpis.unpaid_rate;
        unpaidTotal.value = data.kpis.unpaid_invoices_total;

        chargesRecoveryRate.value = data.kpis.charges_recovery_rate;
        chargesRecovered.value = data.kpis.charges_recovered;
        chargesTotal.value = data.kpis.charges_total;

        contratsActifsList.value = data.active_contracts || [];

        renderCharts(data.unpaid_period_data);
    } catch (error) {
        console.error("Error fetching real-estate stats:", error);
    }
};

onMounted(() => {
    fetchStats();
});

onUnmounted(() => {
    if (unpaidChartInstance) unpaidChartInstance.destroy();
});
</script>
