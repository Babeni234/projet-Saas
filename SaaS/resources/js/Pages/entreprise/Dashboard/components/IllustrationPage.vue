<template>
    <div class="flex flex-col gap-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 flex items-center gap-2">
                    Gestion des Illustrations
                    <span :class="[
                        'text-sm font-semibold px-2.5 py-1 rounded-full border',
                        isAgency ? 'bg-amber-50 text-amber-600 border-amber-200' : 'bg-indigo-50 text-indigo-600 border-indigo-200'
                    ]">Module Immobilier</span>
                </h1>
                <p class="text-slate-600 mt-1">Affecter des images et des vidéos aux biens immobiliers ou bâtiments et gérer les galeries.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <!-- Agency Filter (Enterprise dashboard only, when not locked to agency) -->
                <div v-if="!isAgency && agencies.length > 0" class="relative">
                    <select
                        v-model="selectedAgencyFilter"
                        class="pl-11 pr-10 py-3 bg-white border border-slate-200 rounded-xl appearance-none focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/15 transition-all duration-200 text-slate-700 font-semibold text-sm cursor-pointer shadow-sm min-w-[220px]"
                    >
                        <option value="">Toutes les agences</option>
                        <option value="none">Siège principal (Aucune agence)</option>
                        <option v-for="agency in agencies" :key="agency.id" :value="agency.id">
                            {{ agency.name }}
                        </option>
                    </select>
                    <!-- Left Icon: Professional Office Building Icon -->
                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <!-- Right Icon: Custom Down Chevron -->
                    <div class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                <button
                    @click="openAddModal"
                    :class="[
                        'flex items-center gap-2 px-5 py-3 text-white rounded-xl font-medium shadow-lg hover:shadow-xl hover:scale-[1.02] active:scale-95 transition-all duration-200',
                        isAgency ? 'bg-gradient-to-r from-amber-600 to-orange-600 shadow-amber-500/30' : 'bg-gradient-to-r from-indigo-600 to-violet-600 shadow-indigo-500/30'
                    ]"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nouvelle Illustration
                </button>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Illustrations -->
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl animate-fade-in" style="animation-delay: 0ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Total Médias</p>
                        <p class="text-3xl font-bold text-slate-900 mt-1">{{ totalMedias }}</p>
                    </div>
                    <div :class="['w-12 h-12 rounded-xl flex items-center justify-center', isAgency ? 'bg-amber-100/80 text-amber-600' : 'bg-indigo-100/80 text-indigo-600']">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-3 3 5z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <!-- Images count -->
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl animate-fade-in" style="animation-delay: 100ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Images</p>
                        <p class="text-3xl font-bold text-slate-900 mt-1">{{ totalImages }}</p>
                    </div>
                    <div :class="['w-12 h-12 rounded-xl flex items-center justify-center', isAgency ? 'bg-amber-100/80 text-amber-600' : 'bg-indigo-100/80 text-indigo-600']">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.12-1.12A1 1 0 0011.878 3H8.122a1 1 0 00-.707.293L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <!-- Videos count -->
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl animate-fade-in" style="animation-delay: 200ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Vidéos</p>
                        <p class="text-3xl font-bold text-slate-900 mt-1">{{ totalVideos }}</p>
                    </div>
                    <div :class="['w-12 h-12 rounded-xl flex items-center justify-center', isAgency ? 'bg-amber-100/80 text-amber-600' : 'bg-indigo-100/80 text-indigo-600']">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <!-- Targets count -->
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl animate-fade-in" style="animation-delay: 300ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Cibles Illustrées</p>
                        <p class="text-3xl font-bold text-slate-900 mt-1">{{ uniqueTargetsCount }}</p>
                    </div>
                    <div :class="['w-12 h-12 rounded-xl flex items-center justify-center', isAgency ? 'bg-amber-100/80 text-amber-600' : 'bg-indigo-100/80 text-indigo-600']">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-6">
            <h2 class="text-xl font-bold text-slate-900 mb-4">Parc Immobilier Affecté</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50">
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Cible / Élément</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Images</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Vidéos</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Dernière mise à jour</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-slate-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="target in groupedTargets" :key="target.type + '-' + target.id" class="hover:bg-slate-50/80 transition-all duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div :class="[
                                        'w-10 h-10 rounded-xl flex items-center justify-center text-white font-bold',
                                        target.type === 'batiment' ? 'bg-gradient-to-tr from-cyan-500 to-blue-600' : 'bg-gradient-to-tr from-emerald-500 to-teal-600'
                                    ]">
                                        <svg v-if="target.type === 'batiment'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-900 block">{{ target.name }}</span>
                                        <span class="text-[10px] text-slate-400">ID: {{ target.id }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="[
                                    'px-3 py-1 rounded-full text-xs font-semibold',
                                    target.type === 'batiment' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                                ]">
                                    {{ target.type === 'batiment' ? 'Bâtiment' : 'Bien Immobilier' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 font-medium">
                                {{ target.images_count }} image(s)
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 font-medium">
                                {{ target.videos_count }} vidéo(s)
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500">
                                {{ formatDate(target.last_updated) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <button
                                    @click="openGalleryModal(target)"
                                    :class="[
                                        'px-4 py-2 text-xs font-bold rounded-lg border hover:scale-105 active:scale-95 transition-all shadow-sm',
                                        isAgency 
                                            ? 'border-amber-200 text-amber-700 bg-amber-50 hover:bg-amber-100 hover:border-amber-300' 
                                            : 'border-indigo-200 text-indigo-700 bg-indigo-50 hover:bg-indigo-100 hover:border-indigo-300'
                                    ]"
                                >
                                    Galerie
                                </button>
                            </td>
                        </tr>
                        <tr v-if="illustrationsLoading">
                            <td colspan="6" class="text-center py-12">
                                <div class="flex flex-col items-center justify-center gap-3 text-slate-400">
                                    <svg class="animate-spin h-8 w-8" :class="isAgency ? 'text-amber-500' : 'text-indigo-500'" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                    </svg>
                                    <span class="text-sm font-medium">Chargement des illustrations...</span>
                                </div>
                            </td>
                        </tr>
                        <tr v-else-if="groupedTargets.length === 0">
                            <td colspan="6" class="text-center py-12 text-slate-400">
                                Aucun bien immobilier ou bâtiment n'a encore d'illustration affectée. Cliquez sur "Nouvelle Illustration" pour commencer.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Illustration Modal -->
        <div v-if="showAddModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-xl w-full overflow-hidden animate-scale-up border border-slate-100">
                <!-- Header -->
                <div :class="['px-6 py-5 border-b border-slate-100 flex items-center justify-between', isAgency ? 'bg-gradient-to-r from-amber-50 to-orange-50/50' : 'bg-gradient-to-r from-indigo-50 to-violet-50/50']">
                    <div class="flex items-center gap-3">
                        <div :class="['w-10 h-10 rounded-xl flex items-center justify-center shadow-sm', isAgency ? 'bg-amber-100 text-amber-600' : 'bg-indigo-100 text-indigo-600']">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">Affecter des Médias</h2>
                            <p class="text-xs text-slate-500">Sélectionnez un bien immobilier ou un bâtiment pour y lier des images ou vidéos.</p>
                        </div>
                    </div>
                    <button @click="closeAddModal" class="text-slate-400 hover:text-slate-600 transition p-1.5 hover:bg-slate-100 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Form Body -->
                <form @submit.prevent="submitForm" class="p-6 space-y-4">
                    <!-- Target Type selection -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Type de cible</label>
                        <div class="grid grid-cols-2 gap-4">
                            <button
                                type="button"
                                @click="newForm.target_type = 'batiment'; newForm.target_id = ''; newForm.target_name = ''"
                                :class="[
                                    'py-3 border-2 rounded-xl text-sm font-semibold transition flex items-center justify-center gap-2',
                                    newForm.target_type === 'batiment'
                                        ? (isAgency ? 'border-amber-600 bg-amber-50/50 text-amber-700' : 'border-indigo-600 bg-indigo-50/50 text-indigo-700')
                                        : 'border-slate-200 text-slate-600 hover:bg-slate-50'
                                ]"
                            >
                                Bâtiment
                            </button>
                            <button
                                type="button"
                                @click="newForm.target_type = 'logement'; newForm.target_id = ''; newForm.target_name = ''"
                                :class="[
                                    'py-3 border-2 rounded-xl text-sm font-semibold transition flex items-center justify-center gap-2',
                                    newForm.target_type === 'logement'
                                        ? (isAgency ? 'border-amber-600 bg-amber-50/50 text-amber-700' : 'border-indigo-600 bg-indigo-50/50 text-indigo-700')
                                        : 'border-slate-200 text-slate-600 hover:bg-slate-50'
                                ]"
                            >
                                Bien Immobilier
                            </button>
                        </div>
                    </div>

                    <!-- Target selection -->
                    <div v-if="newForm.target_type">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">
                            Sélectionner le {{ newForm.target_type === 'batiment' ? 'bâtiment' : 'bien immobilier' }}
                        </label>
                        <select
                            v-model="selectedTargetKey"
                            :class="[
                                'w-full px-4 py-2.5 border rounded-xl text-sm focus:ring-2 focus:border-transparent transition bg-white text-slate-700',
                                isAgency ? 'focus:ring-amber-500' : 'focus:ring-indigo-500',
                                selectedTargetKey ? 'border-slate-200' : 'border-rose-300 bg-rose-50/10'
                            ]"
                        >
                            <option value="">-- Choisir un élément --</option>
                            <template v-if="newForm.target_type === 'batiment'">
                                <option v-for="b in localBatiments" :key="b.id" :value="JSON.stringify({id: b.id, name: b.nom})">
                                    {{ b.nom }} ({{ b.ville }})
                                </option>
                            </template>
                            <template v-else>
                                <option v-for="l in localLogements" :key="l.id" :value="JSON.stringify({id: l.id, name: l.reference})">
                                    {{ l.reference }} - {{ l.categorie }} ({{ l.batiment }})
                                </option>
                            </template>
                        </select>
                    </div>

                    <!-- Agency Selection (Enterprise dashboard only) -->
                    <div v-if="!isAgency && agencies.length > 0">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Rattacher à une agence (Facultatif)</label>
                        <select
                            v-model="newForm.agency_id"
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition bg-white text-slate-700"
                        >
                            <option value="">Aucune agence (Siège principal)</option>
                            <option v-for="agency in agencies" :key="agency.id" :value="agency.id">
                                {{ agency.name }} ({{ agency.city }})
                            </option>
                        </select>
                    </div>

                    <!-- Photos upload field -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Photos (Images)</label>
                        <div
                            @click="triggerPhotoInput"
                            :class="[
                                'border-2 border-dashed rounded-xl p-4 text-center cursor-pointer transition-all flex flex-col items-center justify-center bg-slate-50/50 hover:bg-slate-50',
                                isAgency ? 'border-amber-200 hover:border-amber-400' : 'border-indigo-200 hover:border-indigo-400'
                            ]"
                        >
                            <input
                                type="file"
                                ref="photoInput"
                                @change="handlePhotoChange"
                                multiple
                                class="hidden"
                                accept="image/*"
                            >
                            <svg :class="['w-6 h-6 mb-1', isAgency ? 'text-amber-500' : 'text-indigo-500']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-xs font-bold text-slate-700">Sélectionner des Photos</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">Maximum 100 photos simultanées (JPG, PNG, WEBP)</p>
                        </div>
                        <!-- Photos Selected listing -->
                        <div v-if="selectedPhotos.length > 0" class="mt-2 space-y-1 max-h-24 overflow-y-auto pr-1">
                            <div v-for="(file, idx) in selectedPhotos" :key="idx" class="flex items-center justify-between text-[11px] bg-slate-50 border border-slate-100 rounded-lg px-2.5 py-1 font-medium">
                                <span class="truncate text-slate-700 max-w-[280px]">{{ file.name }}</span>
                                <div class="flex items-center gap-2">
                                    <span class="text-[9px] text-slate-400 font-semibold uppercase">{{ formatSize(file.size) }}</span>
                                    <button type="button" @click="removeSelectedPhoto(idx)" class="text-rose-500 hover:text-rose-700 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Videos upload field -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Vidéos</label>
                        <div
                            @click="triggerVideoInput"
                            :class="[
                                'border-2 border-dashed rounded-xl p-4 text-center cursor-pointer transition-all flex flex-col items-center justify-center bg-slate-50/50 hover:bg-slate-50',
                                isAgency ? 'border-amber-200 hover:border-amber-400' : 'border-indigo-200 hover:border-indigo-400'
                            ]"
                        >
                            <input
                                type="file"
                                ref="videoInput"
                                @change="handleVideoChange"
                                multiple
                                class="hidden"
                                accept="video/*"
                            >
                            <svg :class="['w-6 h-6 mb-1', isAgency ? 'text-amber-500' : 'text-indigo-500']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 00-2 2z" />
                            </svg>
                            <p class="text-xs font-bold text-slate-700">Sélectionner des Vidéos</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">Maximum 5 vidéos simultanées (MP4, WEBM, MOV)</p>
                        </div>
                        <!-- Videos Selected listing -->
                        <div v-if="selectedVideos.length > 0" class="mt-2 space-y-1 max-h-24 overflow-y-auto pr-1">
                            <div v-for="(file, idx) in selectedVideos" :key="idx" class="flex items-center justify-between text-[11px] bg-slate-50 border border-slate-100 rounded-lg px-2.5 py-1 font-medium">
                                <span class="truncate text-slate-700 max-w-[280px]">{{ file.name }}</span>
                                <div class="flex items-center gap-2">
                                    <span class="text-[9px] text-slate-400 font-semibold uppercase">{{ formatSize(file.size) }}</span>
                                    <button type="button" @click="removeSelectedVideo(idx)" class="text-rose-500 hover:text-rose-700 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Description (Facultatif)</label>
                        <textarea
                            v-model="newForm.description"
                            rows="2"
                            placeholder="Entrez une brève description ou un commentaire pour ces fichiers..."
                            :class="[
                                'w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:border-transparent transition bg-white',
                                isAgency ? 'focus:ring-amber-500' : 'focus:ring-indigo-500'
                            ]"
                        ></textarea>
                    </div>

                    <!-- Footer buttons -->
                    <div class="pt-4 border-t border-slate-100 flex gap-3 justify-end">
                        <button
                            type="button"
                            @click="closeAddModal"
                            class="px-5 py-2.5 border border-slate-200 text-slate-700 font-medium rounded-xl hover:bg-slate-100 transition text-sm"
                        >
                            Annuler
                        </button>
                        <button
                            type="submit"
                            :disabled="!selectedTargetKey || (selectedPhotos.length === 0 && selectedVideos.length === 0) || uploading"
                            :class="[
                                'px-5 py-2.5 text-white font-medium rounded-xl disabled:opacity-50 disabled:cursor-not-allowed shadow transition text-sm flex items-center gap-2',
                                isAgency ? 'bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700' : 'bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700'
                            ]"
                        >
                            <svg v-if="uploading" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                            </svg>
                            {{ uploading ? 'Importation...' : 'Enregistrer' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Target Gallery Modal -->
        <div v-if="showGalleryModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-5xl w-full max-h-[92vh] animate-scale-up border border-slate-100 flex flex-col" style="height: 92vh;">

                <!-- ====== HEADER FIXE (toujours visible) ====== -->
                <div :class="['px-6 py-4 border-b border-slate-100 flex items-center justify-between shrink-0 z-10', isAgency ? 'bg-gradient-to-r from-amber-50 to-orange-50/50' : 'bg-gradient-to-r from-indigo-50 to-violet-50/50']">
                    <div class="flex items-center gap-3">
                        <div :class="['w-10 h-10 rounded-xl flex items-center justify-center shadow-sm shrink-0', isAgency ? 'bg-amber-100 text-amber-600' : 'bg-indigo-100 text-indigo-600']">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h2 class="text-lg font-bold text-slate-900 truncate">Galerie — {{ selectedTarget?.name }}</h2>
                            <p class="text-xs text-slate-500">{{ filteredGalleryMedias.length }} média(s) · Cliquez sur un média pour l'agrandir</p>
                        </div>
                    </div>
                    <button
                        @click="closeGalleryModal"
                        class="ml-4 shrink-0 w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-all"
                        title="Fermer"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- ====== TABS FIXES ====== -->
                <div class="px-6 py-3 border-b border-slate-100 bg-slate-50/50 flex flex-wrap items-center justify-between gap-3 shrink-0">
                    <div class="flex bg-slate-200/60 p-1 rounded-xl w-fit gap-1">
                        <!-- Tab: Tout -->
                        <button
                            @click="galleryFilter = 'all'"
                            :class="[
                                'flex items-center gap-1.5 px-4 py-1.5 text-xs font-bold rounded-lg transition-all',
                                galleryFilter === 'all'
                                    ? (isAgency ? 'bg-amber-600 text-white shadow' : 'bg-indigo-600 text-white shadow')
                                    : 'text-slate-500 hover:text-slate-700 hover:bg-white/70'
                            ]"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                            Tout
                        </button>
                        <!-- Tab: Images -->
                        <button
                            @click="galleryFilter = 'image'"
                            :class="[
                                'flex items-center gap-1.5 px-4 py-1.5 text-xs font-bold rounded-lg transition-all',
                                galleryFilter === 'image'
                                    ? (isAgency ? 'bg-amber-600 text-white shadow' : 'bg-indigo-600 text-white shadow')
                                    : 'text-slate-500 hover:text-slate-700 hover:bg-white/70'
                            ]"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Images
                        </button>
                        <!-- Tab: Vidéos -->
                        <button
                            @click="galleryFilter = 'video'"
                            :class="[
                                'flex items-center gap-1.5 px-4 py-1.5 text-xs font-bold rounded-lg transition-all',
                                galleryFilter === 'video'
                                    ? (isAgency ? 'bg-amber-600 text-white shadow' : 'bg-indigo-600 text-white shadow')
                                    : 'text-slate-500 hover:text-slate-700 hover:bg-white/70'
                            ]"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            Vidéos
                        </button>
                    </div>
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide">
                        {{ filteredGalleryMedias.length }} affiché(s)
                    </span>
                </div>

                <!-- ====== CORPS SCROLLABLE ====== -->
                <div class="flex-1 overflow-y-auto p-6 scrollbar-thin">
                    <div v-if="filteredGalleryMedias.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        <div
                            v-for="media in filteredGalleryMedias"
                            :key="media.id"
                            class="bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm flex flex-col group hover:shadow-lg hover:border-slate-200 transition-all duration-200"
                        >
                            <!-- Vignette cliquable → Lightbox -->
                            <div
                                class="aspect-video w-full bg-slate-950 relative overflow-hidden cursor-pointer"
                                @click="openLightbox(media)"
                                :title="media.media_type === 'image' ? 'Cliquer pour agrandir' : 'Cliquer pour lire en plein écran'"
                            >
                                <img
                                    v-if="media.media_type === 'image'"
                                    :src="media.file_path.startsWith('http') ? media.file_path : '/storage/' + media.file_path"
                                    class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500"
                                    alt="Illustration"
                                    loading="lazy"
                                >
                                <video
                                    v-else
                                    :src="media.file_path.startsWith('http') ? media.file_path : '/storage/' + media.file_path"
                                    class="w-full h-full object-cover pointer-events-none"
                                    preload="metadata"
                                ></video>

                                <!-- Badge type -->
                                <span :class="[
                                    'absolute top-2 left-2 px-2 py-0.5 rounded-md text-[9px] font-bold text-white tracking-widest uppercase shadow',
                                    media.media_type === 'image' ? 'bg-blue-600' : 'bg-rose-600'
                                ]">{{ media.media_type }}</span>

                                <!-- Overlay expand au survol -->
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-all duration-300 flex items-center justify-center">
                                    <div class="opacity-0 group-hover:opacity-100 transition-all duration-200 transform scale-75 group-hover:scale-100">
                                        <div class="bg-white/20 backdrop-blur-md rounded-full p-3 border border-white/30 shadow-xl">
                                            <svg v-if="media.media_type === 'image'" class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                            </svg>
                                            <svg v-else class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Icône vidéo permanente si video -->
                                <div v-if="media.media_type === 'video'" class="absolute bottom-2 right-2 bg-black/60 rounded-lg px-2 py-1 flex items-center gap-1">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z" />
                                    </svg>
                                    <span class="text-white text-[9px] font-bold">VIDÉO</span>
                                </div>
                            </div>

                            <!-- Détails + Actions -->
                            <div class="p-4 flex flex-col gap-2">
                                <h4 class="font-bold text-slate-800 text-sm truncate" :title="media.file_name">{{ media.file_name }}</h4>
                                <p class="text-[10px] text-slate-400 font-semibold uppercase">{{ formatDate(media.created_at) }}</p>
                                <p v-if="media.description" class="text-xs text-slate-500 line-clamp-2 italic">"{{ media.description }}"</p>

                                <div class="mt-2 pt-2 border-t border-slate-100 flex items-center gap-2 justify-between">
                                    <!-- Bouton agrandir -->
                                    <button
                                        @click="openLightbox(media)"
                                        :class="['flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg border transition-all', isAgency ? 'border-amber-200 text-amber-700 bg-amber-50 hover:bg-amber-100' : 'border-indigo-200 text-indigo-700 bg-indigo-50 hover:bg-indigo-100']"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                                        </svg>
                                        Agrandir
                                    </button>
                                    <div class="flex gap-1.5">
                                        <button @click="openEditModal(media)" class="p-1.5 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Modifier">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                        <button @click="openDeleteModal(media)" class="p-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Supprimer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="flex flex-col items-center justify-center py-24 text-slate-400">
                        <svg class="w-16 h-16 mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="font-semibold text-sm">Aucun média dans cette catégorie</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ====================================================== -->
        <!-- LIGHTBOX PLEIN ÉCRAN (Image ou Vidéo) -->
        <!-- ====================================================== -->
        <Teleport to="body">
            <div
                v-if="lightboxMedia"
                class="fixed inset-0 z-[100] flex items-center justify-center"
                style="background: rgba(0,0,0,0.95);"
                @click.self="closeLightbox"
            >
                <!-- Bouton Fermer -->
                <button
                    @click="closeLightbox"
                    class="absolute top-4 right-4 z-10 w-11 h-11 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/25 border border-white/20 text-white transition-all"
                    title="Fermer (Échap)"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Compteur -->
                <div class="absolute top-4 left-1/2 -translate-x-1/2 bg-black/50 text-white text-xs font-bold px-4 py-2 rounded-full backdrop-blur-sm border border-white/10">
                    {{ lightboxIndex + 1 }} / {{ filteredGalleryMedias.length }}
                </div>

                <!-- Flèche Gauche -->
                <button
                    v-if="filteredGalleryMedias.length > 1"
                    @click="lightboxPrev"
                    class="absolute left-4 top-1/2 -translate-y-1/2 z-10 w-12 h-12 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/25 border border-white/20 text-white transition-all hover:scale-110"
                    title="Précédent (←)"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <!-- Flèche Droite -->
                <button
                    v-if="filteredGalleryMedias.length > 1"
                    @click="lightboxNext"
                    class="absolute right-4 top-1/2 -translate-y-1/2 z-10 w-12 h-12 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/25 border border-white/20 text-white transition-all hover:scale-110"
                    title="Suivant (→)"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <!-- Media Plein Écran -->
                <div class="flex flex-col items-center justify-center px-20 max-w-full max-h-full" style="max-width: 92vw; max-height: 88vh;">
                    <img
                        v-if="lightboxMedia.media_type === 'image'"
                        :src="lightboxMedia.file_path.startsWith('http') ? lightboxMedia.file_path : '/storage/' + lightboxMedia.file_path"
                        :alt="lightboxMedia.file_name"
                        class="rounded-xl shadow-2xl object-contain animate-scale-up"
                        style="max-width: 88vw; max-height: 78vh;"
                    >
                    <video
                        v-else
                        :src="lightboxMedia.file_path.startsWith('http') ? lightboxMedia.file_path : '/storage/' + lightboxMedia.file_path"
                        controls
                        autoplay
                        class="rounded-xl shadow-2xl animate-scale-up"
                        style="max-width: 88vw; max-height: 78vh;"
                    ></video>

                    <!-- Légende -->
                    <div class="mt-4 text-center">
                        <p class="text-white font-semibold text-sm">{{ lightboxMedia.file_name }}</p>
                        <p v-if="lightboxMedia.description" class="text-white/60 text-xs mt-1 italic">{{ lightboxMedia.description }}</p>
                        <span :class="['mt-2 inline-block px-3 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-widest', lightboxMedia.media_type === 'image' ? 'bg-blue-600/80 text-white' : 'bg-rose-600/80 text-white']">
                            {{ lightboxMedia.media_type }}
                        </span>
                    </div>
                </div>

                <!-- Miniatures en bas -->
                <div v-if="filteredGalleryMedias.length > 1" class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 px-4 overflow-x-auto max-w-[90vw] scrollbar-thin pb-1">
                    <button
                        v-for="(m, idx) in filteredGalleryMedias"
                        :key="m.id"
                        @click="lightboxGoTo(idx)"
                        :class="['w-12 h-12 rounded-lg overflow-hidden border-2 shrink-0 transition-all hover:scale-110', idx === lightboxIndex ? 'border-white shadow-lg scale-110' : 'border-white/30 opacity-60']"
                    >
                        <img
                            v-if="m.media_type === 'image'"
                            :src="m.file_path.startsWith('http') ? m.file_path : '/storage/' + m.file_path"
                            class="w-full h-full object-cover"
                            loading="lazy"
                        >
                        <div v-else class="w-full h-full bg-slate-800 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                </div>
            </div>
        </Teleport>

        <!-- Edit Description Sub-Modal -->
        <div v-if="showEditModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[60] flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 animate-scale-up border border-slate-100">
                <h3 class="text-lg font-bold text-slate-900 mb-1">Modifier les Détails</h3>
                <p class="text-xs text-slate-500 mb-4">Mettre à jour les métadonnées de l'illustration.</p>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nom affiché</label>
                        <input
                            v-model="editForm.target_name"
                            type="text"
                            class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition bg-white"
                        >
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Description</label>
                        <textarea
                            v-model="editForm.description"
                            rows="3"
                            class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition bg-white"
                        ></textarea>
                    </div>
                </div>

                <div class="mt-6 flex gap-3 justify-end">
                    <button @click="closeEditModal" class="px-4 py-2 border border-slate-200 text-slate-700 font-medium rounded-xl hover:bg-slate-50 transition text-sm">Annuler</button>
                    <button @click="saveEdit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition text-sm">Enregistrer</button>
                </div>
            </div>
        </div>

        <!-- Delete Media Confirmation Sub-Modal -->
        <div v-if="showDeleteModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[60] flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 animate-scale-up">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100 mx-auto mb-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-center text-slate-900 mb-1">Supprimer l'illustration ?</h3>
                <p class="text-center text-slate-500 text-xs mb-6">Cette action est irréversible. Le fichier sera définitivement effacé du serveur.</p>

                <div class="flex gap-3">
                    <button @click="closeDeleteModal" class="flex-1 px-3 py-2 border border-slate-200 text-slate-700 font-semibold rounded-xl hover:bg-slate-50 transition text-xs">Annuler</button>
                    <button @click="confirmDelete" class="flex-1 px-3 py-2 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 transition text-xs">Supprimer</button>
                </div>
            </div>
        </div>

        <!-- Success Alert Modal -->
        <div v-if="showSuccess" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 animate-scale-up">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-emerald-100 mx-auto mb-4">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-center text-slate-900 mb-2">Opération réussie</h3>
                <p class="text-center text-slate-555 text-sm mb-6">{{ successMessage }}</p>

                <button @click="showSuccess = false" class="w-full px-4 py-2.5 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 shadow shadow-emerald-500/20 transition text-sm">Fermer</button>
            </div>
        </div>

        <!-- Error Alert Modal -->
        <div v-if="showError" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 animate-scale-up">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-rose-100 mx-auto mb-4">
                    <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-center text-slate-900 mb-2">Erreur</h3>
                <p class="text-center text-slate-600 text-sm mb-6 whitespace-pre-line">{{ errorMessage }}</p>

                <button @click="showError = false" class="w-full px-4 py-2.5 bg-rose-600 text-white font-semibold rounded-xl hover:bg-rose-700 transition text-sm">Fermer</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
