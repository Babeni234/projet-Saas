<template>
    <div class="space-y-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
            <div class="enterprise-card group p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm font-semibold uppercase tracking-wider">Total Contrats</p>
                        <p class="text-4xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent mt-3">{{ stats.total }}</p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
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
                        <p class="text-slate-500 text-sm font-semibold uppercase tracking-wider">Expirés</p>
                        <p class="text-4xl font-bold bg-gradient-to-r from-amber-600 to-amber-500 bg-clip-text text-transparent mt-3">{{ stats.expired }}</p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-amber-500 via-amber-600 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg shadow-amber-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-white to-violet-50/30 rounded-3xl shadow-xl shadow-violet-200/50 p-8 border border-violet-100/50 hover:shadow-2xl hover:shadow-violet-200/60 transition-all duration-500 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm font-semibold uppercase tracking-wider">Loyer Mensuel Total</p>
                        <p class="text-4xl font-bold bg-gradient-to-r from-violet-600 to-violet-500 bg-clip-text text-transparent mt-3">{{ formatCurrency(stats.totalRent) }}</p>
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
                            placeholder="Rechercher par numéro, locataire..."
                            class="w-full px-6 py-4 bg-white border-2 border-slate-200 rounded-2xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-300"
                        />
                        <svg class="absolute right-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <select v-model="filters.status" class="px-6 py-4 bg-white border-2 border-slate-200 rounded-2xl focus:outline-none focus:border-blue-500">
                        <option value="">Tous les statuts</option>
                        <option value="active">Actif</option>
                        <option value="expired">Expiré</option>
                    </select>
                </div>

                <button @click="openCreateModal" class="px-8 py-4 bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600 text-white rounded-2xl hover:shadow-2xl hover:shadow-blue-500/40 transition-all duration-300 font-semibold transform hover:scale-105">
                    <span class="inline-flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Nouveau Contrat
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
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">N° Contrat</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Locataire</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Logement</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Loyer</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Date Début</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Date Fin</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Statut</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-slate-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <tr v-for="lease in filteredLeases" :key="lease.id" class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ lease.contractNumber }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ lease.tenantName }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ lease.apartment }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-900">{{ formatCurrency(lease.rent) }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ formatDate(lease.startDate) }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ formatDate(lease.endDate) }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-bold"
                                    :class="lease.status === 'active' 
                                        ? 'bg-emerald-100 text-emerald-700' 
                                        : 'bg-red-100 text-red-700'">
                                    {{ lease.status === 'active' ? 'Actif' : 'Expiré' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex gap-2 justify-center">
                                    <button @click="editLease(lease)" class="text-blue-600 hover:text-blue-700 hover:bg-blue-50 p-2 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button @click="deleteLease(lease)" class="text-red-600 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-colors">
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
            <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full p-8 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between mb-6 sticky top-0 bg-white">
                    <h2 class="text-2xl font-bold text-slate-900">{{ isEditing ? 'Modifier' : 'Créer' }} Contrat de Bail</h2>
                    <button @click="closeModal" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="saveLease" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">N° Contrat</label>
                            <input v-model="formData.contractNumber" type="text" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Locataire</label>
                            <input v-model="formData.tenantName" type="text" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Logement</label>
                            <input v-model="formData.apartment" type="text" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Loyer Mensuel (€)</label>
                            <input v-model="formData.rent" type="number" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Date de Début</label>
                            <input v-model="formData.startDate" type="date" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Date de Fin</label>
                            <input v-model="formData.endDate" type="date" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Statut</label>
                        <select v-model="formData.status" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="active">Actif</option>
                            <option value="expired">Expiré</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Termes</label>
                        <textarea v-model="formData.terms" rows="4" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
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
                <h3 class="text-xl font-bold text-center text-slate-900 mb-2">Supprimer le contrat ?</h3>
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

const leases = ref([
    { id: 1, contractNumber: 'BAIL-001', tenantName: 'Jean Dupont', apartment: 'A101', rent: 1200, startDate: '2023-01-15', endDate: '2024-01-14', status: 'active', terms: 'Contrat standard' },
    { id: 2, contractNumber: 'BAIL-002', tenantName: 'Marie Martin', apartment: 'B202', rent: 1500, startDate: '2022-06-01', endDate: '2024-05-31', status: 'active', terms: 'Contrat avec optionnel' },
]);

const filters = ref({ search: '', status: '' });
const showModal = ref(false);
const showDeleteConfirm = ref(false);
const showSuccess = ref(false);
const showError = ref(false);
const isEditing = ref(false);
const successMessage = ref('');
const errorMessage = ref('');
const leaseToDelete = ref(null);

const formData = ref({
    contractNumber: '',
    tenantName: '',
    apartment: '',
    rent: '',
    startDate: '',
    endDate: '',
    status: 'active',
    terms: ''
});

const stats = computed(() => ({
    total: leases.value.length,
    active: leases.value.filter(l => l.status === 'active').length,
    expired: leases.value.filter(l => l.status === 'expired').length,
    totalRent: leases.value.filter(l => l.status === 'active').reduce((sum, l) => sum + l.rent, 0)
}));

const filteredLeases = computed(() => {
    return leases.value.filter(l => {
        const matchSearch = l.contractNumber.toLowerCase().includes(filters.value.search.toLowerCase()) ||
            l.tenantName.toLowerCase().includes(filters.value.search.toLowerCase());
        const matchStatus = !filters.value.status || l.status === filters.value.status;
        return matchSearch && matchStatus;
    });
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(value);
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('fr-FR');
};

const openCreateModal = () => {
    isEditing.value = false;
    formData.value = { contractNumber: '', tenantName: '', apartment: '', rent: '', startDate: '', endDate: '', status: 'active', terms: '' };
    showModal.value = true;
};

const editLease = (lease) => {
    isEditing.value = true;
    formData.value = { ...lease };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const saveLease = () => {
    if (!formData.value.contractNumber || !formData.value.tenantName) {
        errorMessage.value = 'Veuillez remplir tous les champs obligatoires';
        showError.value = true;
        return;
    }

    if (isEditing.value) {
        const index = leases.value.findIndex(l => l.id === formData.value.id);
        leases.value[index] = { ...formData.value };
        successMessage.value = 'Contrat modifié avec succès';
    } else {
        leases.value.push({ ...formData.value, id: Date.now() });
        successMessage.value = 'Contrat créé avec succès';
    }

    showModal.value = false;
    showSuccess.value = true;
};

const deleteLease = (lease) => {
    leaseToDelete.value = lease;
    showDeleteConfirm.value = true;
};

const confirmDelete = () => {
    leases.value = leases.value.filter(l => l.id !== leaseToDelete.value.id);
    showDeleteConfirm.value = false;
    successMessage.value = 'Contrat supprimé avec succès';
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
