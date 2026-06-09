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
                    <div class="w-12 h-12 bg-gradient-to-br from-violet-500 to-purple-650 rounded-xl flex items-center justify-center shadow-lg shadow-violet-500/30">
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
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold uppercase text-slate-400">Type :</span>
                    <select v-model="typeFilter" class="bg-white border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:outline-none shadow-sm">
                        <option value="All">Tous les types</option>
                        <option value="Entrée">Entrée</option>
                        <option value="Sortie">Sortie</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold uppercase text-slate-400">Statut :</span>
                    <select v-model="statusFilter" class="bg-white border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:outline-none shadow-sm">
                        <option value="All">Tous les statuts</option>
                        <option value="Brouillon">Brouillon</option>
                        <option value="En attente">En attente</option>
                        <option value="Validé">Validé</option>
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
                        <tr v-for="role in filteredEtats" :key="role.id" class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 text-sm font-bold text-slate-850">{{ role.reference }}</td>
                            <td class="px-6 py-4 text-sm text-slate-700 font-bold">{{ role.logement }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div :class="['w-9 h-9 rounded-xl flex items-center justify-center text-white font-extrabold text-xs shadow-sm bg-gradient-to-br', getAvatarGradient(role.locataire)]">
                                        {{ getInitials(role.locataire) }}
                                    </div>
                                    <div class="font-bold text-slate-800 text-sm">{{ role.locataire }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span :class="[
                                    'inline-flex px-3 py-1 rounded-full text-xs font-bold border',
                                    role.type === 'Entrée' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200'
                                ]">
                                    {{ role.type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 font-semibold">{{ formatDate(role.date) }}</td>
                            <td class="px-6 py-4">
                                <span :class="[
                                    'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border',
                                    role.statut === 'Validé' ? 'bg-emerald-50 text-emerald-700 border-emerald-250' :
                                    role.statut === 'En attente' ? 'bg-violet-50 text-violet-750 border-violet-200' :
                                    'bg-slate-50 text-slate-600 border-slate-200'
                                ]">
                                    <span :class="['w-1.5 h-1.5 rounded-full',
                                        role.statut === 'Validé' ? 'bg-emerald-500' :
                                        role.statut === 'En attente' ? 'bg-violet-500 animate-pulse' :
                                        'bg-slate-550'
                                    ]"></span>
                                    {{ role.statut }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <button 
                                        @click="editEtat(role)" 
                                        class="p-2 text-teal-600 hover:bg-teal-50 rounded-xl transition-all"
                                        title="Modifier"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button 
                                        @click="deleteEtat(role)" 
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
                                {{ editingEtat ? 'Modifier' : 'Nouvel' }} État des Lieux
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

                            <!-- Active Lease Selection -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Contrat / Locataire Associé *</label>
                                <select 
                                    v-model="selectedContratNo" 
                                    @change="handleContratChange"
                                    class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all font-semibold text-slate-800 text-sm shadow-sm"
                                    :disabled="editingEtat"
                                >
                                    <option value="" disabled>Sélectionner un contrat actif</option>
                                    <option v-for="c in contratsActifs" :key="c.id" :value="c.numero">
                                        {{ c.numero }} - {{ c.locataire }} ({{ c.reference }})
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Logement (Auto) -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Logement</label>
                                <input 
                                    v-model="formData.logement" 
                                    type="text" 
                                    placeholder="Auto-rempli" 
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-500 text-sm focus:outline-none cursor-not-allowed shadow-sm" 
                                    readonly
                                />
                            </div>

                            <!-- Locataire (Auto) -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Locataire</label>
                                <input 
                                    v-model="formData.locataire" 
                                    type="text" 
                                    placeholder="Auto-rempli" 
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-500 text-sm focus:outline-none cursor-not-allowed shadow-sm" 
                                    readonly
                                />
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <!-- Type -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Type d'État *</label>
                                <select 
                                    v-model="formData.type" 
                                    class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all font-semibold text-slate-800 text-sm shadow-sm"
                                >
                                    <option value="Entrée">Entrée</option>
                                    <option value="Sortie">Sortie</option>
                                </select>
                            </div>

                            <!-- Date -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Date d'Inspection *</label>
                                <input 
                                    v-model="formData.date" 
                                    type="date" 
                                    class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all font-semibold text-slate-800 text-sm shadow-sm"
                                />
                            </div>

                            <!-- Statut -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Statut *</label>
                                <select 
                                    v-model="formData.statut" 
                                    class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all font-semibold text-slate-800 text-sm shadow-sm"
                                >
                                    <option value="Brouillon">Brouillon</option>
                                    <option value="En attente">En attente</option>
                                    <option value="Validé">Validé</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-4 mt-8 border-t border-slate-100 pt-5">
                        <button 
                            @click="closeModal" 
                            class="flex-1 px-5 py-3.5 bg-slate-100 text-slate-650 rounded-xl font-bold hover:bg-slate-200 transition-all text-xs"
                        >
                            Annuler
                        </button>
                        <button 
                            @click="saveEtat" 
                            class="flex-1 px-5 py-3.5 bg-teal-600 hover:bg-teal-750 text-white rounded-xl font-bold shadow-md transition-all text-xs"
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
                    <h3 class="text-xl font-extrabold text-slate-800 mb-2">Supprimer cet état des lieux ?</h3>
                    <p class="text-slate-500 text-sm mb-6">Cette action est définitive.</p>

                    <div class="flex gap-4">
                        <button @click="closeDeleteModal" class="flex-1 px-5 py-3.5 bg-slate-100 text-slate-650 rounded-xl font-bold hover:bg-slate-200 transition-all text-xs">Annuler</button>
                        <button @click="confirmDelete" class="flex-1 px-5 py-3.5 bg-gradient-to-r from-red-500 to-rose-600 text-white rounded-xl font-bold shadow-lg shadow-red-500/30 hover:shadow-xl hover:shadow-red-500/40 transition-all hover:scale-[1.01] text-xs">Supprimer</button>
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

const etats = ref([]);
const contratsActifs = ref([]);

const searchQuery = ref('');
const typeFilter = ref('All');
const statusFilter = ref('All');

const selectedContratNo = ref('');

onMounted(() => {
    // 1. Load inspections from localStorage
    const storedEtats = localStorage.getItem('immobilier_etats-des-lieux');
    if (storedEtats) {
        etats.value = JSON.parse(storedEtats);
    } else {
        etats.value = [
            { id: 1, reference: 'EDL-2026-001', logement: 'Apt 204', locataire: 'Jean Dupont', type: 'Entrée', date: '2025-07-01', statut: 'Validé' },
            { id: 2, reference: 'EDL-2026-002', logement: 'Bureau 102', locataire: 'Marie Lambert', type: 'Entrée', date: '2025-07-16', statut: 'Validé' },
            { id: 3, reference: 'EDL-2026-003', logement: 'Apt 305', locataire: 'Pierre Martin', type: 'Entrée', date: '2025-06-01', statut: 'Brouillon' }
        ];
        localStorage.setItem('immobilier_etats-des-lieux', JSON.stringify(etats.value));
    }

    // 2. Load active contracts
    const storedContrats = localStorage.getItem('immobilier_contrats');
    if (storedContrats) {
        contratsActifs.value = JSON.parse(storedContrats).filter(c => c.statut === 'Actif');
    }
});

const filteredEtats = computed(() => {
    let result = etats.value;

    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        result = result.filter(e => 
            e.reference.toLowerCase().includes(query) ||
            e.logement.toLowerCase().includes(query) ||
            e.locataire.toLowerCase().includes(query)
        );
    }

    if (typeFilter.value !== 'All') {
        result = result.filter(e => e.type === typeFilter.value);
    }

    if (statusFilter.value !== 'All') {
        result = result.filter(e => e.statut === statusFilter.value);
    }

    return result;
});

const totalEtats = computed(() => etats.value.length);
const entrees = computed(() => etats.value.filter(e => e.type === 'Entrée').length);
const sorties = computed(() => etats.value.filter(e => e.type === 'Sortie').length);
const enAttente = computed(() => etats.value.filter(e => e.statut === 'En attente').length);

const showModal = ref(false);
const showDeleteModal = ref(false);
const showSuccess = ref(false);
const showError = ref(false);
const editingEtat = ref(null);
const successMessage = ref('');
const errorMessage = ref('');
const deleteTarget = ref(null);

const formData = ref({
    reference: '',
    logement: '',
    locataire: '',
    type: 'Entrée',
    date: '',
    statut: 'Brouillon'
});

const openAddModal = () => {
    editingEtat.value = null;
    selectedContratNo.value = '';
    formData.value = {
        reference: `EDL-${new Date().getFullYear()}-${String(etats.value.length + 1).padStart(3, '0')}`,
        logement: '',
        locataire: '',
        type: 'Entrée',
        date: new Date().toISOString().split('T')[0],
        statut: 'Brouillon'
    };
    showModal.value = true;
};

const handleContratChange = () => {
    const selected = contratsActifs.value.find(c => c.numero === selectedContratNo.value);
    if (selected) {
        formData.value.logement = selected.reference;
        formData.value.locataire = selected.locataire;
    }
};

const editEtat = (etat) => {
    editingEtat.value = etat;
    formData.value = { ...etat };
    // Find associated contract
    const matchingContrat = contratsActifs.value.find(c => c.locataire === etat.locataire && c.reference === etat.logement);
    selectedContratNo.value = matchingContrat ? matchingContrat.numero : '';
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editingEtat.value = null;
};

const saveEtat = () => {
    if (!formData.value.reference || !formData.value.locataire || !formData.value.logement || !formData.value.date) {
        errorMessage.value = 'Veuillez remplir tous les champs obligatoires (*)';
        showError.value = true;
        return;
    }

    if (editingEtat.value) {
        const index = etats.value.findIndex(e => e.id === editingEtat.value.id);
        if (index !== -1) {
            etats.value[index] = { ...formData.value, id: editingEtat.value.id };
        }
        successMessage.value = 'État des lieux modifié avec succès';
    } else {
        etats.value.unshift({
            ...formData.value,
            id: etats.value.length ? Math.max(...etats.value.map(e => e.id)) + 1 : 1
        });
        successMessage.value = 'État des lieux enregistré avec succès';
    }

    localStorage.setItem('immobilier_etats-des-lieux', JSON.stringify(etats.value));
    closeModal();
    showSuccess.value = true;
};

const deleteEtat = (etat) => {
    deleteTarget.value = etat;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    deleteTarget.value = null;
};

const confirmDelete = () => {
    if (deleteTarget.value) {
        etats.value = etats.value.filter(e => e.id !== deleteTarget.value.id);
        localStorage.setItem('immobilier_etats-des-lieux', JSON.stringify(etats.value));
        successMessage.value = 'État des lieux supprimé avec succès';
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
    if (!name) return 'from-teal-500 to-cyan-650';
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
