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
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Type / Catégorie</th>
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                <span v-if="depense.type_depense" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-violet-50 text-violet-700 border border-violet-100">
                                    {{ depense.type_depense.nom }}
                                </span>
                                <span v-else class="text-slate-400 italic">
                                    {{ depense.categorie || '-' }}
                                </span>
                            </td>
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
                <div class="inline-block transform overflow-hidden rounded-2xl bg-white text-left align-bottom shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle animate-scale-up border border-slate-100">
                    
                    <div class="bg-gradient-to-r from-violet-500 to-indigo-600 px-6 py-4 flex items-center justify-between text-white">
                        <div>
                            <h3 class="text-lg font-bold leading-6">{{ isEditing ? 'Modifier la dépense' : 'Nouvelle dépense' }}</h3>
                            <p class="text-xs text-violet-100 mt-1">Saisissez les détails de la sortie de fonds ci-dessous</p>
                        </div>
                        <button @click="closeModal" class="text-white/80 hover:text-white transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="bg-white px-6 pt-6 pb-6 space-y-6">
                        <div class="grid grid-cols-2 gap-5">
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Titre de la dépense <span class="text-rose-500">*</span></label>
                                <div class="relative rounded-2xl shadow-sm">
                                    <input 
                                        v-model="form.titre" 
                                        type="text" 
                                        placeholder="Ex: Achat fournitures de bureau"
                                        class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-2xl text-slate-700 placeholder-slate-400 focus:outline-none focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all font-medium" 
                                        required 
                                    />
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Montant (XAF) <span class="text-rose-500">*</span></label>
                                <input 
                                    v-model="form.montant" 
                                    type="number" 
                                    step="0.01" 
                                    placeholder="0.00"
                                    class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-2xl text-slate-700 placeholder-slate-400 focus:outline-none focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all font-medium" 
                                    required 
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Date <span class="text-rose-500">*</span></label>
                                <input 
                                    v-model="form.date_depense" 
                                    type="date" 
                                    class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-2xl text-slate-700 focus:outline-none focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all font-medium" 
                                    required 
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Type de dépense <span class="text-rose-500">*</span></label>
                                <select 
                                    v-model="form.type_depense_id" 
                                    class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-2xl text-slate-700 focus:outline-none focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all font-medium cursor-pointer"
                                    required
                                >
                                    <option :value="null">Sélectionner un type</option>
                                    <option v-for="t in typeDepenses" :key="t.id" :value="t.id">
                                        {{ t.nom }}
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Statut</label>
                                <select 
                                    v-model="form.statut" 
                                    class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-2xl text-slate-700 focus:outline-none focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all font-medium cursor-pointer"
                                >
                                    <option value="En attente">En attente</option>
                                    <option value="Payé">Payé</option>
                                    <option value="Annulé">Annulé</option>
                                </select>
                            </div>

                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Référence (N° Facture / Reçu)</label>
                                <input 
                                    v-model="form.reference" 
                                    type="text" 
                                    placeholder="Ex: FAC-2026-0042"
                                    class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-2xl text-slate-700 placeholder-slate-400 focus:outline-none focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all font-medium" 
                                />
                            </div>

                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Description (Optionnel)</label>
                                <textarea 
                                    v-model="form.description" 
                                    rows="3" 
                                    placeholder="Ajoutez des détails supplémentaires..."
                                    class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-2xl text-slate-700 placeholder-slate-400 focus:outline-none focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all font-medium"
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 px-6 py-4 flex flex-col sm:flex-row-reverse gap-3 border-t border-slate-100">
                        <button
                            @click="saveDepense"
                            :disabled="isLoading"
                            class="px-6 py-3.5 bg-gradient-to-r from-violet-500 via-violet-600 to-indigo-600 text-white rounded-2xl text-sm font-bold hover:shadow-lg hover:shadow-violet-500/20 transition-all transform hover:scale-[1.02] flex items-center justify-center gap-2 disabled:opacity-50"
                        >
                            <span v-if="isLoading" class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></span>
                            <span>{{ isEditing ? 'Mettre à jour' : 'Enregistrer' }}</span>
                        </button>
                        <button
                            @click="closeModal"
                            class="px-6 py-3.5 bg-white border-2 border-slate-300 text-slate-700 rounded-2xl text-sm font-bold hover:bg-slate-50 transition-all transform hover:scale-[1.02]"
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
const typeDepenses = ref([]);
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
    type_depense_id: null,
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

const fetchTypeDepenses = async () => {
    try {
        const response = await axios.get('/api/type-depenses');
        typeDepenses.value = response.data;
    } catch (error) {
        console.error('Erreur lors de la récupération des types de dépenses:', error);
    }
};

onMounted(() => {
    fetchDepenses();
    fetchTypeDepenses();
});

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
        (d.type_depense?.nom && d.type_depense.nom.toLowerCase().includes(query)) ||
        (d.categorie && d.categorie.toLowerCase().includes(query))
    );
});

const openModal = (depense = null) => {
    if (depense) {
        isEditing.value = true;
        form.value = { 
            id: depense.id,
            titre: depense.titre,
            description: depense.description || '',
            montant: depense.montant,
            date_depense: depense.date_depense ? depense.date_depense.split('T')[0] : new Date().toISOString().split('T')[0],
            type_depense_id: depense.type_depense_id || null,
            categorie: depense.categorie || '',
            reference: depense.reference || '',
            statut: depense.statut || 'En attente'
        };
    } else {
        isEditing.value = false;
        form.value = {
            id: null,
            titre: '',
            description: '',
            montant: 0,
            date_depense: new Date().toISOString().split('T')[0],
            type_depense_id: null,
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
    if (!form.value.titre || !form.value.montant || !form.value.date_depense || !form.value.type_depense_id) {
        alert("Veuillez remplir tous les champs obligatoires (*)");
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
