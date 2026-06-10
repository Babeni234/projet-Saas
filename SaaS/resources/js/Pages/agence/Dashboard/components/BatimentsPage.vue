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
                        <tr v-if="filteredBatiments.length === 0">
                            <td colspan="5" class="text-center py-12 text-slate-400">
                                Aucun bâtiment trouvé correspondant à vos critères de recherche.
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
import { ref, computed, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const agencies = computed(() => page.props.agencies || []);
const currentAgencyId = computed(() => page.props.auth?.user?.employee?.agency_id);

const getAgencyName = (agencyId) => {
    const agency = agencies.value.find(a => Number(a.id) === Number(agencyId));
    return agency ? agency.name : 'Siège';
};

const defaultBatiments = [
    { id: 1, nom: 'Immeuble A', adresse: '123 Rue de Paris', etages: 5, appartements: 25, statut: 'Actif', pays: 'FR', ville: 'Paris', quartier: 'Le Marais', proprietaire: 'Jean Dupont' },
    { id: 2, nom: 'Immeuble B', adresse: '456 Avenue Lyon', etages: 7, appartements: 35, statut: 'Actif', pays: 'FR', ville: 'Lyon', quartier: 'Vieux Lyon', proprietaire: 'Marie Martin' },
    { id: 3, nom: 'Immeuble C', adresse: '789 Boulevard Nice', etages: 3, appartements: 15, statut: 'Maintenance', pays: 'FR', ville: 'Nice', quartier: 'Vieux Nice', proprietaire: 'Pierre Bernard' },
    { id: 4, nom: 'Immeuble D', adresse: '321 Rue Marseille', etages: 8, appartements: 40, statut: 'Actif', pays: 'CM', ville: 'Douala', quartier: 'Akwa', proprietaire: 'Sophie Petit' },
    { id: 5, nom: 'Immeuble E', adresse: '654 Avenue Toulouse', etages: 4, appartements: 20, statut: 'Maintenance', pays: 'CM', ville: 'Yaoundé', quartier: 'Bastos', proprietaire: 'Michel Leroy' },
];

const batiments = ref(JSON.parse(localStorage.getItem('immobilier_batiments')) || defaultBatiments);
if (!localStorage.getItem('immobilier_batiments')) {
    localStorage.setItem('immobilier_batiments', JSON.stringify(defaultBatiments));
}

const saveBatimentsToStorage = () => {
    localStorage.setItem('immobilier_batiments', JSON.stringify(batiments.value));
};

const searchQuery = ref('');
const statusFilter = ref('');

const filteredBatiments = computed(() => {
    // Agency scope
    const agencyId = currentAgencyId.value;
    let list = batiments.value.filter(b => Number(b.agency_id) === Number(agencyId));
    
    // Status Filter
    if (statusFilter.value) {
        list = list.filter(b => b.statut === statusFilter.value);
    }
    
    // Search Query
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        list = list.filter(b => 
            b.nom.toLowerCase().includes(query) ||
            b.adresse.toLowerCase().includes(query) ||
            (b.ville && b.ville.toLowerCase().includes(query)) ||
            (b.pays && b.pays.toLowerCase().includes(query)) ||
            (b.quartier && b.quartier.toLowerCase().includes(query))
        );
    }
    
    return list;
});

const totalBatiments = computed(() => filteredBatiments.value.length);
const totalAppartements = computed(() => filteredBatiments.value.reduce((sum, b) => sum + b.appartements, 0));
const batimentsActifs = computed(() => filteredBatiments.value.filter(b => b.statut === 'Actif').length);
const batimentsMaintenance = computed(() => filteredBatiments.value.filter(b => b.statut === 'Maintenance').length);

const showModal = ref(false);
const showDeleteModal = ref(false);
const showSuccess = ref(false);
const showError = ref(false);

// Auto-hide error modal after 5 seconds
let errorTimeout = null;
watch(showError, (newVal) => {
    if (newVal) {
        if (errorTimeout) clearTimeout(errorTimeout);
        errorTimeout = setTimeout(() => {
            showError.value = false;
        }, 5000);
    } else {
        if (errorTimeout) {
            clearTimeout(errorTimeout);
            errorTimeout = null;
        }
    }
});
const editingBatiment = ref(null);
const deletingBatiment = ref(null);
const successMessage = ref('');
const errorMessage = ref('');

const formData = ref({
    nom: '',
    proprietaire: '',
    pays: '',
    ville: '',
    quartier: '',
    adresse: '',
    etages: '',
    appartements: '',
    statut: 'Actif',
    agency_id: '',
});

// Countries list
const countries = ref([
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
    { code: 'IN', name: 'Inde' },
    { code: 'CN', name: 'Chine' },
    { code: 'RU', name: 'Russie' },
    { code: 'MX', name: 'Mexique' },
    { code: 'KR', name: 'Corée du Sud' },
    { code: 'NL', name: 'Pays-Bas' },
    { code: 'CH', name: 'Suisse' },
    { code: 'BE', name: 'Belgique' },
    { code: 'AT', name: 'Autriche' },
    { code: 'SE', name: 'Suède' },
    { code: 'NO', name: 'Norvège' },
    { code: 'DK', name: 'Danemark' },
    { code: 'FI', name: 'Finlande' },
    { code: 'PL', name: 'Pologne' },
    { code: 'PT', name: 'Portugal' },
    { code: 'GR', name: 'Grèce' },
    { code: 'TR', name: 'Turquie' },
    { code: 'ZA', name: 'Afrique du Sud' },
    { code: 'EG', name: 'Égypte' },
    { code: 'NG', name: 'Nigeria' },
    { code: 'KE', name: 'Kenya' },
    { code: 'MA', name: 'Maroc' },
    { code: 'DZ', name: 'Algérie' },
    { code: 'TN', name: 'Tunisie' },
    { code: 'CM', name: 'Cameroun' },
    { code: 'CI', name: 'Côte d\'Ivoire' },
    { code: 'SN', name: 'Sénégal' },
    { code: 'MG', name: 'Madagascar' },
    { code: 'CD', name: 'République Démocratique du Congo' },
    { code: 'ET', name: 'Éthiopie' },
    { code: 'GH', name: 'Ghana' },
    { code: 'TZ', name: 'Tanzanie' },
    { code: 'UG', name: 'Ouganda' },
    { code: 'ZW', name: 'Zimbabwe' },
    { code: 'ZM', name: 'Zambie' },
    { code: 'AO', name: 'Angola' },
    { code: 'MU', name: 'Maurice' },
    { code: 'AR', name: 'Argentine' },
    { code: 'CL', name: 'Chili' },
    { code: 'CO', name: 'Colombie' },
    { code: 'PE', name: 'Pérou' },
    { code: 'VE', name: 'Venezuela' },
    { code: 'EC', name: 'Équateur' },
    { code: 'BO', name: 'Bolivie' },
    { code: 'PY', name: 'Paraguay' },
    { code: 'UY', name: 'Uruguay' },
    { code: 'TH', name: 'Thaïlande' },
    { code: 'VN', name: 'Vietnam' },
    { code: 'MY', name: 'Malaisie' },
    { code: 'SG', name: 'Singapour' },
    { code: 'ID', name: 'Indonésie' },
    { code: 'PH', name: 'Philippines' },
    { code: 'HK', name: 'Hong Kong' },
    { code: 'TW', name: 'Taïwan' },
    { code: 'NZ', name: 'Nouvelle-Zélande' },
    { code: 'IL', name: 'Israël' },
    { code: 'AE', name: 'Émirats Arabes Unis' },
    { code: 'SA', name: 'Arabie Saoudite' },
    { code: 'QA', name: 'Qatar' },
    { code: 'KW', name: 'Koweït' },
    { code: 'BH', name: 'Bahreïn' },
    { code: 'OM', name: 'Oman' },
    { code: 'JO', name: 'Jordanie' },
    { code: 'LB', name: 'Liban' },
    { code: 'PK', name: 'Pakistan' },
    { code: 'BD', name: 'Bangladesh' },
    { code: 'LK', name: 'Sri Lanka' },
    { code: 'NP', name: 'Népal' },
    { code: 'MM', name: 'Myanmar' },
    { code: 'KH', name: 'Cambodge' },
    { code: 'LA', name: 'Laos' },
    { code: 'UA', name: 'Ukraine' },
    { code: 'CZ', name: 'République Tchèque' },
    { code: 'HU', name: 'Hongrie' },
    { code: 'RO', name: 'Roumanie' },
    { code: 'BG', name: 'Bulgarie' },
    { code: 'HR', name: 'Croatie' },
    { code: 'SI', name: 'Slovénie' },
    { code: 'SK', name: 'Slovaquie' },
    { code: 'EE', name: 'Estonie' },
    { code: 'LV', name: 'Lettonie' },
    { code: 'LT', name: 'Lituanie' },
    { code: 'IE', name: 'Irlande' },
    { code: 'IS', name: 'Islande' },
    { code: 'LU', name: 'Luxembourg' },
    { code: 'MC', name: 'Monaco' },
    { code: 'AD', name: 'Andorre' },
    { code: 'SM', name: 'Saint-Marin' },
    { code: 'VA', name: 'Vatican' },
    { code: 'MT', name: 'Malte' },
    { code: 'CY', name: 'Chypre' },
    { code: 'AL', name: 'Albanie' },
    { code: 'MK', name: 'Macédoine du Nord' },
    { code: 'RS', name: 'Serbie' },
    { code: 'BA', name: 'Bosnie-Herzégovine' },
    { code: 'ME', name: 'Monténégro' },
    { code: 'XK', name: 'Kosovo' },
]);

