<template>
    <div class="space-y-8 select-none">
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
            
            <!-- Total Agencies Card -->
            <div class="bg-gradient-to-br from-white to-indigo-50/20 rounded-3xl shadow-xl shadow-indigo-100/30 p-8 border border-indigo-100/30 hover:shadow-2xl hover:shadow-indigo-200/40 transition-all duration-500 group relative overflow-hidden">
                <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full bg-indigo-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Total Agences</p>
                        <p class="text-4xl font-extrabold bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent mt-3">{{ stats.total }}</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 via-indigo-600 to-violet-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active Agencies Card -->
            <div class="bg-gradient-to-br from-white to-emerald-50/20 rounded-3xl shadow-xl shadow-emerald-100/30 p-8 border border-emerald-100/30 hover:shadow-2xl hover:shadow-emerald-200/40 transition-all duration-500 group relative overflow-hidden">
                <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full bg-emerald-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Agences Actives</p>
                        <p class="text-4xl font-extrabold bg-gradient-to-r from-emerald-600 to-teal-500 bg-clip-text text-transparent mt-3">{{ stats.active }}</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 via-emerald-600 to-teal-600 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Employees Card -->
            <div class="bg-gradient-to-br from-white to-purple-50/20 rounded-3xl shadow-xl shadow-purple-100/30 p-8 border border-purple-100/30 hover:shadow-2xl hover:shadow-purple-200/40 transition-all duration-500 group relative overflow-hidden">
                <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full bg-purple-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Effectif Global</p>
                        <p class="text-4xl font-extrabold bg-gradient-to-r from-purple-600 to-violet-500 bg-clip-text text-transparent mt-3">{{ stats.total_employees || 0 }}</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-500 via-purple-600 to-violet-600 rounded-2xl flex items-center justify-center shadow-lg shadow-purple-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Management Alerts Card -->
            <div class="bg-gradient-to-br from-white to-rose-50/20 rounded-3xl shadow-xl shadow-rose-100/30 p-8 border border-rose-100/30 hover:shadow-2xl hover:shadow-rose-200/40 transition-all duration-500 group relative overflow-hidden">
                <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full bg-rose-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Alertes Gestion</p>
                        <p class="text-4xl font-extrabold bg-gradient-to-r from-rose-600 to-red-500 bg-clip-text text-transparent mt-3">{{ stats.needs_attention || 0 }}</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-rose-500 via-rose-600 to-red-600 rounded-2xl flex items-center justify-center shadow-lg shadow-rose-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Operational Pilotage Console -->
        <div class="bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-950 rounded-3xl shadow-2xl p-8 text-white relative overflow-hidden border border-slate-800">
            <!-- Background accent glow -->
            <div class="absolute -right-20 -top-20 w-80 h-80 rounded-full bg-indigo-500/10 blur-3xl pointer-events-none"></div>
            <div class="absolute -left-20 -bottom-20 w-80 h-80 rounded-full bg-blue-500/10 blur-3xl pointer-events-none"></div>
            
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 pb-6 border-b border-slate-800/85">
                <div>
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 uppercase tracking-widest">Pilotage Opérationnel</span>
                    <h2 class="text-2xl font-bold mt-2 bg-gradient-to-r from-white via-slate-100 to-slate-300 bg-clip-text text-transparent">Console de Pilotage & Affectation des Rôles</h2>
                    <p class="text-slate-400 text-sm mt-1">Supervisez l'efficacité globale, modifiez les managers d'agence et optimisez la taille de vos équipes.</p>
                </div>
                
                <div class="flex gap-3">
                    <button 
                        @click="showQuickAssign = !showQuickAssign" 
                        class="px-5 py-3 bg-indigo-600/80 hover:bg-indigo-600 border border-indigo-500/50 hover:border-indigo-400 rounded-2xl transition-all duration-300 font-semibold text-sm flex items-center gap-2 transform hover:scale-[1.02] shadow-lg shadow-indigo-600/20"
                    >
                        <svg class="w-4 h-4 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        {{ showQuickAssign ? 'Fermer la console' : 'Pilotage des Managers' }}
                    </button>
                </div>
            </div>

            <!-- Transition for Quick Assign Panel -->
            <Transition
                enter-active-class="transition duration-300 ease-out"
                enter-from-class="transform -translate-y-4 opacity-0"
                enter-to-class="transform translate-y-0 opacity-100"
                leave-active-class="transition duration-200 ease-in"
                leave-from-class="transform translate-y-0 opacity-100"
                leave-to-class="transform -translate-y-4 opacity-0"
            >
                <div v-if="showQuickAssign" class="mt-6 p-6 bg-slate-800/40 rounded-2xl border border-slate-800/80 backdrop-blur-md">
                    <h3 class="text-sm font-bold text-slate-200 mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                        Modifier rapidement le manager et les effectifs d'une agence
                    </h3>
                    
                    <form @submit.prevent="submitQuickAssign" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2">Sélectionner l'Agence</label>
                            <select 
                                v-model="quickAssignForm.agency_id"
                                @change="onQuickAssignAgencyChange"
                                class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-sm text-slate-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 cursor-pointer"
                                required
                            >
                                <option value="" disabled>Choisir une agence...</option>
                                <option v-for="a in agencies.data" :key="a.id" :value="a.id">
                                    {{ a.name }} ({{ a.code }})
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2">Manager Responsable</label>
                            <input 
                                v-model="quickAssignForm.manager_name"
                                type="text"
                                placeholder="Nom complet du manager"
                                class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-sm text-slate-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                                required
                            />
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2">Effectif Alloué</label>
                            <input 
                                v-model.number="quickAssignForm.employee_count"
                                type="number"
                                min="0"
                                placeholder="Nombre de salariés"
                                class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-sm text-slate-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                                required
                            />
                        </div>
                        <div class="flex gap-2">
                            <button 
                                type="submit" 
                                :disabled="quickAssignSubmitting"
                                class="flex-1 px-5 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-xl font-bold text-sm transition-all duration-300 shadow-md shadow-emerald-500/20 transform hover:scale-[1.02] flex items-center justify-center gap-1.5"
                            >
                                <span v-if="!quickAssignSubmitting">Enregistrer</span>
                                <span v-else class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></span>
                            </button>
                            <button 
                                type="button"
                                @click="resetQuickAssignForm"
                                class="px-4 py-3 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 rounded-xl font-medium text-sm transition-all"
                            >
                                Effacer
                            </button>
                        </div>
                    </form>
                </div>
            </Transition>

            <!-- Metrics grid within the dark panel -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6 pt-6 border-t border-slate-800/80">
                <div class="p-4 bg-slate-800/20 border border-slate-800/50 rounded-2xl flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-400 border border-indigo-500/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Taux de couverture active</p>
                        <div class="flex items-baseline gap-2 mt-1">
                            <span class="text-xl font-extrabold text-slate-100">{{ Math.round((stats.active / (stats.total || 1)) * 100) }}%</span>
                            <span class="text-[10px] text-emerald-400 font-bold flex items-center bg-emerald-500/10 px-1.5 py-0.5 rounded">
                                Optimal
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-slate-800/20 border border-slate-800/50 rounded-2xl flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl bg-violet-500/10 flex items-center justify-center text-violet-400 border border-violet-500/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Moyenne Salariés / Agence</p>
                        <p class="text-xl font-extrabold text-slate-100 mt-1">
                            {{ stats.total ? (stats.total_employees / stats.total).toFixed(1) : 0 }}
                            <span class="text-xs text-slate-400 font-normal">pers.</span>
                        </p>
                    </div>
                </div>

                <div class="p-4 bg-slate-800/20 border border-slate-800/50 rounded-2xl flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl bg-rose-500/10 flex items-center justify-center text-rose-400 border border-rose-500/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Alertes Direction</p>
                        <p class="text-xl font-extrabold text-slate-100 mt-1 flex items-center gap-2">
                            <span>{{ stats.needs_attention || 0 }}</span>
                            <span 
                                v-if="stats.needs_attention > 0" 
                                class="inline-flex px-1.5 py-0.5 text-[9px] font-bold bg-rose-500/20 text-rose-400 border border-rose-500/30 rounded-full animate-pulse"
                            >
                                Suivi requis
                            </span>
                            <span v-else class="text-xs text-emerald-400 font-bold">Aucune</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Premium Search and Controls -->
        <div class="bg-gradient-to-br from-white to-slate-50 rounded-3xl shadow-xl shadow-slate-200/50 p-8 border border-slate-100/50">
            <div class="flex flex-col lg:flex-row gap-6 items-center justify-between">
                <div class="flex-1 flex gap-4 w-full lg:w-auto">
                    <div class="relative flex-1">
                        <input
                            v-model="filters.search"
                            @input="handleSearch"
                            type="text"
                            placeholder="Rechercher par nom, code, email, ville..."
                            class="w-full px-6 py-4 bg-white border-2 border-slate-200 rounded-2xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 text-slate-700 placeholder-slate-400"
                        />
                        <svg class="absolute right-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <select
                        v-model="filters.status"
                        @change="handleFilter"
                        class="px-6 py-4 bg-white border-2 border-slate-200 rounded-2xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 text-slate-700 font-medium cursor-pointer"
                    >
                        <option value="">Tous les statuts</option>
                        <option value="active">Actif</option>
                        <option value="inactive">Inactif</option>
                        <option value="suspended">Suspendu</option>
                    </select>
                </div>

                <div class="flex gap-4 w-full lg:w-auto">
                    <button
                        @click="openCreateModal"
                        class="flex-1 lg:flex-none px-8 py-4 bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600 text-white rounded-2xl hover:shadow-2xl hover:shadow-blue-500/40 transition-all duration-300 font-semibold text-center transform hover:scale-105"
                    >
                        <span class="inline-flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Nouvelle Agence
                        </span>
                    </button>
                    <button
                        @click="exportData"
                        class="px-8 py-4 bg-white border-2 border-slate-200 text-slate-700 rounded-2xl hover:bg-slate-50 hover:border-slate-300 transition-all duration-300 font-semibold transform hover:scale-105"
                        title="Exporter en CSV"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Premium Agencies Table -->
        <div class="bg-gradient-to-br from-white to-slate-50 rounded-3xl shadow-xl shadow-slate-200/50 overflow-hidden border border-slate-100/50">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-slate-200/50 bg-gradient-to-r from-slate-50 to-slate-100/50">
                            <th class="px-8 py-5 text-left text-sm font-bold text-slate-700 uppercase tracking-wider">
                                <input
                                    type="checkbox"
                                    @change="toggleSelectAll"
                                    :checked="selectedIds.length === agencies.data.length && agencies.data.length > 0"
                                    class="w-5 h-5 rounded-lg border-2 border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer"
                                />
                            </th>
                            <th class="px-8 py-5 text-left text-sm font-bold text-slate-700 uppercase tracking-wider">Agence</th>
                            <th class="px-8 py-5 text-left text-sm font-bold text-slate-700 uppercase tracking-wider">Contact</th>
                            <th class="px-8 py-5 text-left text-sm font-bold text-slate-700 uppercase tracking-wider">Localisation</th>
                            <th class="px-8 py-5 text-left text-sm font-bold text-slate-700 uppercase tracking-wider">Responsable</th>
                            <th class="px-8 py-5 text-left text-sm font-bold text-slate-700 uppercase tracking-wider">Employés</th>
                            <th class="px-8 py-5 text-left text-sm font-bold text-slate-700 uppercase tracking-wider">Statut</th>
                            <th class="px-8 py-5 text-left text-sm font-bold text-slate-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/50">
                        <tr v-for="agency in agencies.data" :key="agency.id" class="hover:bg-gradient-to-r hover:from-blue-50/20 hover:to-indigo-50/20 transition-all duration-300 group">
                            <td class="px-8 py-5">
                                <input
                                    type="checkbox"
                                    :checked="selectedIds.includes(agency.id)"
                                    @change="toggleSelect(agency.id)"
                                    class="w-5 h-5 rounded-lg border-2 border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer"
                                />
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <!-- Dynamic custom letters gradient avatar -->
                                    <div 
                                        :class="[
                                            'w-12 h-12 rounded-2xl flex items-center justify-center text-white font-extrabold text-sm shadow-md transition-transform duration-300 group-hover:scale-105 bg-gradient-to-br',
                                            getAvatarGradient(agency.name)
                                        ]"
                                    >
                                        {{ getInitials(agency.name) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 text-base flex items-center gap-2">
                                            {{ agency.name }}
                                            <span class="text-[10px] bg-blue-50 text-blue-700 border border-blue-150 font-bold px-2 py-0.5 rounded-full shadow-sm">
                                                {{ agency.code }}
                                            </span>
                                        </div>
                                        <div v-if="agency.description" class="text-xs text-slate-500 mt-1 max-w-xs truncate" :title="agency.description">
                                            {{ agency.description }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <div class="text-sm text-slate-700 font-medium flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    {{ agency.email || '-' }}
                                </div>
                                <div class="text-xs text-slate-500 mt-1 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    {{ agency.phone || '-' }}
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <div class="text-sm text-slate-700 font-medium">{{ agency.city || '-' }}</div>
                                <div class="text-xs text-slate-500 mt-0.5">{{ agency.country || '-' }}</div>
                            </td>
                            <td class="px-8 py-5">
                                <div class="text-sm text-slate-750 font-semibold">{{ agency.manager_name || 'Non attribué' }}</div>
                                <div class="text-xs text-slate-500 mt-0.5">{{ agency.manager_phone || '-' }}</div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-100 text-slate-700 border border-slate-200 rounded-xl text-xs font-bold shadow-sm">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    {{ agency.employee_count || 0 }}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <span
                                    :class="[
                                        'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold border shadow-sm transition-all duration-300',
                                        agency.status === 'active' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' :
                                        agency.status === 'inactive' ? 'bg-slate-50 text-slate-600 border-slate-200' :
                                        'bg-rose-50 text-rose-700 border-rose-200'
                                    ]"
                                >
                                    <span
                                        :class="[
                                            'w-2 h-2 rounded-full',
                                            agency.status === 'active' ? 'bg-emerald-500 animate-pulse' :
                                            agency.status === 'inactive' ? 'bg-slate-400' :
                                            'bg-rose-500'
                                        ]"
                                    ></span>
                                    {{ agency.status === 'active' ? 'Actif' : agency.status === 'inactive' ? 'Inactif' : 'Suspendu' }}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex gap-2.5">
                                    <button
                                        @click="viewAgency(agency.id)"
                                        class="p-2.5 bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-700 rounded-xl transition-all duration-300 transform hover:scale-110 shadow-sm border border-blue-100/50"
                                        title="Voir les détails"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    <button
                                        @click="editAgency(agency.id)"
                                        class="p-2.5 bg-amber-50 text-amber-600 hover:bg-amber-100 hover:text-amber-700 rounded-xl transition-all duration-300 transform hover:scale-110 shadow-sm border border-amber-100/50"
                                        title="Modifier"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button
                                        @click="confirmDelete(agency.id)"
                                        class="p-2.5 bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 rounded-xl transition-all duration-300 transform hover:scale-110 shadow-sm border border-red-100/50"
                                        title="Supprimer"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="agencies.data.length === 0">
                            <td colspan="8" class="px-8 py-16 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-20 h-20 bg-slate-100 rounded-3xl flex items-center justify-center mb-6">
                                        <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <p class="text-xl font-semibold text-slate-600 mb-2">Aucune agence trouvée</p>
                                    <p class="text-slate-500">Commencez par créer votre première agence</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Premium Pagination -->
        <div v-if="agencies.last_page > 1" class="bg-gradient-to-br from-white to-slate-50 rounded-3xl shadow-xl shadow-slate-200/50 p-8 flex items-center justify-between border border-slate-100/50">
            <div class="text-sm font-semibold text-slate-600">
                Affichage de <span class="text-blue-600 font-bold">{{ agencies.from }}</span> à <span class="text-blue-600 font-bold">{{ agencies.to }}</span> sur <span class="text-blue-600 font-bold">{{ agencies.total }}</span> agences
            </div>
            <div class="flex gap-3">
                <button
                    v-for="page in getPaginationPages()"
                    :key="page"
                    @click="goToPage(page)"
                    :class="[
                        'px-6 py-3 rounded-2xl font-bold transition-all duration-300 transform hover:scale-105',
                        page === agencies.current_page
                            ? 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-lg shadow-blue-500/30'
                            : 'bg-white border-2 border-slate-200 text-slate-700 hover:border-blue-500 hover:text-blue-600'
                    ]"
                >
                    {{ page }}
                </button>
            </div>
        </div>

        <!-- Premium Bulk Actions -->
        <Transition
            enter-active-class="transition duration-500 ease-out"
            enter-from-class="transform translate-y-full opacity-0"
            enter-to-class="transform translate-y-0 opacity-100"
            leave-active-class="transition duration-300 ease-in"
            leave-from-class="transform translate-y-0 opacity-100"
            leave-to-class="transform translate-y-full opacity-0"
        >
            <div v-if="selectedIds.length > 0" class="fixed bottom-8 left-1/2 transform -translate-x-1/2 bg-gradient-to-r from-white to-slate-50 rounded-3xl shadow-2xl shadow-slate-900/20 border-2 border-slate-200 p-6 flex items-center gap-6 z-50">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-lg font-bold text-slate-800">{{ selectedIds.length }} agence(s) sélectionnée(s)</span>
                </div>
                <div class="h-8 w-px bg-slate-300"></div>
                <button
                    @click="bulkMarkActive"
                    class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-2xl hover:shadow-lg hover:shadow-emerald-500/30 transition-all duration-300 font-bold transform hover:scale-105"
                >
                    Marquer Actives
                </button>
                <button
                    @click="bulkMarkInactive"
                    class="px-6 py-3 bg-gradient-to-r from-slate-500 to-slate-600 text-white rounded-2xl hover:shadow-lg hover:shadow-slate-500/30 transition-all duration-300 font-bold transform hover:scale-105"
                >
                    Marquer Inactives
                </button>
                <button
                    @click="confirmBulkDelete"
                    class="px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-2xl hover:shadow-lg hover:shadow-red-500/30 transition-all duration-300 font-bold transform hover:scale-105"
                >
                    Supprimer
                </button>
                <button
                    @click="clearSelection"
                    class="px-6 py-3 bg-white border-2 border-slate-300 text-slate-700 rounded-2xl hover:bg-slate-100 hover:border-slate-400 transition-all duration-300 font-bold transform hover:scale-105"
                >
                    Annuler
                </button>
            </div>
        </Transition>

        <!-- Create/Edit Modal -->
        <ModalPremium
            :show="showModal"
            :title="modalTitle"
            :subtitle="modalSubtitle"
            size="lg"
            type="default"
            @close="closeModal"
        >
            <AgencyFormContent
                :agency="editingAgency"
                :errors="formErrors"
                :eligible-managers="eligibleManagers"
                @submit="handleFormSubmit"
                @cancel="closeModal"
            />
        </ModalPremium>

        <!-- Delete Confirmation Modal -->
        <ModalPremium
            :show="showDeleteModal"
            title="Confirmer la suppression"
            subtitle="Êtes-vous sûr de vouloir supprimer cette agence ?"
            size="sm"
            type="warning"
            @close="closeDeleteModal"
        >
            <div class="space-y-4">
                <p class="text-slate-600">Cette action est irréversible. L'agence sera définitivement supprimée de la base de données.</p>
                <div class="flex gap-4 justify-end pt-4">
                    <button
                        @click="closeDeleteModal"
                        class="px-6 py-3 bg-white border-2 border-slate-300 text-slate-700 rounded-2xl hover:bg-slate-100 hover:border-slate-400 transition-all duration-300 font-bold transform hover:scale-105"
                    >
                        Annuler
                    </button>
                    <button
                        @click="confirmDeleteAction"
                        class="px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-2xl hover:shadow-lg hover:shadow-red-500/30 transition-all duration-300 font-bold transform hover:scale-105"
                    >
                        Supprimer
                    </button>
                </div>
            </div>
        </ModalPremium>

        <!-- Notification -->
        <NotificationPremium
            :show="notification.show"
            :type="notification.type"
            :title="notification.title"
            :message="notification.message"
            @close="closeNotification"
        />
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { usePage, router as inertiaRouter } from '@inertiajs/vue3';
import ModalPremium from '../../../../components/ModalPremium.vue';
import NotificationPremium from '../../../../components/NotificationPremium.vue';
import AgencyFormContent from '../../../../components/AgencyFormContent.vue';

