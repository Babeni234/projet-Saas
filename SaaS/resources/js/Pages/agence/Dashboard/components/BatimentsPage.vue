<template>
    <div class="flex flex-col gap-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 flex items-center gap-2">
                    Gestion des Bâtiments
                    <span class="text-indigo-500 text-sm font-semibold bg-indigo-50 px-2.5 py-1 rounded-full border border-indigo-200">Espace Agence</span>
                </h1>
                <p class="text-slate-650 mt-1">Consultez les immeubles et les logements gérés par votre agence.</p>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Batiments -->
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-indigo-500/20 animate-fade-in" style="animation-delay: 0ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Total Bâtiments</p>
                        <p class="text-3xl font-bold text-slate-900 mt-1 animate-number">{{ totalBatiments }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-indigo-100/80 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                        </svg>
                    </div>
                </div>
            </div>
            <!-- Total Appartements -->
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-violet-500/20 animate-fade-in" style="animation-delay: 100ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Total Appartements</p>
                        <p class="text-3xl font-bold text-slate-900 mt-1 animate-number">{{ totalAppartements }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-violet-100/80 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-violet-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                        </svg>
                    </div>
                </div>
            </div>
            <!-- Batiments Actifs -->
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-emerald-500/20 animate-fade-in" style="animation-delay: 200ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Bâtiments Actifs</p>
                        <p class="text-3xl font-bold text-emerald-600 mt-1 animate-number">{{ batimentsActifs }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-100/80 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <!-- En Maintenance -->
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-amber-500/20 animate-fade-in" style="animation-delay: 300ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">En Maintenance</p>
                        <p class="text-3xl font-bold text-amber-600 mt-1 animate-number">{{ batimentsMaintenance }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-100/80 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Advanced Filtering Bar -->
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-4 border border-slate-100 flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="relative w-full md:flex-1">
                <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Rechercher par nom, ville, pays ou adresse..."
                    class="w-full pl-12 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-sm"
                >
            </div>
            
            <div class="flex items-center gap-3 w-full md:w-auto">
                <select 
                    v-model="statusFilter" 
                    class="px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-sm bg-white text-slate-700 w-full md:w-48"
                >
                    <option value="">Tous les statuts</option>
                    <option value="Actif">Actifs</option>
                    <option value="Maintenance">En Maintenance</option>
                </select>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 overflow-hidden border border-slate-100">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50">
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Nom du Bâtiment</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Adresse & Localisation</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Nombre d'Étages</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Unités / Appartements</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Statut Opérationnel</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="batiment in filteredBatiments" :key="batiment.id" class="hover:bg-slate-50/80 transition-all duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-500 to-violet-600 flex items-center justify-center text-white font-bold text-base shadow-sm">
                                        {{ getInitials(batiment.nom) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-slate-900 block">{{ batiment.nom }}</div>
                                        <div class="text-[10px] text-slate-400">ID: BAT-{{ String(batiment.id).padStart(3, '0') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col text-sm text-slate-600">
                                    <span class="font-medium text-slate-900">{{ batiment.adresse }}</span>
                                    <span class="text-xs text-slate-500">
                                        {{ batiment.quartier ? batiment.quartier + ', ' : '' }}{{ batiment.ville }} ({{ getCountryName(batiment.pays) }})
                                    </span>
                                    <span v-if="batiment.agency_id" class="text-[10px] font-semibold text-emerald-600 mt-1 uppercase tracking-wider">
                                        Géré par: {{ getAgencyName(batiment.agency_id) }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100 flex items-center gap-1.5 w-max">
                                    {{ batiment.etages }} étages
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-violet-50 text-violet-700 border border-violet-100 flex items-center gap-1.5 w-max">
                                    {{ batiment.appartements }} unités
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="[
                                    'px-3 py-1.5 rounded-full text-xs font-semibold border inline-flex items-center gap-1.5 shadow-sm',
                                    batiment.statut === 'Actif' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200'
                                ]">
                                    <span :class="[
                                        'w-1.5 h-1.5 rounded-full',
                                        batiment.statut === 'Actif' ? 'bg-emerald-500' : 'bg-amber-500'
                                    ]"></span>
                                    {{ batiment.statut === 'Actif' ? 'Actif' : 'En Maintenance' }}
                                </span>
                            </td>
                        </tr>
                        <tr v-if="loading">
                            <td colspan="5" class="text-center py-12">
                                <div class="flex flex-col items-center gap-3 text-slate-400">
                                    <svg class="w-8 h-8 animate-spin text-indigo-500" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                    </svg>
                                    <span class="text-sm">Chargement des bâtiments...</span>
                                </div>
                            </td>
                        </tr>
                        <tr v-else-if="filteredBatiments.length === 0">
                            <td colspan="5" class="text-center py-12 text-slate-400">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <p class="text-sm font-medium">Aucun bâtiment trouvé</p>
                                    <p class="text-xs">{{ searchQuery || statusFilter ? 'Modifiez vos critères de recherche.' : 'Aucun bâtiment n\'est rattaché à votre agence.' }}</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add/Edit Modal (Premium 2-Column landscape-oriented layout) -->
        <div v-if="showModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-3xl w-full overflow-hidden animate-scale-up border border-slate-100">
                <!-- Header -->
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-indigo-50 to-violet-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">{{ editingBatiment ? 'Modifier' : 'Ajouter' }} un Bâtiment</h2>
                            <p class="text-xs text-slate-500">Définissez les propriétés de l'immeuble et sa localisation précise.</p>
                        </div>
                    </div>
                    <button @click="closeModal" class="text-slate-400 hover:text-slate-600 transition p-1.5 hover:bg-slate-100 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Form Body -->
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Column 1: Identification & Capacity -->
                    <div class="space-y-4">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-1.5 mb-2">Identification & Capacité</h3>

                        <!-- Nom du bâtiment -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nom du Bâtiment</label>
                            <input 
                                v-model="formData.nom" 
                                type="text" 
                                placeholder="Ex: Immeuble A" 
                                :class="[
                                    'w-full px-4 py-2.5 border rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition bg-white',
                                    formData.nom ? 'border-slate-200' : 'border-rose-300 bg-rose-50/20'
                                ]"
                            >
                        </div>

                        <!-- Propriétaire -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Propriétaire</label>
                            <select 
                                v-model="formData.proprietaire" 
                                :class="[
                                    'w-full px-4 py-2.5 border rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition bg-white text-slate-700',
                                    formData.proprietaire ? 'border-slate-200' : 'border-rose-300 bg-rose-50/20'
                                ]"
                            >
                                <option value="">Sélectionner un propriétaire</option>
                                <option value="Jean Dupont">Jean Dupont</option>
                                <option value="Marie Martin">Marie Martin</option>
                                <option value="Pierre Bernard">Pierre Bernard</option>
                                <option value="Sophie Petit">Sophie Petit</option>
                                <option value="Michel Leroy">Michel Leroy</option>
                            </select>
                        </div>

                        <!-- Agence Affectée -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Agence Affectée</label>
                            <select 
                                v-model="formData.agency_id" 
                                class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition bg-white text-slate-700"
                            >
                                <option value="">Aucune agence (Géré par le siège)</option>
                                <option v-for="agency in agencies" :key="agency.id" :value="agency.id">
                                    {{ agency.name }} ({{ agency.city }})
                                </option>
                            </select>
                        </div>

                        <!-- Dimension grid -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Étages</label>
                                <input v-model.number="formData.etages" type="number" placeholder="Ex: 5" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition bg-white">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Appartements</label>
                                <input v-model.number="formData.appartements" type="number" placeholder="Ex: 25" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition bg-white">
                            </div>
                        </div>

                        <!-- Statut -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Statut opérationnel</label>
                            <select v-model="formData.statut" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition bg-white text-slate-700">
                                <option value="Actif">Actif / Exploité</option>
                                <option value="Maintenance">En Maintenance / Travaux</option>
                            </select>
                        </div>
                    </div>

                    <!-- Column 2: Localisation details -->
                    <div class="space-y-4">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-1.5 mb-2">Localisation géographique</h3>

                        <!-- Pays -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Pays</label>
                            <select 
                                v-model="formData.pays" 
                                @change="onCountryChange" 
                                :class="[
                                    'w-full px-4 py-2.5 border rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition bg-white text-slate-700',
                                    formData.pays ? 'border-slate-200' : 'border-rose-300 bg-rose-50/20'
                                ]"
                            >
                                <option value="">Sélectionner un pays</option>
                                <option v-for="country in countries" :key="country.code" :value="country.code">{{ country.name }}</option>
                            </select>
                        </div>

                        <!-- Ville -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Ville</label>
                            <div class="relative">
                                <select 
                                    v-model="formData.ville" 
                                    @change="onCityChange"
                                    :disabled="!formData.pays || loadingCities"
                                    :class="[
                                        'w-full pl-4 pr-10 py-2.5 border rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition bg-white text-slate-700 disabled:bg-slate-100 disabled:cursor-not-allowed',
                                        formData.ville ? 'border-slate-200' : 'border-rose-300 bg-rose-50/20'
                                    ]"
                                >
                                    <option value="">{{ loadingCities ? 'Chargement...' : formData.pays ? 'Sélectionner une ville' : "Sélectionnez d'abord un pays" }}</option>
                                    <option v-for="city in cities" :key="city.geonameId || city.name" :value="city.name">{{ city.name }}{{ formatCoords(city) }}</option>
                                </select>
                                <button 
                                    type="button"
                                    @click="detectLocation" 
                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition"
                                    title="Détecter ma position"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Quartier -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Quartier</label>
                            <select 
                                v-model="formData.quartier" 
                                :disabled="!formData.ville || loadingNeighborhoods"
                                class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition bg-white text-slate-700 disabled:bg-slate-100 disabled:cursor-not-allowed"
                            >
                                <option value="">{{ loadingNeighborhoods ? 'Chargement...' : formData.ville ? 'Sélectionner un quartier' : "Sélectionnez d'abord une ville" }}</option>
                                <option v-for="quartier in neighborhoods" :key="quartier.geonameId || quartier.name" :value="quartier.name">{{ quartier.name }}{{ formatCoords(quartier) }}</option>
                            </select>
                        </div>

                        <!-- Adresse -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Adresse postale</label>
                            <input 
                                v-model="formData.adresse" 
                                type="text" 
                                placeholder="Ex: 123 Rue de Paris" 
                                :class="[
                                    'w-full px-4 py-2.5 border rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition bg-white',
                                    formData.adresse ? 'border-slate-200' : 'border-rose-300 bg-rose-50/20'
                                ]"
                            >
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex gap-3 justify-end">
                    <button 
                        @click="closeModal" 
                        class="px-5 py-2.5 border border-slate-200 text-slate-700 font-medium rounded-xl hover:bg-slate-100 transition text-sm"
                    >
                        Annuler
                    </button>
                    <button 
                        @click="saveBatiment" 
                        :disabled="!formData.nom || !formData.proprietaire || !formData.pays || !formData.ville || !formData.adresse"
                        class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-medium rounded-xl hover:from-indigo-700 hover:to-violet-700 disabled:opacity-50 disabled:cursor-not-allowed shadow transition text-sm"
                    >
                        Enregistrer
                    </button>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div v-if="showDeleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 animate-scale-up">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100 mx-auto mb-4 animate-bounce">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-center text-slate-900 mb-2">Supprimer ce bâtiment ?</h3>
                <p class="text-center text-slate-500 mb-6 text-sm">Cette action est définitive et supprimera toutes les informations de <strong>{{ deletingBatiment?.nom }}</strong>.</p>

                <div class="flex gap-3">
                    <button @click="closeDeleteModal" class="flex-1 px-4 py-2.5 border border-slate-200 text-slate-700 font-medium rounded-xl hover:bg-slate-50 transition text-sm">Annuler</button>
                    <button @click="confirmDelete" class="flex-1 px-4 py-2.5 bg-red-600 text-white font-medium rounded-xl hover:bg-red-700 transition text-sm">Supprimer</button>
                </div>
            </div>
        </div>

        <!-- Success Modal -->
        <div v-if="showSuccess" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 animate-scale-up">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-emerald-100 mx-auto mb-4">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-center text-slate-900 mb-2">{{ successMessage }}</h3>
                <p class="text-center text-slate-500 text-sm mb-6">Fiche bâtiment enregistrée dans le système.</p>

                <button @click="closeSuccess" class="w-full px-4 py-2.5 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 shadow shadow-emerald-500/20 transition text-sm">Fermer</button>
            </div>
        </div>

        <!-- Error Modal -->
        <div v-if="showError" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 animate-scale-up">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100 mx-auto mb-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-center text-slate-900 mb-2">Erreur</h3>
                <p class="text-center text-slate-500 text-sm mb-6">{{ errorMessage }}</p>

                <button @click="closeError" class="w-full px-4 py-2.5 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 transition text-sm">Fermer</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();

// ── Agencies (pour le select dans le formulaire) ──────────────────────
const agencies = computed(() => {
    const raw = page.props.agencies;
    if (!raw) return [];
    return Array.isArray(raw) ? raw : (raw.data || []);
});

const currentAgencyId = computed(() => page.props.auth?.user?.employee?.agency_id);

const getAgencyName = (agencyId) => {
    if (!agencyId) return 'Siège';
    const agency = agencies.value.find(a => Number(a.id) === Number(agencyId));
    return agency ? agency.name : `Agence #${agencyId}`;
};

// ── Données bâtiments (chargées depuis l'API) ────────────────────────
const batiments = ref([]);
const loading = ref(false);
const searchQuery = ref('');
const statusFilter = ref('');

// Charger les bâtiments depuis le backend
const loadBatiments = async () => {
    loading.value = true;
    try {
        const response = await fetch('/api/batiments', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
        });
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        const data = await response.json();
        batiments.value = Array.isArray(data) ? data : (data.data || []);
    } catch (error) {
        console.error('Erreur chargement bâtiments:', error);
        errorMessage.value = 'Impossible de charger les bâtiments. Veuillez rafraîchir la page.';
        showError.value = true;
        batiments.value = [];
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    loadBatiments();
});

// ── Filtrage local (statut + recherche) ──────────────────────────────
// Nota : le filtre par agence est déjà appliqué côté serveur par BatimentController
const filteredBatiments = computed(() => {
    let list = [...batiments.value];

    if (statusFilter.value) {
        list = list.filter(b => b.statut === statusFilter.value);
    }

    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        list = list.filter(b =>
            (b.nom   && b.nom.toLowerCase().includes(query)) ||
            (b.adresse && b.adresse.toLowerCase().includes(query)) ||
            (b.ville && b.ville.toLowerCase().includes(query)) ||
            (b.pays  && b.pays.toLowerCase().includes(query)) ||
            (b.quartier && b.quartier.toLowerCase().includes(query))
        );
    }

    return list;
});

