<template>
    <div class="flex flex-col gap-6">
        <!-- Header with Add Button -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 flex items-center gap-2">
                    Affectation des Logements
                    <span class="text-emerald-500 text-sm font-semibold bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">Module Immobilier</span>
                </h1>
                <p class="text-slate-600 mt-1">Attribuer et gérer l'occupation des logements par les locataires</p>
            </div>
            <button
                @click="openAddModal"
                class="flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-xl font-medium shadow-lg shadow-emerald-500/30 hover:shadow-xl hover:shadow-emerald-500/40 hover:scale-[1.02] active:scale-95 transition-all duration-200"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                Nouvelle Affectation
            </button>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Taux d'occupation -->
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-[1.03] hover:shadow-xl hover:shadow-emerald-500/10 animate-fade-in" style="animation-delay: 0ms">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Taux d'Occupation</p>
                        <p class="text-3xl font-bold text-slate-900 mt-1">{{ occupancyRate }}%</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-inner">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2">
                    <div class="bg-emerald-500 h-2 rounded-full transition-all duration-500" :style="{ width: occupancyRate + '%' }"></div>
                </div>
            </div>

            <!-- Logements Affectés -->
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-[1.03] hover:shadow-xl hover:shadow-blue-500/10 animate-fade-in" style="animation-delay: 100ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Logements Occupés</p>
                        <p class="text-3xl font-bold text-blue-600 mt-1">{{ activeLeases }} <span class="text-sm text-slate-400 font-medium">/ {{ totalLogements }}</span></p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 shadow-inner">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-slate-400 mt-4">{{ totalVacant }} logements vacants prêts à l'affectation</p>
            </div>

            <!-- Cautions Encaissées -->
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-[1.03] hover:shadow-xl hover:shadow-violet-500/10 animate-fade-in" style="animation-delay: 200ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Garanties Encaissées</p>
                        <p class="text-3xl font-bold text-violet-600 mt-1">{{ formatCurrency(totalDeposits) }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-violet-50 flex items-center justify-center text-violet-600 shadow-inner">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-slate-400 mt-4">Fonds sécurisés déposés en banque</p>
            </div>

            <!-- Revenus Mensuels Estimés -->
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-[1.03] hover:shadow-xl hover:shadow-teal-500/10 animate-fade-in" style="animation-delay: 300ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Revenu Mensuel</p>
                        <p class="text-3xl font-bold text-slate-900 mt-1">{{ formatCurrency(monthlyRevenue) }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600 shadow-inner">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-slate-400 mt-4">Loyers théoriques sur affectations actives</p>
            </div>
        </div>

        <!-- Filters and Search Bar -->
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-5 border border-slate-100 flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="relative w-full md:w-96">
                <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Rechercher un locataire, logement..."
                    class="w-full pl-12 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                >
            </div>
            
            <div class="flex flex-wrap gap-3 w-full md:w-auto">
                <select 
                    v-model="filterBuilding"
                    class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                >
                    <option value="">Tous les bâtiments</option>
                    <option v-for="b in buildingsList" :key="b" :value="b">{{ b }}</option>
                </select>

                <select 
                    v-model="filterStatus"
                    class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                >
                    <option value="">Tous les statuts</option>
                    <option value="Actif">Actif</option>
                    <option value="Terminé">Terminé</option>
                    <option value="À venir">À venir</option>
                </select>
            </div>
        </div>

        <!-- Table Grid Section -->
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 overflow-hidden border border-slate-100">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Locataire</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Logement</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Bâtiment</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Date d'effet</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Bail / Durée</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Loyer / Caution</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Statut</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-slate-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="aff in filteredAffectations" :key="aff.id" class="border-b border-slate-100 hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold shadow shadow-emerald-500/20">
                                        {{ getInitials(aff.locataire) }}
                                    </div>
                                    <div>
                                        <span class="font-semibold text-slate-900 block">{{ aff.locataire }}</span>
                                        <span class="text-xs text-slate-500">Locataire Officiel</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span class="font-medium text-slate-900">{{ aff.reference }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600 font-medium">{{ aff.batiment }}</td>
                            <td class="px-6 py-4 text-slate-600 text-sm">{{ formatDate(aff.dateEffet) }}</td>
                            <td class="px-6 py-4">
                                <span class="text-slate-700 block font-medium">{{ aff.typeBail }}</span>
                                <span class="text-xs text-slate-500">{{ aff.duree }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-900 block">{{ formatCurrency(aff.loyer) }}</span>
                                <span class="text-xs text-slate-500">Caution : {{ formatCurrency(aff.caution) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span :class="[
                                    'px-3 py-1.5 rounded-full text-xs font-semibold border inline-block shadow-sm',
                                    aff.statut === 'Actif' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : aff.statut === 'Terminé' ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-amber-50 text-amber-700 border-amber-200'
                                ]">
                                    {{ aff.statut }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <button 
                                        @click="printReceipt(aff)"
                                        class="p-2 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition"
                                        title="Imprimer la fiche de bail"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                    </button>
                                    <button 
                                        v-if="aff.statut !== 'Terminé'"
                                        @click="openTerminateModal(aff)" 
                                        class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition"
                                        title="Terminer le bail"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="filteredAffectations.length === 0">
                            <td colspan="8" class="text-center py-12 text-slate-400">
                                Aucun logement affecté correspondant aux critères de recherche.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add/Edit Split-Screen Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-5xl w-full overflow-hidden flex flex-col md:flex-row h-[90vh] md:h-[80vh] animate-slide-up">
                
                <!-- Left panel: Form Inputs -->
                <div class="flex-1 p-8 overflow-y-auto flex flex-col border-r border-slate-100">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-slate-900">Nouvelle Affectation</h2>
                        <button @click="closeModal" class="text-slate-400 hover:text-slate-600 md:hidden">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4 flex-1">
                        <!-- Step 1: Batiment & Logement -->
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-3">
                            <h3 class="text-sm font-semibold text-slate-800 uppercase tracking-wider">Étape 1: Choix du Logement</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Bâtiment</label>
                                    <select 
                                        v-model="formData.batiment" 
                                        @change="onBuildingChange"
                                        class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                                    >
                                        <option value="">Sélectionner</option>
                                        <option v-for="b in buildingsList" :key="b" :value="b">{{ b }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Logement vacant</label>
                                    <select 
                                        v-model="formData.reference" 
                                        @change="onLogementChange"
                                        :disabled="!formData.batiment"
                                        class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent disabled:opacity-50 disabled:cursor-not-allowed transition"
                                    >
                                        <option value="">Sélectionner</option>
                                        <option v-for="l in availableLogements" :key="l.id" :value="l.reference">{{ l.reference }} ({{ l.categorie }})</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Locataire -->
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-3">
                            <h3 class="text-sm font-semibold text-slate-800 uppercase tracking-wider">Étape 2: Choix du Locataire</h3>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Locataire</label>
                                <select 
                                    v-model="formData.locataire" 
                                    class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                                >
                                    <option value="">Sélectionner un locataire</option>
                                    <option v-for="t in tenantsList" :key="t" :value="t">{{ t }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- Step 3: Finance & Contrat -->
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-3">
                            <h3 class="text-sm font-semibold text-slate-800 uppercase tracking-wider">Étape 3: Conditions Financières</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Loyer mensuel (€)</label>
                                    <input 
                                        v-model.number="formData.loyer" 
                                        type="number" 
                                        class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                                    >
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Dépôt de garantie (€)</label>
                                    <input 
                                        v-model.number="formData.caution" 
                                        type="number" 
                                        class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                                    >
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Date d'effet</label>
                                    <input 
                                        v-model="formData.dateEffet" 
                                        type="date" 
                                        class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                                    >
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Type de bail</label>
                                    <select 
                                        v-model="formData.typeBail" 
                                        class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                                    >
                                        <option value="Habitation">Bail d'Habitation</option>
                                        <option value="Commercial">Bail Commercial</option>
                                        <option value="Professionnel">Bail Professionnel</option>
                                        <option value="Mixte">Bail Mixte</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Durée du bail</label>
                                    <select 
                                        v-model="formData.duree" 
                                        class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                                    >
                                        <option value="1 an">1 an</option>
                                        <option value="2 ans">2 ans</option>
                                        <option value="3 ans">3 ans</option>
                                        <option value="Indéterminée">Indéterminée</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Cycle de paiement</label>
                                    <select 
                                        v-model="formData.cyclePaiement" 
                                        class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                                    >
                                        <option value="Mensuel">Mensuel</option>
                                        <option value="Trimestriel">Trimestriel</option>
                                        <option value="Annuel">Annuel</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button @click="closeModal" class="flex-1 px-4 py-3 border border-slate-200 text-slate-700 font-medium rounded-xl hover:bg-slate-50 transition">Annuler</button>
                        <button @click="saveAffectation" class="flex-1 px-4 py-3 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 shadow shadow-emerald-500/20 transition">Valider l'affectation</button>
                    </div>
                </div>

                <!-- Right panel: Document Live Preview -->
                <div class="hidden md:flex flex-col w-96 bg-slate-50 p-8 justify-center items-center border-l border-slate-100 relative">
                    <button @click="closeModal" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    
                    <div class="text-center mb-6">
                        <span class="text-xs font-bold text-indigo-500 uppercase tracking-widest block mb-1">Aperçu en direct</span>
                        <p class="text-xs text-slate-400">Ce document se met à jour au fil de votre saisie</p>
                    </div>

                    <!-- Visual sheet of paper -->
                    <div class="w-full aspect-[1/1.4] bg-white rounded-xl shadow-xl border border-slate-200/80 p-5 flex flex-col justify-between relative select-none">
                        <!-- Watermark -->
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-[0.03] rotate-45 select-none">
                            <span class="text-3xl font-bold tracking-widest text-slate-900">SIMULATION - BAIL</span>
                        </div>

                        <div class="space-y-4">
                            <!-- Paper Header -->
                            <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-5 h-5 rounded bg-emerald-500 flex items-center justify-center text-[10px] text-white font-bold">E</div>
                                    <span class="text-[9px] font-bold text-slate-700 tracking-wider">ENTERPRISE SAAS</span>
                                </div>
                                <span class="text-[8px] bg-slate-100 px-1.5 py-0.5 rounded text-slate-500 font-semibold uppercase">Fiche de Bail</span>
                            </div>

                            <!-- Document Title -->
                            <div class="text-center space-y-1">
                                <h4 class="text-[12px] font-bold text-slate-900 tracking-wider">CONTRAT DE LOCATION</h4>
                                <p class="text-[8px] text-slate-400 uppercase tracking-wide">Simulation d'Affectation Immobilière</p>
                            </div>

                            <!-- Parties Section -->
                            <div class="space-y-1.5 mt-2">
                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-wider block">1. Parties contractantes</span>
                                <div class="text-[9px] text-slate-700 space-y-0.5 pl-2 border-l border-emerald-500">
                                    <p><span class="font-semibold text-slate-500">Bailleur :</span> Enterprise Management</p>
                                    <p><span class="font-semibold text-slate-500">Preneur :</span> {{ formData.locataire || 'Non spécifié' }}</p>
                                </div>
                            </div>

                            <!-- Property Section -->
                            <div class="space-y-1.5">
                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-wider block">2. Désignation des locaux</span>
                                <div class="text-[9px] text-slate-700 space-y-0.5 pl-2 border-l border-indigo-500">
                                    <p><span class="font-semibold text-slate-500">Bâtiment :</span> {{ formData.batiment || 'Non spécifié' }}</p>
                                    <p><span class="font-semibold text-slate-500">Logement :</span> {{ formData.reference || 'Non spécifié' }}</p>
                                    <p><span class="font-semibold text-slate-500">Catégorie :</span> {{ getLogementCategory(formData.reference) }}</p>
                                </div>
                            </div>

                            <!-- Finance Section -->
                            <div class="space-y-1.5">
                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-wider block">3. Conditions financières & Dates</span>
                                <div class="text-[9px] text-slate-700 space-y-0.5 pl-2 border-l border-amber-500">
                                    <p><span class="font-semibold text-slate-500">Loyer mensuel :</span> {{ formatCurrency(formData.loyer) }}</p>
                                    <p><span class="font-semibold text-slate-500">Dépôt de garantie :</span> {{ formatCurrency(formData.caution) }}</p>
                                    <p><span class="font-semibold text-slate-500">Date d'effet :</span> {{ formatDate(formData.dateEffet) || 'Non spécifiée' }}</p>
                                    <p><span class="font-semibold text-slate-500">Durée / Cycle :</span> {{ formData.duree }} / {{ formData.cyclePaiement }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Signatures -->
                        <div class="border-t border-slate-100 pt-3 flex items-end justify-between">
                            <div class="text-[7px] text-slate-400">
                                <p>Date signature : {{ getTodayDate() }}</p>
                                <p>Valide pour accord</p>
                            </div>
                            <div class="text-right space-y-1">
                                <div class="w-16 h-8 border border-dashed border-slate-200 rounded flex items-center justify-center bg-slate-50/50">
                                    <span class="text-[6px] text-slate-300 font-semibold uppercase tracking-wider">Signature</span>
                                </div>
                                <span class="text-[7px] text-slate-500 font-medium">Bailleur & Preneur</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Terminate Lease Confirmation Modal -->
        <div v-if="showTerminateModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 animate-scale-up">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-rose-100 mx-auto mb-4">
                    <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-center text-slate-900 mb-2">Libérer le logement ?</h3>
                <p class="text-center text-slate-600 text-sm mb-6">
                    Vous êtes sur le point de terminer le bail de <span class="font-semibold text-slate-800">{{ terminatingAff?.locataire }}</span> pour le logement <span class="font-semibold text-slate-800">{{ terminatingAff?.reference }}</span>. Le logement sera marqué comme disponible/libre.
                </p>

                <div class="flex gap-3">
                    <button @click="closeTerminateModal" class="flex-1 px-4 py-2.5 border border-slate-200 text-slate-700 font-medium rounded-xl hover:bg-slate-50 transition">Annuler</button>
                    <button @click="confirmTerminateLease" class="flex-1 px-4 py-2.5 bg-rose-600 text-white font-medium rounded-xl hover:bg-rose-700 transition">Confirmer la fin</button>
                </div>
            </div>
        </div>

        <!-- Success Modal -->
        <div v-if="showSuccess" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 animate-scale-up">
                <div class="flex items-center justify-center w-14 h-14 rounded-full bg-emerald-100 mx-auto mb-4">
                    <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-center text-slate-900 mb-2">{{ successMessage }}</h3>
                <p class="text-center text-slate-500 text-sm mb-6">L'affectation locative a été enregistrée avec succès.</p>

                <button @click="closeSuccess" class="w-full py-3 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 shadow shadow-emerald-500/20 transition">Fermer</button>
            </div>
        </div>

        <!-- Print Receipt Simulation Modal -->
        <div v-if="showPrintModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full p-8 relative animate-scale-up">
                <button @click="closePrintModal" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                
                <h3 class="text-xl font-bold text-slate-950 mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Fiche du Bail d'Habitation
                </h3>

                <!-- Document Print View Container -->
                <div id="printable-bail" class="bg-white border-2 border-slate-100 p-8 rounded-2xl shadow-inner text-slate-800 space-y-6">
                    <div class="flex justify-between items-start border-b-2 border-slate-100 pb-5">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-white font-bold">E</div>
                            <div>
                                <span class="font-bold text-slate-950 block text-sm">Enterprise System</span>
                                <span class="text-xs text-slate-400">Gestion de patrimoine</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-bold text-emerald-600 uppercase bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">Bail Actif</span>
                            <p class="text-[10px] text-slate-400 mt-2">Bail Ref: #B-2026-0{{ printingAff?.id }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-8 text-sm">
                        <div class="space-y-1">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Propriétaire / Bailleur</span>
                            <p class="font-semibold text-slate-950">Enterprise Property Corp</p>
                            <p class="text-xs text-slate-500">Service Immobilier</p>
                            <p class="text-xs text-slate-500">immo@enterprise-saas.com</p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Locataire / Preneur</span>
                            <p class="font-semibold text-slate-950">{{ printingAff?.locataire }}</p>
                            <p class="text-xs text-slate-500">Résident officiel</p>
                            <p class="text-xs text-slate-500">Contrat Individuel</p>
                        </div>
                    </div>

                    <div class="border-y border-slate-100 py-4 space-y-2 text-sm">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Désignation du bien loué</span>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <span class="text-xs text-slate-500 block">Logement</span>
                                <span class="font-semibold text-slate-900">{{ printingAff?.reference }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-slate-500 block">Bâtiment</span>
                                <span class="font-semibold text-slate-900">{{ printingAff?.batiment }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-slate-500 block">Type de Bail</span>
                                <span class="font-semibold text-slate-900">{{ printingAff?.typeBail }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2 text-sm">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Conditions financières & Durée</span>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <span class="text-xs text-slate-500 block">Loyer Mensuel</span>
                                <span class="font-bold text-slate-950 text-base">{{ formatCurrency(printingAff?.loyer) }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-slate-500 block">Dépôt de Garantie</span>
                                <span class="font-bold text-slate-950 text-base">{{ formatCurrency(printingAff?.caution) }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-slate-500 block">Date d'effet / Durée</span>
                                <span class="font-semibold text-slate-900 block">{{ formatDate(printingAff?.dateEffet) }}</span>
                                <span class="text-[10px] text-slate-400">Durée: {{ printingAff?.duree }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 flex justify-between items-center text-xs text-slate-400">
                        <span>Fait à Douala, le {{ getTodayDate() }}</span>
                        <span class="italic">Signature et cachet du bailleur font foi</span>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button @click="closePrintModal" class="flex-1 px-4 py-2.5 border border-slate-200 text-slate-700 font-medium rounded-xl hover:bg-slate-50 transition">Fermer</button>
                    <button @click="triggerPrint" class="flex-1 px-4 py-2.5 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 shadow shadow-indigo-500/20 transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Imprimer
                    </button>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

// Mock listings (similar to LogementsPage)
const defaultLogements = [
    { id: 1, reference: 'APT-A101', batiment: 'Immeuble A', categorie: 'Appartement', standardRent: 800, statut: 'Occupé' },
    { id: 2, reference: 'APT-A102', batiment: 'Immeuble A', categorie: 'Appartement', standardRent: 900, statut: 'Libre' },
    { id: 3, reference: 'APT-A201', batiment: 'Immeuble A', categorie: 'Duplex', standardRent: 1000, statut: 'Occupé' },
    { id: 4, reference: 'APT-B101', batiment: 'Immeuble B', categorie: 'Studio', standardRent: 650, statut: 'Libre' },
    { id: 5, reference: 'APT-B102', batiment: 'Immeuble B', categorie: 'Appartement', standardRent: 950, statut: 'Occupé' },
    { id: 6, reference: 'APT-C101', batiment: 'Immeuble C', categorie: 'Loft', standardRent: 850, statut: 'Occupé' },
    { id: 7, reference: 'APT-C102', batiment: 'Immeuble C', categorie: 'Magasin', standardRent: 1500, statut: 'Libre' },
    { id: 8, reference: 'APT-D101', batiment: 'Immeuble D', categorie: 'Bureau', standardRent: 1200, statut: 'Libre' },
];

const getStoredLogements = () => {
    const stored = localStorage.getItem('immobilier_logements');
    if (stored) {
        const parsed = JSON.parse(stored);
        return parsed.map(l => ({
            ...l,
            standardRent: l.loyer || l.standardRent || 800
        }));
    }
    return defaultLogements;
};

const logements = ref(getStoredLogements());

const getStoredLocatairesNames = () => {
    const stored = localStorage.getItem('immobilier_locataires');
    if (stored) {
        const parsed = JSON.parse(stored);
        return parsed.map(l => l.nom);
    }
    return [
        'Jean Dupont',
        'Marie Martin',
        'Pierre Bernard',
        'Sophie Richard',
        'Lucas Petit',
        'Emma Leroy',
        'Alain Delon',
        'Chantal Jouanno'
    ];
};

const tenantsList = ref(getStoredLocatairesNames());

// Mock buildings list
const buildingsList = ref(['Immeuble A', 'Immeuble B', 'Immeuble C', 'Immeuble D']);

// Mock Active/Historical Assignments
const initialAffectations = [
    { id: 1, reference: 'APT-A101', batiment: 'Immeuble A', locataire: 'Jean Dupont', loyer: 800, caution: 1600, dateEffet: '2026-01-01', duree: '1 an', typeBail: 'Habitation', cyclePaiement: 'Mensuel', statut: 'Actif' },
    { id: 2, reference: 'APT-A201', batiment: 'Immeuble A', locataire: 'Marie Martin', loyer: 1000, caution: 2000, dateEffet: '2026-02-15', duree: '3 ans', typeBail: 'Commercial', cyclePaiement: 'Trimestriel', statut: 'Actif' },
    { id: 3, reference: 'APT-B102', batiment: 'Immeuble B', locataire: 'Lucas Petit', loyer: 950, caution: 1900, dateEffet: '2026-03-01', duree: 'Indéterminée', typeBail: 'Habitation', cyclePaiement: 'Mensuel', statut: 'Actif' },
    { id: 4, reference: 'APT-C101', batiment: 'Immeuble C', locataire: 'Sophie Richard', loyer: 850, caution: 1700, dateEffet: '2026-04-10', duree: '1 an', typeBail: 'Professionnel', cyclePaiement: 'Mensuel', statut: 'Actif' },
];

const affectations = ref(JSON.parse(localStorage.getItem('immobilier_affectations')) || initialAffectations);
if (!localStorage.getItem('immobilier_affectations')) {
    localStorage.setItem('immobilier_affectations', JSON.stringify(initialAffectations));
}

const saveAllToStorage = () => {
    localStorage.setItem('immobilier_affectations', JSON.stringify(affectations.value));
    localStorage.setItem('immobilier_logements', JSON.stringify(logements.value));
};

const searchQuery = ref('');
const filterBuilding = ref('');
const filterStatus = ref('');

const showModal = ref(false);
const showTerminateModal = ref(false);
const showSuccess = ref(false);
const showPrintModal = ref(false);

const successMessage = ref('');
const terminatingAff = ref(null);
const printingAff = ref(null);

const formData = ref({
    reference: '',
    batiment: '',
    locataire: '',
    loyer: '',
    caution: '',
    dateEffet: '',
    duree: '1 an',
    typeBail: 'Habitation',
    cyclePaiement: 'Mensuel',
});

// Computed Values for dashboard KPIs
const totalLogements = computed(() => logements.value.length);
const activeLeases = computed(() => affectations.value.filter(a => a.statut === 'Actif').length);
const totalVacant = computed(() => logements.value.filter(l => l.statut === 'Libre').length);
const occupancyRate = computed(() => totalLogements.value > 0 ? Math.round((activeLeases.value / totalLogements.value) * 100) : 0);
const totalDeposits = computed(() => affectations.value.filter(a => a.statut === 'Actif').reduce((sum, a) => sum + a.caution, 0));
const monthlyRevenue = computed(() => affectations.value.filter(a => a.statut === 'Actif').reduce((sum, a) => sum + a.loyer, 0));

// Filtered assignments
const filteredAffectations = computed(() => {
    return affectations.value.filter(a => {
        const matchesSearch = 
            a.locataire.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            a.reference.toLowerCase().includes(searchQuery.value.toLowerCase());
        const matchesBuilding = !filterBuilding.value || a.batiment === filterBuilding.value;
        const matchesStatus = !filterStatus.value || a.statut === filterStatus.value;
        return matchesSearch && matchesBuilding && matchesStatus;
    });
});

// Logements filter list for form select (vacant in selected building)
const availableLogements = computed(() => {
    if (!formData.value.batiment) return [];
    return logements.value.filter(l => l.batiment === formData.value.batiment && l.statut === 'Libre');
});

// Event Handlers
const onBuildingChange = () => {
    formData.value.reference = '';
    formData.value.loyer = '';
    formData.value.caution = '';
};

const onLogementChange = () => {
    const selected = logements.value.find(l => l.reference === formData.value.reference);
    if (selected) {
        formData.value.loyer = selected.standardRent;
        formData.value.caution = selected.standardRent * 2; // Prefill deposit with 2x rent
    } else {
        formData.value.loyer = '';
        formData.value.caution = '';
    }
};

const openAddModal = () => {
    formData.value = {
        reference: '',
        batiment: '',
        locataire: '',
        loyer: '',
        caution: '',
        dateEffet: getTodayIsoDate(),
        duree: '1 an',
        typeBail: 'Habitation',
        cyclePaiement: 'Mensuel',
    };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const saveAffectation = () => {
    const data = formData.value;
    if (!data.reference || !data.batiment || !data.locataire || !data.loyer || !data.dateEffet) {
        alert("Veuillez remplir tous les champs obligatoires d'étape");
        return;
    }

    // Add assignment - status defaults to 'En attente'
    const newAff = {
        id: affectations.value.length + 1,
        reference: data.reference,
        batiment: data.batiment,
        locataire: data.locataire,
        loyer: Number(data.loyer),
        caution: Number(data.caution || 0),
        dateEffet: data.dateEffet,
        duree: data.duree,
        typeBail: data.typeBail,
        cyclePaiement: data.cyclePaiement,
        statut: 'En attente',
    };
    affectations.value.unshift(newAff);

    // Set logement status to Réservé
    const logIdx = logements.value.findIndex(l => l.reference === data.reference);
    if (logIdx !== -1) {
        logements.value[logIdx].statut = 'Réservé';
    }

    saveAllToStorage();

    // Force locataire list update in storage if it doesn't contain this locataire
    const locStore = localStorage.getItem('immobilier_locataires');
    if (locStore) {
        const parsedLoc = JSON.parse(locStore);
        if (!parsedLoc.some(l => l.nom.toLowerCase() === data.locataire.toLowerCase())) {
            parsedLoc.push({
                id: Math.max(...parsedLoc.map(l => l.id), 0) + 1,
                nom: data.locataire,
                email: data.locataire.toLowerCase().replace(/\s+/g, '.') + '@email.com',
                telephone: '06 00 00 00 00',
                logement: data.reference,
                garantie: Number(data.caution || 0),
                statut: 'Inactif'
            });
            localStorage.setItem('immobilier_locataires', JSON.stringify(parsedLoc));
        }
    }

    successMessage.value = "Logement affecté avec succès (Bail en attente)";
    showModal.value = false;
    showSuccess.value = true;
};

const openTerminateModal = (aff) => {
    terminatingAff.value = aff;
    showTerminateModal.value = true;
};

const closeTerminateModal = () => {
    showTerminateModal.value = false;
    terminatingAff.value = null;
};

const confirmTerminateLease = () => {
    if (!terminatingAff.value) return;

    // Set assignment status to Terminé
    const affIdx = affectations.value.findIndex(a => a.id === terminatingAff.value.id);
    if (affIdx !== -1) {
        affectations.value[affIdx].statut = 'Terminé';
    }

    // Set logement status to Libre
    const logIdx = logements.value.findIndex(l => l.reference === terminatingAff.value.reference);
    if (logIdx !== -1) {
        logements.value[logIdx].statut = 'Libre';
    }

    // Set locataire status to Inactif in storage
    const locStore = localStorage.getItem('immobilier_locataires');
    if (locStore) {
        const parsedLoc = JSON.parse(locStore);
        const locIdx = parsedLoc.findIndex(l => l.nom.toLowerCase() === terminatingAff.value.locataire.toLowerCase());
        if (locIdx !== -1) {
            parsedLoc[locIdx].statut = 'Inactif';
            parsedLoc[locIdx].logement = 'Aucun';
            localStorage.setItem('immobilier_locataires', JSON.stringify(parsedLoc));
        }
    }

    saveAllToStorage();
    closeTerminateModal();
    successMessage.value = "Le bail a été clôturé";
    showSuccess.value = true;
};

const printReceipt = (aff) => {
    printingAff.value = aff;
    showPrintModal.value = true;
};

const closePrintModal = () => {
    showPrintModal.value = false;
    printingAff.value = null;
};

const triggerPrint = () => {
    window.print();
};

const closeSuccess = () => {
    showSuccess.value = false;
};

// Helpers
const getInitials = (name) => {
    if (!name) return 'L';
    return name.split(' ').map(n => n.charAt(0)).join('').toUpperCase();
};

const formatCurrency = (val) => {
    if (val === undefined || val === null || isNaN(val)) return '0 €';
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR', maximumFractionDigits: 0 }).format(val);
};

const formatDate = (dateStr) => {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return new Intl.DateTimeFormat('fr-FR', { day: '2-digit', month: '2-digit', year: 'numeric' }).format(date);
};

const getTodayDate = () => {
    return new Intl.DateTimeFormat('fr-FR', { day: '2-digit', month: '2-digit', year: 'numeric' }).format(new Date());
};

const getTodayIsoDate = () => {
    return new Date().toISOString().split('T')[0];
};

const getLogementCategory = (refNum) => {
    const log = logements.value.find(l => l.reference === refNum);
    return log ? log.categorie : 'Logement standard';
};
</script>

<style scoped>
@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(40px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes scaleUp {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(15px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-slide-up {
    animation: slideUp 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.animate-scale-up {
    animation: scaleUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.animate-fade-in {
    animation: fadeIn 0.4s ease-out forwards;
    opacity: 0;
}
</style>
