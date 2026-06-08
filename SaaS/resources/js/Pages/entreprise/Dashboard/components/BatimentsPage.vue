<template>
    <div class="flex flex-col gap-6">
        <!-- Header with Add Button -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Bâtiments</h1>
                <p class="text-slate-600 mt-1">Gestion complète de vos immeubles</p>
            </div>
            <button
                @click="openAddModal"
                class="flex items-center gap-2 px-4 py-3 bg-gradient-to-r from-indigo-600 to-violet-600 text-white rounded-xl font-medium shadow-lg shadow-indigo-500/30 hover:shadow-xl hover:shadow-indigo-500/40 transition-all"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Ajouter un Bâtiment
            </button>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-indigo-500/20 animate-fade-in" style="animation-delay: 0ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Total Bâtiments</p>
                        <p class="text-3xl font-bold text-slate-900 mt-1 animate-number">{{ totalBatiments }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-emerald-500/20 animate-fade-in" style="animation-delay: 100ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Total Appartements</p>
                        <p class="text-3xl font-bold text-slate-900 mt-1 animate-number">{{ totalAppartements }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-emerald-500/20 animate-fade-in" style="animation-delay: 200ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Bâtiments Actifs</p>
                        <p class="text-3xl font-bold text-emerald-600 mt-1 animate-number">{{ batimentsActifs }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-amber-500/20 animate-fade-in" style="animation-delay: 300ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">En Maintenance</p>
                        <p class="text-3xl font-bold text-amber-600 mt-1 animate-number">{{ batimentsMaintenance }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
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
                    placeholder="Rechercher un bâtiment..."
                    class="w-full pl-12 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
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
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Adresse</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Étages</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Appartements</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Statut</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-slate-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="batiment in filteredBatiments" :key="batiment.id" class="border-b border-slate-100 hover:bg-slate-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                        </svg>
                                    </div>
                                    <span class="font-medium text-slate-900">{{ batiment.nom }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ batiment.adresse }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ batiment.etages }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ batiment.appartements }}</td>
                            <td class="px-6 py-4">
                                <span :class="[
                                    'px-3 py-1 rounded-full text-xs font-semibold',
                                    batiment.statut === 'Actif' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'
                                ]">
                                    {{ batiment.statut }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="openEditModal(batiment)" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button @click="openDeleteModal(batiment)" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
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
        <div v-if="showModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-slate-900">{{ editingBatiment ? 'Modifier' : 'Ajouter' }} un Bâtiment</h2>
                    <button @click="closeModal" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="animate-slide-in" style="animation-delay: 0ms">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nom du Bâtiment</label>
                        <input v-model="formData.nom" type="text" placeholder="Ex: Immeuble A" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300 hover:border-slate-300">
                    </div>
                    <div class="animate-slide-in" style="animation-delay: 50ms">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Propriétaire</label>
                        <select v-model="formData.proprietaire" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300 hover:border-slate-300 bg-white">
                            <option value="">Sélectionner un propriétaire</option>
                            <option value="Jean Dupont">Jean Dupont</option>
                            <option value="Marie Martin">Marie Martin</option>
                            <option value="Pierre Bernard">Pierre Bernard</option>
                            <option value="Sophie Petit">Sophie Petit</option>
                            <option value="Michel Leroy">Michel Leroy</option>
                        </select>
                    </div>
                    <div class="animate-slide-in" style="animation-delay: 100ms">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Pays</label>
                        <select v-model="formData.pays" @change="onCountryChange" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300 hover:border-slate-300 bg-white">
                            <option value="">Sélectionner un pays</option>
                            <option v-for="country in countries" :key="country.code" :value="country.code">{{ country.name }}</option>
                        </select>
                    </div>
                    <div class="animate-slide-in" style="animation-delay: 125ms">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Ville</label>
                        <div class="relative">
                            <select 
                                v-model="formData.ville" 
                                @change="onCityChange"
                                :disabled="!formData.pays || loadingCities"
                                class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300 hover:border-slate-300 bg-white disabled:bg-slate-100 disabled:cursor-not-allowed"
                            >
                                <option value="">{{ loadingCities ? 'Chargement...' : formData.pays ? 'Sélectionner une ville' : 'Sélectionnez d\'abord un pays' }}</option>
                                <option v-for="city in cities" :key="city.geonameId" :value="city.name">{{ city.name }} ({{ city.lat.toFixed(4) }}, {{ city.lng.toFixed(4) }})</option>
                            </select>
                            <button 
                                @click="detectLocation" 
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition"
                                title="Utiliser ma position"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="animate-slide-in col-span-2" style="animation-delay: 150ms">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Quartier</label>
                        <select 
                            v-model="formData.quartier" 
                            :disabled="!formData.ville || loadingNeighborhoods"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300 hover:border-slate-300 bg-white disabled:bg-slate-100 disabled:cursor-not-allowed"
                        >
                            <option value="">{{ loadingNeighborhoods ? 'Chargement...' : formData.ville ? 'Sélectionner un quartier' : 'Sélectionnez d\'abord une ville' }}</option>
                            <option v-for="quartier in neighborhoods" :key="quartier.geonameId" :value="quartier.name">{{ quartier.name }} ({{ quartier.lat.toFixed(4) }}, {{ quartier.lng.toFixed(4) }})</option>
                        </select>
                    </div>
                    <div class="animate-slide-in col-span-2" style="animation-delay: 175ms">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Adresse</label>
                        <input v-model="formData.adresse" type="text" placeholder="Ex: 123 Rue de Paris" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300 hover:border-slate-300">
                    </div>
                    <div class="animate-slide-in" style="animation-delay: 200ms">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Étages</label>
                        <input v-model="formData.etages" type="number" placeholder="5" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300 hover:border-slate-300">
                    </div>
                    <div class="animate-slide-in" style="animation-delay: 225ms">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Appartements</label>
                        <input v-model="formData.appartements" type="number" placeholder="25" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300 hover:border-slate-300">
                    </div>
                    <div class="animate-slide-in col-span-2" style="animation-delay: 250ms">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Statut</label>
                        <select v-model="formData.statut" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300 hover:border-slate-300 bg-white">
                            <option value="Actif">Actif</option>
                            <option value="Maintenance">Maintenance</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button @click="closeModal" class="flex-1 px-4 py-2 border border-slate-200 text-slate-700 rounded-lg hover:bg-slate-50 transition">Annuler</button>
                    <button @click="saveBatiment" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">Enregistrer</button>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div v-if="showDeleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100 mx-auto mb-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-center text-slate-900 mb-2">Supprimer ce bâtiment?</h3>
                <p class="text-center text-slate-600 mb-6">Cette action est irréversible</p>

                <div class="flex gap-3">
                    <button @click="closeDeleteModal" class="flex-1 px-4 py-2 border border-slate-200 text-slate-700 rounded-lg hover:bg-slate-50 transition">Annuler</button>
                    <button @click="confirmDelete" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">Supprimer</button>
                </div>
            </div>
        </div>

        <!-- Success Modal -->
        <div v-if="showSuccess" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-emerald-100 mx-auto mb-4">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-center text-slate-900 mb-2">{{ successMessage }}</h3>
                <p class="text-center text-slate-600 mb-6">Opération effectuée avec succès</p>

                <button @click="closeSuccess" class="w-full px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition">Fermer</button>
            </div>
        </div>

        <!-- Error Modal -->
        <div v-if="showError" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100 mx-auto mb-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-center text-slate-900 mb-2">Erreur</h3>
                <p class="text-center text-slate-600 mb-6">{{ errorMessage }}</p>

                <button @click="closeError" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">Fermer</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const batiments = ref([
    { id: 1, nom: 'Immeuble A', adresse: '123 Rue de Paris', etages: 5, appartements: 25, statut: 'Actif' },
    { id: 2, nom: 'Immeuble B', adresse: '456 Avenue Lyon', etages: 7, appartements: 35, statut: 'Actif' },
    { id: 3, nom: 'Immeuble C', adresse: '789 Boulevard Nice', etages: 3, appartements: 15, statut: 'Maintenance' },
    { id: 4, nom: 'Immeuble D', adresse: '321 Rue Marseille', etages: 8, appartements: 40, statut: 'Actif' },
    { id: 5, nom: 'Immeuble E', adresse: '654 Avenue Toulouse', etages: 4, appartements: 20, statut: 'Maintenance' },
]);

