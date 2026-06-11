<template>
    <div class="flex flex-col gap-6">

        <!-- ── Header ──────────────────────────────────────────────────────── -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 flex items-center gap-2">
                    Propriétaires
                    <span class="text-emerald-600 text-sm font-semibold bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">Gestion</span>
                </h1>
                <p class="text-slate-500 mt-1 text-sm">Gérer les propriétaires et leurs contrats avec votre entreprise.</p>
            </div>
            <button
                type="button"
                @click="openAddModal"
                class="flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-xl font-medium shadow-lg shadow-emerald-500/30 hover:shadow-xl hover:shadow-emerald-500/40 hover:scale-[1.02] active:scale-95 transition-all duration-200"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouveau Propriétaire
            </button>
        </div>

        <!-- ── KPI Cards ───────────────────────────────────────────────────── -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div v-for="(kpi, i) in kpis" :key="i"
                class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex items-center gap-4 hover:shadow-md transition-shadow">
                <div :class="`w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 ${kpi.bg}`">
                    <svg class="w-6 h-6" :class="kpi.color" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" :d="kpi.icon" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">{{ kpi.label }}</p>
                    <p class="text-2xl font-bold mt-0.5" :class="kpi.color">{{ kpi.value }}</p>
                </div>
            </div>
        </div>

        <!-- ── Search & Filters ────────────────────────────────────────────── -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 flex flex-col md:flex-row gap-3">
            <div class="relative flex-1">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input v-model="search" type="text" placeholder="Nom, email, téléphone, ville..."
                    class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition" />
            </div>
            <div class="flex gap-2">
                <select v-model="filterType"
                    class="px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 bg-white text-slate-700">
                    <option value="">Tous les types</option>
                    <option value="personne_physique">Personne physique</option>
                    <option value="personne_morale">Personne morale</option>
                </select>
                <select v-model="filterStatut"
                    class="px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 bg-white text-slate-700">
                    <option value="">Tous statuts</option>
                    <option value="actif">Actif</option>
                    <option value="inactif">Inactif</option>
                    <option value="suspendu">Suspendu</option>
                </select>
            </div>
        </div>

        <!-- ── Table ───────────────────────────────────────────────────────── -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <!-- Loading -->
            <div v-if="loading" class="flex items-center justify-center py-16 gap-3 text-slate-400">
                <svg class="w-6 h-6 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                </svg>
                Chargement des propriétaires...
            </div>

            <div v-else class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50">
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Propriétaire</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Localisation</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Contrat</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3.5 text-right text-xs font-bold text-slate-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-for="p in filtered" :key="p.id"
                            class="hover:bg-slate-50/60 transition-colors duration-100">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl overflow-hidden flex-shrink-0">
                                        <img v-if="p.photo_path" :src="p.photo_path" :alt="p.nom_complet" class="w-full h-full object-cover cursor-zoom-in" @click.stop="lightboxPhotoUrl = p.photo_path" />
                                        <div v-else class="w-full h-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold text-sm">
                                            {{ p.initiales }}
                                        </div>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-900 text-sm">{{ p.nom_complet }}</p>
                                        <p class="text-xs text-slate-400">{{ p.email || '—' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span :class="p.type === 'personne_morale'
                                    ? 'bg-violet-50 text-violet-700 border-violet-200'
                                    : 'bg-blue-50 text-blue-700 border-blue-200'"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold border">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path v-if="p.type === 'personne_morale'" stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1" />
                                        <path v-else stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    {{ p.type === 'personne_morale' ? 'Société' : 'Particulier' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-slate-700">{{ p.telephone || '—' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-slate-700">{{ p.ville || '—' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs text-slate-500">{{ p.type_contrat ? formatContrat(p.type_contrat) : '—' }}</span>
                                <span v-if="p.commission_taux" class="ml-1 text-xs font-semibold text-emerald-600">{{ p.commission_taux }}%</span>
                            </td>
                            <td class="px-6 py-4">
                                <span :class="{
                                    'bg-emerald-50 text-emerald-700 border-emerald-200': p.statut === 'actif',
                                    'bg-slate-50 text-slate-600 border-slate-200': p.statut === 'inactif',
                                    'bg-amber-50 text-amber-700 border-amber-200': p.statut === 'suspendu',
                                }" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border">
                                    <span class="w-1.5 h-1.5 rounded-full" :class="{
                                        'bg-emerald-500': p.statut === 'actif',
                                        'bg-slate-400': p.statut === 'inactif',
                                        'bg-amber-500': p.statut === 'suspendu',
                                    }"></span>
                                    {{ capitalize(p.statut) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button type="button" @click="openProfile(p)" title="Profil"
                                        class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    <button type="button" @click="openEdit(p)" title="Modifier"
                                        class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button type="button" @click="openDelete(p)" title="Supprimer"
                                        class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!loading && filtered.length === 0">
                            <td colspan="7" class="text-center py-16 text-slate-400">
                                <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <p class="font-medium">Aucun propriétaire trouvé</p>
                                <p class="text-sm mt-1">Ajoutez votre premier propriétaire pour commencer.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ── Profile Panel (slide-over) ─────────────────────────────────── -->
        <Transition name="slide-over">
            <div v-if="profileProp" class="fixed inset-0 z-50 flex">
                <!-- Overlay -->
                <div class="flex-1 bg-black/40 backdrop-blur-sm" @click="profileProp = null"></div>
                <!-- Panel -->
                <div class="w-full max-w-md bg-white shadow-2xl flex flex-col overflow-hidden">
                    <!-- Header -->
                    <div class="bg-gradient-to-br from-emerald-600 to-teal-700 px-6 py-8 text-white relative">
                        <button type="button" @click="profileProp = null"
                            class="absolute top-4 right-4 p-1.5 rounded-lg bg-white/20 hover:bg-white/30 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <!-- Avatar avec upload -->
                        <div class="flex flex-col items-center gap-3">
                            <div class="relative group">
                                <div class="w-24 h-24 rounded-2xl overflow-hidden border-4 border-white/30 shadow-xl">
                                    <img v-if="profileProp.photo_path" :src="profileProp.photo_path" :alt="profileProp.nom_complet" class="w-full h-full object-cover cursor-zoom-in" @click="lightboxPhotoUrl = profileProp.photo_path" />
                                    <div v-else class="w-full h-full bg-white/20 flex items-center justify-center text-3xl font-bold">
                                        {{ profileProp.initiales }}
                                    </div>
                                </div>
                                <!-- Upload overlay -->
                                <label :for="`photo-${profileProp.id}`"
                                    class="absolute inset-0 rounded-2xl flex items-center justify-center bg-black/50 opacity-0 group-hover:opacity-100 transition cursor-pointer">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </label>
                                <input :id="`photo-${profileProp.id}`" type="file" accept="image/*" class="hidden" @change="onPhotoChange" />
                                <!-- Spinner upload -->
                                <div v-if="uploadingPhoto"
                                    class="absolute inset-0 rounded-2xl flex items-center justify-center bg-black/60">
                                    <svg class="w-6 h-6 text-white animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="text-center">
                                <p class="text-xl font-bold">{{ profileProp.nom_complet }}</p>
                                <p class="text-emerald-200 text-sm mt-0.5">{{ profileProp.type === 'personne_morale' ? 'Personne morale' : 'Personne physique' }}</p>
                            </div>
                        </div>
                        <!-- Supprimer photo link -->
                        <button v-if="profileProp.photo_path" type="button" @click="deletePhoto"
                            class="mt-3 mx-auto block text-xs text-white/60 hover:text-white/90 underline transition">
                            Supprimer la photo
                        </button>
                    </div>

                    <!-- Body scrollable -->
                    <div class="flex-1 overflow-y-auto p-6 space-y-5">
                        <!-- Statut -->
                        <div class="flex items-center justify-between">
                            <span :class="{
                                'bg-emerald-100 text-emerald-700': profileProp.statut === 'actif',
                                'bg-slate-100 text-slate-600': profileProp.statut === 'inactif',
                                'bg-amber-100 text-amber-700': profileProp.statut === 'suspendu',
                            }" class="px-3 py-1 rounded-full text-sm font-semibold">
                                {{ capitalize(profileProp.statut) }}
                            </span>
                            <button type="button" @click="openEdit(profileProp); profileProp = null"
                                class="flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg text-sm font-medium hover:bg-indigo-100 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Modifier
                            </button>
                        </div>

                        <!-- Contact -->
                        <section>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Contact</p>
                            <div class="bg-slate-50 rounded-xl divide-y divide-slate-100">
                                <div class="flex items-center gap-3 px-4 py-3">
                                    <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    <span class="text-sm text-slate-700">{{ profileProp.email || '—' }}</span>
                                </div>
                                <div class="flex items-center gap-3 px-4 py-3">
                                    <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    <span class="text-sm text-slate-700">{{ profileProp.telephone || '—' }}</span>
                                </div>
                                <div v-if="profileProp.ville" class="flex items-center gap-3 px-4 py-3">
                                    <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span class="text-sm text-slate-700">{{ profileProp.ville }}, {{ profileProp.pays || '' }}</span>
                                </div>
                            </div>
                        </section>

                        <!-- Contrat -->
                        <section v-if="profileProp.type_contrat || profileProp.commission_taux">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Contrat</p>
                            <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4 space-y-2">
                                <div v-if="profileProp.type_contrat" class="flex justify-between text-sm">
                                    <span class="text-slate-500">Type</span>
                                    <span class="font-semibold text-slate-800">{{ formatContrat(profileProp.type_contrat) }}</span>
                                </div>
                                <div v-if="profileProp.commission_taux" class="flex justify-between text-sm">
                                    <span class="text-slate-500">Commission</span>
                                    <span class="font-bold text-emerald-700">{{ profileProp.commission_taux }}%</span>
                                </div>
                            </div>
                        </section>

                        <!-- Bâtiments liés -->
                        <section>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Bâtiments associés ({{ profileProp.batiments?.length || 0 }})</p>
                            <div v-if="profileProp.batiments && profileProp.batiments.length > 0" class="space-y-2">
                                <div 
                                    v-for="b in profileProp.batiments" 
                                    :key="b.id"
                                    @click="openBatimentDetail(b)"
                                    class="p-3 bg-slate-50 hover:bg-indigo-50 border border-slate-100 hover:border-indigo-200 rounded-xl cursor-pointer transition-all duration-200 group flex items-center justify-between"
                                >
                                    <div class="min-w-0">
                                        <h4 class="font-semibold text-slate-800 text-sm group-hover:text-indigo-700 transition truncate">{{ b.nom }}</h4>
                                        <p class="text-xs text-slate-500 mt-0.5 truncate">{{ b.adresse }}, {{ b.ville }}</p>
                                    </div>
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <span :class="b.statut === 'Actif' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-amber-50 text-amber-700 border-amber-100'" class="px-2 py-0.5 text-[10px] font-semibold rounded border">
                                            {{ b.statut }}
                                        </span>
                                        <svg class="w-4 h-4 text-slate-400 group-hover:text-indigo-500 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="bg-slate-50 rounded-xl px-4 py-3 text-sm text-slate-500 italic">
                                Aucun bâtiment associé pour l'instant.
                            </div>
                        </section>

                        <!-- Documents Légaux -->
                        <section>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Documents légaux</p>
                            <div v-if="profileProp.documents && profileProp.documents.length > 0" class="space-y-2">
                                <div v-for="(doc, idx) in profileProp.documents" :key="idx" class="flex items-center justify-between p-3 bg-slate-50 border border-slate-100 rounded-xl">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <div class="min-w-0">
                                            <p class="text-xs font-semibold text-slate-700 truncate" :title="doc.filename">{{ doc.filename }}</p>
                                            <p class="text-[10px] text-slate-400 uppercase font-bold">{{ doc.name }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1.5 flex-shrink-0">
                                        <a v-if="doc.url" :href="doc.url" target="_blank" download class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition" title="Télécharger">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                        </a>
                                        <button type="button" @click="deleteDocument(idx)" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Supprimer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="bg-slate-50 rounded-xl px-4 py-3 text-sm text-slate-500 italic">
                                Aucun document légal importé pour l'instant.
                            </div>
                        </section>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 border-t border-slate-100 flex gap-3">
                        <button type="button" @click="openDelete(profileProp); profileProp = null"
                            class="flex-1 py-2.5 border border-rose-200 text-rose-600 rounded-xl text-sm font-medium hover:bg-rose-50 transition">
                            Supprimer
                        </button>
                        <button type="button" @click="profileProp = null"
                            class="flex-1 py-2.5 bg-slate-100 text-slate-700 rounded-xl text-sm font-medium hover:bg-slate-200 transition">
                            Fermer
                        </button>
                    </div>
                </div>
            </div>
        </Transition>

        <!-- ── Add / Edit Modal ────────────────────────────────────────────── -->
        <Transition name="modal">
            <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
                <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col border border-slate-100">
                    <!-- Modal Header -->
                    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-emerald-50 to-teal-50/50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-slate-900">{{ editingProp ? 'Modifier' : 'Nouveau' }} Propriétaire</h2>
                                <p class="text-xs text-slate-500">Renseignez les informations du propriétaire</p>
                            </div>
                        </div>
                        <button type="button" @click="closeModal" class="p-1.5 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="flex-1 overflow-y-auto p-6 space-y-6">
                        <!-- Type toggle -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Type de propriétaire</label>
                            <div class="flex gap-3">
                                <button type="button" @click="form.type = 'personne_physique'"
                                    :class="form.type === 'personne_physique' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-500/30' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                    class="flex-1 py-2.5 rounded-xl text-sm font-semibold transition">
                                    👤 Personne physique
                                </button>
                                <button type="button" @click="form.type = 'personne_morale'"
                                    :class="form.type === 'personne_morale' ? 'bg-violet-600 text-white shadow-lg shadow-violet-500/30' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                    class="flex-1 py-2.5 rounded-xl text-sm font-semibold transition">
                                    🏢 Personne morale
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Identité -->
                            <div class="space-y-3 md:col-span-2">
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-1.5">Identité</h3>
                                <div class="grid grid-cols-2 gap-3">
                                    <div v-if="form.type === 'personne_physique'">
                                        <label class="field-label">Prénom</label>
                                        <input v-model="form.prenom" type="text" placeholder="Prénom" class="field-input" />
                                    </div>
                                    <div :class="form.type === 'personne_physique' ? '' : 'col-span-2'">
                                        <label class="field-label">{{ form.type === 'personne_morale' ? 'Dénomination sociale' : 'Nom *' }}</label>
                                        <input v-model="form.nom" type="text" :placeholder="form.type === 'personne_morale' ? 'Nom de la société' : 'Nom de famille'" class="field-input" />
                                    </div>
                                    <div v-if="form.type === 'personne_morale'">
                                        <label class="field-label">SIRET / Immatriculation</label>
                                        <input v-model="form.siret" type="text" placeholder="Ex: 123 456 789 00010" class="field-input" />
                                    </div>
                                    <div>
                                        <label class="field-label">N° Contribuable / NIF</label>
                                        <input v-model="form.numero_contribuable" type="text" placeholder="NIF..." class="field-input" />
                                    </div>
                                    <div v-if="form.type === 'personne_physique'">
                                        <label class="field-label">Pièce d'identité</label>
                                        <select v-model="form.piece_identite_type" class="field-input">
                                            <option value="">Sélectionner</option>
                                            <option value="CNI">CNI</option>
                                            <option value="Passeport">Passeport</option>
                                            <option value="Titre de séjour">Titre de séjour</option>
                                            <option value="Autre">Autre</option>
                                        </select>
                                    </div>
                                    <div v-if="form.type === 'personne_physique'">
                                        <label class="field-label">Numéro pièce</label>
                                        <input v-model="form.piece_identite_numero" type="text" placeholder="N° de la pièce" class="field-input" />
                                    </div>
                                </div>
                            </div>

                            <!-- Contact -->
                            <div class="space-y-3 md:col-span-2">
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-1.5">Coordonnées</h3>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="field-label">Email</label>
                                        <input v-model="form.email" type="email" placeholder="email@exemple.com" class="field-input" />
                                    </div>
                                    <div>
                                        <label class="field-label">Téléphone</label>
                                        <input v-model="form.telephone" type="tel" placeholder="+237 6XX XXX XXX" class="field-input" />
                                    </div>
                                    <div>
                                        <label class="field-label">Téléphone secondaire</label>
                                        <input v-model="form.telephone_secondaire" type="tel" placeholder="Optionnel" class="field-input" />
                                    </div>
                                    <div>
                                        <label class="field-label">Ville</label>
                                        <input v-model="form.ville" type="text" placeholder="Ville" class="field-input" />
                                    </div>
                                    <div>
                                        <label class="field-label">Pays</label>
                                        <input v-model="form.pays" type="text" placeholder="CM" class="field-input" />
                                    </div>
                                    <div class="col-span-2">
                                        <label class="field-label">Adresse</label>
                                        <input v-model="form.adresse" type="text" placeholder="Adresse complète" class="field-input" />
                                    </div>
                                </div>
                            </div>

                            <!-- Bancaire -->
                            <div class="space-y-3 md:col-span-2">
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-1.5">Informations bancaires</h3>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="field-label">Banque</label>
                                        <input v-model="form.banque" type="text" placeholder="Nom de la banque" class="field-input" />
                                    </div>
                                    <div>
                                        <label class="field-label">RIB / IBAN</label>
                                        <input v-model="form.rib" type="text" placeholder="RIB ou IBAN" class="field-input" />
                                    </div>
                                </div>
                            </div>

                            <!-- Contrat -->
                            <div class="space-y-3 md:col-span-2">
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-1.5">Contrat avec l'entreprise</h3>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="field-label">Type de contrat</label>
                                        <select v-model="form.type_contrat" class="field-input">
                                            <option value="">Aucun</option>
                                            <option value="gestion_locative">Gestion locative</option>
                                            <option value="syndic">Syndic</option>
                                            <option value="mandat_vente">Mandat de vente</option>
                                            <option value="mandat_gestion">Mandat de gestion</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="field-label">Commission (%)</label>
                                        <input v-model.number="form.commission_taux" type="number" step="0.5" min="0" max="100" placeholder="Ex: 8.5" class="field-input" />
                                    </div>
                                    <div>
                                        <label class="field-label">Date début contrat</label>
                                        <input v-model="form.date_debut_contrat" type="date" class="field-input" />
                                    </div>
                                    <div>
                                        <label class="field-label">Date fin contrat</label>
                                        <input v-model="form.date_fin_contrat" type="date" class="field-input" />
                                    </div>
                                    <div>
                                        <label class="field-label">Statut</label>
                                        <select v-model="form.statut" class="field-input">
                                            <option value="actif">Actif</option>
                                            <option value="inactif">Inactif</option>
                                            <option value="suspendu">Suspendu</option>
                                        </select>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="field-label">Notes</label>
                                        <textarea v-model="form.notes" rows="2" placeholder="Remarques internes..." class="field-input resize-none"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Documents Légaux -->
                            <div class="space-y-3 md:col-span-2">
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-1.5">Documents légaux</h3>
                                <div v-if="form.type === 'personne_physique'" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <label class="field-label">Carte Nationale d'Identité (CNI)</label>
                                        <input type="file" @change="e => onFileSelected(e, 'cni')" class="field-input py-1.5" accept=".pdf,.png,.jpg,.jpeg,.webp" />
                                    </div>
                                    <div>
                                        <label class="field-label">Autre document utile</label>
                                        <input type="file" @change="e => onFileSelected(e, 'autre')" class="field-input py-1.5" accept=".pdf,.png,.jpg,.jpeg,.webp,.doc,.docx" />
                                    </div>
                                </div>
                                <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <div>
                                        <label class="field-label">Registre du Commerce (KBIS)</label>
                                        <input type="file" @change="e => onFileSelected(e, 'kbis')" class="field-input py-1.5" accept=".pdf,.png,.jpg,.jpeg,.webp" />
                                    </div>
                                    <div>
                                        <label class="field-label">Statuts de l'entreprise</label>
                                        <input type="file" @change="e => onFileSelected(e, 'statuts')" class="field-input py-1.5" accept=".pdf,.png,.jpg,.jpeg,.webp" />
                                    </div>
                                    <div>
                                        <label class="field-label">Autre document utile</label>
                                        <input type="file" @change="e => onFileSelected(e, 'autre')" class="field-input py-1.5" accept=".pdf,.png,.jpg,.jpeg,.webp,.doc,.docx" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex gap-3 justify-end">
                        <button type="button" @click="closeModal"
                            class="px-5 py-2.5 border border-slate-200 text-slate-700 rounded-xl text-sm font-medium hover:bg-slate-100 transition">
                            Annuler
                        </button>
                        <button type="button" @click="save" :disabled="!form.nom || saving"
                            class="px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-xl text-sm font-medium disabled:opacity-50 shadow hover:from-emerald-700 hover:to-teal-700 transition flex items-center gap-2">
                            <svg v-if="saving" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                            </svg>
                            {{ saving ? 'Enregistrement...' : 'Enregistrer' }}
                        </button>
                    </div>
                </div>
            </div>
        </Transition>

        <!-- ── Delete Confirm ─────────────────────────────────────────────── -->
        <Transition name="modal">
            <div v-if="deletingProp" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
                <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 text-center">
                    <div class="w-14 h-14 rounded-full bg-rose-100 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-rose-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-1">Supprimer ce propriétaire ?</h3>
                    <p class="text-slate-500 text-sm mb-6">
                        <strong>{{ deletingProp.nom_complet }}</strong> sera définitivement supprimé de la liste.
                    </p>
                    <div class="flex gap-3">
                        <button type="button" @click="deletingProp = null"
                            class="flex-1 py-2.5 border border-slate-200 text-slate-700 rounded-xl text-sm font-medium hover:bg-slate-50 transition">
                            Annuler
                        </button>
                        <button type="button" @click="confirmDelete" :disabled="deleting"
                            class="flex-1 py-2.5 bg-rose-600 text-white rounded-xl text-sm font-medium hover:bg-rose-700 transition">
                            {{ deleting ? '...' : 'Supprimer' }}
                        </button>
                    </div>
                </div>
            </div>
        </Transition>

        <!-- ── Toast ──────────────────────────────────────────────────────── -->
        <Transition name="toast">
            <div v-if="toast.show"
                :class="toast.type === 'success' ? 'bg-emerald-600' : 'bg-rose-600'"
                class="fixed bottom-6 right-6 z-[60] px-5 py-3.5 rounded-2xl text-white shadow-xl flex items-center gap-3 text-sm font-medium">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path v-if="toast.type === 'success'" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    <path v-else stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                {{ toast.message }}
            </div>
        </Transition>

        <!-- Lightbox Modal -->
        <Transition name="modal">
            <div v-if="lightboxPhotoUrl" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 backdrop-blur-sm" @click="lightboxPhotoUrl = null">
                <button type="button" @click="lightboxPhotoUrl = null" class="absolute top-6 right-6 text-white/70 hover:text-white hover:bg-white/10 p-2.5 rounded-full transition">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <img :src="lightboxPhotoUrl" class="max-w-[90vw] max-h-[90vh] rounded-2xl shadow-2xl border border-white/10 object-contain animate-scale-up" @click.stop />
            </div>
        </Transition>

        <!-- Batiment Detail Modal -->
        <Transition name="modal">
            <div v-if="selectedBatimentDetail" class="fixed inset-0 z-[90] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
                <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden animate-scale-up border border-slate-100 animate-fade-in">
                    <!-- Header -->
                    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-indigo-50 to-violet-50/50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600 shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-slate-900">Détails du Bâtiment</h2>
                                <p class="text-xs text-slate-500">Informations structurelles et géographiques.</p>
                            </div>
                        </div>
                        <button @click="selectedBatimentDetail = null" class="text-slate-400 hover:text-slate-600 transition p-1.5 hover:bg-slate-100 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="p-6 space-y-4 text-sm">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Nom du bâtiment</span>
                                <span class="text-slate-900 font-semibold text-base">{{ selectedBatimentDetail.nom }}</span>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Ville</span>
                                <span class="text-slate-800 font-medium">{{ selectedBatimentDetail.ville || '—' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Quartier</span>
                                <span class="text-slate-800 font-medium">{{ selectedBatimentDetail.quartier || '—' }}</span>
                            </div>
                            <div class="col-span-2">
                                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Adresse</span>
                                <span class="text-slate-800 font-medium">{{ selectedBatimentDetail.adresse || '—' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Étages</span>
                                <span class="text-slate-800 font-semibold bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded text-xs inline-block mt-0.5">{{ selectedBatimentDetail.etages }} étages</span>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Appartements</span>
                                <span class="text-slate-800 font-semibold bg-violet-50 text-violet-700 px-2 py-0.5 rounded text-xs inline-block mt-0.5">{{ selectedBatimentDetail.appartements }} unités</span>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Statut</span>
                                <span :class="selectedBatimentDetail.statut === 'Actif' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-amber-50 text-amber-700 border-amber-100'" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold border mt-0.5">
                                    {{ selectedBatimentDetail.statut }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end">
                        <button @click="selectedBatimentDetail = null" class="px-5 py-2.5 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 shadow transition text-sm">Fermer</button>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

// ── State ─────────────────────────────────────────────────────────────────────
const proprietaires = ref([]);
const loading = ref(false);
const saving = ref(false);
const deleting = ref(false);
const uploadingPhoto = ref(false);
const search = ref('');
const filterType = ref('');
const filterStatut = ref('');
const showModal = ref(false);
const editingProp = ref(null);
const deletingProp = ref(null);
const profileProp = ref(null);
const toast = ref({ show: false, type: 'success', message: '' });

const lightboxPhotoUrl = ref(null);
const selectedBatimentDetail = ref(null);
const selectedFiles = ref({
    cni: null,
    kbis: null,
    statuts: null,
    autre: null,
});

const openBatimentDetail = (b) => {
    selectedBatimentDetail.value = b;
};

const onFileSelected = (e, key) => {
    const file = e.target.files[0];
    if (file) {
        selectedFiles.value[key] = file;
    } else {
        selectedFiles.value[key] = null;
    }
};

const emptyForm = () => ({
    type: 'personne_physique',
    nom: '',
    prenom: '',
    raison_sociale: '',
    siret: '',
    numero_contribuable: '',
    piece_identite_type: '',
    piece_identite_numero: '',
    email: '',
    telephone: '',
    telephone_secondaire: '',
    adresse: '',
    ville: '',
    pays: 'CM',
    code_postal: '',
    banque: '',
    rib: '',
    swift: '',
    statut: 'actif',
    date_debut_contrat: '',
    date_fin_contrat: '',
    commission_taux: '',
    type_contrat: '',
    notes: '',
});
const form = ref(emptyForm());

// ── Computed ──────────────────────────────────────────────────────────────────
const filtered = computed(() => {
    let list = proprietaires.value;
    if (filterType.value) list = list.filter(p => p.type === filterType.value);
    if (filterStatut.value) list = list.filter(p => p.statut === filterStatut.value);
    if (search.value) {
        const q = search.value.toLowerCase();
        list = list.filter(p =>
            (p.nom_complet || '').toLowerCase().includes(q) ||
            (p.email || '').toLowerCase().includes(q) ||
            (p.telephone || '').toLowerCase().includes(q) ||
            (p.ville || '').toLowerCase().includes(q)
        );
    }
    return list;
});

const kpis = computed(() => [
    {
        label: 'Total', value: proprietaires.value.length,
        icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
        bg: 'bg-emerald-100', color: 'text-emerald-600',
    },
    {
        label: 'Actifs', value: proprietaires.value.filter(p => p.statut === 'actif').length,
        icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        bg: 'bg-blue-100', color: 'text-blue-600',
    },
    {
        label: 'Personnes physiques', value: proprietaires.value.filter(p => p.type === 'personne_physique').length,
        icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
        bg: 'bg-violet-100', color: 'text-violet-600',
    },
    {
        label: 'Personnes morales', value: proprietaires.value.filter(p => p.type === 'personne_morale').length,
        icon: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1',
        bg: 'bg-amber-100', color: 'text-amber-600',
    },
]);

// ── API ───────────────────────────────────────────────────────────────────────
const csrf = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
const headers = (withBody = false) => ({
    'X-Requested-With': 'XMLHttpRequest',
    'Accept': 'application/json',
    ...(withBody ? { 'Content-Type': 'application/json' } : {}),
    'X-CSRF-TOKEN': csrf(),
});

const fetchProprietaires = async () => {
    loading.value = true;
    try {
        const res = await fetch('/api/proprietaires', { headers: headers(), credentials: 'same-origin' });
        if (!res.ok) throw new Error();
        proprietaires.value = await res.json();
    } catch {
        showToast('Erreur de chargement.', 'error');
    } finally {
        loading.value = false;
    }
};

const save = async () => {
    saving.value = true;
    try {
        const url = editingProp.value ? `/api/proprietaires/${editingProp.value.id}` : '/api/proprietaires';
        const method = editingProp.value ? 'PUT' : 'POST';
        const res = await fetch(url, {
            method,
            headers: headers(true),
            credentials: 'same-origin',
            body: JSON.stringify(form.value),
        });
        const data = await res.json();
        if (!res.ok) throw new Error(data.message || Object.values(data.errors || {}).flat().join(' '));

        const proprietaireId = data.id || (editingProp.value ? editingProp.value.id : null);

        if (proprietaireId) {
            // Upload selected files
            for (const [key, file] of Object.entries(selectedFiles.value)) {
                if (file) {
                    const fd = new FormData();
                    fd.append('document', file);
                    fd.append('name', key.toUpperCase()); // CNI, KBIS, STATUTS, AUTRE

                    const uploadRes = await fetch(`/api/proprietaires/${proprietaireId}/documents`, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrf(),
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin',
                        body: fd,
                    });

                    if (!uploadRes.ok) {
                        const uploadData = await uploadRes.json();
                        console.error('Failed to upload document:', key, uploadData);
                    }
                }
            }
        }

        // Reset selected files
        selectedFiles.value = { cni: null, kbis: null, statuts: null, autre: null };

        await fetchProprietaires();

        // If the profile side panel was open, refresh it with updated data
        if (profileProp.value && profileProp.value.id === proprietaireId) {
            const updated = proprietaires.value.find(p => p.id === proprietaireId);
            if (updated) {
                profileProp.value = updated;
            }
        }

        closeModal();
        showToast(editingProp.value ? 'Propriétaire modifié.' : 'Propriétaire ajouté.', 'success');
    } catch (e) {
        showToast(e.message || 'Erreur.', 'error');
    } finally {
        saving.value = false;
    }
};

const confirmDelete = async () => {
    deleting.value = true;
    try {
        const res = await fetch(`/api/proprietaires/${deletingProp.value.id}`, {
            method: 'DELETE',
            headers: headers(),
            credentials: 'same-origin',
        });
        if (!res.ok) throw new Error();
        await fetchProprietaires();
        deletingProp.value = null;
        showToast('Propriétaire supprimé.', 'success');
    } catch {
        showToast('Erreur de suppression.', 'error');
    } finally {
        deleting.value = false;
    }
};

const onPhotoChange = async (e) => {
    const file = e.target.files[0];
    if (!file || !profileProp.value) return;
    uploadingPhoto.value = true;
    try {
        const fd = new FormData();
        fd.append('photo', file);
        const res = await fetch(`/api/proprietaires/${profileProp.value.id}/photo`, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf(), 'Accept': 'application/json' },
            credentials: 'same-origin',
            body: fd,
        });
        const data = await res.json();
        if (!res.ok) throw new Error(data.message || 'Erreur upload');
        // Update local state
        profileProp.value = { ...profileProp.value, photo_path: data.photo_url };
        const idx = proprietaires.value.findIndex(p => p.id === profileProp.value.id);
        if (idx !== -1) proprietaires.value[idx] = { ...proprietaires.value[idx], photo_path: data.photo_url };
        showToast('Photo mise à jour.', 'success');
    } catch (err) {
        showToast(err.message || 'Erreur upload.', 'error');
    } finally {
        uploadingPhoto.value = false;
        e.target.value = '';
    }
};

const deletePhoto = async () => {
    if (!profileProp.value) return;
    uploadingPhoto.value = true;
    try {
        const res = await fetch(`/api/proprietaires/${profileProp.value.id}/photo`, {
            method: 'DELETE',
            headers: headers(),
            credentials: 'same-origin',
        });
        if (!res.ok) throw new Error();
        profileProp.value = { ...profileProp.value, photo_path: null };
        const idx = proprietaires.value.findIndex(p => p.id === profileProp.value.id);
        if (idx !== -1) proprietaires.value[idx] = { ...proprietaires.value[idx], photo_path: null };
        showToast('Photo supprimée.', 'success');
    } catch {
        showToast('Erreur.', 'error');
    } finally {
        uploadingPhoto.value = false;
    }
};

const deleteDocument = async (index) => {
    if (!profileProp.value) return;
    const confirmDeleteDoc = confirm("Voulez-vous vraiment supprimer ce document ?");
    if (!confirmDeleteDoc) return;
    
    try {
        const res = await fetch(`/api/proprietaires/${profileProp.value.id}/documents/${index}`, {
            method: 'DELETE',
            headers: headers(),
            credentials: 'same-origin',
        });
        const data = await res.json();
        if (!res.ok) throw new Error(data.message || 'Erreur lors de la suppression');
        
        // Update local state
        profileProp.value = { ...profileProp.value, documents: data.documents };
        const idx = proprietaires.value.findIndex(p => p.id === profileProp.value.id);
        if (idx !== -1) {
            proprietaires.value[idx] = { ...proprietaires.value[idx], documents: data.documents };
        }
        showToast('Document supprimé.', 'success');
    } catch (err) {
        showToast(err.message || 'Erreur lors de la suppression du document.', 'error');
    }
};

// ── Modal helpers ─────────────────────────────────────────────────────────────
const openAddModal = () => {
    editingProp.value = null;
    form.value = emptyForm();
    showModal.value = true;
};

const openEdit = (p) => {
    editingProp.value = p;
    form.value = {
        type: p.type || 'personne_physique',
        nom: p.nom || '',
        prenom: p.prenom || '',
        raison_sociale: p.raison_sociale || '',
        siret: p.siret || '',
        numero_contribuable: p.numero_contribuable || '',
        piece_identite_type: p.piece_identite_type || '',
        piece_identite_numero: p.piece_identite_numero || '',
        email: p.email || '',
        telephone: p.telephone || '',
        telephone_secondaire: p.telephone_secondaire || '',
        adresse: p.adresse || '',
        ville: p.ville || '',
        pays: p.pays || 'CM',
        code_postal: p.code_postal || '',
        banque: p.banque || '',
        rib: p.rib || '',
        swift: p.swift || '',
        statut: p.statut || 'actif',
        date_debut_contrat: p.date_debut_contrat || '',
        date_fin_contrat: p.date_fin_contrat || '',
        commission_taux: p.commission_taux || '',
        type_contrat: p.type_contrat || '',
        notes: p.notes || '',
    };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editingProp.value = null;
};

const openProfile = (p) => { profileProp.value = p; };
const openDelete = (p) => { deletingProp.value = p; };

// ── Utils ─────────────────────────────────────────────────────────────────────
const capitalize = (s) => s ? s.charAt(0).toUpperCase() + s.slice(1) : '';

const formatContrat = (c) => ({
    gestion_locative: 'Gestion locative',
    syndic: 'Syndic',
    mandat_vente: 'Mandat de vente',
    mandat_gestion: 'Mandat de gestion',
})[c] || c;

let toastTimer;
const showToast = (message, type = 'success') => {
    clearTimeout(toastTimer);
    toast.value = { show: true, type, message };
    toastTimer = setTimeout(() => { toast.value.show = false; }, 3500);
};

onMounted(fetchProprietaires);
</script>

<style scoped>
.field-label {
    @apply block text-xs font-bold text-slate-500 uppercase mb-1;
}
.field-input {
    @apply w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition bg-white text-slate-800;
}

/* Page transition */
.page-enter-active, .page-leave-active { transition: opacity 0.2s, transform 0.2s; }
.page-enter-from, .page-leave-to { opacity: 0; transform: translateY(8px); }

/* Modal transition */
.modal-enter-active, .modal-leave-active { transition: opacity 0.2s; }
.modal-enter-from, .modal-leave-to { opacity: 0; }

/* Slide-over */
.slide-over-enter-active, .slide-over-leave-active { transition: opacity 0.25s; }
.slide-over-enter-from, .slide-over-leave-to { opacity: 0; }
.slide-over-enter-active .slide-over-panel,
.slide-over-leave-active .slide-over-panel { transition: transform 0.25s; }
.slide-over-enter-from .slide-over-panel,
.slide-over-leave-to .slide-over-panel { transform: translateX(100%); }

/* Toast */
.toast-enter-active, .toast-leave-active { transition: all 0.3s; }
.toast-enter-from, .toast-leave-to { opacity: 0; transform: translateY(12px) scale(0.95); }
</style>