const page = usePage();

const getInitialAgencies = () => {
    const raw = page.props.agencies;
    if (!raw) {
        return { data: [], current_page: 1, last_page: 1, from: 0, to: 0, total: 0 };
    }
    if (Array.isArray(raw)) {
        return { data: raw, current_page: 1, last_page: 1, from: 0, to: 0, total: 0 };
    }
    if (!raw.data) {
        return { data: [], current_page: 1, last_page: 1, from: 0, to: 0, total: 0 };
    }
    return raw;
};

const agencies = ref(getInitialAgencies());
const eligibleManagers = ref(page.props.eligibleManagers || []);
const initialStats = page.props.stats || {
    total: 0,
    active: 0,
    inactive: 0,
    suspended: 0,
    total_employees: 0,
    needs_attention: 0
};
const stats = ref(initialStats);

// Modal states
const showModal = ref(false);
const showDeleteModal = ref(false);
const editingAgency = ref(null);
const formErrors = ref({});
const deletingAgencyId = ref(null);
const modalTitle = ref('');
const modalSubtitle = ref('');

// Notification state
const notification = ref({
    show: false,
    type: 'success',
    title: '',
    message: ''
});

// Pilotage Console states
const showQuickAssign = ref(false);
const quickAssignSubmitting = ref(false);
const quickAssignForm = ref({
    agency_id: '',
    manager_name: '',
    employee_count: 0
});