import { useRoute } from 'vue-router';
import { usePage, router } from '@inertiajs/vue3';

const route = useRoute();
const page = usePage();

const isAgency = computed(() => route.name?.startsWith('agence.') || !!currentAgencyId.value);
const currentAgencyId = computed(() => page.props.auth?.user?.employee?.agency_id || null);

// Illustrations fetched via AJAX (not from Inertia props, since the page is an SPA sub-route)
const illustrationsData = ref([]);
const illustrationsLoading = ref(false);
const agencies = computed(() => {
    const raw = page.props.agencies;
    if (!raw) return [];
    return Array.isArray(raw) ? raw : (raw.data || []);
});

// Fetch illustrations from our JSON API endpoint
const fetchIllustrations = async () => {
    illustrationsLoading.value = true;
    try {
        const response = await fetch('/api/illustrations', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            credentials: 'same-origin',
        });
        if (response.ok) {
            const data = await response.json();
            illustrationsData.value = data.illustrations || [];
        } else {
            console.error('Failed to fetch illustrations:', response.status);
        }
    } catch (err) {
        console.error('Error fetching illustrations:', err);
    } finally {
        illustrationsLoading.value = false;
    }
};

// Filter illustrations based on connected user agency scope or selected agency filter
const selectedAgencyFilter = ref('');

