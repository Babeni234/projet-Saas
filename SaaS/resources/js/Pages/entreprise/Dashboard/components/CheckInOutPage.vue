<template>
    <div class="flex flex-col gap-6">
        <!-- Header with Add Button -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Check-in / Check-out</h1>
                <p class="text-slate-600 mt-1">Gestion des arrivées et départs</p>
            </div>
            <button
                @click="openAddModal"
                class="flex items-center gap-2 px-4 py-3 bg-gradient-to-r from-cyan-600 to-blue-600 text-white rounded-xl font-medium shadow-lg shadow-cyan-500/30 hover:shadow-xl hover:shadow-cyan-500/40 transition-all"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouveau Check-in
            </button>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-cyan-500/20 animate-fade-in" style="animation-delay: 0ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Check-ins Aujourd'hui</p>
                        <p class="text-3xl font-bold text-slate-900 mt-1 animate-number">{{ checkinsAujourdhui }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-cyan-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-cyan-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-emerald-500/20 animate-fade-in" style="animation-delay: 100ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Check-outs Aujourd'hui</p>
                        <p class="text-3xl font-bold text-emerald-600 mt-1 animate-number">{{ checkoutsAujourdhui }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-blue-500/20 animate-fade-in" style="animation-delay: 200ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Occupés Actuellement</p>
                        <p class="text-3xl font-bold text-blue-600 mt-1 animate-number">{{ occupesActuellement }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-violet-500/20 animate-fade-in" style="animation-delay: 300ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Chambres Disponibles</p>
                        <p class="text-3xl font-bold text-slate-900 mt-1 animate-number">{{ chambresDisponibles }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-violet-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-violet-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
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
                    placeholder="Rechercher un check-in/out..."
                    class="w-full pl-12 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                >
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 overflow-hidden border border-slate-100">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Client</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Chambre</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Type</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Date</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Heure</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Statut</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-slate-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="check in filteredChecks" :key="check.id" class="border-b border-slate-100 hover:bg-slate-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center text-white font-semibold">
                                        {{ check.client.charAt(0) }}
                                    </div>
                                    <span class="font-medium text-slate-900">{{ check.client }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ check.chambre }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ check.type }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ check.date }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ check.heure }}</td>
                            <td class="px-6 py-4">
                                <span :class="[
                                    'px-3 py-1 rounded-full text-xs font-semibold',
                                    check.statut === 'Complété' ? 'bg-emerald-100 text-emerald-700' : check.statut === 'En cours' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700'
                                ]">
                                    {{ check.statut }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="openEditModal(check)" class="p-2 text-cyan-600 hover:bg-cyan-50 rounded-lg transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button @click="openDeleteModal(check)" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
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
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-slate-900">{{ editingCheck ? 'Modifier' : 'Nouveau' }} Check-in</h2>
                    <button @click="closeModal" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Client</label>
                        <input v-model="formData.client" type="text" placeholder="Ex: Jean Dupont" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Chambre</label>
                        <input v-model="formData.chambre" type="text" placeholder="Ex: Chambre 101" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Type</label>
                        <select v-model="formData.type" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition">
                            <option value="Check-in">Check-in</option>
                            <option value="Check-out">Check-out</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Date</label>
                            <input v-model="formData.date" type="date" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Heure</label>
                            <input v-model="formData.heure" type="time" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Statut</label>
                        <select v-model="formData.statut" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition">
                            <option value="En attente">En attente</option>
                            <option value="En cours">En cours</option>
                            <option value="Complété">Complété</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button @click="closeModal" class="flex-1 px-4 py-2 border border-slate-200 text-slate-700 rounded-lg hover:bg-slate-50 transition">Annuler</button>
                    <button @click="saveCheck" class="flex-1 px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition">Enregistrer</button>
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
                <h3 class="text-lg font-bold text-center text-slate-900 mb-2">Supprimer ce check-in/out?</h3>
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

const checks = ref([
    { id: 1, client: 'Jean Dupont', chambre: 'Chambre 101', type: 'Check-in', date: '2024-01-15', heure: '14:00', statut: 'Complété' },
    { id: 2, client: 'Marie Martin', chambre: 'Chambre 102', type: 'Check-in', date: '2024-01-15', heure: '15:30', statut: 'Complété' },
    { id: 3, client: 'Pierre Bernard', chambre: 'Chambre 201', type: 'Check-out', date: '2024-01-15', heure: '11:00', statut: 'Complété' },
    { id: 4, client: 'Sophie Richard', chambre: 'Chambre 103', type: 'Check-in', date: '2024-01-16', heure: '12:00', statut: 'En attente' },
    { id: 5, client: 'Lucas Petit', chambre: 'Chambre 202', type: 'Check-out', date: '2024-01-16', heure: '10:00', statut: 'En attente' },
    { id: 6, client: 'Emma Leroy', chambre: 'Chambre 104', type: 'Check-in', date: '2024-01-16', heure: '16:00', statut: 'En cours' },
]);

const searchQuery = ref('');

const filteredChecks = computed(() => {
    if (!searchQuery.value) return checks.value;
    const query = searchQuery.value.toLowerCase();
    return checks.value.filter(c => 
        c.client.toLowerCase().includes(query) ||
        c.chambre.toLowerCase().includes(query) ||
        c.type.toLowerCase().includes(query)
    );
});

const checkinsAujourdhui = computed(() => checks.value.filter(c => c.type === 'Check-in').length);
const checkoutsAujourdhui = computed(() => checks.value.filter(c => c.type === 'Check-out').length);
const occupesActuellement = computed(() => checks.value.filter(c => c.statut === 'En cours' || c.statut === 'Complété').filter(c => c.type === 'Check-in').length);
const chambresDisponibles = computed(() => 50 - occupesActuellement.value);

const showModal = ref(false);
const showDeleteModal = ref(false);
const showSuccess = ref(false);
const showError = ref(false);
const editingCheck = ref(null);
const deletingCheck = ref(null);
const successMessage = ref('');
const errorMessage = ref('');

const formData = ref({
    client: '',
    chambre: '',
    type: 'Check-in',
    date: '',
    heure: '',
    statut: 'En attente',
});

const openAddModal = () => {
    editingCheck.value = null;
    formData.value = { client: '', chambre: '', type: 'Check-in', date: '', heure: '', statut: 'En attente' };
    showModal.value = true;
};

const openEditModal = (check) => {
    editingCheck.value = check;
    formData.value = { ...check };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editingCheck.value = null;
};

const openDeleteModal = (check) => {
    deletingCheck.value = check;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    deletingCheck.value = null;
};

const saveCheck = () => {
    if (!formData.value.client || !formData.value.chambre) {
        errorMessage.value = 'Veuillez remplir tous les champs';
        showError.value = true;
        return;
    }

    if (editingCheck.value) {
        const index = checks.value.findIndex(c => c.id === editingCheck.value.id);
        if (index !== -1) {
            checks.value[index] = { ...editingCheck.value, ...formData.value };
        }
        successMessage.value = 'Check-in/out modifié avec succès';
    } else {
        checks.value.push({
            id: Math.max(...checks.value.map(c => c.id), 0) + 1,
            ...formData.value,
        });
        successMessage.value = 'Check-in/out créé avec succès';
    }

    closeModal();
    showSuccess.value = true;
};

const confirmDelete = () => {
    const index = checks.value.findIndex(c => c.id === deletingCheck.value.id);
    if (index !== -1) {
        checks.value.splice(index, 1);
        successMessage.value = 'Check-in/out supprimé avec succès';
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
</style>
