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
                <!-- Direct Action Buttons or Administration controls -->
                <div class="flex items-center gap-3">
                    <button 
                        @click="showAdminPanel = !showAdminPanel" 
                        class="px-5 py-3 rounded-2xl bg-indigo-600/80 hover:bg-indigo-600 border border-indigo-500/50 hover:border-indigo-400 text-white font-bold text-xs transition-all duration-300 transform hover:scale-[1.02] flex items-center gap-2 shadow-lg shadow-indigo-600/20"
                    >
                        <svg class="w-4 h-4 text-indigo-200 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                        {{ showAdminPanel ? 'Fermer Administration' : 'Console d\'Administration' }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Premium Administration Panel (Collapsible) -->
        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="transform -translate-y-4 opacity-0"
            enter-to-class="transform translate-y-0 opacity-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="transform translate-y-0 opacity-100"
            leave-to-class="transform -translate-y-4 opacity-0"
        >
            <div v-if="showAdminPanel" class="bg-gradient-to-br from-slate-900 via-slate-950 to-slate-900 rounded-3xl p-8 border border-slate-800 text-white shadow-2xl">
                <h3 class="text-lg font-bold text-slate-200 mb-6 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-500 animate-ping"></span>
                    Console de Configuration Directe des KPIs Immobilier
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Vacancy Admin -->
                    <div class="space-y-2 p-4 bg-slate-800/20 rounded-2xl border border-slate-800">
                        <label class="block text-xs font-bold text-slate-400 uppercase">Taux de Vacance (%)</label>
                        <input v-model.number="kpis.vacancyRate" type="number" step="0.1" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-indigo-500" />
                        <div class="grid grid-cols-2 gap-2 mt-2">
                            <input v-model.number="kpis.vacantCount" type="number" placeholder="Vacants" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-2 py-1 text-xs text-center focus:outline-none" />
                            <input v-model.number="kpis.totalCount" type="number" placeholder="Total" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-2 py-1 text-xs text-center focus:outline-none" />
                        </div>
                    </div>
                    
                    <!-- Revenue Admin -->
                    <div class="space-y-2 p-4 bg-slate-800/20 rounded-2xl border border-slate-800">
                        <label class="block text-xs font-bold text-slate-400 uppercase">Revenu Mensuel (€)</label>
                        <input v-model.number="kpis.monthlyRevenueActual" type="number" step="100" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-indigo-500" />
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mt-1">Revenu Attendu (€)</label>
                        <input v-model.number="kpis.monthlyRevenueExpected" type="number" step="100" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2 text-xs focus:outline-none focus:border-indigo-500" />
                    </div>

                    <!-- Unpaid Admin -->
                    <div class="space-y-2 p-4 bg-slate-800/20 rounded-2xl border border-slate-800">
                        <label class="block text-xs font-bold text-slate-400 uppercase">Taux d'Impayés (%)</label>
                        <input v-model.number="kpis.unpaidRate" type="number" step="0.1" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-indigo-500" />
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mt-1">Montant Cumulé (€)</label>
                        <input v-model.number="kpis.unpaidTotal" type="number" step="10" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2 text-xs focus:outline-none focus:border-indigo-500" />
                    </div>

                    <!-- Charges Admin -->
                    <div class="space-y-2 p-4 bg-slate-800/20 rounded-2xl border border-slate-800">
                        <label class="block text-xs font-bold text-slate-400 uppercase">Récupération (%)</label>
                        <input v-model.number="kpis.chargesRecoveryRate" type="number" step="0.1" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-indigo-500" />
                        <div class="grid grid-cols-2 gap-2 mt-2">
                            <input v-model.number="kpis.chargesRecovered" type="number" placeholder="Récupéré" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-2 py-1 text-xs text-center focus:outline-none" />
                            <input v-model.number="kpis.chargesTotal" type="number" placeholder="Total" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-2 py-1 text-xs text-center focus:outline-none" />
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button @click="showAdminPanel = false" class="px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-xl text-white text-xs font-bold shadow-md shadow-emerald-500/20 hover:scale-[1.02] transition-transform">Enregistrer les Simulations</button>
                </div>
            </div>
        </Transition>

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
                        <svg width="10" height="10" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-rose-600">
                            <path d="M6 9L3 6m0 0l3-3m-3 3h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        +2.1%
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-slate-800 mb-1 tracking-tight">{{ kpis.vacancyRate }}%</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Taux de Vacance</div>
                <div class="text-xs text-slate-500 mt-4 border-t border-slate-100 pt-3 flex justify-between">
                    <span>Inoccupés : <strong class="text-slate-800">{{ kpis.vacantCount }}</strong></span>
                    <span>Total : {{ kpis.totalCount }}</span>
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
                        <svg width="10" height="10" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-emerald-600">
                            <path d="M6 3L3 6m0 0l3 3m-3-3h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        +5.8%
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-slate-800 mb-1 tracking-tight">€{{ kpis.monthlyRevenueActual.toLocaleString() }}</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Revenus Locatifs</div>
                <div class="text-xs text-slate-500 mt-4 border-t border-slate-100 pt-3 flex justify-between">
                    <span>Cible : <strong class="text-slate-800">€{{ kpis.monthlyRevenueExpected.toLocaleString() }}</strong></span>
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
                        <svg width="10" height="10" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-red-650">
                            <path d="M6 3L3 6m0 0l3 3m-3-3h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        +1
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-slate-800 mb-1 tracking-tight">{{ kpis.unpaidRate }}%</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Taux d'Impayés</div>
                <div class="text-xs text-slate-500 mt-4 border-t border-slate-100 pt-3 flex justify-between">
                    <span>Déficit cumulé : <strong class="text-rose-600">€{{ kpis.unpaidTotal.toLocaleString() }}</strong></span>
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
                        <svg width="10" height="10" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-emerald-600">
                            <path d="M6 9L3 6m0 0l3-3m-3 3h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        -3.2%
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-slate-800 mb-1 tracking-tight">{{ kpis.chargesRecoveryRate }}%</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Récupération Charges</div>
                <div class="text-xs text-slate-500 mt-4 border-t border-slate-100 pt-3 flex justify-between">
                    <span>Recouvré : <strong class="text-slate-800">€{{ kpis.chargesRecovered.toLocaleString() }}</strong> / €{{ kpis.chargesTotal.toLocaleString() }}</span>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Unpaid Chart -->
            <div class="bg-gradient-to-br from-white to-slate-50 rounded-3xl p-6 shadow-xl shadow-slate-200/50 border border-slate-150">
                <div class="mb-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-slate-850">Impayés par Période</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Distribution chronologique de l'encours locatif</p>
                    </div>
                </div>
                <div class="h-72">
                    <canvas id="unpaidChart"></canvas>
                </div>
            </div>

            <!-- Charges Comparison -->
            <div class="bg-gradient-to-br from-white to-slate-50 rounded-3xl p-6 shadow-xl shadow-slate-200/50 border border-slate-150">
                <div class="mb-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-slate-850">Comparatif des Charges par Immeuble</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Charges récupérables vs charges réelles constatées</p>
                    </div>
                </div>
                <div class="h-72">
                    <canvas id="chargesChart"></canvas>
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
                <button class="px-5 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-2xl text-xs font-bold hover:shadow-lg hover:shadow-blue-500/20 transition-all duration-300 transform hover:scale-[1.02]">
                    Voir tous les baux
                </button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200/50 bg-slate-50/50">
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Locataire</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Propriété</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Loyer Mensuel</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Fin de Bail</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-150">
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div :class="['w-10 h-10 rounded-xl flex items-center justify-center text-white font-extrabold text-sm shadow-sm bg-gradient-to-br', getAvatarGradient('Jean Dupont')]">
                                        {{ getInitials('Jean Dupont') }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800 text-sm">Jean Dupont</div>
                                        <div class="text-xs text-slate-500">jean.dupont@email.com</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700 font-semibold">Immeuble A - Apt 204</td>
                            <td class="px-6 py-4 text-sm text-slate-700 font-bold">€1,200</td>
                            <td class="px-6 py-4 text-sm font-extrabold text-rose-600">15 Juin 2026</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-250">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                    À renouveler
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <button class="px-4 py-2 bg-white border-2 border-slate-200 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 hover:border-slate-350 transition-all">Contacter</button>
                            </td>
                        </tr>
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div :class="['w-10 h-10 rounded-xl flex items-center justify-center text-white font-extrabold text-sm shadow-sm bg-gradient-to-br', getAvatarGradient('Marie Lambert')]">
                                        {{ getInitials('Marie Lambert') }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800 text-sm">Marie Lambert</div>
                                        <div class="text-xs text-slate-500">m.lambert@email.com</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700 font-semibold">Immeuble B - Bureau 102</td>
                            <td class="px-6 py-4 text-sm text-slate-700 font-bold">€2,500</td>
                            <td class="px-6 py-4 text-sm font-extrabold text-rose-600">22 Juin 2026</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-250">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                    À renouveler
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <button class="px-4 py-2 bg-white border-2 border-slate-200 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 hover:border-slate-350 transition-all">Contacter</button>
                            </td>
                        </tr>
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div :class="['w-10 h-10 rounded-xl flex items-center justify-center text-white font-extrabold text-sm shadow-sm bg-gradient-to-br', getAvatarGradient('Pierre Martin')]">
                                        {{ getInitials('Pierre Martin') }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800 text-sm">Pierre Martin</div>
                                        <div class="text-xs text-slate-500">p.martin@email.com</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700 font-semibold">Immeuble C - Apt 305</td>
                            <td class="px-6 py-4 text-sm text-slate-700 font-bold">€950</td>
                            <td class="px-6 py-4 text-sm font-extrabold text-amber-600">28 Juin 2026</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-250">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Bientôt
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <button class="px-4 py-2 bg-white border-2 border-slate-200 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 hover:border-slate-350 transition-all">Contacter</button>
                            </td>
                        </tr>
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div :class="['w-10 h-10 rounded-xl flex items-center justify-center text-white font-extrabold text-sm shadow-sm bg-gradient-to-br', getAvatarGradient('Sophie Bernard')]">
                                        {{ getInitials('Sophie Bernard') }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800 text-sm">Sophie Bernard</div>
                                        <div class="text-xs text-slate-500">s.bernard@email.com</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700 font-semibold">Immeuble A - Apt 108</td>
                            <td class="px-6 py-4 text-sm text-slate-700 font-bold">€1,100</td>
                            <td class="px-6 py-4 text-sm text-slate-500">15 Déc 2026</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-250">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-550"></span>
                                    Actif
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <button class="px-4 py-2 bg-white border-2 border-slate-200 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 hover:border-slate-350 transition-all">Voir</button>
                            </td>
                        </tr>
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div :class="['w-10 h-10 rounded-xl flex items-center justify-center text-white font-extrabold text-sm shadow-sm bg-gradient-to-br', getAvatarGradient('Antoine Roux')]">
                                        {{ getInitials('Antoine Roux') }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800 text-sm">Antoine Roux</div>
                                        <div class="text-xs text-slate-500">a.roux@email.com</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700 font-semibold">Immeuble D - Bureau 301</td>
                            <td class="px-6 py-4 text-sm text-slate-700 font-bold">€3,200</td>
                            <td class="px-6 py-4 text-sm text-slate-500">28 Fév 2027</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-250">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-550"></span>
                                    Actif
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <button class="px-4 py-2 bg-white border-2 border-slate-200 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 hover:border-slate-350 transition-all">Voir</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Rent Indexation -->
        <div class="bg-gradient-to-br from-white to-slate-50 rounded-3xl p-6 shadow-xl shadow-slate-200/50 border border-slate-150">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-850">Indexation des Loyers (IRL)</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Calcul et mise en œuvre des révisions annuelles de loyers</p>
                </div>
                <button class="px-5 py-3 bg-gradient-to-br from-slate-800 to-slate-900 text-white rounded-2xl text-xs font-bold hover:shadow-lg hover:shadow-slate-800/20 transition-all duration-300 transform hover:scale-[1.02]">
                    Gérer les indexations
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="flex flex-col gap-4 p-5 bg-white rounded-2xl border-2 border-amber-200 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 relative">
                    <span class="absolute top-4 right-4 text-[10px] font-bold text-amber-700 bg-amber-50 border border-amber-200 px-2.5 py-0.5 rounded-full uppercase">À appliquer</span>
                    <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center text-amber-500 shadow-sm border border-amber-100">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" fill="currentColor"/>
                        </svg>
                    </div>
                    <div>
                        <div class="font-extrabold text-slate-800 text-base">Immeuble A</div>
                        <div class="text-xs text-slate-500 mt-0.5">12 baux concernés</div>
                        <div class="flex flex-col gap-1 mt-3 text-xs text-slate-600 font-semibold">
                            <span>Index IRL : +3.5%</span>
                            <span>Échéance : 30 Juin 2026</span>
                        </div>
                    </div>
                    <button class="w-full mt-2 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold shadow-md shadow-amber-500/10 transition-colors">Appliquer l'indexation</button>
                </div>

                <div class="flex flex-col gap-4 p-5 bg-white rounded-2xl border-2 border-amber-200 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 relative">
                    <span class="absolute top-4 right-4 text-[10px] font-bold text-amber-700 bg-amber-50 border border-amber-200 px-2.5 py-0.5 rounded-full uppercase">À appliquer</span>
                    <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center text-amber-500 shadow-sm border border-amber-100">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" fill="currentColor"/>
                        </svg>
                    </div>
                    <div>
                        <div class="font-extrabold text-slate-800 text-base">Immeuble B</div>
                        <div class="text-xs text-slate-500 mt-0.5">8 baux concernés</div>
                        <div class="flex flex-col gap-1 mt-3 text-xs text-slate-600 font-semibold">
                            <span>Index IRL : +3.5%</span>
                            <span>Échéance : 15 Juil 2026</span>
                        </div>
                    </div>
                    <button class="w-full mt-2 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold shadow-md shadow-amber-500/10 transition-colors">Appliquer l'indexation</button>
                </div>

                <div class="flex flex-col gap-4 p-5 bg-white rounded-2xl border-2 border-emerald-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 relative">
                    <span class="absolute top-4 right-4 text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-250 px-2.5 py-0.5 rounded-full uppercase">Appliqué</span>
                    <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-500 shadow-sm border border-emerald-100">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" fill="currentColor"/>
                        </svg>
                    </div>
                    <div>
                        <div class="font-extrabold text-slate-800 text-base">Immeuble C</div>
                        <div class="text-xs text-slate-500 mt-0.5">15 baux indexés</div>
                        <div class="flex flex-col gap-1 mt-3 text-xs text-slate-600 font-semibold">
                            <span>Index IRL : +3.2%</span>
                            <span>Appliqué le : 15 Mai 2026</span>
                        </div>
                    </div>
                    <button class="w-full mt-2 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-colors">Voir les détails</button>
                </div>

                <div class="flex flex-col gap-4 p-5 bg-white rounded-2xl border-2 border-emerald-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 relative">
                    <span class="absolute top-4 right-4 text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-250 px-2.5 py-0.5 rounded-full uppercase">Appliqué</span>
                    <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-500 shadow-sm border border-emerald-100">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" fill="currentColor"/>
                        </svg>
                    </div>
                    <div>
                        <div class="font-extrabold text-slate-800 text-base">Immeuble D</div>
                        <div class="text-xs text-slate-500 mt-0.5">10 baux indexés</div>
                        <div class="flex flex-col gap-1 mt-3 text-xs text-slate-600 font-semibold">
                            <span>Index IRL : +3.2%</span>
                            <span>Appliqué le : 10 Mai 2026</span>
                        </div>
                    </div>
                    <button class="w-full mt-2 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-colors">Voir les détails</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue';
import { Chart } from 'chart.js/auto';

const showAdminPanel = ref(false);

const kpis = ref({
    vacancyRate: 7.7,
    vacantCount: 12,
    totalCount: 156,
    
    monthlyRevenueActual: 156800,
    monthlyRevenueExpected: 164500,
    
    unpaidRate: 4.2,
    unpaidTotal: 6920,
    unpaidPeriodData: [1200, 1500, 1800, 2100, 2700], // 30j, 60j, 90j, 120j, 150j+
    
    chargesRecoveryRate: 98.5,
    chargesRecovered: 24500,
    chargesTotal: 24900,
    chargesRecoverableData: [6500, 6800, 6200, 7000], // Immeubles A, B, C, D
    chargesActualData: [6000, 6500, 5800, 6500]
});

let unpaidChartInstance = null;
let chargesChartInstance = null;

// Watchers to update chart data dynamically when admin inputs change
watch(() => kpis.value.unpaidTotal, (newVal) => {
    // Distribute newVal across categories with weights
    const weights = [0.15, 0.20, 0.25, 0.20, 0.20];
    kpis.value.unpaidPeriodData = weights.map(w => Math.round(newVal * w));
    updateCharts();
});

watch(() => kpis.value.chargesRecovered, (newVal) => {
    // Distribute actual charges across 4 buildings
    const weights = [0.24, 0.26, 0.23, 0.27];
    kpis.value.chargesActualData = weights.map(w => Math.round(newVal * w));
    updateCharts();
});

watch(() => kpis.value.chargesTotal, (newVal) => {
    // Distribute recoverable charges
    const weights = [0.26, 0.27, 0.25, 0.28];
    kpis.value.chargesRecoverableData = weights.map(w => Math.round(newVal * w));
    updateCharts();
});

const updateCharts = () => {
    if (unpaidChartInstance) {
        unpaidChartInstance.data.datasets[0].data = kpis.value.unpaidPeriodData;
        unpaidChartInstance.update();
    }
    if (chargesChartInstance) {
        chargesChartInstance.data.datasets[0].data = kpis.value.chargesRecoverableData;
        chargesChartInstance.data.datasets[1].data = kpis.value.chargesActualData;
        chargesChartInstance.update();
    }
};

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

onMounted(() => {
    // Unpaid Chart
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
                    data: kpis.value.unpaidPeriodData,
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
                            font: { family: 'Plus Jakarta Sans', size: 10 }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: '#94a3b8',
                            font: { family: 'Plus Jakarta Sans', size: 10 }
                        }
                    }
                }
            }
        });
    }

    // Charges Chart
    const chargesCtx = document.getElementById('chargesChart');
    if (chargesCtx) {
        const gradientRecoverable = chargesCtx.getContext('2d').createLinearGradient(0, 0, 0, 300);
        gradientRecoverable.addColorStop(0, 'rgba(59, 130, 246, 0.85)');
        gradientRecoverable.addColorStop(1, 'rgba(59, 130, 246, 0.25)');

        const gradientActual = chargesCtx.getContext('2d').createLinearGradient(0, 0, 0, 300);
        gradientActual.addColorStop(0, 'rgba(16, 185, 129, 0.85)');
        gradientActual.addColorStop(1, 'rgba(16, 185, 129, 0.25)');

        chargesChartInstance = new Chart(chargesCtx, {
            type: 'bar',
            data: {
                labels: ['Immeuble A', 'Immeuble B', 'Immeuble C', 'Immeuble D'],
                datasets: [
                    {
                        label: 'Récupérables',
                        data: kpis.value.chargesRecoverableData,
                        backgroundColor: gradientRecoverable,
                        borderColor: '#3b82f6',
                        borderWidth: 1.5,
                        borderRadius: 8,
                        borderSkipped: false
                    },
                    {
                        label: 'Réelles',
                        data: kpis.value.chargesActualData,
                        backgroundColor: gradientActual,
                        borderColor: '#10b981',
                        borderWidth: 1.5,
                        borderRadius: 8,
                        borderSkipped: false
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
                            font: { family: 'Plus Jakarta Sans', size: 11, weight: 600 }
                        }
                    },
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
                            font: { family: 'Plus Jakarta Sans', size: 10 }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: '#94a3b8',
                            font: { family: 'Plus Jakarta Sans', size: 10 }
                        }
                    }
                }
            }
        });
    }
});

onUnmounted(() => {
    if (unpaidChartInstance) unpaidChartInstance.destroy();
    if (chargesChartInstance) chargesChartInstance.destroy();
});
</script>
