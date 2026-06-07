<template>
    <div class="flex flex-col gap-6">
        <!-- Header with Add Button -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Équipements</h1>
                <p class="text-slate-600 mt-1">Gestion des équipements hôteliers</p>
            </div>
            <button
                @click="openAddModal"
                class="flex items-center gap-2 px-4 py-3 bg-gradient-to-r from-lime-600 to-green-600 text-white rounded-xl font-medium shadow-lg shadow-lime-500/30 hover:shadow-xl hover:shadow-lime-500/40 transition-all"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Ajouter un Équipement
            </button>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-lime-500/20 animate-fade-in" style="animation-delay: 0ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Total Équipements</p>
                        <p class="text-3xl font-bold text-slate-900 mt-1 animate-number">{{ totalEquipements }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-lime-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-lime-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-emerald-500/20 animate-fade-in" style="animation-delay: 100ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Fonctionnels</p>
                        <p class="text-3xl font-bold text-emerald-600 mt-1 animate-number">{{ equipementsFonctionnels }}</p>
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
                        <p class="text-sm font-medium text-slate-600">En Maintenance</p>
                        <p class="text-3xl font-bold text-amber-600 mt-1 animate-number">{{ equipementsMaintenance }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-red-500/20 animate-fade-in" style="animation-delay: 300ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Hors Service</p>
                        <p class="text-3xl font-bold text-red-600 mt-1 animate-number">{{ equipementsHorsService }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
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
                    placeholder="Rechercher un équipement..."
                    class="w-full pl-12 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-lime-500 focus:border-transparent"
                >
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 overflow-hidden border border-slate-100">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Nom</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Catégorie</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Emplacement</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Date Achat</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Statut</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-slate-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="equipement in filteredEquipements" :key="equipement.id" class="border-b border-slate-100 hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-medium text-slate-900">{{ equipement.nom }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ equipement.categorie }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ equipement.emplacement }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ equipement.dateAchat }}</td>
                            <td class="px-6 py-4">
                                <span :class="[
                                    'px-3 py-1 rounded-full text-xs font-semibold',
                                    equipement.statut === 'Fonctionnel' ? 'bg-emerald-100 text-emerald-700' : equipement.statut === 'En maintenance' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700'
                                ]">
                                    {{ equipement.statut }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="openEditModal(equipement)" class="p-2 text-lime-600 hover:bg-lime-50 rounded-lg transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button @click="openDeleteModal(equipement)" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
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
            <div class="bg-gradient-to-br from-white via-white to-lime-50/30 rounded-3xl shadow-2xl shadow-lime-900/20 max-w-md w-full p-8 border border-lime-100/50 relative overflow-hidden">
                <!-- Decorative gradient blob -->
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-gradient-to-br from-lime-400/20 to-green-500/20 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-gradient-to-br from-lime-400/20 to-green-500/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-lime-500 to-green-600 flex items-center justify-center shadow-lg shadow-lime-500/30">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent">{{ editingEquipement ? 'Modifier' : 'Nouvel' }} Équipement</h2>
                        </div>
                        <button @click="closeModal" class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-all hover:rotate-90">
                            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-5">
                        <div class="group">
                            <label class="block text-sm font-semibold text-slate-700 mb-2 group-focus-within:text-lime-600 transition-colors">Nom de l'équipement</label>
                            <input v-model="formData.nom" type="text" placeholder="Ex: Climatisation" class="w-full px-5 py-4 bg-white/80 border-2 border-slate-200 rounded-2xl focus:ring-2 focus:ring-lime-500/20 focus:border-lime-500 transition-all shadow-sm group-hover:shadow-md">
                        </div>
                        <div class="group">
                            <label class="block text-sm font-semibold text-slate-700 mb-2 group-focus-within:text-lime-600 transition-colors">Catégorie</label>
                            <select v-model="formData.categorie" class="w-full px-5 py-4 bg-white/80 border-2 border-slate-200 rounded-2xl focus:ring-2 focus:ring-lime-500/20 focus:border-lime-500 transition-all shadow-sm group-hover:shadow-md">
                                <option value="Climatisation">Climatisation</option>
                                <option value="Plomberie">Plomberie</option>
                                <option value="Électricité">Électricité</option>
                                <option value="Mobilier">Mobilier</option>
                                <option value="Électronique">Électronique</option>
                            </select>
                        </div>
                        <div class="group">
                            <label class="block text-sm font-semibold text-slate-700 mb-2 group-focus-within:text-lime-600 transition-colors">Emplacement</label>
                            <input v-model="formData.emplacement" type="text" placeholder="Ex: Chambre 101" class="w-full px-5 py-4 bg-white/80 border-2 border-slate-200 rounded-2xl focus:ring-2 focus:ring-lime-500/20 focus:border-lime-500 transition-all shadow-sm group-hover:shadow-md">
                        </div>
                        <div class="group">
                            <label class="block text-sm font-semibold text-slate-700 mb-2 group-focus-within:text-lime-600 transition-colors">Date d'achat</label>
                            <input v-model="formData.dateAchat" type="date" class="w-full px-5 py-4 bg-white/80 border-2 border-slate-200 rounded-2xl focus:ring-2 focus:ring-lime-500/20 focus:border-lime-500 transition-all shadow-sm group-hover:shadow-md">
                        </div>
                        <div class="group">
                            <label class="block text-sm font-semibold text-slate-700 mb-2 group-focus-within:text-lime-600 transition-colors">Statut</label>
                            <select v-model="formData.statut" class="w-full px-5 py-4 bg-white/80 border-2 border-slate-200 rounded-2xl focus:ring-2 focus:ring-lime-500/20 focus:border-lime-500 transition-all shadow-sm group-hover:shadow-md">
                                <option value="Fonctionnel">Fonctionnel</option>
                                <option value="En maintenance">En maintenance</option>
                                <option value="Hors service">Hors service</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-4 mt-8">
                        <button @click="closeModal" class="flex-1 px-6 py-4 bg-slate-100 text-slate-700 rounded-2xl font-semibold hover:bg-slate-200 transition-all hover:shadow-lg">Annuler</button>
                        <button @click="saveEquipement" class="flex-1 px-6 py-4 bg-gradient-to-r from-lime-500 to-green-600 text-white rounded-2xl font-semibold shadow-lg shadow-lime-500/30 hover:shadow-xl hover:shadow-lime-500/40 transition-all hover:scale-[1.02]">Enregistrer</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div v-if="showDeleteModal" class="fixed inset-0 bg-black/60 backdrop-blur-md z-50 flex items-center justify-center p-4 animate-modal-in">
            <div class="bg-gradient-to-br from-white via-white to-red-50/30 rounded-3xl shadow-2xl shadow-red-900/20 max-w-md w-full p-8 border border-red-100/50 relative overflow-hidden">
                <!-- Decorative gradient blob -->
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-gradient-to-br from-red-400/20 to-pink-500/20 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-gradient-to-br from-red-400/20 to-pink-500/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center justify-center w-20 h-20 rounded-3xl bg-gradient-to-br from-red-500 to-pink-600 mx-auto mb-6 shadow-xl shadow-red-500/30">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-center bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent mb-3">Supprimer cet équipement?</h3>
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
                <!-- Decorative gradient blob -->
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
                <!-- Decorative gradient blob -->
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

const equipements = ref([
    { id: 1, nom: 'Climatisation centrale', categorie: 'Climatisation', emplacement: 'Hall', dateAchat: '2023-01-15', statut: 'Fonctionnel' },
    { id: 2, nom: 'Chauffe-eau', categorie: 'Plomberie', emplacement: 'Sous-sol', dateAchat: '2022-06-20', statut: 'Fonctionnel' },
    { id: 3, nom: 'Télévision 4K', categorie: 'Électronique', emplacement: 'Chambre 101', dateAchat: '2023-03-10', statut: 'En maintenance' },
    { id: 4, nom: 'Lit King Size', categorie: 'Mobilier', emplacement: 'Chambre 205', dateAchat: '2023-02-05', statut: 'Fonctionnel' },
    { id: 5, nom: 'Réfrigérateur', categorie: 'Électronique', emplacement: 'Chambre 302', dateAchat: '2022-11-30', statut: 'Hors service' },
    { id: 6, nom: 'Tableau électrique', categorie: 'Électricité', emplacement: 'Couloir 2', dateAchat: '2021-08-15', statut: 'Fonctionnel' },
]);

const searchQuery = ref('');

const filteredEquipements = computed(() => {
    if (!searchQuery.value) return equipements.value;
    const query = searchQuery.value.toLowerCase();
    return equipements.value.filter(e => 
        e.nom.toLowerCase().includes(query) ||
        e.categorie.toLowerCase().includes(query) ||
        e.emplacement.toLowerCase().includes(query)
    );
});

const totalEquipements = computed(() => equipements.value.length);
const equipementsFonctionnels = computed(() => equipements.value.filter(e => e.statut === 'Fonctionnel').length);
const equipementsMaintenance = computed(() => equipements.value.filter(e => e.statut === 'En maintenance').length);
const equipementsHorsService = computed(() => equipements.value.filter(e => e.statut === 'Hors service').length);

const showModal = ref(false);
const showDeleteModal = ref(false);
const showSuccess = ref(false);
const showError = ref(false);
const editingEquipement = ref(null);
const deletingEquipement = ref(null);
const successMessage = ref('');
const errorMessage = ref('');

const formData = ref({
    nom: '',
    categorie: 'Climatisation',
    emplacement: '',
    dateAchat: '',
    statut: 'Fonctionnel',
});

const openAddModal = () => {
    editingEquipement.value = null;
    formData.value = { nom: '', categorie: 'Climatisation', emplacement: '', dateAchat: '', statut: 'Fonctionnel' };
    showModal.value = true;
};

const openEditModal = (equipement) => {
    editingEquipement.value = equipement;
    formData.value = { ...equipement };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editingEquipement.value = null;
};

const openDeleteModal = (equipement) => {
    deletingEquipement.value = equipement;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    deletingEquipement.value = null;
};

const saveEquipement = () => {
    if (!formData.value.nom || !formData.value.emplacement) {
        errorMessage.value = 'Veuillez remplir tous les champs';
        showError.value = true;
        return;
    }

    if (editingEquipement.value) {
        const index = equipements.value.findIndex(e => e.id === editingEquipement.value.id);
        if (index !== -1) {
            equipements.value[index] = { ...editingEquipement.value, ...formData.value };
        }
        successMessage.value = 'Équipement modifié avec succès';
    } else {
        equipements.value.push({
            id: Math.max(...equipements.value.map(e => e.id), 0) + 1,
            ...formData.value,
        });
        successMessage.value = 'Équipement créé avec succès';
    }

    closeModal();
    showSuccess.value = true;
};

const confirmDelete = () => {
    const index = equipements.value.findIndex(e => e.id === deletingEquipement.value.id);
    if (index !== -1) {
        equipements.value.splice(index, 1);
        successMessage.value = 'Équipement supprimé avec succès';
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
