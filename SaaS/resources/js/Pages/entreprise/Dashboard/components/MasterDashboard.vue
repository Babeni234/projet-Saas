<template>
    <div class="flex flex-col gap-8">
        <!-- Ultra Premium Banner -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 shadow-2xl">
            <!-- Animated background elements -->
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-indigo-500/20 to-violet-500/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                <div class="absolute bottom-0 left-0 w-80 h-80 bg-gradient-to-tr from-emerald-500/10 to-cyan-500/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/4"></div>
            </div>

            <!-- Content -->
            <div class="relative px-6 py-12 sm:px-8 lg:px-12 lg:py-16">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                    <!-- Left section -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 shadow-lg shadow-indigo-500/40">
                                <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl sm:text-3xl font-bold text-white">Tableau de Bord Enterprise</h2>
                                <p class="text-sm text-slate-300 mt-1">Gestion intégrée de votre plateforme</p>
                            </div>
                        </div>
                        <p class="text-slate-400 text-sm sm:text-base max-w-md">Suivez en temps réel les performances globales avec des métriques détaillées et des analyses approfondies.</p>
                    </div>

                    <!-- Right section - Stats -->
                    <div class="flex gap-4 flex-wrap lg:flex-nowrap lg:gap-6" v-if="!loading">
                        <div class="flex flex-col items-center justify-center px-4 py-3 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10 min-w-[100px]">
                            <span class="text-2xl sm:text-3xl font-bold text-emerald-400">{{ kpis.count_agencies_or_employees }}</span>
                            <span class="text-xs sm:text-sm text-slate-400 mt-1 font-medium">Agences</span>
                        </div>
                        <div class="flex flex-col items-center justify-center px-4 py-3 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10 min-w-[100px]">
                            <span class="text-2xl sm:text-3xl font-bold text-blue-400">{{ kpis.count_buildings }}</span>
                            <span class="text-xs sm:text-sm text-slate-400 mt-1 font-medium">Immeubles</span>
                        </div>
                        <div class="flex flex-col items-center justify-center px-4 py-3 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10 min-w-[100px]">
                            <span class="text-2xl sm:text-3xl font-bold text-amber-400">{{ kpis.count_contracts }}</span>
                            <span class="text-xs sm:text-sm text-slate-400 mt-1 font-medium">Baux Actifs</span>
                        </div>
                    </div>
                </div>

                <!-- Bottom accent line -->
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-indigo-500 via-violet-500 to-emerald-500 opacity-60"></div>
            </div>
        </div>

        <div v-if="loading" class="flex flex-col items-center justify-center py-20 gap-3">
            <span class="animate-spin h-10 w-10 border-4 border-violet-600 border-t-transparent rounded-full"></span>
            <p class="text-slate-500 text-sm font-semibold">Chargement des données en cours...</p>
        </div>

        <template v-else>
            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Revenue Card -->
                <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="text-white">
                                <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="flex items-center gap-1 px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">
                            CA Global
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-slate-800 mb-1">{{ formatCurrency(kpis.total_revenue) }}</div>
                    <div class="text-sm text-slate-500 mb-4">Chiffre d'Affaires Global</div>
                    <div class="pt-4 border-t border-slate-100 space-y-2">
                        <div class="flex items-center justify-between text-xs text-slate-600">
                            <span>Revenus mensuels :</span>
                            <strong class="text-slate-800">{{ formatCurrency(kpis.revenue_actual) }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Occupancy Card -->
                <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/30">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="text-white">
                                <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="flex items-center gap-1 px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">
                            Taux
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-slate-800 mb-1">{{ kpis.occupancy_rate }}%</div>
                    <div class="text-sm text-slate-500 mb-4">Taux d'Occupation</div>
                    <div class="pt-4 border-t border-slate-100 space-y-2">
                        <div class="flex items-center justify-between text-xs text-slate-600">
                            <span>Loués / Total :</span>
                            <strong class="text-slate-800">{{ kpis.occupied_count }} / {{ kpis.count_logements }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Cashflow Card -->
                <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="text-white">
                                <path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="flex items-center gap-1 px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">
                            Solde Net
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-slate-800 mb-1" :class="kpis.cashflow_net >= 0 ? 'text-emerald-600' : 'text-rose-600'">
                        {{ formatCurrency(kpis.cashflow_net) }}
                    </div>
                    <div class="text-sm text-slate-500 mb-4">Encaissements Nets</div>
                    <div class="pt-4 border-t border-slate-100 space-y-2">
                        <div class="flex items-center justify-between text-xs text-slate-600">
                            <span>Charges Réelles :</span>
                            <strong class="text-slate-800">{{ formatCurrency(kpis.total_expenses) }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Pending Card -->
                <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100" :class="{ 'border-l-4 border-l-amber-500': pendingExpenses.length > 0 }">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/30">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="text-white">
                                <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="flex items-center gap-1 px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-semibold" v-if="pendingExpenses.length > 0">
                            Action Requise
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-slate-800 mb-1">{{ pendingExpenses.length }}</div>
                    <div class="text-sm text-slate-500 mb-4">Dépenses en attente</div>
                    <div class="pt-4 border-t border-slate-100 space-y-2">
                        <div class="flex items-center justify-between text-xs text-slate-600">
                            <span>Factures impayées :</span>
                            <strong class="text-slate-850 font-bold">{{ unpaidInvoices.length }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Revenue Chart -->
                <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-slate-800">Évolution Recettes vs Dépenses ({{ currentYear }})</h3>
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-emerald-500 shadow-lg shadow-emerald-500/50"></span>
                                <span class="text-xs text-slate-600 font-medium">Recettes (Encaissements)</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-rose-500 shadow-lg shadow-rose-500/50"></span>
                                <span class="text-xs text-slate-600 font-medium">Dépenses (Payées)</span>
                            </div>
                        </div>
                    </div>
                    <div class="h-64">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <!-- Occupation Pie Chart -->
                <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100 flex flex-col justify-between">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-slate-800">Répartition des charges par type</h3>
                    </div>
                    <div class="h-48 flex items-center justify-center relative mb-4">
                        <canvas id="distributionChart"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none" v-if="Object.keys(chartExpensesByType).length === 0">
                            <p class="text-slate-400 text-xs italic">Aucune charge enregistrée</p>
                        </div>
                    </div>
                    <!-- Detailed Breakdown -->
                    <div class="space-y-2 max-h-40 overflow-y-auto custom-scrollbar" v-if="Object.keys(chartExpensesByType).length > 0">
                        <div v-for="(amount, type, index) in chartExpensesByType" :key="type" class="flex items-center justify-between p-2 rounded-xl bg-slate-50 border border-slate-100 hover:bg-slate-100/50 transition-all duration-200">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="w-3.5 h-3.5 rounded-full shrink-0" :style="{ backgroundColor: getChartColor(index) }"></span>
                                <span class="text-xs font-bold text-slate-700 truncate">{{ type }}</span>
                            </div>
                            <div class="flex items-center gap-3 shrink-0">
                                <span class="text-xs font-medium text-slate-500">{{ getPercentage(amount) }}%</span>
                                <span class="text-xs font-bold text-slate-800">{{ formatCurrency(amount) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Validation and Unpaid sections -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Unpaid Invoices Section -->
                <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100 flex flex-col">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-bold text-slate-800">Factures non réglées</h3>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-100">
                            {{ unpaidInvoices.length }} à régulariser
                        </span>
                    </div>
                    <div class="overflow-x-auto flex-1">
                        <table class="min-w-full divide-y divide-slate-100" v-if="unpaidInvoices.length > 0">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Réf</th>
                                    <th class="px-4 py-2 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Locataire</th>
                                    <th class="px-4 py-2 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Agence</th>
                                    <th class="px-4 py-2 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Total</th>
                                    <th class="px-4 py-2 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Statut</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                <tr v-for="facture in unpaidInvoices.slice(0, 5)" :key="facture.id" class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-4 py-3 whitespace-nowrap text-xs font-semibold text-slate-900">{{ facture.numero }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-xs text-slate-700">{{ facture.locataire?.nom || 'N/A' }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-xs text-slate-500">
                                        <span v-if="facture.agency" class="inline-flex items-center rounded-lg bg-blue-50 px-2 py-0.5 text-[10px] font-bold text-blue-700 border border-blue-100">
                                            {{ facture.agency.name }}
                                        </span>
                                        <span v-else class="inline-flex items-center rounded-lg bg-slate-50 px-2 py-0.5 text-[10px] font-bold text-slate-600 border border-slate-100">
                                            Siège
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-xs text-right font-bold text-slate-900">{{ formatCurrency(facture.total) }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-bold bg-amber-100 text-amber-800">
                                            {{ facture.statut }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="flex flex-col items-center justify-center py-8 text-center" v-else>
                            <svg class="h-8 w-8 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-xs text-slate-400 font-medium">Toutes les factures ont été réglées !</p>
                        </div>
                    </div>
                </div>

                <!-- Pending Expenses Validation Section -->
                <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100 flex flex-col">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-bold text-slate-800">Dépenses en attente de validation</h3>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-100">
                            {{ pendingExpenses.length }} à valider
                        </span>
                    </div>
                    <div class="overflow-x-auto flex-1">
                        <table class="min-w-full divide-y divide-slate-100" v-if="pendingExpenses.length > 0">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Date</th>
                                    <th class="px-4 py-2 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Titre</th>
                                    <th class="px-4 py-2 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Agence</th>
                                    <th class="px-4 py-2 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Montant</th>
                                    <th class="px-4 py-2 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                <tr v-for="depense in pendingExpenses.slice(0, 5)" :key="depense.id" class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-4 py-3 whitespace-nowrap text-xs text-slate-600">{{ formatDate(depense.date_depense) }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-xs font-semibold text-slate-900">{{ depense.titre }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-xs text-slate-500">
                                        <span v-if="depense.agency" class="inline-flex items-center rounded-lg bg-blue-50 px-2 py-0.5 text-[10px] font-bold text-blue-700 border border-blue-100">
                                            {{ depense.agency.name }}
                                        </span>
                                        <span v-else class="inline-flex items-center rounded-lg bg-slate-50 px-2 py-0.5 text-[10px] font-bold text-slate-600 border border-slate-100">
                                            Siège
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-xs text-right font-bold text-slate-900">{{ formatCurrency(depense.montant) }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-center">
                                        <router-link :to="{ name: 'accounting.depenses' }" class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-bold bg-violet-600 text-white rounded-lg hover:bg-violet-700 hover:scale-[1.02] transition-all">
                                            Traiter
                                        </router-link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="flex flex-col items-center justify-center py-8 text-center" v-else>
                            <svg class="h-8 w-8 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-xs text-slate-400 font-medium">Aucune dépense en attente de validation.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions Section -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-bold text-slate-800">Transactions Récentes</h3>
                    <router-link :to="{ name: 'immobilier.historique' }" class="px-3 py-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-lg text-xs font-semibold transition-colors">
                        Tout Voir
                    </router-link>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Motif / Description</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Agence</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Montant</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            <tr v-for="t in recentTransactions" :key="t.id" class="hover:bg-slate-50/80 transition-all">
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-600">{{ formatDate(t.date_transaction) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs font-semibold text-slate-800">{{ t.motif }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500">
                                    <span v-if="t.agency" class="inline-flex items-center rounded-lg bg-blue-50 px-2.5 py-1 text-[10px] font-bold text-blue-700 border border-blue-100 shadow-sm">
                                        {{ t.agency.name }}
                                    </span>
                                    <span v-else class="inline-flex items-center rounded-lg bg-slate-50 px-2.5 py-1 text-[10px] font-bold text-slate-600 border border-slate-100 shadow-sm">
                                        Siège Social
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-right font-bold" :class="Number(t.montant) >= 0 ? 'text-emerald-600' : 'text-rose-600'">
                                    {{ Number(t.montant) >= 0 ? '+' : '' }}{{ formatCurrency(t.montant) }}
                                </td>
                            </tr>
                            <tr v-if="recentTransactions.length === 0">
                                <td colspan="4" class="px-6 py-12 text-center text-xs text-slate-400 italic">Aucune transaction enregistrée.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </template>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import { Chart } from 'chart.js/auto';

const loading = ref(true);

const kpis = ref({
    count_agencies_or_employees: 0,
    count_buildings: 0,
    count_contracts: 0,
    count_logements: 0,
    total_revenue: 0,
    total_expenses: 0,
    revenue_actual: 0,
    revenue_expected: 0,
    occupancy_rate: 0,
    occupied_count: 0,
    vacant_count: 0,
    cashflow_net: 0,
});

const unpaidInvoices = ref([]);
const pendingExpenses = ref([]);
const recentTransactions = ref([]);
const chartRevenueExpenses = ref({ revenues: [], expenses: [] });
const chartExpensesByType = ref({});

const currentYear = ref(new Date().getFullYear());

const revenueChart = ref(null);
const distributionChart = ref(null);
let revenueChartInstance = null;
let distributionChartInstance = null;

const fetchStats = async () => {
    try {
        loading.value = true;
        const response = await axios.get('/api/dashboard/stats');
        const data = response.data;
        
        kpis.value = data.kpis;
        unpaidInvoices.value = data.unpaid_invoices;
        pendingExpenses.value = data.pending_expenses;
        recentTransactions.value = data.recent_transactions;
        chartRevenueExpenses.value = data.chart_revenue_expenses;
        chartExpensesByType.value = data.chart_expenses_by_type;
        currentYear.value = data.current_year;

        // Render charts once data is loaded
        setTimeout(() => {
            renderCharts();
        }, 100);
    } catch (error) {
        console.error('Erreur lors du chargement des statistiques:', error);
    } finally {
        loading.value = false;
    }
};

const renderCharts = () => {
    // Destroy previous instances if any
    if (revenueChartInstance) revenueChartInstance.destroy();
    if (distributionChartInstance) distributionChartInstance.destroy();

    // 1. Line Chart: Revenues vs Expenses
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        const gradientRevenues = revenueCtx.getContext('2d').createLinearGradient(0, 0, 0, 300);
        gradientRevenues.addColorStop(0, 'rgba(16, 185, 129, 0.4)');
        gradientRevenues.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

        const gradientExpenses = revenueCtx.getContext('2d').createLinearGradient(0, 0, 0, 300);
        gradientExpenses.addColorStop(0, 'rgba(244, 63, 94, 0.4)');
        gradientExpenses.addColorStop(1, 'rgba(244, 63, 94, 0.0)');

        revenueChartInstance = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'],
                datasets: [
                    {
                        label: 'Recettes (Encaissements)',
                        data: chartRevenueExpenses.value.revenues,
                        borderColor: '#10b981',
                        backgroundColor: gradientRevenues,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    },
                    {
                        label: 'Dépenses (Payées)',
                        data: chartRevenueExpenses.value.expenses,
                        borderColor: '#f43f5e',
                        backgroundColor: gradientExpenses,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#f43f5e',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.95)',
                        cornerRadius: 8,
                        padding: 10,
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 12 }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(148, 163, 184, 0.08)' },
                        ticks: {
                            callback: (val) => formatCurrency(val),
                            color: '#64748b',
                            font: { size: 9 }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#64748b', font: { size: 10 } }
                    }
                }
            }
        });
    }

    // 2. Doughnut Chart: Expenses by Type
    const distributionCtx = document.getElementById('distributionChart');
    if (distributionCtx) {
        const labels = Object.keys(chartExpensesByType.value);
        const data = Object.values(chartExpensesByType.value);

        distributionChartInstance = new Chart(distributionCtx, {
            type: 'doughnut',
            data: {
                labels: labels.length > 0 ? labels : ['Aucune charge'],
                datasets: [{
                    data: data.length > 0 ? data : [1],
                    backgroundColor: labels.length > 0 ? chartColors.slice(0, labels.length) : ['rgba(226, 232, 240, 0.8)'],
                    borderWidth: 2
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
                            color: '#64748b',
                            font: { size: 10 }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: (context) => {
                                if (labels.length === 0) return 'Aucune dépense';
                                return `${context.label}: ${formatCurrency(context.parsed)}`;
                            }
                        }
                    }
                },
                cutout: '60%'
            }
        });
    }
};

onMounted(() => {
    fetchStats();
});

onUnmounted(() => {
    if (revenueChartInstance) revenueChartInstance.destroy();
    if (distributionChartInstance) distributionChartInstance.destroy();
});

const chartColors = [
    '#3b82f6',
    '#f59e0b',
    '#10b981',
    '#8b5cf6',
    '#ec4899',
    '#64748b'
];

const getChartColor = (index) => {
    return chartColors[index % chartColors.length];
};

const getPercentage = (amount) => {
    if (!kpis.value.total_expenses) return 0;
    return Math.round((amount / kpis.value.total_expenses) * 100);
};

const formatCurrency = (value) => {
    if (!value && value !== 0) return '-';
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'XAF', maximumFractionDigits: 0 }).format(value);
};

const formatDate = (dateStr) => {
    if (!dateStr) return '-';
    return new Intl.DateTimeFormat('fr-FR', { year: 'numeric', month: 'short', day: 'numeric' }).format(new Date(dateStr));
};
</script>

<style scoped>
.animate-scale-up {
    animation: scaleUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
@keyframes scaleUp {
    from {
        opacity: 0;
        transform: scale(0.97) translateY(5px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}
</style>
