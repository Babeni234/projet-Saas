<template>
    <div class="flex flex-col gap-8 p-1">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-900 text-white rounded-3xl p-6 shadow-xl shadow-slate-950/20">
            <div>
                <h1 class="text-2xl font-bold tracking-tight">Rapports & Statistiques (Agence)</h1>
                <p class="text-sm text-slate-300 mt-1">Générez et consultez les performances de votre agence locale.</p>
            </div>
            <button @click="openGenerateModal" class="px-5 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-xl text-sm font-semibold transition-all duration-300 shadow-lg shadow-emerald-500/20 hover:shadow-emerald-600/30 flex items-center gap-2 transform active:scale-95">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 4v16m8-8H4" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                </svg>
                Nouveau Rapport
            </button>
        </div>

        <!-- Report Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-5">
            <!-- Rent Report -->
            <div @click="quickGenerate('Loyer')" class="cursor-pointer bg-white rounded-2xl p-5 shadow-md shadow-slate-200/50 hover:shadow-xl hover:shadow-emerald-500/10 hover:-translate-y-1 transition-all duration-300 border border-slate-100 group">
                <div class="w-11 h-11 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-xl flex items-center justify-center shadow-md shadow-emerald-500/20 mb-4 group-hover:scale-110 transition-transform">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-white">
                        <rect x="2" y="4" width="20" height="16" rx="2" />
                        <line x1="12" y1="8" x2="12" y2="16" />
                        <line x1="8" y1="12" x2="16" y2="12" />
                    </svg>
                </div>
                <div class="text-base font-bold text-slate-800 mb-1">Rapport Loyers</div>
                <div class="text-xs text-slate-500 mb-3">État des loyers et pénalités mensuels</div>
                <div class="text-xs text-emerald-600 font-semibold group-hover:underline flex items-center gap-1">Générer en 1 clic →</div>
            </div>

            <!-- Financial Report -->
            <div @click="quickGenerate('Financier')" class="cursor-pointer bg-white rounded-2xl p-5 shadow-md shadow-slate-200/50 hover:shadow-xl hover:shadow-blue-500/10 hover:-translate-y-1 transition-all duration-300 border border-slate-100 group">
                <div class="w-11 h-11 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center shadow-md shadow-blue-500/20 mb-4 group-hover:scale-110 transition-transform">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-white">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" />
                    </svg>
                </div>
                <div class="text-base font-bold text-slate-800 mb-1">Financier</div>
                <div class="text-xs text-slate-500 mb-3">Revenus et charges de l'agence</div>
                <div class="text-xs text-blue-600 font-semibold group-hover:underline flex items-center gap-1">Générer en 1 clic →</div>
            </div>

            <!-- Occupancy Report -->
            <div @click="quickGenerate('Occupation')" class="cursor-pointer bg-white rounded-2xl p-5 shadow-md shadow-slate-200/50 hover:shadow-xl hover:shadow-purple-500/10 hover:-translate-y-1 transition-all duration-300 border border-slate-100 group">
                <div class="w-11 h-11 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center shadow-md shadow-purple-500/20 mb-4 group-hover:scale-110 transition-transform">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-white">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        <polyline points="9 22 9 12 15 12 15 22" />
                    </svg>
                </div>
                <div class="text-base font-bold text-slate-800 mb-1">Occupation</div>
                <div class="text-xs text-slate-500 mb-3">Occupation des logements locaux</div>
                <div class="text-xs text-purple-600 font-semibold group-hover:underline flex items-center gap-1">Générer en 1 clic →</div>
            </div>

            <!-- Maintenance Report -->
            <div @click="quickGenerate('Maintenance')" class="cursor-pointer bg-white rounded-2xl p-5 shadow-md shadow-slate-200/50 hover:shadow-xl hover:shadow-amber-500/10 hover:-translate-y-1 transition-all duration-300 border border-slate-100 group">
                <div class="w-11 h-11 bg-gradient-to-br from-amber-400 to-amber-600 rounded-xl flex items-center justify-center shadow-md shadow-amber-500/20 mb-4 group-hover:scale-110 transition-transform">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-white">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z" />
                    </svg>
                </div>
                <div class="text-base font-bold text-slate-800 mb-1">Maintenance</div>
                <div class="text-xs text-slate-500 mb-3">Interventions et travaux gérés</div>
                <div class="text-xs text-amber-600 font-semibold group-hover:underline flex items-center gap-1">Générer en 1 clic →</div>
            </div>

            <!-- AI / Custom Report -->
            <div @click="openGenerateModal" class="cursor-pointer bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-5 shadow-md hover:shadow-xl hover:shadow-teal-500/10 hover:-translate-y-1 transition-all duration-300 border border-slate-700 group">
                <div class="w-11 h-11 bg-gradient-to-br from-teal-400 to-emerald-500 rounded-xl flex items-center justify-center shadow-md shadow-teal-500/20 mb-4 group-hover:scale-110 transition-transform">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="text-white animate-pulse">
                        <path d="M12 2a10 10 0 0 1 8.29 4.41L18 8h4V4l-1.59 1.59A10 10 0 1 0 12 22z" />
                    </svg>
                </div>
                <div class="text-base font-bold text-white mb-1">Analyse IA</div>
                <div class="text-xs text-slate-300 mb-3">Analyses prédictives locales par l'IA</div>
                <div class="text-xs text-teal-400 font-semibold group-hover:underline flex items-center gap-1">Ouvrir le configurateur →</div>
            </div>
        </div>

        <!-- Recent Reports List -->
        <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-100 border border-slate-100">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Rapports d'Agence</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Historique des rapports générés pour votre agence.</p>
                </div>
                <div class="flex flex-wrap items-center gap-2.5 w-full sm:w-auto">
                    <input v-model="searchQuery" type="text" placeholder="Rechercher par nom..." class="px-4 py-2 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all outline-none w-full sm:w-48 bg-slate-50/50">
                    
                    <select v-model="typeFilter" class="px-4 py-2 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all outline-none bg-slate-50/50">
                        <option value="all">Tous les types</option>
                        <option value="Loyer">Loyer</option>
                        <option value="Financier">Financier</option>
                        <option value="Occupation">Occupation</option>
                        <option value="Maintenance">Maintenance</option>
                    </select>
                </div>
            </div>

            <!-- Loader -->
            <div v-if="loading" class="flex flex-col items-center justify-center py-20 gap-3">
                <div class="w-12 h-12 border-4 border-emerald-500 border-t-transparent rounded-full animate-spin"></div>
                <p class="text-sm text-slate-500 font-medium">Chargement des rapports...</p>
            </div>

            <!-- Empty State -->
            <div v-else-if="filteredReports.length === 0" class="text-center py-16 bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
                <svg class="mx-auto text-slate-400 mb-4" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                    <polyline points="14 2 14 8 20 8" />
                </svg>
                <h4 class="text-sm font-bold text-slate-800">Aucun rapport</h4>
                <p class="text-xs text-slate-500 mt-1">Vous n'avez pas encore généré de rapport pour cette agence.</p>
            </div>

            <!-- Table -->
            <div v-else class="overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-y-2">
                    <thead>
                        <tr class="text-slate-500 text-xs font-bold uppercase tracking-wider">
                            <th class="px-5 py-3">Nom</th>
                            <th class="px-5 py-3">Type</th>
                            <th class="px-5 py-3">Période</th>
                            <th class="px-5 py-3">Généré le</th>
                            <th class="px-5 py-3">Taille</th>
                            <th class="px-5 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="r in filteredReports" :key="r.id" class="bg-slate-50/40 hover:bg-slate-50 border border-slate-100 rounded-xl transition-all duration-200">
                            <td class="px-5 py-4 font-bold text-slate-800">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg flex items-center justify-center" :class="getTypeClass(r.type)">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                            <polyline points="14 2 14 8 20 8" />
                                        </svg>
                                    </div>
                                    <div>
                                        <span>{{ r.nom }}</span>
                                        <div v-if="r.ai_analysis" class="flex items-center gap-1 mt-1">
                                            <span class="inline-block w-2.5 h-2.5 bg-teal-400 rounded-full animate-pulse"></span>
                                            <span class="text-[9px] text-teal-600 font-bold uppercase tracking-wider">Analyse IA Active</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold" :class="getTypeBadge(r.type)">
                                    {{ r.type }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-sm font-medium text-slate-600">{{ r.periode }}</td>
                            <td class="px-5 py-4 text-sm text-slate-500">{{ r.created_at }}</td>
                            <td class="px-5 py-4 text-sm font-medium text-slate-600">{{ r.file_size }}</td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="showReportDetail(r)" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors flex items-center gap-1.5">
                                        Voir
                                    </button>
                                    <a :href="`/api/rapports/${r.uuid}/download`" target="_blank" class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-lg transition-colors flex items-center gap-1.5">
                                        Télécharger
                                    </a>
                                    <button @click="deleteReport(r.id)" class="p-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg transition-colors" title="Supprimer">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <polyline points="3 6 5 6 21 6" />
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Generate Modal -->
        <div v-if="showGenModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl p-6 w-full max-w-md shadow-2xl border border-slate-100 transform transition-all duration-300 scale-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-slate-800">Générer un Rapport d'Agence</h3>
                    <button @click="showGenModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="flex flex-col gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Type de rapport</label>
                        <select v-model="form.type" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all outline-none text-sm bg-slate-50">
                            <option value="Loyer">État des Loyers (Recommandé)</option>
                            <option value="Financier">Consolidé Financier d'Agence</option>
                            <option value="Occupation">Statistiques d'Occupation</option>
                            <option value="Maintenance">Rapport de Maintenance / Travaux</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Mois / Période</label>
                        <div class="flex gap-2">
                            <select v-model="form.month" class="flex-1 px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all outline-none text-sm bg-slate-50">
                                <option v-for="(m, idx) in monthsList" :key="idx" :value="m">{{ m }}</option>
                            </select>
                            <select v-model="form.year" class="w-28 px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all outline-none text-sm bg-slate-50">
                                <option value="2026">2026</option>
                                <option value="2025">2025</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-2 p-4 bg-teal-50/50 rounded-2xl border border-teal-100 flex items-start gap-3">
                        <input v-model="form.use_ai" type="checkbox" id="use_ai_agence" class="mt-1 accent-teal-600 cursor-pointer w-4 h-4 rounded">
                        <label for="use_ai_agence" class="cursor-pointer">
                            <span class="block text-sm font-bold text-teal-800">Analyser avec l'IA (Gemini)</span>
                            <span class="block text-xs text-teal-600 mt-0.5">Permet de compiler des alertes locales pour votre agence et des stratégies de relances.</span>
                        </label>
                    </div>
                </div>

                <div class="flex gap-3 mt-8">
                    <button @click="showGenModal = false" class="flex-1 px-4 py-3 border border-slate-200 text-slate-700 text-sm font-semibold rounded-xl hover:bg-slate-50 transition-colors">
                        Annuler
                    </button>
                    <button @click="submitGenerate" :disabled="generating" class="flex-1 px-4 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white text-sm font-semibold rounded-xl transition-all shadow-md shadow-emerald-500/20 flex items-center justify-center gap-2">
                        <span v-if="generating" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                        {{ generating ? 'Génération...' : 'Confirmer' }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Detail Drawer -->
        <div v-if="showDetail" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex justify-end">
            <div class="bg-white w-full max-w-3xl h-full shadow-2xl flex flex-col transform transition-transform duration-300">
                <!-- Header -->
                <div class="p-6 border-bottom border-slate-100 flex justify-between items-center bg-slate-900 text-white">
                    <div>
                        <span class="px-2 py-0.5 bg-teal-500/20 text-teal-300 border border-teal-500/30 rounded text-[10px] font-bold uppercase tracking-wider">{{ selectedReport.type }}</span>
                        <h3 class="text-lg font-bold mt-1.5">{{ selectedReport.nom }}</h3>
                    </div>
                    <button @click="showDetail = false" class="text-slate-300 hover:text-white transition-colors">
                        <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Tabs -->
                <div class="flex border-b border-slate-200 px-6 bg-slate-50">
                    <button @click="activeTab = 'data'" :class="{'border-emerald-500 text-emerald-600 font-bold': activeTab === 'data', 'border-transparent text-slate-500 hover:text-slate-700': activeTab !== 'data'}" class="px-4 py-3 text-sm border-b-2 font-medium transition-all">
                        Données
                    </button>
                    <button @click="activeTab = 'ai'" :class="{'border-emerald-500 text-emerald-600 font-bold': activeTab === 'ai', 'border-transparent text-slate-500 hover:text-slate-700': activeTab !== 'ai'}" class="px-4 py-3 text-sm border-b-2 font-medium transition-all flex items-center gap-1.5">
                        Analyse IA
                        <span class="inline-block w-2.5 h-2.5 bg-teal-400 rounded-full animate-pulse"></span>
                    </button>
                    <button @click="activeTab = 'chat'" :class="{'border-emerald-500 text-emerald-600 font-bold': activeTab === 'chat', 'border-transparent text-slate-500 hover:text-slate-700': activeTab !== 'chat'}" class="px-4 py-3 text-sm border-b-2 font-medium transition-all">
                        Discuter avec l'IA
                    </button>
                </div>

                <!-- Content Area -->
                <div class="flex-1 overflow-y-auto p-6">
                    <!-- DATA TAB -->
                    <div v-if="activeTab === 'data'" class="flex flex-col gap-6">
                        <div class="text-slate-500 text-sm italic">
                            Données financières et locatives de votre agence.
                        </div>

                        <!-- Rent report preview (Agency version: loops starting from owners/bailleurs immediately) -->
                        <div v-if="selectedReport.type === 'Loyer'" class="flex flex-col gap-6">
                            <div v-if="isObject(selectedReport.report_data)" class="flex flex-col gap-6">
                                <div v-for="(ownerVal, ownerName) in selectedReport.report_data.hierarchy" :key="ownerName" class="border border-slate-200 rounded-2xl overflow-hidden shadow-sm bg-white p-5">
                                    <div class="text-sm font-bold text-teal-800 border-b border-slate-100 pb-2 mb-4">
                                        Bailleur / Propriétaire : {{ ownerName }}
                                    </div>
                                    <div v-for="(buildingRows, buildingName) in ownerVal.buildings" :key="buildingName" class="flex flex-col gap-2 mb-4">
                                        <div class="text-xs font-bold text-slate-600 underline">
                                            Bâtiment : {{ buildingName }}
                                        </div>
                                        <div class="overflow-x-auto">
                                            <table class="w-full text-xs text-left">
                                                <thead>
                                                    <tr class="bg-slate-50 text-slate-500 font-bold">
                                                        <th class="p-2">Logement</th>
                                                        <th class="p-2">Contrat</th>
                                                        <th class="p-2">Locataire</th>
                                                        <th class="p-2 text-right">Loyer</th>
                                                        <th class="p-2 text-right">Pénalité</th>
                                                        <th class="p-2 text-right">Payé</th>
                                                        <th class="p-2">Statut</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(row, idx) in buildingRows" :key="idx" class="border-t border-slate-100 hover:bg-slate-50/50">
                                                        <td class="p-2 font-bold">{{ row.logement_ref }}</td>
                                                        <td class="p-2">{{ row.contrat_numero }}</td>
                                                        <td class="p-2 font-medium text-slate-700">{{ row.locataire_name }}</td>
                                                        <td class="p-2 text-right font-semibold">{{ formatMoney(row.loyer) }} €</td>
                                                        <td class="p-2 text-right text-rose-600 font-semibold">{{ formatMoney(row.penalite) }} €</td>
                                                        <td class="p-2 text-right text-emerald-600 font-semibold">{{ formatMoney(row.montant_paye) }} €</td>
                                                        <td class="p-2">
                                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold" :class="row.statut === 'Payé' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'">
                                                                {{ row.statut }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Financial report preview -->
                        <div v-else-if="selectedReport.type === 'Financier' && selectedReport.report_data" class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-xs text-slate-500 font-bold uppercase">Revenus de l'Agence</span>
                                <div class="text-lg font-bold text-emerald-600 mt-1">{{ formatMoney(selectedReport.report_data.total_revenue) }} €</div>
                            </div>
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-xs text-slate-500 font-bold uppercase">Charges d'Agence</span>
                                <div class="text-lg font-bold text-rose-600 mt-1">{{ formatMoney(selectedReport.report_data.total_expenses) }} €</div>
                            </div>
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-xs text-slate-500 font-bold uppercase">Résultat Net</span>
                                <div class="text-lg font-bold text-slate-800 mt-1">{{ formatMoney(selectedReport.report_data.net_profit) }} €</div>
                            </div>
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-xs text-slate-500 font-bold uppercase">Marge brute</span>
                                <div class="text-lg font-bold text-slate-800 mt-1">{{ selectedReport.report_data.profit_margin }} %</div>
                            </div>
                        </div>

                        <!-- Occupancy report preview -->
                        <div v-else-if="selectedReport.type === 'Occupation' && selectedReport.report_data" class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-xs text-slate-500 font-bold uppercase">Logements gérés</span>
                                <div class="text-lg font-bold text-slate-800 mt-1">{{ selectedReport.report_data.total_units }}</div>
                            </div>
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-xs text-slate-500 font-bold uppercase">Logements occupés</span>
                                <div class="text-lg font-bold text-emerald-600 mt-1">{{ selectedReport.report_data.occupied_units }}</div>
                            </div>
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-xs text-slate-500 font-bold uppercase">Logements vacants</span>
                                <div class="text-lg font-bold text-amber-600 mt-1">{{ selectedReport.report_data.vacant_units }}</div>
                            </div>
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-xs text-slate-500 font-bold uppercase">Taux d'occupation</span>
                                <div class="text-lg font-bold text-blue-600 mt-1">{{ selectedReport.report_data.occupancy_rate }} %</div>
                            </div>
                        </div>

                        <!-- Maintenance report preview -->
                        <div v-else-if="selectedReport.report_data" class="grid grid-cols-3 gap-4">
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-xs text-slate-500 font-bold uppercase">Dépenses d'interventions</span>
                                <div class="text-lg font-bold text-slate-800 mt-1">{{ formatMoney(selectedReport.report_data.total_expenses) }} €</div>
                            </div>
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-xs text-slate-500 font-bold uppercase">Interventions</span>
                                <div class="text-lg font-bold text-slate-800 mt-1">{{ selectedReport.report_data.interventions_count }}</div>
                            </div>
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-xs text-slate-500 font-bold uppercase">Coût moyen d'intervention</span>
                                <div class="text-lg font-bold text-slate-800 mt-1">{{ formatMoney(selectedReport.report_data.average_cost) }} €</div>
                            </div>
                        </div>
                    </div>

                    <!-- AI ANALYSIS TAB -->
                    <div v-else-if="activeTab === 'ai'" class="flex flex-col gap-6">
                        <div v-if="selectedReport.ai_analysis" class="flex flex-col gap-6">
                            <div class="bg-gradient-to-r from-teal-500/10 to-emerald-500/10 border border-teal-100 rounded-2xl p-5 shadow-sm">
                                <h4 class="text-sm font-bold text-teal-800 flex items-center gap-2 mb-2">
                                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Synthèse d'Agence par l'IA
                                </h4>
                                <p class="text-sm text-teal-900 leading-relaxed italic">"{{ selectedReport.ai_analysis.summary }}"</p>
                            </div>

                            <div>
                                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Indicateurs clés d'Agence</h4>
                                <div class="grid grid-cols-1 gap-3">
                                    <div v-for="(insight, idx) in selectedReport.ai_analysis.insights" :key="idx" class="flex items-start gap-3 p-4 bg-slate-50 rounded-xl border border-slate-100">
                                        <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">{{ idx + 1 }}</span>
                                        <p class="text-sm text-slate-700 leading-relaxed">{{ insight }}</p>
                                    </div>
                                </div>
                            </div>

                            <div v-if="selectedReport.ai_analysis.alerts && selectedReport.ai_analysis.alerts.length > 0">
                                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Alertes d'exploitation d'Agence</h4>
                                <div class="grid grid-cols-1 gap-3">
                                    <div v-for="(alert, idx) in selectedReport.ai_analysis.alerts" :key="idx" class="flex items-start gap-3 p-4 bg-amber-50 rounded-xl border border-amber-100">
                                        <svg class="text-amber-600 mt-0.5 shrink-0" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        <p class="text-sm text-amber-900 leading-relaxed">{{ alert }}</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Recommandations d'Agence</h4>
                                <div class="grid grid-cols-1 gap-3">
                                    <div v-for="(rec, idx) in selectedReport.ai_analysis.recommendations" :key="idx" class="flex items-start gap-3 p-4 bg-teal-50/50 rounded-xl border border-teal-100">
                                        <svg class="text-teal-600 mt-0.5 shrink-0" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-sm text-teal-900 leading-relaxed font-medium">{{ rec }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-center py-16 bg-slate-50 rounded-2xl border border-slate-100 text-slate-500 text-sm">
                            Aucune analyse IA disponible.
                        </div>
                    </div>

                    <!-- CHAT TAB -->
                    <div v-else-if="activeTab === 'chat'" class="flex flex-col h-full gap-4 min-h-[300px]">
                        <div class="text-slate-500 text-xs italic bg-slate-50 p-3 rounded-xl border border-slate-200">
                            🤖 Posez vos questions à l'IA d'Agence.
                        </div>
                        
                        <!-- Chat Messages -->
                        <div class="flex-1 overflow-y-auto flex flex-col gap-3 min-h-[220px] max-h-[350px] p-2 bg-slate-50/50 rounded-2xl border border-slate-100">
                            <div v-for="(msg, idx) in chatMessages" :key="idx" class="flex" :class="msg.role === 'user' ? 'justify-end' : 'justify-start'">
                                <div class="max-w-[85%] px-4 py-2.5 rounded-2xl text-sm" :class="msg.role === 'user' ? 'bg-emerald-500 text-white rounded-tr-none' : 'bg-slate-100 text-slate-800 rounded-tl-none border border-slate-200'">
                                    <p class="leading-relaxed whitespace-pre-line">{{ msg.text }}</p>
                                </div>
                            </div>
                            <div v-if="sendingChat" class="flex justify-start">
                                <div class="bg-slate-100 text-slate-500 px-4 py-3 rounded-2xl rounded-tl-none border border-slate-200 flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-ping"></span>
                                    <span class="text-xs font-semibold">L'IA de l'agence analyse le rapport...</span>
                                </div>
                            </div>
                        </div>

                        <!-- Input Bar -->
                        <div class="flex gap-2">
                            <input v-model="chatInput" @keyup.enter="sendChatMessage" type="text" placeholder="Posez une question sur les loyers de l'agence..." class="flex-1 px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none text-sm bg-slate-50">
                            <button @click="sendChatMessage" :disabled="sendingChat || !chatInput.trim()" class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 disabled:opacity-40 text-white font-bold rounded-xl text-sm transition-colors">
                                Envoyer
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Footer Download -->
                <div class="p-6 border-t border-slate-100 flex gap-3 bg-slate-50 justify-end">
                    <button @click="showDetail = false" class="px-5 py-2.5 border border-slate-200 text-slate-700 text-sm font-semibold rounded-xl hover:bg-slate-100 transition-colors">
                        Fermer
                    </button>
                    <a :href="`/api/rapports/${selectedReport.uuid}/download`" target="_blank" class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-bold rounded-xl transition-all shadow-md shadow-emerald-500/20">
                        Télécharger le PDF d'Agence
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

const reports = ref([]);
const loading = ref(true);
const generating = ref(false);
const showGenModal = ref(false);
const showDetail = ref(false);
const selectedReport = ref({});
const activeTab = ref('data');
const searchQuery = ref('');
const typeFilter = ref('all');

const form = ref({
    type: 'Loyer',
    month: 'Juin',
    year: '2026',
    use_ai: true
});

// Chat variables
const chatInput = ref('');
const chatMessages = ref([]);
const sendingChat = ref(false);

const monthsList = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

onMounted(() => {
    fetchReports();
    // Default current month selection
    const d = new Date();
    const currentMonthFr = monthsList[d.getMonth()];
    form.value.month = currentMonthFr;
});

const fetchReports = async () => {
    loading.value = true;
    try {
        const res = await fetch('/api/rapports');
        if (res.ok) {
            reports.value = await res.json();
        }
    } catch (e) {
        console.error(e);
    } finally {
        loading.value = false;
    }
};

const filteredReports = computed(() => {
    return reports.value.filter(r => {
        const matchesSearch = r.nom.toLowerCase().includes(searchQuery.value.toLowerCase());
        const matchesType = typeFilter.value === 'all' || r.type === typeFilter.value;
        return matchesSearch && matchesType;
    });
});

const openGenerateModal = () => {
    showGenModal.value = true;
};

const submitGenerate = async () => {
    generating.value = true;
    try {
        const periodeStr = `${form.value.month} ${form.value.year}`;
        const res = await fetch('/api/rapports/generate', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                type: form.value.type,
                periode: periodeStr,
                use_ai: form.value.use_ai,
            })
        });

        if (res.ok) {
            showGenModal.value = false;
            fetchReports();
        } else {
            const err = await res.json();
            alert(err.error || 'Erreur lors de la génération.');
        }
    } catch (e) {
        console.error(e);
    } finally {
        generating.value = false;
    }
};