const cities = ref([]);
const neighborhoods = ref([]);
const loadingCities = ref(false);
const loadingNeighborhoods = ref(false);

// Fallback city data for common countries
const fallbackCities = {
    FR: [
        { geonameId: 2988507, name: 'Paris', lat: 48.8534, lng: 2.3488 },
        { geonameId: 2988507, name: 'Lyon', lat: 45.7640, lng: 4.8357 },
        { geonameId: 2988507, name: 'Marseille', lat: 43.2965, lng: 5.3698 },
        { geonameId: 2988507, name: 'Toulouse', lat: 43.6047, lng: 1.4442 },
        { geonameId: 2988507, name: 'Nice', lat: 43.7102, lng: 7.2620 },
        { geonameId: 2988507, name: 'Nantes', lat: 47.2184, lng: -1.5536 },
        { geonameId: 2988507, name: 'Strasbourg', lat: 48.5734, lng: 7.7521 },
        { geonameId: 2988507, name: 'Montpellier', lat: 43.6108, lng: 3.8767 },
        { geonameId: 2988507, name: 'Bordeaux', lat: 44.8378, lng: -0.5792 },
        { geonameId: 2988507, name: 'Lille', lat: 50.6292, lng: 3.0573 },
    ],
    US: [
        { geonameId: 5128581, name: 'New York', lat: 40.7128, lng: -74.0060 },
        { geonameId: 5128581, name: 'Los Angeles', lat: 34.0522, lng: -118.2437 },
        { geonameId: 5128581, name: 'Chicago', lat: 41.8781, lng: -87.6298 },
        { geonameId: 5128581, name: 'Houston', lat: 29.7604, lng: -95.3698 },
        { geonameId: 5128581, name: 'Phoenix', lat: 33.4484, lng: -112.0740 },
        { geonameId: 5128581, name: 'Philadelphia', lat: 39.9526, lng: -75.1652 },
        { geonameId: 5128581, name: 'San Antonio', lat: 29.4241, lng: -98.4936 },
        { geonameId: 5128581, name: 'San Diego', lat: 32.7157, lng: -117.1611 },
        { geonameId: 5128581, name: 'Dallas', lat: 32.7767, lng: -96.7970 },
        { geonameId: 5128581, name: 'San Jose', lat: 37.3382, lng: -121.8863 },
    ],
    GB: [
        { geonameId: 2643743, name: 'London', lat: 51.5074, lng: -0.1278 },
        { geonameId: 2643743, name: 'Manchester', lat: 53.4808, lng: -2.2426 },
        { geonameId: 2643743, name: 'Birmingham', lat: 52.4862, lng: -1.8904 },
        { geonameId: 2643743, name: 'Liverpool', lat: 53.4084, lng: -2.9916 },
        { geonameId: 2643743, name: 'Leeds', lat: 53.8008, lng: -1.5491 },
        { geonameId: 2643743, name: 'Glasgow', lat: 55.8642, lng: -4.2518 },
        { geonameId: 2643743, name: 'Edinburgh', lat: 55.9533, lng: -3.1883 },
        { geonameId: 2643743, name: 'Bristol', lat: 51.4545, lng: -2.5879 },
        { geonameId: 2643743, name: 'Sheffield', lat: 53.3811, lng: -1.4701 },
        { geonameId: 2643743, name: 'Newcastle', lat: 54.9783, lng: -1.6178 },
    ],
    DE: [
        { geonameId: 2950159, name: 'Berlin', lat: 52.5200, lng: 13.4050 },
        { geonameId: 2950159, name: 'Munich', lat: 48.1351, lng: 11.5820 },
        { geonameId: 2950159, name: 'Hamburg', lat: 53.5511, lng: 9.9937 },
        { geonameId: 2950159, name: 'Cologne', lat: 50.9375, lng: 6.9603 },
        { geonameId: 2950159, name: 'Frankfurt', lat: 50.1109, lng: 8.6821 },
        { geonameId: 2950159, name: 'Stuttgart', lat: 48.7758, lng: 9.1829 },
        { geonameId: 2950159, name: 'Düsseldorf', lat: 51.2277, lng: 6.7735 },
        { geonameId: 2950159, name: 'Dortmund', lat: 51.5136, lng: 7.4653 },
        { geonameId: 2950159, name: 'Essen', lat: 51.4556, lng: 7.0116 },
        { geonameId: 2950159, name: 'Bremen', lat: 53.0793, lng: 8.8017 },
    ],
    ES: [
        { geonameId: 3128760, name: 'Madrid', lat: 40.4168, lng: -3.7038 },
        { geonameId: 3128760, name: 'Barcelona', lat: 41.3851, lng: 2.1734 },
        { geonameId: 3128760, name: 'Valencia', lat: 39.4699, lng: -0.3763 },
        { geonameId: 3128760, name: 'Seville', lat: 37.3891, lng: -5.9845 },
        { geonameId: 3128760, name: 'Zaragoza', lat: 41.6561, lng: -0.8773 },
        { geonameId: 3128760, name: 'Malaga', lat: 36.7213, lng: -4.4214 },
        { geonameId: 3128760, name: 'Murcia', lat: 37.9922, lng: -1.1307 },
        { geonameId: 3128760, name: 'Palma', lat: 39.5695, lng: 2.6502 },
        { geonameId: 3128760, name: 'Las Palmas', lat: 28.1235, lng: -15.4364 },
        { geonameId: 3128760, name: 'Bilbao', lat: 43.2630, lng: -2.9350 },
    ],
    IT: [
        { geonameId: 3169070, name: 'Rome', lat: 41.9028, lng: 12.4964 },
        { geonameId: 3169070, name: 'Milan', lat: 45.4642, lng: 9.1900 },
        { geonameId: 3169070, name: 'Naples', lat: 40.8518, lng: 14.2681 },
        { geonameId: 3169070, name: 'Turin', lat: 45.0703, lng: 7.6869 },
        { geonameId: 3169070, name: 'Palermo', lat: 38.1157, lng: 13.3615 },
        { geonameId: 3169070, name: 'Genoa', lat: 44.4056, lng: 8.9463 },
        { geonameId: 3169070, name: 'Bologna', lat: 44.4949, lng: 11.3428 },
        { geonameId: 3169070, name: 'Florence', lat: 43.7696, lng: 11.2558 },
        { geonameId: 3169070, name: 'Venice', lat: 45.4408, lng: 12.3155 },
        { geonameId: 3169070, name: 'Verona', lat: 45.4384, lng: 10.9916 },
    ],
    CA: [
        { geonameId: 6167865, name: 'Toronto', lat: 43.6532, lng: -79.3832 },
        { geonameId: 6167865, name: 'Montreal', lat: 45.5017, lng: -73.5673 },
        { geonameId: 6167865, name: 'Vancouver', lat: 49.2827, lng: -123.1207 },
        { geonameId: 6167865, name: 'Calgary', lat: 51.0447, lng: -114.0719 },
        { geonameId: 6167865, name: 'Edmonton', lat: 53.5461, lng: -113.4938 },
        { geonameId: 6167865, name: 'Ottawa', lat: 45.4215, lng: -75.6972 },
        { geonameId: 6167865, name: 'Winnipeg', lat: 49.8951, lng: -97.1384 },
        { geonameId: 6167865, name: 'Quebec City', lat: 46.8139, lng: -71.2080 },
        { geonameId: 6167865, name: 'Hamilton', lat: 43.2557, lng: -79.8711 },
        { geonameId: 6167865, name: 'Kitchener', lat: 43.4516, lng: -80.4925 },
    ],
    MA: [
        { geonameId: 2542004, name: 'Casablanca', lat: 33.5731, lng: -7.5898 },
        { geonameId: 2542004, name: 'Rabat', lat: 34.0209, lng: -6.8416 },
        { geonameId: 2542004, name: 'Marrakech', lat: 31.6295, lng: -7.9811 },
        { geonameId: 2542004, name: 'Fes', lat: 34.0331, lng: -5.0003 },
        { geonameId: 2542004, name: 'Tangier', lat: 35.7595, lng: -5.8340 },
        { geonameId: 2542004, name: 'Agadir', lat: 30.4278, lng: -9.5981 },
        { geonameId: 2542004, name: 'Meknes', lat: 33.8935, lng: -5.5473 },
        { geonameId: 2542004, name: 'Oujda', lat: 34.6867, lng: -1.9114 },
        { geonameId: 2542004, name: 'Kenitra', lat: 34.2610, lng: -6.5802 },
        { geonameId: 2542004, name: 'Tetouan', lat: 35.5889, lng: -5.3626 },
    ],
    DZ: [
        { geonameId: 2507484, name: 'Algiers', lat: 36.7538, lng: 3.0588 },
        { geonameId: 2507484, name: 'Oran', lat: 35.6911, lng: -0.6417 },
        { geonameId: 2507484, name: 'Constantine', lat: 36.3650, lng: 6.6147 },
        { geonameId: 2507484, name: 'Annaba', lat: 36.8993, lng: 7.7754 },
        { geonameId: 2507484, name: 'Blida', lat: 36.7764, lng: 3.0417 },
        { geonameId: 2507484, name: 'Batna', lat: 35.5558, lng: 6.1743 },
        { geonameId: 2507484, name: 'Sétif', lat: 36.1911, lng: 5.4104 },
        { geonameId: 2507484, name: 'Sidi Bel Abbès', lat: 35.1911, lng: -0.6356 },
        { geonameId: 2507484, name: 'Skikda', lat: 36.8788, lng: 6.9092 },
        { geonameId: 2507484, name: 'Tlemcen', lat: 34.8854, lng: -1.3153 },
    ],
    TN: [
        { geonameId: 2464470, name: 'Tunis', lat: 36.8065, lng: 10.1815 },
        { geonameId: 2464470, name: 'Sfax', lat: 34.7406, lng: 10.7603 },
        { geonameId: 2464470, name: 'Sousse', lat: 35.8256, lng: 10.6084 },
        { geonameId: 2464470, name: 'Ettadhamen', lat: 36.8127, lng: 10.0622 },
        { geonameId: 2464470, name: 'Kairouan', lat: 35.6781, lng: 10.0963 },
        { geonameId: 2464470, name: 'Bizerte', lat: 37.2744, lng: 9.8739 },
        { geonameId: 2464470, name: 'Gabès', lat: 33.8815, lng: 10.0982 },
        { geonameId: 2464470, name: 'Ariana', lat: 36.8665, lng: 10.1647 },
        { geonameId: 2464470, name: 'Gafsa', lat: 34.4250, lng: 8.7842 },
        { geonameId: 2464470, name: 'Monastir', lat: 35.7789, lng: 10.8264 },
    ],
    CM: [
        { geonameId: 2233387, name: 'Douala', lat: 4.0493, lng: 9.7043 },
        { geonameId: 2233387, name: 'Yaoundé', lat: 3.8667, lng: 11.5167 },
        { geonameId: 2233387, name: 'Bafoussam', lat: 5.4667, lng: 10.4167 },
        { geonameId: 2233387, name: 'Bamenda', lat: 5.9667, lng: 10.1500 },
        { geonameId: 2233387, name: 'Garoua', lat: 9.3000, lng: 13.3833 },
        { geonameId: 2233387, name: 'Maroua', lat: 10.5333, lng: 14.3167 },
        { geonameId: 2233387, name: 'Buea', lat: 4.1500, lng: 9.2333 },
        { geonameId: 2233387, name: 'Kumba', lat: 4.6333, lng: 9.4333 },
        { geonameId: 2233387, name: 'Nkongsamba', lat: 4.9500, lng: 9.9333 },
        { geonameId: 2233387, name: 'Edéa', lat: 3.8000, lng: 10.1333 },
        { geonameId: 2233387, name: 'Limbe', lat: 4.0167, lng: 9.2000 },
        { geonameId: 2233387, name: 'Bertoua', lat: 4.5667, lng: 13.6833 },
        { geonameId: 2233387, name: 'Ebolowa', lat: 2.9167, lng: 11.1500 },
        { geonameId: 2233387, name: 'Ngaoundéré', lat: 7.3167, lng: 13.5667 },
        { geonameId: 2233387, name: 'Kribi', lat: 2.9333, lng: 9.9167 },
        { geonameId: 2233387, name: 'Foumban', lat: 5.4333, lng: 10.7000 },
        { geonameId: 2233387, name: 'Dschang', lat: 5.4500, lng: 10.0500 },
        { geonameId: 2233387, name: 'Mbouda', lat: 5.6167, lng: 10.2500 },
        { geonameId: 2233387, name: 'Tiko', lat: 4.0667, lng: 9.3667 },
    ],
    CI: [
        { geonameId: 2281824, name: 'Abidjan', lat: 5.3096, lng: -4.0127 },
        { geonameId: 2279555, name: 'Yamoussoukro', lat: 6.8276, lng: -5.2755 },
        { geonameId: 2289656, name: 'Bouaké', lat: 7.6939, lng: -5.0303 },
        { geonameId: 2282006, name: 'San-Pédro', lat: 4.7485, lng: -6.6363 },
        { geonameId: 2286302, name: 'Korhogo', lat: 9.4580, lng: -5.6296 },
        { geonameId: 2290449, name: 'Daloa', lat: 6.8774, lng: -6.4502 },
        { geonameId: 2284988, name: 'Man', lat: 7.4125, lng: -7.5538 },
    ],
    SN: [
        { geonameId: 2253354, name: 'Dakar', lat: 14.7167, lng: -17.4677 },
        { geonameId: 2244991, name: 'Thiès', lat: 14.7912, lng: -16.9298 },
        { geonameId: 2246284, name: 'Saint-Louis', lat: 16.0244, lng: -16.5047 },
        { geonameId: 2243223, name: 'Ziguinchor', lat: 12.5833, lng: -16.2719 },
        { geonameId: 2244070, name: 'Touba', lat: 14.8642, lng: -15.8858 },
        { geonameId: 2248554, name: 'Mbour', lat: 14.4220, lng: -16.9638 },
        { geonameId: 2249901, name: 'Kaolack', lat: 14.1378, lng: -16.0772 },
        { geonameId: 2245226, name: 'Rufisque', lat: 14.7161, lng: -17.2736 },
    ],
};

