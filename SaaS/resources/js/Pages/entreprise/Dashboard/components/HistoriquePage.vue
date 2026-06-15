<template>
    <div class="flex flex-col gap-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Historique Immobilier</h1>
                <p class="text-slate-600 mt-1">Journal des activités et opérations de l'entreprise</p>
            </div>
            <div class="flex items-center gap-3">
                <button
                    @click="openConnectionsModal"
                    class="flex items-center gap-2 px-4 py-3 bg-white border border-slate-200 text-slate-700 rounded-xl font-semibold hover:bg-slate-50 shadow-sm transition-all"
                >
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Statut Connexions
                </button>
                <button
                    @click="exportHistory"
                    class="flex items-center gap-2 px-4 py-3 bg-gradient-to-r from-slate-600 to-slate-700 text-white rounded-xl font-medium shadow-lg shadow-slate-500/30 hover:shadow-xl hover:shadow-slate-500/40 transition-all"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Exporter
                </button>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-slate-500/20 animate-fade-in" style="animation-delay: 0ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Total Événements</p>
                        <p class="text-3xl font-bold text-slate-900 mt-1 animate-number">{{ totalEvenements }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-slate-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-emerald-500/20 animate-fade-in" style="animation-delay: 100ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Ce Mois</p>
                        <p class="text-3xl font-bold text-emerald-600 mt-1 animate-number">{{ ceMois }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-blue-500/20 animate-fade-in" style="animation-delay: 200ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Créations</p>
                        <p class="text-3xl font-bold text-blue-600 mt-1 animate-number">{{ creations }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-amber-500/20 animate-fade-in" style="animation-delay: 300ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Modifications</p>
                        <p class="text-3xl font-bold text-amber-600 mt-1 animate-number">{{ modifications }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-rose-500/20 animate-fade-in" style="animation-delay: 400ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Suppressions</p>
                        <p class="text-3xl font-bold text-rose-600 mt-1 animate-number">{{ suppressions }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-rose-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1-1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-4 border border-slate-100">
            <div class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Rechercher..."
                            class="w-full pl-12 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-500 focus:border-transparent"
                        >
                    </div>
                </div>
                <div class="min-w-[180px]">
                    <select v-model="filterType" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-500 focus:border-transparent">
                        <option value="">Tous les types</option>
                        <option value="Création">Création</option>
                        <option value="Modification">Modification</option>
                        <option value="Suppression">Suppression</option>
                        <option value="Renouvellement">Renouvellement</option>
                        <option value="État des lieux">État des lieux</option>
                        <option value="Connexion">Connexion</option>
                        <option value="Déconnexion">Déconnexion</option>
                    </select>
                </div>
                <div class="min-w-[180px]">
                    <select v-model="filterCategorie" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-500 focus:border-transparent">
                        <option value="">Toutes catégories</option>
                        <option value="Contrat">Contrat</option>
                        <option value="Locataire">Locataire</option>
                        <option value="Logement">Logement</option>
                        <option value="Bâtiment">Bâtiment</option>
                        <option value="Engagement">Engagement</option>
                        <option value="Utilisateur">Utilisateur</option>
                    </select>
                </div>
                <div class="min-w-[180px]">
                    <input v-model="filterDate" type="date" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-500 focus:border-transparent">
                </div>
            </div>
        </div>

        <!-- Timeline Section -->
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100">
            <div v-if="loading" class="flex flex-col items-center justify-center py-12 text-slate-500">
                <svg class="w-8 h-8 animate-spin text-slate-600 mb-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <span>Chargement de l'historique...</span>
            </div>

            <div v-else-if="filteredHistorique.length === 0" class="text-center py-12 text-slate-500">
                Aucun événement enregistré pour le moment.
            </div>

            <div v-else class="space-y-6">
                <div v-for="(evenement, index) in filteredHistorique" :key="evenement.id" class="flex gap-4">
                    <div class="flex flex-col items-center">
                        <div :class="{
                            'w-10 h-10 rounded-full flex items-center justify-center shadow-sm': true,
                            'bg-emerald-100 text-emerald-700': evenement.type === 'Création',
                            'bg-amber-100 text-amber-700': evenement.type === 'Modification',
                            'bg-red-100 text-red-700': evenement.type === 'Suppression',
                            'bg-blue-100 text-blue-700': evenement.type === 'Renouvellement' || evenement.type === 'Connexion',
                            'bg-purple-100 text-purple-700': evenement.type === 'État des lieux',
                            'bg-slate-100 text-slate-700': evenement.type === 'Déconnexion'
                        }">
                            <svg v-if="evenement.type === 'Création'" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            <svg v-else-if="evenement.type === 'Modification'" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            <svg v-else-if="evenement.type === 'Suppression'" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1-1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <svg v-else-if="evenement.type === 'Renouvellement' || evenement.type === 'Connexion'" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                            </svg>
                            <svg v-else class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div v-if="index !== filteredHistorique.length - 1" class="w-0.5 flex-1 bg-slate-200 mt-2"></div>
                    </div>
                    <div class="flex-1 pb-6">
                        <div 
                            @click="goToObjectPage(evenement)"
                            :class="{
                                'bg-slate-50 rounded-xl p-4 hover:bg-slate-100 hover:shadow-md transition-all duration-200 group': true,
                                'cursor-pointer': isRealEstateEvent(evenement)
                            }"
                        >
                            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-slate-900 flex items-center gap-2">
                                        {{ evenement.titre }}
                                        <svg v-if="isRealEstateEvent(evenement)" class="w-4 h-4 text-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </h3>
                                    <p class="text-sm text-slate-600 mt-1">{{ evenement.description }}</p>
                                    <div class="flex flex-wrap items-center gap-3 mt-2">
                                        <span :class="{
                                            'px-2 py-1 rounded-full text-xs font-semibold': true,
                                            'bg-emerald-100 text-emerald-700': evenement.type === 'Création',
                                            'bg-amber-100 text-amber-700': evenement.type === 'Modification',
                                            'bg-red-100 text-red-700': evenement.type === 'Suppression',
                                            'bg-blue-100 text-blue-700': evenement.type === 'Renouvellement' || evenement.type === 'Connexion',
                                            'bg-purple-100 text-purple-700': evenement.type === 'État des lieux',
                                            'bg-slate-100 text-slate-600': evenement.type === 'Déconnexion'
                                        }">
                                            {{ evenement.type }}
                                        </span>
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-slate-200 text-slate-700">
                                            {{ evenement.categorie }}
                                        </span>
                                        <!-- Agency or Siège Badge -->
                                        <span :class="{
                                            'px-2 py-1 rounded-full text-xs font-semibold border': true,
                                            'bg-indigo-50 text-indigo-700 border-indigo-150': evenement.agency_id !== null,
                                            'bg-slate-100 text-slate-600 border-slate-200': evenement.agency_id === null
                                        }">
                                            {{ evenement.agency_name }}
                                        </span>
                                        <span class="text-xs text-slate-500">{{ evenement.date }}</span>
                                        <span class="text-xs text-slate-500">{{ evenement.heure }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 self-end sm:self-center">
                                    <div class="text-right">
                                        <span class="text-sm font-semibold text-slate-700 block">{{ evenement.utilisateur }}</span>
                                        <span class="text-xs text-slate-500 block font-medium">{{ evenement.role }}</span>
                                    </div>
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                        {{ evenement.utilisateur ? evenement.utilisateur.charAt(0).toUpperCase() : 'S' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Connections Modal -->
        <div v-if="showConnectionsModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full overflow-hidden animate-scale-up border border-slate-100">
                <!-- Header -->
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-slate-50 to-indigo-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">Statut des connexions</h2>
                            <p class="text-xs text-slate-500">Membres de la compagnie et leur statut en temps réel.</p>
                        </div>
                    </div>
                    <button @click="closeConnectionsModal" class="text-slate-400 hover:text-slate-600 transition p-1.5 hover:bg-slate-100 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="p-6 overflow-y-auto max-h-[60vh] space-y-4">
                    <div v-if="loadingUsers" class="flex flex-col items-center justify-center py-8 text-slate-500">
                        <svg class="w-8 h-8 animate-spin text-indigo-600 mb-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <span>Chargement des statuts...</span>
                    </div>

                    <div v-else class="divide-y divide-slate-100">
                        <div v-for="user in usersStatusList" :key="user.id" class="py-3.5 flex items-center justify-between hover:bg-slate-50/50 rounded-xl px-2 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="relative">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                        {{ user.name.charAt(0).toUpperCase() }}
                                    </div>
                                    <span :class="[
                                        'absolute bottom-0 right-0 w-3 h-3 rounded-full border-2 border-white shadow-sm',
                                        user.is_connected ? 'bg-emerald-500' : 'bg-slate-400'
                                    ]"></span>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-slate-800 text-sm">{{ user.name }}</span>
                                        <span class="px-2 py-0.5 text-[10px] font-bold bg-slate-100 text-slate-600 rounded-full border border-slate-200">
                                            {{ user.role }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-slate-400 mt-0.5">{{ user.email }} • <span class="font-medium text-slate-500">{{ user.agency_name }}</span></div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button
                                    @click="openUserDetails(user)"
                                    class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors"
                                    title="Détails"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </button>
                                <button
                                    v-if="user.is_connected && page.props.auth?.user?.id !== user.id"
                                    @click="forceLogout(user)"
                                    class="px-3 py-1.5 bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 rounded-xl text-xs font-semibold shadow-sm transition-all"
                                >
                                    Déconnecter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end">
                    <button @click="closeConnectionsModal" class="px-5 py-2.5 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 shadow transition text-sm">Fermer</button>
                </div>
            </div>
        </div>

        <!-- User Connection Details Modal -->
        <div v-if="showDetailModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[60] flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 animate-scale-up border border-slate-100">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Informations de connexion</h3>
                <div class="space-y-3 text-sm text-slate-600">
                    <div>
                        <span class="block text-xs font-semibold text-slate-400 uppercase">Utilisateur</span>
                        <span class="font-bold text-slate-800">{{ selectedUserDetail?.name }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-slate-400 uppercase">Adresse E-mail</span>
                        <span class="font-medium text-slate-800">{{ selectedUserDetail?.email }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-slate-400 uppercase">Rôle / Profil</span>
                        <span class="font-semibold text-slate-800">{{ selectedUserDetail?.role }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-slate-400 uppercase">Dernière Connexion</span>
                        <span class="font-semibold text-slate-800">{{ selectedUserDetail?.last_login_at }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-slate-400 uppercase">Statut</span>
                        <span :class="[
                            'px-2 py-0.5 rounded-full text-xs font-semibold inline-block mt-1',
                            selectedUserDetail?.is_connected ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600'
                        ]">
                            {{ selectedUserDetail?.is_connected ? 'En ligne' : 'Hors ligne' }}
                        </span>
                    </div>
                </div>
                <button @click="closeUserDetails" class="mt-6 w-full px-4 py-2.5 bg-slate-100 text-slate-700 font-semibold rounded-xl hover:bg-slate-200 transition text-sm">Fermer</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const router = useRouter();

const historique = ref([]);
const loading = ref(false);

const showConnectionsModal = ref(false);
const loadingUsers = ref(false);
const usersStatusList = ref([]);

const showDetailModal = ref(false);
const selectedUserDetail = ref(null);

const fetchHistorique = async () => {
    loading.value = true;
    try {
        const res = await fetch('/api/evenements', {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            credentials: 'same-origin',
        });
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        historique.value = await res.json();
    } catch (err) {
        console.error('fetchHistorique:', err);
        historique.value = [];
    } finally {
        loading.value = false;
    }
};

const fetchUsersStatus = async () => {
    loadingUsers.value = true;
    try {
        const res = await fetch('/api/users/connection-status', {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            credentials: 'same-origin',
        });
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        usersStatusList.value = await res.json();
    } catch (err) {
        console.error('fetchUsersStatus:', err);
        usersStatusList.value = [];
    } finally {
        loadingUsers.value = false;
    }
};

const forceLogout = async (user) => {
    if (!confirm(`Voulez-vous vraiment déconnecter de force ${user.name} ?`)) {
        return;
    }
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const res = await fetch(`/api/users/${user.id}/force-logout`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken || '',
            },
            credentials: 'same-origin',
        });
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        alert(`${user.name} a été déconnecté.`);
        fetchUsersStatus();
        fetchHistorique();
    } catch (err) {
        console.error('forceLogout:', err);
        alert('Erreur lors de la déconnexion forcée.');
    }
};

const openConnectionsModal = () => {
    showConnectionsModal.value = true;
    fetchUsersStatus();
};

const closeConnectionsModal = () => {
    showConnectionsModal.value = false;
};

const openUserDetails = (user) => {
    selectedUserDetail.value = user;
    showDetailModal.value = true;
};

const closeUserDetails = () => {
    showDetailModal.value = false;
    selectedUserDetail.value = null;
};

const isRealEstateEvent = (e) => {
    return e.categorie !== 'Utilisateur';
};

const goToObjectPage = (e) => {
    if (!isRealEstateEvent(e)) return;
    
    const isAgence = page.props.auth?.user?.employee?.agency_id !== null;
    let routeName = '';

    // Route to page depending on category and type
    if (e.categorie === 'Contrat') {
        if (e.type === 'Renouvellement') {
            routeName = isAgence ? 'agence.immobilier.renouvellements' : 'immobilier.renouvellements';
        } else {
            routeName = isAgence ? 'agence.immobilier.contrats' : 'immobilier.contrats';
        }
    } else if (e.categorie === 'Logement') {
        if (e.type === 'État des lieux') {
            routeName = isAgence ? 'agence.immobilier.etats-des-lieux' : 'immobilier.etats-des-lieux';
        } else {
            routeName = isAgence ? 'agence.immobilier.logements' : 'immobilier.logements';
        }
    } else if (e.categorie === 'Locataire') {
        routeName = isAgence ? 'agence.immobilier.locataires' : 'immobilier.locataires';
    } else if (e.categorie === 'Bâtiment') {
        routeName = isAgence ? 'agence.immobilier.batiments' : 'immobilier.batiments';
    } else if (e.categorie === 'Engagement') {
        routeName = isAgence ? 'agence.immobilier.engagements' : 'immobilier.engagements';
    }

    if (routeName) {
        router.push({ name: routeName });
    }
};

onMounted(() => {
    fetchHistorique();
});

const searchQuery = ref('');
const filterType = ref('');
const filterCategorie = ref('');
const filterDate = ref('');

const filteredHistorique = computed(() => {
    let filtered = historique.value;
    
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(e => 
            e.titre.toLowerCase().includes(query) ||
            e.description.toLowerCase().includes(query) ||
            e.utilisateur.toLowerCase().includes(query) ||
            (e.role && e.role.toLowerCase().includes(query)) ||
            (e.agency_name && e.agency_name.toLowerCase().includes(query))
        );
    }
    
    if (filterType.value) {
        filtered = filtered.filter(e => e.type === filterType.value);
    }
    
    if (filterCategorie.value) {
        filtered = filtered.filter(e => e.categorie === filterCategorie.value);
    }
    
    if (filterDate.value) {
        filtered = filtered.filter(e => e.date === filterDate.value);
    }
    
    return filtered;
});

const totalEvenements = computed(() => historique.value.length);
const ceMois = computed(() => {
    const now = new Date();
    const currentMonth = now.getMonth();
    const currentYear = now.getFullYear();
    return historique.value.filter(e => {
        if (!e.date) return false;
        const eventDate = new Date(e.date);
        return eventDate.getMonth() === currentMonth && eventDate.getFullYear() === currentYear;
    }).length;
});
const creations = computed(() => historique.value.filter(e => e.type === 'Création').length);
const modifications = computed(() => historique.value.filter(e => e.type === 'Modification').length);
const suppressions = computed(() => historique.value.filter(e => e.type === 'Suppression').length);

const exportHistory = () => {
    let csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "ID,Titre,Description,Type,Categorie,Date,Heure,Utilisateur,Role,Agence\n";
    
    historique.value.forEach(e => {
        const row = [
            e.id,
            `"${e.titre.replace(/"/g, '""')}"`,
            `"${e.description.replace(/"/g, '""')}"`,
            e.type,
            e.categorie,
            e.date,
            e.heure,
            e.utilisateur,
            e.role,
            e.agency_name
        ].join(",");
        csvContent += row + "\n";
    });

    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", `historique_immobilier_${new Date().toISOString().slice(0,10)}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
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

.animate-scale-up {
    animation: scaleUp 0.25s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
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
</style>