const quickGenerate = async (type) => {
    if (confirm(`Générer le rapport ${type} pour votre agence ?`)) {
        generating.value = true;
        try {
            const d = new Date();
            const currentMonthFr = monthsList[d.getMonth()];
            const periodStr = `${currentMonthFr} 2026`;
            
            const res = await fetch('/api/rapports/generate', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    type: type,
                    periode: periodStr,
                    use_ai: true,
                })
            });

            if (res.ok) {
                fetchReports();
            } else {
                alert('Erreur lors de la génération rapide.');
            }
        } catch (e) {
            console.error(e);
        } finally {
            generating.value = false;
        }
    }
};

const deleteReport = async (id) => {
    if (confirm('Voulez-vous vraiment supprimer ce rapport ?')) {
        try {
            const res = await fetch(`/api/rapports/${id}`, {
                method: 'DELETE'
            });
            if (res.ok) {
                fetchReports();
            }
        } catch (e) {
            console.error(e);
        }
    }
};

const showReportDetail = async (r) => {
    selectedReport.value = r;
    activeTab.value = 'data';

    // Initialize chatbot message
    chatMessages.value = [
        { role: 'assistant', text: `Bonjour ! Je suis votre analyste d'agence HABITATUM. J'ai analysé les chiffres du "${r.nom}". Des questions ?` }
    ];
    showDetail.value = true;
};

