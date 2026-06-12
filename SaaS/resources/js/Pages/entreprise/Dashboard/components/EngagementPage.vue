<template>
    <div class="flex flex-col gap-8 select-none">
        
        <!-- Header with Luxury Purple/Pink Banner -->
        <div class="bg-gradient-to-br from-slate-900 via-slate-950 to-slate-900 rounded-3xl p-8 border border-slate-800 shadow-2xl text-white relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-48 h-48 rounded-full bg-purple-500/10 blur-2xl pointer-events-none"></div>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative z-10">
                <div>
                    <span class="text-purple-400 text-xs font-bold tracking-widest uppercase bg-purple-500/20 px-3 py-1 rounded-full border border-purple-500/30">Actes & Conventions</span>
                    <h1 class="text-3xl font-extrabold bg-gradient-to-r from-white via-slate-100 to-slate-300 bg-clip-text text-transparent mt-2">Gestion des Engagements</h1>
                    <p class="text-slate-400 text-sm mt-1">Rédigez et pilotez vos promesses de bail, avenants, conventions de cautionnement et protocoles d'accords.</p>
                </div>
                <button
                    @click="openAddModal"
                    class="px-5 py-3 rounded-2xl bg-gradient-to-r from-purple-600 to-pink-650 hover:from-purple-500 hover:to-pink-600 border border-purple-500/30 text-white font-bold text-xs transition-all duration-300 transform hover:scale-[1.02] flex items-center gap-2 shadow-lg shadow-purple-600/35"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nouvel Engagement
                </button>
            </div>
        </div>

        <!-- Premium KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-purple-200/30 transition-all duration-500 hover:-translate-y-1 border border-slate-150 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-purple-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-slate-800 mb-1 tracking-tight">{{ totalEngagements }}</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Engagements</div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-emerald-200/30 transition-all duration-500 hover:-translate-y-1 border border-slate-150 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-emerald-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-650 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-emerald-600 mb-1 tracking-tight">{{ actifs }}</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Engagements Actifs</div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-amber-200/30 transition-all duration-500 hover:-translate-y-1 border border-slate-150 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-amber-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-amber-600 mb-1 tracking-tight">{{ enCours }}</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">En Cours d'Approbation</div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-rose-200/30 transition-all duration-500 hover:-translate-y-1 border border-slate-150 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-rose-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-rose-500 to-red-650 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-rose-600 mb-1 tracking-tight">{{ expires }}</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Actes Expirés</div>
            </div>
        </div>

        <!-- Filter & Search Panel -->
        <div class="bg-gradient-to-br from-white to-slate-50 rounded-3xl p-6 shadow-xl shadow-slate-200/50 border border-slate-150 flex flex-col gap-4">
            <div class="flex flex-col lg:flex-row gap-4 items-center justify-between">
                <div class="relative w-full lg:w-80">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-450" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Rechercher par référence, locataire..."
                        class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-2xl focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 text-sm font-semibold transition-all shadow-sm"
                    />
                </div>
                <div class="flex flex-wrap items-center gap-4 w-full lg:w-auto">
                    <!-- Agency Filter -->
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold uppercase text-slate-400">Agence :</span>
                        <select v-model="agencyFilter" class="bg-white border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:outline-none shadow-sm">
                            <option value="All">Toutes les agences</option>
                            <option v-for="a in agencies" :key="a.id" :value="a.id">{{ a.nom }}</option>
                        </select>
                    </div>
                    <!-- Building Filter -->
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold uppercase text-slate-400">Bâtiment :</span>
                        <select v-model="buildingFilter" class="bg-white border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:outline-none shadow-sm">
                            <option value="All">Tous les bâtiments</option>
                            <option v-for="b in filterBuildings" :key="b.id" :value="b.id">{{ b.nom }}</option>
                        </select>
                    </div>
                    <!-- Type Filter -->
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold uppercase text-slate-400">Type :</span>
                        <select v-model="typeFilter" class="bg-white border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:outline-none shadow-sm">
                            <option value="All">Tous les types</option>
                            <option v-for="t in typeEngagements" :key="t.id" :value="t.id">{{ t.nom }}</option>
                        </select>
                    </div>
                    <!-- Status Filter -->
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold uppercase text-slate-400">Statut :</span>
                        <select v-model="statusFilter" class="bg-white border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:outline-none shadow-sm">
                            <option value="All">Tous les statuts</option>
                            <option value="actif">Actif</option>
                            <option value="en cours">En cours</option>
                            <option value="expirer">Expiré</option>
                            <option value="valider">Validé</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading state -->
        <div v-if="isInitialLoading" class="flex justify-center items-center py-12">
            <span class="animate-spin h-10 w-10 border-4 border-purple-500 border-t-transparent rounded-full"></span>
        </div>

        <!-- Table Section -->
        <div v-else class="bg-gradient-to-br from-white to-slate-50 rounded-3xl shadow-xl shadow-slate-200/50 overflow-hidden border border-slate-150">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200/50 bg-slate-50/50">
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Référence</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Partie Concernée</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Durée</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Montant</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-150">
                        <tr v-for="eng in filteredEngagements" :key="eng.id" class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 text-sm font-bold text-slate-850">{{ eng.reference }}</td>
                            <td class="px-6 py-4 text-sm text-slate-700 font-bold">{{ eng.type_engagement?.nom || 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div :class="['w-9 h-9 rounded-xl flex items-center justify-center text-white font-extrabold text-xs shadow-sm bg-gradient-to-br', getAvatarGradient(eng.locataire?.nom)]">
                                        {{ getInitials(eng.locataire?.nom) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <div class="font-bold text-slate-800 text-sm">{{ eng.locataire?.nom || 'N/A' }}</div>
                                        <div v-if="eng.agency" class="text-[10px] font-bold text-slate-450 uppercase tracking-wider">{{ eng.agency.nom }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 font-semibold">{{ formatDate(eng.date_debut) }} au {{ formatDate(eng.date_fin) }}</td>
                            <td class="px-6 py-4 text-sm text-slate-800 font-extrabold">{{ formatCurrency(eng.montant) }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1.5">
                                    <span :class="[
                                        'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border w-fit',
                                        eng.statut === 'actif' ? 'bg-emerald-50 text-emerald-700 border-emerald-250' :
                                        eng.statut === 'en cours' ? 'bg-amber-50 text-amber-700 border-amber-200' :
                                        eng.statut === 'valider' ? 'bg-purple-50 text-purple-700 border-purple-200' :
                                        'bg-rose-50 text-rose-700 border-rose-200'
                                    ]">
                                        <span :class="['w-1.5 h-1.5 rounded-full',
                                            eng.statut === 'actif' ? 'bg-emerald-500' :
                                            eng.statut === 'en cours' ? 'bg-amber-500 animate-pulse' :
                                            eng.statut === 'valider' ? 'bg-purple-500' :
                                            'bg-rose-500'
                                        ]"></span>
                                        {{ eng.statut === 'actif' ? 'Actif' : eng.statut === 'en cours' ? 'En cours' : eng.statut === 'valider' ? 'Validé' : 'Expiré' }}
                                    </span>
                                    <span v-if="eng.statut_honneur" :class="[
                                        'inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold border w-fit',
                                        eng.statut_honneur === 'honner' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-650 border-rose-100'
                                    ]">
                                        {{ eng.statut_honneur === 'honner' ? 'Honoré' : 'Non Honoré' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <!-- Confirm Honor button -->
                                    <button 
                                        v-if="eng.statut !== 'valider'"
                                        @click="confirmHonor(eng)" 
                                        :disabled="confirmingId === eng.id"
                                        class="p-2 text-emerald-650 hover:bg-emerald-50 rounded-xl transition-all relative flex items-center justify-center disabled:opacity-50"
                                        title="Valider & Honorer"
                                    >
                                        <span v-if="confirmingId === eng.id" class="w-5 h-5 border-2 border-emerald-500/30 border-t-emerald-600 rounded-full animate-spin"></span>
                                        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </button>
                                    <button 
                                        v-if="eng.content" 
                                        @click="viewEngagement(eng)" 
                                        class="p-2 text-slate-650 hover:bg-slate-100 rounded-xl transition-all"
                                        title="Voir l'acte"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    <button 
                                        @click="editEngagement(eng)" 
                                        class="p-2 text-purple-650 hover:bg-purple-50 rounded-xl transition-all"
                                        title="Modifier"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button 
                                        @click="deleteEngagement(eng)" 
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
                        <tr v-if="filteredEngagements.length === 0">
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400 font-semibold text-sm">
                                Aucun acte d'engagement trouvé.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add/Edit Modal (Canevas & Prompts options) -->
        <div v-if="showModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-3xl w-full p-8 border border-slate-200 relative overflow-hidden animate-scale-up">
                
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-2xl bg-purple-600 flex items-center justify-center shadow-md">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h2 class="text-2xl font-extrabold text-slate-800">
                                {{ editingEngagement ? 'Modifier' : 'Créer un' }} Engagement
                            </h2>
                        </div>
                        <button @click="closeModal" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-all hover:rotate-90">
                            <svg class="w-4 h-4 text-slate-550" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-1 scrollbar-thin">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Reference -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Référence *</label>
                                <input v-model="formData.reference" type="text" placeholder="Ex: ENG-2026-004" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-semibold text-slate-800 text-sm shadow-sm" />
                            </div>

                            <!-- Type -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Type d'engagement *</label>
                                <select v-model="formData.type_engagement_id" required class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-semibold text-slate-800 text-sm shadow-sm">
                                    <option value="" disabled>Sélectionner le type</option>
                                    <option v-for="t in typeEngagements" :key="t.id" :value="t.id">{{ t.nom }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- 3-level cascade selection -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Agency Select -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Agence *</label>
                                <select 
                                    v-model="formData.agency_id" 
                                    required
                                    class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-semibold text-slate-800 text-sm shadow-sm"
                                    @change="onAgencyChange"
                                >
                                    <option value="" disabled>Sélectionner une agence</option>
                                    <option v-for="a in agencies" :key="a.id" :value="a.id">{{ a.nom }}</option>
                                </select>
                            </div>

                            <!-- Building Select -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Bâtiment *</label>
                                <select 
                                    v-model="formData.batiment_id" 
                                    required
                                    :disabled="!formData.agency_id"
                                    class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-semibold text-slate-800 text-sm shadow-sm disabled:opacity-50"
                                    @change="onBatimentChange"
                                >
                                    <option value="" disabled>Sélectionner un bâtiment</option>
                                    <option v-for="b in modalFilteredBatiments" :key="b.id" :value="b.id">{{ b.nom }}</option>
                                </select>
                            </div>

                            <!-- Contract / Tenant Select -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Contrepartie (Locataire Actif) *</label>
                                <select 
                                    v-model="formData.contrat_id" 
                                    required
                                    :disabled="!formData.batiment_id"
                                    class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-semibold text-slate-800 text-sm shadow-sm disabled:opacity-50"
                                    @change="onContratChange"
                                >
                                    <option value="" disabled>Sélectionner le locataire</option>
                                    <option v-for="c in modalFilteredContrats" :key="c.id" :value="c.id">
                                        {{ c.locataire }} (Bail {{ c.numero }} - {{ c.reference }})
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Date Debut -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Date Début *</label>
                                <input v-model="formData.date_debut" type="date" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-semibold text-slate-800 text-sm shadow-sm" />
                            </div>
                            <!-- Date Fin -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Date Fin *</label>
                                <input v-model="formData.date_fin" type="date" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-semibold text-slate-800 text-sm shadow-sm" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Montant -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Montant (€)</label>
                                <input v-model.number="formData.montant" type="number" placeholder="Ex: 5000" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-semibold text-slate-800 text-sm shadow-sm" />
                            </div>
                        </div>

                        <!-- Instructions IA -->
                        <div class="group">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Consignes additionnelles pour la rédaction IA</label>
                            <textarea 
                                v-model="formData.instructions" 
                                placeholder="Indiquez ici les conditions particulières à faire figurer dans l'acte d'engagement..." 
                                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-medium text-slate-800 text-sm shadow-sm h-20"
                            ></textarea>
                        </div>

                        <!-- Canvas Template display -->
                        <div v-if="canevasTemplate" class="bg-slate-55 border border-slate-200 rounded-2xl p-4">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase mb-2">Modèle de Canevas Actuel :</span>
                            <div class="text-[11px] text-slate-550 leading-relaxed font-mono whitespace-pre-line max-h-24 overflow-y-auto">
                                {{ canevasTemplate }}
                            </div>
                        </div>
                    </div>

                    <!-- Action buttons for Editor -->
                    <div class="grid grid-cols-2 gap-4 mt-6 border-t border-slate-100 pt-5">
                        <button 
                            @click="openManualEditor" 
                            class="px-4 py-3 bg-white border-2 border-slate-200 text-slate-700 rounded-xl font-bold hover:bg-slate-50 hover:border-slate-350 transition-all text-xs"
                        >
                            ✏️ Rédiger Manuellement
                        </button>
                        <button 
                            @click="generateWithIA" 
                            class="px-4 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-xl font-bold shadow-md transition-all flex items-center justify-center gap-2 text-xs"
                            :disabled="isGenerating"
                        >
                            <span v-if="isGenerating" class="w-3.5 h-3.5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                            🤖 Générer avec l'IA
                        </button>
                    </div>
                    <div class="flex justify-end mt-4">
                        <button @click="closeModal" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 rounded-xl text-slate-600 text-xs font-bold transition-all">Fermer</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rich Text TinyMCE Editor Modal -->
        <div v-if="showEditorModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-4xl w-full p-8 border border-slate-200 relative overflow-hidden animate-scale-up">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-extrabold text-slate-800">Éditeur d'Acte d'Engagement</h3>
                    <span class="text-xs font-bold text-slate-400">TinyMCE Workspace</span>
                </div>
                
                <div class="mb-6 border border-slate-200 rounded-2xl overflow-hidden bg-slate-50">
                    <textarea id="engagement-editor"></textarea>
                </div>

                <div class="flex justify-end gap-4">
                    <button 
                        @click="closeEditorModal" 
                        class="px-6 py-3 bg-slate-100 text-slate-650 font-bold rounded-xl hover:bg-slate-200 transition-all text-xs"
                    >
                        Fermer sans enregistrer
                    </button>
                    <button 
                        @click="confirmRedaction" 
                        :disabled="isSaving"
                        class="px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-650 text-white font-bold rounded-xl shadow-lg shadow-purple-500/20 hover:shadow-xl hover:shadow-purple-500/35 transition-all text-xs flex items-center gap-2"
                    >
                        <span v-if="isSaving" class="w-3.5 h-3.5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                        <span>Valider & Enregistrer l'Acte</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- View Content Modal -->
        <div v-if="showViewContentModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-3xl w-full p-8 border border-slate-200 relative overflow-hidden animate-scale-up">
                <div class="flex items-center justify-between mb-6 border-b border-slate-100 pb-4">
                    <div>
                        <span class="text-[10px] font-bold text-purple-600 uppercase">{{ viewingEngagementRef?.type_engagement?.nom }}</span>
                        <h3 class="text-lg font-extrabold text-slate-850">Acte {{ viewingEngagementRef?.reference }}</h3>
                    </div>
                    <button @click="closeViewContentModal" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-all hover:rotate-90">
                        <svg class="w-4 h-4 text-slate-550" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Generated Document Viewer (Simulating a formal paper print) -->
                <div class="bg-slate-50 border border-slate-200/60 rounded-2xl p-6 md:p-8 max-h-[50vh] overflow-y-auto scrollbar-thin shadow-inner font-serif text-slate-850 text-sm leading-relaxed whitespace-pre-line" v-html="viewingEngagementRef?.content"></div>

                <div class="flex justify-end gap-3 mt-6">
                    <button @click="closeViewContentModal" class="px-6 py-2.5 bg-slate-150 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition-all">Fermer</button>
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
                    <h3 class="text-xl font-extrabold text-slate-800 mb-2">Supprimer cet engagement ?</h3>
                    <p class="text-slate-500 text-sm mb-6">Cette action est définitive.</p>

                    <div class="flex gap-4">
                        <button @click="closeDeleteModal" class="flex-1 px-5 py-3.5 bg-slate-100 text-slate-650 rounded-xl font-bold hover:bg-slate-200 transition-all text-xs">Annuler</button>
                        <button @click="confirmDelete" :disabled="isDeleting" class="flex-1 px-5 py-3.5 bg-gradient-to-r from-red-500 to-rose-600 text-white rounded-xl font-bold shadow-lg shadow-red-500/30 hover:shadow-xl hover:shadow-red-500/40 transition-all hover:scale-[1.01] text-xs flex items-center justify-center gap-2">
                            <span v-if="isDeleting" class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                            <span>Supprimer</span>
                        </button>
                    </div>
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
                    <button @click="closeSuccess" class="w-full px-5 py-3.5 bg-gradient-to-r from-emerald-500 to-teal-650 text-white rounded-xl font-bold shadow-md shadow-emerald-500/20 hover:scale-[1.01] transition-all text-xs">Fermer</button>
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
import { ref, computed, onMounted, nextTick, watch } from 'vue';
import axios from 'axios';

const engagements = ref([]);
const typeEngagements = ref([]);
const agencies = ref([]);
const batiments = ref([]);
const contratsActifs = ref([]);

const searchQuery = ref('');
const typeFilter = ref('All');
const statusFilter = ref('All');
const agencyFilter = ref('All');
const buildingFilter = ref('All');

const showModal = ref(false);
const showEditorModal = ref(false);
const showViewContentModal = ref(false);
const showDeleteModal = ref(false);
const showSuccess = ref(false);
const showError = ref(false);
const editingEngagement = ref(null);
const successMessage = ref('');
const errorMessage = ref('');
const deleteTarget = ref(null);
const isGenerating = ref(false);
const isSaving = ref(false);
const isDeleting = ref(false);
const confirmingId = ref(null);
const isInitialLoading = ref(false);

const viewingEngagementRef = ref(null);
const editorContent = ref('');
const canevasTemplate = ref('');

const formData = ref({
    id: null,
    reference: '',
    type_engagement_id: '',
    agency_id: '',
    batiment_id: '',
    contrat_id: '',
    locataire_id: '',
    date_debut: '',
    date_fin: '',
    montant: '',
    instructions: '',
    content: ''
});

// Load database data
const fetchEngagementsData = async () => {
    isInitialLoading.value = true;
    try {
        const response = await axios.get('/api/engagements');
        engagements.value = response.data.engagements || [];
        typeEngagements.value = response.data.typeEngagements || [];
        agencies.value = response.data.agencies || [];
        batiments.value = response.data.batiments || [];
        contratsActifs.value = response.data.contrats || [];
    } catch (err) {
        console.error("Erreur de chargement des engagements:", err);
        errorMessage.value = "Une erreur est survenue lors de la récupération des données.";
        showError.value = true;
    } finally {
        isInitialLoading.value = false;
    }
};

onMounted(() => {
    fetchEngagementsData();
});

// 3-level cascade methods
const onAgencyChange = () => {
    formData.value.batiment_id = '';
    formData.value.contrat_id = '';
    formData.value.locataire_id = '';
};

const onBatimentChange = () => {
    formData.value.contrat_id = '';
    formData.value.locataire_id = '';
};

const onContratChange = () => {
    const selectedC = contratsActifs.value.find(c => c.id === formData.value.contrat_id);
    if (selectedC) {
        formData.value.locataire_id = selectedC.locataire_id;
    } else {
        formData.value.locataire_id = '';
    }
};

// Watch Type Engagement to pre-fill template
watch(() => formData.value.type_engagement_id, (newVal) => {
    const selectedType = typeEngagements.value.find(t => t.id === newVal);
    if (selectedType) {
        canevasTemplate.value = selectedType.template || '';
    } else {
        canevasTemplate.value = '';
    }
});

// Filter Buildings in filter panel based on selected Agency filter
const filterBuildings = computed(() => {
    if (agencyFilter.value === 'All') return batiments.value;
    return batiments.value.filter(b => b.agency_id === Number(agencyFilter.value));
});

// Reset Building Filter if Agency filter changes
watch(agencyFilter, () => {
    buildingFilter.value = 'All';
});

// Computed properties for add/edit modal cascades
const modalFilteredBatiments = computed(() => {
    if (!formData.value.agency_id) return [];
    return batiments.value.filter(b => b.agency_id === formData.value.agency_id);
});

const modalFilteredContrats = computed(() => {
    if (!formData.value.batiment_id) return [];
    return contratsActifs.value.filter(c => c.batiment_id === formData.value.batiment_id);
});

// Table & KPI filtering
const filteredEngagements = computed(() => {
    let result = engagements.value;

    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        result = result.filter(e => 
            e.reference.toLowerCase().includes(query) ||
            (e.locataire && e.locataire.nom.toLowerCase().includes(query))
        );
    }

    if (typeFilter.value !== 'All') {
        result = result.filter(e => e.type_engagement_id === Number(typeFilter.value));
    }

    if (statusFilter.value !== 'All') {
        result = result.filter(e => e.statut === statusFilter.value);
    }

    if (agencyFilter.value !== 'All') {
        result = result.filter(e => e.agency_id === Number(agencyFilter.value));
    }

    if (buildingFilter.value !== 'All') {
        result = result.filter(e => e.batiment_id === Number(buildingFilter.value));
    }

    return result;
});

// KPIs recalculated dynamically based on filters
const totalEngagements = computed(() => filteredEngagements.value.length);
const actifs = computed(() => filteredEngagements.value.filter(e => e.statut === 'actif').length);
const enCours = computed(() => filteredEngagements.value.filter(e => e.statut === 'en cours').length);
const expires = computed(() => filteredEngagements.value.filter(e => e.statut === 'expirer').length);

const openAddModal = () => {
    editingEngagement.value = null;
    formData.value = {
        id: null,
        reference: `ENG-${new Date().getFullYear()}-${String(engagements.value.length + 1).padStart(3, '0')}`,
        type_engagement_id: '',
        agency_id: '',
        batiment_id: '',
        contrat_id: '',
        locataire_id: '',
        date_debut: new Date().toISOString().split('T')[0],
        date_fin: '',
        montant: '',
        instructions: '',
        content: ''
    };
    editorContent.value = '';
    canevasTemplate.value = '';
    showModal.value = true;
};

const editEngagement = (engagement) => {
    editingEngagement.value = engagement;
    formData.value = {
        id: engagement.id,
        reference: engagement.reference,
        type_engagement_id: engagement.type_engagement_id,
        agency_id: engagement.agency_id || '',
        batiment_id: engagement.batiment_id,
        contrat_id: engagement.contrat_id,
        locataire_id: engagement.locataire_id,
        date_debut: engagement.date_debut,
        date_fin: engagement.date_fin,
        montant: engagement.montant,
        instructions: engagement.instructions || '',
        content: engagement.content || ''
    };
    editorContent.value = engagement.content || '';
    const selectedType = typeEngagements.value.find(t => t.id === engagement.type_engagement_id);
    canevasTemplate.value = selectedType ? (selectedType.template || '') : '';
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editingEngagement.value = null;
};

// TinyMCE setup
const initTinyMCE = () => {
    nextTick(() => {
        if (typeof tinymce !== 'undefined') {
            tinymce.init({
                selector: '#engagement-editor',
                height: 450,
                menubar: false,
                plugins: 'lists link image code table wordcount',
                toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | code',
                setup: (editor) => {
                    editor.on('init', () => {
                        editor.setContent(editorContent.value);
                    });
                    editor.on('change keyup', () => {
                        editorContent.value = editor.getContent();
                    });
                }
            });
        }
    });
};

const openManualEditor = () => {
    if (!formData.value.contrat_id) {
        alert("Veuillez d'abord sélectionner le locataire concerné.");
        return;
    }

    if (!editorContent.value) {
        editorContent.value = buildInitialEngagementFromTemplate();
    }
    
    showEditorModal.value = true;
    nextTick(() => {
        initTinyMCE();
    });
};

const buildInitialEngagementFromTemplate = () => {
    let tpl = canevasTemplate.value || '';
    const contract = contratsActifs.value.find(c => c.id === formData.value.contrat_id);
    const tenantName = contract ? contract.locataire : 'Non spécifié';
    const typeObj = typeEngagements.value.find(t => t.id === formData.value.type_engagement_id);
    const typeName = typeObj ? typeObj.nom : 'Non spécifié';

    tpl = tpl.replace(/\[Partie\]/g, tenantName);
    tpl = tpl.replace(/\[Montant\]/g, formatCurrency(formData.value.montant) || 'Non spécifié');
    tpl = tpl.replace(/\[Date Début\]/g, formatDate(formData.value.date_debut) || 'Non spécifiée');
    tpl = tpl.replace(/\[Date Fin\]/g, formatDate(formData.value.date_fin) || 'Non spécifiée');
    tpl = tpl.replace(/\[Type\]/g, typeName);
    tpl = tpl.replace(/\[Date\]/g, getTodayDate());
    return tpl.replace(/\n/g, '<br>');
};

const generateWithIA = async () => {
    if (!formData.value.contrat_id) {
        alert("Veuillez d'abord sélectionner le locataire concerné.");
        return;
    }

    isGenerating.value = true;
    try {
        const contract = contratsActifs.value.find(c => c.id === formData.value.contrat_id);
        const tenantName = contract ? contract.locataire : 'Non spécifié';
        const typeObj = typeEngagements.value.find(t => t.id === formData.value.type_engagement_id);
        const typeName = typeObj ? typeObj.nom : 'Non spécifié';

        const payload = {
            partie: tenantName,
            montant: formData.value.montant,
            type: typeName,
            dateDebut: formData.value.date_debut,
            dateFin: formData.value.date_fin,
            template: canevasTemplate.value,
            instructions: formData.value.instructions,
        };

        const response = await axios.post(route('ai.generate-engagement'), payload);
        if (response.data && response.data.success) {
            editorContent.value = response.data.engagement;
            showEditorModal.value = true;
            nextTick(() => {
                initTinyMCE();
            });
        } else {
            errorMessage.value = response.data.message || "Impossible de générer le document.";
            showError.value = true;
        }
    } catch (e) {
        console.error(e);
        errorMessage.value = e.response?.data?.message || "Une erreur s'est produite lors de l'appel de l'API Gemini.";
        showError.value = true;
    } finally {
        isGenerating.value = false;
    }
};

const confirmRedaction = () => {
    const editor = tinymce.get('engagement-editor');
    if (editor) {
        editorContent.value = editor.getContent();
        tinymce.remove(editor);
    }
    showEditorModal.value = false;
    saveAndPersist();
};

const closeEditorModal = () => {
    const editor = tinymce.get('engagement-editor');
    if (editor) {
        tinymce.remove(editor);
    }
    showEditorModal.value = false;
};

const saveAndPersist = async () => {
    isSaving.value = true;
    try {
        const payload = {
            reference: formData.value.reference,
            type_engagement_id: formData.value.type_engagement_id,
            agency_id: formData.value.agency_id || null,
            batiment_id: formData.value.batiment_id,
            contrat_id: formData.value.contrat_id,
            locataire_id: formData.value.locataire_id,
            date_debut: formData.value.date_debut,
            date_fin: formData.value.date_fin,
            montant: formData.value.montant || 0,
            instructions: formData.value.instructions || '',
            content: editorContent.value || ''
        };

        if (editingEngagement.value) {
            await axios.put(`/api/engagements/${editingEngagement.value.id}`, payload);
            successMessage.value = "Engagement mis à jour avec succès.";
        } else {
            await axios.post('/api/engagements', payload);
            successMessage.value = "Engagement enregistré et archivé avec succès.";
        }

        await fetchEngagementsData();
        closeModal();
        showSuccess.value = true;
    } catch (err) {
        console.error(err);
        errorMessage.value = err.response?.data?.message || "Erreur lors de l'enregistrement de l'engagement.";
        showError.value = true;
    } finally {
        isSaving.value = false;
    }
};

const confirmHonor = async (engagement) => {
    confirmingId.value = engagement.id;
    try {
        await axios.post(`/api/engagements/${engagement.id}/confirm-honor`);
        await fetchEngagementsData();
        successMessage.value = "L'engagement a été validé et honoré avec succès !";
        showSuccess.value = true;
    } catch (err) {
        console.error(err);
        errorMessage.value = err.response?.data?.message || "Erreur lors de la confirmation de l'engagement.";
        showError.value = true;
    } finally {
        confirmingId.value = null;
    }
};

const viewEngagement = (eng) => {
    viewingEngagementRef.value = eng;
    showViewContentModal.value = true;
};

const closeViewContentModal = () => {
    showViewContentModal.value = false;
    viewingEngagementRef.value = null;
};

const deleteEngagement = (engagement) => {
    deleteTarget.value = engagement;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    deleteTarget.value = null;
};

const confirmDelete = async () => {
    if (!deleteTarget.value) return;
    isDeleting.value = true;
    try {
        await axios.delete(`/api/engagements/${deleteTarget.value.id}`);
        await fetchEngagementsData();
        successMessage.value = "Engagement supprimé avec succès.";
        closeDeleteModal();
        showSuccess.value = true;
    } catch (err) {
        console.error(err);
        errorMessage.value = err.response?.data?.message || "Erreur lors de la suppression de l'engagement.";
        showError.value = true;
    } finally {
        isDeleting.value = false;
    }
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

const formatDate = (dateStr) => {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return new Intl.DateTimeFormat('fr-FR', { day: '2-digit', month: '2-digit', year: 'numeric' }).format(date);
};

const getTodayDate = () => {
    return new Intl.DateTimeFormat('fr-FR', { day: '2-digit', month: '2-digit', year: 'numeric' }).format(new Date());
};

const getInitials = (name) => {
    if (!name) return 'EG';
    const parts = name.trim().split(' ');
    if (parts.length >= 2) {
        return (parts[0][0] + parts[1][0]).toUpperCase();
    }
    return name.substring(0, 2).toUpperCase();
};

const getAvatarGradient = (name) => {
    if (!name) return 'from-purple-500 to-pink-650';
    const colors = [
        'from-purple-550 to-pink-600',
        'from-fuchsia-500 to-rose-600',
        'from-indigo-500 to-purple-600',
        'from-violet-500 to-pink-600',
        'from-rose-500 to-red-600'
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

.bg-slate-55 {
    background-color: #f8fafc;
}
</style>
