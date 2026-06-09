<template>
    <div class="flex flex-col gap-8 select-none">
        
        <!-- Header with Luxury Dark Gradient Banner -->
        <div class="bg-gradient-to-br from-slate-900 via-slate-950 to-slate-900 rounded-3xl p-8 border border-slate-800 shadow-2xl text-white relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-48 h-48 rounded-full bg-blue-500/10 blur-2xl pointer-events-none"></div>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative z-10">
                <div>
                    <span class="text-blue-400 text-xs font-bold tracking-widest uppercase bg-blue-500/20 px-3 py-1 rounded-full border border-blue-500/30">Renouvellement</span>
                    <h1 class="text-3xl font-extrabold bg-gradient-to-r from-white via-slate-100 to-slate-300 bg-clip-text text-transparent mt-2">Renouvellements de Bail</h1>
                    <p class="text-slate-400 text-sm mt-1">Gérez les prolongations de baux actifs, les indexations de loyers et l'approbation des avenants.</p>
                </div>
                <button
                    @click="openAddModal"
                    class="px-5 py-3 rounded-2xl bg-blue-600 hover:bg-blue-500 border border-blue-500/50 hover:border-blue-400 text-white font-bold text-xs transition-all duration-300 transform hover:scale-[1.02] flex items-center gap-2 shadow-lg shadow-blue-600/35"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nouveau Renouvellement
                </button>
            </div>
        </div>

        <!-- Premium KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-blue-200/30 transition-all duration-500 hover:-translate-y-1 border border-slate-150 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-blue-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-slate-800 mb-1 tracking-tight">{{ totalRenouvellements }}</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Demandes</div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-amber-200/30 transition-all duration-500 hover:-translate-y-1 border border-slate-150 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-amber-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-amber-600 mb-1 tracking-tight">{{ enAttente }}</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">En Attente</div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-emerald-200/30 transition-all duration-500 hover:-translate-y-1 border border-slate-150 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-emerald-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-emerald-600 mb-1 tracking-tight">{{ aVenir }}</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">À Venir (30j)</div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-violet-200/30 transition-all duration-500 hover:-translate-y-1 border border-slate-150 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-violet-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-violet-500 to-purple-650 rounded-xl flex items-center justify-center shadow-lg shadow-violet-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-violet-600 mb-1 tracking-tight">{{ completes }}</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Complétés</div>
            </div>
        </div>

        <!-- Filter & Search Panel -->
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
                    placeholder="Rechercher par contrat, locataire..."
                    class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-2xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-semibold transition-all shadow-sm"
                />
            </div>
            <div class="flex items-center gap-2 w-full md:w-auto">
                <span class="text-xs font-bold uppercase text-slate-400">Statut :</span>
                <select 
                    v-model="statusFilter" 
                    class="bg-white border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:outline-none shadow-sm"
                >
                    <option value="All">Tous les statuts</option>
                    <option value="En attente">En attente</option>
                    <option value="À venir">À venir</option>
                    <option value="Complété">Complété</option>
                </select>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-gradient-to-br from-white to-slate-50 rounded-3xl shadow-xl shadow-slate-200/50 overflow-hidden border border-slate-150">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200/50 bg-slate-50/50">
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Contrat</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Locataire</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Date Fin Actuelle</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Nouvelle Date</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Nouveau Loyer</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-150">
                        <tr v-for="renouvellement in filteredRenouvellements" :key="renouvellement.id" class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 text-sm font-bold text-slate-800">{{ renouvellement.contrat }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div :class="['w-9 h-9 rounded-xl flex items-center justify-center text-white font-extrabold text-xs shadow-sm bg-gradient-to-br', getAvatarGradient(renouvellement.locataire)]">
                                        {{ getInitials(renouvellement.locataire) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800 text-sm">{{ renouvellement.locataire }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 font-semibold">{{ formatDate(renouvellement.dateFinActuelle) }}</td>
                            <td class="px-6 py-4 text-sm text-slate-800 font-bold">{{ formatDate(renouvellement.nouvelleDate) }}</td>
                            <td class="px-6 py-4 text-sm text-slate-800 font-extrabold">{{ formatCurrency(renouvellement.nouveauLoyer) }}</td>
                            <td class="px-6 py-4">
                                <span :class="[
                                    'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border',
                                    renouvellement.statut === 'Complété' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' :
                                    renouvellement.statut === 'En attente' ? 'bg-amber-50 text-amber-700 border-amber-200' :
                                    'bg-blue-50 text-blue-700 border-blue-200'
                                ]">
                                    <span :class="['w-1.5 h-1.5 rounded-full',
                                        renouvellement.statut === 'Complété' ? 'bg-emerald-500' :
                                        renouvellement.statut === 'En attente' ? 'bg-amber-500 animate-pulse' :
                                        'bg-blue-500'
                                    ]"></span>
                                    {{ renouvellement.statut }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <button 
                                        v-if="renouvellement.content"
                                        @click="viewContract(renouvellement)" 
                                        class="p-2 text-slate-650 hover:bg-slate-100 rounded-xl transition-all"
                                        title="Voir le contrat"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    <button 
                                        @click="openContractEditor(renouvellement)" 
                                        class="p-2 text-indigo-650 hover:bg-indigo-50 rounded-xl transition-all"
                                        title="Rédiger/Éditer le nouveau contrat"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </button>
                                    <button 
                                        @click="editRenouvellement(renouvellement)" 
                                        class="p-2 text-blue-650 hover:bg-blue-50 rounded-xl transition-all"
                                        title="Modifier"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button 
                                        @click="deleteRenouvellement(renouvellement)" 
                                        class="p-2 text-red-650 hover:bg-red-50 rounded-xl transition-all"
                                        title="Supprimer"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="filteredRenouvellements.length === 0">
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400 font-semibold text-sm">
                                Aucun renouvellement de bail trouvé.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add/Edit Modal (Premium backdrop blur design) -->
        <div v-if="showModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl shadow-slate-950/40 max-w-3xl w-full p-8 border border-slate-200 relative overflow-hidden animate-scale-up">
                
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-2xl bg-blue-650 flex items-center justify-center shadow-md">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" />
                                </svg>
                            </div>
                            <h2 class="text-2xl font-extrabold text-slate-800">
                                {{ editingRenouvellement ? 'Modifier' : 'Nouveau' }} Renouvellement
                            </h2>
                        </div>
                        <button @click="closeModal" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-all hover:rotate-90">
                            <svg class="w-4 h-4 text-slate-550" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-1 scrollbar-thin">
                        
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Leases selection (active leases only) -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Contrat de Bail Actif *</label>
                                <select 
                                    v-model="formData.contrat" 
                                    @change="handleContratChange"
                                    class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-semibold text-slate-800 text-sm shadow-sm"
                                    :disabled="editingRenouvellement"
                                >
                                    <option value="" disabled>Sélectionner un bail actif</option>
                                    <option v-for="c in contratsActifs" :key="c.id" :value="c.numero">
                                        {{ c.numero }} - {{ c.locataire }} ({{ c.reference }})
                                    </option>
                                </select>
                                <p v-if="contratsActifs.length === 0" class="text-xs text-rose-600 font-semibold mt-1">Aucun contrat actif trouvé dans le système.</p>
                            </div>

                            <!-- Locataire (Read-only after lease choice) -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Locataire</label>
                                <input 
                                    v-model="formData.locataire" 
                                    type="text" 
                                    placeholder="Sélectionnez un bail d'abord" 
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-500 text-sm focus:outline-none cursor-not-allowed shadow-sm" 
                                    readonly
                                />
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Date de fin actuelle (Read-only) -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Fin Actuelle</label>
                                <input 
                                    v-model="formData.dateFinActuelle" 
                                    type="date" 
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-500 text-sm focus:outline-none cursor-not-allowed shadow-sm" 
                                    readonly
                                />
                            </div>

                            <!-- Nouvelle date de fin -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Nouvelle Fin *</label>
                                <input 
                                    v-model="formData.nouvelleDate" 
                                    type="date" 
                                    class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-semibold text-slate-800 text-sm shadow-sm"
                                />
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Nouveau Loyer -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Nouveau Loyer (€) *</label>
                                <input 
                                    v-model.number="formData.nouveauLoyer" 
                                    type="number" 
                                    placeholder="Ex: 1250" 
                                    class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-semibold text-slate-800 text-sm shadow-sm"
                                />
                            </div>

                            <!-- Statut -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Statut *</label>
                                <select 
                                    v-model="formData.statut" 
                                    class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-semibold text-slate-800 text-sm shadow-sm"
                                >
                                    <option value="En attente">En attente</option>
                                    <option value="À venir">À venir</option>
                                    <option value="Complété">Complété</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Footers -->
                    <div class="flex gap-4 mt-8 border-t border-slate-100 pt-5">
                        <button 
                            @click="closeModal" 
                            class="flex-1 px-5 py-3.5 bg-slate-100 text-slate-650 rounded-xl font-bold hover:bg-slate-200 transition-all text-xs"
                        >
                            Annuler
                        </button>
                        <button 
                            @click="saveRenouvellement" 
                            class="flex-1 px-5 py-3.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-md transition-all text-xs"
                        >
                            Enregistrer
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div v-if="showDeleteModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 flex items-center justify-center p-4">
            <div class="bg-gradient-to-br from-white via-white to-red-50/20 rounded-3xl shadow-2xl max-w-md w-full p-8 border border-red-100/50 relative overflow-hidden animate-scale-up">
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-gradient-to-br from-red-400/10 to-pink-500/10 rounded-full blur-3xl"></div>
                <div class="relative z-10 text-center">
                    <div class="flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-red-500 to-rose-600 mx-auto mb-5 shadow-lg shadow-red-500/30">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-extrabold text-slate-800 mb-2">Supprimer ce renouvellement ?</h3>
                    <p class="text-slate-500 text-sm mb-6">Cette action est définitive et supprimera l'historique de cette demande de renouvellement.</p>

                    <div class="flex gap-4">
                        <button 
                            @click="closeDeleteModal" 
                            class="flex-1 px-5 py-3.5 bg-slate-100 text-slate-650 rounded-xl font-bold hover:bg-slate-200 transition-all text-xs"
                        >
                            Annuler
                        </button>
                        <button 
                            @click="confirmDelete" 
                            class="flex-1 px-5 py-3.5 bg-gradient-to-r from-red-500 to-rose-600 text-white rounded-xl font-bold shadow-lg shadow-red-500/30 hover:shadow-xl hover:shadow-red-500/40 transition-all hover:scale-[1.01] text-xs"
                        >
                            Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Toast Modal -->
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
                    <button 
                        @click="closeSuccess" 
                        class="w-full px-5 py-3.5 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-xl font-bold shadow-md shadow-emerald-500/20 hover:scale-[1.01] transition-all text-xs"
                    >
                        Fermer
                    </button>
                </div>
            </div>
        </div>

        <!-- Error Toast Modal -->
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
                    <button 
                        @click="closeError" 
                        class="w-full px-5 py-3.5 bg-gradient-to-r from-red-500 to-rose-600 text-white rounded-xl font-bold shadow-md shadow-red-500/20 hover:scale-[1.01] transition-all text-xs"
                    >
                        Fermer
                    </button>
                </div>
            </div>
        </div>

        <!-- Contract Options Modal -->
        <div v-if="showContractOptionsModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full p-8 border border-slate-200 relative overflow-hidden animate-scale-up">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-2xl bg-indigo-600 flex items-center justify-center shadow-md">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-extrabold text-slate-800">Édition du Nouveau Contrat</h2>
                    </div>
                    <button @click="closeContractOptionsModal" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-all">
                        <svg class="w-4 h-4 text-slate-550" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <div class="p-4 bg-slate-50 border border-slate-150 rounded-2xl space-y-2 text-sm text-slate-700">
                        <div class="flex justify-between">
                            <span class="font-semibold text-slate-400">Locataire :</span>
                            <span class="font-bold text-slate-800">{{ selectedRenouvellement?.locataire }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold text-slate-400">Contrat initial :</span>
                            <span class="font-bold text-slate-800">{{ selectedRenouvellement?.contrat }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold text-slate-400">Nouveau Loyer :</span>
                            <span class="font-bold text-slate-800">{{ formatCurrency(selectedRenouvellement?.nouveauLoyer) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold text-slate-400">Nouvelle date de fin :</span>
                            <span class="font-bold text-slate-800">{{ formatDate(selectedRenouvellement?.nouvelleDate) }}</span>
                        </div>
                    </div>

                    <div class="group">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Consignes additionnelles de guidage IA</label>
                        <textarea 
                            v-model="selectedRenouvellementInstructions" 
                            placeholder="Indiquez ici les conditions particulières à faire figurer dans le bail renouvelé..." 
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-medium text-slate-800 text-sm shadow-sm h-24"
                        ></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-100">
                        <button 
                            @click="openManualContractEditor" 
                            class="px-4 py-3.5 bg-white border-2 border-slate-250 text-slate-750 rounded-xl font-bold hover:bg-slate-50 transition-all text-xs"
                        >
                            ✏️ Rédiger Manuellement
                        </button>
                        <button 
                            @click="generateContractWithIA" 
                            class="px-4 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-md transition-all flex items-center justify-center gap-2 text-xs"
                            :disabled="isGenerating"
                        >
                            <span v-if="isGenerating" class="w-3.5 h-3.5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                            🤖 Générer avec l'IA
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rich Text TinyMCE Editor Modal -->
        <div v-if="showEditorModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-4xl w-full p-8 border border-slate-200 relative overflow-hidden animate-scale-up">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-extrabold text-slate-800">Éditeur de Contrat de Renouvellement</h3>
                    <span class="text-xs font-bold text-slate-400">TinyMCE Workspace</span>
                </div>
                
                <div class="mb-6 border border-slate-200 rounded-2xl overflow-hidden">
                    <textarea id="contract-renewal-editor"></textarea>
                </div>

                <div class="flex justify-end gap-4">
                    <button 
                        @click="closeEditorModal" 
                        class="px-6 py-3 bg-slate-100 text-slate-650 font-bold rounded-xl hover:bg-slate-200 transition-all text-xs"
                    >
                        Fermer sans enregistrer
                    </button>
                    <button 
                        @click="confirmContractRedaction" 
                        class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-md transition-all text-xs"
                    >
                        Valider & Enregistrer le Contrat
                    </button>
                </div>
            </div>
        </div>

        <!-- View Contract Modal -->
        <div v-if="showViewContractModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-3xl w-full p-8 border border-slate-200 relative overflow-hidden animate-scale-up">
                <div class="flex items-center justify-between mb-6 border-b border-slate-100 pb-4">
                    <div>
                        <span class="text-[10px] font-bold text-blue-600 uppercase">Contrat de renouvellement</span>
                        <h3 class="text-lg font-extrabold text-slate-850">Bail renouvelé pour {{ viewingContractRef?.locataire }}</h3>
                    </div>
                    <button @click="closeViewContractModal" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-all hover:rotate-90">
                        <svg class="w-4 h-4 text-slate-550" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="bg-slate-50 border border-slate-200/60 rounded-2xl p-6 md:p-8 max-h-[50vh] overflow-y-auto scrollbar-thin shadow-inner font-serif text-slate-850 text-sm leading-relaxed whitespace-pre-line" v-html="viewingContractRef?.content"></div>

                <div class="flex justify-end gap-3 mt-6">
                    <button @click="closeViewContractModal" class="px-6 py-2.5 bg-slate-150 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition-all">Fermer</button>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref, computed, onMounted, nextTick } from 'vue';
import axios from 'axios';
import tinymce from 'tinymce';

const renouvellements = ref([]);
const contratsActifs = ref([]);

const searchQuery = ref('');
const statusFilter = ref('All');

const showContractOptionsModal = ref(false);
const showEditorModal = ref(false);
const showViewContractModal = ref(false);
const selectedRenouvellement = ref(null);
const selectedRenouvellementInstructions = ref('');
const editorContent = ref('');
const isGenerating = ref(false);
const viewingContractRef = ref(null);

onMounted(() => {
    // 1. Load renewals from localStorage
    const storedRen = localStorage.getItem('immobilier_renouvellements');
    if (storedRen) {
        renouvellements.value = JSON.parse(storedRen);
    } else {
        renouvellements.value = [
            { id: 1, contrat: 'CTR-001', locataire: 'Jean Dupont', dateFinActuelle: '2026-06-30', nouvelleDate: '2027-06-30', nouveauLoyer: 1250, statut: 'En attente', instructions: '', content: '' },
            { id: 2, contrat: 'CTR-002', locataire: 'Marie Lambert', dateFinActuelle: '2026-07-15', nouvelleDate: '2027-07-15', nouveauLoyer: 2600, statut: 'À venir', instructions: '', content: '' },
            { id: 3, contrat: 'CTR-003', locataire: 'Pierre Martin', dateFinActuelle: '2026-05-31', nouvelleDate: '2027-05-31', nouveauLoyer: 980, statut: 'Complété', instructions: '', content: '' }
        ];
        localStorage.setItem('immobilier_renouvellements', JSON.stringify(renouvellements.value));
    }

    // 2. Load active contracts from localStorage
    const storedContrats = localStorage.getItem('immobilier_contrats');
    if (storedContrats) {
        contratsActifs.value = JSON.parse(storedContrats).filter(c => c.statut === 'Actif');
    } else {
        // Create standard mock contracts in localStorage if none exist to enable UX
        const mockContrats = [
            { id: 1, numero: 'CTR-001', locataire: 'Jean Dupont', loyer: 1200, caution: 2400, debut: '2025-07-01', fin: '2026-06-30', statut: 'Actif', reference: 'Apt 204', batiment: 'Immeuble A', duree: '1 an', typeBail: 'Habitation' },
            { id: 2, numero: 'CTR-002', locataire: 'Marie Lambert', loyer: 2500, caution: 5000, debut: '2025-07-16', fin: '2026-07-15', statut: 'Actif', reference: 'Bureau 102', batiment: 'Immeuble B', duree: '1 an', typeBail: 'Commercial' },
            { id: 3, numero: 'CTR-003', locataire: 'Pierre Martin', loyer: 950, caution: 1900, debut: '2025-06-01', fin: '2026-05-31', statut: 'Actif', reference: 'Apt 305', batiment: 'Immeuble C', duree: '1 an', typeBail: 'Habitation' }
        ];
        localStorage.setItem('immobilier_contrats', JSON.stringify(mockContrats));
        contratsActifs.value = mockContrats;
    }
});

const filteredRenouvellements = computed(() => {
    let result = renouvellements.value;
    
    // Search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        result = result.filter(r => 
            r.contrat.toLowerCase().includes(query) ||
            r.locataire.toLowerCase().includes(query)
        );
    }
    
    // Status filter
    if (statusFilter.value !== 'All') {
        result = result.filter(r => r.statut === statusFilter.value);
    }

    return result;
});

const totalRenouvellements = computed(() => renouvellements.value.length);
const enAttente = computed(() => renouvellements.value.filter(r => r.statut === 'En attente').length);
const aVenir = computed(() => renouvellements.value.filter(r => r.statut === 'À venir').length);
const completes = computed(() => renouvellements.value.filter(r => r.statut === 'Complété').length);

const showModal = ref(false);
const showDeleteModal = ref(false);
const showSuccess = ref(false);
const showError = ref(false);
const editingRenouvellement = ref(null);
const successMessage = ref('');
const errorMessage = ref('');
const deleteTarget = ref(null);

const formData = ref({
    contrat: '',
    locataire: '',
    dateFinActuelle: '',
    nouvelleDate: '',
    nouveauLoyer: '',
    statut: 'En attente'
});

const openAddModal = () => {
    editingRenouvellement.value = null;
    formData.value = {
        contrat: '',
        locataire: '',
        dateFinActuelle: '',
        nouvelleDate: '',
        nouveauLoyer: '',
        statut: 'En attente'
    };
    showModal.value = true;
};

const handleContratChange = () => {
    const selected = contratsActifs.value.find(c => c.numero === formData.value.contrat);
    if (selected) {
        formData.value.locataire = selected.locataire;
        formData.value.dateFinActuelle = selected.fin;
        
        // Autopopulate defaults: +1 year for renewal date
        if (selected.fin) {
            const date = new Date(selected.fin);
            date.setFullYear(date.getFullYear() + 1);
            formData.value.nouvelleDate = date.toISOString().split('T')[0];
        }
        // Autopopulate defaults: +5% on rent
        formData.value.nouveauLoyer = Math.round(selected.loyer * 1.05);
    }
};

const editRenouvellement = (renouvellement) => {
    editingRenouvellement.value = renouvellement;
    formData.value = { ...renouvellement };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editingRenouvellement.value = null;
};

const saveRenouvellement = () => {
    if (!formData.value.contrat || !formData.value.locataire || !formData.value.nouvelleDate || !formData.value.nouveauLoyer) {
        errorMessage.value = 'Veuillez remplir tous les champs obligatoires (*)';
        showError.value = true;
        return;
    }

    if (editingRenouvellement.value) {
        const index = renouvellements.value.findIndex(r => r.id === editingRenouvellement.value.id);
        if (index !== -1) {
            renouvellements.value[index] = { ...formData.value, id: editingRenouvellement.value.id };
        }
        successMessage.value = 'Renouvellement modifié avec succès';
    } else {
        renouvellements.value.unshift({
            ...formData.value,
            id: renouvellements.value.length ? Math.max(...renouvellements.value.map(r => r.id)) + 1 : 1
        });
        successMessage.value = 'Renouvellement ajouté avec succès';
    }

    // Sync back to contract if status is "Complété"
    if (formData.value.statut === 'Complété') {
        const storedContrats = localStorage.getItem('immobilier_contrats');
        if (storedContrats) {
            const list = JSON.parse(storedContrats);
            const cIdx = list.findIndex(c => c.numero === formData.value.contrat);
            if (cIdx !== -1) {
                list[cIdx].fin = formData.value.nouvelleDate;
                list[cIdx].loyer = Number(formData.value.nouveauLoyer);
                localStorage.setItem('immobilier_contrats', JSON.stringify(list));
            }
        }

        // Also sync locataire profile if it exists
        const storedLoc = localStorage.getItem('immobilier_locataires');
        if (storedLoc) {
            const locList = JSON.parse(storedLoc);
            const locIdx = locList.findIndex(l => l.nom.toLowerCase() === formData.value.locataire.toLowerCase());
            if (locIdx !== -1) {
                locList[locIdx].loyer = Number(formData.value.nouveauLoyer);
                localStorage.setItem('immobilier_locataires', JSON.stringify(locList));
            }
        }
    }

    localStorage.setItem('immobilier_renouvellements', JSON.stringify(renouvellements.value));
    closeModal();
    showSuccess.value = true;
};

const deleteRenouvellement = (renouvellement) => {
    deleteTarget.value = renouvellement;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    deleteTarget.value = null;
};

const confirmDelete = () => {
    if (deleteTarget.value) {
        renouvellements.value = renouvellements.value.filter(r => r.id !== deleteTarget.value.id);
        localStorage.setItem('immobilier_renouvellements', JSON.stringify(renouvellements.value));
        successMessage.value = 'Renouvellement supprimé avec succès';
        closeDeleteModal();
        showSuccess.value = true;
    }
};

const closeSuccess = () => {
    showSuccess.value = false;
};

const closeError = () => {
    showError.value = false;
};

// Styling and formatting helpers
const formatCurrency = (val) => {
    if (val === undefined || val === null || isNaN(val)) return '0 €';
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR', maximumFractionDigits: 0 }).format(val);
};

const formatDate = (dateStr) => {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return new Intl.DateTimeFormat('fr-FR', { day: '2-digit', month: '2-digit', year: 'numeric' }).format(date);
};

const getInitials = (name) => {
    if (!name) return 'LD';
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
        'from-cyan-500 to-blue-600',
        'from-pink-500 to-rose-600'
    ];
    let hash = 0;
    for (let i = 0; i < name.length; i++) {
        hash = name.charCodeAt(i) + ((hash << 5) - hash);
    }
    const index = Math.abs(hash) % colors.length;
    return colors[index];
};

const openContractEditor = (renouvellement) => {
    selectedRenouvellement.value = renouvellement;
    selectedRenouvellementInstructions.value = renouvellement.instructions || '';
    editorContent.value = renouvellement.content || '';
    showContractOptionsModal.value = true;
};

const closeContractOptionsModal = () => {
    showContractOptionsModal.value = false;
    selectedRenouvellement.value = null;
    selectedRenouvellementInstructions.value = '';
};

const initTinyMCE = () => {
    if (!tinymce) {
        alert("TinyMCE n'est pas chargé. Veuillez vérifier votre connexion.");
        return;
    }
    
    const editor = tinymce.get('contract-renewal-editor');
    if (editor) {
        tinymce.remove(editor);
    }
    
    tinymce.init({
        selector: '#contract-renewal-editor',
        license_key: 'gpl',
        skin_url: 'https://cdn.jsdelivr.net/npm/tinymce@7.2.0/skins/ui/oxide',
        content_css: 'https://cdn.jsdelivr.net/npm/tinymce@7.2.0/skins/content/default/content.css',
        height: 320,
        menubar: false,
        branding: false,
        plugins: 'advlist autolink lists link charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime table code help wordcount',
        toolbar: 'undo redo | blocks | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | code',
        setup: (editor) => {
            editor.on('init', () => {
                editor.setContent(editorContent.value || '');
            });
            editor.on('change keyup', () => {
                editorContent.value = editor.getContent();
            });
        }
    });
};

const openManualContractEditor = () => {
    if (!editorContent.value) {
        editorContent.value = buildInitialContractFromTemplate();
    }
    showContractOptionsModal.value = false;
    showEditorModal.value = true;
    nextTick(() => {
        initTinyMCE();
    });
};

const buildInitialContractFromTemplate = () => {
    return `<h3>AVENANT DE RENOUVELLEMENT DE BAIL</h3>
<p><strong>Bailleur :</strong> Enterprise Property Corp<br>
<strong>Locataire :</strong> ${selectedRenouvellement.value.locataire}<br>
<strong>Bail initial :</strong> Numéro ${selectedRenouvellement.value.contrat} se terminant le ${formatDate(selectedRenouvellement.value.dateFinActuelle)}</p>

<p>Par le présent avenant, les parties conviennent de renouveler le contrat de bail susmentionné aux conditions suivantes :</p>
<ul>
    <li><strong>Nouvelle date de fin :</strong> ${formatDate(selectedRenouvellement.value.nouvelleDate)}</li>
    <li><strong>Nouveau loyer mensuel :</strong> ${formatCurrency(selectedRenouvellement.value.nouveauLoyer)}</li>
</ul>

<p>Les autres clauses et conditions du bail initial demeurent inchangées et restent pleinement applicables.</p>
<p>Fait en double exemplaire le ${getTodayDate()}.</p>`;
};

const getTodayDate = () => {
    const today = new Date();
    return today.toLocaleDateString('fr-FR');
};

const closeEditorModal = () => {
    const editor = tinymce.get('contract-renewal-editor');
    if (editor) {
        tinymce.remove(editor);
    }
    showEditorModal.value = false;
};

const confirmContractRedaction = () => {
    const editor = tinymce.get('contract-renewal-editor');
    if (editor) {
        editorContent.value = editor.getContent();
        tinymce.remove(editor);
    }
    showEditorModal.value = false;
    
    // Save content to renewal
    const index = renouvellements.value.findIndex(r => r.id === selectedRenouvellement.value.id);
    if (index !== -1) {
        renouvellements.value[index].content = editorContent.value;
        renouvellements.value[index].instructions = selectedRenouvellementInstructions.value;
        localStorage.setItem('immobilier_renouvellements', JSON.stringify(renouvellements.value));
        successMessage.value = "Contrat de renouvellement enregistré avec succès.";
        showSuccess.value = true;
    }
    closeContractOptionsModal();
};

const generateContractWithIA = async () => {
    isGenerating.value = true;
    try {
        const activeContrat = contratsActifs.value.find(c => c.numero === selectedRenouvellement.value.contrat) || {};
        
        const payload = {
            locataire: selectedRenouvellement.value.locataire,
            loyer: selectedRenouvellement.value.nouveauLoyer,
            caution: activeContrat.caution || (selectedRenouvellement.value.nouveauLoyer * 2),
            debut: selectedRenouvellement.value.dateFinActuelle,
            fin: selectedRenouvellement.value.nouvelleDate,
            reference: activeContrat.reference || 'Non spécifié',
            batiment: activeContrat.batiment || 'Non spécifié',
            duree: activeContrat.duree || '1 an',
            typeBail: activeContrat.typeBail || 'Habitation',
            instructions: selectedRenouvellementInstructions.value || '',
        };

        const response = await axios.post('/api/ai/generate-contract', payload);
        if (response.data && response.data.success) {
            editorContent.value = response.data.contract;
            showContractOptionsModal.value = false;
            showEditorModal.value = true;
            nextTick(() => {
                initTinyMCE();
            });
        } else {
            errorMessage.value = response.data.message || "Impossible de générer le contrat.";
            showError.value = true;
        }
    } catch (e) {
        console.error(e);
        errorMessage.value = e.response?.data?.message || "Une erreur s'est produite lors de l'appel de l'API de génération.";
        showError.value = true;
    } finally {
        isGenerating.value = false;
    }
};

const viewContract = (renouvellement) => {
    viewingContractRef.value = renouvellement;
    showViewContractModal.value = true;
};

const closeViewContractModal = () => {
    showViewContractModal.value = false;
    viewingContractRef.value = null;
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
