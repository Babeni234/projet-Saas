<template>
    <div class="space-y-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
            <div class="enterprise-card group p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm font-semibold uppercase tracking-wider">Total Locataires</p>
                        <p class="text-4xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent mt-3">{{ stats.total }}</p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM12.92 14.278l.046.087.057.1c.036.066.084.152.133.244.099.174.226.404.374.66.301.514.75 1.126 1.424 1.526.325.19.66.353 1.001.487.667.3 1.37.488 2.07.449.336-.017.668-.074.994-.157a.987.987 0 00.79-.909v-6h-4.38z" />
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
                        <p class="text-slate-500 text-sm font-semibold uppercase tracking-wider">En Attente</p>
                        <p class="text-4xl font-bold bg-gradient-to-r from-amber-600 to-amber-500 bg-clip-text text-transparent mt-3">{{ stats.pending }}</p>
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
                        <p class="text-slate-500 text-sm font-semibold uppercase tracking-wider">Revenu Annuel</p>
                        <p class="text-4xl font-bold bg-gradient-to-r from-violet-600 to-violet-500 bg-clip-text text-transparent mt-3">{{ formatCurrency(stats.annualRevenue) }}</p>
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
                            placeholder="Rechercher par nom, email, téléphone..."
                            class="w-full px-6 py-4 bg-white border-2 border-slate-200 rounded-2xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-300"
                        />
                        <svg class="absolute right-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <select v-model="filters.status" class="px-6 py-4 bg-white border-2 border-slate-200 rounded-2xl focus:outline-none focus:border-blue-500">
                        <option value="">Tous les statuts</option>
                        <option value="active">Actif</option>
                        <option value="pending">En attente</option>
                        <option value="inactive">Inactif</option>
                    </select>
                </div>

                <button @click="openCreateModal" class="px-8 py-4 bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600 text-white rounded-2xl hover:shadow-2xl hover:shadow-blue-500/40 transition-all duration-300 font-semibold transform hover:scale-105">
                    <span class="inline-flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Nouveau Locataire
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
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Email</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Téléphone</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Logement</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Date Entrée</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Statut</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-slate-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <tr v-for="tenant in filteredTenants" :key="tenant.id" class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ tenant.name }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ tenant.email }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ tenant.phone }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ tenant.apartment }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ formatDate(tenant.moveInDate) }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-bold"
                                    :class="tenant.status === 'active' 
                                        ? 'bg-emerald-100 text-emerald-700' 
                                        : tenant.status === 'pending'
                                        ? 'bg-amber-100 text-amber-700'
                                        : 'bg-slate-100 text-slate-700'">
                                    {{ statusLabel(tenant.status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex gap-2 justify-center">
                                    <button @click="editTenant(tenant)" class="text-blue-600 hover:text-blue-700 hover:bg-blue-50 p-2 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button @click="deleteTenant(tenant)" class="text-red-600 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-colors">
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

        <!-- Modal Form -->
        <div v-if="showModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full p-8 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between mb-6 sticky top-0 bg-white">
                    <h2 class="text-2xl font-bold text-slate-900">{{ isEditing ? 'Modifier' : 'Créer' }} Locataire</h2>
                    <button @click="closeModal" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="saveTenant" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Nom Complet</label>
                            <input v-model="formData.name" type="text" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                            <input v-model="formData.email" type="email" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Téléphone</label>
                            <input v-model="formData.phone" type="tel" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Logement</label>
                            <input v-model="formData.apartment" type="text" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Date d'Entrée</label>
                            <input v-model="formData.moveInDate" type="date" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Statut</label>
                            <select v-model="formData.status" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="active">Actif</option>
                                <option value="pending">En attente</option>
                                <option value="inactive">Inactif</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Adresse Personnelle</label>
                        <input v-model="formData.address" type="text" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Notes</label>
                        <textarea v-model="formData.notes" rows="4" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
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

        <!-- Delete Confirmation -->
        <div v-if="showDeleteConfirm" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8">
                <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m9-15a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-center text-slate-900 mb-2">Supprimer le locataire ?</h3>
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

const tenants = ref([
    { id: 1, name: 'Jean Dupont', email: 'jean.dupont@email.com', phone: '06 12 34 56 78', apartment: 'A101', moveInDate: '2023-01-15', status: 'active', address: '123 Rue de Paris', notes: 'Très bon locataire' },
    { id: 2, name: 'Marie Martin', email: 'marie.martin@email.com', phone: '06 87 65 43 21', apartment: 'B202', moveInDate: '2023-06-01', status: 'active', address: '456 Avenue des Champs', notes: '' },
    { id: 3, name: 'Pierre Bernard', email: 'pierre.bernard@email.com', phone: '06 11 22 33 44', apartment: '', moveInDate: '', status: 'pending', address: '789 Boulevard Victor Hugo', notes: 'En attente de confirmation' },
]);

const filters = ref({ search: '', status: '' });
const showModal = ref(false);
const showDeleteConfirm = ref(false);
const showSuccess = ref(false);
const showError = ref(false);
const isEditing = ref(false);
const successMessage = ref('');
const errorMessage = ref('');
const tenantToDelete = ref(null);

const formData = ref({
    name: '',
    email: '',
    phone: '',
    apartment: '',
    moveInDate: '',
    status: 'active',
    address: '',
    notes: ''
});

const stats = computed(() => ({
    total: tenants.value.length,
    active: tenants.value.filter(t => t.status === 'active').length,
    pending: tenants.value.filter(t => t.status === 'pending').length,
    annualRevenue: tenants.value.filter(t => t.status === 'active').length * 1200 * 12
}));

const filteredTenants = computed(() => {
    return tenants.value.filter(t => {
        const matchSearch = t.name.toLowerCase().includes(filters.value.search.toLowerCase()) ||
            t.email.toLowerCase().includes(filters.value.search.toLowerCase()) ||
            t.phone.includes(filters.value.search);
        const matchStatus = !filters.value.status || t.status === filters.value.status;
        return matchSearch && matchStatus;
    });
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(value);
};

const formatDate = (date) => {
    return date ? new Date(date).toLocaleDateString('fr-FR') : '-';
};

const statusLabel = (status) => {
    const labels = { active: 'Actif', pending: 'En attente', inactive: 'Inactif' };
    return labels[status] || status;
};

const openCreateModal = () => {
    isEditing.value = false;
    formData.value = { name: '', email: '', phone: '', apartment: '', moveInDate: '', status: 'active', address: '', notes: '' };
    showModal.value = true;
};

const editTenant = (tenant) => {
    isEditing.value = true;
    formData.value = { ...tenant };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const saveTenant = () => {
    if (!formData.value.name || !formData.value.email) {
        errorMessage.value = 'Veuillez remplir tous les champs obligatoires';
        showError.value = true;
        return;
    }

    if (isEditing.value) {
        const index = tenants.value.findIndex(t => t.id === formData.value.id);
        tenants.value[index] = { ...formData.value };
        successMessage.value = 'Locataire modifié avec succès';
    } else {
        tenants.value.push({ ...formData.value, id: Date.now() });
        successMessage.value = 'Locataire créé avec succès';
    }

    showModal.value = false;
    showSuccess.value = true;
};

const deleteTenant = (tenant) => {
    tenantToDelete.value = tenant;
    showDeleteConfirm.value = true;
};

const confirmDelete = () => {
    tenants.value = tenants.value.filter(t => t.id !== tenantToDelete.value.id);
    showDeleteConfirm.value = false;
    successMessage.value = 'Locataire supprimé avec succès';
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