const onQuickAssignAgencyChange = () => {
    const selected = agencies.value.data.find(a => a.id === quickAssignForm.value.agency_id);
    if (selected) {
        quickAssignForm.value.manager_name = selected.manager_name || '';
        quickAssignForm.value.employee_count = selected.employee_count || 0;
    }
};

const resetQuickAssignForm = () => {
    quickAssignForm.value = {
        agency_id: '',
        manager_name: '',
        employee_count: 0
    };
};

const submitQuickAssign = async () => {
    const selected = agencies.value.data.find(a => a.id === quickAssignForm.value.agency_id);
    if (!selected) return;

    quickAssignSubmitting.value = true;
    try {
        const payload = {
            chef_id: selected.chef_id,
            name: selected.name,
            code: selected.code,
            description: selected.description,
            email: selected.email,
            phone: selected.phone,
            fax: selected.fax,
            address: selected.address,
            city: selected.city,
            postal_code: selected.postal_code,
            country: selected.country,
            latitude: selected.latitude,
            longitude: selected.longitude,
            manager_name: quickAssignForm.value.manager_name,
            manager_email: selected.manager_email,
            manager_phone: selected.manager_phone,
            status: selected.status,
            employee_count: quickAssignForm.value.employee_count,
            establishment_date: selected.establishment_date,
        };

        const response = await fetch(route('agencies.update', selected.id), {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
            },
            body: JSON.stringify(payload),
        });

        if (response.ok) {
            showNotification('success', 'Succès', 'Responsabilité et effectif mis à jour avec succès');
            showQuickAssign.value = false;
            resetQuickAssignForm();
            loadAgencies();
        } else {
            const data = await response.json();
            showNotification('error', 'Erreur', data.message || 'Une erreur est survenue');
        }
    } catch (error) {
        console.error('Error in quick assignment:', error);
        showNotification('error', 'Erreur', 'Une erreur est survenue');
    } finally {
        quickAssignSubmitting.value = false;
    }
};