const searchQuery = ref('');

const filteredBatiments = computed(() => {
    if (!searchQuery.value) return batiments.value;
    const query = searchQuery.value.toLowerCase();
    return batiments.value.filter(b => 
        b.nom.toLowerCase().includes(query) ||
        b.adresse.toLowerCase().includes(query)
    );
});

const totalBatiments = computed(() => batiments.value.length);
const totalAppartements = computed(() => batiments.value.reduce((sum, b) => sum + b.appartements, 0));
const batimentsActifs = computed(() => batiments.value.filter(b => b.statut === 'Actif').length);
const batimentsMaintenance = computed(() => batiments.value.filter(b => b.statut === 'Maintenance').length);

const showModal = ref(false);
const showDeleteModal = ref(false);
const showSuccess = ref(false);
const showError = ref(false);
const editingBatiment = ref(null);
const deletingBatiment = ref(null);
const successMessage = ref('');
const errorMessage = ref('');

const formData = ref({
    nom: '',
    proprietaire: '',
    pays: '',
    ville: '',
    quartier: '',
    adresse: '',
    etages: '',
    appartements: '',
    statut: 'Actif',
});

// Countries list
const countries = ref([
    { code: 'FR', name: 'France' },
    { code: 'US', name: 'États-Unis' },
    { code: 'GB', name: 'Royaume-Uni' },
    { code: 'DE', name: 'Allemagne' },
    { code: 'ES', name: 'Espagne' },
    { code: 'IT', name: 'Italie' },
    { code: 'CA', name: 'Canada' },
    { code: 'AU', name: 'Australie' },
    { code: 'JP', name: 'Japon' },
    { code: 'BR', name: 'Brésil' },
    { code: 'IN', name: 'Inde' },
    { code: 'CN', name: 'Chine' },
    { code: 'RU', name: 'Russie' },
    { code: 'MX', name: 'Mexique' },
    { code: 'KR', name: 'Corée du Sud' },
    { code: 'NL', name: 'Pays-Bas' },
    { code: 'CH', name: 'Suisse' },
    { code: 'BE', name: 'Belgique' },
    { code: 'AT', name: 'Autriche' },
    { code: 'SE', name: 'Suède' },
    { code: 'NO', name: 'Norvège' },
    { code: 'DK', name: 'Danemark' },
    { code: 'FI', name: 'Finlande' },
    { code: 'PL', name: 'Pologne' },
    { code: 'PT', name: 'Portugal' },
    { code: 'GR', name: 'Grèce' },
    { code: 'TR', name: 'Turquie' },
    { code: 'ZA', name: 'Afrique du Sud' },
    { code: 'EG', name: 'Égypte' },
    { code: 'NG', name: 'Nigeria' },
    { code: 'KE', name: 'Kenya' },
    { code: 'MA', name: 'Maroc' },
    { code: 'DZ', name: 'Algérie' },
    { code: 'TN', name: 'Tunisie' },
    { code: 'CM', name: 'Cameroun' },
    { code: 'CI', name: 'Côte d\'Ivoire' },
    { code: 'SN', name: 'Sénégal' },
    { code: 'MG', name: 'Madagascar' },
    { code: 'CD', name: 'République Démocratique du Congo' },
    { code: 'ET', name: 'Éthiopie' },
    { code: 'GH', name: 'Ghana' },
    { code: 'TZ', name: 'Tanzanie' },
    { code: 'UG', name: 'Ouganda' },
    { code: 'ZW', name: 'Zimbabwe' },
    { code: 'ZM', name: 'Zambie' },
    { code: 'AO', name: 'Angola' },
    { code: 'MU', name: 'Maurice' },
    { code: 'AR', name: 'Argentine' },
    { code: 'CL', name: 'Chili' },
    { code: 'CO', name: 'Colombie' },
    { code: 'PE', name: 'Pérou' },
    { code: 'VE', name: 'Venezuela' },
    { code: 'EC', name: 'Équateur' },
    { code: 'BO', name: 'Bolivie' },
    { code: 'PY', name: 'Paraguay' },
    { code: 'UY', name: 'Uruguay' },
    { code: 'TH', name: 'Thaïlande' },
    { code: 'VN', name: 'Vietnam' },
    { code: 'MY', name: 'Malaisie' },
    { code: 'SG', name: 'Singapour' },
    { code: 'ID', name: 'Indonésie' },
    { code: 'PH', name: 'Philippines' },
    { code: 'HK', name: 'Hong Kong' },
    { code: 'TW', name: 'Taïwan' },
    { code: 'NZ', name: 'Nouvelle-Zélande' },
    { code: 'IL', name: 'Israël' },
    { code: 'AE', name: 'Émirats Arabes Unis' },
    { code: 'SA', name: 'Arabie Saoudite' },
    { code: 'QA', name: 'Qatar' },
    { code: 'KW', name: 'Koweït' },
    { code: 'BH', name: 'Bahreïn' },
    { code: 'OM', name: 'Oman' },
    { code: 'JO', name: 'Jordanie' },
    { code: 'LB', name: 'Liban' },
    { code: 'PK', name: 'Pakistan' },
    { code: 'BD', name: 'Bangladesh' },
    { code: 'LK', name: 'Sri Lanka' },
    { code: 'NP', name: 'Népal' },
    { code: 'MM', name: 'Myanmar' },
    { code: 'KH', name: 'Cambodge' },
    { code: 'LA', name: 'Laos' },
    { code: 'UA', name: 'Ukraine' },
    { code: 'CZ', name: 'République Tchèque' },
    { code: 'HU', name: 'Hongrie' },
    { code: 'RO', name: 'Roumanie' },
    { code: 'BG', name: 'Bulgarie' },
    { code: 'HR', name: 'Croatie' },
    { code: 'SI', name: 'Slovénie' },
    { code: 'SK', name: 'Slovaquie' },
    { code: 'EE', name: 'Estonie' },
    { code: 'LV', name: 'Lettonie' },
    { code: 'LT', name: 'Lituanie' },
    { code: 'IE', name: 'Irlande' },
    { code: 'IS', name: 'Islande' },
    { code: 'LU', name: 'Luxembourg' },
    { code: 'MC', name: 'Monaco' },
    { code: 'AD', name: 'Andorre' },
    { code: 'SM', name: 'Saint-Marin' },
    { code: 'VA', name: 'Vatican' },
    { code: 'MT', name: 'Malte' },
    { code: 'CY', name: 'Chypre' },
    { code: 'AL', name: 'Albanie' },
    { code: 'MK', name: 'Macédoine du Nord' },
    { code: 'RS', name: 'Serbie' },
    { code: 'BA', name: 'Bosnie-Herzégovine' },
    { code: 'ME', name: 'Monténégro' },
    { code: 'XK', name: 'Kosovo' },
]);

