<template>
    <div class="flex flex-col gap-6">
        <!-- Header with Add Button -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Engagements</h1>
                <p class="text-slate-600 mt-1">Gestion des engagements et conventions</p>
            </div>
            <button
                @click="openAddModal"
                class="flex items-center gap-2 px-4 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl font-medium shadow-lg shadow-purple-500/30 hover:shadow-xl hover:shadow-purple-500/40 transition-all"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouvel Engagement
            </button>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-purple-500/20 animate-fade-in" style="animation-delay: 0ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Total Engagements</p>
                        <p class="text-3xl font-bold text-slate-900 mt-1 animate-number">{{ totalEngagements }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-emerald-500/20 animate-fade-in" style="animation-delay: 100ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Actifs</p>
                        <p class="text-3xl font-bold text-emerald-600 mt-1 animate-number">{{ actifs }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-amber-500/20 animate-fade-in" style="animation-delay: 200ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">En Cours</p>
                        <p class="text-3xl font-bold text-amber-600 mt-1 animate-number">{{ enCours }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-rose-500/20 animate-fade-in" style="animation-delay: 300ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Expirés</p>
                        <p class="text-3xl font-bold text-rose-600 mt-1 animate-number">{{ expires }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-rose-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l.707.707a1 1 0 001.414-1.414L10 8.586l.707-.707a1 1 0 00-1.414-1.414L8.586 8 8.293 7.293z" clip-rule="evenodd" />
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
                    placeholder="Rechercher un engagement..."
                    class="w-full pl-12 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                >
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 overflow-hidden border border-slate-100">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Référence</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Type</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Partie</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Date Début</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Date Fin</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Montant</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Statut</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="engagement in filteredEngagements" :key="engagement.id" class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ engagement.reference }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ engagement.type }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ engagement.partie }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ engagement.dateDebut }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ engagement.dateFin }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ engagement.montant }}€</td>
                            <td class="px-6 py-4">
                                <span :class="{
                                    'px-3 py-1 rounded-full text-xs font-medium': true,
                                    'bg-emerald-100 text-emerald-700': engagement.statut === 'Actif',
                                    'bg-amber-100 text-amber-700': engagement.statut === 'En cours',
                                    'bg-rose-100 text-rose-700': engagement.statut === 'Expiré'
                                }">
                                    {{ engagement.statut }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <button @click="editEngagement(engagement)" class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button @click="deleteEngagement(engagement)" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add/Edit Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-black/60 backdrop-blur-md z-50 flex items-center justify-center p-4 animate-modal-in">
            <div class="bg-gradient-to-br from-white via-white to-purple-50/30 rounded-3xl shadow-2xl shadow-purple-900/20 max-w-md w-full p-8 border border-purple-100/50 relative overflow-hidden">
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-gradient-to-br from-purple-400/20 to-pink-500/20 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-gradient-to-br from-purple-400/20 to-pink-500/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center shadow-lg shadow-purple-500/30">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent">{{ editingEngagement ? 'Modifier' : 'Nouvel' }} Engagement</h2>
                        </div>
                        <button @click="closeModal" class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-all hover:rotate-90">
                            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-5">
                        <div class="group">
                            <label class="block text-sm font-semibold text-slate-700 mb-2 group-focus-within:text-purple-600 transition-colors">Référence</label>
                            <input v-model="formData.reference" type="text" placeholder="Ex: ENG-2024-001" class="w-full px-5 py-4 bg-white/80 border-2 border-slate-200 rounded-2xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all shadow-sm group-hover:shadow-md">
                        </div>
                        <div class="group">
                            <label class="block text-sm font-semibold text-slate-700 mb-2 group-focus-within:text-purple-600 transition-colors">Type d'engagement</label>
                            <select v-model="formData.type" class="w-full px-5 py-4 bg-white/80 border-2 border-slate-200 rounded-2xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all shadow-sm group-hover:shadow-md">
                                <option value="Convention">Convention</option>
                                <option value="Promesse">Promesse</option>
                                <option value="Avenant">Avenant</option>
                                <option value="Accord">Accord</option>
                            </select>
                        </div>
                        <div class="group">
                            <label class="block text-sm font-semibold text-slate-700 mb-2 group-focus-within:text-purple-600 transition-colors">Partie concernée</label>
                            <input v-model="formData.partie" type="text" placeholder="Ex: Jean Dupont" class="w-full px-5 py-4 bg-white/80 border-2 border-slate-200 rounded-2xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all shadow-sm group-hover:shadow-md">
                        </div>
                        <div class="group">
                            <label class="block text-sm font-semibold text-slate-700 mb-2 group-focus-within:text-purple-600 transition-colors">Date de début</label>
                            <input v-model="formData.dateDebut" type="date" class="w-full px-5 py-4 bg-white/80 border-2 border-slate-200 rounded-2xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all shadow-sm group-hover:shadow-md">
                        </div>
                        <div class="group">
                            <label class="block text-sm font-semibold text-slate-700 mb-2 group-focus-within:text-purple-600 transition-colors">Date de fin</label>
                            <input v-model="formData.dateFin" type="date" class="w-full px-5 py-4 bg-white/80 border-2 border-slate-200 rounded-2xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all shadow-sm group-hover:shadow-md">
                        </div>
                        <div class="group">
                            <label class="block text-sm font-semibold text-slate-700 mb-2 group-focus-within:text-purple-600 transition-colors">Montant (€)</label>
                            <input v-model="formData.montant" type="number" placeholder="Ex: 5000" class="w-full px-5 py-4 bg-white/80 border-2 border-slate-200 rounded-2xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all shadow-sm group-hover:shadow-md">
                        </div>
                        <div class="group">
                            <label class="block text-sm font-semibold text-slate-700 mb-2 group-focus-within:text-purple-600 transition-colors">Statut</label>
                            <select v-model="formData.statut" class="w-full px-5 py-4 bg-white/80 border-2 border-slate-200 rounded-2xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all shadow-sm group-hover:shadow-md">
                                <option value="Actif">Actif</option>
                                <option value="En cours">En cours</option>
                                <option value="Expiré">Expiré</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-4 mt-8">
                        <button @click="closeModal" class="flex-1 px-6 py-4 bg-slate-100 text-slate-700 rounded-2xl font-semibold hover:bg-slate-200 transition-all hover:shadow-lg">Annuler</button>
                        <button @click="saveEngagement" class="flex-1 px-6 py-4 bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-2xl font-semibold shadow-lg shadow-purple-500/30 hover:shadow-xl hover:shadow-purple-500/40 transition-all hover:scale-[1.02]">Enregistrer</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div v-if="showDeleteModal" class="fixed inset-0 bg-black/60 backdrop-blur-md z-50 flex items-center justify-center p-4 animate-modal-in">
            <div class="bg-gradient-to-br from-white via-white to-red-50/30 rounded-3xl shadow-2xl shadow-red-900/20 max-w-md w-full p-8 border border-red-100/50 relative overflow-hidden">
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-gradient-to-br from-red-400/20 to-pink-500/20 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-gradient-to-br from-red-400/20 to-pink-500/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center justify-center w-20 h-20 rounded-3xl bg-gradient-to-br from-red-500 to-pink-600 mx-auto mb-6 shadow-xl shadow-red-500/30">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-center bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent mb-3">Supprimer cet engagement?</h3>
                    <p class="text-center text-slate-600 mb-8 text-lg">Cette action est irréversible</p>

                    <div class="flex gap-4">
                        <button @click="closeDeleteModal" class="flex-1 px-6 py-4 bg-slate-100 text-slate-700 rounded-2xl font-semibold hover:bg-slate-200 transition-all hover:shadow-lg">Annuler</button>
                        <button @click="confirmDelete" class="flex-1 px-6 py-4 bg-gradient-to-r from-red-500 to-pink-600 text-white rounded-2xl font-semibold shadow-lg shadow-red-500/30 hover:shadow-xl hover:shadow-red-500/40 transition-all hover:scale-[1.02]">Supprimer</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Modal -->
        <div v-if="showSuccess" class="fixed inset-0 bg-black/60 backdrop-blur-md z-50 flex items-center justify-center p-4 animate-modal-in">
            <div class="bg-gradient-to-br from-white via-white to-emerald-50/30 rounded-3xl shadow-2xl shadow-emerald-900/20 max-w-md w-full p-8 border border-emerald-100/50 relative overflow-hidden">
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-gradient-to-br from-emerald-400/20 to-green-500/20 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-gradient-to-br from-emerald-400/20 to-green-500/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center justify-center w-20 h-20 rounded-3xl bg-gradient-to-br from-emerald-500 to-green-600 mx-auto mb-6 shadow-xl shadow-emerald-500/30 animate-bounce-slow">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-center bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent mb-3">{{ successMessage }}</h3>
                    <p class="text-center text-slate-600 mb-8 text-lg">Opération effectuée avec succès</p>

                    <button @click="closeSuccess" class="w-full px-6 py-4 bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-2xl font-semibold shadow-lg shadow-emerald-500/30 hover:shadow-xl hover:shadow-emerald-500/40 transition-all hover:scale-[1.02]">Fermer</button>
                </div>
            </div>
        </div>

        <!-- Error Modal -->
        <div v-if="showError" class="fixed inset-0 bg-black/60 backdrop-blur-md z-50 flex items-center justify-center p-4 animate-modal-in">
            <div class="bg-gradient-to-br from-white via-white to-red-50/30 rounded-3xl shadow-2xl shadow-red-900/20 max-w-md w-full p-8 border border-red-100/50 relative overflow-hidden">
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-gradient-to-br from-red-400/20 to-rose-500/20 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-gradient-to-br from-red-400/20 to-rose-500/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center justify-center w-20 h-20 rounded-3xl bg-gradient-to-br from-red-500 to-rose-600 mx-auto mb-6 shadow-xl shadow-red-500/30 animate-shake">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-center bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent mb-3">Erreur</h3>
                    <p class="text-center text-slate-600 mb-8 text-lg">{{ errorMessage }}</p>

                    <button @click="closeError" class="w-full px-6 py-4 bg-gradient-to-r from-red-500 to-rose-600 text-white rounded-2xl font-semibold shadow-lg shadow-red-500/30 hover:shadow-xl hover:shadow-red-500/40 transition-all hover:scale-[1.02]">Fermer</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const engagements = ref([
    { id: 1, reference: 'ENG-2024-001', type: 'Convention', partie: 'Jean Dupont', dateDebut: '2024-01-01', dateFin: '2024-12-31', montant: 12000, statut: 'Actif' },
    { id: 2, reference: 'ENG-2024-002', type: 'Promesse', partie: 'Marie Martin', dateDebut: '2024-02-15', dateFin: '2025-02-14', montant: 8500, statut: 'En cours' },
    { id: 3, reference: 'ENG-2024-003', type: 'Avenant', partie: 'Pierre Bernard', dateDebut: '2024-03-01', dateFin: '2024-08-31', montant: 5000, statut: 'Expiré' },
    { id: 4, reference: 'ENG-2024-004', type: 'Accord', partie: 'Sophie Richard', dateDebut: '2024-04-10', dateFin: '2025-04-09', montant: 15000, statut: 'Actif' },
    { id: 5, reference: 'ENG-2024-005', type: 'Convention', partie: 'Lucas Petit', dateDebut: '2024-05-20', dateFin: '2025-05-19', montant: 9500, statut: 'En cours' },
]);

const searchQuery = ref('');

const filteredEngagements = computed(() => {
    if (!searchQuery.value) return engagements.value;
    const query = searchQuery.value.toLowerCase();
    return engagements.value.filter(e => 
        e.reference.toLowerCase().includes(query) ||
        e.type.toLowerCase().includes(query) ||
        e.partie.toLowerCase().includes(query) ||
        e.statut.toLowerCase().includes(query)
    );
});

const totalEngagements = computed(() => engagements.value.length);
const actifs = computed(() => engagements.value.filter(e => e.statut === 'Actif').length);
const enCours = computed(() => engagements.value.filter(e => e.statut === 'En cours').length);
const expires = computed(() => engagements.value.filter(e => e.statut === 'Expiré').length);

const showModal = ref(false);
const showDeleteModal = ref(false);
const showSuccess = ref(false);
const showError = ref(false);
const editingEngagement = ref(null);
const successMessage = ref('');
const errorMessage = ref('');
const deleteTarget = ref(null);

const formData = ref({
    reference: '',
    type: 'Convention',
    partie: '',
    dateDebut: '',
    dateFin: '',
    montant: '',
    statut: 'Actif'
});

const openAddModal = () => {
    editingEngagement.value = null;
    formData.value = {
        reference: '',
        type: 'Convention',
        partie: '',
        dateDebut: '',
        dateFin: '',
        montant: '',
        statut: 'Actif'
    };
    showModal.value = true;
};

const editEngagement = (engagement) => {
    editingEngagement.value = engagement;
    formData.value = { ...engagement };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editingEngagement.value = null;
};

const saveEngagement = () => {
    if (editingEngagement.value) {
        const index = engagements.value.findIndex(e => e.id === editingEngagement.value.id);
        if (index !== -1) {
            engagements.value[index] = { ...formData.value, id: editingEngagement.value.id };
        }
        successMessage.value = 'Engagement modifié avec succès';
    } else {
        engagements.value.push({
            ...formData.value,
            id: engagements.value.length + 1
        });
        successMessage.value = 'Engagement ajouté avec succès';
    }
    closeModal();
    showSuccess.value = true;
};

const deleteEngagement = (engagement) => {
    deleteTarget.value = engagement;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    deleteTarget.value = null;
};

const confirmDelete = () => {
    if (deleteTarget.value) {
        engagements.value = engagements.value.filter(e => e.id !== deleteTarget.value.id);
        successMessage.value = 'Engagement supprimé avec succès';
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

@keyframes modalIn {
    from {
        opacity: 0;
        transform: scale(0.9) translateY(20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

@keyframes bounceSlow {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}

@keyframes shake {
    0%, 100% {
        transform: translateX(0);
    }
    25% {
        transform: translateX(-5px);
    }
    75% {
        transform: translateX(5px);
    }
}

.animate-fade-in {
    animation: fadeIn 0.5s ease-out forwards;
    opacity: 0;
}

.animate-number {
    animation: countUp 0.5s ease-out;
}

.animate-modal-in {
    animation: modalIn 0.3s ease-out forwards;
}

.animate-bounce-slow {
    animation: bounceSlow 2s ease-in-out infinite;
}

.animate-shake {
    animation: shake 0.5s ease-in-out;
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
</style>
