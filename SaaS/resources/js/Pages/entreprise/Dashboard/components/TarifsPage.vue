<template>
    <div class="flex flex-col gap-6">
        <!-- Header with Add Button -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Tarifs</h1>
                <p class="text-slate-600 mt-1">Gestion des tarifs hôteliers</p>
            </div>
            <button
                @click="openAddModal"
                class="flex items-center gap-2 px-4 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl font-medium shadow-lg shadow-green-500/30 hover:shadow-xl hover:shadow-green-500/40 transition-all"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Ajouter un Tarif
            </button>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-green-500/20 animate-fade-in" style="animation-delay: 0ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Total Tarifs</p>
                        <p class="text-3xl font-bold text-slate-900 mt-1 animate-number">{{ totalTarifs }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.312-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.312.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-emerald-500/20 animate-fade-in" style="animation-delay: 100ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Prix Moyen</p>
                        <p class="text-3xl font-bold text-emerald-600 mt-1 animate-number">{{ prixMoyen }}€</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-blue-500/20 animate-fade-in" style="animation-delay: 200ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Prix Min</p>
                        <p class="text-3xl font-bold text-blue-600 mt-1 animate-number">{{ prixMin }}€</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-violet-500/20 animate-fade-in" style="animation-delay: 300ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Prix Max</p>
                        <p class="text-3xl font-bold text-violet-600 mt-1 animate-number">{{ prixMax }}€</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-violet-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-violet-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
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
                    placeholder="Rechercher un tarif..."
                    class="w-full pl-12 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent"
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
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Type de Chambre</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Prix/Nuit</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Saison</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Statut</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-slate-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="tarif in filteredTarifs" :key="tarif.id" class="border-b border-slate-100 hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-medium text-slate-900">{{ tarif.nom }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ tarif.typeChambre }}</td>
                            <td class="px-6 py-4 font-semibold text-green-600">{{ tarif.prix }}€</td>
                            <td class="px-6 py-4 text-slate-600">{{ tarif.saison }}</td>
                            <td class="px-6 py-4">
                                <span :class="[
                                    'px-3 py-1 rounded-full text-xs font-semibold',
                                    tarif.statut === 'Actif' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700'
                                ]">
                                    {{ tarif.statut }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="openEditModal(tarif)" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button @click="openDeleteModal(tarif)" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
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
            <div class="bg-gradient-to-br from-white via-white to-green-50/30 rounded-3xl shadow-2xl shadow-green-900/20 max-w-md w-full p-8 border border-green-100/50 relative overflow-hidden">
                <!-- Decorative gradient blob -->
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-gradient-to-br from-green-400/20 to-emerald-500/20 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-gradient-to-br from-green-400/20 to-emerald-500/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-green-500/30">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent">{{ editingTarif ? 'Modifier' : 'Nouveau' }} Tarif</h2>
                        </div>
                        <button @click="closeModal" class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-all hover:rotate-90">
                            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-5">
                        <div class="group">
                            <label class="block text-sm font-semibold text-slate-700 mb-2 group-focus-within:text-green-600 transition-colors">Nom du tarif</label>
                            <input v-model="formData.nom" type="text" placeholder="Ex: Tarif Standard" class="w-full px-5 py-4 bg-white/80 border-2 border-slate-200 rounded-2xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all shadow-sm group-hover:shadow-md">
                        </div>
                        <div class="group">
                            <label class="block text-sm font-semibold text-slate-700 mb-2 group-focus-within:text-green-600 transition-colors">Type de chambre</label>
                            <select v-model="formData.typeChambre" class="w-full px-5 py-4 bg-white/80 border-2 border-slate-200 rounded-2xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all shadow-sm group-hover:shadow-md">
                                <option value="Simple">Simple</option>
                                <option value="Double">Double</option>
                                <option value="Suite">Suite</option>
                                <option value="Deluxe">Deluxe</option>
                            </select>
                        </div>
                        <div class="group">
                            <label class="block text-sm font-semibold text-slate-700 mb-2 group-focus-within:text-green-600 transition-colors">Prix/Nuit (€)</label>
                            <input v-model="formData.prix" type="number" placeholder="100" class="w-full px-5 py-4 bg-white/80 border-2 border-slate-200 rounded-2xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all shadow-sm group-hover:shadow-md">
                        </div>
                        <div class="group">
                            <label class="block text-sm font-semibold text-slate-700 mb-2 group-focus-within:text-green-600 transition-colors">Saison</label>
                            <select v-model="formData.saison" class="w-full px-5 py-4 bg-white/80 border-2 border-slate-200 rounded-2xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all shadow-sm group-hover:shadow-md">
                                <option value="Basse saison">Basse saison</option>
                                <option value="Moyenne saison">Moyenne saison</option>
                                <option value="Haute saison">Haute saison</option>
                                <option value="Toutes saisons">Toutes saisons</option>
                            </select>
                        </div>
                        <div class="group">
                            <label class="block text-sm font-semibold text-slate-700 mb-2 group-focus-within:text-green-600 transition-colors">Statut</label>
                            <select v-model="formData.statut" class="w-full px-5 py-4 bg-white/80 border-2 border-slate-200 rounded-2xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all shadow-sm group-hover:shadow-md">
                                <option value="Actif">Actif</option>
                                <option value="Inactif">Inactif</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-4 mt-8">
                        <button @click="closeModal" class="flex-1 px-6 py-4 bg-slate-100 text-slate-700 rounded-2xl font-semibold hover:bg-slate-200 transition-all hover:shadow-lg">Annuler</button>
                        <button @click="saveTarif" class="flex-1 px-6 py-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-2xl font-semibold shadow-lg shadow-green-500/30 hover:shadow-xl hover:shadow-green-500/40 transition-all hover:scale-[1.02]">Enregistrer</button>
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
                    <h3 class="text-2xl font-bold text-center bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent mb-3">Supprimer ce tarif?</h3>
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

const tarifs = ref([
    { id: 1, nom: 'Tarif Standard', typeChambre: 'Simple', prix: 80, saison: 'Toutes saisons', statut: 'Actif' },
    { id: 2, nom: 'Tarif Double', typeChambre: 'Double', prix: 120, saison: 'Toutes saisons', statut: 'Actif' },
    { id: 3, nom: 'Tarif Suite', typeChambre: 'Suite', prix: 250, saison: 'Haute saison', statut: 'Actif' },
    { id: 4, nom: 'Tarif Deluxe', typeChambre: 'Deluxe', prix: 350, saison: 'Haute saison', statut: 'Actif' },
    { id: 5, nom: 'Promo Été', typeChambre: 'Double', prix: 100, saison: 'Moyenne saison', statut: 'Actif' },
    { id: 6, nom: 'Tarif Hiver', typeChambre: 'Suite', prix: 200, saison: 'Basse saison', statut: 'Inactif' },
]);

const searchQuery = ref('');

const filteredTarifs = computed(() => {
    if (!searchQuery.value) return tarifs.value;
    const query = searchQuery.value.toLowerCase();
    return tarifs.value.filter(t => 
        t.nom.toLowerCase().includes(query) ||
        t.typeChambre.toLowerCase().includes(query) ||
        t.saison.toLowerCase().includes(query)
    );
});

const totalTarifs = computed(() => tarifs.value.length);
const prixMoyen = computed(() => Math.round(tarifs.value.reduce((sum, t) => sum + t.prix, 0) / tarifs.value.length));
const prixMin = computed(() => Math.min(...tarifs.value.map(t => t.prix)));
const prixMax = computed(() => Math.max(...tarifs.value.map(t => t.prix)));

const showModal = ref(false);
const showDeleteModal = ref(false);
const showSuccess = ref(false);
const showError = ref(false);
const editingTarif = ref(null);
const deletingTarif = ref(null);
const successMessage = ref('');
const errorMessage = ref('');

const formData = ref({
    nom: '',
    typeChambre: 'Simple',
    prix: '',
    saison: 'Toutes saisons',
    statut: 'Actif',
});

const openAddModal = () => {
    editingTarif.value = null;
    formData.value = { nom: '', typeChambre: 'Simple', prix: '', saison: 'Toutes saisons', statut: 'Actif' };
    showModal.value = true;
};

const openEditModal = (tarif) => {
    editingTarif.value = tarif;
    formData.value = { ...tarif };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editingTarif.value = null;
};

const openDeleteModal = (tarif) => {
    deletingTarif.value = tarif;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    deletingTarif.value = null;
};

const saveTarif = () => {
    if (!formData.value.nom || !formData.value.prix) {
        errorMessage.value = 'Veuillez remplir tous les champs';
        showError.value = true;
        return;
    }

    if (editingTarif.value) {
        const index = tarifs.value.findIndex(t => t.id === editingTarif.value.id);
        if (index !== -1) {
            tarifs.value[index] = { ...editingTarif.value, ...formData.value };
        }
        successMessage.value = 'Tarif modifié avec succès';
    } else {
        tarifs.value.push({
            id: Math.max(...tarifs.value.map(t => t.id), 0) + 1,
            ...formData.value,
        });
        successMessage.value = 'Tarif créé avec succès';
    }

    closeModal();
    showSuccess.value = true;
};

const confirmDelete = () => {
    const index = tarifs.value.findIndex(t => t.id === deletingTarif.value.id);
    if (index !== -1) {
        tarifs.value.splice(index, 1);
        successMessage.value = 'Tarif supprimé avec succès';
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