// Helper to format coordinates safely
const formatCoords = (item) => {
    if (item && typeof item.lat === 'number' && typeof item.lng === 'number') {
        return ` (${item.lat.toFixed(4)}, ${item.lng.toFixed(4)})`;
    }
    if (item && item.lat && item.lng) {
        const lat = parseFloat(item.lat);
        const lng = parseFloat(item.lng);
        if (!isNaN(lat) && !isNaN(lng)) {
            return ` (${lat.toFixed(4)}, ${lng.toFixed(4)})`;
        }
    }
    return '';
};

// Load cities based on selected country
const onCountryChange = async (options = {}) => {
    const preserveSelection = options === true || (options && typeof options === 'object' && options.preserveSelection === true);

    if (!formData.value.pays) {
        cities.value = [];
        neighborhoods.value = [];
        if (!preserveSelection) {
            formData.value.ville = '';
            formData.value.quartier = '';
        }
        return;
    }

    loadingCities.value = true;
    if (!preserveSelection) {
        formData.value.ville = '';
        formData.value.quartier = '';
        neighborhoods.value = [];
    }

    try {
        // Try to fetch from GeoNames API first
        const response = await fetch(
            `https://api.geonames.org/searchJSON?country=${formData.value.pays}&featureClass=P&maxRows=100&username=demo`
        );
        const data = await response.json();
        
        console.log('Cities API response:', data);
        
        // Check for API errors
        if (data.status && data.status.value === 40) {
            console.error('API rate limit exceeded');
            throw new Error('Rate limit exceeded');
        }
        
        if (data.geonames && data.geonames.length > 0) {
            cities.value = data.geonames.map(city => ({
                geonameId: city.geonameId,
                name: city.name,
                lat: parseFloat(city.lat),
                lng: parseFloat(city.lng),
            })).sort((a, b) => a.name.localeCompare(b.name));
            console.log('Cities loaded from API:', cities.value.length);
        } else {
            throw new Error('No cities found');
        }
    } catch (error) {
        console.error('Error loading cities from API:', error);
        
        // Use fallback data if available
        if (fallbackCities[formData.value.pays]) {
            cities.value = fallbackCities[formData.value.pays];
            console.log('Using fallback cities for country:', formData.value.pays);
        } else {
            // If no fallback data, show error
            errorMessage.value = 'Impossible de charger les villes. Veuillez réessayer ou sélectionner un autre pays.';
            showError.value = true;
            cities.value = [];
        }
    } finally {
        loadingCities.value = false;
    }
};

