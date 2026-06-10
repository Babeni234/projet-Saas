<template>
    <div class="flex flex-col gap-6">
        <!-- Header with Add Button -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 flex items-center gap-2">
                    Gestion des Locataires
                    <span class="text-amber-500 text-sm font-semibold bg-amber-50 px-2.5 py-1 rounded-full border border-amber-200">Module Immobilier</span>
                </h1>
                <p class="text-slate-600 mt-1">Gérer les résidents, coordonner les informations de contact et suivre les dépôts de garantie.</p>
            </div>
            <button
                @click="openAddModal"
                class="flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-amber-600 to-orange-600 text-white rounded-xl font-medium shadow-lg shadow-amber-500/30 hover:shadow-xl hover:shadow-amber-500/40 hover:scale-[1.02] active:scale-95 transition-all duration-200"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouveau Locataire
            </button>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Locataires -->
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-amber-500/20 animate-fade-in" style="animation-delay: 0ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Total Locataires</p>
                        <p class="text-3xl font-bold text-slate-900 mt-1 animate-number">{{ totalLocataires }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-100/80 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                        </svg>
                    </div>
                </div>
            </div>
            <!-- Locataires Actifs -->
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-emerald-500/20 animate-fade-in" style="animation-delay: 100ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Membres Actifs</p>
                        <p class="text-3xl font-bold text-emerald-600 mt-1 animate-number">{{ locatairesActifs }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-100/80 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <!-- Suspendus -->
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-rose-500/20 animate-fade-in" style="animation-delay: 200ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Suspendus / Litiges</p>
                        <p class="text-3xl font-bold text-rose-600 mt-1 animate-number">{{ locatairesSuspendus }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-rose-100/80 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <!-- Total Garanties -->
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-violet-500/20 animate-fade-in" style="animation-delay: 300ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Total Cautions</p>
                        <p class="text-3xl font-bold text-slate-900 mt-1 animate-number">{{ formatCurrency(totalGaranties) }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-violet-100/80 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-violet-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.312-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.312.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Advanced Filtering Bar -->
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-4 border border-slate-100 flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="relative w-full md:flex-1">
                <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Rechercher par nom, email ou référence de logement..."
                    class="w-full pl-12 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition text-sm"
                >
            </div>
            
            <div class="flex items-center gap-3 w-full md:w-auto">
                <select 
                    v-model="statusFilter" 
                    class="px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition text-sm bg-white text-slate-700 w-full md:w-48"
                >
                    <option value="">Tous les statuts</option>
                    <option value="Actif">Actifs</option>
                    <option value="Suspendu">Suspendus</option>
                    <option value="Inactif">Inactifs</option>
                </select>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 overflow-hidden border border-slate-100">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50">
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Locataire</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Coordonnées de Contact</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Logement Occupé</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Dépôt de Garantie</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Statut Dossier</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-slate-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="locataire in filteredLocataires" :key="locataire.id" class="hover:bg-slate-50/80 transition-all duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-amber-500 to-orange-600 flex items-center justify-center text-white font-bold text-base shadow-sm">
                                        {{ locataire.nom.charAt(0).toUpperCase() }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-slate-900">{{ locataire.nom }}</div>
                                        <div class="text-[10px] text-slate-400">ID: LOC-{{ String(locataire.id).padStart(3, '0') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1 text-sm text-slate-600">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        {{ locataire.email }}
                                    </span>
                                    <span class="flex items-center gap-1.5 text-xs text-slate-500">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        {{ locataire.telephone }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <span v-if="locataire.logement && locataire.logement !== 'Aucun'" class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                        {{ locataire.logement }}
                                    </span>
                                    <span v-else class="px-3 py-1.5 rounded-xl text-xs font-medium bg-slate-50 text-slate-400 border border-slate-100">
                                        Aucun logement
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-bold text-amber-600 text-sm bg-amber-50/60 border border-amber-100 px-3 py-1.5 rounded-xl">
                                    {{ formatCurrency(locataire.garantie) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="[
                                    'px-3 py-1.5 rounded-full text-xs font-semibold border inline-flex items-center gap-1.5 shadow-sm',
                                    locataire.statut === 'Actif' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : locataire.statut === 'Suspendu' ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-slate-50 text-slate-600 border-slate-200'
                                ]">
                                    <span :class="[
                                        'w-1.5 h-1.5 rounded-full',
                                        locataire.statut === 'Actif' ? 'bg-emerald-500' : locataire.statut === 'Suspendu' ? 'bg-rose-500' : 'bg-slate-400'
                                    ]"></span>
                                    {{ locataire.statut }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button 
                                        @click="openEditModal(locataire)" 
                                        class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition"
                                        title="Modifier la fiche"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button 
                                        @click="openDeleteModal(locataire)" 
                                        class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition"
                                        title="Supprimer locataire"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="filteredLocataires.length === 0">
                            <td colspan="6" class="text-center py-12 text-slate-400">
                                Aucun locataire trouvé correspondant à vos critères de recherche.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Premium Add/Edit Modal (2 Columns landscape-oriented) -->
        <div v-if="showModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full overflow-hidden animate-scale-up border border-slate-100">
                <!-- Header -->
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-amber-50 to-orange-50/55">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">{{ editingLocataire ? 'Modifier' : 'Ajouter' }} un Locataire</h2>
                            <p class="text-xs text-slate-500">Renseignez les informations personnelles et locatives.</p>
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
                    <!-- Column 1: Personal Details -->
                    <div class="space-y-4">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-1.5 mb-2">Informations Personnelles</h3>
                        
                        <!-- Nom Complet -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nom Complet</label>
                            <div class="relative">
                                <input 
                                    v-model="formData.nom" 
                                    type="text" 
                                    placeholder="Ex: Jean Dupont" 
                                    :class="[
                                        'w-full pl-10 pr-4 py-2.5 border rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent transition bg-white',
                                        formData.nom ? 'border-slate-200' : 'border-rose-300 bg-rose-50/20'
                                    ]"
                                >
                                <span class="absolute left-3.5 top-1/2 transform -translate-y-1/2 text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Email</label>
                            <div class="relative">
                                <input 
                                    v-model="formData.email" 
                                    type="email" 
                                    placeholder="Ex: jean.dupont@email.com" 
                                    :class="[
                                        'w-full pl-10 pr-4 py-2.5 border rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent transition bg-white',
                                        isEmailValid ? 'border-slate-200' : 'border-rose-300 bg-rose-50/20'
                                    ]"
                                >
                                <span class="absolute left-3.5 top-1/2 transform -translate-y-1/2 text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </span>
                            </div>
                            <span v-if="formData.email && !isEmailValid" class="text-[10px] text-rose-500 mt-1 block">Veuillez entrer un email valide.</span>
                        </div>

                        <!-- Téléphone -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Téléphone</label>
                            <div class="relative">
                                <input 
                                    v-model="formData.telephone" 
                                    type="tel" 
                                    placeholder="Ex: 06 12 34 56 78" 
                                    class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent transition bg-white"
                                >
                                <span class="absolute left-3.5 top-1/2 transform -translate-y-1/2 text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Column 2: Rental & Financial Details -->
                    <div class="space-y-4">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-1.5 mb-2">Détails Locatifs & Financiers</h3>

                        <!-- Logement (Dropdown selected from system units) -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Logement Assigné</label>
                            <select 
                                v-model="formData.logement" 
                                class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent transition bg-white text-slate-700"
                            >
                                <option value="Aucun">Aucun logement</option>
                                <option v-for="logement in systemLogements" :key="logement.id" :value="logement.reference">
                                    {{ logement.reference }} - {{ logement.batiment || 'Sans bâtiment' }} ({{ logement.statut }})
                                </option>
                            </select>
                            <p class="text-[10px] text-slate-400 mt-1">La liste affiche les logements créés dans le module Immobilier.</p>
                        </div>

                        <!-- Garantie (Caution) -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Dépôt de Garantie (Caution)</label>
                            <div class="relative">
                                <input 
                                    v-model="formData.garantie" 
                                    type="number" 
                                    placeholder="Ex: 1500" 
                                    class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent transition bg-white"
                                >
                                <span class="absolute left-3.5 top-1/2 transform -translate-y-1/2 text-sm font-bold text-slate-400">€</span>
                            </div>
                        </div>

                        <!-- Statut -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Statut Dossier</label>
                            <select 
                                v-model="formData.statut" 
                                class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent transition bg-white text-slate-700"
                            >
                                <option value="Actif">Actif (Dossier validé & en règle)</option>
                                <option value="Suspendu">Suspendu (Impayés ou litiges)</option>
                                <option value="Inactif">Inactif (Départ ou dossier archivé)</option>
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
                        @click="saveLocataire" 
                        :disabled="!formData.nom || !formData.email || !isEmailValid"
                        class="px-5 py-2.5 bg-gradient-to-r from-amber-600 to-orange-600 text-white font-medium rounded-xl hover:from-amber-700 hover:to-orange-700 disabled:opacity-50 disabled:cursor-not-allowed shadow transition text-sm"
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
                <h3 class="text-lg font-bold text-center text-slate-900 mb-2">Supprimer ce locataire ?</h3>
                <p class="text-center text-slate-500 mb-6 text-sm">Cette action est définitive et supprimera toutes les informations liées à <strong>{{ deletingLocataire?.nom }}</strong>.</p>

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
                <p class="text-center text-slate-500 text-sm mb-6">Fiche locataire enregistrée dans le système.</p>

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
import { ref, computed, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const currentAgencyId = computed(() => page.props.auth?.user?.employee?.agency_id);

const defaultLocataires = [
    { id: 1, nom: 'Jean Dupont', email: 'jean.dupont@email.com', telephone: '06 12 34 56 78', logement: 'APT-A101', garantie: 2400, statut: 'Actif' },
    { id: 2, nom: 'Marie Martin', email: 'marie.martin@email.com', telephone: '06 98 76 54 32', logement: 'APT-A201', garantie: 3000, statut: 'Actif' },
    { id: 3, nom: 'Pierre Bernard', email: 'pierre.bernard@email.com', telephone: '06 11 22 33 44', logement: 'APT-B101', garantie: 3600, statut: 'Suspendu' },
    { id: 4, nom: 'Sophie Richard', email: 'sophie.richard@email.com', telephone: '06 44 55 66 77', logement: 'APT-C101', garantie: 2400, statut: 'Actif' },
    { id: 5, nom: 'Lucas Petit', email: 'lucas.petit@email.com', telephone: '06 55 66 77 88', logement: 'APT-B102', garantie: 2800, statut: 'Actif' },
    { id: 6, nom: 'Emma Leroy', email: 'emma.leroy@email.com', telephone: '06 77 88 99 00', logement: 'APT-A102', garantie: 3200, statut: 'Inactif' },
];

const locataires = ref(JSON.parse(localStorage.getItem('immobilier_locataires')) || defaultLocataires);
if (!localStorage.getItem('immobilier_locataires')) {
    localStorage.setItem('immobilier_locataires', JSON.stringify(defaultLocataires));
}

const saveLocatairesToStorage = () => {
    localStorage.setItem('immobilier_locataires', JSON.stringify(locataires.value));
};

const searchQuery = ref('');
const statusFilter = ref('');

// Dynamic buildings list filtered by agency
const systemBatiments = computed(() => {
    const stored = localStorage.getItem('immobilier_batiments');
    let bats = [];
    if (stored) {
        bats = JSON.parse(stored);
    } else {
        bats = [
            { id: 1, nom: 'Immeuble A', ville: 'Paris' },
            { id: 2, nom: 'Immeuble B', ville: 'Lyon' },
            { id: 3, nom: 'Immeuble C', ville: 'Nice' },
            { id: 4, nom: 'Immeuble D', ville: 'Douala' }
        ];
    }
    const agencyId = currentAgencyId.value;
    return bats.filter(b => Number(b.agency_id) === Number(agencyId));
});

// Dynamic Lodgings List filtered by agency buildings
const systemLogements = computed(() => {
    const stored = localStorage.getItem('immobilier_logements');
    let logs = [];
    if (stored) {
        logs = JSON.parse(stored);
    } else {
        logs = [
            { id: 1, reference: 'APT-A101', batiment: 'Immeuble A', statut: 'Occupé' },
            { id: 2, reference: 'APT-A201', batiment: 'Immeuble A', statut: 'Occupé' },
            { id: 3, reference: 'APT-B101', batiment: 'Immeuble B', statut: 'Occupé' },
            { id: 4, reference: 'APT-C101', batiment: 'Immeuble C', statut: 'Occupé' },
            { id: 5, reference: 'APT-B102', batiment: 'Immeuble B', statut: 'Occupé' },
            { id: 6, reference: 'APT-A102', batiment: 'Immeuble A', statut: 'Libre' }
        ];
    }
    const agencyBuildingNames = systemBatiments.value.map(b => b.nom);
    return logs.filter(l => agencyBuildingNames.includes(l.batiment));
});

const filteredLocataires = computed(() => {
    const agencyLogementRefs = systemLogements.value.map(l => l.reference);
    const agencyId = currentAgencyId.value;
    
    let list = locataires.value.filter(l => agencyLogementRefs.includes(l.logement) || Number(l.agency_id) === Number(agencyId));
    
    // Status Filter
    if (statusFilter.value) {
        list = list.filter(l => l.statut === statusFilter.value);
    }
    
    // Search Query
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        list = list.filter(l => 
            l.nom.toLowerCase().includes(query) ||
            l.email.toLowerCase().includes(query) ||
            l.telephone.includes(query) ||
            l.logement.toLowerCase().includes(query)
        );
    }
    
    return list;
});

const totalLocataires = computed(() => filteredLocataires.value.length);
const locatairesActifs = computed(() => filteredLocataires.value.filter(l => l.statut === 'Actif').length);
const locatairesSuspendus = computed(() => filteredLocataires.value.filter(l => l.statut === 'Suspendu').length);
const totalGaranties = computed(() => filteredLocataires.value.reduce((sum, l) => sum + l.garantie, 0));

const showModal = ref(false);
const showDeleteModal = ref(false);
const showSuccess = ref(false);
const showError = ref(false);
const editingLocataire = ref(null);
const deletingLocataire = ref(null);
const successMessage = ref('');
const errorMessage = ref('');

const formData = ref({
    nom: '',
    email: '',
    telephone: '',
    logement: 'Aucun',
    garantie: 0,
    statut: 'Actif',
});

// Watch Error Alert to auto-hide after 5 seconds
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

// Client-side validations
const isEmailValid = computed(() => {
    if (!formData.value.email) return false;
    const re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return re.test(formData.value.email);
});

const openAddModal = () => {
    editingLocataire.value = null;
    formData.value = { nom: '', email: '', telephone: '', logement: 'Aucun', garantie: 0, statut: 'Actif' };
    showModal.value = true;
};

const openEditModal = (locataire) => {
    editingLocataire.value = locataire;
    formData.value = { ...locataire };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editingLocataire.value = null;
};

const openDeleteModal = (locataire) => {
    deletingLocataire.value = locataire;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    deletingLocataire.value = null;
};

const saveLocataire = () => {
    if (!formData.value.nom || !formData.value.email) {
        errorMessage.value = 'Le nom et l\'email sont requis.';
        showError.value = true;
        return;
    }

    if (!isEmailValid.value) {
        errorMessage.value = 'L\'adresse email saisie est incorrecte.';
        showError.value = true;
        return;
    }

    const locData = {
        nom: formData.value.nom,
        email: formData.value.email,
        telephone: formData.value.telephone,
        logement: formData.value.logement || 'Aucun',
        garantie: Number(formData.value.garantie || 0),
        statut: formData.value.statut || 'Actif',
        agency_id: currentAgencyId.value,
    };

    if (editingLocataire.value) {
        const index = locataires.value.findIndex(l => l.id === editingLocataire.value.id);
        if (index !== -1) {
            locataires.value[index] = { ...editingLocataire.value, ...locData };
        }
        successMessage.value = 'Locataire modifié avec succès';
    } else {
        const newId = Math.max(...locataires.value.map(l => l.id), 0) + 1;
        locataires.value.push({
            id: newId,
            ...locData
        });
        successMessage.value = 'Locataire ajouté avec succès';
    }

    saveLocatairesToStorage();
    closeModal();
    showSuccess.value = true;
};

const confirmDelete = () => {
    const index = locataires.value.findIndex(l => l.id === deletingLocataire.value.id);
    if (index !== -1) {
        locataires.value.splice(index, 1);
        successMessage.value = 'Locataire supprimé avec succès';
        saveLocatairesToStorage();
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

// Global refresh listener for agent actions
import { onMounted, onUnmounted } from 'vue';

const loadLocatairesFromStorage = () => {
    const stored = localStorage.getItem('immobilier_locataires');
    if (stored) {
        locataires.value = JSON.parse(stored);
    }
};

onMounted(() => {
    window.addEventListener('enterprise:refresh', loadLocatairesFromStorage);
});

onUnmounted(() => {
    window.removeEventListener('enterprise:refresh', loadLocatairesFromStorage);
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