const cities = ref([]);
const neighborhoods = ref([]);
const loadingCities = ref(false);
const loadingNeighborhoods = ref(false);

// Load cities based on selected country
const onCountryChange = async () => {
    if (!formData.value.pays) {
        cities.value = [];
        neighborhoods.value = [];
        formData.value.ville = '';
        formData.value.quartier = '';
        return;
    }

    loadingCities.value = true;
    formData.value.ville = '';
    formData.value.quartier = '';
    neighborhoods.value = [];

    try {
        // Using GeoNames API (free, no auth required for basic usage)
        const response = await fetch(
            `http://api.geonames.org/searchJSON?country=${formData.value.pays}&featureClass=P&maxRows=100&username=demo`
        );
        const data = await response.json();
        
        if (data.geonames) {
            cities.value = data.geonames.map(city => ({
                geonameId: city.geonameId,
                name: city.name,
                lat: city.lat,
                lng: city.lng,
            })).sort((a, b) => a.name.localeCompare(b.name));
        }
    } catch (error) {
        console.error('Error loading cities:', error);
        errorMessage.value = 'Erreur lors du chargement des villes';
        showError.value = true;
    } finally {
        loadingCities.value = false;
    }
};

// Load neighborhoods based on selected city
const onCityChange = async () => {
    if (!formData.value.ville) {
        neighborhoods.value = [];
        formData.value.quartier = '';
        return;
    }

    loadingNeighborhoods.value = true;
    formData.value.quartier = '';

    try {
        // Get the selected city's coordinates
        const selectedCity = cities.value.find(c => c.name === formData.value.ville);
        if (!selectedCity) return;

        // Using GeoNames API to find nearby places (neighborhoods/districts)
        const response = await fetch(
            `http://api.geonames.org/searchJSON?lat=${selectedCity.lat}&lng=${selectedCity.lng}&radius=10&featureCode=PPL&featureCode=PPLA&featureCode=PPLA2&featureCode=PPLA3&featureCode=PPLA4&maxRows=50&username=demo`
        );
        const data = await response.json();
        
        if (data.geonames) {
            // Filter out the city itself and get nearby districts/neighborhoods
            neighborhoods.value = data.geonames
                .filter(place => place.name !== selectedCity.name)
                .map(place => ({
                    geonameId: place.geonameId,
                    name: place.name,
                    lat: parseFloat(place.lat),
                    lng: parseFloat(place.lng),
                }))
                .sort((a, b) => a.name.localeCompare(b.name));
        }
    } catch (error) {
        console.error('Error loading neighborhoods:', error);
        errorMessage.value = 'Erreur lors du chargement des quartiers';
        showError.value = true;
    } finally {
        loadingNeighborhoods.value = false;
    }
};

