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
                    placeholder="Rechercher par référence, partie..."
                    class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-2xl focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 text-sm font-semibold transition-all shadow-sm"
                />
            </div>
            <div class="flex items-center gap-4 w-full md:w-auto">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold uppercase text-slate-400">Type :</span>
                    <select v-model="typeFilter" class="bg-white border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:outline-none shadow-sm">
                        <option value="All">Tous les types</option>
                        <option value="Convention">Convention</option>
                        <option value="Promesse">Promesse</option>
                        <option value="Avenant">Avenant</option>
                        <option value="Accord">Accord</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold uppercase text-slate-400">Statut :</span>
                    <select v-model="statusFilter" class="bg-white border border-slate-200 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:outline-none shadow-sm">
                        <option value="All">Tous les statuts</option>
                        <option value="Actif">Actif</option>
                        <option value="En cours">En cours</option>
                        <option value="Expiré">Expiré</option>
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
                            <td class="px-6 py-4 text-sm text-slate-700 font-bold">{{ eng.type }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div :class="['w-9 h-9 rounded-xl flex items-center justify-center text-white font-extrabold text-xs shadow-sm bg-gradient-to-br', getAvatarGradient(eng.partie)]">
                                        {{ getInitials(eng.partie) }}
                                    </div>
                                    <div class="font-bold text-slate-800 text-sm">{{ eng.partie }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 font-semibold">{{ formatDate(eng.dateDebut) }} au {{ formatDate(eng.dateFin) }}</td>
                            <td class="px-6 py-4 text-sm text-slate-800 font-extrabold">{{ formatCurrency(eng.montant) }}</td>
                            <td class="px-6 py-4">
                                <span :class="[
                                    'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border',
                                    eng.statut === 'Actif' ? 'bg-emerald-50 text-emerald-700 border-emerald-250' :
                                    eng.statut === 'En cours' ? 'bg-amber-50 text-amber-700 border-amber-200' :
                                    'bg-rose-50 text-rose-700 border-rose-200'
                                ]">
                                    <span :class="['w-1.5 h-1.5 rounded-full',
                                        eng.statut === 'Actif' ? 'bg-emerald-500' :
                                        eng.statut === 'En cours' ? 'bg-amber-500 animate-pulse' :
                                        'bg-rose-500'
                                    ]"></span>
                                    {{ eng.statut }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
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
            <div class="bg-gradient-to-br from-white via-white to-purple-50/20 rounded-3xl shadow-2xl max-w-xl w-full p-8 border border-purple-100/50 relative overflow-hidden animate-scale-up">
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-gradient-to-br from-purple-400/10 to-pink-500/10 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-650 flex items-center justify-center shadow-lg shadow-purple-500/30">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h2 class="text-2xl font-extrabold bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent">
                                {{ editingEngagement ? 'Modifier' : 'Créer un' }} Engagement
                            </h2>
                        </div>
                        <button @click="closeModal" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-all hover:rotate-90">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-1 scrollbar-thin">
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Reference -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Référence *</label>
                                <input v-model="formData.reference" type="text" placeholder="Ex: ENG-2026-004" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-semibold text-slate-800 text-sm shadow-sm" />
                            </div>

                            <!-- Type -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Type d'engagement *</label>
                                <select v-model="formData.type" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-semibold text-slate-800 text-sm shadow-sm">
                                    <option value="Convention">Convention</option>
                                    <option value="Promesse">Promesse</option>
                                    <option value="Avenant">Avenant</option>
                                    <option value="Accord">Accord</option>
                                </select>
                            </div>
                        </div>

                        <!-- Active Tenants selection for counterpart -->
                        <div class="group">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Partie Concernée (Locataire Actif) *</label>
                            <select 
                                v-model="formData.partie" 
                                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-semibold text-slate-800 text-sm shadow-sm"
                            >
                                <option value="" disabled>Sélectionner la contrepartie</option>
                                <option v-for="c in contratsActifs" :key="c.id" :value="c.locataire">
                                    {{ c.locataire }} (Bail {{ c.numero }} - {{ c.reference }})
                                </option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Date Debut -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Date Début *</label>
                                <input v-model="formData.dateDebut" type="date" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-semibold text-slate-800 text-sm shadow-sm" />
                            </div>
                            <!-- Date Fin -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Date Fin *</label>
                                <input v-model="formData.dateFin" type="date" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-semibold text-slate-800 text-sm shadow-sm" />
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Montant -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Montant (€)</label>
                                <input v-model.number="formData.montant" type="number" placeholder="Ex: 5000" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-semibold text-slate-800 text-sm shadow-sm" />
                            </div>
                            <!-- Statut -->
                            <div class="group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Statut *</label>
                                <select v-model="formData.statut" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-semibold text-slate-800 text-sm shadow-sm">
                                    <option value="Actif">Actif</option>
                                    <option value="En cours">En cours</option>
                                    <option value="Expiré">Expiré</option>
                                </select>
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
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4">
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
                            class="px-4 py-3 bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-xl font-bold shadow-lg shadow-purple-500/20 hover:shadow-xl hover:shadow-purple-500/35 transition-all flex items-center justify-center gap-2 text-xs"
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
                
                <div class="mb-6 border border-slate-200 rounded-2xl overflow-hidden">
                    <textarea id="engagement-editor"></textarea>
                </div>

                <div class="flex justify-end gap-4">
                    <button 
                        @click="closeEditorModal" 
                        class="px-6 py-3 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-250 transition-all text-xs"
                    >
                        Fermer sans enregistrer
                    </button>
                    <button 
                        @click="confirmRedaction" 
                        class="px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-650 text-white font-bold rounded-xl shadow-lg shadow-purple-500/20 hover:shadow-xl hover:shadow-purple-500/35 transition-all text-xs"
                    >
                        Valider & Enregistrer l'Acte
                    </button>
                </div>
            </div>
        </div>

        <!-- View Content Modal -->
        <div v-if="showViewContentModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-3xl w-full p-8 border border-slate-200 relative overflow-hidden animate-scale-up">
                <div class="flex items-center justify-between mb-6 border-b border-slate-100 pb-4">
                    <div>
                        <span class="text-[10px] font-bold text-purple-600 uppercase">{{ viewingEngagementRef?.type }}</span>
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
import { ref, computed, onMounted, nextTick } from 'vue';
import axios from 'axios';

const engagements = ref([]);
const contratsActifs = ref([]);

const searchQuery = ref('');
const typeFilter = ref('All');
const statusFilter = ref('All');

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

const viewingEngagementRef = ref(null);
const editorContent = ref('');

const canevasTemplate = ref(`CONVENTION D'ENGAGEMENT DE TYPE [Type]
Entre l'organisme Enterprise Property Corp (le Bailleur), et la Partie Concernée [Partie].

Il est convenu ce qui suit :
1. Objet : La partie concernée s'engage à respecter les obligations stipulées dans le cadre de la convention.
2. Montant financier : L'engagement porte sur une enveloppe financière estimée à [Montant].
3. Durée : Cet engagement prend effet le [Date Début] et prend fin le [Date Fin].
4. Litiges : Tout litige relatif à l'interprétation ou à l'exécution de cet acte sera soumis au tribunal compétent.

Fait le [Date], en double exemplaire.`);

const formData = ref({
    reference: '',
    type: 'Convention',
    partie: '',
    dateDebut: '',
    dateFin: '',
    montant: '',
    statut: 'Actif',
    instructions: '',
    content: ''
});

onMounted(() => {
    // 1. Load engagements from localStorage
    const storedEng = localStorage.getItem('immobilier_engagements');
    if (storedEng) {
        engagements.value = JSON.parse(storedEng);
    } else {
        // Mock fallback
        engagements.value = [
            { id: 1, reference: 'ENG-2026-001', type: 'Convention', partie: 'Jean Dupont', dateDebut: '2026-01-01', dateFin: '2026-12-31', montant: 12000, statut: 'Actif', content: '<h3>CONVENTION D\'ENGAGEMENT</h3><p>Contenu mocké.</p>' },
            { id: 2, reference: 'ENG-2026-002', type: 'Promesse', partie: 'Marie Lambert', dateDebut: '2026-02-15', dateFin: '2027-02-14', montant: 8500, statut: 'En cours', content: '<h3>PROMESSE DE BAIL</h3><p>Contenu mocké.</p>' },
            { id: 3, reference: 'ENG-2026-003', type: 'Avenant', partie: 'Pierre Martin', dateDebut: '2026-03-01', dateFin: '2026-08-31', montant: 5000, statut: 'Expiré', content: '<h3>AVENANT</h3><p>Contenu mocké.</p>' }
        ];
        localStorage.setItem('immobilier_engagements', JSON.stringify(engagements.value));
    }

    // 2. Load active contracts
    const storedContrats = localStorage.getItem('immobilier_contrats');
    if (storedContrats) {
        contratsActifs.value = JSON.parse(storedContrats).filter(c => c.statut === 'Actif');
    }
});

const filteredEngagements = computed(() => {
    let result = engagements.value;

    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        result = result.filter(e => 
            e.reference.toLowerCase().includes(query) ||
            e.partie.toLowerCase().includes(query)
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

const totalEngagements = computed(() => engagements.value.length);
const actifs = computed(() => engagements.value.filter(e => e.statut === 'Actif').length);
const enCours = computed(() => engagements.value.filter(e => e.statut === 'En cours').length);
const expires = computed(() => engagements.value.filter(e => e.statut === 'Expiré').length);

const openAddModal = () => {
    editingEngagement.value = null;
    formData.value = {
        reference: `ENG-${new Date().getFullYear()}-${String(engagements.value.length + 1).padStart(3, '0')}`,
        type: 'Convention',
        partie: '',
        dateDebut: new Date().toISOString().split('T')[0],
        dateFin: '',
        montant: '',
        statut: 'Actif',
        instructions: '',
        content: ''
    };
    editorContent.value = '';
    showModal.value = true;
};

const editEngagement = (engagement) => {
    editingEngagement.value = engagement;
    formData.value = { ...engagement };
    editorContent.value = engagement.content || '';
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
                height: 500,
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
    if (!formData.value.partie) {
        alert("Veuillez sélectionner la partie concernée.");
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
    let tpl = canevasTemplate.value;
    tpl = tpl.replace(/\[Partie\]/g, formData.value.partie || 'Non spécifiée');
    tpl = tpl.replace(/\[Montant\]/g, formatCurrency(formData.value.montant) || 'Non spécifié');
    tpl = tpl.replace(/\[Date Début\]/g, formatDate(formData.value.dateDebut) || 'Non spécifiée');
    tpl = tpl.replace(/\[Date Fin\]/g, formatDate(formData.value.dateFin) || 'Non spécifiée');
    tpl = tpl.replace(/\[Type\]/g, formData.value.type || 'Non spécifié');
    tpl = tpl.replace(/\[Date\]/g, getTodayDate());
    return tpl.replace(/\n/g, '<br>');
};

const generateWithIA = async () => {
    if (!formData.value.partie) {
        alert("Veuillez d'abord sélectionner la partie concernée.");
        return;
    }

    isGenerating.value = true;
    try {
        const payload = {
            partie: formData.value.partie,
            montant: formData.value.montant,
            type: formData.value.type,
            dateDebut: formData.value.dateDebut,
            dateFin: formData.value.dateFin,
            template: canevasTemplate.value,
            instructions: formData.value.instructions,
        };

        const response = await axios.post('/api/ai/generate-engagement', payload);
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

const saveAndPersist = () => {
    const engagementData = {
        id: editingEngagement.value ? editingEngagement.value.id : (engagements.value.length ? Math.max(...engagements.value.map(e => e.id)) + 1 : 1),
        reference: formData.value.reference,
        type: formData.value.type,
        partie: formData.value.partie,
        dateDebut: formData.value.dateDebut,
        dateFin: formData.value.dateFin,
        montant: Number(formData.value.montant || 0),
        statut: formData.value.statut,
        instructions: formData.value.instructions,
        content: editorContent.value
    };

    if (editingEngagement.value) {
        const index = engagements.value.findIndex(e => e.id === editingEngagement.value.id);
        if (index !== -1) {
            engagements.value[index] = engagementData;
        }
        successMessage.value = "Engagement mis à jour avec succès.";
    } else {
        engagements.value.unshift(engagementData);
        successMessage.value = "Engagement enregistré et archivé avec succès.";
    }

    localStorage.setItem('immobilier_engagements', JSON.stringify(engagements.value));
    closeModal();
    showSuccess.value = true;
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

const confirmDelete = () => {
    if (deleteTarget.value) {
        engagements.value = engagements.value.filter(e => e.id !== deleteTarget.value.id);
        localStorage.setItem('immobilier_engagements', JSON.stringify(engagements.value));
        successMessage.value = "Engagement supprimé avec succès.";
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
</style>
