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
                    <div class="flex items-center gap-1 px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 9L3 6m0 0l3-3m-3 3h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        +15.3%
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-800 mb-1">€441,300</div>
                <div class="text-sm text-slate-500 mb-4">Revenus du Mois</div>
                <div class="text-xs text-slate-400">vs €382,800 le mois dernier</div>
            </div>

            <!-- Expenses Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 6H3m0 0l4-4m-4 4l4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        +8.7%
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-800 mb-1">€287,500</div>
                <div class="text-sm text-slate-500 mb-4">Dépenses du Mois</div>
                <div class="text-xs text-slate-400">vs €264,300 le mois dernier</div>
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
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 9L3 6m0 0l3-3m-3 3h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        +24.1%
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-800 mb-1">€153,800</div>
                <div class="text-sm text-slate-500 mb-4">Bénéfice Net</div>
                <div class="text-xs text-slate-400">Marge: 34.8%</div>
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
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 6H3m0 0l4-4m-4 4l4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        8 en attente
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-800 mb-1">€42,500</div>
                <div class="text-sm text-slate-500 mb-4">Factures en Attente</div>
                <div class="text-xs text-slate-400">3 en retard (>30j)</div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Revenue vs Expenses -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-slate-800">Revenus vs Dépenses</h3>
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
                <button class="px-4 py-2 bg-blue-500 text-white rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors shadow-md shadow-blue-500/20">Voir toutes</button>
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
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr>
                            <td class="px-4 py-4 text-sm text-slate-600">15 Juin 2026</td>
                            <td class="px-4 py-4 text-sm text-slate-600">Loyer Immeuble A - Apt 204</td>
                            <td class="px-4 py-4"><span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">Revenu</span></td>
                            <td class="px-4 py-4 text-sm font-semibold text-emerald-600">+€1,200</td>
                            <td class="px-4 py-4"><span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">Payé</span></td>
                        </tr>
                        <tr>
                            <td class="px-4 py-4 text-sm text-slate-600">15 Juin 2026</td>
                            <td class="px-4 py-4 text-sm text-slate-600">Facture Électricité - Mai</td>
                            <td class="px-4 py-4"><span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Dépense</span></td>
                            <td class="px-4 py-4 text-sm font-semibold text-red-600">-€2,450</td>
                            <td class="px-4 py-4"><span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">Payé</span></td>
                        </tr>
                        <tr class="bg-amber-50">
                            <td class="px-4 py-4 text-sm text-slate-600">14 Juin 2026</td>
                            <td class="px-4 py-4 text-sm text-slate-600">Loyer Immeuble B - Bureau 102</td>
                            <td class="px-4 py-4"><span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">Revenu</span></td>
                            <td class="px-4 py-4 text-sm font-semibold text-emerald-600">+€2,500</td>
                            <td class="px-4 py-4"><span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-semibold">En attente</span></td>
                        </tr>
                        <tr>
                            <td class="px-4 py-4 text-sm text-slate-600">14 Juin 2026</td>
                            <td class="px-4 py-4 text-sm text-slate-600">Maintenance Ascenseur A</td>
                            <td class="px-4 py-4"><span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Dépense</span></td>
                            <td class="px-4 py-4 text-sm font-semibold text-red-600">-€850</td>
                            <td class="px-4 py-4"><span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">Payé</span></td>
                        </tr>
                        <tr>
                            <td class="px-4 py-4 text-sm text-slate-600">13 Juin 2026</td>
                            <td class="px-4 py-4 text-sm text-slate-600">Réservation Hôtel - Suite 402</td>
                            <td class="px-4 py-4"><span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">Revenu</span></td>
                            <td class="px-4 py-4 text-sm font-semibold text-emerald-600">+€890</td>
                            <td class="px-4 py-4"><span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">Payé</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pending Invoices -->
        <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-slate-800">Factures en Attente</h3>
                <button class="px-4 py-2 bg-blue-500 text-white rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors shadow-md shadow-blue-500/20">Gérer</button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="flex flex-col gap-3 p-4 bg-amber-50 rounded-xl border border-amber-200">
                    <div class="flex items-center justify-between">
                        <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-sm">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-amber-500">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" fill="currentColor"/>
                            </svg>
                        </div>
                        <div class="text-xs font-semibold text-amber-600">En retard</div>
                    </div>
                    <div>
                        <div class="font-semibold text-slate-800">INV-2026-089</div>
                        <div class="text-xs text-slate-600">Loyer Immeuble C</div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-slate-800">€950</span>
                        <span class="text-xs text-slate-500">45j</span>
                    </div>
                    <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-100 transition-colors">Relancer</button>
                </div>

                <div class="flex flex-col gap-3 p-4 bg-amber-50 rounded-xl border border-amber-200">
                    <div class="flex items-center justify-between">
                        <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-sm">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-amber-500">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" fill="currentColor"/>
                            </svg>
                        </div>
                        <div class="text-xs font-semibold text-amber-600">En retard</div>
                    </div>
                    <div>
                        <div class="font-semibold text-slate-800">INV-2026-087</div>
                        <div class="text-xs text-slate-600">Loyer Immeuble D</div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-slate-800">€3,200</span>
                        <span class="text-xs text-slate-500">38j</span>
                    </div>
                    <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-100 transition-colors">Relancer</button>
                </div>

                <div class="flex flex-col gap-3 p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <div class="flex items-center justify-between">
                        <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-sm">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-slate-500">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" fill="currentColor"/>
                            </svg>
                        </div>
                        <div class="text-xs font-semibold text-slate-600">En attente</div>
                    </div>
                    <div>
                        <div class="font-semibold text-slate-800">INV-2026-092</div>
                        <div class="text-xs text-slate-600">Loyer Immeuble B</div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-slate-800">€2,500</span>
                        <span class="text-xs text-slate-500">5j</span>
                    </div>
                    <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-100 transition-colors">Voir</button>
                </div>

                <div class="flex flex-col gap-3 p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <div class="flex items-center justify-between">
                        <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-sm">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-slate-500">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" fill="currentColor"/>
                            </svg>
                        </div>
                        <div class="text-xs font-semibold text-slate-600">En attente</div>
                    </div>
                    <div>
                        <div class="font-semibold text-slate-800">INV-2026-093</div>
                        <div class="text-xs text-slate-600">Loyer Immeuble A</div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-slate-800">€1,100</span>
                        <span class="text-xs text-slate-500">3j</span>
                    </div>
                    <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-100 transition-colors">Voir</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Chart } from 'chart.js/auto';