// ── KPIs (basés sur tous les bâtiments chargés, pas seulement le filtre) ──
const totalBatiments     = computed(() => batiments.value.length);
const totalAppartements  = computed(() => batiments.value.reduce((sum, b) => sum + (b.appartements || 0), 0));
const batimentsActifs    = computed(() => batiments.value.filter(b => b.statut === 'Actif').length);
const batimentsMaintenance = computed(() => batiments.value.filter(b => b.statut === 'Maintenance').length);

// ── Modals ────────────────────────────────────────────────────────────
const showModal      = ref(false);
const showDeleteModal = ref(false);
const showSuccess    = ref(false);
const showError      = ref(false);

let errorTimeout = null;
watch(showError, (newVal) => {
    if (newVal) {
        if (errorTimeout) clearTimeout(errorTimeout);
        errorTimeout = setTimeout(() => { showError.value = false; }, 5000);
    } else {
        if (errorTimeout) { clearTimeout(errorTimeout); errorTimeout = null; }
    }
});

const editingBatiment = ref(null);
const deletingBatiment = ref(null);
const successMessage = ref('');
const errorMessage   = ref('');
const savingForm     = ref(false);

// ── Formulaire ────────────────────────────────────────────────────────
const formData = ref({
    nom: '',
    proprietaire_id: '',
    pays: '',
    ville: '',
    quartier: '',
    adresse: '',
    etages: '',
    appartements: '',
    statut: 'Actif',
    agency_id: '',
    surface_totale: '',
    type_batiment: '',
    ascenseur: false,
    gardiennage: false,
    generatrice: false,
});

