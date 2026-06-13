<template>
    <div class="flex flex-col gap-8 select-none">
        
        <!-- Header with Luxury Teal Banner -->
        <div class="bg-gradient-to-br from-slate-900 via-slate-950 to-slate-900 rounded-3xl p-8 border border-slate-800 shadow-2xl text-white relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-48 h-48 rounded-full bg-teal-500/10 blur-2xl pointer-events-none"></div>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative z-10">
                <div>
                    <span class="text-teal-400 text-xs font-bold tracking-widest uppercase bg-teal-500/20 px-3 py-1 rounded-full border border-teal-500/30">Inspection</span>
                    <h1 class="text-3xl font-extrabold bg-gradient-to-r from-white via-slate-100 to-slate-300 bg-clip-text text-transparent mt-2">États des Lieux</h1>
                    <p class="text-slate-400 text-sm mt-1">Planifiez, réalisez et archivez les procès-verbaux d'entrée et de sortie des logements.</p>
                </div>
                <button
                    @click="openAddModal"
                    class="px-5 py-3 rounded-2xl bg-teal-600 hover:bg-teal-500 border border-teal-500/50 hover:border-teal-400 text-white font-bold text-xs transition-all duration-300 transform hover:scale-[1.02] flex items-center gap-2 shadow-lg shadow-teal-600/35"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nouvel État des Lieux
                </button>
            </div>
        </div>

        <!-- Premium KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-teal-200/30 transition-all duration-500 hover:-translate-y-1 border border-slate-150 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-teal-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-teal-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-slate-800 mb-1 tracking-tight">{{ totalEtats }}</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total États</div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-emerald-200/30 transition-all duration-500 hover:-translate-y-1 border border-slate-150 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-emerald-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-emerald-600 mb-1 tracking-tight">{{ entrees }}</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">États d'Entrée</div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-amber-200/30 transition-all duration-500 hover:-translate-y-1 border border-slate-150 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-amber-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-amber-600 mb-1 tracking-tight">{{ sorties }}</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">États de Sortie</div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-violet-200/30 transition-all duration-500 hover:-translate-y-1 border border-slate-150 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-violet-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-violet-500 to-purple-655 rounded-xl flex items-center justify-center shadow-lg shadow-violet-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-violet-600 mb-1 tracking-tight">{{ enAttente }}</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">En Attente</div>
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
                    placeholder="Rechercher par logement, locataire, réf..."
                    class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-2xl focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-sm font-semibold transition-all shadow-sm"
                />
            </div>
            <div class="flex items-center gap-4 w-full md:w-auto">
                <!-- Building Filter -->
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold uppercase text-slate-400">Bâtiment :</span>
                    <select v-model="buildingFilter" class="bg-white border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:outline-none shadow-sm" style="color: #000000 !important; background-color: #ffffff !important;">
                        <option value="All" style="color: #000000 !important; background-color: #ffffff !important;">Tous les bâtiments</option>
                        <option v-for="b in batiments" :key="b.id" :value="b.id" style="color: #000000 !important; background-color: #ffffff !important;">{{ b.nom }}</option>
                    </select>
                </div>
                <!-- Type Filter -->
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold uppercase text-slate-400">Type :</span>
                    <select v-model="typeFilter" class="bg-white border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:outline-none shadow-sm" style="color: #000000 !important; background-color: #ffffff !important;">
                        <option value="All" style="color: #000000 !important; background-color: #ffffff !important;">Tous les types</option>
                        <option v-for="t in typeEtatDesLieux" :key="t.id" :value="t.id" style="color: #000000 !important; background-color: #ffffff !important;">{{ t.nom }}</option>
                    </select>
                </div>
                <!-- Status Filter -->
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold uppercase text-slate-400">Statut :</span>
                    <select v-model="statusFilter" class="bg-white border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:outline-none shadow-sm" style="color: #000000 !important; background-color: #ffffff !important;">
                        <option value="All" style="color: #000000 !important; background-color: #ffffff !important;">Tous les statuts</option>
                        <option value="Brouillon" style="color: #000000 !important; background-color: #ffffff !important;">Brouillon</option>
                        <option value="En attente" style="color: #000000 !important; background-color: #ffffff !important;">En attente</option>
                        <option value="Validé" style="color: #000000 !important; background-color: #ffffff !important;">Validé</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="isInitialLoading" class="flex justify-center items-center py-12">
            <span class="animate-spin h-10 w-10 border-4 border-teal-500 border-t-transparent rounded-full"></span>
        </div>

        <!-- Table Section -->
        <div v-else class="bg-gradient-to-br from-white to-slate-50 rounded-3xl shadow-xl shadow-slate-200/50 overflow-hidden border border-slate-150">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200/50 bg-slate-50/50">
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Référence</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Logement</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Locataire</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-150">
                        <tr v-for="etat in filteredEtats" :key="etat.id" class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 text-sm font-bold text-slate-850">{{ etat.reference }}</td>
                            <td class="px-6 py-4 text-sm text-slate-700 font-bold">{{ etat.logement?.reference || 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div :class="['w-9 h-9 rounded-xl flex items-center justify-center text-white font-extrabold text-xs shadow-sm bg-gradient-to-br', getAvatarGradient(etat.locataire?.nom)]">
                                        {{ getInitials(etat.locataire?.nom) }}
                                    </div>
                                    <div class="font-bold text-slate-800 text-sm">{{ etat.locataire?.nom || 'N/A' }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700 font-bold">
                                {{ etat.type_etat_des_lieux?.nom || 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 font-semibold">{{ formatDate(etat.date) }}</td>
                            <td class="px-6 py-4">
                                <span :class="[
                                    'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border',
                                    etat.statut === 'Validé' ? 'bg-emerald-50 text-emerald-700 border-emerald-250' :
                                    etat.statut === 'En attente' ? 'bg-violet-50 text-violet-750 border-violet-200' :
                                    'bg-slate-550 text-slate-600 border-slate-200'
                                ]">
                                    <span :class="['w-1.5 h-1.5 rounded-full',
                                        etat.statut === 'Validé' ? 'bg-emerald-500' :
                                        etat.statut === 'En attente' ? 'bg-violet-500 animate-pulse' :
                                        'bg-slate-550'
                                    ]"></span>
                                    {{ etat.statut }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <button 
                                        v-if="etat.content"
                                        @click="viewEtatContent(etat)" 
                                        class="p-2 text-slate-655 hover:bg-slate-100 rounded-xl transition-all"
                                        title="Voir le document"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    <button 
                                        @click="printEtat(etat)" 
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-xl transition-all"
                                        title="Imprimer"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                    </button>
                                    <button 
                                        @click="editEtat(etat)" 
                                        class="p-2 text-teal-600 hover:bg-teal-50 rounded-xl transition-all"
                                        title="Modifier"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <!-- NO DELETE BUTTON (ONLY SEATS CAN DELETE ENGAGEMENTS OR ETATS DES LIEUX) -->
                                </div>
                            </td>
                        </tr>
                        <tr v-if="filteredEtats.length === 0">
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400 font-semibold text-sm">
                                Aucun état des lieux trouvé.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add/Edit Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-3xl w-full p-8 border border-slate-200 relative overflow-hidden animate-scale-up">
                
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-2xl bg-teal-600 flex items-center justify-center shadow-md">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h2 class="text-2xl font-extrabold text-slate-800">
                                {{ formData.id ? 'Modifier' : 'Nouvel' }} État des Lieux
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
                                <input 
                                    v-model="formData.reference" 
                                    type="text" 
                                    placeholder="Ex: EDL-2026-004" 
                                    class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all font-semibold text-slate-800 text-sm shadow-sm"
                                />
                            </div>

                            <!-- Type -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Type d'État *</label>
                                <select 
                                    v-model="formData.type_etat_des_lieux_id" 
                                    required
                                    class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all font-semibold text-slate-800 text-sm shadow-sm"
                                    style="color: #000000 !important; background-color: #ffffff !important;"
                                >
                                    <option value="" disabled style="color: #000000 !important; background-color: #ffffff !important;">Sélectionner le type</option>
                                    <option v-for="t in typeEtatDesLieux" :key="t.id" :value="t.id" style="color: #000000 !important; background-color: #ffffff !important;">{{ t.nom }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- 2-level cascade selection (Building -> Tenant) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Building Select -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Bâtiment *</label>
                                <select 
                                    v-model="formData.batiment_id" 
                                    required
                                    class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all font-semibold text-slate-800 text-sm shadow-sm"
                                    @change="onBatimentChange"
                                    style="color: #000000 !important; background-color: #ffffff !important;"
                                >
                                    <option value="" disabled style="color: #000000 !important; background-color: #ffffff !important;">Sélectionner un bâtiment</option>
                                    <option v-for="b in batiments" :key="b.id" :value="b.id" style="color: #000000 !important; background-color: #ffffff !important;">{{ b.nom }}</option>
                                </select>
                            </div>

                            <!-- Tenant Select -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Locataire *</label>
                                <select 
                                    v-model="formData.locataire_id" 
                                    required
                                    :disabled="!formData.batiment_id"
                                    class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all font-semibold text-slate-800 text-sm shadow-sm disabled:opacity-50"
                                    @change="onLocataireChange"
                                    style="color: #000000 !important; background-color: #ffffff !important;"
                                >
                                    <option value="" disabled style="color: #000000 !important; background-color: #ffffff !important;">Sélectionner le locataire</option>
                                    <option v-for="loc in modalFilteredLocataires" :key="loc.id" :value="loc.id" style="color: #000000 !important; background-color: #ffffff !important;">
                                        {{ loc.nom }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Conditional Contract Selector & Auto Logement -->
                        <div v-if="formData.locataire_id" class="grid grid-cols-1 md:grid-cols-2 gap-4 animate-scale-up">
                            <!-- Contract Select -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Contrat Associé *</label>
                                <select 
                                    v-model="formData.contrat_id" 
                                    required
                                    class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all font-semibold text-slate-800 text-sm shadow-sm"
                                    @change="onContratChange"
                                    style="color: #000000 !important; background-color: #ffffff !important;"
                                >
                                    <option value="" disabled style="color: #000000 !important; background-color: #ffffff !important;">Sélectionner le contrat</option>
                                    <option v-for="c in modalFilteredContrats" :key="c.id" :value="c.id" style="color: #000000 !important; background-color: #ffffff !important;">
                                        Bail N° {{ c.numero }} ({{ c.statut }})
                                    </option>
                                </select>
                            </div>

                            <!-- Logement (Auto Filled) -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Logement / Local</label>
                                <input 
                                    v-model="logementRefDisplay" 
                                    type="text" 
                                    placeholder="Logement automatiquement détecté" 
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-500 text-sm focus:outline-none cursor-not-allowed shadow-sm" 
                                    readonly
                                />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Date -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Date d'Inspection *</label>
                                <input v-model="formData.date" type="date" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all font-semibold text-slate-800 text-sm shadow-sm" />
                            </div>
                            <!-- Statut -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Statut *</label>
                                <select v-model="formData.statut" required class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all font-semibold text-slate-800 text-sm shadow-sm" style="color: #000000 !important; background-color: #ffffff !important;">
                                    <option value="Brouillon" style="color: #000000 !important; background-color: #ffffff !important;">Brouillon</option>
                                    <option value="En attente" style="color: #000000 !important; background-color: #ffffff !important;">En attente</option>
                                    <option value="Validé" style="color: #000000 !important; background-color: #ffffff !important;">Validé</option>
                                </select>
                            </div>
                        </div>

                        <!-- Instructions IA -->
                        <div class="group">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Consignes additionnelles ou anomalies observées (IA)</label>
                            <textarea 
                                v-model="formData.instructions" 
                                placeholder="Indiquez ici les pièces spécifiques, meubles, ou anomalies de peinture, robinetterie à notifier..." 
                                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all font-medium text-slate-800 text-sm shadow-sm h-20"
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
                            class="px-4 py-3 bg-teal-600 hover:bg-teal-700 text-white rounded-xl font-bold shadow-md transition-all flex items-center justify-center gap-2 text-xs"
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

        <!-- Rich Text TinyMCE Editor Modal (Fullscreen) -->
        <div v-if="showEditorModal" class="fixed inset-0 bg-white z-50 flex flex-col p-6 animate-scale-up">
            <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-2xl font-extrabold text-slate-800">Éditeur de Procès-verbal d'État des Lieux</h3>
                    <p class="text-xs font-bold text-slate-400">Rédigez ou modifiez le contenu officiel du procès-verbal d'inspection</p>
                </div>
                <span class="text-xs font-bold text-teal-650 uppercase bg-teal-50 px-3 py-1 rounded-full border border-teal-100">Mode Plein Écran</span>
            </div>
            
            <div class="flex-1 min-h-0 mb-4 border border-slate-200 rounded-2xl overflow-hidden bg-slate-55 flex flex-col">
                <textarea id="etat-editor" class="flex-1"></textarea>
            </div>

            <div class="flex justify-end gap-4 border-t border-slate-150 pt-4">
                <button 
                    @click="closeEditorModal" 
                    class="px-6 py-3.5 bg-slate-150 text-slate-655 font-bold rounded-xl hover:bg-slate-200 transition-all text-xs"
                >
                    Fermer sans enregistrer
                </button>
                <button 
                    @click="confirmRedaction" 
                    :disabled="isSaving"
                    class="px-6 py-3.5 bg-gradient-to-r from-teal-500 to-emerald-650 text-white font-bold rounded-xl shadow-lg shadow-teal-500/20 hover:shadow-xl hover:shadow-teal-500/35 transition-all text-xs flex items-center gap-2"
                >
                    <span v-if="isSaving" class="w-3.5 h-3.5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                    <span>Valider & Enregistrer l'Acte</span>
                </button>
            </div>
        </div>

        <!-- View Content Modal -->
        <div v-if="showViewContentModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-3xl w-full p-8 border border-slate-200 relative overflow-hidden animate-scale-up">
                <div class="flex items-center justify-between mb-6 border-b border-slate-100 pb-4">
                    <div>
                        <span class="text-[10px] font-bold text-teal-600 uppercase">{{ viewingEtatRef?.type_etat_des_lieux?.nom }}</span>
                        <h3 class="text-lg font-extrabold text-slate-855">Procès-verbal {{ viewingEtatRef?.reference }}</h3>
                    </div>
                    <button @click="closeViewContentModal" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-all hover:rotate-90">
                        <svg class="w-4 h-4 text-slate-550" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Document Viewer -->
                <div class="bg-slate-50 border border-slate-200/60 rounded-2xl p-6 md:p-8 max-h-[50vh] overflow-y-auto scrollbar-thin shadow-inner font-serif text-slate-855 text-sm leading-relaxed whitespace-pre-line" v-html="viewingEtatRef?.content"></div>

                <div class="flex justify-end gap-3 mt-6">
                    <button @click="printEtat(viewingEtatRef)" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs transition-all flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Imprimer
                    </button>
                    <button @click="closeViewContentModal" class="px-6 py-2.5 bg-slate-150 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition-all">Fermer</button>
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

const etats = ref([]);
const typeEtatDesLieux = ref([]);
const batiments = ref([]);
const locataires = ref([]);

const searchQuery = ref('');
const typeFilter = ref('All');
const statusFilter = ref('All');
const buildingFilter = ref('All');

const showModal = ref(false);
const showEditorModal = ref(false);
const showViewContentModal = ref(false);
const showSuccess = ref(false);
const showError = ref(false);
const successMessage = ref('');
const errorMessage = ref('');
const isGenerating = ref(false);
const isSaving = ref(false);
const isInitialLoading = ref(false);

const viewingEtatRef = ref(null);
const editorContent = ref('');
const canevasTemplate = ref('');

const formData = ref({
    id: null,
    reference: '',
    type_etat_des_lieux_id: '',
    batiment_id: '',
    locataire_id: '',
    contrat_id: '',
    logement_id: '',
    date: '',
    statut: 'Brouillon',
    instructions: '',
    content: ''
});

// Display of associated logement reference
const logementRefDisplay = computed(() => {
    if (!formData.value.contrat_id || !formData.value.locataire_id) return '';
    const selectedLoc = locataires.value.find(l => l.id === formData.value.locataire_id);
    if (!selectedLoc || !selectedLoc.contrats) return '';
    const contract = selectedLoc.contrats.find(c => c.id === formData.value.contrat_id);
    return contract && contract.logement ? contract.logement.reference : '';
});

// Load database data
const fetchEtatsData = async () => {
    isInitialLoading.value = true;
    try {
        const response = await axios.get('/api/etat-des-lieux');
        etats.value = response.data.etats || [];
        typeEtatDesLieux.value = response.data.typeEtatDesLieux || [];
        batiments.value = response.data.batiments || [];
        locataires.value = response.data.locataires || [];
    } catch (err) {
        console.error("Erreur de chargement des états des lieux:", err);
        errorMessage.value = "Une erreur est survenue lors de la récupération des données.";
        showError.value = true;
    } finally {
        isInitialLoading.value = false;
    }
};

onMounted(() => {
    fetchEtatsData();
});

// 2-level cascade methods
const onBatimentChange = () => {
    formData.value.locataire_id = '';
    formData.value.contrat_id = '';
    formData.value.logement_id = '';
};

const onLocataireChange = () => {
    formData.value.contrat_id = '';
    formData.value.logement_id = '';
};

const onContratChange = () => {
    const selectedLoc = locataires.value.find(l => l.id === formData.value.locataire_id);
    if (!selectedLoc || !selectedLoc.contrats) {
        formData.value.logement_id = '';
        return;
    }
    const contract = selectedLoc.contrats.find(c => c.id === formData.value.contrat_id);
    if (contract) {
        formData.value.logement_id = contract.logement_id;
    } else {
        formData.value.logement_id = '';
    }
};

// Watch Type d'État to pre-fill template
watch(() => formData.value.type_etat_des_lieux_id, (newVal) => {
    const selectedType = typeEtatDesLieux.value.find(t => t.id === newVal);
    if (selectedType) {
        canevasTemplate.value = selectedType.template || '';
    } else {
        canevasTemplate.value = '';
    }
});

// Computed properties for add/edit modal cascades
const modalFilteredLocataires = computed(() => {
    if (!formData.value.batiment_id) return [];
    return locataires.value.filter(loc => {
        return loc.contrats && loc.contrats.some(c => c.logement && c.logement.batiment_id === formData.value.batiment_id);
    });
});

const modalFilteredContrats = computed(() => {
    if (!formData.value.locataire_id || !formData.value.batiment_id) return [];
    const selectedLoc = locataires.value.find(l => l.id === formData.value.locataire_id);
    if (!selectedLoc || !selectedLoc.contrats) return [];
    return selectedLoc.contrats.filter(c => c.logement && c.logement.batiment_id === formData.value.batiment_id);
});

// KPIs
const totalEtats = computed(() => etats.value.length);

const entrees = computed(() => {
    return etats.value.filter(e => e.type_etat_des_lieux?.nom?.toLowerCase().includes('entrée')).length;
});

const sorties = computed(() => {
    return etats.value.filter(e => e.type_etat_des_lieux?.nom?.toLowerCase().includes('sortie')).length;
});

const enAttente = computed(() => {
    return etats.value.filter(e => e.statut === 'En attente').length;
});

// Main filtered inspections list
const filteredEtats = computed(() => {
    let result = etats.value;

    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        result = result.filter(e => 
            e.reference.toLowerCase().includes(query) ||
            (e.locataire?.nom && e.locataire.nom.toLowerCase().includes(query)) ||
            (e.logement?.reference && e.logement.reference.toLowerCase().includes(query))
        );
    }

    if (buildingFilter.value !== 'All') {
        result = result.filter(e => e.batiment_id === Number(buildingFilter.value));
    }

    if (typeFilter.value !== 'All') {
        result = result.filter(e => e.type_etat_des_lieux_id === Number(typeFilter.value));
    }

    if (statusFilter.value !== 'All') {
        result = result.filter(e => e.statut === statusFilter.value);
    }

    return result;
});

// TinyMCE setup
const initTinyMCE = () => {
    nextTick(() => {
        if (typeof tinymce !== 'undefined') {
            tinymce.init({
                selector: '#etat-editor',
                height: '65vh',
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
        alert("Veuillez d'abord sélectionner le locataire et son contrat.");
        return;
    }

    if (!editorContent.value) {
        editorContent.value = buildInitialContentFromTemplate();
    }
    
    showEditorModal.value = true;
    nextTick(() => {
        initTinyMCE();
    });
};

const buildInitialContentFromTemplate = () => {
    let tpl = canevasTemplate.value || '';
    const selectedLoc = locataires.value.find(l => l.id === formData.value.locataire_id);
    const tenantName = selectedLoc ? selectedLoc.nom : 'Non spécifié';
    const selectedContrat = modalFilteredContrats.value.find(c => c.id === formData.value.contrat_id);
    const contractNum = selectedContrat ? selectedContrat.numero : 'Non spécifié';
    const unitRef = selectedContrat && selectedContrat.logement ? selectedContrat.logement.reference : 'Non spécifié';
    const typeObj = typeEtatDesLieux.value.find(t => t.id === formData.value.type_etat_des_lieux_id);
    const typeName = typeObj ? typeObj.nom : 'Non spécifié';

    tpl = tpl.replace(/\[Locataire\]/g, tenantName);
    tpl = tpl.replace(/\[Logement\]/g, unitRef);
    tpl = tpl.replace(/\[Contrat\]/g, contractNum);
    tpl = tpl.replace(/\[Type\]/g, typeName);
    tpl = tpl.replace(/\[Date\]/g, formatDate(formData.value.date) || 'Non spécifiée');
    return tpl.replace(/\n/g, '<br>');
};

const generateWithIA = async () => {
    if (!formData.value.contrat_id) {
        alert("Veuillez d'abord sélectionner le locataire et son contrat.");
        return;
    }

    isGenerating.value = true;
    try {
        const selectedLoc = locataires.value.find(l => l.id === formData.value.locataire_id);
        const tenantName = selectedLoc ? selectedLoc.nom : 'Non spécifié';
        const selectedContrat = modalFilteredContrats.value.find(c => c.id === formData.value.contrat_id);
        const unitRef = selectedContrat && selectedContrat.logement ? selectedContrat.logement.reference : 'Non spécifié';
        const typeObj = typeEtatDesLieux.value.find(t => t.id === formData.value.type_etat_des_lieux_id);
        const typeName = typeObj ? typeObj.nom : 'Non spécifié';

        const payload = {
            locataire: tenantName,
            logement: unitRef,
            type: typeName,
            date: formData.value.date,
            template: canevasTemplate.value,
            instructions: formData.value.instructions,
        };

        const response = await axios.post('/api/ai/generate-etat-des-lieux', payload);
        if (response.data && response.data.success) {
            editorContent.value = response.data.etat;
            showEditorModal.value = true;
            nextTick(() => {
                initTinyMCE();
            });
        } else {
            alert("Une erreur est survenue lors de la génération IA.");
        }
    } catch (err) {
        console.error(err);
        alert("Erreur de communication avec l'assistant IA.");
    } finally {
        isGenerating.value = false;
    }
};

const closeEditorModal = () => {
    const editor = typeof tinymce !== 'undefined' ? tinymce.get('etat-editor') : null;
    if (editor) {
        tinymce.remove(editor);
    }
    showEditorModal.value = false;
};

const confirmRedaction = () => {
    formData.value.content = editorContent.value;
    closeEditorModal();
};

const viewEtatContent = (etat) => {
    viewingEtatRef.value = etat;
    showViewContentModal.value = true;
};

const closeViewContentModal = () => {
    showViewContentModal.value = false;
    viewingEtatRef.value = null;
};

const printEtat = (etat) => {
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
        <head>
            <title>État des Lieux - ${etat.reference}</title>
            <style>
                body { font-family: 'Georgia', serif; padding: 40px; color: #1e293b; line-height: 1.6; }
                h1 { font-family: sans-serif; text-align: center; color: #0f172a; margin-bottom: 20px; font-weight: 800; }
                .meta { margin-bottom: 40px; border-bottom: 2px solid #e2e8f0; padding-bottom: 20px; font-family: sans-serif; font-size: 14px; }
                .meta table { width: 100%; border-collapse: collapse; }
                .meta td { padding: 8px 0; }
                .content { font-size: 15px; }
                @media print {
                    body { padding: 0; }
                }
            </style>
        </head>
        <body>
            <h1>PROCÈS-VERBAL D'ÉTAT DES LIEUX</h1>
            <div class="meta">
                <table>
                    <tr>
                        <td><strong>Référence :</strong> ${etat.reference}</td>
                        <td><strong>Date d'inspection :</strong> ${formatDate(etat.date)}</td>
                    </tr>
                    <tr>
                        <td><strong>Type :</strong> ${etat.type_etat_des_lieux?.nom || 'N/A'}</td>
                        <td><strong>Statut :</strong> ${etat.statut}</td>
                    </tr>
                    <tr>
                        <td><strong>Locataire :</strong> ${etat.locataire?.nom || 'N/A'}</td>
                        <td><strong>Logement :</strong> ${etat.logement?.reference || 'N/A'}</td>
                    </tr>
                    <tr>
                        <td><strong>Contrat :</strong> Bail N° ${etat.contrat?.numero || 'N/A'}</td>
                        <td><strong>Bâtiment :</strong> ${etat.batiment?.nom || 'N/A'}</td>
                    </tr>
                </table>
            </div>
            <div class="content">
                ${etat.content || '<em>Aucun contenu rédigé pour cet état des lieux.</em>'}
            </div>
            <script>
                window.onload = function() {
                    window.print();
                };
            <\/script>
        <\/body>
        <\/html>
    `);
    printWindow.document.close();
};

const openAddModal = () => {
    formData.value = {
        id: null,
        reference: `EDL-${new Date().getFullYear()}-${String(etats.value.length + 1).padStart(3, '0')}`,
        type_etat_des_lieux_id: '',
        batiment_id: '',
        locataire_id: '',
        contrat_id: '',
        logement_id: '',
        date: new Date().toISOString().split('T')[0],
        statut: 'Brouillon',
        instructions: '',
        content: ''
    };
    editorContent.value = '';
    canevasTemplate.value = '';
    showModal.value = true;
};

const editEtat = (etat) => {
    formData.value = {
        id: etat.id,
        reference: etat.reference,
        type_etat_des_lieux_id: etat.type_etat_des_lieux_id,
        batiment_id: etat.batiment_id || '',
        locataire_id: etat.locataire_id,
        contrat_id: etat.contrat_id,
        logement_id: etat.logement_id,
        date: etat.date ? new Date(etat.date).toISOString().split('T')[0] : '',
        statut: etat.statut,
        instructions: etat.instructions || '',
        content: etat.content || ''
    };
    editorContent.value = etat.content || '';
    const selectedType = typeEtatDesLieux.value.find(t => t.id === etat.type_etat_des_lieux_id);
    canevasTemplate.value = selectedType ? (selectedType.template || '') : '';
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const saveEtat = async () => {
    if (!formData.value.reference || !formData.value.type_etat_des_lieux_id || !formData.value.contrat_id || !formData.value.locataire_id || !formData.value.logement_id || !formData.value.date) {
        errorMessage.value = 'Veuillez remplir tous les champs obligatoires (*)';
        showError.value = true;
        return;
    }

    isSaving.value = true;
    try {
        if (formData.value.id) {
            await axios.put(`/api/etat-des-lieux/${formData.value.id}`, formData.value);
            successMessage.value = 'État des lieux modifié avec succès';
        } else {
            await axios.post('/api/etat-des-lieux', formData.value);
            successMessage.value = 'État des lieux enregistré avec succès';
        }
        await fetchEtatsData();
        closeModal();
        showSuccess.value = true;
    } catch (err) {
        console.error(err);
        errorMessage.value = "Erreur lors de l'enregistrement de l'état des lieux.";
        showError.value = true;
    } finally {
        isSaving.value = false;
    }
};

const closeSuccess = () => {
    showSuccess.value = false;
};

const closeError = () => {
    showError.value = false;
};

// Formatting & visual helpers
const formatDate = (dateStr) => {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return new Intl.DateTimeFormat('fr-FR', { day: '2-digit', month: '2-digit', year: 'numeric' }).format(date);
};

const getInitials = (name) => {
    if (!name) return 'ED';
    const parts = name.trim().split(' ');
    if (parts.length >= 2) {
        return (parts[0][0] + parts[1][0]).toUpperCase();
    }
    return name.substring(0, 2).toUpperCase();
};

const getAvatarGradient = (name) => {
    if (!name) return 'from-teal-500 to-cyan-655';
    const colors = [
        'from-teal-500 to-cyan-600',
        'from-emerald-500 to-teal-600',
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
