<template>
    <div class="flex flex-col gap-8">
        <!-- Report Types -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Financial Report -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100">
                <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30 mb-4">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                        <path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="text-lg font-semibold text-slate-800 mb-2">Rapport Financier</div>
                <div class="text-sm text-slate-500 mb-4">Revenus, dépenses et bénéfices</div>
                <button class="w-full px-4 py-2 bg-emerald-500 text-white rounded-lg text-sm font-medium hover:bg-emerald-600 transition-colors shadow-md shadow-emerald-500/20">Générer</button>
            </div>

            <!-- Occupancy Report -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30 mb-4">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                        <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="text-lg font-semibold text-slate-800 mb-2">Rapport d'Occupation</div>
                <div class="text-sm text-slate-500 mb-4">Taux d'occupation par immeuble</div>
                <button class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors shadow-md shadow-blue-500/20">Générer</button>
            </div>

            <!-- Maintenance Report -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100">
                <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/30 mb-4">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                        <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="text-lg font-semibold text-slate-800 mb-2">Rapport Maintenance</div>
                <div class="text-sm text-slate-500 mb-4">Tickets et interventions</div>
                <button class="w-full px-4 py-2 bg-amber-500 text-white rounded-lg text-sm font-medium hover:bg-amber-600 transition-colors shadow-md shadow-amber-500/20">Générer</button>
            </div>

            <!-- Custom Report -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-500/30 mb-4">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                        <path d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="text-lg font-semibold text-slate-800 mb-2">Rapport Personnalisé</div>
                <div class="text-sm text-slate-500 mb-4">Créer un rapport sur mesure</div>
                <button class="w-full px-4 py-2 bg-purple-500 text-white rounded-lg text-sm font-medium hover:bg-purple-600 transition-colors shadow-md shadow-purple-500/20">Créer</button>
            </div>
        </div>

        <!-- Recent Reports -->
        <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-slate-800">Rapports Récents</h3>
                <button class="px-4 py-2 bg-blue-500 text-white rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors shadow-md shadow-blue-500/20">Voir tous</button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Nom</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Type</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Période</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Créé le</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Taille</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-emerald-600">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" fill="currentColor"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-slate-800">Rapport Financier Mai 2026</div>
                                        <div class="text-xs text-slate-500">Généré automatiquement</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4"><span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">Financier</span></td>
                            <td class="px-4 py-4 text-sm text-slate-600">Mai 2026</td>
                            <td class="px-4 py-4 text-sm text-slate-600">1 Juin 2026</td>
                            <td class="px-4 py-4 text-sm text-slate-600">2.4 MB</td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    <button class="px-3 py-1.5 bg-blue-500 text-white rounded-lg text-xs font-medium hover:bg-blue-600 transition-colors">Télécharger</button>
                                    <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">Voir</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-blue-600">
                                            <path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-slate-800">Occupation Q2 2026</div>
                                        <div class="text-xs text-slate-500">Généré par Admin</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4"><span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">Occupation</span></td>
                            <td class="px-4 py-4 text-sm text-slate-600">Avr - Juin 2026</td>
                            <td class="px-4 py-4 text-sm text-slate-600">15 Juin 2026</td>
                            <td class="px-4 py-4 text-sm text-slate-600">1.8 MB</td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    <button class="px-3 py-1.5 bg-blue-500 text-white rounded-lg text-xs font-medium hover:bg-blue-600 transition-colors">Télécharger</button>
                                    <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">Voir</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-amber-600">
                                            <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-slate-800">Maintenance Mai 2026</div>
                                        <div class="text-xs text-slate-500">Généré automatiquement</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4"><span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-semibold">Maintenance</span></td>
                            <td class="px-4 py-4 text-sm text-slate-600">Mai 2026</td>
                            <td class="px-4 py-4 text-sm text-slate-600">1 Juin 2026</td>
                            <td class="px-4 py-4 text-sm text-slate-600">1.2 MB</td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    <button class="px-3 py-1.5 bg-blue-500 text-white rounded-lg text-xs font-medium hover:bg-blue-600 transition-colors">Télécharger</button>
                                    <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">Voir</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-purple-600">
                                            <path d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-slate-800">Analyse Complète Q1 2026</div>
                                        <div class="text-xs text-slate-500">Généré par Admin</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4"><span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-semibold">Personnalisé</span></td>
                            <td class="px-4 py-4 text-sm text-slate-600">Jan - Mar 2026</td>
                            <td class="px-4 py-4 text-sm text-slate-600">5 Avr 2026</td>
                            <td class="px-4 py-4 text-sm text-slate-600">4.5 MB</td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    <button class="px-3 py-1.5 bg-blue-500 text-white rounded-lg text-xs font-medium hover:bg-blue-600 transition-colors">Télécharger</button>
                                    <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">Voir</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Scheduled Reports -->
        <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-slate-800">Rapports Programmés</h3>
                <button class="px-4 py-2 bg-blue-500 text-white rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors shadow-md shadow-blue-500/20">Ajouter</button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-emerald-600">
                                <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">Actif</div>
                    </div>
                    <div class="font-semibold text-slate-800 mb-1">Rapport Financier Mensuel</div>
                    <div class="text-sm text-slate-600 mb-3">Généré le 1er de chaque mois</div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500">Prochain: 1 Juil 2026</span>
                        <button class="text-xs text-blue-600 font-medium hover:text-blue-700">Modifier</button>
                    </div>
                </div>

                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-blue-600">
                                <path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">Actif</div>
                    </div>
                    <div class="font-semibold text-slate-800 mb-1">Rapport Occupation Hebdo</div>
                    <div class="text-sm text-slate-600 mb-3">Généré chaque lundi</div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500">Prochain: 21 Juin 2026</span>
                        <button class="text-xs text-blue-600 font-medium hover:text-blue-700">Modifier</button>
                    </div>
                </div>

                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-amber-600">
                                <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="px-2 py-1 bg-slate-200 text-slate-600 rounded-full text-xs font-semibold">Inactif</div>
                    </div>
                    <div class="font-semibold text-slate-800 mb-1">Rapport Maintenance Trimestriel</div>
                    <div class="text-sm text-slate-600 mb-3">Généré chaque trimestre</div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500">Désactivé</span>
                        <button class="text-xs text-blue-600 font-medium hover:text-blue-700">Activer</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-emerald-600">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-800">47</div>
                        <div class="text-sm text-slate-500">Rapports ce mois</div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-blue-600">
                            <path d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-800">156 MB</div>
                        <div class="text-sm text-slate-500">Espace utilisé</div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-purple-600">
                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-800">3</div>
                        <div class="text-sm text-slate-500">Rapports programmés</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
</script>
