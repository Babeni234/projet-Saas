<template>
    <div class="flex flex-col gap-6" :class="{ 'fixed inset-0 z-50 bg-white p-6 overflow-y-auto': isFullScreenForm }">
        <!-- Header with Luxury Teal Banner -->
        <div class="bg-gradient-to-br from-slate-900 via-slate-950 to-slate-900 rounded-3xl p-8 border border-slate-800 shadow-2xl text-white relative overflow-hidden" v-if="!isFullScreenForm">
            <div class="absolute -right-10 -top-10 w-48 h-48 rounded-full bg-teal-500/10 blur-2xl pointer-events-none"></div>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative z-10">
                <div>
                    <span class="text-teal-400 text-xs font-bold tracking-widest uppercase bg-teal-500/20 px-3 py-1 rounded-full border border-teal-500/30">Espace Agence</span>
                    <h1 class="text-3xl font-extrabold bg-gradient-to-r from-white via-slate-100 to-slate-300 bg-clip-text text-transparent mt-2">Renouvellements de Baux</h1>
                    <p class="text-slate-400 text-sm mt-1">Gérez les renouvellements de baux de votre agence, ajustez les loyers et générez de nouveaux contrats.</p>
                </div>
                <button
                    @click="openAddModal"
                    class="px-5 py-3 rounded-2xl bg-teal-600 hover:bg-teal-500 border border-teal-500/50 hover:border-teal-400 text-white font-bold text-xs transition-all duration-300 transform hover:scale-[1.02] flex items-center gap-2 shadow-lg shadow-teal-600/35"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nouveau Renouvellement
                </button>
            </div>
        </div>

        <!-- Premium KPI Cards (5 Columns Grid) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4" v-if="!isFullScreenForm">
            <div class="bg-white rounded-3xl p-5 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-teal-200/30 transition-all duration-500 border border-slate-150 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-teal-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-teal-500/35">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-extrabold text-slate-800 tracking-tight">{{ totalRenouvellements }}</div>
                <div class="text-[10px] font-bold text-slate-450 uppercase tracking-wider">Total Demandes</div>
            </div>

            <div class="bg-white rounded-3xl p-5 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-amber-200/30 transition-all duration-500 border border-slate-150 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-amber-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/35">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-extrabold text-amber-600 tracking-tight">{{ enAttenteCount }}</div>
                <div class="text-[10px] font-bold text-slate-450 uppercase tracking-wider">En Attente</div>
            </div>

            <div class="bg-white rounded-3xl p-5 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-blue-200/30 transition-all duration-500 border border-slate-150 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-blue-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/35">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-extrabold text-blue-600 tracking-tight">{{ aVenirCount }}</div>
                <div class="text-[10px] font-bold text-slate-450 uppercase tracking-wider">À Venir</div>
            </div>

            <div class="bg-white rounded-3xl p-5 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-emerald-200/30 transition-all duration-500 border border-slate-150 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-emerald-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/35">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-extrabold text-emerald-600 tracking-tight">{{ completeCount }}</div>
                <div class="text-[10px] font-bold text-slate-450 uppercase tracking-wider">Complétés</div>
            </div>

            <!-- Rejected Count Card -->
            <div class="bg-white rounded-3xl p-5 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-rose-200/30 transition-all duration-500 border border-slate-150 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-rose-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-rose-500 to-red-650 rounded-xl flex items-center justify-center shadow-lg shadow-rose-500/35">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-extrabold text-rose-600 tracking-tight">{{ rejectedCount }}</div>
                <div class="text-[10px] font-bold text-slate-450 uppercase tracking-wider">Demandes Rejetées</div>
            </div>
        </div>

        <!-- Filters & Table -->
        <template v-if="!isFullScreenForm && !showModal">
            <!-- Filters -->
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-5 border border-slate-100 flex flex-col md:flex-row gap-4 items-center justify-between">
                <div class="relative w-full md:w-96">
                    <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Rechercher par locataire..."
                        class="w-full pl-12 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                    >
                </div>
                <div class="flex flex-wrap gap-3 w-full md:w-auto">
                    <select 
                        v-model="filterStatus"
                        class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                    >
                        <option value="">Tous les statuts</option>
                        <option value="En attente">En attente</option>
                        <option value="A venir">A venir</option>
                        <option value="Complete">Complete</option>
                        <option value="Rejeté">Rejeté</option>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 overflow-hidden border border-slate-100">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50">
                                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Locataire</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Contrat Ref / Logement</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Nouvelles Conditions</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Date Demande</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Statut</th>
                                <th class="px-6 py-4 text-right text-sm font-semibold text-slate-900">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="r in filteredRenouvellements" :key="r.id" class="border-b border-slate-100 hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4">
                                    <span class="font-bold text-slate-900 block">{{ r.locataire?.nom || 'Inconnu' }}</span>
                                    <span class="text-xs text-slate-500">{{ r.locataire?.user?.email }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="block text-slate-900 font-semibold">Contrat #{{ r.contrat?.numero || r.contrat?.id }}</span>
                                    <span class="text-xs text-slate-500">Logement: {{ r.contrat?.logement?.reference || 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="block text-slate-900 font-bold">{{ formatCurrency(r.nouveau_loyer) }} / {{ r.cycle_paiement }}</span>
                                    <span class="text-xs text-slate-500">Durée : {{ r.duree }}</span>
                                </td>
                                <td class="px-6 py-4 text-slate-600 text-sm">{{ formatDate(r.created_at) }}</td>
                                <td class="px-6 py-4">
                                    <span :class="getStatusClass(r.statut)">
                                        {{ r.statut }}
                                    </span>
                                    <!-- Rejection detail directly under status if rejected -->
                                    <span v-if="r.statut === 'Rejeté' && r.motif_rejet" class="block text-xs text-rose-600 mt-1 max-w-[200px] truncate" :title="r.motif_rejet">
                                        Motif: {{ r.motif_rejet }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Open Decision Popup -->
                                        <button v-if="r.statut === 'En attente'" @click="openDecisionModal(r)" class="px-3 py-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-lg text-xs font-bold transition shadow-sm">
                                            Traiter
                                        </button>
                                        
                                        <!-- Open Custom Contract Confirmation modal -->
                                        <button v-if="r.statut === 'A venir'" @click="openConfirmModal(r)" class="px-3 py-1.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 rounded-lg text-xs font-bold transition shadow-sm">
                                            Confirmer
                                        </button>

                                        <button @click="openViewModal(r)" class="p-2 text-slate-500 hover:bg-slate-100 rounded-lg transition" title="Voir les détails">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </button>

                                        <!-- Only allow delete if Rejected -->
                                        <button v-if="r.statut === 'Rejeté'" @click="destroy(r.id)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Supprimer">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="filteredRenouvellements.length === 0">
                                <td colspan="6" class="text-center py-12 text-slate-400">Aucun renouvellement trouvé.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </template>

        <!-- Form for New / Edit Renouvellement (Can be Full Screen) -->
        <div v-if="showModal || isFullScreenForm" :class="isFullScreenForm ? 'flex flex-col h-full animate-scale-up' : 'fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4'">
            <div :class="isFullScreenForm ? 'w-full h-full bg-white flex flex-col' : 'bg-white rounded-3xl shadow-2xl max-w-4xl w-full overflow-hidden flex flex-col max-h-[90vh] animate-scale-up'">
                
                <div class="flex items-center justify-between p-6 border-b border-slate-100 bg-slate-50/50">
                    <h2 class="text-2xl font-bold text-slate-900">Nouvelle Demande de Renouvellement</h2>
                    <div class="flex gap-2">
                        <button @click="toggleFullScreen" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Plein écran">
                            <svg v-if="!isFullScreenForm" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" /></svg>
                            <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                        <button v-if="!isFullScreenForm" @click="closeModal" class="text-slate-400 hover:text-slate-650 p-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="p-6 overflow-y-auto flex-1 space-y-6">
                    <!-- Agency Building-first Cascade -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Building (First in Agency) -->
                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Sélectionner le Bâtiment *</label>
                            <select 
                                v-model="formData.batiment_id" 
                                @change="onBatimentChange"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition font-bold"
                                style="color: #000000; background-color: #f8fafc;"
                            >
                                <option value="">Choisir un bâtiment...</option>
                                <option v-for="b in dbBatiments" :key="b.id" :value="b.id">{{ b.nom }}</option>
                            </select>
                        </div>

                        <!-- Tenant (Second in Agency) -->
                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm" v-if="formData.batiment_id">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Sélectionner le Locataire *</label>
                            <select 
                                v-model="formData.locataire_id" 
                                @change="onLocataireChange"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition font-bold"
                                style="color: #000000; background-color: #f8fafc;"
                            >
                                <option value="">Choisir un locataire...</option>
                                <option v-for="loc in filteredLocataires" :key="loc.id" :value="loc.id">{{ loc.nom }}</option>
                            </select>
                        </div>

                        <!-- Contract -->
                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm" v-if="formData.locataire_id">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Sélectionner le Contrat à Renouveler *</label>
                            <select 
                                v-model="formData.contrat_id" 
                                @change="onContratChange"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition font-bold"
                                style="color: #000000; background-color: #f8fafc;"
                            >
                                <option value="">Choisir un contrat...</option>
                                <option v-for="c in locataireContrats" :key="c.id" :value="c.id">
                                    {{ c.numero || 'Contrat #' + c.id }} - Loyer: {{ formatCurrency(c.loyer) }} - Statut: {{ c.statut }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Step 3: Nouveaux paramètres (Appeurs after contract selection) -->
                    <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100" v-if="formData.contrat_id">
                        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg>
                            Conditions de Renouvellement
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                            <div>
                                <label class="block text-xs font-semibold text-slate-655 mb-1">Nouveau Cycle de paiement</label>
                                <select v-model="formData.cycle_paiement" class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 font-bold" style="color: #000000;">
                                    <option value="Mensuel">Mensuel</option>
                                    <option value="Trimestriel">Trimestriel</option>
                                    <option value="Annuel">Annuel</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-655 mb-1">Nouveau Loyer (€)</label>
                                <input v-model.number="formData.nouveau_loyer" type="number" class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 font-bold" style="color: #000000;">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-655 mb-1">Durée du nouveau bail</label>
                                <select v-model="formData.duree" class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 font-bold" style="color: #000000;">
                                    <option value="1 an">1 an</option>
                                    <option value="2 ans">2 ans</option>
                                    <option value="3 ans">3 ans</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-655 mb-1">Frais de contrat (€)</label>
                                <input v-model.number="formData.frais_contrat" type="number" class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 font-bold" style="color: #000000;">
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Rédaction de la demande -->
                    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm flex-1 flex flex-col" v-if="formData.contrat_id">
                        <div class="bg-slate-50 px-5 py-3 border-b border-slate-200">
                            <h3 class="text-sm font-bold text-slate-800">Rédiger votre demande</h3>
                        </div>
                        <textarea 
                            v-model="formData.motif_demande" 
                            class="w-full flex-1 p-5 focus:outline-none resize-none min-h-[150px] text-sm" 
                            placeholder="Saisissez les termes et justifications de cette demande..."
                        ></textarea>
                    </div>

                </div>

                <div class="p-6 border-t border-slate-100 bg-slate-50 flex justify-end gap-3" v-if="formData.contrat_id">
                    <button @click="closeModal" class="px-6 py-3 border border-slate-300 text-slate-700 font-semibold rounded-xl hover:bg-white transition">Annuler</button>
                    <button @click="saveDemande" :disabled="saving" class="px-8 py-3 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 shadow-lg shadow-emerald-500/30 transition disabled:opacity-50">
                        {{ saving ? 'Enregistrement...' : 'Valider la demande' }}
                    </button>
                </div>
            </div>
        </div>

        <!-- DECISION POPUP (Approuver / Rejeter) -->
        <div v-if="showDecisionModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full overflow-hidden flex flex-col max-h-[90vh] animate-scale-up border border-slate-100">
                <div class="flex items-center justify-between p-6 border-b border-slate-100 bg-slate-50">
                    <h3 class="text-xl font-bold text-slate-900">Traiter la demande de renouvellement</h3>
                    <button @click="showDecisionModal = false" class="text-slate-400 hover:text-slate-650">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="p-6 overflow-y-auto space-y-6">
                    <div class="bg-slate-50 p-4 rounded-xl space-y-2 text-sm text-slate-700 border border-slate-150">
                        <p><strong>Locataire :</strong> {{ selectedRenewal?.locataire?.nom }}</p>
                        <p><strong>Logement :</strong> {{ selectedRenewal?.contrat?.logement?.reference }}</p>
                        <p><strong>Conditions proposées :</strong> {{ formatCurrency(selectedRenewal?.nouveau_loyer) }} / {{ selectedRenewal?.cycle_paiement }} (Durée: {{ selectedRenewal?.duree }})</p>
                        <p><strong>Frais de contrat prévus :</strong> {{ formatCurrency(selectedRenewal?.frais_contrat) }}</p>
                        <p v-if="selectedRenewal?.motif_demande"><strong>Description de la demande :</strong> {{ selectedRenewal?.motif_demande }}</p>
                    </div>

                    <!-- AI Rejection workflow section inside modal -->
                    <div v-if="isRejectionMode" class="space-y-4">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-bold text-rose-600 uppercase tracking-widest">Motif de rejet rédigé par l'IA</label>
                            <span v-if="isGeneratingMotif" class="text-xs text-slate-500 animate-pulse flex items-center gap-1">
                                <span class="animate-spin h-3.5 w-3.5 border-2 border-teal-500 border-t-transparent rounded-full"></span>
                                Rédaction en cours...
                            </span>
                        </div>
                        <textarea 
                            v-model="adjustedRejectionMotif" 
                            class="w-full p-4 border border-rose-200 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-transparent text-sm min-h-[120px]"
                            placeholder="Le motif du refus apparaîtra ici..."
                        ></textarea>
                    </div>
                </div>
                <div class="p-6 border-t border-slate-100 bg-slate-50 flex justify-end gap-3">
                    <template v-if="!isRejectionMode">
                        <button @click="showDecisionModal = false" class="px-5 py-2.5 border border-slate-300 text-slate-700 rounded-lg text-xs font-bold hover:bg-white transition">Annuler</button>
                        <button @click="toggleRejectionMode" class="px-5 py-2.5 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-lg text-xs font-bold transition">Rejeter la demande</button>
                        <button @click="confirmApproval" class="px-6 py-2.5 bg-emerald-600 text-white rounded-lg text-xs font-bold hover:bg-emerald-700 shadow shadow-emerald-500/25 transition">Approuver la demande</button>
                    </template>
                    <template v-else>
                        <button @click="isRejectionMode = false" class="px-5 py-2.5 border border-slate-300 text-slate-700 rounded-lg text-xs font-bold hover:bg-white transition">Retour</button>
                        <button @click="confirmRejection" :disabled="isGeneratingMotif || !adjustedRejectionMotif" class="px-6 py-2.5 bg-rose-600 text-white rounded-lg text-xs font-bold hover:bg-rose-700 shadow shadow-rose-500/25 transition disabled:opacity-50">Confirmer le rejet & notifier</button>
                    </template>
                </div>
            </div>
        </div>

        <!-- CONFIRMATION POPUP (Rédiger le contrat + finaliser) -->
        <div v-if="showConfirmModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-4xl w-full overflow-hidden flex flex-col max-h-[90vh] animate-scale-up border border-slate-100">
                <div class="flex items-center justify-between p-6 border-b border-slate-100 bg-slate-50">
                    <h3 class="text-xl font-bold text-slate-900">Confirmer et finaliser le renouvellement</h3>
                    <button @click="showConfirmModal = false" class="text-slate-400 hover:text-slate-650">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="p-6 overflow-y-auto flex-1 space-y-6">
                    <div class="bg-slate-50 p-4 rounded-xl space-y-2 text-sm text-slate-700 border border-slate-150">
                        <p><strong>Locataire :</strong> {{ selectedRenewal?.locataire?.nom }}</p>
                        <p><strong>Nouveau loyer :</strong> {{ formatCurrency(selectedRenewal?.nouveau_loyer) }} (Cycle: {{ selectedRenewal?.cycle_paiement }}, Durée: {{ selectedRenewal?.duree }})</p>
                        <p><strong>Frais de contrat perçus :</strong> {{ formatCurrency(selectedRenewal?.frais_contrat) }}</p>
                    </div>

                    <!-- Contract redaction block -->
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-bold text-slate-800">Contrat de bail</span>
                            <button 
                                v-if="!isEditingContract" 
                                @click="loadOldContract" 
                                class="px-4 py-2 bg-teal-50 text-teal-650 border border-teal-200 rounded-lg text-xs font-bold hover:bg-teal-100/50 transition flex items-center gap-1"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                Rédiger le contrat
                            </button>
                        </div>

                        <!-- Editable Rich Text Style text area -->
                        <div v-if="isEditingContract" class="flex flex-col border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                            <div class="bg-slate-50 px-4 py-2 border-b border-slate-200 text-xs font-semibold text-slate-550">
                                Éditeur de contrat de bail (Format HTML accepté)
                            </div>
                            <textarea 
                                v-model="customContractContent" 
                                class="w-full p-4 focus:outline-none min-h-[300px] font-mono text-sm leading-relaxed"
                            ></textarea>
                        </div>
                    </div>
                </div>
                <div class="p-6 border-t border-slate-100 bg-slate-50 flex justify-end gap-3">
                    <button @click="showConfirmModal = false" class="px-5 py-2.5 border border-slate-300 text-slate-700 rounded-lg text-xs font-bold hover:bg-white transition">Annuler</button>
                    <button @click="confirmCompletion" :disabled="!customContractContent" class="px-6 py-2.5 bg-emerald-600 text-white rounded-lg text-xs font-bold hover:bg-emerald-700 shadow shadow-emerald-500/25 transition disabled:opacity-50">Valider et finaliser</button>
                </div>
            </div>
        </div>

        <!-- OCCUPIED WARNING MODAL -->
        <div v-if="showOccupiedWarning" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[60] flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-6 text-center animate-scale-up border border-slate-100">
                <div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-4 border border-rose-100">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Attention : Logement déjà occupé</h3>
                <p class="text-sm text-slate-500 mb-6 leading-relaxed">
                    Ce logement est actuellement occupé par un autre locataire avec un bail actif. Veuillez vous assurer que le bail de l'autre locataire est résilié ou expiré avant de finaliser ce renouvellement.
                </p>
                <button @click="showOccupiedWarning = false" class="w-full py-3 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-xl transition">
                    Compris
                </button>
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

const csrf = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

// State
const renouvellements = ref([]);
const dbLocataires = ref([]);
const dbContrats = ref([]);
const dbBatiments = ref([]);

const searchQuery = ref('');
const filterStatus = ref('');

const showModal = ref(false);
const isFullScreenForm = ref(false);
const saving = ref(false);

const formData = ref({
    batiment_id: '',
    locataire_id: '',
    contrat_id: '',
    cycle_paiement: 'Mensuel',
    nouveau_loyer: 0,
    duree: '1 an',
    frais_contrat: 0,
    motif_demande: ''
});

// Workflow Modals State
const showDecisionModal = ref(false);
const selectedRenewal = ref(null);
const isRejectionMode = ref(false);
const isGeneratingMotif = ref(false);
const adjustedRejectionMotif = ref('');

const showConfirmModal = ref(false);
const isEditingContract = ref(false);
const customContractContent = ref('');
const showOccupiedWarning = ref(false);

// Fetch Init
const fetchData = async () => {
    try {
        const resR = await fetch('/api/renouvellements', { headers: { 'Accept': 'application/json' } });
        if(resR.ok) renouvellements.value = await resR.json();

        const resL = await fetch('/api/locataires', { headers: { 'Accept': 'application/json' } });
        if(resL.ok) dbLocataires.value = await resL.json();

        const resC = await fetch('/api/contrats', { headers: { 'Accept': 'application/json' } });
        if(resC.ok) dbContrats.value = await resC.json();

        const resB = await fetch('/api/batiments', { headers: { 'Accept': 'application/json' } });
        if(resB.ok) dbBatiments.value = await resB.json();
    } catch (e) {
        console.error(e);
    }
};

onMounted(fetchData);

// Computeds
const totalRenouvellements = computed(() => renouvellements.value.length);
const enAttenteCount = computed(() => renouvellements.value.filter(r => r.statut === 'En attente').length);
const aVenirCount = computed(() => renouvellements.value.filter(r => r.statut === 'A venir').length);
const completeCount = computed(() => renouvellements.value.filter(r => r.statut === 'Complete').length);
const rejectedCount = computed(() => renouvellements.value.filter(r => r.statut === 'Rejeté').length);

const filteredRenouvellements = computed(() => {
    let list = renouvellements.value;
    if (filterStatus.value) {
        list = list.filter(r => r.statut === filterStatus.value);
    }
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        list = list.filter(r => 
            (r.locataire?.nom && r.locataire.nom.toLowerCase().includes(query)) ||
            (r.agency?.nom && r.agency.nom.toLowerCase().includes(query))
        );
    }
    return list;
});

const filteredLocataires = computed(() => {
    if (!formData.value.batiment_id) return [];
    return dbLocataires.value.filter(loc => loc.batiment_id === Number(formData.value.batiment_id));
});

const locataireContrats = computed(() => {
    if (!formData.value.locataire_id) return [];
    return dbContrats.value.filter(c => c.locataire_id === Number(formData.value.locataire_id));
});

// Actions
const onBatimentChange = () => {
    formData.value.locataire_id = '';
    formData.value.contrat_id = '';
    resetFinancials();
};

const onLocataireChange = () => {
    formData.value.contrat_id = '';
    resetFinancials();
};

const onContratChange = () => {
    const contrat = dbContrats.value.find(c => c.id === Number(formData.value.contrat_id));
    if (contrat) {
        formData.value.nouveau_loyer = Number(contrat.loyer || 0);
        formData.value.cycle_paiement = contrat.cycle_paiement || 'Mensuel';
        formData.value.duree = contrat.duree || '1 an';
        
        // Alert if occupied by another active lease
        const isOccupied = dbContrats.value.some(c => 
            c.logement_id === contrat.logement_id && 
            c.statut === 'Actif' && 
            c.locataire_id !== contrat.locataire_id
        );
        if (isOccupied) {
            showOccupiedWarning.value = true;
        }
    } else {
        resetFinancials();
    }
};

const resetFinancials = () => {
    formData.value.nouveau_loyer = 0;
    formData.value.frais_contrat = 0;
    formData.value.motif_demande = '';
};

const openAddModal = () => {
    formData.value = {
        batiment_id: '',
        locataire_id: '',
        contrat_id: '',
        cycle_paiement: 'Mensuel',
        nouveau_loyer: 0,
        duree: '1 an',
        frais_contrat: 0,
        motif_demande: ''
    };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    isFullScreenForm.value = false;
};

const toggleFullScreen = () => {
    isFullScreenForm.value = !isFullScreenForm.value;
    if (!isFullScreenForm.value && formData.value.locataire_id) {
        showModal.value = true;
    }
};

const saveDemande = async () => {
    saving.value = true;
    try {
        const res = await fetch('/api/renouvellements', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf()
            },
            body: JSON.stringify(formData.value)
        });

        if (res.ok) {
            await fetchData();
            closeModal();
        } else {
            alert("Erreur lors de l'enregistrement");
        }
    } catch(e) {
        console.error(e);
    } finally {
        saving.value = false;
    }
};

// Workflow Modals Handlers
const openDecisionModal = (r) => {
    selectedRenewal.value = r;
    isRejectionMode.value = false;
    adjustedRejectionMotif.value = '';
    showDecisionModal.value = true;
};

const toggleRejectionMode = async () => {
    isRejectionMode.value = true;
    isGeneratingMotif.value = true;
    try {
        const res = await fetch('/api/ai/generate-rejection-motif', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf()
            },
            body: JSON.stringify({
                locataire_name: selectedRenewal.value.locataire?.nom || 'Locataire',
                reference_logement: selectedRenewal.value.contrat?.logement?.reference || 'N/A',
                nouveau_loyer: selectedRenewal.value.nouveau_loyer,
                motif_demande: selectedRenewal.value.motif_demande
            })
        });
        if (res.ok) {
            const data = await res.json();
            adjustedRejectionMotif.value = data.motif || '';
        }
    } catch (e) {
        console.error(e);
    } finally {
        isGeneratingMotif.value = false;
    }
};

