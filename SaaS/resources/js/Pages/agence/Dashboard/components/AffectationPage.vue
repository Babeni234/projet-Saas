<template>
    <div class="flex flex-col gap-6">
        <!-- Header with Add Button -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 flex items-center gap-2">
                    Affectation des Logements
                    <span class="text-emerald-500 text-sm font-semibold bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">Module Agence</span>
                </h1>
                <p class="text-slate-600 mt-1">Attribuer et gérer l'occupation des biens immobiliers par les locataires de votre agence</p>
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
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-[1.03]">
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
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-[1.03]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Biens Occupés / Réservés</p>
                        <p class="text-3xl font-bold text-blue-600 mt-1">{{ activeLeases }} <span class="text-sm text-slate-400 font-medium">/ {{ totalLogements }}</span></p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 shadow-inner">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-slate-400 mt-4">{{ totalVacant }} biens vacants</p>
            </div>

            <!-- Cautions Encaissées -->
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-[1.03]">
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
                <p class="text-xs text-slate-400 mt-4">Fonds sécurisés de l'agence</p>
            </div>

            <!-- Revenus Mensuels Estimés -->
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-[1.03]">
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
                <p class="text-xs text-slate-400 mt-4">Loyers théoriques des contrats actifs</p>
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
                    <option v-for="b in uniqueBuildingsList" :key="b.id" :value="b.nom">{{ b.nom }}</option>
                </select>

                <select 
                    v-model="filterStatus"
                    class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                >
                    <option value="">Tous les statuts</option>
                    <option value="Actif">Actif</option>
                    <option value="En cours d'exécution">En cours d'exécution</option>
                    <option value="Terminé">Terminé</option>
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
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Bien loué</th>
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
                                        <span class="text-xs text-slate-500">Locataire</span>
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
                                <span class="text-xs text-slate-500 block">Caution : {{ formatCurrency(aff.caution) }}</span>
                                <span v-if="aff.frais_de_contrat" class="text-xs text-teal-600 block">Frais : {{ formatCurrency(aff.frais_de_contrat) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span :class="[
                                    'px-3 py-1.5 rounded-full text-xs font-semibold border inline-block shadow-sm',
                                    aff.statut === 'Actif' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200'
                                ]">
                                    {{ aff.statut }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <button 
                                        @click="printReceipt(aff)"
                                        class="p-2 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition"
                                        title="Imprimer le bail"
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
                                Aucune affectation active ou archivée pour cette agence.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add/Edit Split-Screen Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-5xl w-full overflow-hidden flex flex-col md:flex-row h-[90vh] md:h-[80vh] animate-scale-up">
                
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
                                        v-model="formData.batiment_id" 
                                        @change="onBuildingChange"
                                        class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                                    >
                                        <option value="">Sélectionner</option>
                                        <option v-for="b in filteredBuildingsForForm" :key="b.id" :value="b.id">{{ b.nom }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Logement vacant (Libre)</label>
                                    <select 
                                        v-model="formData.logement_id" 
                                        @change="onLogementChange"
                                        :disabled="!formData.batiment_id"
                                        class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent disabled:opacity-50 disabled:cursor-not-allowed transition"
                                    >
                                        <option value="">Sélectionner</option>
                                        <option v-for="l in availableLogements" :key="l.id" :value="l.id">
                                            {{ l.reference }} ({{ l.categorie }})
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div v-if="selectedLogementDetails" class="text-xs text-slate-500 bg-slate-100 p-2.5 rounded-xl border border-slate-200/50">
                                🏠 <strong>Détails du logement :</strong> Étage {{ selectedLogementDetails.etage }} &bull; Surface : {{ selectedLogementDetails.surface }} m² &bull; Catégorie : {{ selectedLogementDetails.categorie }}
                            </div>
                        </div>

                        <!-- Step 2: Locataire -->
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-3">
                            <h3 class="text-sm font-semibold text-slate-800 uppercase tracking-wider">Étape 2: Choix du Locataire</h3>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Locataire</label>
                                <select 
                                    v-model="formData.locataire_id" 
                                    @change="onLocataireChange"
                                    class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                                >
                                    <option value="">Sélectionner un locataire</option>
                                    <option v-for="t in filteredLocatairesForForm" :key="t.id" :value="t.id">
                                        {{ t.nom }} ({{ t.email }})
                                    </option>
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
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Dépôt de garantie / Caution (€)</label>
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
                                        v-model="formData.date_debut" 
                                        type="date" 
                                        class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                                    >
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Type de bail (Automatique)</label>
                                    <input 
                                        :value="selectedTypeContratNom" 
                                        type="text" 
                                        readonly
                                        placeholder="Auto-rempli selon le logement"
                                        class="w-full px-3 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-sm text-slate-500 cursor-not-allowed focus:outline-none transition"
                                    />
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
                                        v-model="formData.cycle_paiement" 
                                        class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                                    >
                                        <option value="Mensuel">Mensuel</option>
                                        <option value="Trimestriel">Trimestriel</option>
                                        <option value="Annuel">Annuel</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Frais de contrat (€)</label>
                                    <input 
                                        v-model.number="formData.frais_de_contrat" 
                                        type="number" 
                                        class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button @click="closeModal" class="flex-1 px-4 py-3 border border-slate-200 text-slate-700 font-medium rounded-xl hover:bg-slate-50 transition">Annuler</button>
                        <button @click="saveAffectation" :disabled="saving" class="flex-1 px-4 py-3 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 shadow shadow-emerald-500/20 transition disabled:opacity-50">
                            {{ saving ? 'Validation...' : 'Valider l\'affectation' }}
                        </button>
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
                        <span class="text-xs font-bold text-emerald-500 uppercase tracking-widest block mb-1">Aperçu du bail</span>
                        <p class="text-xs text-slate-400">Généré dynamiquement</p>
                    </div>

                    <!-- Visual sheet of paper -->
                    <div class="w-full aspect-[1/1.4] bg-white rounded-xl shadow-xl border border-slate-200/80 p-5 flex flex-col justify-between relative select-none">
                        <div class="space-y-4">
                            <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                                <span class="text-[9px] font-bold text-slate-700 tracking-wider">PropertyAI</span>
                                <span class="text-[8px] bg-emerald-50 text-emerald-700 px-1.5 py-0.5 rounded font-semibold uppercase">Fiche de Bail</span>
                            </div>

                            <div class="text-center space-y-1">
                                <h4 class="text-[12px] font-bold text-slate-900 tracking-wider">CONTRAT DE LOCATION</h4>
                            </div>

                            <div class="space-y-1.5 mt-2">
                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-wider block">1. Parties</span>
                                <div class="text-[9px] text-slate-700 space-y-0.5 pl-2 border-l border-emerald-500">
                                    <p><span class="font-semibold text-slate-500">Preneur :</span> {{ selectedLocataireName }}</p>
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-wider block">2. Désignation</span>
                                <div class="text-[9px] text-slate-700 space-y-0.5 pl-2 border-l border-emerald-500">
                                    <p><span class="font-semibold text-slate-500">Bâtiment :</span> {{ selectedBuildingName }}</p>
                                    <p><span class="font-semibold text-slate-500">Logement :</span> {{ selectedLogementRef }}</p>
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-wider block">3. Conditions</span>
                                <div class="text-[9px] text-slate-700 space-y-0.5 pl-2 border-l border-emerald-500">
                                    <p><span class="font-semibold text-slate-500">Loyer :</span> {{ formatCurrency(formData.loyer) }}</p>
                                    <p><span class="font-semibold text-slate-500">Caution :</span> {{ formatCurrency(formData.caution) }}</p>
                                    <p><span class="font-semibold text-slate-500">Début :</span> {{ formData.date_debut }}</p>
                                </div>
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
                    Vous êtes sur le point de terminer le bail de <span class="font-semibold text-slate-800">{{ terminatingAff?.locataire }}</span> pour le logement <span class="font-semibold text-slate-800">{{ terminatingAff?.reference }}</span>. Le bien repassera en statut 'Libre' et le locataire sera libéré.
                </p>

                <div class="flex gap-3">
                    <button @click="closeTerminateModal" class="flex-1 px-4 py-2.5 border border-slate-200 text-slate-700 font-medium rounded-xl hover:bg-slate-50 transition">Annuler</button>
                    <button @click="confirmTerminateLease" :disabled="terminating" class="flex-1 px-4 py-2.5 bg-rose-600 text-white font-medium rounded-xl hover:bg-rose-700 transition disabled:opacity-50">
                        {{ terminating ? 'Résiliation...' : 'Confirmer la fin' }}
                    </button>
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
                <p class="text-center text-slate-500 text-sm mb-6">L'affectation a été enregistrée et mise à jour en base de données.</p>

                <button @click="closeSuccess" class="w-full py-3 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 shadow shadow-emerald-500/20 transition">Fermer</button>
            </div>
        </div>

        <!-- Print Receipt Simulation Modal -->
        <div v-if="showPrintModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full p-8 relative animate-scale-up max-h-[90vh] overflow-y-auto">
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

                <div id="printable-bail" class="bg-white border-2 border-slate-100 p-8 rounded-2xl shadow-inner text-slate-800 space-y-6">
                    <div class="flex justify-between items-start border-b-2 border-slate-100 pb-5">
                        <div>
                            <span class="font-bold text-slate-950 block text-sm">PropertyAI System</span>
                            <span class="text-xs text-slate-400">Gestion de bail</span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-bold text-emerald-600 uppercase bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">Bail Actif</span>
                            <p class="text-[10px] text-slate-400 mt-2">Bail Ref: #B-2026-0{{ printingAff?.id }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-8 text-sm">
                        <div class="space-y-1">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Preneur / Locataire</span>
                            <p class="font-semibold text-slate-950">{{ printingAff?.locataire }}</p>
                        </div>
                    </div>

                    <div class="border-y border-slate-100 py-4 space-y-2 text-sm">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Désignation du bien</span>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <span class="text-xs text-slate-500 block">Référence</span>
                                <span class="font-semibold text-slate-900">{{ printingAff?.reference }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-slate-500 block">Bâtiment</span>
                                <span class="font-semibold text-slate-900">{{ printingAff?.batiment }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-slate-500 block">Bail</span>
                                <span class="font-semibold text-slate-900">{{ printingAff?.typeBail }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2 text-sm">
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
                                <span class="text-xs text-slate-500 block">Date d'effet</span>
                                <span class="font-semibold text-slate-900 block">{{ formatDate(printingAff?.dateEffet) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button @click="closePrintModal" class="flex-1 px-4 py-2.5 border border-slate-200 text-slate-700 font-medium rounded-xl hover:bg-slate-50 transition">Fermer</button>
                    <button @click="triggerPrint" class="flex-1 px-4 py-2.5 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition flex items-center justify-center gap-2">
                        Imprimer
                    </button>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

const csrf = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

// State
const affectations = ref([]);
const dbBatiments = ref([]);
const dbLocataires = ref([]);
const dbLogements = ref([]); // All logs in database

const searchQuery = ref('');
const filterBuilding = ref('');
const filterStatus = ref('');

const showModal = ref(false);
const showTerminateModal = ref(false);
const showSuccess = ref(false);
const showPrintModal = ref(false);

const saving = ref(false);
const terminating = ref(false);

const successMessage = ref('');
const printingAff = ref(null);
const terminatingAff = ref(null);

const formData = ref({
    batiment_id: '',
    logement_id: '',
    locataire_id: '',
    type_contrat_id: '',
    loyer: 0,
    caution: 0,
    date_debut: '',
    type_bail: '',
    duree: '1 an',
    cycle_paiement: 'Mensuel',
    frais_de_contrat: 0,
});

// Load resources from Database APIs
const fetchAllData = async () => {
    try {
        // Fetch Affectations
        const resAff = await fetch('/api/affectations', { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf() } });
        if (resAff.ok) affectations.value = await resAff.json();

        // Fetch Buildings
        const resBat = await fetch('/api/batiments', { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf() } });
        if (resBat.ok) dbBatiments.value = await resBat.json();

        // Fetch Logements
        const resLog = await fetch('/api/logements', { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf() } });
        if (resLog.ok) dbLogements.value = await resLog.json();

        // Fetch Locataires
        const resLoc = await fetch('/api/locataires', { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf() } });
        if (resLoc.ok) {
            const tenants = await resLoc.json();
            dbLocataires.value = tenants.filter(t => t.statut.toLowerCase() === 'inactif');
        }
    } catch (err) {
        console.error(err);
    }
};

// Computed vacant properties of the selected building
const availableLogements = computed(() => {
    if (!formData.value.batiment_id) return [];
    return dbLogements.value.filter(
        l => l.batiment_id === Number(formData.value.batiment_id) && l.statut === 'Libre'
    );
});

// Dynamic filtering of tenants in the form by building's agency
const filteredLocatairesForForm = computed(() => {
    let list = dbLocataires.value;
    if (formData.value.batiment_id) {
        const selectedBuilding = dbBatiments.value.find(b => b.id === Number(formData.value.batiment_id));
        if (selectedBuilding) {
            list = list.filter(t => t.agency_id === selectedBuilding.agency_id);
        }
    }
    return list;
});

// Dynamic filtering of buildings in the form by tenant's agency
const filteredBuildingsForForm = computed(() => {
    let list = dbBatiments.value;
    if (formData.value.locataire_id) {
        const selectedLoc = dbLocataires.value.find(t => t.id === Number(formData.value.locataire_id));
        if (selectedLoc) {
            list = list.filter(b => b.agency_id === selectedLoc.agency_id);
        }
    }
    return list;
});

// Selected property full details
const selectedLogementDetails = computed(() => {
    if (!formData.value.logement_id) return null;
    return dbLogements.value.find(l => l.id === Number(formData.value.logement_id));
});

// Unique buildings having properties, for filtering table
const uniqueBuildingsList = computed(() => dbBatiments.value);

// Form live previews
const selectedLocataireName = computed(() => {
    const loc = dbLocataires.value.find(t => t.id === Number(formData.value.locataire_id));
    return loc ? loc.nom : 'Non spécifié';
});

const selectedBuildingName = computed(() => {
    const bat = dbBatiments.value.find(b => b.id === Number(formData.value.batiment_id));
    return bat ? bat.nom : 'Non spécifié';
});

const selectedLogementRef = computed(() => {
    const log = dbLogements.value.find(l => l.id === Number(formData.value.logement_id));
    return log ? log.reference : 'Non spécifié';
});

const selectedTypeContratNom = computed(() => {
    const log = dbLogements.value.find(l => l.id === Number(formData.value.logement_id));
    return log && log.type_contrat_nom ? log.type_contrat_nom : 'Non spécifié';
});

// KPI Calculations
const totalLogements = computed(() => dbLogements.value.length);
const activeLeases = computed(() => affectations.value.filter(a => a.statut === 'Actif' || a.statut === "En cours d'exécution").length);
const totalVacant = computed(() => dbLogements.value.filter(l => l.statut === 'Libre').length);
const occupancyRate = computed(() => {
    if (totalLogements.value === 0) return 0;
    return Math.round((activeLeases.value / totalLogements.value) * 100);
});
const totalDeposits = computed(() => affectations.value.filter(a => a.statut === 'Actif' || a.statut === "En cours d'exécution").reduce((sum, a) => sum + a.caution, 0));
const monthlyRevenue = computed(() => affectations.value.filter(a => a.statut === 'Actif' || a.statut === "En cours d'exécution").reduce((sum, a) => sum + a.loyer, 0));

// Filtered assignments list
const filteredAffectations = computed(() => {
    let list = affectations.value;

    if (filterBuilding.value) {
        list = list.filter(a => a.batiment === filterBuilding.value);
    }
    if (filterStatus.value) {
        list = list.filter(a => a.statut === filterStatus.value);
    }
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        list = list.filter(a => 
            a.locataire.toLowerCase().includes(query) ||
            a.reference.toLowerCase().includes(query) ||
            a.batiment.toLowerCase().includes(query)
        );
    }
    return list;
});

const onBuildingChange = () => {
    formData.value.logement_id = '';
    formData.value.loyer = 0;
    formData.value.caution = 0;
    formData.value.frais_de_contrat = 0;

    // Clear tenant if it doesn't belong to the selected building's agency
    if (formData.value.batiment_id) {
        const selectedBuilding = dbBatiments.value.find(b => b.id === Number(formData.value.batiment_id));
        const selectedLoc = dbLocataires.value.find(t => t.id === Number(formData.value.locataire_id));
        if (selectedBuilding && selectedLoc && selectedLoc.agency_id !== selectedBuilding.agency_id) {
            formData.value.locataire_id = '';
        }
    }
};

const onLocataireChange = () => {
    // Clear building/logement if it doesn't belong to the selected tenant's agency
    if (formData.value.locataire_id) {
        const selectedLoc = dbLocataires.value.find(t => t.id === Number(formData.value.locataire_id));
        const selectedBuilding = dbBatiments.value.find(b => b.id === Number(formData.value.batiment_id));
        if (selectedLoc && selectedBuilding && selectedBuilding.agency_id !== selectedLoc.agency_id) {
            formData.value.batiment_id = '';
            formData.value.logement_id = '';
            formData.value.loyer = 0;
            formData.value.caution = 0;
            formData.value.frais_de_contrat = 0;
        }
    }
};

// Autofill fields when property is selected
const onLogementChange = () => {
    const details = selectedLogementDetails.value;
    if (details) {
        formData.value.loyer = Number(details.loyer || 0);
        formData.value.caution = Number(details.loyer || 0);
        formData.value.type_contrat_id = details.type_contrat_id || '';
    } else {
        formData.value.loyer = 0;
        formData.value.caution = 0;
        formData.value.type_contrat_id = '';
    }
};

const openAddModal = () => {
    formData.value = {
        batiment_id: '',
        logement_id: '',
        locataire_id: '',
        type_contrat_id: '',
        loyer: 0,
        caution: 0,
        date_debut: new Date().toISOString().substring(0, 10),
        type_bail: '',
        duree: '1 an',
        cycle_paiement: 'Mensuel',
    };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const openTerminateModal = (aff) => {
    terminatingAff.value = aff;
    showTerminateModal.value = true;
};

const closeTerminateModal = () => {
    showTerminateModal.value = false;
    terminatingAff.value = null;
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

const saveAffectation = async () => {
    if (!formData.value.locataire_id || !formData.value.logement_id || !formData.value.loyer || !formData.value.date_debut || !formData.value.type_contrat_id) {
        alert("Veuillez remplir toutes les informations requises (assurez-vous que le logement a un type de contrat associé).");
        return;
    }

    saving.value = true;
    try {
        const res = await fetch('/api/affectations', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf(),
            },
            body: JSON.stringify(formData.value)
        });

        const data = await res.json();
        if (res.ok) {
            successMessage.value = 'Affectation créée avec succès';
            closeModal();
            fetchAllData();
            showSuccess.value = true;
        } else {
            alert(data.error || 'Une erreur est survenue.');
        }
    } catch (err) {
        console.error(err);
        alert('Erreur de communication avec le serveur.');
    } finally {
        saving.value = false;
    }
};

// Terminate lease assignment in DB
const confirmTerminateLease = async () => {
    terminating.value = true;
    try {
        const res = await fetch(`/api/affectations/${terminatingAff.value.id}/terminate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf(),
            }
        });

        if (res.ok) {
            successMessage.value = 'Le bail a été clôturé avec succès et le logement est désormais libre.';
            closeTerminateModal();
            fetchAllData();
            showSuccess.value = true;
        } else {
            alert('Erreur lors de la résiliation.');
        }
    } catch (err) {
        console.error(err);
        alert('Erreur de communication.');
    } finally {
        terminating.value = false;
    }
};

const closeSuccess = () => {
    showSuccess.value = false;
};

// Utils
const getInitials = (name) => {
    if (!name) return 'L';
    return name.split(' ').map(p => p[0]).slice(0, 2).join('').toUpperCase();
};

const formatDate = (dateStr) => {
    if (!dateStr) return '';
    return new Date(dateStr).toLocaleDateString('fr-FR');
};

const formatCurrency = (val) => {
    if (val === undefined || val === null || isNaN(val)) return '0 €';
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR', maximumFractionDigits: 0 }).format(val);
};

onMounted(() => {
    fetchAllData();
});
</script>

<style scoped>
@keyframes scaleUp {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
.animate-scale-up {
    animation: scaleUp 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
</style>