// ── Countries list ────────────────────────────────────────────────────
const countries = ref([
    { code: 'CM', name: 'Cameroun' },
    { code: 'FR', name: 'France' },
    { code: 'US', name: 'États-Unis' },
    { code: 'GB', name: 'Royaume-Uni' },
    { code: 'DE', name: 'Allemagne' },
    { code: 'ES', name: 'Espagne' },
    { code: 'IT', name: 'Italie' },
    { code: 'CA', name: 'Canada' },
    { code: 'AU', name: 'Australie' },
    { code: 'JP', name: 'Japon' },
    { code: 'BR', name: 'Brésil' },
    { code: 'MA', name: 'Maroc' },
    { code: 'DZ', name: 'Algérie' },
    { code: 'TN', name: 'Tunisie' },
    { code: 'CI', name: "Côte d'Ivoire" },
    { code: 'SN', name: 'Sénégal' },
    { code: 'NG', name: 'Nigeria' },
    { code: 'GH', name: 'Ghana' },
    { code: 'ZA', name: 'Afrique du Sud' },
    { code: 'EG', name: 'Égypte' },
    { code: 'CD', name: 'RD Congo' },
    { code: 'KE', name: 'Kenya' },
    { code: 'BE', name: 'Belgique' },
    { code: 'CH', name: 'Suisse' },
    { code: 'AE', name: 'Émirats Arabes Unis' },
    { code: 'NL', name: 'Pays-Bas' },
    { code: 'PT', name: 'Portugal' },
]);