const getInitials = (name) => {
    if (!name) return 'AG';
    const parts = name.trim().split(' ');
    if (parts.length >= 2) {
        return (parts[0][0] + parts[1][0]).toUpperCase();
    }
    return name.substring(0, 2).toUpperCase();
};

const getAvatarGradient = (name) => {
    if (!name) return 'from-blue-500 to-indigo-600';
    const colors = [
        'from-blue-500 to-indigo-600',
        'from-emerald-500 to-teal-600',
        'from-violet-500 to-purple-600',
        'from-amber-500 to-orange-600',
        'from-rose-500 to-red-600',
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

const filters = ref({
    search: page.props.filters?.search || '',
    status: page.props.filters?.status || '',
    per_page: page.props.filters?.per_page || 10,
    sort_by: page.props.filters?.sort_by || 'created_at',
    sort_order: page.props.filters?.sort_order || 'desc',
});

const selectedIds = ref([]);

watch(
    () => page.props.agencies,
    (value) => {
        if (value) {
            if (Array.isArray(value)) {
                agencies.value = { data: value, current_page: 1, last_page: 1, from: 0, to: 0, total: 0 };
            } else if (!value.data) {
                agencies.value = { data: [], current_page: 1, last_page: 1, from: 0, to: 0, total: 0 };
            } else {
                agencies.value = value;
            }
        }
    },
);

watch(
    () => page.props.stats,
    (value) => {
        if (value) stats.value = value;
    },
);

watch(
    () => page.props.eligibleManagers,
    (value) => {
        if (value) eligibleManagers.value = value;
    },
);

onMounted(() => {
    loadStateFromStorage();
    if (!page.props.agencies || Array.isArray(page.props.agencies) || !page.props.agencies.data) {
        loadAgencies();
    }
    window.addEventListener('enterprise:refresh', loadAgencies);
});

const loadStateFromStorage = () => {
    try {
        const savedFilters = localStorage.getItem('agencies_filters');
        const savedSelectedIds = localStorage.getItem('agencies_selected_ids');
        
        if (savedFilters) {
            filters.value = JSON.parse(savedFilters);
        }
        if (savedSelectedIds) {
            selectedIds.value = JSON.parse(savedSelectedIds);
        }
    } catch (error) {
        console.error('Error loading state from localStorage:', error);
    }
};

const saveStateToStorage = () => {
    try {
        localStorage.setItem('agencies_filters', JSON.stringify(filters.value));
        localStorage.setItem('agencies_selected_ids', JSON.stringify(selectedIds.value));
    } catch (error) {
        console.error('Error saving state to localStorage:', error);
    }
};

let notificationTimeout = null;
const showNotification = (type, title, message) => {
    notification.value = {
        show: true,
        type,
        title,
        message
    };
    if (notificationTimeout) clearTimeout(notificationTimeout);
    notificationTimeout = setTimeout(() => {
        notification.value.show = false;
    }, 5000);
};

const closeNotification = () => {
    notification.value.show = false;
    if (notificationTimeout) clearTimeout(notificationTimeout);
};

const openCreateModal = () => {
    editingAgency.value = null;
    formErrors.value = {};
    modalTitle.value = 'Créer une nouvelle agence';
    modalSubtitle.value = 'Remplissez les informations ci-dessous pour créer une nouvelle agence';
    showModal.value = true;
};

const openEditModal = (agency) => {
    editingAgency.value = agency;
    formErrors.value = {};
    modalTitle.value = 'Modifier l\'agence';
    modalSubtitle.value = `Modifiez les informations de l'agence ${agency.name}`;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editingAgency.value = null;
    formErrors.value = {};
};

const confirmDelete = (id) => {
    deletingAgencyId.value = id;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    deletingAgencyId.value = null;
};

const confirmDeleteAction = async () => {
    try {
        const response = await fetch(route('agencies.destroy', deletingAgencyId.value), {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
            },
        });

        if (response.ok) {
            showNotification('success', 'Succès', 'L\'agence a été supprimée avec succès');
            closeDeleteModal();
            loadAgencies();
        } else {
            showNotification('error', 'Erreur', 'Une erreur est survenue lors de la suppression');
        }
    } catch (error) {
        console.error('Error deleting agency:', error);
        showNotification('error', 'Erreur', 'Une erreur est survenue lors de la suppression');
    }
};

const handleFormSubmit = async (formData) => {
    formErrors.value = {};
    try {
        const method = editingAgency.value ? 'PUT' : 'POST';
        const url = editingAgency.value ? route('agencies.update', editingAgency.value.id) : route('agencies.store');

        const response = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
            },
            body: JSON.stringify(formData),
        });

        if (response.ok) {
            showNotification('success', 'Succès', editingAgency.value ? 'L\'agence a été mise à jour avec succès' : 'L\'agence a été créée avec succès');
            closeModal();
            loadAgencies();
        } else {
            const data = await response.json();
            if (response.status === 422 && data.errors) {
                formErrors.value = data.errors;
            }
            showNotification('error', 'Erreur', data.message || 'Une erreur est survenue');
        }
    } catch (error) {
        console.error('Error submitting form:', error);
        showNotification('error', 'Erreur', 'Une erreur est survenue');
    }
};

