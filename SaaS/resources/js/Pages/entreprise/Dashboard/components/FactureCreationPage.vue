<template>
    <div class="flex flex-col gap-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Création de Facture</h1>
                <p class="text-slate-600 mt-1">Créer une nouvelle facture</p>
            </div>
            <div class="flex gap-3">
                <button
                    @click="previewFacture"
                    class="flex items-center gap-2 px-4 py-3 bg-slate-100 text-slate-700 rounded-xl font-medium hover:bg-slate-200 transition-all"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Aperçu
                </button>
                <button
                    @click="saveFacture"
                    class="flex items-center gap-2 px-4 py-3 bg-gradient-to-r from-violet-600 to-purple-600 text-white rounded-xl font-medium shadow-lg shadow-violet-500/30 hover:shadow-xl hover:shadow-violet-500/40 transition-all"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    Enregistrer
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Client Information -->
                <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100">
                    <h2 class="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <svg class="w-6 h-6 text-violet-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                        Informations Client
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="group">
                            <label class="block text-sm font-semibold text-slate-700 mb-2 group-focus-within:text-violet-600 transition-colors">Client</label>
                            <select v-model="facture.clientId" class="w-full px-4 py-3 bg-white border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all shadow-sm group-hover:shadow-md">
                                <option value="">Sélectionner un client</option>
                                <option v-for="client in clients" :key="client.id" :value="client.id">{{ client.nom }}</option>
                            </select>
                        </div>
                        <div class="group">
                            <label class="block text-sm font-semibold text-slate-700 mb-2 group-focus-within:text-violet-600 transition-colors">Email</label>
                            <input v-model="facture.clientEmail" type="email" placeholder="client@email.com" class="w-full px-4 py-3 bg-white border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all shadow-sm group-hover:shadow-md">
                        </div>
                        <div class="group md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-2 group-focus-within:text-violet-600 transition-colors">Adresse</label>
                            <input v-model="facture.clientAdresse" type="text" placeholder="Adresse complète" class="w-full px-4 py-3 bg-white border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all shadow-sm group-hover:shadow-md">
                        </div>
                    </div>
                </div>

                <!-- Invoice Details -->
                <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100">
                    <h2 class="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <svg class="w-6 h-6 text-violet-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                        </svg>
                        Détails de la Facture
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="group">
                            <label class="block text-sm font-semibold text-slate-700 mb-2 group-focus-within:text-violet-600 transition-colors">Numéro de facture</label>
                            <input v-model="facture.numero" type="text" placeholder="FAC-2024-001" class="w-full px-4 py-3 bg-white border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all shadow-sm group-hover:shadow-md">
                        </div>
                        <div class="group">
                            <label class="block text-sm font-semibold text-slate-700 mb-2 group-focus-within:text-violet-600 transition-colors">Date d'émission</label>
                            <input v-model="facture.dateEmission" type="date" class="w-full px-4 py-3 bg-white border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all shadow-sm group-hover:shadow-md">
                        </div>
                        <div class="group">
                            <label class="block text-sm font-semibold text-slate-700 mb-2 group-focus-within:text-violet-600 transition-colors">Date d'échéance</label>
                            <input v-model="facture.dateEcheance" type="date" class="w-full px-4 py-3 bg-white border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all shadow-sm group-hover:shadow-md">
                        </div>
                    </div>
                </div>

                <!-- Line Items -->
                <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                            <svg class="w-6 h-6 text-violet-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                            </svg>
                            Lignes de facture
                        </h2>
                        <button @click="addLigne" class="flex items-center gap-2 px-3 py-2 bg-violet-100 text-violet-700 rounded-lg font-medium hover:bg-violet-200 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Ajouter
                        </button>
                    </div>
                    
                    <div class="space-y-4">
                        <div v-for="(ligne, index) in facture.lignes" :key="index" class="bg-slate-50 rounded-xl p-4">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                                <div class="md:col-span-5">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Description</label>
                                    <input v-model="ligne.description" type="text" placeholder="Description du produit/service" class="w-full px-3 py-2 bg-white border-2 border-slate-200 rounded-lg focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Quantité</label>
                                    <input v-model="ligne.quantite" type="number" min="1" @input="calculateTotals" class="w-full px-3 py-2 bg-white border-2 border-slate-200 rounded-lg focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Prix unitaire (€)</label>
                                    <input v-model="ligne.prixUnitaire" type="number" min="0" step="0.01" @input="calculateTotals" class="w-full px-3 py-2 bg-white border-2 border-slate-200 rounded-lg focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">TVA (%)</label>
                                    <input v-model="ligne.tva" type="number" min="0" max="100" step="0.1" @input="calculateTotals" class="w-full px-3 py-2 bg-white border-2 border-slate-200 rounded-lg focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all">
                                </div>
                                <div class="md:col-span-1 flex items-end">
                                    <button @click="removeLigne(index)" class="w-full px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-all">
                                        <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="mt-2 text-right text-sm text-slate-600">
                                Total: {{ (ligne.quantite * ligne.prixUnitaire).toFixed(2) }}€
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100">
                    <h2 class="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <svg class="w-6 h-6 text-violet-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        Notes
                    </h2>
                    <textarea v-model="facture.notes" rows="3" placeholder="Notes ou conditions particulières..." class="w-full px-4 py-3 bg-white border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all shadow-sm"></textarea>
                </div>
            </div>

            <!-- Right Column - Summary -->
            <div class="space-y-6">
                <!-- Summary Card -->
                <div class="bg-gradient-to-br from-violet-50 to-purple-50 rounded-2xl shadow-lg shadow-violet-200/50 p-6 border border-violet-100 sticky top-6">
                    <h2 class="text-xl font-bold text-slate-900 mb-4">Récapitulatif</h2>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between text-slate-600">
                            <span>Sous-total HT</span>
                            <span class="font-semibold">{{ sousTotalHT.toFixed(2) }}€</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>TVA totale</span>
                            <span class="font-semibold">{{ tvaTotale.toFixed(2) }}€</span>
                        </div>
                        <div class="border-t border-violet-200 pt-3 mt-3">
                            <div class="flex justify-between text-lg font-bold text-slate-900">
                                <span>Total TTC</span>
                                <span class="text-violet-600">{{ totalTTC.toFixed(2) }}€</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-violet-200">
                        <div class="group">
                            <label class="block text-sm font-semibold text-slate-700 mb-2 group-focus-within:text-violet-600 transition-colors">Statut</label>
                            <select v-model="facture.statut" class="w-full px-4 py-3 bg-white border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all shadow-sm group-hover:shadow-md">
                                <option value="Brouillon">Brouillon</option>
                                <option value="Envoyée">Envoyée</option>
                                <option value="Payée">Payée</option>
                                <option value="En retard">En retard</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6 space-y-3">
                        <button @click="saveFacture" class="w-full px-6 py-4 bg-gradient-to-r from-violet-500 to-purple-600 text-white rounded-xl font-semibold shadow-lg shadow-violet-500/30 hover:shadow-xl hover:shadow-violet-500/40 transition-all hover:scale-[1.02]">
                            Enregistrer la facture
                        </button>
                        <button @click="resetForm" class="w-full px-6 py-4 bg-slate-100 text-slate-700 rounded-xl font-semibold hover:bg-slate-200 transition-all">
                            Réinitialiser
                        </button>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Statistiques rapides</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Nombre de lignes</span>
                            <span class="font-semibold text-slate-900">{{ facture.lignes.length }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Moyenne par ligne</span>
                            <span class="font-semibold text-slate-900">{{ moyenneLigne.toFixed(2) }}€</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">TVA moyenne</span>
                            <span class="font-semibold text-slate-900">{{ moyenneTVA.toFixed(1) }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Modal -->
        <div v-if="showSuccess" class="fixed inset-0 bg-black/60 backdrop-blur-md z-50 flex items-center justify-center p-4 animate-modal-in">
            <div class="bg-gradient-to-br from-white via-white to-emerald-50/30 rounded-3xl shadow-2xl shadow-emerald-900/20 max-w-md w-full p-8 border border-emerald-100/50 relative overflow-hidden">
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-gradient-to-br from-emerald-400/20 to-green-500/20 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-gradient-to-br from-emerald-400/20 to-green-500/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center justify-center w-20 h-20 rounded-3xl bg-gradient-to-br from-emerald-500 to-green-600 mx-auto mb-6 shadow-xl shadow-emerald-500/30 animate-bounce-slow">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-center bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent mb-3">Facture créée avec succès</h3>
                    <p class="text-center text-slate-600 mb-8 text-lg">La facture {{ facture.numero }} a été enregistrée</p>

                    <button @click="closeSuccess" class="w-full px-6 py-4 bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-2xl font-semibold shadow-lg shadow-emerald-500/30 hover:shadow-xl hover:shadow-emerald-500/40 transition-all hover:scale-[1.02]">Fermer</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const clients = ref([
    { id: 1, nom: 'Jean Dupont' },
    { id: 2, nom: 'Marie Martin' },
    { id: 3, nom: 'Pierre Bernard' },
    { id: 4, nom: 'Sophie Richard' },
    { id: 5, nom: 'Lucas Petit' },
]);

const facture = ref({
    clientId: '',
    clientEmail: '',
    clientAdresse: '',
    numero: '',
    dateEmission: '',
    dateEcheance: '',
    lignes: [
        { description: '', quantite: 1, prixUnitaire: 0, tva: 20 }
    ],
    notes: '',
    statut: 'Brouillon'
});

const showSuccess = ref(false);

const sousTotalHT = computed(() => {
    return facture.value.lignes.reduce((total, ligne) => {
        return total + (ligne.quantite * ligne.prixUnitaire);
    }, 0);
});

const tvaTotale = computed(() => {
    return facture.value.lignes.reduce((total, ligne) => {
        const ligneHT = ligne.quantite * ligne.prixUnitaire;
        return total + (ligneHT * (ligne.tva / 100));
    }, 0);
});

const totalTTC = computed(() => {
    return sousTotalHT.value + tvaTotale.value;
});

const moyenneLigne = computed(() => {
    if (facture.value.lignes.length === 0) return 0;
    return sousTotalHT.value / facture.value.lignes.length;
});

const moyenneTVA = computed(() => {
    if (facture.value.lignes.length === 0) return 0;
    const totalTVA = facture.value.lignes.reduce((sum, ligne) => sum + ligne.tva, 0);
    return totalTVA / facture.value.lignes.length;
});

const calculateTotals = () => {
    // Computed properties will automatically recalculate
};

const addLigne = () => {
    facture.value.lignes.push({ description: '', quantite: 1, prixUnitaire: 0, tva: 20 });
};

const removeLigne = (index) => {
    if (facture.value.lignes.length > 1) {
        facture.value.lignes.splice(index, 1);
    }
};

const saveFacture = () => {
    showSuccess.value = true;
};

const previewFacture = () => {
    alert('Fonctionnalité d\'aperçu à implémenter');
};

const resetForm = () => {
    facture.value = {
        clientId: '',
        clientEmail: '',
        clientAdresse: '',
        numero: '',
        dateEmission: '',
        dateEcheance: '',
        lignes: [
            { description: '', quantite: 1, prixUnitaire: 0, tva: 20 }
        ],
        notes: '',
        statut: 'Brouillon'
    };
};

const closeSuccess = () => {
    showSuccess.value = false;
};
</script>

<style scoped>
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

.animate-modal-in {
    animation: modalIn 0.3s ease-out forwards;
}

.animate-bounce-slow {
    animation: bounceSlow 2s ease-in-out infinite;
}
</style>