// Fallback neighborhood data for common cities
const fallbackNeighborhoods = {
    'Paris': [
        { geonameId: 1, name: 'Le Marais', lat: 48.8570, lng: 2.3610 },
        { geonameId: 2, name: 'Montmartre', lat: 48.8867, lng: 2.3431 },
        { geonameId: 3, name: 'Saint-Germain', lat: 48.8540, lng: 2.3325 },
        { geonameId: 4, name: 'Le Quartier Latin', lat: 48.8500, lng: 2.3425 },
        { geonameId: 5, name: 'Champs-Élysées', lat: 48.8698, lng: 2.3075 },
        { geonameId: 6, name: 'Bastille', lat: 48.8530, lng: 2.3690 },
        { geonameId: 7, name: 'Belleville', lat: 48.8700, lng: 2.3850 },
        { geonameId: 8, name: 'Canal Saint-Martin', lat: 48.8680, lng: 2.3640 },
    ],
    'Lyon': [
        { geonameId: 1, name: 'Vieux Lyon', lat: 45.7622, lng: 4.8273 },
        { geonameId: 2, name: 'La Croix-Rousse', lat: 45.7744, lng: 4.8315 },
        { geonameId: 3, name: 'Presqu\'île', lat: 45.7597, lng: 4.8322 },
        { geonameId: 4, name: 'Part-Dieu', lat: 45.7610, lng: 4.8573 },
        { geonameId: 5, name: 'Confluence', lat: 45.7428, lng: 4.8185 },
        { geonameId: 6, name: 'Gerland', lat: 45.7275, lng: 4.8310 },
    ],
    'Marseille': [
        { geonameId: 1, name: 'Le Panier', lat: 43.2978, lng: 5.3678 },
        { geonameId: 2, name: 'Vieux-Port', lat: 43.2952, lng: 5.3739 },
        { geonameId: 3, name: 'La Plaine', lat: 43.2942, lng: 5.3857 },
        { geonameId: 4, name: 'Cours Julien', lat: 43.2929, lng: 5.3845 },
        { geonameId: 5, name: 'Endoume', lat: 43.2847, lng: 5.3525 },
        { geonameId: 6, name: 'Belle de Mai', lat: 43.3105, lng: 5.3855 },
    ],
    'Toulouse': [
        { geonameId: 1, name: 'Capitole', lat: 43.6044, lng: 1.4440 },
        { geonameId: 2, name: 'Saint-Cyprien', lat: 43.5992, lng: 1.4322 },
        { geonameId: 3, name: 'Carmes', lat: 43.5969, lng: 1.4445 },
        { geonameId: 4, name: 'Jeanne d\'Arc', lat: 43.6085, lng: 1.4452 },
        { geonameId: 5, name: 'Minimes', lat: 43.6210, lng: 1.4360 },
    ],
    'Nice': [
        { geonameId: 1, name: 'Vieux Nice', lat: 43.6970, lng: 7.2775 },
        { geonameId: 2, name: 'Cimiez', lat: 43.7185, lng: 7.2745 },
        { geonameId: 3, name: 'Libération', lat: 43.7112, lng: 7.2618 },
        { geonameId: 4, name: 'Port', lat: 43.6975, lng: 7.2870 },
        { geonameId: 5, name: 'Promenade des Anglais', lat: 43.6912, lng: 7.2485 },
    ],
    'Bordeaux': [
        { geonameId: 1, name: 'Chartrons', lat: 44.8565, lng: -0.5732 },
        { geonameId: 2, name: 'Saint-Pierre', lat: 44.8402, lng: -0.5707 },
        { geonameId: 3, name: 'Saint-Michel', lat: 44.8340, lng: -0.5651 },
        { geonameId: 4, name: 'La Bastide', lat: 44.8415, lng: -0.5562 },
        { geonameId: 5, name: 'Caudéran', lat: 44.8475, lng: -0.6125 },
    ],
    'Lille': [
        { geonameId: 1, name: 'Vieux Lille', lat: 50.6430, lng: 3.0620 },
        { geonameId: 2, name: 'Wazemmes', lat: 50.6262, lng: 3.0512 },
        { geonameId: 3, name: 'Lille-Centre', lat: 50.6333, lng: 3.0667 },
        { geonameId: 4, name: 'Fives', lat: 50.6300, lng: 3.0900 },
        { geonameId: 5, name: 'Saint-Maurice Pellevoisin', lat: 50.6435, lng: 3.0880 },
    ],
    'New York': [
        { geonameId: 1, name: 'Manhattan', lat: 40.7589, lng: -73.9851 },
        { geonameId: 2, name: 'Brooklyn', lat: 40.6782, lng: -73.9442 },
        { geonameId: 3, name: 'Queens', lat: 40.7282, lng: -73.7949 },
        { geonameId: 4, name: 'Bronx', lat: 40.8448, lng: -73.8648 },
        { geonameId: 5, name: 'Staten Island', lat: 40.5795, lng: -74.1502 },
        { geonameId: 6, name: 'Harlem', lat: 40.8116, lng: -73.9465 },
        { geonameId: 7, name: 'SoHo', lat: 40.7233, lng: -74.0030 },
        { geonameId: 8, name: 'Greenwich Village', lat: 40.7310, lng: -74.0027 },
    ],
    'London': [
        { geonameId: 1, name: 'Westminster', lat: 51.5007, lng: -0.1246 },
        { geonameId: 2, name: 'Kensington', lat: 51.4990, lng: -0.1938 },
        { geonameId: 3, name: 'Camden', lat: 51.5416, lng: -0.1463 },
        { geonameId: 4, name: 'Shoreditch', lat: 51.5265, lng: -0.0825 },
        { geonameId: 5, name: 'Greenwich', lat: 51.4826, lng: -0.0077 },
        { geonameId: 6, name: 'Brixton', lat: 51.4600, lng: -0.1150 },
        { geonameId: 7, name: 'Notting Hill', lat: 51.5130, lng: -0.1950 },
        { geonameId: 8, name: 'Soho', lat: 51.5138, lng: -0.1328 },
    ],
    'Casablanca': [
        { geonameId: 1, name: 'Maârif', lat: 33.5850, lng: -7.6500 },
        { geonameId: 2, name: 'Hassan II', lat: 33.5900, lng: -7.6200 },
        { geonameId: 3, name: 'Ain Diab', lat: 33.5600, lng: -7.6800 },
        { geonameId: 4, name: 'Old Medina', lat: 33.5900, lng: -7.6200 },
        { geonameId: 5, name: 'Roches Noires', lat: 33.5700, lng: -7.6300 },
        { geonameId: 6, name: 'Anfa', lat: 33.5800, lng: -7.6600 },
    ],
    'Rabat': [
        { geonameId: 1, name: 'Hassan', lat: 34.0200, lng: -6.8400 },
        { geonameId: 2, name: 'Agdal', lat: 34.0100, lng: -6.8500 },
        { geonameId: 3, name: 'Souissi', lat: 34.0300, lng: -6.8300 },
        { geonameId: 4, name: 'Old Medina', lat: 34.0250, lng: -6.8450 },
        { geonameId: 5, name: 'Ocean', lat: 34.0150, lng: -6.8600 },
    ],
    'Algiers': [
        { geonameId: 1, name: 'Algiers Centre', lat: 36.7538, lng: 3.0588 },
        { geonameId: 2, name: 'Hussein Dey', lat: 36.7400, lng: 3.0700 },
        { geonameId: 3, name: 'Kouba', lat: 36.7300, lng: 3.0500 },
        { geonameId: 4, name: 'El Biar', lat: 36.7600, lng: 3.0400 },
        { geonameId: 5, name: 'Bab El Oued', lat: 36.7700, lng: 3.0800 },
    ],
    'Tunis': [
        { geonameId: 1, name: 'La Marsa', lat: 36.8700, lng: 10.2800 },
        { geonameId: 2, name: 'Carthage', lat: 36.8500, lng: 10.3200 },
        { geonameId: 3, name: 'Sidi Bou Said', lat: 36.8700, lng: 10.3400 },
        { geonameId: 4, name: 'Le Bardo', lat: 36.8100, lng: 10.1400 },
        { geonameId: 5, name: 'La Goulette', lat: 36.8200, lng: 10.3000 },
    ],
    'Douala': [
        { geonameId: 1, name: 'Akwa', lat: 4.0500, lng: 9.7000 },
        { geonameId: 2, name: 'Bonapriso', lat: 4.0600, lng: 9.7100 },
        { geonameId: 3, name: 'Bonanjo', lat: 4.0400, lng: 9.6900 },
        { geonameId: 4, name: 'Deido', lat: 4.0300, lng: 9.7300 },
        { geonameId: 5, name: 'Bepanda', lat: 4.0200, lng: 9.7500 },
        { geonameId: 6, name: 'New Bell', lat: 4.0100, lng: 9.7600 },
        { geonameId: 7, name: 'Nkololoun', lat: 4.0700, lng: 9.6800 },
    ],
    'Yaoundé': [
        { geonameId: 1, name: 'Bastos', lat: 3.8700, lng: 11.5000 },
        { geonameId: 2, name: 'Mvog-Ada', lat: 3.8600, lng: 11.5100 },
        { geonameId: 3, name: 'Ekoumdoum', lat: 3.8500, lng: 11.5200 },
        { geonameId: 4, name: 'Mvan', lat: 3.8800, lng: 11.4900 },
        { geonameId: 5, name: 'Messamendongo', lat: 3.8900, lng: 11.4800 },
        { geonameId: 6, name: 'Nlongkak', lat: 3.8400, lng: 11.5300 },
        { geonameId: 7, name: 'Oyom-Abang', lat: 3.8300, lng: 11.5400 },
        { geonameId: 8, name: 'Tsinga', lat: 3.8200, lng: 11.5500 },
        { geonameId: 9, name: 'Ngousso', lat: 3.9100, lng: 11.4700 },
        { geonameId: 10, name: 'Emombo', lat: 3.9200, lng: 11.4600 },
    ],
    'Bafoussam': [
        { geonameId: 1, name: 'Djemoum', lat: 5.4700, lng: 10.4200 },
        { geonameId: 2, name: 'Nkong-Long', lat: 5.4600, lng: 10.4100 },
        { geonameId: 3, name: 'Tougang', lat: 5.4800, lng: 10.4300 },
        { geonameId: 4, name: 'Balatchi', lat: 5.4900, lng: 10.4400 },
        { geonameId: 5, name: 'Ndieng', lat: 5.4500, lng: 10.4000 },
    ],
    'Bamenda': [
        { geonameId: 1, name: 'Nkwen', lat: 5.9700, lng: 10.1400 },
        { geonameId: 2, name: 'Mankon', lat: 5.9800, lng: 10.1300 },
        { geonameId: 3, name: 'Bamenda Station', lat: 5.9600, lng: 10.1600 },
        { geonameId: 4, name: 'Mbatu', lat: 5.9900, lng: 10.1200 },
        { geonameId: 5, name: 'Bambili', lat: 6.0000, lng: 10.1100 },
    ],
    'Garoua': [
        { geonameId: 1, name: 'Garoua Central', lat: 9.3000, lng: 13.3800 },
        { geonameId: 2, name: 'Pouss', lat: 9.3100, lng: 13.3900 },
        { geonameId: 3, name: 'Yelwa', lat: 9.2900, lng: 13.3700 },
        { geonameId: 4, name: 'Kousseri', lat: 9.3200, lng: 13.4000 },
        { geonameId: 5, name: 'Figuil', lat: 9.2800, lng: 13.3600 },
    ],
    'Maroua': [
        { geonameId: 1, name: 'Maroua Central', lat: 10.5300, lng: 14.3100 },
        { geonameId: 2, name: 'Djarengol', lat: 10.5400, lng: 14.3200 },
        { geonameId: 3, name: 'Palar', lat: 10.5200, lng: 14.3000 },
        { geonameId: 4, name: 'Mokolo', lat: 10.5500, lng: 14.3300 },
        { geonameId: 5, name: 'Yagoua', lat: 10.5100, lng: 14.2900 },
    ],
    'Buea': [
        { geonameId: 1, name: 'Buea Town', lat: 4.1500, lng: 9.2300 },
        { geonameId: 2, name: 'Molyko', lat: 4.1400, lng: 9.2400 },
        { geonameId: 3, name: 'Bokova', lat: 4.1600, lng: 9.2200 },
        { geonameId: 4, name: 'Great Soppo', lat: 4.1300, lng: 9.2500 },
        { geonameId: 5, name: 'Muea', lat: 4.1700, lng: 9.2100 },
    ],
    'Kumba': [
        { geonameId: 1, name: 'Kumba Town', lat: 4.6300, lng: 9.4300 },
        { geonameId: 2, name: 'Fiango', lat: 4.6400, lng: 9.4400 },
        { geonameId: 3, name: 'Buea Road', lat: 4.6200, lng: 9.4200 },
        { geonameId: 4, name: 'Muea', lat: 4.6500, lng: 9.4500 },
        { geonameId: 5, name: 'Kumba Mbeng', lat: 4.6100, lng: 9.4100 },
    ],
    'Nkongsamba': [
        { geonameId: 1, name: 'Nkongsamba Central', lat: 4.9500, lng: 9.9300 },
        { geonameId: 2, name: 'Nkongsamba Market', lat: 4.9600, lng: 9.9400 },
        { geonameId: 3, name: 'Mbanga', lat: 4.9400, lng: 9.9200 },
        { geonameId: 4, name: 'Loum', lat: 4.9700, lng: 9.9500 },
        { geonameId: 5, name: 'Manjo', lat: 4.9300, lng: 9.9100 },
    ],
    'Edéa': [
        { geonameId: 1, name: 'Edéa Central', lat: 3.8000, lng: 10.1300 },
        { geonameId: 2, name: 'Edéa Market', lat: 3.8100, lng: 10.1400 },
        { geonameId: 3, name: 'Bipindi', lat: 3.7900, lng: 10.1200 },
        { geonameId: 4, name: 'Eseka', lat: 3.8200, lng: 10.1500 },
        { geonameId: 5, name: 'Ngambe', lat: 3.7800, lng: 10.1100 },
    ],
    'Limbe': [
        { geonameId: 1, name: 'Limbe Town', lat: 4.0167, lng: 9.2000 },
        { geonameId: 2, name: 'Down Beach', lat: 4.0067, lng: 9.2100 },
        { geonameId: 3, name: 'Bota', lat: 4.0267, lng: 9.1900 },
        { geonameId: 4, name: 'Mile 4', lat: 4.0367, lng: 9.1800 },
        { geonameId: 5, name: 'Mile 6', lat: 3.9967, lng: 9.2200 },
    ],
    'Bertoua': [
        { geonameId: 1, name: 'Bertoua Central', lat: 4.5667, lng: 13.6833 },
        { geonameId: 2, name: 'Bertoua Market', lat: 4.5767, lng: 13.6933 },
        { geonameId: 3, name: 'Batouri', lat: 4.5567, lng: 13.6733 },
        { geonameId: 4, name: 'Yokadouma', lat: 4.5867, lng: 13.7033 },
        { geonameId: 5, name: 'Moloundou', lat: 4.5467, lng: 13.6633 },
    ],
    'Ebolowa': [
        { geonameId: 1, name: 'Ebolowa Central', lat: 2.9167, lng: 11.1500 },
        { geonameId: 2, name: 'Ebolowa Market', lat: 2.9267, lng: 11.1600 },
        { geonameId: 3, name: 'Kribi', lat: 2.9067, lng: 11.1400 },
        { geonameId: 4, name: 'Akonolinga', lat: 2.9367, lng: 11.1700 },
        { geonameId: 5, name: 'Sangmelima', lat: 2.8967, lng: 11.1300 },
    ],
    'Ngaoundéré': [
        { geonameId: 1, name: 'Ngaoundéré Central', lat: 7.3167, lng: 13.5667 },
        { geonameId: 2, name: 'Ngaoundéré Market', lat: 7.3267, lng: 13.5767 },
        { geonameId: 3, name: 'Tignère', lat: 7.3067, lng: 13.5567 },
        { geonameId: 4, name: 'Meiganga', lat: 7.3367, lng: 13.5867 },
        { geonameId: 5, name: 'Martap', lat: 7.2967, lng: 13.5467 },
    ],
    'Kribi': [
        { geonameId: 1, name: 'Kribi Town', lat: 2.9333, lng: 9.9167 },
        { geonameId: 2, name: 'Kribi Beach', lat: 2.9233, lng: 9.9267 },
        { geonameId: 3, name: 'Lolabe', lat: 2.9433, lng: 9.9067 },
        { geonameId: 4, name: 'Campo', lat: 2.9133, lng: 9.9367 },
        { geonameId: 5, name: 'Akom II', lat: 2.9533, lng: 9.8967 },
    ],
    'Foumban': [
        { geonameId: 1, name: 'Foumban Central', lat: 5.4333, lng: 10.7000 },
        { geonameId: 2, name: 'Foumban Market', lat: 5.4433, lng: 10.7100 },
        { geonameId: 3, name: 'Bafang', lat: 5.4233, lng: 10.6900 },
        { geonameId: 4, name: 'Bamendjou', lat: 5.4533, lng: 10.7200 },
        { geonameId: 5, name: 'Koutaba', lat: 5.4133, lng: 10.6800 },
    ],
    'Dschang': [
        { geonameId: 1, name: 'Dschang Central', lat: 5.4500, lng: 10.0500 },
        { geonameId: 2, name: 'Dschang Market', lat: 5.4600, lng: 10.0600 },
        { geonameId: 3, name: 'Fotouni', lat: 5.4400, lng: 10.0400 },
        { geonameId: 4, name: 'Batcham', lat: 5.4700, lng: 10.0700 },
        { geonameId: 5, name: 'Bafoussam', lat: 5.4300, lng: 10.0300 },
    ],
    'Mbouda': [
        { geonameId: 1, name: 'Mbouda Central', lat: 5.6167, lng: 10.2500 },
        { geonameId: 2, name: 'Mbouda Market', lat: 5.6267, lng: 10.2600 },
        { geonameId: 3, name: 'Bamenda', lat: 5.6067, lng: 10.2400 },
        { geonameId: 4, name: 'Bafoussam', lat: 5.6367, lng: 10.2700 },
        { geonameId: 5, name: 'Bamenda', lat: 5.5967, lng: 10.2300 },
    ],
    'Tiko': [
        { geonameId: 1, name: 'Tiko Central', lat: 4.0667, lng: 9.3667 },
        { geonameId: 2, name: 'Tiko Market', lat: 4.0767, lng: 9.3767 },
        { geonameId: 3, name: 'Limbe', lat: 4.0567, lng: 9.3567 },
        { geonameId: 4, name: 'Buea', lat: 4.0867, lng: 9.3867 },
        { geonameId: 5, name: 'Douala', lat: 4.0467, lng: 9.3467 },
    ],
    'Abidjan': [
        { geonameId: 1, name: 'Cocody', lat: 5.3484, lng: -3.9867 },
        { geonameId: 2, name: 'Plateau', lat: 5.3210, lng: -4.0178 },
        { geonameId: 3, name: 'Marcory', lat: 5.3022, lng: -3.9858 },
        { geonameId: 4, name: 'Yopougon', lat: 5.3400, lng: -4.0800 },
        { geonameId: 5, name: 'Treichville', lat: 5.2970, lng: -4.0135 },
        { geonameId: 6, name: 'Adjamé', lat: 5.3565, lng: -4.0240 },
        { geonameId: 7, name: 'Koumassi', lat: 5.2905, lng: -3.9620 },
        { geonameId: 8, name: 'Abobo', lat: 5.4167, lng: -4.0167 },
        { geonameId: 9, name: 'Port-Bouët', lat: 5.2570, lng: -3.9350 },
    ],
    'Yamoussoukro': [
        { geonameId: 1, name: 'Morofé', lat: 6.8350, lng: -5.2650 },
        { geonameId: 2, name: 'Assabou', lat: 6.8150, lng: -5.2800 },
        { geonameId: 3, name: 'N\'Gokro', lat: 6.8270, lng: -5.2750 },
        { geonameId: 4, name: 'Dioulabougou', lat: 6.8200, lng: -5.2900 },
    ],
    'Bouaké': [
        { geonameId: 1, name: 'Nimbo', lat: 7.7050, lng: -5.0250 },
        { geonameId: 2, name: 'Ahougnansou', lat: 7.6850, lng: -5.0400 },
        { geonameId: 3, name: 'Air France', lat: 7.7120, lng: -5.0150 },
        { geonameId: 4, name: 'Kennedy', lat: 7.6800, lng: -5.0200 },
        { geonameId: 5, name: 'Broukro', lat: 7.6900, lng: -5.0500 },
    ],
    'San-Pédro': [
        { geonameId: 1, name: 'Bardot', lat: 4.7550, lng: -6.6450 },
        { geonameId: 2, name: 'Cité', lat: 4.7450, lng: -6.6300 },
        { geonameId: 3, name: 'Nitoro', lat: 4.7350, lng: -6.6250 },
        { geonameId: 4, name: 'Balmer', lat: 4.7250, lng: -6.6550 },
    ],
    'Korhogo': [
        { geonameId: 1, name: 'Koko', lat: 9.4520, lng: -5.6200 },
        { geonameId: 2, name: 'Soba', lat: 9.4630, lng: -5.6350 },
        { geonameId: 3, name: 'Tchekelezo', lat: 9.4450, lng: -5.6400 },
    ],
    'Daloa': [
        { geonameId: 1, name: 'Tazibouo', lat: 6.8850, lng: -6.4420 },
        { geonameId: 2, name: 'Loboguia', lat: 6.8700, lng: -6.4600 },
        { geonameId: 3, name: 'Gbeuliville', lat: 6.8780, lng: -6.4520 },
    ],
    'Man': [
        { geonameId: 1, name: 'Libreville', lat: 7.4200, lng: -7.5450 },
        { geonameId: 2, name: 'Kassiapleu', lat: 7.4050, lng: -7.5600 },
        { geonameId: 3, name: 'Gbeupleu', lat: 7.4150, lng: -7.5500 },
    ],
    'Dakar': [
        { geonameId: 1, name: 'Plateau', lat: 14.6670, lng: -17.4330 },
        { geonameId: 2, name: 'Médina', lat: 14.6830, lng: -17.4500 },
        { geonameId: 3, name: 'Yoff', lat: 14.7600, lng: -17.4700 },
        { geonameId: 4, name: 'Almadies', lat: 14.7470, lng: -17.5180 },
        { geonameId: 5, name: 'Ngor', lat: 14.7500, lng: -17.5100 },
        { geonameId: 6, name: 'Ouakam', lat: 14.7200, lng: -17.4900 },
        { geonameId: 7, name: 'Hann Bel-Air', lat: 14.7100, lng: -17.4300 },
        { geonameId: 8, name: 'Fann', lat: 14.6900, lng: -17.4700 },
    ],
    'Thiès': [
        { geonameId: 1, name: 'Dixième', lat: 14.7950, lng: -16.9200 },
        { geonameId: 2, name: 'Mbouranène', lat: 14.7850, lng: -16.9350 },
        { geonameId: 3, name: 'Grand Thiès', lat: 14.7900, lng: -16.9250 },
        { geonameId: 4, name: 'Cité Lamy', lat: 14.8050, lng: -16.9150 },
    ],
    'Saint-Louis': [
        { geonameId: 1, name: 'Ndar', lat: 16.0200, lng: -16.5000 },
        { geonameId: 2, name: 'Guet Ndar', lat: 16.0250, lng: -16.5100 },
        { geonameId: 3, name: 'Sor', lat: 16.0150, lng: -16.4900 },
    ],
    'Ziguinchor': [
        { geonameId: 1, name: 'Escale', lat: 12.5850, lng: -16.2700 },
        { geonameId: 2, name: 'Boudody', lat: 12.5800, lng: -16.2650 },
        { geonameId: 3, name: 'Santhiaba', lat: 12.5750, lng: -16.2750 },
        { geonameId: 4, name: 'Nema', lat: 12.5900, lng: -16.2800 },
    ],
    'Touba': [
        { geonameId: 1, name: 'Darou Mousty', lat: 14.8700, lng: -15.8800 },
        { geonameId: 2, name: 'Darou Marnane', lat: 14.8600, lng: -15.8900 },
        { geonameId: 3, name: 'Darou Khoudoss', lat: 14.8580, lng: -15.8750 },
    ],
};

