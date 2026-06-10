<template>
    <div class="flex flex-col gap-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 flex items-center gap-2">
                    Collaborateurs de l'Agence
                    <span class="text-emerald-500 text-sm font-semibold bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">Espace Agence</span>
                </h1>
                <p class="text-slate-650 mt-1">Gérer les employés de votre agence, définir leurs postes et affecter des rôles.</p>
            </div>
            <button
                @click="openAddModal"
                class="flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-xl font-medium shadow-lg hover:shadow-xl hover:scale-[1.02] active:scale-95 transition-all duration-200"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                Ajouter un Collaborateur
            </button>
        </div>

        <!-- Employees List -->
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 overflow-hidden border border-slate-100">
            <div v-if="loading" class="flex flex-col items-center justify-center py-12">
                <svg class="w-10 h-10 animate-spin text-emerald-600" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                </svg>
                <p class="text-slate-500 mt-3 text-sm">Chargement des collaborateurs...</p>
            </div>

            <div v-else class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50 text-slate-400">
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Matricule</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Nom complet</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Email / Téléphone</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Poste</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Rôle système</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="emp in employees" :key="emp.id" class="hover:bg-slate-50/80 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-indigo-600">
                                {{ emp.employee?.matricule || 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold text-sm">
                                        {{ emp.name.charAt(0).toUpperCase() }}
                                    </div>
                                    <div class="font-semibold text-slate-900">{{ emp.name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                <div>{{ emp.email }}</div>
                                <div class="text-xs text-slate-400">{{ emp.employee?.phone || '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                {{ emp.employee?.position || 'Agent d\'agence' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700">
                                    {{ emp.role?.name || 'Aucun rôle' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="[
                                    'px-2.5 py-1 rounded-full text-xs font-semibold inline-flex items-center gap-1.5 border',
                                    emp.status === 'active' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-50 text-slate-600 border-slate-200'
                                ]">
                                    <span :class="['w-1.5 h-1.5 rounded-full', emp.status === 'active' ? 'bg-emerald-500' : 'bg-slate-400']"></span>
                                    {{ emp.status === 'active' ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                        </tr>
                        <tr v-if="employees.length === 0">
                            <td colspan="6" class="text-center py-12 text-slate-400">
                                Aucun collaborateur trouvé pour cette agence.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Employee Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full overflow-hidden border border-slate-100">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-emerald-50 to-teal-50/40">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">Ajouter un Collaborateur</h2>
                            <p class="text-xs text-slate-500">Renseignez les identifiants d'accès de l'employé.</p>
                        </div>
                    </div>
                    <button @click="closeModal" class="text-slate-400 hover:text-slate-600 transition p-1.5 hover:bg-slate-100 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="submitForm" class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nom Complet <span class="text-red-500">*</span></label>
                        <input
                            v-model="form.name"
                            type="text"
                            required
                            placeholder="Ex: Jean Dupont"
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                        />
                        <span v-if="errors.name" class="text-red-500 text-xs mt-1 block">{{ errors.name[0] }}</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Adresse Email <span class="text-red-500">*</span></label>
                        <input
                            v-model="form.email"
                            type="email"
                            required
                            placeholder="Ex: jean.dupont@agence.com"
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                        />
                        <span v-if="errors.email" class="text-red-500 text-xs mt-1 block">{{ errors.email[0] }}</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Mot de Passe <span class="text-red-500">*</span></label>
                        <input
                            v-model="form.password"
                            type="password"
                            required
                            placeholder="Minimum 8 caractères"
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                        />
                        <span v-if="errors.password" class="text-red-500 text-xs mt-1 block">{{ errors.password[0] }}</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Téléphone</label>
                        <input
                            v-model="form.phone"
                            type="text"
                            placeholder="Ex: +237 670 000 000"
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                        />
                        <span v-if="errors.phone" class="text-red-500 text-xs mt-1 block">{{ errors.phone[0] }}</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Poste / Fonction</label>
                        <input
                            v-model="form.position"
                            type="text"
                            placeholder="Ex: Comptable, Chargé clientèle..."
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                        />
                        <span v-if="errors.position" class="text-red-500 text-xs mt-1 block">{{ errors.position[0] }}</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Rôle Système <span class="text-red-500">*</span></label>
                        <select
                            v-model="form.role_id"
                            required
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition bg-white"
                        >
                            <option value="">Sélectionner un rôle</option>
                            <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
                        </select>
                        <span v-if="errors.role_id" class="text-red-500 text-xs mt-1 block">{{ errors.role_id[0] }}</span>
                        <p class="text-[10px] text-slate-400 mt-1">Seuls les rôles autorisés par l'entreprise (hors Admin & Chef d'agence) sont disponibles.</p>
                    </div>

                    <div class="md:col-span-2 flex gap-3 justify-end pt-4 border-t border-slate-100">
                        <button
                            type="button"
                            @click="closeModal"
                            class="px-5 py-2.5 border border-slate-200 text-slate-700 font-semibold rounded-xl hover:bg-slate-100 transition text-sm"
                        >
                            Annuler
                        </button>
                        <button
                            type="submit"
                            :disabled="submitting"
                            class="px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-xl hover:from-emerald-700 hover:to-teal-700 disabled:opacity-50 shadow transition text-sm flex items-center gap-1.5"
                        >
                            <svg v-if="submitting" class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                            </svg>
                            {{ submitting ? 'Enregistrement...' : 'Enregistrer' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Success Modal -->
        <div v-if="showSuccess" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 text-center">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-emerald-100 mx-auto mb-4 text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Collaborateur Ajouté</h3>
                <p class="text-slate-500 text-sm mb-6">Le collaborateur a été créé avec succès pour votre agence.</p>
                <button @click="showSuccess = false" class="w-full px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition text-sm">Fermer</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const loading = ref(true);
const submitting = ref(false);
const showModal = ref(false);
const showSuccess = ref(false);

const roles = ref([]);
const employees = ref([]);
const errors = ref({});

const form = ref({
    name: '',
    email: '',
    password: '',
    phone: '',
    position: '',
    role_id: '',
});

const loadData = async () => {
    loading.value = true;
    try {
        const response = await axios.get(route('agence.employees.data'));
        roles.value = response.data.roles;
        employees.value = response.data.employees;
    } catch (err) {
        console.error('Error loading employees data:', err);
    } finally {
        loading.value = false;
    }
};

const openAddModal = () => {
    form.value = { name: '', email: '', password: '', phone: '', position: '', role_id: '' };
    errors.value = {};
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const submitForm = async () => {
    submitting.value = true;
    errors.value = {};
    try {
        await axios.post(route('agence.employees.store'), form.value);
        showModal.value = false;
        showSuccess.value = true;
        await loadData();
    } catch (err) {
        if (err.response && err.response.data && err.response.data.errors) {
            errors.value = err.response.data.errors;
        } else {
            console.error('Error creating employee:', err);
        }
    } finally {
        submitting.value = false;
    }
};

onMounted(() => {
    loadData();
});
</script>
