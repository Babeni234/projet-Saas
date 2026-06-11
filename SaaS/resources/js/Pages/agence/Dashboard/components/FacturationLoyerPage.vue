<template>
    <div class="flex flex-col gap-8 select-none">
        
        <!-- Header with Luxury Dark Blue Banner -->
        <div class="bg-gradient-to-br from-slate-900 via-slate-950 to-slate-900 rounded-3xl p-8 border border-slate-800 shadow-2xl text-white relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-48 h-48 rounded-full bg-blue-500/10 blur-2xl pointer-events-none"></div>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative z-10">
                <div>
                    <span class="text-blue-400 text-xs font-bold tracking-widest uppercase bg-blue-500/20 px-3 py-1 rounded-full border border-blue-500/30">Comptabilité Locative</span>
                    <h1 class="text-3xl font-extrabold bg-gradient-to-r from-white via-slate-100 to-slate-300 bg-clip-text text-transparent mt-2">Facturation des Loyers</h1>
                    <p class="text-slate-400 text-sm mt-1">Émettez des factures mensuelles pour vos locataires actifs et suivez le recouvrement en temps réel.</p>
                </div>
                <button
                    @click="openAddModal"
                    class="px-5 py-3 rounded-2xl bg-blue-600 hover:bg-blue-500 border border-blue-500/50 hover:border-blue-400 text-white font-bold text-xs transition-all duration-300 transform hover:scale-[1.02] flex items-center gap-2 shadow-lg shadow-blue-600/35"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Générer une Facture
                </button>
            </div>
        </div>

        <!-- Premium KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-blue-200/30 transition-all duration-500 hover:-translate-y-1 border border-slate-150 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-blue-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 font-bold shadow-sm">€</div>
                </div>
                <div class="text-2xl font-extrabold text-slate-800 mb-1 tracking-tight">{{ formatCurrency(totalInvoiced) }}</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Facturé</div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-emerald-200/30 transition-all duration-500 hover:-translate-y-1 border border-slate-150 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-emerald-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 font-bold shadow-sm">✓</div>
                </div>
                <div class="text-2xl font-extrabold text-emerald-600 mb-1 tracking-tight">{{ formatCurrency(totalPaid) }}</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Montant Recouvré</div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-rose-200/30 transition-all duration-500 hover:-translate-y-1 border border-slate-150 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-rose-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 bg-rose-50 rounded-xl flex items-center justify-center text-rose-600 font-bold shadow-sm">!</div>
                </div>
                <div class="text-2xl font-extrabold text-rose-600 mb-1 tracking-tight">{{ formatCurrency(totalDue) }}</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Solde Dû / Impayés</div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-violet-200/30 transition-all duration-500 hover:-translate-y-1 border border-slate-150 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-violet-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 bg-violet-50 rounded-xl flex items-center justify-center text-violet-600 font-bold shadow-sm">%</div>
                </div>
                <div class="text-2xl font-extrabold text-violet-600 mb-1 tracking-tight">{{ recoveryRate }}%</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Taux de Recouvrement</div>
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
                    placeholder="Rechercher par locataire, facture, logement..."
                    class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-2xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-semibold transition-all shadow-sm"
                />
            </div>
            <div class="flex items-center gap-4 w-full md:w-auto">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold uppercase text-slate-400">Période :</span>
                    <input type="month" v-model="periodFilter" class="bg-white border border-slate-200 rounded-2xl px-4 py-2 text-xs font-bold text-slate-700 focus:outline-none shadow-sm" />
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold uppercase text-slate-400">Statut :</span>
                    <select v-model="statusFilter" class="bg-white border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:outline-none shadow-sm">
                        <option value="All">Tous</option>
                        <option value="Payé">Payé</option>
                        <option value="Partiellement payé">Partiel</option>
                        <option value="Impayé">Impayé</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-gradient-to-br from-white to-slate-50 rounded-3xl shadow-xl shadow-slate-200/50 overflow-hidden border border-slate-150">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200/50 bg-slate-50/50">
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Facture</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Locataire</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Logement</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Période</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Reçu</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-150">
                        <tr v-for="inv in filteredInvoices" :key="inv.id" class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 text-sm font-bold text-slate-800">{{ inv.numero }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div :class="['w-9 h-9 rounded-xl flex items-center justify-center text-white font-extrabold text-xs shadow-sm bg-gradient-to-br', getAvatarGradient(inv.locataire)]">
                                        {{ getInitials(inv.locataire) }}
                                    </div>
                                    <div class="font-bold text-slate-800 text-sm">{{ inv.locataire }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 font-semibold">{{ inv.logement }} ({{ inv.batiment }})</td>
                            <td class="px-6 py-4 text-sm text-slate-600 font-semibold">{{ formatPeriod(inv.periode) }}</td>
                            <td class="px-6 py-4 text-sm text-slate-800 font-extrabold">{{ formatCurrency(inv.total) }}</td>
                            <td class="px-6 py-4 text-sm text-emerald-600 font-bold">{{ formatCurrency(inv.montantPaye) }}</td>
                            <td class="px-6 py-4">
                                <span :class="[
                                    'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border',
                                    inv.statut === 'Payé' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' :
                                    inv.statut === 'Partiellement payé' ? 'bg-amber-50 text-amber-700 border-amber-200' :
                                    'bg-red-50 text-red-700 border-red-200'
                                ]">
                                    <span :class="['w-1.5 h-1.5 rounded-full',
                                        inv.statut === 'Payé' ? 'bg-emerald-500' :
                                        inv.statut === 'Partiellement payé' ? 'bg-amber-500 animate-pulse' :
                                        'bg-red-500 animate-pulse'
                                    ]"></span>
                                    {{ inv.statut }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <button @click="viewInvoice(inv)" class="p-2 text-slate-650 hover:bg-slate-100 rounded-xl transition-all" title="Aperçu / Imprimer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                    </button>
                                    <button @click="deleteInvoice(inv)" class="p-2 text-red-650 hover:bg-red-50 rounded-xl transition-all" title="Supprimer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="filteredInvoices.length === 0">
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400 font-semibold text-sm">
                                Aucune facture émise trouvée.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Modal (Generate Invoices) -->
        <div v-if="showModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 flex items-center justify-center p-4">
            <div class="bg-gradient-to-br from-white via-white to-blue-50/20 rounded-3xl shadow-2xl max-w-4xl w-full p-8 border border-blue-100/50 relative overflow-hidden animate-scale-up grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <div class="relative z-10 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-650 flex items-center justify-center shadow-lg shadow-blue-500/30">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h2 class="text-2xl font-extrabold bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent">Générer une Facture</h2>
                        </div>

                        <div class="space-y-4 max-h-[50vh] overflow-y-auto pr-1 scrollbar-thin">
                            <!-- Lease Selection -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Locataire Actif *</label>
                                <select 
                                    v-model="selectedContratNo" 
                                    @change="handleContratChange"
                                    class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-semibold text-slate-800 text-sm shadow-sm"
                                >
                                    <option value="" disabled>Sélectionner le locataire</option>
                                    <option v-for="c in contratsActifs" :key="c.id" :value="c.numero">
                                        {{ c.locataire }} (Bail {{ c.numero }} - Loyer: {{ c.loyer }}€)
                                    </option>
                                </select>
                            </div>

                            <!-- Invoice Type Selector -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Type de Facture *</label>
                                <div class="grid grid-cols-2 gap-2 bg-slate-100 p-1 rounded-xl">
                                    <button 
                                        type="button" 
                                        @click="typeFacture = 'loyer'" 
                                        :class="['py-2 text-xs font-bold rounded-lg transition-all', typeFacture === 'loyer' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-550 hover:text-slate-800']"
                                    >
                                        Facture Loyer
                                    </button>
                                    <button 
                                        type="button" 
                                        @click="typeFacture = 'libre'" 
                                        :class="['py-2 text-xs font-bold rounded-lg transition-all', typeFacture === 'libre' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-550 hover:text-slate-800']"
                                    >
                                        Facture libre / Motifs
                                    </button>
                                </div>
                            </div>

                            <!-- Rent Invoice months selection grid -->
                            <div class="group" v-if="typeFacture === 'loyer' && selectedContratNo">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Sélectionner les mois à facturer *</label>
                                <div class="grid grid-cols-3 gap-2">
                                    <label 
                                        v-for="month in contractMonths" 
                                        :key="month" 
                                        :class="[
                                            'flex flex-col items-center justify-center p-3 rounded-xl border-2 transition-all cursor-pointer select-none text-center',
                                            isMonthPaid(month) ? 'bg-slate-50 border-slate-100 text-slate-350 cursor-not-allowed opacity-60' :
                                            selectedMonths.includes(month) ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-slate-200 bg-white text-slate-700 hover:border-slate-350'
                                        ]"
                                    >
                                        <input 
                                            type="checkbox" 
                                            :value="month" 
                                            v-model="selectedMonths" 
                                            :disabled="isMonthPaid(month)"
                                            class="sr-only"
                                        />
                                        <span class="text-[11px] font-extrabold">{{ formatPeriod(month) }}</span>
                                        <span class="text-[9px] font-bold mt-1" :class="isMonthPaid(month) ? 'text-emerald-600' : selectedMonths.includes(month) ? 'text-blue-650' : 'text-slate-450'">
                                            {{ isMonthPaid(month) ? 'Déjà Payé' : 'Sélectionner' }}
                                        </span>
                                    </label>
                                </div>
                                <p v-if="contractMonths.length === 0" class="text-xs text-amber-600 font-semibold mt-1">Aucune période disponible.</p>
                            </div>

                            <!-- Libre Invoice fields -->
                            <div class="space-y-4" v-if="typeFacture === 'libre'">
                                <!-- Billing Month -->
                                <div class="group">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Période de Facturation (Mois) *</label>
                                    <input type="month" v-model="formData.periode" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-semibold text-slate-850 text-sm shadow-sm" />
                                </div>

                                <!-- Items listing -->
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Détails de facturation</span>
                                        <button @click="addInvoiceItem" type="button" class="text-xs font-bold text-blue-650 hover:underline">+ Ajouter une ligne</button>
                                    </div>
                                    <div class="space-y-2">
                                        <div v-for="(item, idx) in formData.items" :key="idx" class="flex gap-2 items-center bg-slate-50 p-2 rounded-xl border border-slate-200/50">
                                            <input type="text" v-model="item.libelle" placeholder="Libellé / Motif" class="flex-1 bg-white border border-slate-200 rounded-lg px-2 py-1.5 text-xs font-bold focus:outline-none" />
                                            <input type="number" v-model.number="item.montant" placeholder="Montant" class="w-24 bg-white border border-slate-200 rounded-lg px-2 py-1.5 text-xs font-bold focus:outline-none" />
                                            <button @click="removeInvoiceItem(idx)" type="button" class="text-red-500 font-bold px-2 hover:bg-slate-200 rounded-lg" :disabled="formData.items.length === 1">×</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footers -->
                    <div class="flex gap-4 mt-6 border-t border-slate-100 pt-5">
                        <button @click="closeModal" class="flex-1 px-5 py-3.5 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition-all text-xs">Annuler</button>
                        <button @click="saveInvoice" class="flex-1 px-5 py-3.5 bg-gradient-to-r from-blue-500 to-indigo-650 text-white rounded-xl font-bold shadow-lg shadow-blue-500/20 hover:shadow-xl hover:shadow-blue-500/35 transition-all text-xs">Générer la facture</button>
                    </div>
                </div>

                <!-- Live Printable Style Invoice Preview -->
                <div class="border-l border-slate-200 pl-8 hidden lg:flex flex-col justify-between h-[65vh]">
                    <div class="bg-white border-2 border-slate-100 shadow-lg rounded-2xl p-6 flex-1 flex flex-col justify-between font-sans text-xs text-slate-800 overflow-y-auto">
                        <div>
                            <!-- Header -->
                            <div class="flex justify-between items-start border-b border-slate-100 pb-4 mb-4">
                                <div>
                                    <div class="font-extrabold text-slate-900 text-sm">Enterprise Property Corp</div>
                                    <div class="text-[10px] text-slate-400">Gestion locative & Immobilière</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-extrabold text-blue-600">FACTURE</div>
                                    <div class="text-[10px] text-slate-500">{{ formData.numero || 'Draft' }}</div>
                                </div>
                            </div>

                            <!-- Parties details -->
                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div>
                                    <span class="block text-[9px] font-bold text-slate-400 uppercase">Destinataire</span>
                                    <strong class="text-slate-800 text-xs">{{ formData.locataire || 'Locataire non sélectionné' }}</strong>
                                    <span class="block text-slate-550 mt-1">Logement: {{ formData.logement || 'Aucun' }}</span>
                                    <span class="block text-slate-550">{{ formData.batiment || '' }}</span>
                                </div>
                                <div class="text-right">
                                    <span class="block text-[9px] font-bold text-slate-400 uppercase">Période</span>
                                    <strong class="text-slate-800 text-xs" v-if="typeFacture === 'libre'">{{ formatPeriod(formData.periode) }}</strong>
                                    <strong class="text-slate-800 text-xs" v-else-if="selectedMonths.length === 1">{{ formatPeriod(selectedMonths[0]) }}</strong>
                                    <strong class="text-slate-800 text-xs" v-else-if="selectedMonths.length > 1">Multiples ({{ selectedMonths.length }})</strong>
                                    <strong class="text-slate-800 text-xs" v-else>Aucun mois sélectionné</strong>
                                    <span class="block text-[9px] text-slate-400 mt-2">Date d'émission</span>
                                    <span>{{ getTodayDate() }}</span>
                                </div>
                            </div>

                            <!-- Table of items -->
                            <div class="border-b border-slate-100 mb-4">
                                <table class="w-full">
                                    <thead>
                                        <tr class="border-b border-slate-100 text-[9px] text-slate-400 font-bold">
                                            <th class="text-left pb-2">Description</th>
                                            <th class="text-right pb-2">Montant</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="typeFacture === 'libre'">
                                        <tr v-for="(item, idx) in formData.items" :key="idx" class="text-slate-700">
                                            <td class="py-2">{{ item.libelle || 'Ligne de frais' }}</td>
                                            <td class="py-2 text-right font-bold">{{ formatCurrency(item.montant) }}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else-if="typeFacture === 'loyer' && selectedMonths.length > 0">
                                        <tr class="text-slate-700">
                                            <td class="py-2">Loyer mensuel HC (par mois)</td>
                                            <td class="py-2 text-right font-bold">{{ formatCurrency(selectedContractLoyer) }}</td>
                                        </tr>
                                        <tr class="text-slate-700">
                                            <td class="py-2">Provisions de charges (par mois)</td>
                                            <td class="py-2 text-right font-bold">{{ formatCurrency(75) }}</td>
                                        </tr>
                                        <tr class="text-[10px] text-blue-500 italic bg-blue-50/30 font-semibold" v-if="selectedMonths.length > 1">
                                            <td class="py-2 px-1" colspan="2">
                                                Génération par lot : {{ selectedMonths.length }} factures distinctes de {{ formatCurrency(selectedContractLoyer + 75) }} chacune.
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="2" class="py-4 text-center text-slate-400 italic">Veuillez cocher au moins un mois</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Total display -->
                        <div class="flex justify-between items-center border-t-2 border-slate-100 pt-4 bg-slate-50 p-3 rounded-xl">
                            <span class="font-extrabold text-slate-900 text-sm">TOTAL À PAYER</span>
                            <span class="font-extrabold text-blue-600 text-base" v-if="typeFacture === 'libre'">{{ formatCurrency(invoiceTotalCalculated) }}</span>
                            <span class="font-extrabold text-blue-600 text-base" v-else>{{ formatCurrency((selectedContractLoyer + 75) * (selectedMonths.length || 1)) }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- View Invoice Details Modal -->
        <div v-if="showViewModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full p-8 border border-slate-200 relative overflow-hidden animate-scale-up">
                <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-extrabold text-slate-800">Détails de la facture</h3>
                    <button @click="closeViewModal" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-all">
                        <svg class="w-4 h-4 text-slate-550" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Printable invoice format -->
                <div id="invoice-print-area" class="border border-slate-200 rounded-2xl p-6 bg-white font-sans text-xs text-slate-850 shadow-inner">
                    <div class="flex justify-between items-start border-b border-slate-100 pb-4 mb-4">
                        <div>
                            <div class="font-extrabold text-slate-900 text-sm">Enterprise Property Corp</div>
                            <div class="text-[9px] text-slate-400">Services d'administration locative</div>
                        </div>
                        <div class="text-right">
                            <div class="font-extrabold text-blue-600 text-sm">FACTURE ENREGISTRÉE</div>
                            <div class="text-xs font-bold text-slate-800 mt-1">{{ viewingInvoice?.numero }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <span class="block text-[9px] font-bold text-slate-450 uppercase mb-0.5">Locataire</span>
                            <strong class="text-slate-800 text-xs">{{ viewingInvoice?.locataire }}</strong>
                            <span class="block text-slate-500 mt-1">Logement: {{ viewingInvoice?.logement }}</span>
                            <span class="block text-slate-500">{{ viewingInvoice?.batiment }}</span>
                        </div>
                        <div class="text-right">
                            <span class="block text-[9px] font-bold text-slate-450 uppercase mb-0.5">Période</span>
                            <strong class="text-slate-800">{{ formatPeriod(viewingInvoice?.periode) }}</strong>
                            <span class="block text-[9px] text-slate-400 mt-3">Statut actuel</span>
                            <span :class="[
                                'inline-flex px-2 py-0.5 rounded text-[10px] font-bold mt-1 uppercase',
                                viewingInvoice?.statut === 'Payé' ? 'bg-emerald-100 text-emerald-800' :
                                viewingInvoice?.statut === 'Partiellement payé' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800'
                            ]">{{ viewingInvoice?.statut }}</span>
                        </div>
                    </div>

                    <table class="w-full mb-6">
                        <thead>
                            <tr class="border-b border-slate-100 text-[9px] text-slate-400 font-bold text-left">
                                <th class="pb-2">Description</th>
                                <th class="text-right pb-2">Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, idx) in viewingInvoice?.items" :key="idx" class="border-b border-slate-50/50">
                                <td class="py-2 text-slate-700 font-medium">{{ item.libelle }}</td>
                                <td class="py-2 text-right font-bold text-slate-800">{{ formatCurrency(item.montant) }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="flex justify-between items-center bg-slate-50 p-4 rounded-xl">
                        <div>
                            <span class="block text-[9px] text-slate-400 uppercase">Déjà réglé</span>
                            <strong class="text-emerald-600">{{ formatCurrency(viewingInvoice?.montantPaye) }}</strong>
                        </div>
                        <div class="text-right">
                            <span class="block text-[9px] text-slate-400 uppercase">Total Facturé</span>
                            <strong class="text-blue-600 text-sm font-extrabold">{{ formatCurrency(viewingInvoice?.total) }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Print simulator button -->
                <div class="flex justify-between items-center mt-6">
                    <button @click="printInvoice" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold flex items-center gap-1.5 transition-all">
                        🖨️ Imprimer la Facture
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
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const currentAgencyId = computed(() => page.props.auth?.user?.employee?.agency_id);

const systemBatiments = computed(() => {
    const stored = localStorage.getItem('immobilier_batiments');
    let bats = [];
    if (stored) {
        bats = JSON.parse(stored);
    } else {
        bats = [
            { id: 1, nom: 'Immeuble A', ville: 'Paris' },
            { id: 2, nom: 'Immeuble B', ville: 'Lyon' },
            { id: 3, nom: 'Immeuble C', ville: 'Nice' },
            { id: 4, nom: 'Immeuble D', ville: 'Douala' }
        ];
    }
    const agencyId = currentAgencyId.value;
    return bats.filter(b => Number(b.agency_id) === Number(agencyId));
});

const buildingsList = computed(() => systemBatiments.value.map(b => b.nom));

const invoices = ref([]);
const rawContratsActifs = ref([]);

const contratsActifs = computed(() => {
    const agencyBuildingNames = buildingsList.value;
    return rawContratsActifs.value.filter(c => agencyBuildingNames.includes(c.batiment));
});

const scopedInvoices = computed(() => {
    const agencyBuildingNames = buildingsList.value;
    return invoices.value.filter(i => agencyBuildingNames.includes(i.batiment));
});

const searchQuery = ref('');
const statusFilter = ref('All');
const periodFilter = ref('');

const selectedContratNo = ref('');
const showModal = ref(false);
const showViewModal = ref(false);
const showSuccess = ref(false);
const showError = ref(false);
const successMessage = ref('');
const errorMessage = ref('');
const viewingInvoice = ref(null);

const typeFacture = ref('loyer'); // 'loyer' or 'libre'
const contractMonths = ref([]);
const selectedMonths = ref([]);

const formData = ref({
    numero: '',
    locataire: '',
    logement: '',
    batiment: '',
    periode: '',
    items: [],
    total: 0,
    montantPaye: 0,
    statut: 'Impayé'
});

onMounted(() => {
    // 1. Load invoices
    const storedInvoices = localStorage.getItem('immobilier_factures');
    if (storedInvoices) {
        invoices.value = JSON.parse(storedInvoices);
    } else {
        // Mock default invoices
        invoices.value = [
            { id: 1, numero: 'FAC-2026-001', locataire: 'Jean Dupont', logement: 'APT-A101', batiment: 'Immeuble A', periode: '2026-05', items: [{ libelle: 'Loyer mensuel HC', montant: 1200 }, { libelle: 'Provisions de charges', montant: 80 }], total: 1280, montantPaye: 1280, statut: 'Payé' },
            { id: 2, numero: 'FAC-2026-002', locataire: 'Marie Lambert', logement: 'APT-A201', batiment: 'Immeuble A', periode: '2026-05', items: [{ libelle: 'Loyer commercial HC', montant: 2500 }, { libelle: 'Charges syndic', montant: 150 }], total: 2650, montantPaye: 1000, statut: 'Partiellement payé' },
            { id: 3, numero: 'FAC-2026-003', locataire: 'Pierre Martin', logement: 'APT-B101', batiment: 'Immeuble B', periode: '2026-05', items: [{ libelle: 'Loyer mensuel HC', montant: 950 }], total: 950, montantPaye: 0, statut: 'Impayé' }
        ];
        localStorage.setItem('immobilier_factures', JSON.stringify(invoices.value));
    }

    // 2. Load active contracts
    const storedContrats = localStorage.getItem('immobilier_contrats');
    if (storedContrats) {
        rawContratsActifs.value = JSON.parse(storedContrats).filter(c => c.statut === 'Actif');
    }
});

// Computed KPIs
const totalInvoiced = computed(() => scopedInvoices.value.reduce((sum, i) => sum + i.total, 0));
const totalPaid = computed(() => scopedInvoices.value.reduce((sum, i) => sum + i.montantPaye, 0));
const totalDue = computed(() => totalInvoiced.value - totalPaid.value);
const recoveryRate = computed(() => {
    if (!totalInvoiced.value) return 0;
    return Math.round((totalPaid.value / totalInvoiced.value) * 100);
});

const filteredInvoices = computed(() => {
    let result = scopedInvoices.value;

    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        result = result.filter(i => 
            i.locataire.toLowerCase().includes(query) ||
            i.numero.toLowerCase().includes(query) ||
            i.logement.toLowerCase().includes(query)
        );
    }

    if (periodFilter.value) {
        result = result.filter(i => i.periode === periodFilter.value);
    }

    if (statusFilter.value !== 'All') {
        result = result.filter(i => i.statut === statusFilter.value);
    }

    return result;
});

const invoiceTotalCalculated = computed(() => {
    return formData.value.items.reduce((sum, item) => sum + Number(item.montant || 0), 0);
});

const selectedContractLoyer = computed(() => {
    const selected = contratsActifs.value.find(c => c.numero === selectedContratNo.value);
    return selected ? Number(selected.loyer) : 0;
});

const getMonthsBetweenDates = (startDateStr, endDateStr) => {
    if (!startDateStr || !endDateStr) return [];
    
    const startParts = startDateStr.split('-');
    const endParts = endDateStr.split('-');
    
    const startYear = parseInt(startParts[0], 10);
    const startMonth = parseInt(startParts[1], 10) - 1;
    
    const endYear = parseInt(endParts[0], 10);
    const endMonth = parseInt(endParts[1], 10) - 1;
    
    const months = [];
    let currentYear = startYear;
    let currentMonth = startMonth;
    
    while (currentYear < endYear || (currentYear === endYear && currentMonth <= endMonth)) {
        const yyyy = currentYear;
        const mm = String(currentMonth + 1).padStart(2, '0');
        months.push(`${yyyy}-${mm}`);
        
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
    }
    return months;
};

const isMonthPaid = (month) => {
    if (!formData.value.locataire) return false;
    return invoices.value.some(inv => 
        inv.locataire.toLowerCase() === formData.value.locataire.toLowerCase() && 
        inv.periode === month && 
        inv.statut === 'Payé'
    );
};

const openAddModal = () => {
    selectedContratNo.value = '';
    typeFacture.value = 'loyer';
    selectedMonths.value = [];
    contractMonths.value = [];
    
    // Default current month
    const now = new Date();
    const currentMonth = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}`;

    formData.value = {
        numero: `FAC-${now.getFullYear()}-${String(invoices.value.length + 1).padStart(3, '0')}`,
        locataire: '',
        logement: '',
        batiment: '',
        periode: currentMonth,
        items: [{ libelle: 'Loyer mensuel HC', montant: 0 }],
        total: 0,
        montantPaye: 0,
        statut: 'Impayé'
    };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const handleContratChange = () => {
    const selected = contratsActifs.value.find(c => c.numero === selectedContratNo.value);
    if (selected) {
        formData.value.locataire = selected.locataire;
        formData.value.logement = selected.reference;
        formData.value.batiment = selected.batiment;
        
        // Auto-populate default invoice items
        formData.value.items = [
            { libelle: 'Loyer mensuel HC', montant: selected.loyer },
            { libelle: 'Provisions de charges', montant: 75 }
        ];
        
        if (selected.debut && selected.fin) {
            contractMonths.value = getMonthsBetweenDates(selected.debut, selected.fin);
        } else {
            contractMonths.value = [];
        }
        selectedMonths.value = [];
    }
};

const addInvoiceItem = () => {
    formData.value.items.push({ libelle: '', montant: 0 });
};

const removeInvoiceItem = (idx) => {
    formData.value.items.splice(idx, 1);
};

const saveInvoice = () => {
    if (!formData.value.locataire) {
        errorMessage.value = "Veuillez sélectionner un locataire.";
        showError.value = true;
        return;
    }

    if (typeFacture.value === 'libre') {
        if (!formData.value.periode || invoiceTotalCalculated.value <= 0) {
            errorMessage.value = "Veuillez renseigner la période et les lignes de frais.";
            showError.value = true;
            return;
        }

        formData.value.total = invoiceTotalCalculated.value;
        invoices.value.unshift({
            ...formData.value,
            id: invoices.value.length ? Math.max(...invoices.value.map(i => i.id)) + 1 : 1
        });
        
        const dashboardKpi = localStorage.getItem('kpi_simulated_monthly_revenue');
        if (dashboardKpi) {
            localStorage.setItem('kpi_simulated_monthly_revenue', Number(dashboardKpi) + formData.value.total);
        }
    } else {
        // Loyer invoice type
        if (selectedMonths.value.length === 0) {
            errorMessage.value = "Veuillez cocher au moins un mois à facturer.";
            showError.value = true;
            return;
        }

        const selectedContract = contratsActifs.value.find(c => c.numero === selectedContratNo.value);
        if (!selectedContract) return;

        let startLength = invoices.value.length;
        let startMaxId = invoices.value.length ? Math.max(...invoices.value.map(i => i.id)) : 0;
        let totalAdded = 0;

        selectedMonths.value.forEach((m, idx) => {
            const nextId = startMaxId + idx + 1;
            const invoiceNo = `FAC-${new Date().getFullYear()}-${String(startLength + idx + 1).padStart(3, '0')}`;
            
            const newInv = {
                id: nextId,
                numero: invoiceNo,
                locataire: formData.value.locataire,
                logement: formData.value.logement,
                batiment: formData.value.batiment,
                periode: m,
                items: [
                    { libelle: 'Loyer mensuel HC', montant: selectedContract.loyer },
                    { libelle: 'Provisions de charges', montant: 75 }
                ],
                total: selectedContract.loyer + 75,
                montantPaye: 0,
                statut: 'Impayé'
            };
            invoices.value.unshift(newInv);
            totalAdded += newInv.total;
        });

        const dashboardKpi = localStorage.getItem('kpi_simulated_monthly_revenue');
        if (dashboardKpi) {
            localStorage.setItem('kpi_simulated_monthly_revenue', Number(dashboardKpi) + totalAdded);
        }
    }

    localStorage.setItem('immobilier_factures', JSON.stringify(invoices.value));
    showModal.value = false;
    successMessage.value = "Facture(s) locative(s) générée(s) avec succès.";
    showSuccess.value = true;
};

const viewInvoice = (inv) => {
    viewingInvoice.value = inv;
    showViewModal.value = true;
};

const closeViewModal = () => {
    showViewModal.value = false;
    viewingInvoice.value = null;
};

const deleteInvoice = (inv) => {
    invoices.value = invoices.value.filter(i => i.id !== inv.id);
    localStorage.setItem('immobilier_factures', JSON.stringify(invoices.value));
    successMessage.value = "Facture supprimée avec succès.";
    showSuccess.value = true;
};

const printInvoice = () => {
    const printContent = document.getElementById('invoice-print-area').innerHTML;
    const originalContent = document.body.innerHTML;

    // Simulate print window popup or style override
    const w = window.open();
    w.document.write('<html><head><title>Facture</title>');
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

// Formatting helpers
const formatCurrency = (val) => {
    if (val === undefined || val === null || isNaN(val) || val === '') return '0 €';
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR', maximumFractionDigits: 0 }).format(val);
};

const formatPeriod = (periodStr) => {
    if (!periodStr) return '';
    const parts = periodStr.split('-');
    if (parts.length < 2) return periodStr;
    const months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
    const monthIdx = parseInt(parts[1], 10) - 1;
    return `${months[monthIdx]} ${parts[0]}`;
};

const getTodayDate = () => {
    return new Intl.DateTimeFormat('fr-FR', { day: '2-digit', month: '2-digit', year: 'numeric' }).format(new Date());
};

const getInitials = (name) => {
    if (!name) return 'FAC';
    const parts = name.trim().split(' ');
    if (parts.length >= 2) {
        return (parts[0][0] + parts[1][0]).toUpperCase();
    }
    return name.substring(0, 2).toUpperCase();
};

const getAvatarGradient = (name) => {
    if (!name) return 'from-blue-500 to-indigo-650';
    const colors = [
        'from-blue-550 to-indigo-600',
        'from-emerald-500 to-teal-600',
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