const cities = ref([]);
const neighborhoods = ref([]);
const loadingCities = ref(false);
const loadingNeighborhoods = ref(false);

const fallbackCities = {
    CM: [
        { name: 'Douala' }, { name: 'Yaoundé' }, { name: 'Bafoussam' },
        { name: 'Bamenda' }, { name: 'Garoua' }, { name: 'Maroua' },
        { name: 'Buea' }, { name: 'Limbe' }, { name: 'Kribi' },
        { name: 'Ebolowa' }, { name: 'Bertoua' }, { name: 'Ngaoundéré' },
    ],
    FR: [
        { name: 'Paris' }, { name: 'Lyon' }, { name: 'Marseille' },
        { name: 'Toulouse' }, { name: 'Nice' }, { name: 'Nantes' },
        { name: 'Strasbourg' }, { name: 'Bordeaux' }, { name: 'Lille' },
    ],
    CI: [
        { name: 'Abidjan' }, { name: 'Yamoussoukro' }, { name: 'Bouaké' },
        { name: 'San-Pédro' }, { name: 'Korhogo' }, { name: 'Daloa' },
    ],
    SN: [
        { name: 'Dakar' }, { name: 'Thiès' }, { name: 'Saint-Louis' },
        { name: 'Ziguinchor' }, { name: 'Kaolack' }, { name: 'Mbour' },
    ],
    MA: [
        { name: 'Casablanca' }, { name: 'Rabat' }, { name: 'Marrakech' },
        { name: 'Fès' }, { name: 'Tanger' }, { name: 'Agadir' },
    ],
};

