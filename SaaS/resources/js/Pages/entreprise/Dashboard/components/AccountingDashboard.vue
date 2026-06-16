<template>
    <div class="flex flex-col gap-8">
        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Revenue Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div :class="[
                        'flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold',
                        revenueChangePercent >= 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700'
                    ]">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path :d="revenueChangePercent >= 0 ? 'M6 3L3 6m0 0l3 3m-3-3h6' : 'M6 9L3 6m0 0l3-3m-3 3h6'" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        {{ revenueChangePercent >= 0 ? '+' : '' }}{{ revenueChangePercent }}%
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-800 mb-1">{{ formatCurrency(currentMonthRevenue) }}</div>
                <div class="text-sm text-slate-500 mb-4">Revenus du Mois</div>
                <div class="text-xs text-slate-400">vs {{ formatCurrency(lastMonthRevenue) }} le mois dernier</div>
            </div>

            <!-- Expenses Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div :class="[
                        'flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold',
                        expensesChangePercent >= 0 ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700'
                    ]">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path :d="expensesChangePercent >= 0 ? 'M6 3L3 6m0 0l3 3m-3-3h6' : 'M6 9L3 6m0 0l3-3m-3 3h6'" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        {{ expensesChangePercent >= 0 ? '+' : '' }}{{ expensesChangePercent }}%
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-800 mb-1">{{ formatCurrency(currentMonthExpenses) }}</div>
                <div class="text-sm text-slate-500 mb-4">Dépenses du Mois</div>
                <div class="text-xs text-slate-400">vs {{ formatCurrency(lastMonthExpenses) }} le mois dernier</div>
            </div>

            <!-- Profit Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">
                        Marge : {{ profitMargin }}%
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-800 mb-1">{{ formatCurrency(netProfit) }}</div>
                <div class="text-sm text-slate-500 mb-4">Bénéfice Net</div>
                <div class="text-xs text-slate-400">Revenus de l'année en cours</div>
            </div>

            <!-- Pending Invoices Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100 border-l-4 border-l-amber-500">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-semibold">
                        {{ pendingInvoicesCount }} en attente
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-800 mb-1">{{ formatCurrency(pendingInvoicesAmount) }}</div>
                <div class="text-sm text-slate-500 mb-4">Factures en Attente</div>
                <div class="text-xs text-slate-400">{{ lateInvoicesCount }} en retard (>30j)</div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Revenue vs Expenses -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-slate-800">Revenus vs Dépenses (Année en cours)</h3>
                </div>
                <div class="h-64">
                    <canvas id="revenueExpensesChart"></canvas>
                </div>
            </div>

            <!-- Expense Breakdown -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-slate-800">Répartition des Dépenses</h3>
                </div>
                <div class="h-64">
                    <canvas id="expenseBreakdownChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-slate-800">Transactions Récentes</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Description</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Catégorie</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Montant</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Statut</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Agence</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="(tx, index) in recentTransactions" :key="index" :class="{'bg-slate-50/40': index % 2 === 1}">
                            <td class="px-4 py-4 text-sm text-slate-600">{{ formatDate(tx.date_transaction) }}</td>
                            <td class="px-4 py-4 text-sm text-slate-700 font-medium">{{ tx.motif || 'Flux financier' }}</td>
                            <td class="px-4 py-4">
                                <span :class="[
                                    'px-3 py-1 rounded-full text-xs font-semibold',
                                    tx.montant > 0 ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700'
                                ]">
                                    {{ tx.montant > 0 ? 'Revenu' : 'Dépense' }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-sm font-semibold" :class="tx.montant > 0 ? 'text-emerald-600' : 'text-red-650'">
                                {{ tx.montant > 0 ? '+' : '' }}{{ formatCurrency(tx.montant) }}
                            </td>
                            <td class="px-4 py-4">
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">Payé</span>
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-650 font-medium">
                                {{ tx.agency ? tx.agency.name : 'Siège' }}
                            </td>
                        </tr>
                        <tr v-if="recentTransactions.length === 0">
                            <td colspan="6" class="px-4 py-8 text-center text-slate-400 italic">Aucune transaction enregistrée</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pending Invoices -->
        <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-slate-800">Factures en Attente</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div v-for="inv in activePendingInvoicesList" :key="inv.id" class="flex flex-col gap-3 p-4 bg-amber-50 rounded-xl border border-amber-200">
                    <div class="flex items-center justify-between">
                        <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-sm">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-amber-500">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" fill="currentColor"/>
                            </svg>
                        </div>
                        <div class="text-xs font-semibold text-amber-600" v-if="inv.statut === 'En retard'">En retard</div>
                        <div class="text-xs font-semibold text-amber-600" v-else>À percevoir</div>
                    </div>
                    <div>
                        <div class="font-semibold text-slate-800">{{ inv.numero }}</div>
                        <div class="text-xs text-slate-650">Locataire : {{ inv.locataire ? (inv.locataire.user ? inv.locataire.user.name : inv.locataire.nom) : 'Inconnu' }}</div>
                        <div class="text-[10px] text-slate-500" v-if="inv.agency">Agence : {{ inv.agency.name }}</div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-slate-800">{{ formatCurrency(Number(inv.total) - Number(inv.montant_paye || 0)) }}</span>
                        <span class="text-xs text-slate-500">Échéance: {{ formatDateShort(inv.date_echeance) }}</span>
                    </div>
                    <button @click="relaunchInvoice(inv)" class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-650 hover:bg-slate-100 transition-colors">Relancer</button>
                </div>
                <div v-if="activePendingInvoicesList.length === 0" class="col-span-full py-6 text-center text-slate-400 italic">
                    Aucune facture en attente de paiement
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import { Chart } from 'chart.js/auto';

// KPIs reactive refs
const currentMonthRevenue = ref(0);
const lastMonthRevenue = ref(0);
const revenueChangePercent = ref(0);

const currentMonthExpenses = ref(0);
const lastMonthExpenses = ref(0);
const expensesChangePercent = ref(0);

const netProfit = ref(0);
const profitMargin = ref(0);

const pendingInvoicesAmount = ref(0);
const pendingInvoicesCount = ref(0);
const lateInvoicesCount = ref(0);

const recentTransactions = ref([]);
const activePendingInvoicesList = ref([]);

// Charts instances
let revenueExpensesChartInstance = null;
let expenseBreakdownChartInstance = null;

const formatCurrency = (val) => {
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR', maximumFractionDigits: 0 }).format(val);
};

const formatDate = (dateStr) => {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return date.toLocaleDateString('fr-FR', { day: 'numeric', month: 'long', year: 'numeric' });
};

const formatDateShort = (dateStr) => {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return date.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short' });
};

const relaunchInvoice = (invoice) => {
    alert(`Relance envoyée avec succès pour la facture ${invoice.numero}`);
};

const renderCharts = (revExpData, expensesByType) => {
    if (revenueExpensesChartInstance) revenueExpensesChartInstance.destroy();
    if (expenseBreakdownChartInstance) expenseBreakdownChartInstance.destroy();

    const revenueCtx = document.getElementById('revenueExpensesChart');
    if (revenueCtx && revExpData) {
        const gradientRevenue = revenueCtx.getContext('2d').createLinearGradient(0, 0, 0, 400);
        gradientRevenue.addColorStop(0, 'rgba(16, 185, 129, 0.8)');
        gradientRevenue.addColorStop(1, 'rgba(16, 185, 129, 0.3)');

        const gradientExpenses = revenueCtx.getContext('2d').createLinearGradient(0, 0, 0, 400);
        gradientExpenses.addColorStop(0, 'rgba(239, 68, 68, 0.8)');
        gradientExpenses.addColorStop(1, 'rgba(239, 68, 68, 0.3)');

        const labels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];

        revenueExpensesChartInstance = new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Revenus',
                        data: revExpData.revenues || Array(12).fill(0),
                        backgroundColor: gradientRevenue,
                        borderColor: '#10b981',
                        borderWidth: 2,
                        borderRadius: 6
                    },
                    {
                        label: 'Dépenses',
                        data: revExpData.expenses || Array(12).fill(0),
                        backgroundColor: gradientExpenses,
                        borderColor: '#ef4444',
                        borderWidth: 2,
                        borderRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            color: '#64748b',
                            font: { size: 12, weight: 500 }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(148, 163, 184, 0.2)',
                        borderWidth: 1,
                        cornerRadius: 12,
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': €' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(148, 163, 184, 0.1)', drawBorder: false },
                        ticks: {
                            callback: function(value) { return '€' + (value / 1000) + 'k'; },
                            color: '#64748b',
                            font: { size: 11 }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#64748b', font: { size: 11 } }
                    }
                }
            }
        });
    }

    const expenseCtx = document.getElementById('expenseBreakdownChart');
    if (expenseCtx && expensesByType) {
        const categories = Object.keys(expensesByType);
        const amounts = Object.values(expensesByType);

        const labels = categories.length > 0 ? categories : ['Aucune dépense'];
        const data = amounts.length > 0 ? amounts : [0];

        const bgColors = [
            'rgba(59, 130, 246, 0.9)',
            'rgba(245, 158, 11, 0.9)',
            'rgba(16, 185, 129, 0.9)',
            'rgba(139, 92, 246, 0.9)',
            'rgba(236, 72, 153, 0.9)',
            'rgba(100, 116, 139, 0.9)'
        ];
        const borderColors = [
            '#3b82f6',
            '#f59e0b',
            '#10b981',
            '#8b5cf6',
            '#ec4899',
            '#64748b'
        ];

        expenseBreakdownChartInstance = new Chart(expenseCtx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: bgColors.slice(0, labels.length),
                    borderColor: borderColors.slice(0, labels.length),
                    borderWidth: 3,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            color: '#64748b',
                            font: { size: 12, weight: 500 }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(148, 163, 184, 0.2)',
                        borderWidth: 1,
                        cornerRadius: 12,
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                return context.label + ': €' + context.parsed.toLocaleString();
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    }
};