// Detect user's location using browser geolocation
const detectLocation = async () => {
    if (!navigator.geolocation) {
        errorMessage.value = 'La géolocalisation n\'est pas supportée par votre navigateur';
        showError.value = true;
        return;
    }

    try {
        const position = await new Promise((resolve, reject) => {
            navigator.geolocation.getCurrentPosition(resolve, reject, {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0,
            });
        });

        const { latitude, longitude } = position.coords;

        // Reverse geocode to get country and city
        const response = await fetch(
            `http://api.geonames.org/findNearbyPlaceNameJSON?lat=${latitude}&lng=${longitude}&username=demo`
        );
        const data = await response.json();

        if (data.geonames && data.geonames.length > 0) {
            const place = data.geonames[0];
            
            // Set country code
            if (place.countryCode) {
                formData.value.pays = place.countryCode;
                await onCountryChange();
                
                // Set city if available
                if (place.name) {
                    formData.value.ville = place.name;
                    await onCityChange();
                }
            }
        }
    } catch (error) {
        console.error('Error detecting location:', error);
        errorMessage.value = 'Impossible de détecter votre position. Veuillez sélectionner manuellement.';
        showError.value = true;
    }
};

const openAddModal = () => {
    editingBatiment.value = null;
    formData.value = { 
        nom: '', 
        proprietaire: '',
        pays: '',
        ville: '',
        quartier: '',
        adresse: '', 
        etages: '', 
        appartements: '',
        statut: 'Actif'
    };
    cities.value = [];
    neighborhoods.value = [];
    showModal.value = true;
};