const fallbackNeighborhoods = {
    'Douala': ['Akwa', 'Bonapriso', 'Bonanjo', 'Deido', 'Bepanda', 'New Bell', 'Nkololoun'].map((n, i) => ({ name: n, geonameId: i })),
    'Yaoundé': ['Bastos', 'Mvog-Ada', 'Ekoumdoum', 'Mvan', 'Nlongkak', 'Omnisports', 'Essos'].map((n, i) => ({ name: n, geonameId: i })),
    'Bafoussam': ['Centre-Ville', 'Tamdja', 'Djeleng', 'Kamkop'].map((n, i) => ({ name: n, geonameId: i })),
    'Abidjan': ['Cocody', 'Plateau', 'Marcory', 'Yopougon', 'Treichville', 'Adjamé', 'Abobo'].map((n, i) => ({ name: n, geonameId: i })),
    'Dakar': ['Plateau', 'Médina', 'Yoff', 'Almadies', 'Ngor', 'Ouakam'].map((n, i) => ({ name: n, geonameId: i })),
    'Paris': ['Le Marais', 'Montmartre', 'Saint-Germain', 'Bastille', 'Belleville', 'Champs-Élysées'].map((n, i) => ({ name: n, geonameId: i })),
    'Lyon': ['Vieux Lyon', 'La Croix-Rousse', "Presqu'île", 'Part-Dieu', 'Confluence'].map((n, i) => ({ name: n, geonameId: i })),
    'Casablanca': ['Maârif', 'Ain Diab', 'Anfa', 'Bourgogne', 'Roches Noires'].map((n, i) => ({ name: n, geonameId: i })),
};

const formatCoords = (item) => '';