// Load neighborhoods based on selected city
const onCityChange = async (options = {}) => {
    const preserveSelection = options === true || (options && typeof options === 'object' && options.preserveSelection === true);

    if (!formData.value.ville) {
        neighborhoods.value = [];
        if (!preserveSelection) {
            formData.value.quartier = '';
        }
        return;
    }

    loadingNeighborhoods.value = true;
    if (!preserveSelection) {
        formData.value.quartier = '';
    }

    try {
        // Get the selected city's coordinates
        const selectedCity = cities.value.find(c => c.name === formData.value.ville);
        if (!selectedCity) return;

        // Try to fetch from GeoNames API
        const response = await fetch(
            `https://api.geonames.org/searchJSON?lat=${selectedCity.lat}&lng=${selectedCity.lng}&radius=10&featureCode=PPL&featureCode=PPLA&featureCode=PPLA2&featureCode=PPLA3&featureCode=PPLA4&maxRows=50&username=demo`
        );
        const data = await response.json();
        
        console.log('Neighborhoods API response:', data);
        
        // Check for API errors
        if (data.status && data.status.value === 40) {
            console.error('API rate limit exceeded');
            throw new Error('Rate limit exceeded');
        }

        if (data.geonames && data.geonames.length > 0) {
            // Filter out the city itself and get nearby districts/neighborhoods
            neighborhoods.value = data.geonames
                .filter(place => place.name !== selectedCity.name)
                .map(place => ({
                    geonameId: place.geonameId,
                    name: place.name,
                    lat: parseFloat(place.lat),
                    lng: parseFloat(place.lng),
                }))
                .sort((a, b) => a.name.localeCompare(b.name));
            console.log('Neighborhoods loaded from API:', neighborhoods.value.length);
        } else {
            throw new Error('No neighborhoods found');
        }
    } catch (error) {
        console.error('Error loading neighborhoods from API:', error);
        
        // Use fallback data if available
        if (fallbackNeighborhoods[formData.value.ville]) {
            neighborhoods.value = fallbackNeighborhoods[formData.value.ville];
            console.log('Using fallback neighborhoods for city:', formData.value.ville);
        } else {
            // If no fallback data, just clear the array
            neighborhoods.value = [];
            console.log('No fallback neighborhoods for city:', formData.value.ville);
        }
    } finally {
        loadingNeighborhoods.value = false;
    }
};