const fetchStats = async () => {
    try {
        const response = await axios.get('/api/dashboard/stats');
        const data = response.data;

        currentMonthRevenue.value = data.kpis.revenue_actual;
        lastMonthRevenue.value = data.kpis.revenue_last_month;
        revenueChangePercent.value = data.kpis.revenue_change_percent;

        currentMonthExpenses.value = data.kpis.expenses_actual;
        lastMonthExpenses.value = data.kpis.expenses_last_month;
        expensesChangePercent.value = data.kpis.expenses_change_percent;

        netProfit.value = data.kpis.profit_actual;
        profitMargin.value = data.kpis.profit_margin;

        pendingInvoicesAmount.value = data.kpis.unpaid_invoices_total;
        pendingInvoicesCount.value = data.kpis.unpaid_invoices_count;
        lateInvoicesCount.value = data.kpis.unpaid_invoices_overdue_count;

        recentTransactions.value = data.recent_transactions || [];
        activePendingInvoicesList.value = (data.unpaid_invoices || []).slice(0, 4);

        renderCharts(data.chart_revenue_expenses, data.chart_expenses_by_type);
    } catch (error) {
        console.error("Error fetching accounting stats:", error);
    }
};

onMounted(() => {
    fetchStats();
});

onUnmounted(() => {
    if (revenueExpensesChartInstance) revenueExpensesChartInstance.destroy();
    if (expenseBreakdownChartInstance) expenseBreakdownChartInstance.destroy();
});
</script>