const filteredIllustrations = computed(() => {
    let list = illustrationsData.value;
    const activeAgencyId = isAgency.value ? currentAgencyId.value : selectedAgencyFilter.value;
    
    if (activeAgencyId) {
        if (activeAgencyId === 'none') {
            list = list.filter(item => !item.agency_id);
        } else {
            list = list.filter(item => Number(item.agency_id) === Number(activeAgencyId));
        }
    }
    return list;
});

// Total counts
const totalMedias = computed(() => filteredIllustrations.value.length);
const totalImages = computed(() => filteredIllustrations.value.filter(m => m.media_type === 'image').length);
const totalVideos = computed(() => filteredIllustrations.value.filter(m => m.media_type === 'video').length);

const localBatiments = ref([]);
const localLogements = ref([]);
const dbBatiments = ref([]);
const dbLogements = ref([]);

const fetchDbData = async () => {
    try {
        const [resBat, resLog] = await Promise.all([
            fetch('/api/batiments', { headers: { 'Accept': 'application/json' } }),
            fetch('/api/logements', { headers: { 'Accept': 'application/json' } })
        ]);
        if (resBat.ok) {
            dbBatiments.value = await resBat.json();
        }
        if (resLog.ok) {
            dbLogements.value = await resLog.json();
        }
        filterData();
    } catch (err) {
        console.error('Error fetching database batiments/logements:', err);
    }
};