const onCountryChange = async (options = {}) => {
    const preserveSelection = options === true || (options && typeof options === 'object' && options.preserveSelection === true);
    if (!formData.value.pays) {
        cities.value = []; neighborhoods.value = [];
        if (!preserveSelection) { formData.value.ville = ''; formData.value.quartier = ''; }
        return;
    }
    loadingCities.value = true;
    if (!preserveSelection) { formData.value.ville = ''; formData.value.quartier = ''; neighborhoods.value = []; }
    try {
        const res = await fetch(`https://api.geonames.org/searchJSON?country=${formData.value.pays}&featureClass=P&maxRows=100&username=demo`);
        const data = await res.json();
        if (data.status?.value === 40) throw new Error('Rate limit');
        if (data.geonames?.length > 0) {
            cities.value = data.geonames.map(c => ({ name: c.name, lat: parseFloat(c.lat), lng: parseFloat(c.lng) })).sort((a, b) => a.name.localeCompare(b.name));
        } else throw new Error('No cities');
    } catch {
        cities.value = (fallbackCities[formData.value.pays] || []);
    } finally {
        loadingCities.value = false;
    }
};

const onCityChange = async (options = {}) => {
    const preserveSelection = options === true || (options && typeof options === 'object' && options.preserveSelection === true);
    if (!formData.value.ville) {
        neighborhoods.value = [];
        if (!preserveSelection) formData.value.quartier = '';
        return;
    }
    loadingNeighborhoods.value = true;
    if (!preserveSelection) formData.value.quartier = '';
    try {
        const selectedCity = cities.value.find(c => c.name === formData.value.ville);
        if (!selectedCity?.lat) throw new Error('No coords');
        const res = await fetch(`https://api.geonames.org/searchJSON?lat=${selectedCity.lat}&lng=${selectedCity.lng}&radius=10&featureCode=PPL&maxRows=50&username=demo`);
        const data = await res.json();
        if (data.status?.value === 40) throw new Error('Rate limit');
        if (data.geonames?.length > 0) {
            neighborhoods.value = data.geonames.filter(p => p.name !== selectedCity.name).map(p => ({ name: p.name, geonameId: p.geonameId }));
        } else throw new Error('No neighborhoods');
    } catch {
        neighborhoods.value = fallbackNeighborhoods[formData.value.ville] || [];
    } finally {
        loadingNeighborhoods.value = false;
    }
};

const detectLocation = async () => {
    if (!navigator.geolocation) { errorMessage.value = 'Géolocalisation non supportée'; showError.value = true; return; }
    try {
        const pos = await new Promise((resolve, reject) => navigator.geolocation.getCurrentPosition(resolve, reject, { timeout: 10000 }));
        const res = await fetch(`https://api.geonames.org/findNearbyPlaceNameJSON?lat=${pos.coords.latitude}&lng=${pos.coords.longitude}&username=demo`);
        const data = await res.json();
        if (data.geonames?.[0]) {
            formData.value.pays = data.geonames[0].countryCode || '';
            await onCountryChange();
            formData.value.ville = data.geonames[0].name || '';
            await onCityChange();
        }
    } catch {
        errorMessage.value = 'Impossible de détecter votre position.'; showError.value = true;
    }
};

// ── Actions CRUD ──────────────────────────────────────────────────────
const openAddModal = () => {
    editingBatiment.value = null;
    formData.value = {
        nom: '', proprietaire_id: '', pays: '', ville: '', quartier: '',
        adresse: '', etages: '', appartements: '', statut: 'Actif',
        agency_id: currentAgencyId.value || '',
        surface_totale: '', type_batiment: '', ascenseur: false, gardiennage: false, generatrice: false,
    };
    cities.value = []; neighborhoods.value = [];
    showModal.value = true;
};

const openEditModal = async (batiment) => {
    editingBatiment.value = batiment;
    formData.value = {
        nom: batiment.nom || '',
        proprietaire_id: batiment.proprietaire_id || '',
        pays: batiment.pays || '',
        ville: batiment.ville || '',
        quartier: batiment.quartier || '',
        adresse: batiment.adresse || '',
        etages: batiment.etages ?? '',
        appartements: batiment.appartements ?? '',
        statut: batiment.statut || 'Actif',
        agency_id: batiment.agency_id || '',
        surface_totale: batiment.surface_totale || '',
        type_batiment: batiment.type_batiment || '',
        ascenseur: batiment.ascenseur || false,
        gardiennage: batiment.gardiennage || false,
        generatrice: batiment.generatrice || false,
    };
    showModal.value = true;
    if (formData.value.pays) {
        await onCountryChange({ preserveSelection: true });
        if (formData.value.ville) await onCityChange({ preserveSelection: true });
    }
};