const handleSearch = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        saveStateToStorage();
        loadAgencies();
    }, 500);
};

const handleFilter = () => {
    saveStateToStorage();
    loadAgencies();
};

const loadAgencies = () => {
    const params = new URLSearchParams(filters.value);
    inertiaRouter.get(route('agencies.index') + `?${params.toString()}`, {}, {
        preserveState: true,
        preserveScroll: true,
        only: ['agencies', 'stats', 'filters', 'eligibleManagers'],
    });
};

const toggleSelectAll = () => {
    if (selectedIds.value.length === agencies.value.data.length) {
        selectedIds.value = [];
    } else {
        selectedIds.value = agencies.value.data.map(a => a.id);
    }
    saveStateToStorage();
};

const toggleSelect = (id) => {
    const index = selectedIds.value.indexOf(id);
    if (index > -1) {
        selectedIds.value.splice(index, 1);
    } else {
        selectedIds.value.push(id);
    }
    saveStateToStorage();
};

const clearSelection = () => {
    selectedIds.value = [];
    saveStateToStorage();
};

const viewAgency = (id) => {
    inertiaRouter.visit(route('agencies.show', id));
};

const editAgency = (id) => {
    const agency = agencies.value.data.find(a => a.id === id);
    if (agency) {
        openEditModal(agency);
    }
};

