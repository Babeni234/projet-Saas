<template>
    <div class="space-y-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
            <div class="enterprise-card group p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm font-semibold uppercase tracking-wider">Total Bâtiments</p>
                        <p class="text-4xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent mt-3">{{ stats.total }}</p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-white to-emerald-50/30 rounded-3xl shadow-xl shadow-emerald-200/50 p-8 border border-emerald-100/50 hover:shadow-2xl hover:shadow-emerald-200/60 transition-all duration-500 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm font-semibold uppercase tracking-wider">Actifs</p>
                        <p class="text-4xl font-bold bg-gradient-to-r from-emerald-600 to-emerald-500 bg-clip-text text-transparent mt-3">{{ stats.active }}</p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 via-emerald-600 to-teal-600 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-white to-amber-50/30 rounded-3xl shadow-xl shadow-amber-200/50 p-8 border border-amber-100/50 hover:shadow-2xl hover:shadow-amber-200/60 transition-all duration-500 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm font-semibold uppercase tracking-wider">Unités</p>
                        <p class="text-4xl font-bold bg-gradient-to-r from-amber-600 to-amber-500 bg-clip-text text-transparent mt-3">{{ stats.units }}</p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-amber-500 via-amber-600 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg shadow-amber-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-white to-violet-50/30 rounded-3xl shadow-xl shadow-violet-200/50 p-8 border border-violet-100/50 hover:shadow-2xl hover:shadow-violet-200/60 transition-all duration-500 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm font-semibold uppercase tracking-wider">Valeur Totale</p>
                        <p class="text-4xl font-bold bg-gradient-to-r from-violet-600 to-violet-500 bg-clip-text text-transparent mt-3">{{ formatCurrency(stats.totalValue) }}</p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-violet-500 via-violet-600 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg shadow-violet-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Controls -->
        <div class="bg-gradient-to-br from-white to-slate-50 rounded-3xl shadow-xl shadow-slate-200/50 p-8 border border-slate-100/50">
            <div class="flex flex-col lg:flex-row gap-6 items-center justify-between">
                <div class="flex-1 flex gap-4 w-full lg:w-auto">
                    <div class="relative flex-1">
                        <input
                            v-model="filters.search"
                            type="text"
                            placeholder="Rechercher par nom, adresse..."
                            class="w-full px-6 py-4 bg-white border-2 border-slate-200 rounded-2xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-300"
                        />
                        <svg class="absolute right-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <button
                    @click="openCreateModal"
                    class="px-8 py-4 bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600 text-white rounded-2xl hover:shadow-2xl hover:shadow-blue-500/40 transition-all duration-300 font-semibold transform hover:scale-105"
                >
                    <span class="inline-flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Nouveau Bâtiment
                    </span>
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100/50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Nom</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Adresse</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Unités</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Valeur</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Statut</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-slate-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <tr v-for="building in filteredBuildings" :key="building.id" class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ building.name }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ building.address }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-900">{{ building.units }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-900">{{ formatCurrency(building.value) }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-bold"
                                    :class="building.status === 'active' 
                                        ? 'bg-emerald-100 text-emerald-700' 
                                        : 'bg-slate-100 text-slate-700'">
                                    {{ building.status === 'active' ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex gap-2 justify-center">
                                    <button @click="editBuilding(building)" class="text-blue-600 hover:text-blue-700 hover:bg-blue-50 p-2 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button @click="deleteBuilding(building)" class="text-red-600 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-colors">
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

        <!-- Create/Edit Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-slate-900">{{ isEditing ? 'Modifier' : 'Créer' }} Bâtiment</h2>
                    <button @click="closeModal" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="saveBuilding" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Nom du Bâtiment</label>
                            <input v-model="formData.name" type="text" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Nombre d'Unités</label>
                            <input v-model="formData.units" type="number" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Adresse</label>
                        <input v-model="formData.address" type="text" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Valeur (€)</label>
                            <input v-model="formData.value" type="number" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Statut</label>
                            <select v-model="formData.status" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="active">Actif</option>
                                <option value="inactive">Inactif</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                        <textarea v-model="formData.description" rows="4" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    <div class="flex gap-3 justify-end">
                        <button @click="closeModal" type="button" class="px-6 py-3 border border-slate-200 text-slate-700 rounded-xl hover:bg-slate-50 transition-colors">
                            Annuler
                        </button>
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl hover:shadow-lg transition-all">
                            {{ isEditing ? 'Modifier' : 'Créer' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div v-if="showDeleteConfirm" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8">
                <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m9-15a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-center text-slate-900 mb-2">Supprimer le bâtiment ?</h3>
                <p class="text-center text-slate-600 mb-6">Cette action est irréversible.</p>
                <div class="flex gap-3">
                    <button @click="closeDeleteConfirm" class="flex-1 px-6 py-3 border border-slate-200 text-slate-700 rounded-xl hover:bg-slate-50 transition-colors">
                        Annuler
                    </button>
                    <button @click="confirmDelete" class="flex-1 px-6 py-3 bg-red-500 text-white rounded-xl hover:bg-red-600 transition-colors">
                        Supprimer
                    </button>
                </div>
            </div>
        </div>

        <!-- Success Modal -->
        <div v-if="showSuccess" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 text-center">
                <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">{{ successMessage }}</h3>
                <p class="text-slate-600 mb-6">L'opération a été effectuée avec succès.</p>
                <button @click="closeSuccess" class="w-full px-6 py-3 bg-emerald-500 text-white rounded-xl hover:bg-emerald-600 transition-colors">
                    Fermer
                </button>
            </div>
        </div>

        <!-- Error Modal -->
        <div v-if="showError" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 text-center">
                <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Erreur</h3>
                <p class="text-slate-600 mb-6">{{ errorMessage }}</p>
                <button @click="closeError" class="w-full px-6 py-3 bg-red-500 text-white rounded-xl hover:bg-red-600 transition-colors">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const buildings = ref([
    { id: 1, name: 'Tour Horizon', address: '12 Rue de la Paix', units: 45, value: 2500000, status: 'active', description: 'Immeuble résidentiel moderne' },
    { id: 2, name: 'Bâtiment A', address: '45 Avenue des Champs', units: 32, value: 1800000, status: 'active', description: 'Complexe commercial' },
    { id: 3, name: 'Residence Paradise', address: '78 Boulevard Victor Hugo', units: 56, value: 3200000, status: 'active', description: 'Résidence de standing' },
]);

const filters = ref({ search: '' });
const showModal = ref(false);
const showDeleteConfirm = ref(false);
const showSuccess = ref(false);
const showError = ref(false);
const isEditing = ref(false);
const successMessage = ref('');
const errorMessage = ref('');
const buildingToDelete = ref(null);

const formData = ref({
    name: '',
    address: '',
    units: '',
    value: '',
    status: 'active',
    description: ''
});

const stats = computed(() => ({
    total: buildings.value.length,
    active: buildings.value.filter(b => b.status === 'active').length,
    units: buildings.value.reduce((sum, b) => sum + b.units, 0),
    totalValue: buildings.value.reduce((sum, b) => sum + b.value, 0)
}));

const filteredBuildings = computed(() => {
    return buildings.value.filter(b =>
        b.name.toLowerCase().includes(filters.value.search.toLowerCase()) ||
        b.address.toLowerCase().includes(filters.value.search.toLowerCase())
    );
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(value);
};

const openCreateModal = () => {
    isEditing.value = false;
    formData.value = { name: '', address: '', units: '', value: '', status: 'active', description: '' };
    showModal.value = true;
};

const editBuilding = (building) => {
    isEditing.value = true;
    formData.value = { ...building };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const saveBuilding = () => {
    if (!formData.value.name || !formData.value.address) {
        errorMessage.value = 'Veuillez remplir tous les champs obligatoires';
        showError.value = true;
        return;
    }

    if (isEditing.value) {
        const index = buildings.value.findIndex(b => b.id === formData.value.id);
        buildings.value[index] = { ...formData.value };
        successMessage.value = 'Bâtiment modifié avec succès';
    } else {
        buildings.value.push({ ...formData.value, id: Date.now() });
        successMessage.value = 'Bâtiment créé avec succès';
    }

    showModal.value = false;
    showSuccess.value = true;
};

const deleteBuilding = (building) => {
    buildingToDelete.value = building;
    showDeleteConfirm.value = true;
};

const confirmDelete = () => {
    buildings.value = buildings.value.filter(b => b.id !== buildingToDelete.value.id);
    showDeleteConfirm.value = false;
    successMessage.value = 'Bâtiment supprimé avec succès';
    showSuccess.value = true;
};

const closeDeleteConfirm = () => {
    showDeleteConfirm.value = false;
};

const closeSuccess = () => {
    showSuccess.value = false;
};

const closeError = () => {
    showError.value = false;
};
</script>