// Detect user's location using browser geolocation
const detectLocation = async () => {
    if (!navigator.geolocation) {
        errorMessage.value = 'La géolocalisation n\'est pas supportée par votre navigateur';
        showError.value = true;
        return;
    }

    try {
        const position = await new Promise((resolve, reject) => {
            navigator.geolocation.getCurrentPosition(resolve, reject, {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0,
            });
        });

        const { latitude, longitude } = position.coords;

        // Reverse geocode to get country and city
        const response = await fetch(
            `https://api.geonames.org/findNearbyPlaceNameJSON?lat=${latitude}&lng=${longitude}&username=demo`
        );
        const data = await response.json();

        if (data.geonames && data.geonames.length > 0) {
            const place = data.geonames[0];
            
            // Set country code
            if (place.countryCode) {
                formData.value.pays = place.countryCode;
                await onCountryChange();
                
                // Set city if available
                if (place.name) {
                    formData.value.ville = place.name;
                    await onCityChange();
                }
            }
        }
    } catch (error) {
        console.error('Error detecting location:', error);
        errorMessage.value = 'Impossible de détecter votre position. Veuillez sélectionner manuellement.';
        showError.value = true;
    }
};

const openAddModal = () => {
    editingBatiment.value = null;
    formData.value = { 
        nom: '', 
        proprietaire: '',
        pays: '',
        ville: '',
        quartier: '',
        adresse: '', 
        etages: '', 
        appartements: '',
        statut: 'Actif',
        agency_id: ''
    };
    cities.value = [];
    neighborhoods.value = [];
    showModal.value = true;
};

