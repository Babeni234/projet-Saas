<template>
    <div class="flex flex-col gap-6">
        <!-- Header with Add Button -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 flex items-center gap-2">
                    Contrats de Bail
                    <span class="text-emerald-500 text-sm font-semibold bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">Module Immobilier</span>
                </h1>
                <p class="text-slate-600 mt-1">Gérer, rédiger et générer par IA les contrats de location officiels</p>
            </div>
            <button
                @click="openAddModal"
                class="flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-xl font-medium shadow-lg shadow-emerald-500/30 hover:shadow-xl hover:shadow-emerald-500/40 hover:scale-[1.02] active:scale-95 transition-all duration-200"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Créer un Contrat
            </button>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-emerald-500/20 animate-fade-in" style="animation-delay: 0ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Total Contrats</p>
                        <p class="text-3xl font-bold text-slate-900 mt-1">{{ totalContrats }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-emerald-500/20 animate-fade-in" style="animation-delay: 100ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Contrats Actifs</p>
                        <p class="text-3xl font-bold text-emerald-600 mt-1">{{ contratsActifs }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-red-500/20 animate-fade-in" style="animation-delay: 200ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Contrats Expirés</p>
                        <p class="text-3xl font-bold text-red-600 mt-1">{{ contratsExpire }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l.707.707a1 1 0 001.414-1.414L10 8.586l.707-.707a1 1 0 00-1.414-1.414L8.586 8 8.293 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-violet-500/20 animate-fade-in" style="animation-delay: 300ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Revenu Mensuel</p>
                        <p class="text-3xl font-bold text-slate-900 mt-1">{{ formatCurrency(revenuMensuel) }}</p>
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

        <!-- Search Bar -->
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-4 border border-slate-100">
            <div class="relative">
                <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Rechercher un contrat..."
                    class="w-full pl-12 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                >
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 overflow-hidden border border-slate-100">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Numéro</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Locataire</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Logement / Bien</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Loyer / Caution</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Début</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Fin</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Statut</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-slate-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="contrat in filteredContrats" :key="contrat.id" class="border-b border-slate-100 hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-semibold text-slate-900">{{ contrat.numero }}</td>
                            <td class="px-6 py-4 text-slate-700 font-medium">{{ contrat.locataire }}</td>
                            <td class="px-6 py-4 text-slate-600">
                                <span class="font-medium text-slate-900 block">{{ contrat.reference || 'Non spécifié' }}</span>
                                <span class="text-xs text-slate-400">{{ contrat.batiment || 'Sans bâtiment' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-emerald-600 block">{{ formatCurrency(contrat.loyer) }}</span>
                                <span class="text-xs text-slate-400">Garantie : {{ formatCurrency(contrat.caution) }}</span>
                            </td>
                            <td class="px-6 py-4 text-slate-600 text-sm">{{ formatDate(contrat.debut) }}</td>
                            <td class="px-6 py-4 text-slate-600 text-sm">{{ formatDate(contrat.fin) }}</td>
                            <td class="px-6 py-4">
                                <span :class="[
                                    'px-3 py-1.5 rounded-full text-xs font-semibold border inline-block shadow-sm',
                                    contrat.statut === 'Actif' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : contrat.statut === 'Expiré' ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-blue-50 text-blue-700 border-blue-200'
                                ]">
                                    {{ contrat.statut }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <button 
                                        v-if="contrat.content"
                                        @click="viewContractContent(contrat)"
                                        class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition"
                                        title="Consulter le contrat rédigé"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    <button @click="openEditModal(contrat)" class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-lg transition" title="Modifier">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button @click="openDeleteModal(contrat)" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Supprimer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="filteredContrats.length === 0">
                            <td colspan="8" class="text-center py-12 text-slate-400">
                                Aucun contrat de bail enregistré ou correspondant à la recherche.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Reference Template Configuration Card -->
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Exemplaire de Contrat de Référence (Template IA)
                    </h3>
                    <p class="text-sm text-slate-500 mt-1">Ce modèle est utilisé par l'IA Gemini comme structure contractuelle de base pour générer de nouveaux contrats.</p>
                </div>
                <button 
                    @click="saveExemplaire" 
                    class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-sm font-medium shadow transition"
                >
                    Enregistrer le modèle
                </button>
            </div>
            <textarea 
                v-model="exemplaireContrat" 
                rows="8" 
                class="w-full p-4 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent font-mono text-sm text-slate-700 bg-slate-50"
                placeholder="Rédigez ou collez votre exemplaire de contrat type ici..."
            ></textarea>
        </div>

        <!-- Add/Edit Modal (Select Pending Assignments) -->
        <div v-if="showModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-5xl w-full flex flex-col md:flex-row h-[75vh] max-h-[580px] overflow-hidden animate-scale-up">
                
                <!-- Left Column: Parameters and Actions -->
                <div class="flex-1 p-6 overflow-y-auto flex flex-col justify-between border-r border-slate-100">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-bold text-slate-900">{{ editingContrat ? 'Modifier' : 'Créer un' }} Contrat</h2>
                            <button @click="closeModal" class="text-slate-400 hover:text-slate-600 md:hidden">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="space-y-4">
                            <!-- Dropdown for Locataire (Only on Creation mode) -->
                            <div v-if="!editingContrat">
                                <label class="block text-xs font-semibold text-slate-500 mb-1">Locataire en attente de bail</label>
                                <div v-if="pendingAssignments.length === 0" class="bg-amber-50 border border-amber-200 p-3 rounded-xl text-amber-800 text-xs">
                                    Aucun locataire avec une affectation "En attente". Veuillez d'abord créer une affectation dans le module **Affectation des logements**.
                                </div>
                                <select 
                                    v-else
                                    v-model="formData.locataire" 
                                    @change="onTenantChange" 
                                    class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition bg-white text-sm"
                                >
                                    <option value="">Sélectionner un locataire</option>
                                    <option v-for="a in pendingAssignments" :key="a.id" :value="a.locataire">
                                        {{ a.locataire }} (Logement: {{ a.reference }}, Loyer: {{ a.loyer }}€)
                                    </option>
                                </select>
                            </div>

                            <!-- Input for edit mode -->
                            <div v-else>
                                <label class="block text-xs font-semibold text-slate-500 mb-1">Locataire</label>
                                <input v-model="formData.locataire" type="text" disabled class="w-full px-4 py-2 border border-slate-200 rounded-xl bg-slate-100 text-slate-500 cursor-not-allowed text-sm">
                            </div>

                            <!-- Dynamic Details Form: Optimized in Width -->
                            <div v-if="formData.locataire" class="space-y-4">
                                <!-- 3 Column summary info (landscape) -->
                                <div class="grid grid-cols-3 gap-3 bg-slate-50 border border-slate-100 rounded-xl p-3">
                                    <div class="text-[11px] text-slate-600">
                                        <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-wide">Bien</span>
                                        <span class="font-semibold text-slate-900 block truncate">{{ formData.batiment || 'N/A' }}</span>
                                        <span class="text-xs font-medium text-slate-500 block truncate">{{ formData.reference || 'N/A' }}</span>
                                    </div>
                                    <div class="text-[11px] text-slate-600 border-x border-slate-200/60 px-3">
                                        <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-wide">Finances</span>
                                        <span class="font-bold text-emerald-600 block">{{ formatCurrency(formData.loyer) }}</span>
                                        <span class="text-[10px] text-slate-500 block">Garantie: {{ formatCurrency(formData.caution) }}</span>
                                    </div>
                                    <div class="text-[11px] text-slate-600 pl-1">
                                        <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-wide">Type & Durée</span>
                                        <span class="font-semibold text-slate-900 block truncate">{{ formData.typeBail || 'N/A' }}</span>
                                        <span class="text-xs text-slate-500 block truncate">{{ formData.duree || 'N/A' }}</span>
                                    </div>
                                </div>

                                <!-- Two columns sub-layout for interactive fields (exploiting horizontal space) -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Col 1: Dates -->
                                    <div class="space-y-3">
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Date Début</label>
                                            <input v-model="formData.debut" type="date" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Date Fin</label>
                                            <input v-model="formData.fin" type="date" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition">
                                        </div>
                                    </div>

                                    <!-- Col 2: AI Prompt Instructions -->
                                    <div class="flex flex-col h-full">
                                        <label class="block text-[10px] font-bold text-indigo-600 uppercase tracking-wide mb-1 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                            Consignes IA (Optionnel)
                                        </label>
                                        <textarea 
                                            v-model="formData.instructions" 
                                            rows="4" 
                                            placeholder="Ex: Clause de non sous-location, animaux interdits..."
                                            class="w-full p-2.5 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white resize-none flex-1"
                                        ></textarea>
                                    </div>
                                </div>

                                <!-- Action buttons -->
                                <div class="grid grid-cols-2 gap-3 pt-1">
                                    <button 
                                        type="button"
                                        @click="openManualEditor" 
                                        class="px-3 py-2.5 border border-emerald-600 text-emerald-700 font-semibold rounded-xl text-xs hover:bg-emerald-50 transition flex items-center justify-center gap-1.5"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Rédiger manuellement
                                    </button>
                                    
                                    <button 
                                        type="button"
                                        @click="generateWithIA" 
                                        :disabled="isGenerating"
                                        class="px-3 py-2.5 bg-indigo-600 text-white font-semibold rounded-xl text-xs hover:bg-indigo-700 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-1.5 shadow shadow-indigo-500/20"
                                    >
                                        <svg v-if="isGenerating" class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <svg v-else class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                        Générer avec l'IA
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6 pt-4 border-t border-slate-100">
                        <button @click="closeModal" class="flex-1 px-4 py-2 border border-slate-200 text-slate-700 font-medium rounded-xl hover:bg-slate-50 transition text-sm">Annuler</button>
                        <button @click="saveContrat" :disabled="!editorContent" class="flex-1 px-4 py-2 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed shadow shadow-emerald-500/20 transition text-sm">Enregistrer & Activer</button>
                    </div>
                </div>

                <!-- Right Column: Document Live Preview Sheet -->
                <div class="w-full md:w-[380px] bg-slate-50 p-6 flex flex-col justify-between border-t md:border-t-0 md:border-l border-slate-100 relative">
                    <button @click="closeModal" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 hidden md:block">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    
                    <div class="text-center mb-4">
                        <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest block mb-1">Visualisation du contrat</span>
                        <p class="text-[9px] text-slate-400">Ce document doit être rédigé ou généré avant l'activation</p>
                    </div>

                    <!-- Visual sheet of paper -->
                    <div class="flex-1 bg-white rounded-xl shadow-md border border-slate-200 p-5 flex flex-col justify-between relative overflow-y-auto select-none min-h-[200px] max-h-[380px]">
                        <!-- Watermark -->
                        <div v-if="!editorContent" class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-[0.05] rotate-12">
                            <span class="text-xl font-bold tracking-widest text-slate-900">EN ATTENTE</span>
                        </div>

                        <div v-if="editorContent" class="font-serif text-[10px] text-slate-700 leading-relaxed select-text" v-html="editorContent">
                        </div>
                        <div v-else class="flex flex-col items-center justify-center h-full text-center p-4 text-slate-400 space-y-2">
                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-xs font-semibold text-slate-500">Aucun contenu rédigé</p>
                            <p class="text-[10px] text-slate-400">Sélectionnez un locataire puis générez le bail via l'IA ou rédigez-le manuellement.</p>
                        </div>
                    </div>

                    <div v-if="editorContent" class="mt-4 flex items-center justify-between text-[10px] text-emerald-600 bg-emerald-50 border border-emerald-100 p-2 rounded-lg">
                        <span class="font-semibold flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                            </svg>
                            Contrat prêt
                        </span>
                        <button type="button" @click="openManualEditor" class="font-bold text-indigo-600 hover:underline">Modifier</button>
                    </div>
                </div>

            </div>
        </div>

        <!-- TinyMCE editor modal -->
        <div v-if="showEditorModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-5xl w-full p-6 flex flex-col h-[75vh] max-h-[580px] animate-scale-up">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5" />
                            </svg>
                            Rédaction / Édition du Contrat de Bail
                        </h3>
                        <p class="text-xs text-slate-500 mt-1">Locataire: <span class="font-bold text-slate-700">{{ formData.locataire }}</span> | Loyer: {{ formData.loyer }}€ | Logement: {{ formData.reference }}</p>
                    </div>
                    <button @click="closeEditorModal" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="flex-1 overflow-hidden my-4 border border-slate-200 rounded-2xl">
                    <textarea id="contract-editor" class="w-full h-full"></textarea>
                </div>
                
                <div class="flex gap-3 justify-end pt-2">
                    <button @click="closeEditorModal" class="px-5 py-2.5 border border-slate-200 text-slate-700 font-medium rounded-xl hover:bg-slate-50 transition">Annuler</button>
                    <button @click="confirmRedaction" class="px-5 py-2.5 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 shadow-md shadow-emerald-500/10 transition">Valider la rédaction</button>
                </div>
            </div>
        </div>

        <!-- View Contract Content Modal (Read Only) -->
        <div v-if="showViewContentModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-3xl w-full p-6 flex flex-col h-[80vh] animate-scale-up">
                <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-4">
                    <div>
                        <h3 class="text-xl font-bold text-slate-950 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Contrat Actif - {{ viewingContratRef?.numero }}
                        </h3>
                        <p class="text-xs text-slate-500">Locataire : {{ viewingContratRef?.locataire }} | Loyer : {{ formatCurrency(viewingContratRef?.loyer) }}</p>
                    </div>
                    <button @click="closeViewContentModal" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="flex-1 overflow-y-auto my-4 p-6 bg-slate-50 rounded-2xl border border-slate-100 font-serif text-slate-800 leading-relaxed text-sm select-text whitespace-pre-line" v-html="viewingContratRef?.content">
                </div>
                
                <div class="flex justify-end pt-2">
                    <button @click="closeViewContentModal" class="px-5 py-2.5 bg-slate-900 text-white font-medium rounded-xl hover:bg-slate-800 transition">Fermer</button>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div v-if="showDeleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 animate-scale-up">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100 mx-auto mb-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-center text-slate-900 mb-2">Supprimer ce contrat?</h3>
                <p class="text-center text-slate-600 mb-6">Cette action est irréversible et supprimera le bail actif.</p>

                <div class="flex gap-3">
                    <button @click="closeDeleteModal" class="flex-1 px-4 py-2.5 border border-slate-200 text-slate-700 font-medium rounded-xl hover:bg-slate-50 transition">Annuler</button>
                    <button @click="confirmDelete" class="flex-1 px-4 py-2.5 bg-red-600 text-white font-medium rounded-xl hover:bg-red-700 transition">Supprimer</button>
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
                <p class="text-center text-slate-600 mb-6">Opération effectuée avec succès</p>

                <button @click="closeSuccess" class="w-full px-4 py-2.5 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 shadow shadow-emerald-500/20 transition">Fermer</button>
            </div>
        </div>

        <!-- Error Modal -->
        <div v-if="showError" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 animate-scale-up">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100 mx-auto mb-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-center text-slate-900 mb-2">Erreur</h3>
                <p class="text-center text-slate-600 mb-6">{{ errorMessage }}</p>

                <button @click="closeError" class="w-full px-4 py-2.5 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 transition">Fermer</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, nextTick } from 'vue';
import axios from 'axios';
import tinymce from 'tinymce';

// Default exemplar template
const defaultExemplaire = `CONTRAT DE BAIL D'HABITATION

Entre les soussignés :
Le Bailleur : Enterprise Property Corp (Service Immobilier)
Et
Le Preneur : [Locataire]

Il a été convenu ce qui suit :
Le Bailleur donne en location au Preneur le logement désigné ci-après.

1. Désignation du bien : Logement [Logement] situé dans le bâtiment [Bâtiment].
2. Loyer mensuel : [Loyer] euros hors charges, payable mensuellement.
3. Dépôt de garantie (Caution) : [Caution] euros.
4. Durée du bail : [Durée] à compter du [Date Début].

Fait en double exemplaire, le [Date].

Signatures :
Le Bailleur                       Le Preneur`;

// Load/Save Exemplaire de reference
const exemplaireContrat = ref(localStorage.getItem('immobilier_exemplaire_contrat') || defaultExemplaire);
const saveExemplaire = () => {
    localStorage.setItem('immobilier_exemplaire_contrat', exemplaireContrat.value);
    alert("Modèle exemplaire de contrat de référence enregistré avec succès !");
};

// Default leases list
const defaultContrats = [
    { id: 1, numero: 'CTR-001', locataire: 'Jean Dupont', loyer: 800, caution: 1600, debut: '2026-01-01', fin: '2027-01-01', statut: 'Actif', reference: 'APT-A101', batiment: 'Immeuble A', duree: '1 an', typeBail: 'Habitation', content: '<h3>CONTRAT DE BAIL D\'HABITATION</h3><p>Entre Enterprise Property Corp et Jean Dupont pour le logement APT-A101.</p>' },
    { id: 2, numero: 'CTR-002', locataire: 'Marie Martin', loyer: 1000, caution: 2000, debut: '2026-02-15', fin: '2029-02-15', statut: 'Actif', reference: 'APT-A201', batiment: 'Immeuble A', duree: '3 ans', typeBail: 'Commercial', content: '<h3>CONTRAT DE BAIL COMMERCIAL</h3><p>Entre Enterprise Property Corp et Marie Martin pour le logement APT-A201.</p>' },
];

const contrats = ref(JSON.parse(localStorage.getItem('immobilier_contrats')) || defaultContrats);
if (!localStorage.getItem('immobilier_contrats')) {
    localStorage.setItem('immobilier_contrats', JSON.stringify(defaultContrats));
}

const searchQuery = ref('');

// Computed values for stats
const totalContrats = computed(() => contrats.value.length);
const contratsActifs = computed(() => contrats.value.filter(c => c.statut === 'Actif').length);
const contratsExpire = computed(() => contrats.value.filter(c => c.statut === 'Expiré').length);
const revenuMensuel = computed(() => contrats.value.filter(c => c.statut === 'Actif').reduce((sum, c) => sum + c.loyer, 0));

// Filtered contracts
const filteredContrats = computed(() => {
    if (!searchQuery.value) return contrats.value;
    const query = searchQuery.value.toLowerCase();
    return contrats.value.filter(c => 
        c.numero.toLowerCase().includes(query) ||
        c.locataire.toLowerCase().includes(query) ||
        (c.reference && c.reference.toLowerCase().includes(query))
    );
});

// Load pending assignments from localStorage
const pendingAssignments = computed(() => {
    const stored = localStorage.getItem('immobilier_affectations');
    const affList = stored ? JSON.parse(stored) : [];
    return affList.filter(a => a.statut === 'En attente');
});

// Modal state refs
const showModal = ref(false);
const showDeleteModal = ref(false);
const showSuccess = ref(false);
const showError = ref(false);
const showEditorModal = ref(false);
const showViewContentModal = ref(false);

const editingContrat = ref(null);
const deletingContrat = ref(null);
const viewingContratRef = ref(null);
const successMessage = ref('');
const errorMessage = ref('');

const isGenerating = ref(false);
const editorContent = ref('');

// Form Data Model
const formData = ref({
    locataire: '',
    loyer: '',
    caution: '',
    debut: '',
    fin: '',
    statut: 'Actif',
    reference: '',
    batiment: '',
    duree: '1 an',
    typeBail: 'Habitation',
    instructions: '',
});

// Fetch pending assignment details when tenant is selected
const onTenantChange = () => {
    const selected = pendingAssignments.value.find(a => a.locataire === formData.value.locataire);
    if (selected) {
        formData.value.loyer = selected.loyer;
        formData.value.caution = selected.caution;
        formData.value.debut = selected.dateEffet;
        formData.value.reference = selected.reference;
        formData.value.batiment = selected.batiment;
        formData.value.duree = selected.duree;
        formData.value.typeBail = selected.typeBail;
        
        // Default end date set to 1 year later
        if (selected.dateEffet) {
            const date = new Date(selected.dateEffet);
            date.setFullYear(date.getFullYear() + 1);
            formData.value.fin = date.toISOString().split('T')[0];
        }
    } else {
        formData.value.loyer = '';
        formData.value.caution = '';
        formData.value.debut = '';
        formData.value.fin = '';
        formData.value.reference = '';
        formData.value.batiment = '';
        formData.value.duree = '1 an';
        formData.value.typeBail = 'Habitation';
    }
    editorContent.value = ''; // Reset editor content when changing tenant
};

// Modal functions
const openAddModal = () => {
    editingContrat.value = null;
    editorContent.value = '';
    formData.value = { 
        locataire: '', 
        loyer: '', 
        caution: '', 
        debut: '', 
        fin: '', 
        statut: 'Actif',
        reference: '',
        batiment: '',
        duree: '1 an',
        typeBail: 'Habitation',
        instructions: ''
    };
    showModal.value = true;
};

const openEditModal = (contrat) => {
    editingContrat.value = contrat;
    editorContent.value = contrat.content || '';
    formData.value = { ...contrat, instructions: '' };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editingContrat.value = null;
};

const openDeleteModal = (contrat) => {
    deletingContrat.value = contrat;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    deletingContrat.value = null;
};

// TinyMCE Editor integration
const initTinyMCE = () => {
    if (!tinymce) {
        alert("TinyMCE n'est pas chargé. Veuillez vérifier votre connexion.");
        return;
    }
    
    const editor = tinymce.get('contract-editor');
    if (editor) {
        tinymce.remove(editor);
    }
    
    tinymce.init({
        selector: '#contract-editor',
        license_key: 'gpl',
        skin_url: 'https://cdn.jsdelivr.net/npm/tinymce@7.2.0/skins/ui/oxide',
        content_css: 'https://cdn.jsdelivr.net/npm/tinymce@7.2.0/skins/content/default/content.css',
        height: 320,
        menubar: false,
        branding: false,
        plugins: 'advlist autolink lists link charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime table code help wordcount',
        toolbar: 'undo redo | blocks | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | code',
        setup: (editor) => {
            editor.on('init', () => {
                editor.setContent(editorContent.value || '');
            });
            editor.on('change keyup', () => {
                editorContent.value = editor.getContent();
            });
        }
    });
};

const openManualEditor = () => {
    if (!formData.value.locataire) {
        alert("Veuillez d'abord sélectionner un locataire.");
        return;
    }

    if (!editorContent.value) {
        editorContent.value = buildInitialContractFromTemplate();
    }
    
    showEditorModal.value = true;
    nextTick(() => {
        initTinyMCE();
    });
};

const buildInitialContractFromTemplate = () => {
    let tpl = exemplaireContrat.value;
    tpl = tpl.replace(/\[Locataire\]/g, formData.value.locataire || 'Non spécifié');
    tpl = tpl.replace(/\[Logement\]/g, formData.value.reference || 'Non spécifié');
    tpl = tpl.replace(/\[Bâtiment\]/g, formData.value.batiment || 'Non spécifié');
    tpl = tpl.replace(/\[Loyer\]/g, formData.value.loyer || '0');
    tpl = tpl.replace(/\[Caution\]/g, formData.value.caution || '0');
    tpl = tpl.replace(/\[Durée\]/g, formData.value.duree || '1 an');
    tpl = tpl.replace(/\[Date Début\]/g, formatDate(formData.value.debut) || 'Non spécifiée');
    tpl = tpl.replace(/\[Date\]/g, getTodayDate());
    return tpl.replace(/\n/g, '<br>'); // Convert newlines to HTML br tags
};

const generateWithIA = async () => {
    if (!formData.value.locataire) {
        alert("Veuillez d'abord sélectionner un locataire");
        return;
    }
    
    isGenerating.value = true;
    try {
        const payload = {
            locataire: formData.value.locataire,
            loyer: formData.value.loyer,
            caution: formData.value.caution,
            debut: formData.value.debut,
            fin: formData.value.fin,
            reference: formData.value.reference,
            batiment: formData.value.batiment,
            duree: formData.value.duree,
            typeBail: formData.value.typeBail,
            template: exemplaireContrat.value,
            instructions: formData.value.instructions,
        };
        
        const response = await axios.post('/api/ai/generate-contract', payload);
        if (response.data && response.data.success) {
            editorContent.value = response.data.contract;
            showEditorModal.value = true;
            nextTick(() => {
                initTinyMCE();
            });
        } else {
            errorMessage.value = response.data.message || "Impossible de générer le contrat.";
            showError.value = true;
        }
    } catch (e) {
        console.error(e);
        errorMessage.value = e.response?.data?.message || "Une erreur s'est produite lors de l'appel de l'API Gemini.";
        showError.value = true;
    } finally {
        isGenerating.value = false;
    }
};

const confirmRedaction = () => {
    const editor = tinymce.get('contract-editor');
    if (editor) {
        editorContent.value = editor.getContent();
        tinymce.remove(editor);
    }
    showEditorModal.value = false;
};

const closeEditorModal = () => {
    const editor = tinymce.get('contract-editor');
    if (editor) {
        tinymce.remove(editor);
    }
    showEditorModal.value = false;
};

const viewContractContent = (contrat) => {
    viewingContratRef.value = contrat;
    showViewContentModal.value = true;
};

const closeViewContentModal = () => {
    showViewContentModal.value = false;
    viewingContratRef.value = null;
};

// Save contract and perform state updates
const saveContrat = () => {
    if (!formData.value.locataire || !formData.value.loyer) {
        errorMessage.value = 'Veuillez remplir tous les champs obligatoires';
        showError.value = true;
        return;
    }

    if (!editorContent.value) {
        errorMessage.value = 'Veuillez rédiger ou générer le contrat de bail avant de l\'enregistrer.';
        showError.value = true;
        return;
    }

    const contractData = {
        id: editingContrat.value ? editingContrat.value.id : Math.max(...contrats.value.map(c => c.id), 0) + 1,
        numero: editingContrat.value ? editingContrat.value.numero : `CTR-${String(contrats.value.length + 1).padStart(3, '0')}`,
        locataire: formData.value.locataire,
        loyer: Number(formData.value.loyer),
        caution: Number(formData.value.caution || 0),
        debut: formData.value.debut,
        fin: formData.value.fin,
        statut: 'Actif',
        reference: formData.value.reference,
        batiment: formData.value.batiment,
        duree: formData.value.duree,
        typeBail: formData.value.typeBail,
        content: editorContent.value,
    };

    if (editingContrat.value) {
        const index = contrats.value.findIndex(c => c.id === editingContrat.value.id);
        if (index !== -1) {
            contrats.value[index] = contractData;
        }
        successMessage.value = 'Contrat modifié avec succès';
    } else {
        contrats.value.unshift(contractData);
        successMessage.value = 'Contrat enregistré & activé avec succès !';
        
        // --- Synchronization flow ---
        
        // 1. Update assignment (affectation) status from 'En attente' to 'Actif'
        const storedAff = localStorage.getItem('immobilier_affectations');
        if (storedAff) {
            const affList = JSON.parse(storedAff);
            const affIdx = affList.findIndex(a => a.locataire.toLowerCase() === formData.value.locataire.toLowerCase() && a.statut === 'En attente');
            if (affIdx !== -1) {
                affList[affIdx].statut = 'Actif';
                localStorage.setItem('immobilier_affectations', JSON.stringify(affList));
            }
        }

        // 2. Set logement status to 'Occupé'
        const storedLog = localStorage.getItem('immobilier_logements');
        if (storedLog) {
            const logList = JSON.parse(storedLog);
            const logIdx = logList.findIndex(l => l.reference === formData.value.reference);
            if (logIdx !== -1) {
                logList[logIdx].statut = 'Occupé';
                localStorage.setItem('immobilier_logements', JSON.stringify(logList));
            }
        }

        // 3. Set locataire status to 'Actif'
        const storedLoc = localStorage.getItem('immobilier_locataires');
        if (storedLoc) {
            const locList = JSON.parse(storedLoc);
            const locIdx = locList.findIndex(l => l.nom.toLowerCase() === formData.value.locataire.toLowerCase());
            if (locIdx !== -1) {
                locList[locIdx].statut = 'Actif';
                locList[locIdx].logement = formData.value.reference;
                locList[locIdx].garantie = Number(formData.value.caution);
                localStorage.setItem('immobilier_locataires', JSON.stringify(locList));
            }
        }
    }

    localStorage.setItem('immobilier_contrats', JSON.stringify(contrats.value));
    
    closeModal();
    showSuccess.value = true;
};

const confirmDelete = () => {
    const index = contrats.value.findIndex(c => c.id === deletingContrat.value.id);
    if (index !== -1) {
        const target = contrats.value[index];
        contrats.value.splice(index, 1);
        
        // Synchronize on delete: logement goes back to 'Libre', locataire goes back to 'Inactif'
        const storedLog = localStorage.getItem('immobilier_logements');
        if (storedLog && target.reference) {
            const logList = JSON.parse(storedLog);
            const logIdx = logList.findIndex(l => l.reference === target.reference);
            if (logIdx !== -1) {
                logList[logIdx].statut = 'Libre';
                localStorage.setItem('immobilier_logements', JSON.stringify(logList));
            }
        }
        
        const storedLoc = localStorage.getItem('immobilier_locataires');
        if (storedLoc && target.locataire) {
            const locList = JSON.parse(storedLoc);
            const locIdx = locList.findIndex(l => l.nom.toLowerCase() === target.locataire.toLowerCase());
            if (locIdx !== -1) {
                locList[locIdx].statut = 'Inactif';
                locList[locIdx].logement = 'Aucun';
                localStorage.setItem('immobilier_locataires', JSON.stringify(locList));
            }
        }

        const storedAff = localStorage.getItem('immobilier_affectations');
        if (storedAff && target.locataire) {
            const affList = JSON.parse(storedAff);
            const affIdx = affList.findIndex(a => a.locataire.toLowerCase() === target.locataire.toLowerCase() && a.statut === 'Actif');
            if (affIdx !== -1) {
                affList[affIdx].statut = 'Terminé';
                localStorage.setItem('immobilier_affectations', JSON.stringify(affList));
            }
        }

        localStorage.setItem('immobilier_contrats', JSON.stringify(contrats.value));
        successMessage.value = 'Contrat supprimé avec succès';
        closeDeleteModal();
        showSuccess.value = true;
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

const formatDate = (dateStr) => {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return new Intl.DateTimeFormat('fr-FR', { day: '2-digit', month: '2-digit', year: 'numeric' }).format(date);
};

const getTodayDate = () => {
    return new Intl.DateTimeFormat('fr-FR', { day: '2-digit', month: '2-digit', year: 'numeric' }).format(new Date());
};
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

.animate-scale-up {
    animation: scaleUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
</style>