const revenueExpensesChart = ref(null);
const expenseBreakdownChart = ref(null);

onMounted(() => {
    // Revenue vs Expenses Chart - Grouped Bar Chart
    const revenueCtx = document.getElementById('revenueExpensesChart');
    if (revenueCtx) {
        const gradientRevenue = revenueCtx.getContext('2d').createLinearGradient(0, 0, 0, 400);
        gradientRevenue.addColorStop(0, 'rgba(16, 185, 129, 0.8)');
        gradientRevenue.addColorStop(1, 'rgba(16, 185, 129, 0.3)');

        const gradientExpenses = revenueCtx.getContext('2d').createLinearGradient(0, 0, 0, 400);
        gradientExpenses.addColorStop(0, 'rgba(239, 68, 68, 0.8)');
        gradientExpenses.addColorStop(1, 'rgba(239, 68, 68, 0.3)');

        revenueExpensesChart.value = new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai'],
                datasets: [
                    {
                        label: 'Revenus',
                        data: [380000, 395000, 410000, 425000, 441300],
                        backgroundColor: gradientRevenue,
                        borderColor: '#10b981',
                        borderWidth: 2,
                        borderRadius: 6
                    },
                    {
                        label: 'Dépenses',
                        data: [264000, 270000, 275000, 280000, 287500],
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
                            font: {
                                size: 12,
                                weight: 500
                            }
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
                        grid: {
                            color: 'rgba(148, 163, 184, 0.1)',
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                return '€' + (value / 1000) + 'k';
                            },
                            color: '#64748b',
                            font: {
                                size: 11
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#64748b',
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });
    }

    // Expense Breakdown Chart - Doughnut Chart
    const expenseCtx = document.getElementById('expenseBreakdownChart');
    if (expenseCtx) {
        expenseBreakdownChart.value = new Chart(expenseCtx, {
            type: 'doughnut',
            data: {
                labels: ['Salaires', 'Maintenance', 'Énergie', 'Autres'],
                datasets: [{
                    data: [45, 20, 15, 20],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.9)',
                        'rgba(245, 158, 11, 0.9)',
                        'rgba(16, 185, 129, 0.9)',
                        'rgba(100, 116, 139, 0.9)'
                    ],
                    borderColor: [
                        '#3b82f6',
                        '#f59e0b',
                        '#10b981',
                        '#64748b'
                    ],
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
                            font: {
                                size: 12,
                                weight: 500
                            }
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
                                return context.label + ': ' + context.parsed + '%';
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    }
});

onUnmounted(() => {
    if (revenueExpensesChart.value) revenueExpensesChart.value.destroy();
    if (expenseBreakdownChart.value) expenseBreakdownChart.value.destroy();
});
</script>