const openEditModal = async (batiment) => {
    editingBatiment.value = batiment;
    formData.value = { 
        ...batiment,
        agency_id: batiment.agency_id || ''
    };
    showModal.value = true;

    if (formData.value.pays) {
        await onCountryChange({ preserveSelection: true });
        if (formData.value.ville) {
            await onCityChange({ preserveSelection: true });
        }
    }
};

const closeModal = () => {
    showModal.value = false;
    editingBatiment.value = null;
};

const openDeleteModal = (batiment) => {
    deletingBatiment.value = batiment;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    deletingBatiment.value = null;
};

const saveBatiment = () => {
    if (!formData.value.nom || !formData.value.adresse || !formData.value.pays || !formData.value.ville) {
        errorMessage.value = 'Le nom, le pays, la ville et l\'adresse sont requis.';
        showError.value = true;
        return;
    }

    const batData = {
        nom: formData.value.nom,
        proprietaire: formData.value.proprietaire || '',
        pays: formData.value.pays,
        ville: formData.value.ville,
        quartier: formData.value.quartier || '',
        adresse: formData.value.adresse,
        etages: Number(formData.value.etages || 0),
        appartements: Number(formData.value.appartements || 0),
        statut: formData.value.statut || 'Actif',
        agency_id: formData.value.agency_id || '',
    };

    if (editingBatiment.value) {
        const index = batiments.value.findIndex(b => b.id === editingBatiment.value.id);
        if (index !== -1) {
            batiments.value[index] = {
                id: editingBatiment.value.id,
                ...batData
            };
        }
        successMessage.value = 'Bâtiment modifié avec succès';
    } else {
        const newId = Math.max(...batiments.value.map(b => b.id), 0) + 1;
        batiments.value.push({
            id: newId,
            ...batData
        });
        successMessage.value = 'Bâtiment ajouté avec succès';
    }

    saveBatimentsToStorage();
    closeModal();
    showSuccess.value = true;
};

const confirmDelete = () => {
    const index = batiments.value.findIndex(b => b.id === deletingBatiment.value.id);
    if (index !== -1) {
        batiments.value.splice(index, 1);
        successMessage.value = 'Bâtiment supprimé avec succès';
        saveBatimentsToStorage();
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

const getInitials = (name) => {
    if (!name) return 'B';
    const parts = name.split(' ');
    if (parts.length > 1) {
        return (parts[0].charAt(0) + parts[1].charAt(0)).toUpperCase();
    }
    return name.substring(0, 2).toUpperCase();
};

const getCountryName = (code) => {
    const country = countries.value.find(c => c.code === code);
    return country ? country.name : code;
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