const closeModal = () => { showModal.value = false; editingBatiment.value = null; };

const openDeleteModal = (batiment) => { deletingBatiment.value = batiment; showDeleteModal.value = true; };
const closeDeleteModal = () => { showDeleteModal.value = false; deletingBatiment.value = null; };
const closeSuccess = () => { showSuccess.value = false; };
const closeError   = () => { showError.value = false; };

// ── Sauvegarder via API ───────────────────────────────────────────────
const getCsrfToken = () => {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : '';
};

const saveBatiment = async () => {
    if (!formData.value.nom || !formData.value.adresse || !formData.value.pays || !formData.value.ville) {
        errorMessage.value = 'Le nom, le pays, la ville et l\'adresse sont requis.';
        showError.value = true; return;
    }

    savingForm.value = true;
    const payload = {
        nom:           formData.value.nom,
        proprietaire_id: formData.value.proprietaire_id || null,
        pays:          formData.value.pays,
        ville:         formData.value.ville,
        quartier:      formData.value.quartier || null,
        adresse:       formData.value.adresse,
        etages:        Number(formData.value.etages || 0),
        appartements:  Number(formData.value.appartements || 0),
        statut:        formData.value.statut || 'Actif',
        agency_id:     formData.value.agency_id || null,
        surface_totale: formData.value.surface_totale || null,
        type_batiment: formData.value.type_batiment || null,
        ascenseur:     formData.value.ascenseur || false,
        gardiennage:   formData.value.gardiennage || false,
        generatrice:   formData.value.generatrice || false,
    };

    try {
        const isEdit = !!editingBatiment.value;
        const url    = isEdit ? `/api/batiments/${editingBatiment.value.id}` : '/api/batiments';
        const method = isEdit ? 'PUT' : 'POST';

        const res = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
            body: JSON.stringify(payload),
        });

        if (!res.ok) {
            const err = await res.json().catch(() => ({}));
            errorMessage.value = err.message || `Erreur ${res.status} lors de l'enregistrement.`;
            showError.value = true; return;
        }

        successMessage.value = isEdit ? 'Bâtiment modifié avec succès' : 'Bâtiment ajouté avec succès';
        closeModal();
        showSuccess.value = true;
        await loadBatiments(); // recharger la liste fraîche
    } catch (error) {
        console.error('Erreur save bâtiment:', error);
        errorMessage.value = 'Erreur réseau. Veuillez réessayer.';
        showError.value = true;
    } finally {
        savingForm.value = false;
    }
};

const confirmDelete = async () => {
    if (!deletingBatiment.value) return;
    try {
        const res = await fetch(`/api/batiments/${deletingBatiment.value.id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
        });
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        successMessage.value = 'Bâtiment supprimé avec succès';
        closeDeleteModal();
        showSuccess.value = true;
        await loadBatiments();
    } catch (error) {
        console.error('Erreur delete bâtiment:', error);
        errorMessage.value = 'Impossible de supprimer ce bâtiment.';
        showError.value = true;
    }
};

// ── Helpers ───────────────────────────────────────────────────────────
const getInitials = (name) => {
    if (!name) return 'B';
    const parts = name.split(' ');
    return parts.length > 1
        ? (parts[0].charAt(0) + parts[1].charAt(0)).toUpperCase()
        : name.substring(0, 2).toUpperCase();
};

const getCountryName = (code) => {
    const country = countries.value.find(c => c.code === code);
    return country ? country.name : (code || '—');
};
</script>

<style scoped>
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.animate-fade-in {
    animation: fadeIn 0.5s ease-out forwards;
    opacity: 0;
}

.animate-slide-in {
    animation: slideIn 0.4s ease-out forwards;
    opacity: 0;
}

.animate-number {
    animation: countUp 0.5s ease-out;
}

@keyframes countUp {
    from {
        opacity: 0;
        transform: scale(0.5);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
</style>