const filterData = () => {
    let bats = [...dbBatiments.value];
    let logs = [...dbLogements.value];

    const activeAgencyId = isAgency.value ? currentAgencyId.value : selectedAgencyFilter.value;

    if (activeAgencyId) {
        if (activeAgencyId === 'none') {
            bats = bats.filter(b => !b.agency_id);
        } else {
            const agencyIdNum = Number(activeAgencyId);
            bats = bats.filter(b => Number(b.agency_id) === agencyIdNum);
        }
    }

    if (activeAgencyId) {
        if (activeAgencyId === 'none') {
            logs = logs.filter(l => !l.agency_id);
        } else {
            const agencyIdNum = Number(activeAgencyId);
            logs = logs.filter(l => Number(l.agency_id) === agencyIdNum);
        }
    }

    localBatiments.value = bats;
    localLogements.value = logs;
};

// Re-load data when filter changes
watch(selectedAgencyFilter, () => {
    filterData();
});

// ==========================================
// LIGHTBOX (plein écran image / vidéo)
// ==========================================
const lightboxMedia = ref(null);
const lightboxIndex = ref(0);

const openLightbox = (media) => {
    const idx = filteredGalleryMedias.value.findIndex(m => m.id === media.id);
    lightboxIndex.value = idx >= 0 ? idx : 0;
    lightboxMedia.value = media;
};