const confirmApproval = async () => {
    try {
        const res = await fetch(`/api/renouvellements/${selectedRenewal.value.id}/approuver`, {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf() }
        });
        if(res.ok) {
            await fetchData();
            showDecisionModal.value = false;
        }
    } catch(e) {
        console.error(e);
    }
};

const confirmRejection = async () => {
    try {
        const res = await fetch(`/api/renouvellements/${selectedRenewal.value.id}/rejeter`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf()
            },
            body: JSON.stringify({ motif_rejet: adjustedRejectionMotif.value })
        });
        if(res.ok) {
            await fetchData();
            showDecisionModal.value = false;
        }
    } catch(e) {
        console.error(e);
    }
};

// Confirm Completion & Sign Contract Flow
const openConfirmModal = (r) => {
    selectedRenewal.value = r;
    isEditingContract.value = false;
    customContractContent.value = '';
    showConfirmModal.value = true;
};

const loadOldContract = () => {
    customContractContent.value = selectedRenewal.value?.contrat?.content || `<h3>CONTRAT DE BAIL</h3><p>Modèle standard à éditer...</p>`;
    isEditingContract.value = true;
};

const confirmCompletion = async () => {
    try {
        const res = await fetch(`/api/renouvellements/${selectedRenewal.value.id}/confirmer`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf()
            },
            body: JSON.stringify({ content: customContractContent.value })
        });
        if(res.ok) {
            await fetchData();
            showConfirmModal.value = false;
        } else {
            alert("Erreur lors de la confirmation");
        }
    } catch(e) {
        console.error(e);
    }
};

