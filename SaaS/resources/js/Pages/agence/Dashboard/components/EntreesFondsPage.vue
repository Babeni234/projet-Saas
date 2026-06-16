<template>
    <div class="p-6">
        <!-- Header -->
        <div class="mb-8 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Autres Entrées de Fonds</h1>
                <p class="text-sm text-slate-500">Gérez vos recettes diverses (non liées aux loyers de base).</p>
            </div>
            <button
                @click="openModal()"
                class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-emerald-600 via-emerald-700 to-teal-700 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-emerald-200/50 transition-all hover:scale-[1.02] hover:shadow-xl hover:shadow-emerald-300/60 active:scale-[0.98] focus:ring-2 focus:ring-emerald-500/50"
            >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouvelle Entrée
            </button>
        </div>

        <!-- Professional Contextual Banner -->
        <div class="mb-8 overflow-hidden rounded-2xl border border-emerald-100 bg-gradient-to-r from-emerald-500 via-emerald-600 to-teal-600 p-6 text-white shadow-sm relative">
            <div class="relative z-10 flex flex-col justify-between gap-4 md:flex-row md:items-center">
                <div class="space-y-1">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-2.5 py-0.5 text-xs font-semibold text-emerald-100 backdrop-blur-md">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-200"></span>
                        Trésorerie & Recettes (Agence)
                    </span>
                    <h2 class="text-xl font-bold">Optimisation des Recettes Diverses</h2>
                    <p class="text-sm text-emerald-100/90 max-w-2xl">
                        Suivez toutes vos rentrées financières secondaires (subventions, apports, et autres produits exceptionnels) pour garder une vision globale et saine de la santé financière de votre agence.
                    </p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="hidden lg:flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10 text-white backdrop-blur-md">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            <!-- Decorative background blur elements -->
            <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-emerald-400/20 blur-3xl pointer-events-none"></div>
            <div class="absolute -left-10 -bottom-10 h-40 w-40 rounded-full bg-teal-400/20 blur-3xl pointer-events-none"></div>
        </div>

        <!-- KPIs -->
        <div class="mb-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Total Encaissé</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ formatCurrency(kpis.total) }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Ce Mois</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ formatCurrency(kpis.month) }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">En Attente</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ formatCurrency(kpis.pending) }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="p-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-slate-800">Historique des Entrées</h2>
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Rechercher..."
                    class="rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500 w-64"
                />
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Titre</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Catégorie</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Montant</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Référence</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        <tr v-for="entree in filteredEntrees" :key="entree.id" class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">{{ formatDate(entree.date_entree) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ entree.titre }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ entree.categorie || '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-slate-900">{{ formatCurrency(entree.montant) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ entree.reference || '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold"
                                    :class="{
                                        'bg-emerald-100 text-emerald-800': entree.statut === 'Encaissé',
                                        'bg-amber-100 text-amber-800': entree.statut === 'En attente',
                                        'bg-rose-100 text-rose-800': entree.statut === 'Annulé'
                                    }"
                                >
                                    {{ entree.statut }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button 
                                    @click="openDetailsModal(entree)" 
                                    class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1 text-xs font-semibold text-slate-707 transition-all hover:bg-slate-100 hover:text-slate-900 active:scale-[0.95] mr-2 shadow-sm"
                                >
                                    <svg class="h-3.5 w-3.5 text-slate-505" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Détails
                                </button>
                                <button 
                                    v-if="entree.statut === 'En attente'"
                                    @click="openModal(entree)" 
                                    class="inline-flex items-center gap-1 rounded-lg border border-indigo-150 bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-707 transition-all hover:bg-indigo-100 hover:text-indigo-900 active:scale-[0.95] mr-2 shadow-sm"
                                >
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Modifier
                                </button>
                                <button 
                                    v-if="entree.statut === 'En attente'"
                                    @click="confirmDeleteEntree(entree.id)" 
                                    class="inline-flex items-center gap-1 rounded-lg border border-rose-150 bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-707 transition-all hover:bg-rose-100 hover:text-rose-900 active:scale-[0.95] shadow-sm"
                                >
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Supprimer
                                </button>
                                <span v-else class="text-xs text-slate-400 italic">Dossier Traité</span>
                            </td>
                        </tr>
                        <tr v-if="filteredEntrees.length === 0">
                            <td colspan="7" class="px-6 py-12 text-center text-sm text-slate-500">
                                Aucune entrée de fonds trouvée.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Create / Edit Modal -->
        <div v-if="isModalOpen" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-screen items-center justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="closeModal"></div>
                <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>
                <div class="inline-block transform overflow-hidden rounded-2xl bg-white text-left align-bottom shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle animate-scale-up border border-slate-100">
                    
                    <div class="bg-gradient-to-r from-emerald-500 to-teal-650 px-6 py-4 flex items-center justify-between text-white">
                        <div>
                            <h3 class="text-lg font-bold leading-6">{{ isEditing ? 'Modifier l\'entrée de fonds' : 'Nouvelle entrée de fonds' }}</h3>
                            <p class="text-xs text-emerald-100 mt-1">Saisissez les détails de l'encaissement ci-dessous</p>
                        </div>
                        <button @click="closeModal" class="text-white/80 hover:text-white transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="bg-white px-6 pt-6 pb-6 space-y-6">
                        <div class="grid grid-cols-2 gap-5">
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Titre de l'entrée <span class="text-rose-500">*</span></label>
                                <div class="relative rounded-2xl shadow-sm">
                                    <input 
                                        v-model="form.titre" 
                                        type="text" 
                                        placeholder="Ex: Subvention annuelle ou apport"
                                        class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-2xl text-slate-700 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-medium" 
                                        required 
                                    />
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Montant (XAF) <span class="text-rose-500">*</span></label>
                                <input 
                                    v-model="form.montant" 
                                    type="number" 
                                    step="0.01" 
                                    placeholder="0.00"
                                    class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-2xl text-slate-700 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-medium" 
                                    required 
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Date <span class="text-rose-500">*</span></label>
                                <input 
                                    v-model="form.date_entree" 
                                    type="date" 
                                    class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-2xl text-slate-700 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-medium" 
                                    required 
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Catégorie</label>
                                <input 
                                    v-model="form.categorie" 
                                    type="text" 
                                    placeholder="Ex: Apport, Subvention"
                                    class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-2xl text-slate-700 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-medium" 
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Statut</label>
                                <select 
                                    v-model="form.statut" 
                                    class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-2xl text-slate-700 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-medium cursor-pointer"
                                >
                                    <option value="En attente">En attente</option>
                                    <option value="Encaissé">Encaissé</option>
                                    <option value="Annulé">Annulé</option>
                                </select>
                            </div>

                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Référence (Générée automatiquement)</label>
                                <input 
                                    v-model="form.reference" 
                                    type="text" 
                                    placeholder="Générée automatiquement"
                                    disabled
                                    class="w-full px-4 py-3 bg-slate-100 border-2 border-slate-200 rounded-2xl text-slate-500 font-medium cursor-not-allowed select-none focus:outline-none" 
                                />
                            </div>

                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Description (Optionnel)</label>
                                <textarea 
                                    v-model="form.description" 
                                    rows="3" 
                                    placeholder="Ajoutez des détails supplémentaires..."
                                    class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-2xl text-slate-700 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-medium"
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 px-6 py-4 flex flex-col sm:flex-row-reverse gap-3 border-t border-slate-100">
                        <button
                            @click="saveEntree"
                            :disabled="isLoading"
                            class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-650 text-white rounded-xl text-sm font-bold shadow-md shadow-emerald-200/50 hover:shadow-lg hover:shadow-emerald-300 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-2 disabled:opacity-50"
                        >
                            <span v-if="isLoading" class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></span>
                            <span>{{ isEditing ? 'Mettre à jour' : 'Enregistrer' }}</span>
                        </button>
                        <button
                            @click="closeModal"
                            class="px-6 py-3 bg-white border border-slate-300 text-slate-707 rounded-xl text-sm font-bold hover:bg-slate-50 hover:scale-[1.02] active:scale-[0.98] transition-all"
                        >
                            Annuler
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Custom Deletion Confirmation Modal -->
        <div v-if="isDeleteModalOpen" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-screen items-center justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="closeDeleteModal"></div>
                <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>
                <div class="inline-block transform overflow-hidden rounded-2xl bg-white text-left align-bottom shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md sm:align-middle animate-scale-up border border-slate-100">
                    
                    <div class="p-6">
                        <div class="flex items-center gap-4">
                            <div class="h-12 w-12 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 flex-shrink-0">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">Supprimer l'entrée de fonds</h3>
                                <p class="text-sm text-slate-500 mt-1">Êtes-vous sûr de vouloir supprimer cette entrée ? Cette action est irréversible et supprimera le mouvement de trésorerie associé.</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 px-6 py-4 flex flex-col sm:flex-row-reverse gap-3 border-t border-slate-100">
                        <button
                            @click="submitDeleteEntree"
                            class="px-5 py-2.5 bg-rose-650 text-white rounded-xl text-sm font-bold hover:bg-rose-700 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-2"
                        >
                            <span>Supprimer définitivement</span>
                        </button>
                        <button
                            @click="closeDeleteModal"
                            class="px-5 py-2.5 bg-white border border-slate-300 text-slate-707 rounded-xl text-sm font-bold hover:bg-slate-50 hover:scale-[1.02] active:scale-[0.98] transition-all"
                        >
                            Annuler
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Custom Details Modal -->
        <div v-if="isDetailsModalOpen" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-screen items-center justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="closeDetailsModal"></div>
                <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>
                <div class="inline-block transform overflow-hidden rounded-2xl bg-white text-left align-bottom shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle animate-scale-up border border-slate-100">
                    
                    <div class="bg-gradient-to-r from-emerald-500 to-teal-650 px-6 py-4 flex items-center justify-between text-white">
                        <div>
                            <h3 class="text-lg font-bold leading-6">Détails de l'entrée de fonds</h3>
                            <p class="text-xs text-emerald-100 mt-1">Examen complet de la fiche d'entrée</p>
                        </div>
                        <button @click="closeDetailsModal" class="text-white/80 hover:text-white transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="bg-white px-6 pt-6 pb-6 space-y-6">
                        <div class="grid grid-cols-2 gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-150">
                            <div>
                                <span class="text-xs font-bold text-slate-400 uppercase">Référence</span>
                                <p class="text-sm font-semibold text-slate-800">{{ selectedDetails?.reference || '-' }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-400 uppercase">Date</span>
                                <p class="text-sm font-semibold text-slate-800">{{ formatDate(selectedDetails?.date_entree) }}</p>
                            </div>
                            <div class="col-span-2">
                                <span class="text-xs font-bold text-slate-400 uppercase">Titre</span>
                                <p class="text-sm font-semibold text-slate-800">{{ selectedDetails?.titre }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-400 uppercase">Montant</span>
                                <p class="text-sm font-bold text-slate-900 text-emerald-600">{{ formatCurrency(selectedDetails?.montant) }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-400 uppercase">Catégorie</span>
                                <p class="text-sm font-semibold text-slate-800">{{ selectedDetails?.categorie || '-' }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-400 uppercase">Statut</span>
                                <div class="mt-0.5">
                                    <span
                                        class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold"
                                        :class="{
                                            'bg-emerald-100 text-emerald-800': selectedDetails?.statut === 'Encaissé',
                                            'bg-amber-100 text-amber-800': selectedDetails?.statut === 'En attente',
                                            'bg-rose-100 text-rose-800': selectedDetails?.statut === 'Annulé'
                                        }"
                                    >
                                        {{ selectedDetails?.statut }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-span-2">
                                <span class="text-xs font-bold text-slate-400 uppercase">Description</span>
                                <p class="text-sm text-slate-600 italic mt-0.5">{{ selectedDetails?.description || 'Aucune description' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 px-6 py-4 flex justify-end border-t border-slate-100">
                        <button
                            @click="closeDetailsModal"
                            class="px-5 py-2.5 bg-white border border-slate-300 text-slate-707 rounded-xl text-sm font-bold hover:bg-slate-50 hover:scale-[1.02] active:scale-[0.98] transition-all"
                        >
                            Fermer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

const entrees = ref([]);
const isModalOpen = ref(false);
const isEditing = ref(false);
const isLoading = ref(false);
const searchQuery = ref('');

// New delete modal properties
const isDeleteModalOpen = ref(false);
const entreeIdToDelete = ref(null);

// New details modal properties
const isDetailsModalOpen = ref(false);
const selectedDetails = ref(null);

const form = ref({
    id: null,
    titre: '',
    description: '',
    montant: 0,
    date_entree: new Date().toISOString().split('T')[0],
    categorie: '',
    reference: '',
    statut: 'En attente',
});

const fetchEntrees = async () => {
    try {
        const response = await axios.get('/api/entrees-fonds');
        entrees.value = response.data;
    } catch (error) {
        console.error('Erreur lors de la récupération des entrées:', error);
    }
};

onMounted(fetchEntrees);

const kpis = computed(() => {
    const today = new Date();
    const currentMonth = today.getMonth();
    const currentYear = today.getFullYear();

    let total = 0;
    let month = 0;
    let pending = 0;

    entrees.value.forEach(e => {
        const amt = parseFloat(e.montant);
        if (e.statut === 'Encaissé') {
            total += amt;
            const dateE = new Date(e.date_entree);
            if (dateE.getMonth() === currentMonth && dateE.getFullYear() === currentYear) {
                month += amt;
            }
        } else if (e.statut === 'En attente') {
            pending += amt;
        }
    });

    return { total, month, pending };
});

const filteredEntrees = computed(() => {
    if (!searchQuery.value) return entrees.value;
    const query = searchQuery.value.toLowerCase();
    return entrees.value.filter(e => 
        (e.titre && e.titre.toLowerCase().includes(query)) ||
        (e.reference && e.reference.toLowerCase().includes(query)) ||
        (e.categorie && e.categorie.toLowerCase().includes(query))
    );
});

const openModal = (entree = null) => {
    if (entree) {
        isEditing.value = true;
        form.value = { 
            id: entree.id,
            titre: entree.titre,
            description: entree.description || '',
            montant: entree.montant,
            date_entree: entree.date_entree ? entree.date_entree.split('T')[0] : new Date().toISOString().split('T')[0],
            categorie: entree.categorie || '',
            reference: entree.reference || '',
            statut: entree.statut || 'En attente'
        };
    } else {
        isEditing.value = false;
        form.value = {
            id: null,
            titre: '',
            description: '',
            montant: 0,
            date_entree: new Date().toISOString().split('T')[0],
            categorie: '',
            reference: '',
            statut: 'En attente',
        };
    }
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
};

const saveEntree = async () => {
    if (!form.value.titre || !form.value.montant || !form.value.date_entree) {
        alert("Veuillez remplir les champs obligatoires (*)");
        return;
    }

    isLoading.value = true;
    try {
        if (isEditing.value) {
            await axios.put(`/api/entrees-fonds/${form.value.id}`, form.value);
        } else {
            await axios.post('/api/entrees-fonds', form.value);
        }
        await fetchEntrees();
        closeModal();
    } catch (error) {
        console.error('Erreur lors de la sauvegarde:', error);
        alert('Une erreur est survenue.');
    } finally {
        isLoading.value = false;
    }
};

// Custom deletion modal flow
const confirmDeleteEntree = (id) => {
    entreeIdToDelete.value = id;
    isDeleteModalOpen.value = true;
};

const closeDeleteModal = () => {
    isDeleteModalOpen.value = false;
    entreeIdToDelete.value = null;
};

const submitDeleteEntree = async () => {
    if (!entreeIdToDelete.value) return;
    isLoading.value = true;
    try {
        await axios.delete(`/api/entrees-fonds/${entreeIdToDelete.value}`);
        await fetchEntrees();
        closeDeleteModal();
    } catch (error) {
        console.error('Erreur lors de la suppression:', error);
        alert('Une erreur est survenue.');
    } finally {
        isLoading.value = false;
    }
};

const formatCurrency = (value) => {
    if (!value && value !== 0) return '-';
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'XAF' }).format(value);
};

const formatDate = (dateStr) => {
    if (!dateStr) return '-';
    return new Intl.DateTimeFormat('fr-FR', { year: 'numeric', month: 'long', day: 'numeric' }).format(new Date(dateStr));
};

const openDetailsModal = (entree) => {
    selectedDetails.value = entree;
    isDetailsModalOpen.value = true;
};

const closeDetailsModal = () => {
    isDetailsModalOpen.value = false;
    selectedDetails.value = null;
};
</script>

<style scoped>
.animate-scale-up {
    animation: scaleUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
@keyframes scaleUp {
    from {
        opacity: 0;
        transform: scale(0.95) translateY(10px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}
</style>
