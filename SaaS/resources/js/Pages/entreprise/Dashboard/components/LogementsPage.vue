<template>
    <div class="flex flex-col gap-6">
        <!-- Header with Add Button -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 flex items-center gap-2">
                    Gestion des Biens Immobiliers
                    <span class="text-blue-500 text-sm font-semibold bg-blue-50 px-2.5 py-1 rounded-full border border-blue-200">Module Immobilier</span>
                </h1>
                <p class="text-slate-600 mt-1">Gérer le parc immobilier, suivre l'occupation et les revenus de location.</p>
            </div>
            <button
                @click="openAddModal"
                class="flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-xl font-medium shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 hover:scale-[1.02] active:scale-95 transition-all duration-200"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Ajouter un Bien Immobilier
            </button>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Biens -->
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-blue-500/20 animate-fade-in" style="animation-delay: 0ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Total Biens</p>
                        <p class="text-3xl font-bold text-slate-900 mt-1 animate-number">{{ totalLogements }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                        </svg>
                    </div>
                </div>
            </div>
            <!-- Biens Libres -->
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-emerald-500/20 animate-fade-in" style="animation-delay: 100ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Unités Disponibles</p>
                        <p class="text-3xl font-bold text-emerald-600 mt-1 animate-number">{{ logementsLibres }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <!-- Biens Occupés -->
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-blue-500/20 animate-fade-in" style="animation-delay: 200ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Unités Occupées</p>
                        <p class="text-3xl font-bold text-blue-600 mt-1 animate-number">{{ logementsOccupes }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                        </svg>
                    </div>
                </div>
            </div>
            <!-- Revenu Mensuel -->
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-violet-500/20 animate-fade-in" style="animation-delay: 300ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Revenu Mensuel</p>
                        <p class="text-3xl font-bold text-slate-900 mt-1 animate-number">{{ formatCurrency(revenuMensuel) }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-violet-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-violet-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.312-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.312.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Controls -->
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-4 border border-slate-100 flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="relative w-full md:flex-1">
                <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Rechercher par référence, bâtiment ou catégorie..."
                    class="w-full pl-12 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm"
                >
            </div>
            
            <div class="flex items-center gap-3 w-full md:w-auto">
                <select 
                    v-model="statusFilter" 
                    class="px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm bg-white text-slate-700 w-full md:w-44"
                >
                    <option value="">Tous les statuts</option>
                    <option value="Libre">Libre</option>
                    <option value="Occupé">Occupé</option>
                    <option value="Réservé">Réservé</option>
                </select>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 overflow-hidden border border-slate-100">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50">
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Référence</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Bâtiment / Immeuble</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Catégorie</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Étage / Surface</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Loyer Mensuel</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Statut d'Occupation</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-slate-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="logement in filteredLogements" :key="logement.id" class="hover:bg-slate-50/80 transition-all duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-900 block">{{ logement.reference }}</span>
                                        <span class="text-[10px] text-slate-400" v-if="logement.agency_name">Géré par : {{ logement.agency_name }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-slate-700">{{ logement.batiment || 'Sans bâtiment' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-800 font-medium">{{ logement.categorie || 'Non spécifiée' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-700">Niveau : {{ logement.etage !== null ? logement.etage + 'e étage' : 'Rez-de-chaussée' }}</div>
                                <div class="text-xs text-slate-400 font-medium">Superficie : {{ logement.surface }}m²</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-bold text-blue-600 text-sm bg-blue-50 border border-blue-100 px-3 py-1.5 rounded-xl">
                                    {{ formatCurrency(logement.loyer) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="[
                                    'px-3 py-1.5 rounded-full text-xs font-semibold border inline-flex items-center gap-1.5 shadow-sm',
                                    logement.statut === 'Libre' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' :
                                    logement.statut === 'Occupé' ? 'bg-blue-50 text-blue-700 border-blue-200' :
                                    'bg-amber-50 text-amber-700 border-amber-200'
                                ]">
                                    <span :class="[
                                        'w-1.5 h-1.5 rounded-full',
                                        logement.statut === 'Libre' ? 'bg-emerald-500' :
                                        logement.statut === 'Occupé' ? 'bg-blue-500' :
                                        'bg-amber-500'
                                    ]"></span>
                                    {{ logement.statut }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button @click="openEditModal(logement)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Modifier">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button @click="openDeleteModal(logement)" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Supprimer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="filteredLogements.length === 0">
                            <td colspan="7" class="text-center py-12 text-slate-400">
                                Aucun bien immobilier trouvé dans la base de données.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add/Edit Modal (2 Columns landscape-oriented) -->
        <div v-if="showModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full overflow-hidden animate-scale-up border border-slate-100">
                <!-- Header -->
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-blue-50 to-cyan-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">{{ editingLogement ? 'Modifier' : 'Ajouter' }} un Bien Immobilier</h2>
                            <p class="text-xs text-slate-500">Renseignez le bâtiment de rattachement, la catégorie et les paramètres.</p>
                        </div>
                    </div>
                    <button @click="closeModal" class="text-slate-400 hover:text-slate-600 transition p-1.5 hover:bg-slate-100 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Form Body -->
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Column 1: Core details -->
                    <div class="space-y-4">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-1.5 mb-2">Identification & Catégorie</h3>

                        <!-- Référence (Edit Only) -->
                        <div v-if="editingLogement">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Référence Unique</label>
                            <input 
                                :value="formData.reference" 
                                type="text" 
                                readonly 
                                disabled
                                class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 text-slate-500 cursor-not-allowed"
                            >
                        </div>

                        <!-- Bâtiment -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Bâtiment de Rattachement</label>
                            <select 
                                v-model="formData.batiment_id" 
                                @change="onBatimentChange"
                                class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white text-slate-700"
                            >
                                <option :value="null">Sélectionner un bâtiment</option>
                                <option v-for="b in systemBatiments" :key="b.id" :value="b.id">
                                    {{ b.nom }} ({{ b.ville || 'Localisation non spécifiée' }})
                                </option>
                            </select>
                        </div>

                        <!-- Catégorie -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Catégorie</label>
                            <select 
                                v-model="formData.categorie_id" 
                                class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white text-slate-700"
                            >
                                <option :value="null">Sélectionner une catégorie</option>
                                <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                                    {{ cat.nom }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Column 2: Floor & Dimensions & Financials -->
                    <div class="space-y-4">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-1.5 mb-2">Paramètres de l'unité</h3>

                        <!-- Étage / Surface -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Étage</label>
                                <input 
                                    v-model.number="formData.etage" 
                                    type="number" 
                                    placeholder="Ex: 2" 
                                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white"
                                >
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Surface (m²)</label>
                                <input 
                                    v-model.number="formData.surface" 
                                    type="number" 
                                    placeholder="Ex: 75" 
                                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white"
                                >
                            </div>
                        </div>

                        <!-- Loyer (€) -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Loyer Mensuel (€)</label>
                            <div class="relative">
                                <input 
                                    v-model.number="formData.loyer" 
                                    type="number" 
                                    placeholder="Ex: 850" 
                                    class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white"
                                >
                                <span class="absolute left-3.5 top-1/2 transform -translate-y-1/2 text-sm font-bold text-slate-400">€</span>
                            </div>
                        </div>

                        <!-- Statut -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Statut Initial</label>
                            <select 
                                v-model="formData.statut" 
                                class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white text-slate-700"
                            >
                                <option value="Libre">Libre / Disponible</option>
                                <option value="Occupé">Occupé</option>
                                <option value="Réservé">Réservé (Bail en attente)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex gap-3 justify-end">
                    <button 
                        @click="closeModal" 
                        class="px-5 py-2.5 border border-slate-200 text-slate-700 font-medium rounded-xl hover:bg-slate-100 transition text-sm"
                    >
                        Annuler
                    </button>
                    <button 
                        @click="saveLogement" 
                        :disabled="!formData.categorie_id || !formData.loyer"
                        class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-cyan-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-cyan-700 disabled:opacity-50 disabled:cursor-not-allowed shadow transition text-sm"
                    >
                        Enregistrer
                    </button>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div v-if="showDeleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 animate-scale-up">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-rose-100 mx-auto mb-4 animate-bounce">
                    <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-center text-slate-900 mb-2">Supprimer ce bien immobilier ?</h3>
                <p class="text-center text-slate-555 text-sm mb-6">Cette action supprimera définitivement le bien immobilier <strong>{{ deletingLogement?.reference }}</strong> de votre base de données.</p>

                <div class="flex gap-3">
                    <button @click="closeDeleteModal" class="flex-1 px-4 py-2.5 border border-slate-200 text-slate-700 font-medium rounded-xl hover:bg-slate-50 transition text-sm">Annuler</button>
                    <button @click="confirmDelete" class="flex-1 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-medium rounded-xl transition text-sm">Supprimer</button>
                </div>
            </div>
        </div>

        <!-- Success Modal -->
        <div v-if="showSuccess" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 animate-scale-up">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-emerald-100 mx-auto mb-4">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-center text-slate-900 mb-2">{{ successMessage }}</h3>
                <p class="text-center text-slate-500 text-sm mb-6">L'unité immobilière a bien été actualisée.</p>

                <button @click="closeSuccess" class="w-full px-4 py-2.5 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 shadow shadow-emerald-500/20 transition text-sm">Fermer</button>
            </div>
        </div>

        <!-- Error Modal -->
        <div v-if="showError" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 animate-scale-up">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-rose-100 mx-auto mb-4">
                    <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-center text-slate-900 mb-2">Erreur</h3>
                <p class="text-center text-slate-600 text-sm mb-6">{{ errorMessage }}</p>

                <button @click="closeError" class="w-full px-4 py-2.5 bg-rose-600 text-white font-semibold rounded-xl hover:bg-rose-700 transition text-sm">Fermer</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';

const logements = ref([]);
const categories = ref([]);
const systemBatiments = ref([]);

const searchQuery = ref('');
const statusFilter = ref('');

const fetchLogements = async () => {
    try {
        const response = await fetch('/api/logements', {
            headers: { 'Accept': 'application/json' }
        });
        if (response.ok) {
            logements.value = await response.json();
        }
    } catch (e) {
        console.error("Erreur lors de la récupération des biens immobiliers:", e);
    }
};

const fetchCategories = async () => {
    try {
        const response = await fetch('/api/categories', {
            headers: { 'Accept': 'application/json' }
        });
        if (response.ok) {
            categories.value = await response.json();
        }
    } catch (e) {
        console.error("Erreur lors de la récupération des catégories:", e);
    }
};

const fetchBatiments = async () => {
    try {
        const response = await fetch('/api/batiments', {
            headers: { 'Accept': 'application/json' }
        });
        if (response.ok) {
            systemBatiments.value = await response.json();
        }
    } catch (e) {
        console.error("Erreur lors de la récupération des bâtiments:", e);
    }
};

const filteredLogements = computed(() => {
    let list = logements.value;
    
    // Status Filter
    if (statusFilter.value) {
        list = list.filter(l => l.statut === statusFilter.value);
    }

    // Search Query
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        list = list.filter(l => 
            l.reference.toLowerCase().includes(query) ||
            (l.batiment && l.batiment.toLowerCase().includes(query)) ||
            (l.categorie && l.categorie.toLowerCase().includes(query))
        );
    }
    
    return list;
});

const totalLogements = computed(() => logements.value.length);
const logementsLibres = ref(0);
const logementsOccupes = ref(0);
const revenuMensuel = ref(0);

// Recalculate computed KPIs dynamically
watch(logements, (newVal) => {
    logementsLibres.value = newVal.filter(l => l.statut === 'Libre').length;
    logementsOccupes.value = newVal.filter(l => l.statut === 'Occupé').length;
    revenuMensuel.value = newVal.filter(l => l.statut === 'Occupé').reduce((sum, l) => sum + Number(l.loyer || 0), 0);
}, { immediate: true, deep: true });

const showModal = ref(false);
const showDeleteModal = ref(false);
const showSuccess = ref(false);
const showError = ref(false);
const editingLogement = ref(null);
const deletingLogement = ref(null);
const successMessage = ref('');
const errorMessage = ref('');

const formData = ref({
    reference: '',
    batiment_id: null,
    categorie_id: null,
    etage: '',
    surface: '',
    loyer: '',
    statut: 'Libre',
    agency_id: null
});

// Auto-hide error modal after 5 seconds
let errorTimeout = null;
watch(showError, (newVal) => {
    if (newVal) {
        if (errorTimeout) clearTimeout(errorTimeout);
        errorTimeout = setTimeout(() => {
            showError.value = false;
        }, 5000);
    } else {
        if (errorTimeout) {
            clearTimeout(errorTimeout);
            errorTimeout = null;
        }
    }
});

const openAddModal = () => {
    editingLogement.value = null;
    formData.value = {
        reference: '',
        batiment_id: null,
        categorie_id: null,
        etage: '',
        surface: '',
        loyer: '',
        statut: 'Libre',
        agency_id: null
    };
    showModal.value = true;
};

const openEditModal = (logement) => {
    editingLogement.value = logement;
    formData.value = {
        reference: logement.reference,
        batiment_id: logement.batiment_id,
        categorie_id: logement.categorie_id,
        etage: logement.etage !== null ? logement.etage : '',
        surface: logement.surface !== null ? logement.surface : '',
        loyer: logement.loyer,
        statut: logement.statut,
        agency_id: logement.agency_id
    };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editingLogement.value = null;
};

const openDeleteModal = (logement) => {
    deletingLogement.value = logement;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    deletingLogement.value = null;
};

const onBatimentChange = () => {
    if (formData.value.batiment_id) {
        const selectedBat = systemBatiments.value.find(b => b.id === Number(formData.value.batiment_id));
        if (selectedBat && selectedBat.agency_id) {
            formData.value.agency_id = selectedBat.agency_id;
        } else {
            formData.value.agency_id = null;
        }
    } else {
        formData.value.agency_id = null;
    }
};

const saveLogement = async () => {
    if (!formData.value.categorie_id || !formData.value.loyer) {
        errorMessage.value = 'Veuillez remplir tous les champs obligatoires (*)';
        showError.value = true;
        return;
    }

    const payload = {
        batiment_id: formData.value.batiment_id ? Number(formData.value.batiment_id) : null,
        categorie_id: Number(formData.value.categorie_id),
        etage: formData.value.etage !== '' ? Number(formData.value.etage) : null,
        surface: formData.value.surface !== '' ? Number(formData.value.surface) : null,
        loyer: Number(formData.value.loyer),
        statut: formData.value.statut || 'Libre',
        agency_id: formData.value.agency_id ? Number(formData.value.agency_id) : null,
    };

    try {
        const url = editingLogement.value
            ? `/api/logements/${editingLogement.value.id}`
            : '/api/logements';
        const method = editingLogement.value ? 'PUT' : 'POST';

        const response = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
            },
            body: JSON.stringify(payload)
        });

        if (response.ok) {
            successMessage.value = editingLogement.value
                ? 'Bien immobilier modifié avec succès'
                : 'Bien immobilier ajouté avec succès';
            closeModal();
            fetchLogements();
            showSuccess.value = true;
        } else {
            const data = await response.json();
            errorMessage.value = data.message || 'Une erreur est survenue lors de l\'enregistrement.';
            showError.value = true;
        }
    } catch (e) {
        console.error(e);
        errorMessage.value = 'Impossible de contacter le serveur.';
        showError.value = true;
    }
};

const confirmDelete = async () => {
    if (!deletingLogement.value) return;
    try {
        const response = await fetch(`/api/logements/${deletingLogement.value.id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
            }
        });

        if (response.ok) {
            successMessage.value = 'Bien immobilier supprimé avec succès';
            closeDeleteModal();
            fetchLogements();
            showSuccess.value = true;
        } else {
            const data = await response.json();
            errorMessage.value = data.message || 'Une erreur est survenue lors de la suppression.';
            showError.value = true;
        }
    } catch (e) {
        console.error(e);
        errorMessage.value = 'Impossible de contacter le serveur.';
        showError.value = true;
    }
};

const closeSuccess = () => {
    showSuccess.value = false;
};

const closeError = () => {
    showError.value = false;
};

// Formatting helpers
const formatCurrency = (val) => {
    if (val === undefined || val === null || isNaN(val)) return '0 €';
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR', maximumFractionDigits: 0 }).format(val);
};

const loadAllData = () => {
    fetchLogements();
    fetchCategories();
    fetchBatiments();
};

onMounted(() => {
    loadAllData();
    window.addEventListener('enterprise:refresh', loadAllData);
});

onUnmounted(() => {
    window.removeEventListener('enterprise:refresh', loadAllData);
});
</script>

<style scoped>
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeIn 0.5s ease-out forwards;
    opacity: 0;
}

.animate-number {
    animation: countUp 0.5s ease-out;
}

@keyframes countUp {
    from {
        opacity: 0;
        transform: scale(0.5);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

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
    animation: scaleUp 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
</style>
