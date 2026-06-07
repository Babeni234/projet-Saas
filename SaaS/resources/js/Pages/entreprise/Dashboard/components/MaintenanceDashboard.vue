<template>
    <div class="flex flex-col gap-8">
        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Open Tickets Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 9L3 6m0 0l3-3m-3 3h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        -2
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-800 mb-1">12</div>
                <div class="text-sm text-slate-500 mb-4">Tickets Ouverts</div>
                <div class="text-xs text-slate-400">vs 14 la semaine dernière</div>
            </div>

            <!-- Critical Issues Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100 border-l-4 border-l-red-500">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 6H3m0 0l4-4m-4 4l4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        +1
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-800 mb-1">3</div>
                <div class="text-sm text-slate-500 mb-4">Problèmes Critiques</div>
                <div class="text-xs text-slate-400">Intervention immédiate requise</div>
            </div>

            <!-- Avg Resolution Time Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 9L3 6m0 0l3-3m-3 3h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        -15%
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-800 mb-1">2.4j</div>
                <div class="text-sm text-slate-500 mb-4">Temps de Résolution Moyen</div>
                <div class="text-xs text-slate-400">vs 2.8j le mois dernier</div>
            </div>

            <!-- Maintenance Budget Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-semibold">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 6H3m0 0l4-4m-4 4l4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        78%
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-800 mb-1">€18,500</div>
                <div class="text-sm text-slate-500 mb-4">Budget Maintenance (Mensuel)</div>
                <div class="text-xs text-slate-400">Utilisé: €14,400</div>
            </div>
        </div>

        <!-- Critical Issues Section -->
        <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-slate-800">Problèmes Critiques</h3>
                <button class="px-4 py-2 bg-red-500 text-white rounded-lg text-sm font-medium hover:bg-red-600 transition-colors shadow-md shadow-red-500/20">Voir tous</button>
            </div>
            <div class="space-y-3">
                <div class="flex items-center gap-4 p-4 bg-red-50 rounded-xl border-l-4 border-red-500">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-red-500">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" fill="currentColor"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold text-slate-800">Ascenseur en panne - Immeuble A</div>
                        <div class="text-sm text-slate-600">Depuis 2 jours - Impact sur 12 locataires</div>
                    </div>
                    <button class="px-4 py-2 bg-blue-500 text-white rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors shadow-md shadow-blue-500/20">Gérer</button>
                </div>

                <div class="flex items-center gap-4 p-4 bg-red-50 rounded-xl border-l-4 border-red-500">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-red-500">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" fill="currentColor"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold text-slate-800">Fuite d'eau - Suite 305</div>
                        <div class="text-sm text-slate-600">Hôtel Principal - Chambre indisponible</div>
                    </div>
                    <button class="px-4 py-2 bg-blue-500 text-white rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors shadow-md shadow-blue-500/20">Gérer</button>
                </div>

                <div class="flex items-center gap-4 p-4 bg-red-50 rounded-xl border-l-4 border-red-500">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-red-500">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" fill="currentColor"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold text-slate-800">Climatisation défaillante - Bureau 204</div>
                        <div class="text-sm text-slate-600">Immeuble B - Intervention programmée demain</div>
                    </div>
                    <button class="px-4 py-2 bg-blue-500 text-white rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors shadow-md shadow-blue-500/20">Gérer</button>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Tickets by Category -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-slate-800">Tickets par Catégorie</h3>
                </div>
                <div class="h-64">
                    <canvas id="ticketsCategoryChart"></canvas>
                </div>
            </div>

            <!-- Resolution Time Trend -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-slate-800">Temps de Résolution</h3>
                </div>
                <div class="h-64">
                    <canvas id="resolutionTimeChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Tickets -->
        <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-slate-800">Tickets Récents</h3>
                <button class="px-4 py-2 bg-blue-500 text-white rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors shadow-md shadow-blue-500/20">Voir tous</button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">ID</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Description</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Lieu</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Priorité</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Statut</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr class="bg-red-50">
                            <td class="px-4 py-4 text-sm text-slate-600">#MT-089</td>
                            <td class="px-4 py-4 text-sm text-slate-600">Ascenseur en panne</td>
                            <td class="px-4 py-4 text-sm text-slate-600">Immeuble A</td>
                            <td class="px-4 py-4"><span class="px-3 py-1 bg-red-500 text-white rounded-full text-xs font-semibold">Critique</span></td>
                            <td class="px-4 py-4"><span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-semibold">En cours</span></td>
                            <td class="px-4 py-4">
                                <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">Voir</button>
                            </td>
                        </tr>
                        <tr class="bg-red-50">
                            <td class="px-4 py-4 text-sm text-slate-600">#MT-088</td>
                            <td class="px-4 py-4 text-sm text-slate-600">Fuite d'eau</td>
                            <td class="px-4 py-4 text-sm text-slate-600">Hôtel - Suite 305</td>
                            <td class="px-4 py-4"><span class="px-3 py-1 bg-red-500 text-white rounded-full text-xs font-semibold">Critique</span></td>
                            <td class="px-4 py-4"><span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">Assigné</span></td>
                            <td class="px-4 py-4">
                                <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">Voir</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-4 text-sm text-slate-600">#MT-087</td>
                            <td class="px-4 py-4 text-sm text-slate-600">Climatisation défaillante</td>
                            <td class="px-4 py-4 text-sm text-slate-600">Immeuble B - Bureau 204</td>
                            <td class="px-4 py-4"><span class="px-3 py-1 bg-amber-500 text-white rounded-full text-xs font-semibold">Haute</span></td>
                            <td class="px-4 py-4"><span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">Assigné</span></td>
                            <td class="px-4 py-4">
                                <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">Voir</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-4 text-sm text-slate-600">#MT-086</td>
                            <td class="px-4 py-4 text-sm text-slate-600">Remplacement ampoule</td>
                            <td class="px-4 py-4 text-sm text-slate-600">Immeuble A - Couloir 3</td>
                            <td class="px-4 py-4"><span class="px-3 py-1 bg-slate-500 text-white rounded-full text-xs font-semibold">Normale</span></td>
                            <td class="px-4 py-4"><span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">Résolu</span></td>
                            <td class="px-4 py-4">
                                <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">Voir</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-4 text-sm text-slate-600">#MT-085</td>
                            <td class="px-4 py-4 text-sm text-slate-600">Nettoyage gouttières</td>
                            <td class="px-4 py-4 text-sm text-slate-600">Immeuble C</td>
                            <td class="px-4 py-4"><span class="px-3 py-1 bg-slate-500 text-white rounded-full text-xs font-semibold">Normale</span></td>
                            <td class="px-4 py-4"><span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">Résolu</span></td>
                            <td class="px-4 py-4">
                                <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">Voir</button>
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

