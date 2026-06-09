<template>
    <div class="flex flex-col gap-8 select-none">
        
        <!-- Header with Luxury Dark Teal Banner -->
        <div class="bg-gradient-to-br from-slate-900 via-slate-950 to-slate-900 rounded-3xl p-8 border border-slate-800 shadow-2xl text-white relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-48 h-48 rounded-full bg-emerald-500/10 blur-2xl pointer-events-none"></div>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative z-10">
                <div>
                    <span class="text-emerald-400 text-xs font-bold tracking-widest uppercase bg-emerald-500/20 px-3 py-1 rounded-full border border-emerald-500/30">Trésorerie</span>
                    <h1 class="text-3xl font-extrabold bg-gradient-to-r from-white via-slate-100 to-slate-300 bg-clip-text text-transparent mt-2">Paiements de Loyer</h1>
                    <p class="text-slate-400 text-sm mt-1">Enregistrez les règlements perçus, gérez les reçus de loyer et suivez le flux de trésorerie.</p>
                </div>
                <button
                    @click="openAddModal"
                    class="px-5 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-500 border border-emerald-500/50 hover:border-emerald-400 text-white font-bold text-xs transition-all duration-300 transform hover:scale-[1.02] flex items-center gap-2 shadow-lg shadow-emerald-600/35"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Enregistrer un Paiement
                </button>
            </div>
        </div>

        <!-- Premium KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-emerald-200/30 transition-all duration-500 hover:-translate-y-1 border border-slate-150 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-emerald-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 font-bold shadow-sm">€</div>
                </div>
                <div class="text-2xl font-extrabold text-slate-800 mb-1 tracking-tight">{{ formatCurrency(totalCollected) }}</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Encaissé</div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-blue-200/30 transition-all duration-500 hover:-translate-y-1 border border-slate-150 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-blue-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 font-bold shadow-sm">📅</div>
                </div>
                <div class="text-2xl font-extrabold text-blue-600 mb-1 tracking-tight">{{ formatCurrency(collectedThisMonth) }}</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest font-semibold">Encaissé ce Mois</div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-purple-200/30 transition-all duration-500 hover:-translate-y-1 border border-slate-150 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-purple-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600 font-bold shadow-sm">💳</div>
                </div>
                <div class="text-2xl font-extrabold text-purple-650 mb-1 tracking-tight">{{ topPaymentMethod }}</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Méthode Favorite</div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-violet-200/30 transition-all duration-500 hover:-translate-y-1 border border-slate-150 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-violet-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 bg-violet-50 rounded-xl flex items-center justify-center text-violet-600 font-bold shadow-sm">#</div>
                </div>
                <div class="text-2xl font-extrabold text-violet-600 mb-1 tracking-tight">{{ totalTransactions }}</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Transactions</div>
            </div>
        </div>

        <!-- Filter Panel -->
        <div class="bg-gradient-to-br from-white to-slate-50 rounded-3xl p-6 shadow-xl shadow-slate-200/50 border border-slate-150 flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="relative w-full md:w-96">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-450" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Rechercher par locataire, référence..."
                    class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-2xl focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-sm font-semibold transition-all shadow-sm"
                />
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold uppercase text-slate-400">Mode :</span>
                <select v-model="methodFilter" class="bg-white border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:outline-none shadow-sm">
                    <option value="All">Toutes les méthodes</option>
                    <option value="Virement">Virement</option>
                    <option value="Carte">Carte</option>
                    <option value="Espèces">Espèces</option>
                    <option value="Chèque">Chèque</option>
                </select>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-gradient-to-br from-white to-slate-50 rounded-3xl shadow-xl shadow-slate-200/50 overflow-hidden border border-slate-150">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200/50 bg-slate-50/50">
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Paiement</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Locataire</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Facture Liée</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Méthode</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Montant</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-150">
                        <tr v-for="pay in filteredPayments" :key="pay.id" class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 text-sm font-bold text-slate-800">{{ pay.reference }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div :class="['w-9 h-9 rounded-xl flex items-center justify-center text-white font-extrabold text-xs shadow-sm bg-gradient-to-br', getAvatarGradient(pay.locataire)]">
                                        {{ getInitials(pay.locataire) }}
                                    </div>
                                    <div class="font-bold text-slate-800 text-sm">{{ pay.locataire }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 font-bold">{{ pay.factureNumero || 'Paiement Direct' }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600 font-semibold">{{ formatDate(pay.date) }}</td>
                            <td class="px-6 py-4 text-sm text-slate-700 font-bold">{{ pay.methode }}</td>
                            <td class="px-6 py-4 text-sm text-emerald-600 font-extrabold">{{ formatCurrency(pay.montant) }}</td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <button @click="viewReceipt(pay)" class="p-2 text-slate-650 hover:bg-slate-100 rounded-xl transition-all" title="Aperçu / Imprimer Reçu">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </button>
                                    <button @click="deletePayment(pay)" class="p-2 text-red-650 hover:bg-red-50 rounded-xl transition-all" title="Supprimer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="filteredPayments.length === 0">
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400 font-semibold text-sm">
                                Aucun enregistrement de paiement trouvé.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Modal (Record Payment) -->
        <div v-if="showModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-3xl w-full p-8 border border-slate-200 relative overflow-hidden animate-scale-up">
                
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-2xl bg-emerald-600 flex items-center justify-center shadow-md">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h2 class="text-2xl font-extrabold text-slate-800">Enregistrer un Règlement</h2>
                        </div>
                        <button @click="closeModal" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-all">
                            <svg class="w-4 h-4 text-slate-550" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-1 scrollbar-thin">
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Locataire selection -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Locataire Actif *</label>
                                <select 
                                    v-model="selectedLocataireName" 
                                    @change="handleLocataireChange"
                                    class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-semibold text-slate-800 text-sm shadow-sm"
                                >
                                    <option value="" disabled>Sélectionner le locataire émetteur</option>
                                    <option v-for="c in contratsActifs" :key="c.id" :value="c.locataire">
                                        {{ c.locataire }} (Bail {{ c.numero }} - {{ c.reference }})
                                    </option>
                                </select>
                            </div>

                            <!-- Unpaid invoices dropdown -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Facture à imputer *</label>
                                <select 
                                    v-model="selectedInvoiceId" 
                                    @change="handleInvoiceChange"
                                    class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-semibold text-slate-800 text-sm shadow-sm"
                                    :disabled="!selectedLocataireName"
                                >
                                    <option value="" disabled>Choisir la facture correspondante</option>
                                    <option v-for="f in unpaidInvoicesFiltered" :key="f.id" :value="f.id">
                                        {{ f.numero }} - Période: {{ formatPeriod(f.periode) }} (Reste dû: {{ f.total - f.montantPaye }}€)
                                    </option>
                                    <option v-if="unpaidInvoicesFiltered.length === 0 && selectedLocataireName" value="" disabled>Aucune facture impayée pour ce locataire.</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Date -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Date de Règlement *</label>
                                <input type="date" v-model="formData.date" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-semibold text-slate-800 text-sm shadow-sm" />
                            </div>

                            <!-- Method -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Moyen de Paiement *</label>
                                <select v-model="formData.methode" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-semibold text-slate-850 text-sm shadow-sm">
                                    <option value="Virement">Virement</option>
                                    <option value="Carte">Carte Bancaire</option>
                                    <option value="Espèces">Espèces</option>
                                    <option value="Chèque">Chèque</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Amount -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Montant Réglé (€) *</label>
                                <input type="number" v-model.number="formData.montant" placeholder="Ex: 1250" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-semibold text-slate-855 text-sm shadow-sm" />
                            </div>

                            <!-- Receipt transaction reference -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Référence Transaction</label>
                                <input type="text" v-model="formData.referenceTx" placeholder="Ex: TX-9082348" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-semibold text-slate-850 text-sm shadow-sm" />
                            </div>
                        </div>
                    </div>

                    <!-- Footers -->
                    <div class="flex gap-4 mt-8 border-t border-slate-100 pt-5">
                        <button @click="closeModal" class="flex-1 px-5 py-3.5 bg-slate-100 text-slate-650 rounded-xl font-bold hover:bg-slate-200 transition-all text-xs">Annuler</button>
                        <button @click="savePayment" class="flex-1 px-5 py-3.5 bg-emerald-600 hover:bg-emerald-750 text-white rounded-xl font-bold shadow-md transition-all text-xs">Valider l'Encaissement</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- View Receipt Details Modal -->
        <div v-if="showViewModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 border border-slate-200 relative overflow-hidden animate-scale-up">
                <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-extrabold text-slate-800">Reçu de Loyer</h3>
                    <button @click="closeViewModal" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-all">
                        <svg class="w-4 h-4 text-slate-550" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Receipt Layout -->
                <div id="receipt-print-area" class="border border-slate-200 rounded-2xl p-6 bg-white font-sans text-xs text-slate-850 shadow-inner">
                    <div class="text-center border-b border-slate-100 pb-4 mb-4">
                        <div class="font-extrabold text-slate-900 text-sm">REÇU DE LOYER</div>
                        <div class="text-[10px] text-slate-400 mt-0.5">Réf transaction : {{ viewingPayment?.reference }}</div>
                    </div>

                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Émetteur :</span>
                            <span class="font-bold text-slate-800">Enterprise Property Corp</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Locataire :</span>
                            <span class="font-bold text-slate-800">{{ viewingPayment?.locataire }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Facture réglée :</span>
                            <span class="font-bold text-slate-800">{{ viewingPayment?.factureNumero || 'Paiement de loyer direct' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Date de transaction :</span>
                            <span class="font-semibold text-slate-700">{{ formatDate(viewingPayment?.date) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Mode de paiement :</span>
                            <span class="font-bold text-slate-800">{{ viewingPayment?.methode }}</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center bg-emerald-50 p-4 rounded-xl border border-emerald-100 text-emerald-800">
                        <span class="font-bold">MONTANT ENCAISSÉ</span>
                        <strong class="text-base font-extrabold">{{ formatCurrency(viewingPayment?.montant) }}</strong>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-between items-center mt-6">
                    <button @click="printReceipt" class="px-5 py-2.5 bg-slate-850 hover:bg-slate-900 text-white rounded-xl text-xs font-bold flex items-center gap-1.5 transition-all">
                        🖨️ Imprimer Reçu
                    </button>
                    <button @click="closeViewModal" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all">Fermer</button>
                </div>
            </div>
        </div>

        <!-- Success Toast -->
        <div v-if="showSuccess" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 flex items-center justify-center p-4">
            <div class="bg-gradient-to-br from-white via-white to-emerald-50/20 rounded-3xl shadow-2xl max-w-sm w-full p-8 border border-emerald-100/50 relative overflow-hidden animate-scale-up">
                <div class="relative z-10 text-center">
                    <div class="flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 mx-auto mb-5 shadow-lg shadow-emerald-500/30">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-extrabold text-slate-800 mb-1">Succès !</h3>
                    <p class="text-slate-500 text-sm mb-6">{{ successMessage }}</p>
                    <button @click="closeSuccess" class="w-full px-5 py-3.5 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-xl font-bold shadow-md shadow-emerald-500/20 hover:scale-[1.01] transition-all text-xs">Fermer</button>
                </div>
            </div>
        </div>

        <!-- Error Toast -->
        <div v-if="showError" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 flex items-center justify-center p-4">
            <div class="bg-gradient-to-br from-white via-white to-red-50/20 rounded-3xl shadow-2xl max-w-sm w-full p-8 border border-red-100/50 relative overflow-hidden animate-scale-up">
                <div class="relative z-10 text-center">
                    <div class="flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-red-500 to-rose-600 mx-auto mb-5 shadow-lg shadow-red-500/30">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-extrabold text-slate-800 mb-1">Erreur</h3>
                    <p class="text-slate-500 text-sm mb-6">{{ errorMessage }}</p>
                    <button @click="closeError" class="w-full px-5 py-3.5 bg-gradient-to-r from-red-500 to-rose-600 text-white rounded-xl font-bold shadow-md shadow-red-500/20 hover:scale-[1.01] transition-all text-xs">Fermer</button>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

const payments = ref([]);
const invoices = ref([]);
const contratsActifs = ref([]);

const searchQuery = ref('');
const methodFilter = ref('All');

const selectedLocataireName = ref('');
const selectedInvoiceId = ref('');
const showModal = ref(false);
const showViewModal = ref(false);
const showSuccess = ref(false);
const showError = ref(false);
const successMessage = ref('');
const errorMessage = ref('');
const viewingPayment = ref(null);

const formData = ref({
    reference: '',
    locataire: '',
    factureId: '',
    factureNumero: '',
    date: '',
    montant: 0,
    methode: 'Virement',
    referenceTx: ''
});

onMounted(() => {
    // 1. Load payments
    const storedPayments = localStorage.getItem('immobilier_paiements');
    if (storedPayments) {
        payments.value = JSON.parse(storedPayments);
    } else {
        payments.value = [
            { id: 1, reference: 'PAY-2026-001', locataire: 'Jean Dupont', factureId: 1, factureNumero: 'FAC-2026-001', date: '2026-05-02', montant: 1280, methode: 'Virement', referenceTx: 'TX-8973049' },
            { id: 2, reference: 'PAY-2026-002', locataire: 'Marie Lambert', factureId: 2, factureNumero: 'FAC-2026-002', date: '2026-05-05', montant: 1000, methode: 'Carte', referenceTx: 'TX-9284378' }
        ];
        localStorage.setItem('immobilier_paiements', JSON.stringify(payments.value));
    }

    // 2. Load invoices
    const storedInvoices = localStorage.getItem('immobilier_factures');
    if (storedInvoices) {
        invoices.value = JSON.parse(storedInvoices);
    }

    // 3. Load active contracts
    const storedContrats = localStorage.getItem('immobilier_contrats');
    if (storedContrats) {
        contratsActifs.value = JSON.parse(storedContrats).filter(c => c.statut === 'Actif');
    }
});

// Computed KPIs
const totalCollected = computed(() => payments.value.reduce((sum, p) => sum + p.montant, 0));
const totalTransactions = computed(() => payments.value.length);
const collectedThisMonth = computed(() => {
    const now = new Date();
    const currentMonthStr = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}`;
    return payments.value
        .filter(p => p.date.startsWith(currentMonthStr))
        .reduce((sum, p) => sum + p.montant, 0);
});
const topPaymentMethod = computed(() => {
    if (payments.value.length === 0) return 'Virement';
    const counts = {};
    payments.value.forEach(p => counts[p.methode] = (counts[p.methode] || 0) + 1);
    return Object.keys(counts).reduce((a, b) => counts[a] > counts[b] ? a : b);
});

const filteredPayments = computed(() => {
    let result = payments.value;

    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        result = result.filter(p => 
            p.locataire.toLowerCase().includes(query) ||
            p.reference.toLowerCase().includes(query)
        );
    }

    if (methodFilter.value !== 'All') {
        result = result.filter(p => p.methode === methodFilter.value);
    }

    return result;
});

// Filter invoices for selected locataire that are unpaid or partially paid
const unpaidInvoicesFiltered = computed(() => {
    if (!selectedLocataireName.value) return [];
    return invoices.value.filter(f => 
        f.locataire.toLowerCase() === selectedLocataireName.value.toLowerCase() && 
        f.statut !== 'Payé'
    );
});

const openAddModal = () => {
    selectedLocataireName.value = '';
    selectedInvoiceId.value = '';
    
    const now = new Date();
    formData.value = {
        reference: `PAY-${now.getFullYear()}-${String(payments.value.length + 1).padStart(3, '0')}`,
        locataire: '',
        factureId: '',
        factureNumero: '',
        date: now.toISOString().split('T')[0],
        montant: '',
        methode: 'Virement',
        referenceTx: ''
    };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const handleLocataireChange = () => {
    selectedInvoiceId.value = '';
    formData.value.locataire = selectedLocataireName.value;
};

const handleInvoiceChange = () => {
    const selected = invoices.value.find(f => f.id === selectedInvoiceId.value);
    if (selected) {
        formData.value.factureId = selected.id;
        formData.value.factureNumero = selected.numero;
        // Autofill remaining balance due
        formData.value.montant = selected.total - selected.montantPaye;
    }
};

const savePayment = () => {
    if (!formData.value.locataire || !formData.value.factureId || formData.value.montant <= 0) {
        errorMessage.value = "Veuillez sélectionner un locataire, une facture à solder et renseigner le montant perçu.";
        showError.value = true;
        return;
    }

    // Record the payment
    const newPayment = {
        ...formData.value,
        id: payments.value.length ? Math.max(...payments.value.map(p => p.id)) + 1 : 1
    };
    payments.value.unshift(newPayment);
    localStorage.setItem('immobilier_paiements', JSON.stringify(payments.value));

    // Update associated invoice status & payment amount
    const invoiceIndex = invoices.value.findIndex(f => f.id === formData.value.factureId);
    if (invoiceIndex !== -1) {
        const inv = invoices.value[invoiceIndex];
        inv.montantPaye = Number(inv.montantPaye) + Number(formData.value.montant);
        if (inv.montantPaye >= inv.total) {
            inv.statut = 'Payé';
        } else {
            inv.statut = 'Partiellement payé';
        }
        localStorage.setItem('immobilier_factures', JSON.stringify(invoices.value));
    }

    // Sync back to Real Estate Overview simulated actual monthly revenue (decrease unpaid total)
    const dashboardUnpaid = localStorage.getItem('kpi_simulated_unpaid_total');
    if (dashboardUnpaid) {
        const remainingUnpaid = Math.max(0, Number(dashboardUnpaid) - formData.value.montant);
        localStorage.setItem('kpi_simulated_unpaid_total', remainingUnpaid);
    }

    showModal.value = false;
    successMessage.value = "Règlement enregistré avec succès. Facture locative mise à jour.";
    showSuccess.value = true;
};

const viewReceipt = (pay) => {
    viewingPayment.value = pay;
    showViewModal.value = true;
};

const closeViewModal = () => {
    showViewModal.value = false;
    viewingPayment.value = null;
};

const deletePayment = (pay) => {
    payments.value = payments.value.filter(p => p.id !== pay.id);
    localStorage.setItem('immobilier_paiements', JSON.stringify(payments.value));

    // Optional: revert invoice paid amount
    const invoiceIndex = invoices.value.findIndex(f => f.id === pay.factureId);
    if (invoiceIndex !== -1) {
        const inv = invoices.value[invoiceIndex];
        inv.montantPaye = Math.max(0, Number(inv.montantPaye) - Number(pay.montant));
        if (inv.montantPaye === 0) {
            inv.statut = 'Impayé';
        } else if (inv.montantPaye < inv.total) {
            inv.statut = 'Partiellement payé';
        }
        localStorage.setItem('immobilier_factures', JSON.stringify(invoices.value));
    }

    successMessage.value = "Paiement supprimé avec succès.";
    showSuccess.value = true;
};

const printReceipt = () => {
    const printContent = document.getElementById('receipt-print-area').innerHTML;
    const w = window.open();
    w.document.write('<html><head><title>Reçu</title>');
    w.document.write('<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">');
    w.document.write('<style>body{font-family:"Inter",sans-serif;padding:40px;color:#334155;}</style>');
    w.document.write('</head><body>');
    w.document.write(printContent);
    w.document.write('</body></html>');
    w.document.close();
    w.focus();
    w.print();
    w.close();
};

const closeSuccess = () => {
    showSuccess.value = false;
};

const closeError = () => {
    showError.value = false;
};

// Formatting & visual helpers
const formatCurrency = (val) => {
    if (val === undefined || val === null || isNaN(val) || val === '') return '0 €';
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR', maximumFractionDigits: 0 }).format(val);
};

const formatDate = (dateStr) => {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return new Intl.DateTimeFormat('fr-FR', { day: '2-digit', month: '2-digit', year: 'numeric' }).format(date);
};

const formatPeriod = (periodStr) => {
    if (!periodStr) return '';
    const parts = periodStr.split('-');
    if (parts.length < 2) return periodStr;
    const months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
    const monthIdx = parseInt(parts[1], 10) - 1;
    return `${months[monthIdx]} ${parts[0]}`;
};

const getInitials = (name) => {
    if (!name) return 'PAY';
    const parts = name.trim().split(' ');
    if (parts.length >= 2) {
        return (parts[0][0] + parts[1][0]).toUpperCase();
    }
    return name.substring(0, 2).toUpperCase();
};

const getAvatarGradient = (name) => {
    if (!name) return 'from-emerald-500 to-teal-650';
    const colors = [
        'from-emerald-500 to-teal-600',
        'from-teal-500 to-cyan-600',
        'from-blue-500 to-indigo-600',
        'from-violet-500 to-purple-600',
        'from-amber-500 to-orange-600',
        'from-rose-500 to-red-650',
        'from-cyan-500 to-blue-600'
    ];
    let hash = 0;
    for (let i = 0; i < name.length; i++) {
        hash = name.charCodeAt(i) + ((hash << 5) - hash);
    }
    const index = Math.abs(hash) % colors.length;
    return colors[index];
};
</script>

<style scoped>
@keyframes scaleUp {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.animate-scale-up {
    animation: scaleUp 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
</style>