const closeLightbox = () => {
    lightboxMedia.value = null;
};

const lightboxPrev = () => {
    const list = filteredGalleryMedias.value;
    if (!list.length) return;
    lightboxIndex.value = (lightboxIndex.value - 1 + list.length) % list.length;
    lightboxMedia.value = list[lightboxIndex.value];
};

const lightboxNext = () => {
    const list = filteredGalleryMedias.value;
    if (!list.length) return;
    lightboxIndex.value = (lightboxIndex.value + 1) % list.length;
    lightboxMedia.value = list[lightboxIndex.value];
};

const lightboxGoTo = (idx) => {
    const list = filteredGalleryMedias.value;
    if (idx >= 0 && idx < list.length) {
        lightboxIndex.value = idx;
        lightboxMedia.value = list[idx];
    }
};

// Navigation clavier : Escape = fermer, ← → = naviguer
const handleKeydown = (e) => {
    if (lightboxMedia.value) {
        if (e.key === 'Escape') closeLightbox();
        else if (e.key === 'ArrowLeft') lightboxPrev();
        else if (e.key === 'ArrowRight') lightboxNext();
    } else if (showGalleryModal.value && e.key === 'Escape') {
        closeGalleryModal();
    }
};

onMounted(async () => {
    await fetchDbData();
    await fetchIllustrations();
    window.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeydown);
});

