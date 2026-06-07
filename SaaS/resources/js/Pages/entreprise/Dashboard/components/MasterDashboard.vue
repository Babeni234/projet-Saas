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
                                <svg class="h-7 w-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
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
                    <div class="flex gap-4 flex-wrap lg:flex-nowrap lg:gap-6">
                        <div class="flex flex-col items-center justify-center px-4 py-3 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10">
                            <span class="text-2xl sm:text-3xl font-bold text-emerald-400">{{ countAgencies }}</span>
                            <span class="text-xs sm:text-sm text-slate-400 mt-1">Agences</span>
                        </div>
                        <div class="flex flex-col items-center justify-center px-4 py-3 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10">
                            <span class="text-2xl sm:text-3xl font-bold text-blue-400">{{ formattedReservations }}</span>
                            <span class="text-xs sm:text-sm text-slate-400 mt-1">Réservations</span>
                        </div>
                        <div class="flex flex-col items-center justify-center px-4 py-3 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10">
                            <span class="text-2xl sm:text-3xl font-bold text-amber-400">€{{ countRevenue }}K</span>
                            <span class="text-xs sm:text-sm text-slate-400 mt-1">Revenu</span>
                        </div>
                    </div>
                </div>

                <!-- Bottom accent line -->
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-indigo-500 via-violet-500 to-emerald-500 opacity-60"></div>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Revenue Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 9L3 6m0 0l3-3m-3 3h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        +12.5%
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-800 mb-1">€284,500</div>
                <div class="text-sm text-slate-500 mb-4">Chiffre d'Affaires Global</div>
                <div class="pt-4 border-t border-slate-100 space-y-2">
                    <div class="flex items-center gap-2 text-xs text-slate-600">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        <span>Immobilier: €156,800</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-slate-600">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        <span>Hôtel: €127,700</span>
                    </div>
                </div>
            </div>

            <!-- Occupancy Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 9L3 6m0 0l3-3m-3 3h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        +3.2%
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-800 mb-1">87.5%</div>
                <div class="text-sm text-slate-500 mb-4">Taux d'Occupation Combiné</div>
                <div class="pt-4 border-t border-slate-100 space-y-2">
                    <div class="flex items-center gap-2 text-xs text-slate-600">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        <span>Immobilier: 92.3%</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-slate-600">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        <span>Hôtel: 82.7%</span>
                    </div>
                </div>
            </div>

            <!-- Cashflow Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 9L3 6m0 0l3-3m-3 3h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        +8.1%
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-800 mb-1">€45,200</div>
                <div class="text-sm text-slate-500 mb-4">Trésorerie Nette</div>
                <div class="pt-4 border-t border-slate-100 space-y-2">
                    <div class="flex items-center gap-2 text-xs text-slate-600">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        <span>Entrées: €89,400</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-slate-600">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        <span>Sorties: €44,200</span>
                    </div>
                </div>
            </div>

            <!-- Alerts Card -->
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
                        2 critiques
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-800 mb-1">5</div>
                <div class="text-sm text-slate-500 mb-4">Alertes Maintenance</div>
                <div class="pt-4 border-t border-slate-100 space-y-2">
                    <div class="flex items-center gap-2 text-xs text-slate-600">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        <span>Immeuble A: 2</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-slate-600">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        <span>Hôtel: 3</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Revenue Chart -->
            <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-slate-800">Évolution du Chiffre d'Affaires</h3>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-blue-500 shadow-lg shadow-blue-500/50"></span>
                            <span class="text-sm text-slate-600">Immobilier</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-amber-500 shadow-lg shadow-amber-500/50"></span>
                            <span class="text-sm text-slate-600">Hôtellerie</span>
                        </div>
                    </div>
                </div>
                <div class="h-64">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Occupation Pie Chart -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-slate-800">Répartition de l'Occupation</h3>
                </div>
                <div class="h-64">
                    <canvas id="distributionChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Alerts Section -->
        <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Alertes Critiques</h3>
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

                <div class="flex items-center gap-4 p-4 bg-amber-50 rounded-xl border-l-4 border-amber-500">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-amber-500">
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

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Actions Rapides</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <button class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200 hover:bg-slate-100 hover:border-slate-300 transition-all duration-200 group">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="text-left">
                        <div class="font-semibold text-slate-800">3 Contrats</div>
                        <div class="text-sm text-slate-600">À renouveler ce mois</div>
                    </div>
                </button>

                <button class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200 hover:bg-slate-100 hover:border-slate-300 transition-all duration-200 group">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/30 group-hover:scale-110 transition-transform">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="text-left">
                        <div class="font-semibold text-slate-800">12 Chambres</div>
                        <div class="text-sm text-slate-600">À nettoyer aujourd'hui</div>
                    </div>
                </button>

                <button class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200 hover:bg-slate-100 hover:border-slate-300 transition-all duration-200 group">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/30 group-hover:scale-110 transition-transform">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="text-left">
                        <div class="font-semibold text-slate-800">5 Tickets</div>
                        <div class="text-sm text-slate-600">Maintenance en attente</div>
                    </div>
                </button>

                <button class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200 hover:bg-slate-100 hover:border-slate-300 transition-all duration-200 group">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="text-left">
                        <div class="font-semibold text-slate-800">8 Factures</div>
                        <div class="text-sm text-slate-600">À envoyer ce jour</div>
                    </div>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Chart } from 'chart.js/auto';