const ticketsCategoryChart = ref(null);
const resolutionTimeChart = ref(null);

onMounted(() => {
    // Tickets Category Chart - Doughnut Chart
    const categoryCtx = document.getElementById('ticketsCategoryChart');
    if (categoryCtx) {
        ticketsCategoryChart.value = new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: ['Plomberie', 'Électricité', 'CVC', 'Autres'],
                datasets: [{
                    data: [35, 25, 20, 20],
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

    // Resolution Time Chart - Line Chart with Gradient
    const resolutionCtx = document.getElementById('resolutionTimeChart');
    if (resolutionCtx) {
        const gradient = resolutionCtx.getContext('2d').createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.5)');
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

        resolutionTimeChart.value = new Chart(resolutionCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
                datasets: [{
                    label: 'Temps de Résolution (jours)',
                    data: [3.2, 3.0, 2.8, 2.6, 2.5, 2.4],
                    borderColor: '#3b82f6',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 8,
                    pointShadowColor: 'rgba(59, 130, 246, 0.5)',
                    pointShadowBlur: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
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
                                return context.parsed.y + ' jours';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        min: 2,
                        max: 4,
                        grid: {
                            color: 'rgba(148, 163, 184, 0.1)',
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                return value + 'j';
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
});

onUnmounted(() => {
    if (ticketsCategoryChart.value) ticketsCategoryChart.value.destroy();
    if (resolutionTimeChart.value) resolutionTimeChart.value.destroy();
});
</script>