const openEditModal = (batiment) => {
    editingBatiment.value = batiment;
    formData.value = { ...batiment };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editingBatiment.value = null;
};

const openDeleteModal = (batiment) => {
    deletingBatiment.value = batiment;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    deletingBatiment.value = null;
};

const saveBatiment = () => {
    if (!formData.value.nom || !formData.value.adresse) {
        errorMessage.value = 'Veuillez remplir tous les champs';
        showError.value = true;
        return;
    }

    if (editingBatiment.value) {
        const index = batiments.value.findIndex(b => b.id === editingBatiment.value.id);
        if (index !== -1) {
            batiments.value[index] = { ...editingBatiment.value, ...formData.value };
        }
        successMessage.value = 'Bâtiment modifié avec succès';
    } else {
        batiments.value.push({
            id: Math.max(...batiments.value.map(b => b.id), 0) + 1,
            ...formData.value,
            statut: 'Actif'
        });
        successMessage.value = 'Bâtiment ajouté avec succès';
    }

    closeModal();
    showSuccess.value = true;
};

const confirmDelete = () => {
    const index = batiments.value.findIndex(b => b.id === deletingBatiment.value.id);
    if (index !== -1) {
        batiments.value.splice(index, 1);
        successMessage.value = 'Bâtiment supprimé avec succès';
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

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.animate-fade-in {
    animation: fadeIn 0.5s ease-out forwards;
    opacity: 0;
}

.animate-slide-in {
    animation: slideIn 0.4s ease-out forwards;
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
</style>