// Grouped items having illustrations
const groupedTargets = computed(() => {
    const map = {};
    
    filteredIllustrations.value.forEach(item => {
        const key = `${item.target_type}-${item.target_id}`;
        if (!map[key]) {
            map[key] = {
                type: item.target_type,
                id: item.target_id,
                name: item.target_name,
                images_count: 0,
                videos_count: 0,
                last_updated: item.updated_at || item.created_at,
            };
        }
        
        if (item.media_type === 'image') {
            map[key].images_count++;
        } else {
            map[key].videos_count++;
        }
        
        const itemDate = new Date(item.updated_at || item.created_at);
        const mapDate = new Date(map[key].last_updated);
        if (itemDate > mapDate) {
            map[key].last_updated = item.updated_at || item.created_at;
        }
    });
    
    return Object.values(map).sort((a, b) => new Date(b.last_updated) - new Date(a.last_updated));
});

const uniqueTargetsCount = computed(() => groupedTargets.value.length);

// Modal states
const showAddModal = ref(false);
const showGalleryModal = ref(false);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const showSuccess = ref(false);
const showError = ref(false);

const successMessage = ref('');
const errorMessage = ref('');

// Add Form data
const newForm = ref({
    target_type: 'logement',
    target_id: '',
    target_name: '',
    agency_id: '',
    description: '',
});