// Counters for banner
const countAgencies = ref(0);
const countReservations = ref(0);
const countRevenue = ref(0);

// Formatted counters
const formattedReservations = computed(() => countReservations.value.toLocaleString('fr-FR'));

// Counter animation function
const animateCounter = (ref, targetValue, duration = 1500) => {
    const start = 0;
    const startTime = Date.now();

    const animate = () => {
        const elapsed = Date.now() - startTime;
        const progress = Math.min(elapsed / duration, 1);
        ref.value = Math.floor(start + (targetValue - start) * progress);

        if (progress < 1) {
            requestAnimationFrame(animate);
        } else {
            ref.value = targetValue;
        }
    };

    animate();
};

const revenueChart = ref(null);
const distributionChart = ref(null);

onMounted(() => {
    // Animate counters when component mounts
    animateCounter(countAgencies, 12, 1500);
    animateCounter(countReservations, 1245, 1500);
    animateCounter(countRevenue, 89, 1500);

    // Revenue Chart - Line Chart with Gradient
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        const gradientImmobilier = revenueCtx.getContext('2d').createLinearGradient(0, 0, 0, 400);
        gradientImmobilier.addColorStop(0, 'rgba(59, 130, 246, 0.5)');
        gradientImmobilier.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

        const gradientHotel = revenueCtx.getContext('2d').createLinearGradient(0, 0, 0, 400);
        gradientHotel.addColorStop(0, 'rgba(245, 158, 11, 0.5)');
        gradientHotel.addColorStop(1, 'rgba(245, 158, 11, 0.0)');

        revenueChart.value = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août'],
                datasets: [
                    {
                        label: 'Immobilier',
                        data: [145000, 148000, 152000, 155000, 158000, 162000, 165000, 168000],
                        borderColor: '#3b82f6',
                        backgroundColor: gradientImmobilier,
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
                    },
                    {
                        label: 'Hôtellerie',
                        data: [118000, 122000, 125000, 128000, 132000, 135000, 138000, 141000],
                        borderColor: '#f59e0b',
                        backgroundColor: gradientHotel,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#f59e0b',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 8,
                        pointShadowColor: 'rgba(245, 158, 11, 0.5)',
                        pointShadowBlur: 10
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
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(148, 163, 184, 0.2)',
                        borderWidth: 1,
                        cornerRadius: 12,
                        padding: 12,
                        displayColors: true,
                        boxPadding: 4
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

    // Distribution Chart - Doughnut Chart
    const distributionCtx = document.getElementById('distributionChart');
    if (distributionCtx) {
        distributionChart.value = new Chart(distributionCtx, {
            type: 'doughnut',
            data: {
                labels: ['Immobilier', 'Hôtellerie'],
                datasets: [{
                    data: [52, 48],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.9)',
                        'rgba(245, 158, 11, 0.9)'
                    ],
                    borderColor: [
                        '#3b82f6',
                        '#f59e0b'
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
    if (revenueChart.value) revenueChart.value.destroy();
    if (distributionChart.value) distributionChart.value.destroy();
});
</script>