const bulkMarkActive = async () => {
    await bulkUpdateStatus('active');
};

const bulkMarkInactive = async () => {
    await bulkUpdateStatus('inactive');
};

const bulkUpdateStatus = async (status) => {
    try {
        const response = await fetch(route('agencies.bulk-status'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
            },
            body: JSON.stringify({
                ids: selectedIds.value,
                status
            }),
        });

        if (response.ok) {
            showNotification('success', 'Succès', `${selectedIds.value.length} agence(s) marquée(s) comme ${status === 'active' ? 'active(s)' : 'inactive(s)'}`);
            clearSelection();
            loadAgencies();
        } else {
            showNotification('error', 'Erreur', 'Une erreur est survenue lors de la mise à jour');
        }
    } catch (error) {
        console.error('Error bulk updating status:', error);
        showNotification('error', 'Erreur', 'Une erreur est survenue');
    }
};

const confirmBulkDelete = () => {
    if (confirm(`Êtes-vous sûr de vouloir supprimer ${selectedIds.value.length} agence(s) ?`)) {
        bulkDelete();
    }
};

const bulkDelete = async () => {
    try {
        const response = await fetch(route('agencies.bulk-delete'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
            },
            body: JSON.stringify({
                ids: selectedIds.value
            }),
        });

        if (response.ok) {
            showNotification('success', 'Succès', `${selectedIds.value.length} agence(s) supprimée(s) avec succès`);
            clearSelection();
            loadAgencies();
        } else {
            showNotification('error', 'Erreur', 'Une erreur est survenue lors de la suppression');
        }
    } catch (error) {
        console.error('Error bulk deleting:', error);
        showNotification('error', 'Erreur', 'Une erreur est survenue');
    }
};

const exportData = () => {
    window.location.href = route('agencies.export');
};

const getPaginationPages = () => {
    const current = agencies.value.current_page;
    const last = agencies.value.last_page;
    const pages = [];

    if (last <= 7) {
        for (let i = 1; i <= last; i++) {
            pages.push(i);
        }
    } else {
        pages.push(1, 2, 3, '...', last - 2, last - 1, last);
    }

    return pages;
};

const goToPage = (page) => {
    if (page === '...') return;
    filters.value.page = page;
    saveStateToStorage();
    loadAgencies();
};

let searchTimeout;
</script>
