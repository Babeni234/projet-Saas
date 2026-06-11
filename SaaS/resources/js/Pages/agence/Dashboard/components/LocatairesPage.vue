<template>
    <div class="flex flex-col gap-6">
        <!-- Header with Add Button -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 flex items-center gap-2">
                    Gestion des Locataires
                    <span class="text-amber-500 text-sm font-semibold bg-amber-50 px-2.5 py-1 rounded-full border border-amber-200">Module Agence</span>
                </h1>
                <p class="text-slate-600 mt-1">Gérer les résidents gérés par votre agence, coordonner les informations de contact et suivre leurs dossiers.</p>
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
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-amber-500/20">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Total Locataires</p>
                        <p class="text-3xl font-bold text-slate-900 mt-1">{{ totalLocataires }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-100/80 flex items-center justify-center text-amber-600">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                        </svg>
                    </div>
                </div>
            </div>
            <!-- Locataires Actifs -->
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-emerald-500/20">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Actifs / Affectés</p>
                        <p class="text-3xl font-bold text-emerald-600 mt-1">{{ locatairesActifs }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-100/80 flex items-center justify-center text-emerald-600">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <!-- Suspendus -->
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-rose-500/20">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Suspendus</p>
                        <p class="text-3xl font-bold text-rose-600 mt-1">{{ locatairesSuspendus }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-rose-100/80 flex items-center justify-center text-rose-600">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <!-- Inactifs -->
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-slate-500/20">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Inactifs</p>
                        <p class="text-3xl font-bold text-slate-500 mt-1">{{ locatairesInactifs }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
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
                    placeholder="Rechercher par nom ou email..."
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
                    <option value="Affecté">Affectés</option>
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
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Coordonnées</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Logement Occupé</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Statut Dossier</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-slate-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="locataire in filteredLocataires" :key="locataire.id" class="hover:bg-slate-50/80 transition-all duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <img 
                                        v-if="locataire.profil_url" 
                                        :src="locataire.profil_url" 
                                        class="w-10 h-10 rounded-full object-cover border border-slate-200 shadow-sm"
                                        @click="zoomImage(locataire.profil_url)"
                                    />
                                    <div v-else class="w-10 h-10 rounded-full bg-gradient-to-tr from-amber-500 to-orange-600 flex items-center justify-center text-white font-bold text-base shadow-sm">
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
                                    <span class="flex items-center gap-1.5 text-xs text-slate-500" v-if="locataire.telephone">
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
                                <span :class="[
                                    'px-3 py-1.5 rounded-full text-xs font-semibold border inline-flex items-center gap-1.5 shadow-sm',
                                    locataire.statut === 'Actif' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' :
                                    locataire.statut === 'Affecté' ? 'bg-blue-50 text-blue-700 border-blue-200' :
                                    locataire.statut === 'Suspendu' ? 'bg-rose-50 text-rose-700 border-rose-200' :
                                    'bg-slate-50 text-slate-600 border-slate-200'
                                ]">
                                    <span :class="[
                                        'w-1.5 h-1.5 rounded-full',
                                        locataire.statut === 'Actif' ? 'bg-emerald-500' :
                                        locataire.statut === 'Affecté' ? 'bg-blue-500' :
                                        locataire.statut === 'Suspendu' ? 'bg-rose-500' :
                                        'bg-slate-400'
                                    ]"></span>
                                    {{ locataire.statut }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-1">
                                    <button 
                                        @click="openProfileModal(locataire)" 
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                        title="Voir le profil"
                                    >
                                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    <button 
                                        @click="openEditModal(locataire)" 
                                        class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition"
                                        title="Modifier la fiche"
                                    >
                                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button 
                                        @click="toggleStatus(locataire)" 
                                        class="p-2 text-slate-500 hover:bg-slate-50 rounded-lg transition"
                                        :title="locataire.statut === 'Suspendu' ? 'Activer' : 'Suspendre'"
                                    >
                                        <svg v-if="locataire.statut === 'Suspendu'" class="w-4.5 h-4.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <svg v-else class="w-4.5 h-4.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </button>
                                    <button 
                                        v-if="locataire.statut !== 'Inactif'"
                                        @click="deactivateLocataire(locataire)" 
                                        class="p-2 text-gray-500 hover:bg-gray-50 rounded-lg transition"
                                        title="Désactiver (Passer inactif)"
                                    >
                                        <svg class="w-4.5 h-4.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="filteredLocataires.length === 0">
                            <td colspan="5" class="text-center py-12 text-slate-400">
                                Aucun locataire géré par cette agence trouvé.
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
                            <p class="text-xs text-slate-500">Renseignez les informations personnelles et les documents justificatifs.</p>
                        </div>
                    </div>
                    <button @click="closeModal" class="text-slate-400 hover:text-slate-600 transition p-1.5 hover:bg-slate-100 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Form Body -->
                <form @submit.prevent="saveLocataire" class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6" enctype="multipart/form-data">
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
                                    required
                                    class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent transition bg-white"
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
                                    required
                                    class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent transition bg-white"
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
                                    placeholder="Ex: +237 6xx xxx xxx" 
                                    class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent transition bg-white"
                                >
                                <span class="absolute left-3.5 top-1/2 transform -translate-y-1/2 text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <!-- Agence verrouillée -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Agence gérante</label>
                            <input 
                                type="text" 
                                :value="agencyName" 
                                disabled
                                class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 text-slate-500 font-medium"
                            >
                        </div>
                    </div>

                    <!-- Column 2: Photo and Documents -->
                    <div class="space-y-4">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-1.5 mb-2">Photo & Documentations</h3>

                        <!-- Image de Profil -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Photo de Profil</label>
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center border border-slate-200 overflow-hidden shadow-inner">
                                    <img v-if="photoPreview" :src="photoPreview" class="w-full h-full object-cover" />
                                    <svg v-else class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <input 
                                    type="file" 
                                    accept="image/*" 
                                    @change="onPhotoSelected"
                                    class="text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 transition cursor-pointer"
                                />
                            </div>
                        </div>

                        <!-- Documents justificatifs -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Documents justificatifs (Max 10 à la fois)</label>
                            <div class="border-2 border-dashed border-slate-200 rounded-2xl p-4 text-center hover:border-amber-400 transition relative">
                                <input 
                                    type="file" 
                                    multiple 
                                    accept="image/*,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" 
                                    @change="onDocsSelected"
                                    class="absolute inset-0 opacity-0 cursor-pointer"
                                />
                                <svg class="w-8 h-8 text-slate-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V4a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                </svg>
                                <span class="text-xs text-slate-500 block">Glissez ou sélectionnez vos fichiers</span>
                                <span class="text-[10px] text-slate-400">PDF, Word, Images jusqu'à 10 Mo</span>
                            </div>

                            <!-- Selected Docs list -->
                            <div v-if="selectedDocFiles.length > 0" class="mt-3 space-y-1">
                                <div v-for="(file, i) in selectedDocFiles" :key="i" class="flex items-center justify-between text-xs bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">
                                    <span class="truncate max-w-[200px] text-slate-600 font-medium">{{ file.name }}</span>
                                    <button type="button" @click="removeSelectedDoc(i)" class="text-rose-500 hover:text-rose-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Footer -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex gap-3 justify-end">
                    <button 
                        type="button"
                        @click="closeModal" 
                        class="px-5 py-2.5 border border-slate-200 text-slate-700 font-medium rounded-xl hover:bg-slate-100 transition text-sm"
                    >
                        Annuler
                    </button>
                    <button 
                        type="button"
                        @click="saveLocataire" 
                        :disabled="!formData.nom || !formData.email || !isEmailValid || saving"
                        class="px-5 py-2.5 bg-gradient-to-r from-amber-600 to-orange-600 text-white font-medium rounded-xl hover:from-amber-700 hover:to-orange-700 disabled:opacity-50 disabled:cursor-not-allowed shadow transition text-sm"
                    >
                        {{ saving ? 'Enregistrement...' : 'Enregistrer' }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Locataire Profile / Details Modal -->
        <div v-if="showProfileModal && selectedLocataire" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden animate-scale-up border border-slate-100 max-h-[90vh] flex flex-col">
                <!-- Header -->
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-blue-50 to-indigo-50/55">
                    <h2 class="text-lg font-bold text-slate-900">Profil du Locataire</h2>
                    <button @click="closeProfileModal" class="text-slate-400 hover:text-slate-600 p-1.5 hover:bg-slate-100 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Body (Scrollable) -->
                <div class="p-6 space-y-6 overflow-y-auto flex-1">
                    <div class="flex flex-col items-center text-center">
                        <img 
                            v-if="selectedLocataire.profil_url" 
                            :src="selectedLocataire.profil_url" 
                            class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-md cursor-pointer hover:scale-105 transition"
                            @click="zoomImage(selectedLocataire.profil_url)"
                        />
                        <div v-else class="w-24 h-24 rounded-full bg-gradient-to-tr from-amber-500 to-orange-600 flex items-center justify-center text-white font-bold text-3xl shadow-md">
                            {{ selectedLocataire.nom.charAt(0).toUpperCase() }}
                        </div>
                        <h3 class="font-bold text-xl text-slate-900 mt-3">{{ selectedLocataire.nom }}</h3>
                        <span class="text-xs text-slate-400">LOC-{{ String(selectedLocataire.id).padStart(3, '0') }}</span>

                        <span :class="[
                            'px-3 py-1 rounded-full text-xs font-semibold border mt-2 shadow-sm',
                            selectedLocataire.statut === 'Actif' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' :
                            selectedLocataire.statut === 'Affecté' ? 'bg-blue-50 text-blue-700 border-blue-200' :
                            selectedLocataire.statut === 'Suspendu' ? 'bg-rose-50 text-rose-700 border-rose-200' :
                            'bg-slate-50 text-slate-600 border-slate-200'
                        ]">
                            {{ selectedLocataire.statut }}
                        </span>
                    </div>

                    <div class="space-y-4">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-1.5">Détails généraux</h4>
                        
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-slate-400 block text-xs">Email</span>
                                <span class="text-slate-800 font-medium break-all">{{ selectedLocataire.email }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-xs">Téléphone</span>
                                <span class="text-slate-800 font-medium">{{ selectedLocataire.telephone || 'Non renseigné' }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-xs">Agence responsable</span>
                                <span class="text-slate-800 font-medium">{{ selectedLocataire.agency_name || 'Votre Agence' }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-xs">Bien loué</span>
                                <span class="text-slate-800 font-medium">{{ selectedLocataire.logement || 'Aucun' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-1.5">Documents</h4>
                        
                        <div v-if="selectedLocataire.documents && selectedLocataire.documents.length > 0" class="space-y-2">
                            <div 
                                v-for="(doc, idx) in selectedLocataire.documents" 
                                :key="idx" 
                                class="flex items-center justify-between bg-slate-50 border border-slate-100 p-2.5 rounded-xl text-sm"
                            >
                                <a :href="doc.url" target="_blank" class="flex items-center gap-2 text-amber-600 hover:text-amber-700 font-medium truncate max-w-[320px]">
                                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    {{ doc.filename }}
                                </a>
                                <button @click="deleteLocDoc(idx)" class="p-1 hover:bg-rose-50 text-rose-500 rounded-lg transition" title="Supprimer le document">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <p v-else class="text-xs text-slate-400 italic">Aucun document joint pour ce locataire.</p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 text-right">
                    <button @click="closeProfileModal" class="px-5 py-2 border border-slate-200 text-slate-700 font-medium rounded-xl hover:bg-slate-100 transition text-sm">Fermer</button>
                </div>
            </div>
        </div>

        <!-- Zoom Lightbox Modal -->
        <div v-if="zoomedImageUrl" class="fixed inset-0 bg-black/80 backdrop-blur-md z-[60] flex items-center justify-center p-4" @click="zoomedImageUrl = null">
            <img :src="zoomedImageUrl" class="max-w-full max-h-[85vh] rounded-2xl shadow-2xl object-contain animate-scale-up" />
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
                <p class="text-center text-slate-500 text-sm mb-6">Le dossier du locataire a été mis à jour dans votre espace Agence.</p>

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
import { ref, computed, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const csrf = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

// Connected Agency context
const currentAgency = computed(() => page.props.agency);
const agencyName = computed(() => currentAgency.value?.name || 'Votre Agence');

const locataires = ref([]);
const searchQuery = ref('');
const statusFilter = ref('');

const showModal = ref(false);
const showProfileModal = ref(false);
const showSuccess = ref(false);
const showError = ref(false);
const saving = ref(false);

const editingLocataire = ref(null);
const selectedLocataire = ref(null);
const successMessage = ref('');
const errorMessage = ref('');

const photoPreview = ref(null);
const selectedPhotoFile = ref(null);
const selectedDocFiles = ref([]);
const zoomedImageUrl = ref(null);

const formData = ref({
    nom: '',
    email: '',
    telephone: '',
    agency_id: null,
});

// Client-side validation
const isEmailValid = computed(() => {
    if (!formData.value.email) return false;
    const re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return re.test(formData.value.email);
});

// Load Locataires from DB API (scoping is auto-handled on the backend based on agency session/employee info)
const fetchLocataires = async () => {
    try {
        const res = await fetch('/api/locataires', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf(),
            }
        });
        if (res.ok) {
            locataires.value = await res.json();
        } else {
            console.error('Failed to load tenants');
        }
    } catch (err) {
        console.error(err);
    }
};

const filteredLocataires = computed(() => {
    let list = locataires.value;
    
    // Status Filter
    if (statusFilter.value) {
        list = list.filter(l => l.statut.toLowerCase() === statusFilter.value.toLowerCase());
    }
    
    // Search Query
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        list = list.filter(l => 
            l.nom.toLowerCase().includes(query) ||
            l.email.toLowerCase().includes(query) ||
            (l.telephone && l.telephone.includes(query))
        );
    }
    
    return list;
});

const totalLocataires = computed(() => locataires.value.length);
const locatairesActifs = computed(() => locataires.value.filter(l => l.statut === 'Actif' || l.statut === 'Affecté').length);
const locatairesSuspendus = computed(() => locataires.value.filter(l => l.statut === 'Suspendu').length);
const locatairesInactifs = computed(() => locataires.value.filter(l => l.statut === 'Inactif').length);

const openAddModal = () => {
    editingLocataire.value = null;
    formData.value = { nom: '', email: '', telephone: '', agency_id: currentAgency.value?.id || null };
    photoPreview.value = null;
    selectedPhotoFile.value = null;
    selectedDocFiles.value = [];
    showModal.value = true;
};

const openEditModal = (locataire) => {
    editingLocataire.value = locataire;
    formData.value = { 
        nom: locataire.nom, 
        email: locataire.email, 
        telephone: locataire.telephone || '', 
        agency_id: currentAgency.value?.id || null 
    };
    photoPreview.value = locataire.profil_url;
    selectedPhotoFile.value = null;
    selectedDocFiles.value = [];
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editingLocataire.value = null;
};

const openProfileModal = (locataire) => {
    selectedLocataire.value = locataire;
    showProfileModal.value = true;
};

const closeProfileModal = () => {
    showProfileModal.value = false;
    selectedLocataire.value = null;
};

const zoomImage = (url) => {
    zoomedImageUrl.value = url;
};

// Handle files selection
const onPhotoSelected = (e) => {
    const file = e.target.files[0];
    if (file) {
        selectedPhotoFile.value = file;
        photoPreview.value = URL.createObjectURL(file);
    }
};

const onDocsSelected = (e) => {
    const files = Array.from(e.target.files);
    if (selectedDocFiles.value.length + files.length > 10) {
        alert("Vous ne pouvez importer que 10 documents à la fois.");
        return;
    }
    selectedDocFiles.value = [...selectedDocFiles.value, ...files];
};

const removeSelectedDoc = (index) => {
    selectedDocFiles.value.splice(index, 1);
};

// Save Tenant (Create or Update)
const saveLocataire = async () => {
    if (!formData.value.nom || !formData.value.email) {
        errorMessage.value = 'Le nom et l\'email sont requis.';
        showError.value = true;
        return;
    }

    saving.value = true;
    const dataObj = new FormData();
    dataObj.append('nom', formData.value.nom);
    dataObj.append('email', formData.value.email);
    dataObj.append('telephone', formData.value.telephone);
    
    // Auto-assign agency id
    if (currentAgency.value?.id) {
        dataObj.append('agency_id', currentAgency.value.id);
    }

    if (selectedPhotoFile.value) {
        dataObj.append('profil', selectedPhotoFile.value);
    }

    selectedDocFiles.value.forEach((file) => {
        dataObj.append('documentations[]', file);
    });

    const url = editingLocataire.value 
        ? `/api/locataires/${editingLocataire.value.id}` 
        : '/api/locataires';

    try {
        const res = await fetch(url, {
            method: 'POST',
            body: dataObj,
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf(),
            }
        });

        const data = await res.json();
        if (res.ok) {
            successMessage.value = editingLocataire.value 
                ? 'Locataire modifié avec succès' 
                : 'Locataire ajouté avec succès. Un mail contenant son mot de passe lui a été envoyé.';
            closeModal();
            fetchLocataires();
            showSuccess.value = true;
        } else {
            errorMessage.value = data.message || 'Une erreur est survenue.';
            showError.value = true;
        }
    } catch (err) {
        console.error(err);
        errorMessage.value = 'Impossible de communiquer avec le serveur.';
        showError.value = true;
    } finally {
        saving.value = false;
    }
};

// Toggle suspend/active
const toggleStatus = async (loc) => {
    const newStatus = loc.statut.toLowerCase() === 'suspendu' ? 'actif' : 'suspendu';
    try {
        const res = await fetch(`/api/locataires/${loc.id}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf(),
            },
            body: JSON.stringify({ statut: newStatus })
        });
        if (res.ok) {
            fetchLocataires();
        }
    } catch (err) {
        console.error(err);
    }
};

// Deactivate tenant (statut = inactif)
const deactivateLocataire = async (loc) => {
    try {
        const res = await fetch(`/api/locataires/${loc.id}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf(),
            },
            body: JSON.stringify({ statut: 'inactif' })
        });
        if (res.ok) {
            fetchLocataires();
        }
    } catch (err) {
        console.error(err);
    }
};

// Delete a document from server
const deleteLocDoc = async (index) => {
    if (!confirm("Voulez-vous vraiment supprimer ce document ?")) return;
    try {
        const res = await fetch(`/api/locataires/${selectedLocataire.value.id}/documents/${index}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf(),
            }
        });
        if (res.ok) {
            const updated = await res.json();
            // Update modal data
            selectedLocataire.value = updated;
            fetchLocataires();
        }
    } catch (err) {
        console.error(err);
    }
};

const closeSuccess = () => {
    showSuccess.value = false;
};

const closeError = () => {
    showError.value = false;
};

onMounted(() => {
    fetchLocataires();
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
