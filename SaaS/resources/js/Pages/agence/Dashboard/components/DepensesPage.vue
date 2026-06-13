<template>
    <div class="p-6">
        <!-- Header -->
        <div class="mb-8 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Dépenses</h1>
                <p class="text-sm text-slate-500">Gérez vos sorties de trésorerie de manière centralisée.</p>
            </div>
            <button
                @click="openModal()"
                class="inline-flex items-center gap-2 rounded-xl bg-violet-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-violet-700 hover:shadow-md focus:ring-2 focus:ring-violet-600/50"
            >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouvelle Dépense
            </button>
        </div>

        <!-- KPIs -->
        <div class="mb-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Total Dépenses</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ formatCurrency(kpis.total) }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-violet-100 text-violet-600">
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
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
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
                <h2 class="text-lg font-semibold text-slate-800">Historique des Dépenses</h2>
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Rechercher (Titre, Référence)..."
                    class="rounded-lg border-slate-300 text-sm focus:border-violet-500 focus:ring-violet-500 w-64"
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
                        <tr v-for="depense in filteredDepenses" :key="depense.id" class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">{{ formatDate(depense.date_depense) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ depense.titre }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ depense.categorie || '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-slate-900">{{ formatCurrency(depense.montant) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ depense.reference || '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold"
                                    :class="{
                                        'bg-emerald-100 text-emerald-800': depense.statut === 'Payé',
                                        'bg-amber-100 text-amber-800': depense.statut === 'En attente',
                                        'bg-rose-100 text-rose-800': depense.statut === 'Annulé'
                                    }"
                                >
                                    {{ depense.statut }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button @click="openModal(depense)" class="text-indigo-600 hover:text-indigo-900 mr-4">Modifier</button>
                                <button @click="deleteDepense(depense.id)" class="text-rose-600 hover:text-rose-900">Supprimer</button>
                            </td>
                        </tr>
                        <tr v-if="filteredDepenses.length === 0">
                            <td colspan="7" class="px-6 py-12 text-center text-sm text-slate-500">
                                Aucune dépense trouvée.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal -->
        <div v-if="isModalOpen" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-screen items-center justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="closeModal"></div>
                <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>
                <div class="inline-block transform overflow-hidden rounded-2xl bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle animate-scale-up">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 w-full text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg font-bold leading-6 text-slate-900 mb-6">
                                    {{ isEditing ? 'Modifier la dépense' : 'Nouvelle dépense' }}
                                </h3>
                                <div class="space-y-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="col-span-2">
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Titre de la dépense <span class="text-rose-500">*</span></label>
                                            <input v-model="form.titre" type="text" class="w-full rounded-lg border-slate-300 focus:border-violet-500 focus:ring-violet-500" required />
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Montant <span class="text-rose-500">*</span></label>
                                            <input v-model="form.montant" type="number" step="0.01" class="w-full rounded-lg border-slate-300 focus:border-violet-500 focus:ring-violet-500" required />
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Date <span class="text-rose-500">*</span></label>
                                            <input v-model="form.date_depense" type="date" class="w-full rounded-lg border-slate-300 focus:border-violet-500 focus:ring-violet-500" required />
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Catégorie</label>
                                            <input v-model="form.categorie" type="text" placeholder="ex: Maintenance" class="w-full rounded-lg border-slate-300 focus:border-violet-500 focus:ring-violet-500" />
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Statut</label>
                                            <select v-model="form.statut" class="w-full rounded-lg border-slate-300 focus:border-violet-500 focus:ring-violet-500">
                                                <option value="En attente">En attente</option>
                                                <option value="Payé">Payé</option>
                                                <option value="Annulé">Annulé</option>
                                            </select>
                                        </div>
                                        <div class="col-span-2">
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Référence (N° Facture / Reçu)</label>
                                            <input v-model="form.reference" type="text" class="w-full rounded-lg border-slate-300 focus:border-violet-500 focus:ring-violet-500" />
                                        </div>
                                        <div class="col-span-2">
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Description (Optionnel)</label>
                                            <textarea v-model="form.description" rows="3" class="w-full rounded-lg border-slate-300 focus:border-violet-500 focus:ring-violet-500"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6">
                        <button
                            @click="saveDepense"
                            :disabled="isLoading"
                            class="inline-flex w-full justify-center rounded-xl border border-transparent bg-violet-600 px-4 py-2 text-base font-semibold text-white shadow-sm hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm transition-all disabled:opacity-50"
                        >
                            {{ isLoading ? 'Enregistrement...' : (isEditing ? 'Mettre à jour' : 'Enregistrer') }}
                        </button>
                        <button
                            @click="closeModal"
                            class="mt-3 inline-flex w-full justify-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-base font-medium text-slate-700 shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-all"
                        >
                            Annuler
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

const depenses = ref([]);
const isModalOpen = ref(false);
const isEditing = ref(false);
const isLoading = ref(false);
const searchQuery = ref('');

const form = ref({
    id: null,
    titre: '',
    description: '',
    montant: 0,
    date_depense: new Date().toISOString().split('T')[0],
    categorie: '',
    reference: '',
    statut: 'En attente',
});

const fetchDepenses = async () => {
    try {
        const response = await axios.get('/api/depenses');
        depenses.value = response.data;
    } catch (error) {
        console.error('Erreur lors de la récupération des dépenses:', error);
    }
};

onMounted(fetchDepenses);

const kpis = computed(() => {
    const today = new Date();
    const currentMonth = today.getMonth();
    const currentYear = today.getFullYear();

    let total = 0;
    let month = 0;
    let pending = 0;

    depenses.value.forEach(d => {
        const amt = parseFloat(d.montant);
        total += amt;
        
        if (d.statut === 'En attente') {
            pending += amt;
        }

        const dateD = new Date(d.date_depense);
        if (dateD.getMonth() === currentMonth && dateD.getFullYear() === currentYear) {
            month += amt;
        }
    });

    return { total, month, pending };
});

const filteredDepenses = computed(() => {
    if (!searchQuery.value) return depenses.value;
    const query = searchQuery.value.toLowerCase();
    return depenses.value.filter(d => 
        (d.titre && d.titre.toLowerCase().includes(query)) ||
        (d.reference && d.reference.toLowerCase().includes(query)) ||
        (d.categorie && d.categorie.toLowerCase().includes(query))
    );
});

const openModal = (depense = null) => {
    if (depense) {
        isEditing.value = true;
        form.value = { ...depense };
    } else {
        isEditing.value = false;
        form.value = {
            id: null,
            titre: '',
            description: '',
            montant: 0,
            date_depense: new Date().toISOString().split('T')[0],
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

const saveDepense = async () => {
    if (!form.value.titre || !form.value.montant || !form.value.date_depense) {
        alert("Veuillez remplir les champs obligatoires (*)");
        return;
    }

    isLoading.value = true;
    try {
        if (isEditing.value) {
            await axios.put(`/api/depenses/${form.value.id}`, form.value);
        } else {
            await axios.post('/api/depenses', form.value);
        }
        await fetchDepenses();
        closeModal();
    } catch (error) {
        console.error('Erreur lors de la sauvegarde:', error);
        alert('Une erreur est survenue.');
    } finally {
        isLoading.value = false;
    }
};

const deleteDepense = async (id) => {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette dépense ?')) {
        try {
            await axios.delete(`/api/depenses/${id}`);
            await fetchDepenses();
        } catch (error) {
            console.error('Erreur lors de la suppression:', error);
            alert('Une erreur est survenue.');
        }
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