const openViewModal = (r) => {
    selectedRenewal.value = r;
    alert(`Détails de la demande :\nLocataire: ${r.locataire?.nom}\nStatut: ${r.statut}\nLoyer proposé: ${formatCurrency(r.nouveau_loyer)}\n${r.statut === 'Rejeté' ? 'Motif rejet: ' + r.motif_rejet : ''}`);
};

const destroy = async (id) => {
    if(!confirm("Supprimer cette demande rejetée ?")) return;
    try {
        const res = await fetch(`/api/renouvellements/${id}`, {
            method: 'DELETE',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf() }
        });
        if(res.ok) await fetchData();
    } catch(e) {}
};

// Utils
const formatCurrency = (val) => {
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(val || 0);
};

const formatDate = (dateStr) => {
    if(!dateStr) return '';
    return new Date(dateStr).toLocaleDateString('fr-FR');
};

const getStatusClass = (statut) => {
    switch(statut) {
        case 'En attente': return 'bg-amber-100 text-amber-800 px-3 py-1 rounded-full text-xs font-bold';
        case 'A venir': return 'bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-bold';
        case 'Complete': return 'bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-xs font-bold';
        case 'Rejeté': return 'bg-rose-100 text-rose-800 px-3 py-1 rounded-full text-xs font-bold';
        default: return 'bg-slate-100 text-slate-800 px-3 py-1 rounded-full text-xs font-bold';
    }
};
</script>
