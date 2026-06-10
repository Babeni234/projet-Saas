<template>
    <div class="flex flex-col gap-6">
        <!-- Header with Add Button -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Factures</h1>
                <p class="text-slate-600 mt-1">Gestion des factures</p>
            </div>
            <button
                @click="createNewFacture"
                class="flex items-center gap-2 px-4 py-3 bg-gradient-to-r from-violet-600 to-purple-600 text-white rounded-xl font-medium shadow-lg shadow-violet-500/30 hover:shadow-xl hover:shadow-violet-500/40 transition-all"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouvelle Facture
            </button>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-violet-500/20 animate-fade-in" style="animation-delay: 0ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Total Factures</p>
                        <p class="text-3xl font-bold text-slate-900 mt-1 animate-number">{{ totalFactures }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-violet-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-violet-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-emerald-500/20 animate-fade-in" style="animation-delay: 100ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Payées</p>
                        <p class="text-3xl font-bold text-emerald-600 mt-1 animate-number">{{ facturesPayees }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-amber-500/20 animate-fade-in" style="animation-delay: 200ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">En Attente</p>
                        <p class="text-3xl font-bold text-amber-600 mt-1 animate-number">{{ facturesEnAttente }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-rose-500/20 animate-fade-in" style="animation-delay: 300ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">En Retard</p>
                        <p class="text-3xl font-bold text-rose-600 mt-1 animate-number">{{ facturesEnRetard }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-rose-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l.707.707a1 1 0 001.414-1.414L10 8.586l.707-.707a1 1 0 00-1.414-1.414L8.586 8 8.293 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-4 border border-slate-100">
            <div class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Rechercher une facture..."
                            class="w-full pl-12 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-transparent"
                        >
                    </div>
                </div>
                <div class="min-w-[180px]">
                    <select v-model="filterStatut" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-transparent">
                        <option value="">Tous les statuts</option>
                        <option value="Brouillon">Brouillon</option>
                        <option value="Envoyée">Envoyée</option>
                        <option value="Payée">Payée</option>
                        <option value="En retard">En retard</option>
                    </select>
                </div>
                <div class="min-w-[180px]">
                    <select v-model="filterClient" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-transparent">
                        <option value="">Tous les clients</option>
                        <option v-for="client in clients" :key="client.id" :value="client.nom">{{ client.nom }}</option>
                    </select>
                </div>
                <div class="min-w-[180px]">
                    <select v-model="filterPeriode" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-transparent">
                        <option value="">Toutes les périodes</option>
                        <option value="ce_mois">Ce mois</option>
                        <option value="mois_dernier">Le mois dernier</option>
                        <option value="ce_trimestre">Ce trimestre</option>
                        <option value="cette_annee">Cette année</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 overflow-hidden border border-slate-100">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Numéro</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Client</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Date d'émission</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Date d'échéance</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Montant TTC</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Statut</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="facture in filteredFactures" :key="facture.id" class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ facture.numero }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ facture.client }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ facture.dateEmission }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ facture.dateEcheance }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ facture.montantTTC }}€</td>
                            <td class="px-6 py-4">
                                <span :class="{
                                    'px-3 py-1 rounded-full text-xs font-medium': true,
                                    'bg-slate-100 text-slate-700': facture.statut === 'Brouillon',
                                    'bg-blue-100 text-blue-700': facture.statut === 'Envoyée',
                                    'bg-emerald-100 text-emerald-700': facture.statut === 'Payée',
                                    'bg-rose-100 text-rose-700': facture.statut === 'En retard'
                                }">
                                    {{ facture.statut }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <button @click="viewFacture(facture)" class="p-2 text-violet-600 hover:bg-violet-50 rounded-lg transition-colors" title="Voir">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    <button @click="editFacture(facture)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Modifier">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button @click="downloadFacture(facture)" class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Télécharger PDF">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </button>
                                    <button @click="sendFacture(facture)" class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Envoyer par email">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </button>
                                    <button @click="deleteFacture(facture)" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Supprimer">
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

        <!-- View Modal -->
        <div v-if="showViewModal" class="fixed inset-0 bg-black/60 backdrop-blur-md z-50 flex items-center justify-center p-4 animate-modal-in">
            <div class="bg-gradient-to-br from-white via-white to-violet-50/30 rounded-3xl shadow-2xl shadow-violet-900/20 max-w-2xl w-full p-8 border border-violet-100/50 relative overflow-hidden max-h-[90vh] overflow-y-auto">
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-gradient-to-br from-violet-400/20 to-purple-500/20 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-gradient-to-br from-violet-400/20 to-purple-500/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent">Détails de la Facture</h2>
                        <button @click="closeViewModal" class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-all hover:rotate-90">
                            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div v-if="selectedFacture" class="space-y-4">
                        <div class="bg-slate-50 rounded-xl p-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-slate-600">Numéro</p>
                                    <p class="font-semibold text-slate-900">{{ selectedFacture.numero }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-600">Statut</p>
                                    <span :class="{
                                        'px-3 py-1 rounded-full text-xs font-medium': true,
                                        'bg-slate-100 text-slate-700': selectedFacture.statut === 'Brouillon',
                                        'bg-blue-100 text-blue-700': selectedFacture.statut === 'Envoyée',
                                        'bg-emerald-100 text-emerald-700': selectedFacture.statut === 'Payée',
                                        'bg-rose-100 text-rose-700': selectedFacture.statut === 'En retard'
                                    }">
                                        {{ selectedFacture.statut }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-600">Client</p>
                                    <p class="font-semibold text-slate-900">{{ selectedFacture.client }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-600">Email</p>
                                    <p class="font-semibold text-slate-900">{{ selectedFacture.email }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-600">Date d'émission</p>
                                    <p class="font-semibold text-slate-900">{{ selectedFacture.dateEmission }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-600">Date d'échéance</p>
                                    <p class="font-semibold text-slate-900">{{ selectedFacture.dateEcheance }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-50 rounded-xl p-4">
                            <h3 class="font-semibold text-slate-900 mb-3">Lignes de facture</h3>
                            <div class="space-y-2">
                                <div v-for="(ligne, index) in selectedFacture.lignes" :key="index" class="flex justify-between text-sm">
                                    <span class="text-slate-600">{{ ligne.description }} (x{{ ligne.quantite }})</span>
                                    <span class="font-medium text-slate-900">{{ (ligne.quantite * ligne.prixUnitaire).toFixed(2) }}€</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-violet-50 to-purple-50 rounded-xl p-4">
                            <div class="space-y-2">
                                <div class="flex justify-between text-slate-600">
                                    <span>Sous-total HT</span>
                                    <span>{{ selectedFacture.sousTotalHT.toFixed(2) }}€</span>
                                </div>
                                <div class="flex justify-between text-slate-600">
                                    <span>TVA</span>
                                    <span>{{ selectedFacture.tvaTotale.toFixed(2) }}€</span>
                                </div>
                                <div class="flex justify-between text-lg font-bold text-slate-900 pt-2 border-t border-violet-200">
                                    <span>Total TTC</span>
                                    <span class="text-violet-600">{{ selectedFacture.montantTTC.toFixed(2) }}€</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button @click="downloadFacture(selectedFacture)" class="flex-1 px-4 py-3 bg-emerald-100 text-emerald-700 rounded-xl font-semibold hover:bg-emerald-200 transition-all flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Télécharger PDF
                            </button>
                            <button @click="sendFacture(selectedFacture)" class="flex-1 px-4 py-3 bg-blue-100 text-blue-700 rounded-xl font-semibold hover:bg-blue-200 transition-all flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Envoyer par email
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div v-if="showDeleteModal" class="fixed inset-0 bg-black/60 backdrop-blur-md z-50 flex items-center justify-center p-4 animate-modal-in">
            <div class="bg-gradient-to-br from-white via-white to-red-50/30 rounded-3xl shadow-2xl shadow-red-900/20 max-w-md w-full p-8 border border-red-100/50 relative overflow-hidden">
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-gradient-to-br from-red-400/20 to-pink-500/20 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-gradient-to-br from-red-400/20 to-pink-500/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center justify-center w-20 h-20 rounded-3xl bg-gradient-to-br from-red-500 to-pink-600 mx-auto mb-6 shadow-xl shadow-red-500/30">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-center bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent mb-3">Supprimer cette facture?</h3>
                    <p class="text-center text-slate-600 mb-8 text-lg">Cette action est irréversible</p>

                    <div class="flex gap-4">
                        <button @click="closeDeleteModal" class="flex-1 px-6 py-4 bg-slate-100 text-slate-700 rounded-2xl font-semibold hover:bg-slate-200 transition-all hover:shadow-lg">Annuler</button>
                        <button @click="confirmDelete" class="flex-1 px-6 py-4 bg-gradient-to-r from-red-500 to-pink-600 text-white rounded-2xl font-semibold shadow-lg shadow-red-500/30 hover:shadow-xl hover:shadow-red-500/40 transition-all hover:scale-[1.02]">Supprimer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import { usePage } from '@inertiajs/vue3';

const router = useRouter();
const page = usePage();
const currentAgencyId = computed(() => page.props.auth?.user?.employee?.agency_id);

// Dynamic list of agency tenants to filter invoices
const systemLocataires = computed(() => {
    const stored = localStorage.getItem('immobilier_locataires');
    let locs = [];
    if (stored) {
        locs = JSON.parse(stored);
    } else {
        locs = [
            { nom: 'Jean Dupont' },
            { nom: 'Marie Martin' },
            { nom: 'Pierre Bernard' }
        ];
    }
    
    // Filter by agency logements
    const storedLogs = localStorage.getItem('immobilier_logements');
    const storedBats = localStorage.getItem('immobilier_batiments');
    
    let bats = storedBats ? JSON.parse(storedBats) : [];
    const agencyId = currentAgencyId.value;
    const agencyBuildingNames = bats.filter(b => Number(b.agency_id) === Number(agencyId)).map(b => b.nom);
    
    let logs = storedLogs ? JSON.parse(storedLogs) : [];
    const agencyLogementRefs = logs.filter(l => agencyBuildingNames.includes(l.batiment)).map(l => l.reference);
    
    return locs.filter(l => agencyLogementRefs.includes(l.logement) || Number(l.agency_id) === Number(agencyId));
});

const clients = computed(() => {
    return systemLocataires.value.map((l, index) => ({ id: index + 1, nom: l.nom }));
});

const factures = ref([
    { 
        id: 1, 
        numero: 'FAC-2024-001', 
        client: 'Jean Dupont', 
        email: 'jean.dupont@email.com',
        dateEmission: '2024-01-15', 
        dateEcheance: '2024-02-15', 
        montantTTC: 1200.00,
        statut: 'Payée',
        sousTotalHT: 1000.00,
        tvaTotale: 200.00,
        lignes: [
            { description: 'Location appartement', quantite: 1, prixUnitaire: 1000, tva: 20 }
        ]
    },
    { 
        id: 2, 
        numero: 'FAC-2024-002', 
        client: 'Marie Martin', 
        email: 'marie.martin@email.com',
        dateEmission: '2024-01-20', 
        dateEcheance: '2024-02-20', 
        montantTTC: 950.00,
        statut: 'Envoyée',
        sousTotalHT: 791.67,
        tvaTotale: 158.33,
        lignes: [
            { description: 'Location studio', quantite: 1, prixUnitaire: 791.67, tva: 20 }
        ]
    },
    { 
        id: 3, 
        numero: 'FAC-2024-003', 
        client: 'Pierre Bernard', 
        email: 'pierre.bernard@email.com',
        dateEmission: '2024-01-25', 
        dateEcheance: '2024-02-25', 
        montantTTC: 1500.00,
        statut: 'En retard',
        sousTotalHT: 1250.00,
        tvaTotale: 250.00,
        lignes: [
            { description: 'Location T3', quantite: 1, prixUnitaire: 1250, tva: 20 }
        ]
    },
    { 
        id: 4, 
        numero: 'FAC-2024-004', 
        client: 'Sophie Richard', 
        email: 'sophie.richard@email.com',
        dateEmission: '2024-02-01', 
        dateEcheance: '2024-03-01', 
        montantTTC: 1100.00,
        statut: 'Brouillon',
        sousTotalHT: 916.67,
        tvaTotale: 183.33,
        lignes: [
            { description: 'Location T2', quantite: 1, prixUnitaire: 916.67, tva: 20 }
        ]
    },
    { 
        id: 5, 
        numero: 'FAC-2024-005', 
        client: 'Lucas Petit', 
        email: 'lucas.petit@email.com',
        dateEmission: '2024-02-05', 
        dateEcheance: '2024-03-05', 
        montantTTC: 1350.00,
        statut: 'Envoyée',
        sousTotalHT: 1125.00,
        tvaTotale: 225.00,
        lignes: [
            { description: 'Location duplex', quantite: 1, prixUnitaire: 1125, tva: 20 }
        ]
    },
]);

const searchQuery = ref('');
const filterStatut = ref('');
const filterClient = ref('');
const filterPeriode = ref('');

const showViewModal = ref(false);
const showDeleteModal = ref(false);
const selectedFacture = ref(null);
const deleteTarget = ref(null);

const filteredFactures = computed(() => {
    // Filter by agency tenants
    const agencyLocataireNames = systemLocataires.value.map(l => l.nom);
    let filtered = factures.value.filter(f => agencyLocataireNames.includes(f.client));
    
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(f => 
            f.numero.toLowerCase().includes(query) ||
            f.client.toLowerCase().includes(query) ||
            f.email.toLowerCase().includes(query)
        );
    }
    
    if (filterStatut.value) {
        filtered = filtered.filter(f => f.statut === filterStatut.value);
    }
    
    if (filterClient.value) {
        filtered = filtered.filter(f => f.client === filterClient.value);
    }
    
    if (filterPeriode.value) {
        const now = new Date();
        filtered = filtered.filter(f => {
            const date = new Date(f.dateEmission);
            switch (filterPeriode.value) {
                case 'ce_mois':
                    return date.getMonth() === now.getMonth() && date.getFullYear() === now.getFullYear();
                case 'mois_dernier':
                    const lastMonth = new Date(now.getFullYear(), now.getMonth() - 1);
                    return date.getMonth() === lastMonth.getMonth() && date.getFullYear() === lastMonth.getFullYear();
                case 'ce_trimestre':
                    const quarter = Math.floor(now.getMonth() / 3);
                    const invoiceQuarter = Math.floor(date.getMonth() / 3);
                    return invoiceQuarter === quarter && date.getFullYear() === now.getFullYear();
                case 'cette_annee':
                    return date.getFullYear() === now.getFullYear();
                default:
                    return true;
            }
        });
    }
    
    return filtered;
});

const totalFactures = computed(() => filteredFactures.value.length);
const facturesPayees = computed(() => filteredFactures.value.filter(f => f.statut === 'Payée').length);
const facturesEnAttente = computed(() => filteredFactures.value.filter(f => f.statut === 'Envoyée').length);
const facturesEnRetard = computed(() => filteredFactures.value.filter(f => f.statut === 'En retard').length);

const createNewFacture = () => {
    router.push({ name: 'agence.accounting.facture-creation' });
};

const viewFacture = (facture) => {
    selectedFacture.value = facture;
    showViewModal.value = true;
};

const editFacture = (facture) => {
    router.push({ name: 'agence.accounting.facture-creation', params: { id: facture.id } });
};

const downloadFacture = (facture) => {
    alert(`Téléchargement du PDF pour la facture ${facture.numero}`);
};

const sendFacture = (facture) => {
    alert(`Envoi par email pour la facture ${facture.numero} à ${facture.email}`);
};

const deleteFacture = (facture) => {
    deleteTarget.value = facture;
    showDeleteModal.value = true;
};

const closeViewModal = () => {
    showViewModal.value = false;
    selectedFacture.value = null;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    deleteTarget.value = null;
};

const confirmDelete = () => {
    if (deleteTarget.value) {
        factures.value = factures.value.filter(f => f.id !== deleteTarget.value.id);
        closeDeleteModal();
    }
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