const selectedTargetKey = ref('');

// Synchronize target selection details
watch(selectedTargetKey, (newVal) => {
    if (!newVal) {
        newForm.value.target_id = '';
        newForm.value.target_name = '';
        return;
    }
    try {
        const parsed = JSON.parse(newVal);
        newForm.value.target_id = String(parsed.id);
        newForm.value.target_name = parsed.name;
    } catch (e) {
        console.error('Failed parsing target selection key', e);
    }
});

const selectedPhotos = ref([]);
const selectedVideos = ref([]);
const photoInput = ref(null);
const videoInput = ref(null);
const uploading = ref(false);

const triggerPhotoInput = () => {
    photoInput.value?.click();
};

const triggerVideoInput = () => {
    videoInput.value?.click();
};

const handlePhotoChange = (e) => {
    const files = Array.from(e.target.files || []);
    if (selectedPhotos.value.length + files.length > 100) {
        errorMessage.value = "Vous ne pouvez pas ajouter plus de 100 photos à la fois.";
        showError.value = true;
        return;
    }
    files.forEach(file => {
        if (!selectedPhotos.value.some(f => f.name === file.name && f.size === file.size)) {
            selectedPhotos.value.push(file);
        }
    });
};

const handleVideoChange = (e) => {
    const files = Array.from(e.target.files || []);
    if (selectedVideos.value.length + files.length > 5) {
        errorMessage.value = "Vous ne pouvez pas ajouter plus de 5 vidéos à la fois.";
        showError.value = true;
        return;
    }
    files.forEach(file => {
        if (!selectedVideos.value.some(f => f.name === file.name && f.size === file.size)) {
            selectedVideos.value.push(file);
        }
    });
};