// Chatbot logic
const sendChatMessage = async () => {
    if (!chatInput.value.trim() || sendingChat.value) return;

    const userText = chatInput.value;
    chatMessages.value.push({ role: 'user', text: userText });
    chatInput.value = '';
    sendingChat.value = true;

    try {
        const res = await fetch(`/api/rapports/${selectedReport.value.id}/chat`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message: userText })
        });
        if (res.ok) {
            const data = await res.json();
            chatMessages.value.push({ role: 'assistant', text: data.reply });
        }
    } catch (e) {
        console.error(e);
    } finally {
        sendingChat.value = false;
    }
};

// Utils
const formatMoney = (val) => {
    return new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2 }).format(val);
};

const isObject = (val) => {
    return val !== null && typeof val === 'object';
};

const getTypeBadge = (type) => {
    return {
        'Loyer': 'bg-emerald-100 text-emerald-800',
        'Financier': 'bg-blue-100 text-blue-800',
        'Occupation': 'bg-purple-100 text-purple-800',
        'Maintenance': 'bg-amber-100 text-amber-800',
    }[type] || 'bg-slate-100 text-slate-800';
};

const getTypeClass = (type) => {
    return {
        'Loyer': 'bg-emerald-100 text-emerald-600',
        'Financier': 'bg-blue-100 text-blue-600',
        'Occupation': 'bg-purple-100 text-purple-600',
        'Maintenance': 'bg-amber-100 text-amber-600',
    }[type] || 'bg-slate-100 text-slate-600';
};
</script>