const removeSelectedPhoto = (idx) => {
    selectedPhotos.value.splice(idx, 1);
};

const removeSelectedVideo = (idx) => {
    selectedVideos.value.splice(idx, 1);
};

const formatSize = (bytes) => {
    if (!bytes) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

const openAddModal = () => {
    newForm.value = {
        target_type: 'logement',
        target_id: '',
        target_name: '',
        agency_id: selectedAgencyFilter.value === 'none' ? '' : selectedAgencyFilter.value,
        description: '',
    };
    selectedTargetKey.value = '';
    selectedPhotos.value = [];
    selectedVideos.value = [];
    showAddModal.value = true;
};

const closeAddModal = () => {
    showAddModal.value = false;
};

const submitForm = () => {
    if (!selectedTargetKey.value || (selectedPhotos.value.length === 0 && selectedVideos.value.length === 0)) return;

    const data = new FormData();
    data.append('target_type', newForm.value.target_type);
    data.append('target_id', newForm.value.target_id);
    data.append('target_name', newForm.value.target_name);
    
    if (newForm.value.agency_id) {
        data.append('agency_id', newForm.value.agency_id);
    }
    if (newForm.value.description) {
        data.append('description', newForm.value.description);
    }
    
    selectedPhotos.value.forEach(file => {
        data.append('photos[]', file);
    });

    selectedVideos.value.forEach(file => {
        data.append('videos[]', file);
    });

    uploading.value = true;
    router.post('/api/illustrations', data, {
        forceFormData: true,
        onSuccess: async () => {
            uploading.value = false;
            showAddModal.value = false;
            selectedPhotos.value = [];
            selectedVideos.value = [];
            await fetchIllustrations();
            successMessage.value = 'Illustration affectée avec succès.';
            showSuccess.value = true;
        },
        onError: (err) => {
            uploading.value = false;
            errorMessage.value = Object.values(err).join('\n');
            showError.value = true;
        }
    });
};

// Gallery Viewer variables
const selectedTarget = ref(null);
const galleryFilter = ref('all'); // all, image, video

const openGalleryModal = (target) => {
    selectedTarget.value = target;
    galleryFilter.value = 'all';
    showGalleryModal.value = true;
};

const closeGalleryModal = () => {
    showGalleryModal.value = false;
    selectedTarget.value = null;
};

const filteredGalleryMedias = computed(() => {
    if (!selectedTarget.value) return [];
    
    let list = illustrationsData.value.filter(item => 
        item.target_type === selectedTarget.value.type &&
        String(item.target_id) === String(selectedTarget.value.id)
    );
    
    if (galleryFilter.value !== 'all') {
        list = list.filter(item => item.media_type === galleryFilter.value);
    }
    
    return list;
});

// Edit Details variables
const editingMedia = ref(null);
const editForm = ref({
    target_name: '',
    description: '',
});

const openEditModal = (media) => {
    editingMedia.value = media;
    editForm.value = {
        target_name: media.target_name,
        description: media.description || '',
    };
    showEditModal.value = true;
};

const closeEditModal = () => {
    showEditModal.value = false;
    editingMedia.value = null;
};

const saveEdit = () => {
    if (!editingMedia.value) return;
    
    router.put(`/api/illustrations/${editingMedia.value.id}`, editForm.value, {
        onSuccess: async () => {
            await fetchIllustrations();
            if (selectedTarget.value) {
                selectedTarget.value.name = editForm.value.target_name;
            }
            showEditModal.value = false;
            successMessage.value = 'Informations de l\'illustration mises à jour.';
            showSuccess.value = true;
        },
        onError: (err) => {
            errorMessage.value = Object.values(err).join('\n');
            showError.value = true;
        }
    });
};

// Delete media variables
const deletingMedia = ref(null);

const openDeleteModal = (media) => {
    deletingMedia.value = media;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    deletingMedia.value = null;
};

const confirmDelete = () => {
    if (!deletingMedia.value) return;
    
    router.delete(`/api/illustrations/${deletingMedia.value.id}`, {
        onSuccess: async () => {
            showDeleteModal.value = false;
            deletingMedia.value = null;
            await fetchIllustrations();
            successMessage.value = 'Média supprimé avec succès.';
            showSuccess.value = true;
            
            // Close main gallery if no media left
            if (filteredGalleryMedias.value.length === 0) {
                showGalleryModal.value = false;
            }
        },
        onError: (err) => {
            errorMessage.value = Object.values(err).join('\n');
            showError.value = true;
        }
    });
};

// Date Formatter
const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('fr-FR', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    }).format(date);
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
    animation: scaleUp 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.animate-fade-in {
    animation: fadeIn 0.4s ease-out forwards;
    opacity: 0;
}

.scrollbar-thin::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}
.scrollbar-thin::-webkit-scrollbar-track {
    background: transparent;
}
.scrollbar-thin::-webkit-scrollbar-thumb {
    background-color: rgba(156, 163, 175, 0.3);
    border-radius: 8px;
}
.scrollbar-thin::-webkit-scrollbar-thumb:hover {
    background-color: rgba(156, 163, 175, 0.5);
}
</style>
