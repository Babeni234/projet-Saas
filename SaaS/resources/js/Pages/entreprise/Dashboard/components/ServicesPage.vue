<template>
    <div class="flex flex-col gap-6">
        <!-- Header with Add Button -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Services</h1>
                <p class="text-slate-600 mt-1">Gestion des services hôteliers</p>
            </div>
            <button
                @click="openAddModal"
                class="flex items-center gap-2 px-4 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-medium shadow-lg shadow-indigo-500/30 hover:shadow-xl hover:shadow-indigo-500/40 transition-all"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Ajouter un Service
            </button>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-indigo-500/20 animate-fade-in" style="animation-delay: 0ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Total Services</p>
                        <p class="text-3xl font-bold text-slate-900 mt-1 animate-number">{{ totalServices }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-emerald-500/20 animate-fade-in" style="animation-delay: 100ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Services Actifs</p>
                        <p class="text-3xl font-bold text-emerald-600 mt-1 animate-number">{{ servicesActifs }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-blue-500/20 animate-fade-in" style="animation-delay: 200ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Services Gratuits</p>
                        <p class="text-3xl font-bold text-blue-600 mt-1 animate-number">{{ servicesGratuits }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-violet-500/20 animate-fade-in" style="animation-delay: 300ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Revenu Mensuel</p>
                        <p class="text-3xl font-bold text-slate-900 mt-1 animate-number">{{ revenuMensuel }}€</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-violet-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-violet-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.312-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.312.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-4 border border-slate-100">
            <div class="relative">
                <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Rechercher un service..."
                    class="w-full pl-12 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                >
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 overflow-hidden border border-slate-100">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Nom</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Catégorie</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Prix</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Disponibilité</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Statut</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-slate-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="service in filteredServices" :key="service.id" class="border-b border-slate-100 hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-medium text-slate-900">{{ service.nom }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ service.categorie }}</td>
                            <td class="px-6 py-4 font-semibold text-indigo-600">{{ service.prix === 0 ? 'Gratuit' : service.prix + '€' }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ service.disponibilite }}</td>
                            <td class="px-6 py-4">
                                <span :class="[
                                    'px-3 py-1 rounded-full text-xs font-semibold',
                                    service.statut === 'Actif' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700'
                                ]">
                                    {{ service.statut }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="openEditModal(service)" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button @click="openDeleteModal(service)" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add/Edit Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-black/60 backdrop-blur-md z-50 flex items-center justify-center p-4 animate-modal-in">
            <div class="bg-gradient-to-br from-white via-white to-indigo-50/30 rounded-3xl shadow-2xl shadow-indigo-900/20 max-w-md w-full p-8 border border-indigo-100/50 relative overflow-hidden">
                <!-- Decorative gradient blob -->
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-gradient-to-br from-indigo-400/20 to-purple-500/20 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-gradient-to-br from-indigo-400/20 to-purple-500/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent">{{ editingService ? 'Modifier' : 'Nouveau' }} Service</h2>
                        </div>
                        <button @click="closeModal" class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-all hover:rotate-90">
                            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-5">
                        <div class="group">
                            <label class="block text-sm font-semibold text-slate-700 mb-2 group-focus-within:text-indigo-600 transition-colors">Nom du service</label>
                            <input v-model="formData.nom" type="text" placeholder="Ex: Petit-déjeuner" class="w-full px-5 py-4 bg-white/80 border-2 border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all shadow-sm group-hover:shadow-md">
                        </div>
                        <div class="group">
                            <label class="block text-sm font-semibold text-slate-700 mb-2 group-focus-within:text-indigo-600 transition-colors">Catégorie</label>
                            <select v-model="formData.categorie" class="w-full px-5 py-4 bg-white/80 border-2 border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all shadow-sm group-hover:shadow-md">
                                <option value="Restauration">Restauration</option>
                                <option value="Bien-être">Bien-être</option>
                                <option value="Transport">Transport</option>
                                <option value="Divertissement">Divertissement</option>
                                <option value="Concierge">Concierge</option>
                            </select>
                        </div>
                        <div class="group">
                            <label class="block text-sm font-semibold text-slate-700 mb-2 group-focus-within:text-indigo-600 transition-colors">Prix (€)</label>
                            <input v-model="formData.prix" type="number" placeholder="0 pour gratuit" class="w-full px-5 py-4 bg-white/80 border-2 border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all shadow-sm group-hover:shadow-md">
                        </div>
                        <div class="group">
                            <label class="block text-sm font-semibold text-slate-700 mb-2 group-focus-within:text-indigo-600 transition-colors">Disponibilité</label>
                            <select v-model="formData.disponibilite" class="w-full px-5 py-4 bg-white/80 border-2 border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all shadow-sm group-hover:shadow-md">
                                <option value="24/7">24/7</option>
                                <option value="Sur demande">Sur demande</option>
                                <option value="Horaires fixes">Horaires fixes</option>
                            </select>
                        </div>
                        <div class="group">
                            <label class="block text-sm font-semibold text-slate-700 mb-2 group-focus-within:text-indigo-600 transition-colors">Statut</label>
                            <select v-model="formData.statut" class="w-full px-5 py-4 bg-white/80 border-2 border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all shadow-sm group-hover:shadow-md">
                                <option value="Actif">Actif</option>
                                <option value="Inactif">Inactif</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-4 mt-8">
                        <button @click="closeModal" class="flex-1 px-6 py-4 bg-slate-100 text-slate-700 rounded-2xl font-semibold hover:bg-slate-200 transition-all hover:shadow-lg">Annuler</button>
                        <button @click="saveService" class="flex-1 px-6 py-4 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-2xl font-semibold shadow-lg shadow-indigo-500/30 hover:shadow-xl hover:shadow-indigo-500/40 transition-all hover:scale-[1.02]">Enregistrer</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div v-if="showDeleteModal" class="fixed inset-0 bg-black/60 backdrop-blur-md z-50 flex items-center justify-center p-4 animate-modal-in">
            <div class="bg-gradient-to-br from-white via-white to-red-50/30 rounded-3xl shadow-2xl shadow-red-900/20 max-w-md w-full p-8 border border-red-100/50 relative overflow-hidden">
                <!-- Decorative gradient blob -->
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-gradient-to-br from-red-400/20 to-pink-500/20 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-gradient-to-br from-red-400/20 to-pink-500/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center justify-center w-20 h-20 rounded-3xl bg-gradient-to-br from-red-500 to-pink-600 mx-auto mb-6 shadow-xl shadow-red-500/30">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-center bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent mb-3">Supprimer ce service?</h3>
                    <p class="text-center text-slate-600 mb-8 text-lg">Cette action est irréversible</p>

                    <div class="flex gap-4">
                        <button @click="closeDeleteModal" class="flex-1 px-6 py-4 bg-slate-100 text-slate-700 rounded-2xl font-semibold hover:bg-slate-200 transition-all hover:shadow-lg">Annuler</button>
                        <button @click="confirmDelete" class="flex-1 px-6 py-4 bg-gradient-to-r from-red-500 to-pink-600 text-white rounded-2xl font-semibold shadow-lg shadow-red-500/30 hover:shadow-xl hover:shadow-red-500/40 transition-all hover:scale-[1.02]">Supprimer</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Modal -->
        <div v-if="showSuccess" class="fixed inset-0 bg-black/60 backdrop-blur-md z-50 flex items-center justify-center p-4 animate-modal-in">
            <div class="bg-gradient-to-br from-white via-white to-emerald-50/30 rounded-3xl shadow-2xl shadow-emerald-900/20 max-w-md w-full p-8 border border-emerald-100/50 relative overflow-hidden">
                <!-- Decorative gradient blob -->
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-gradient-to-br from-emerald-400/20 to-green-500/20 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-gradient-to-br from-emerald-400/20 to-green-500/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center justify-center w-20 h-20 rounded-3xl bg-gradient-to-br from-emerald-500 to-green-600 mx-auto mb-6 shadow-xl shadow-emerald-500/30 animate-bounce-slow">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-center bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent mb-3">{{ successMessage }}</h3>
                    <p class="text-center text-slate-600 mb-8 text-lg">Opération effectuée avec succès</p>

                    <button @click="closeSuccess" class="w-full px-6 py-4 bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-2xl font-semibold shadow-lg shadow-emerald-500/30 hover:shadow-xl hover:shadow-emerald-500/40 transition-all hover:scale-[1.02]">Fermer</button>
                </div>
            </div>
        </div>

        <!-- Error Modal -->
        <div v-if="showError" class="fixed inset-0 bg-black/60 backdrop-blur-md z-50 flex items-center justify-center p-4 animate-modal-in">
            <div class="bg-gradient-to-br from-white via-white to-red-50/30 rounded-3xl shadow-2xl shadow-red-900/20 max-w-md w-full p-8 border border-red-100/50 relative overflow-hidden">
                <!-- Decorative gradient blob -->
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-gradient-to-br from-red-400/20 to-rose-500/20 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-gradient-to-br from-red-400/20 to-rose-500/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center justify-center w-20 h-20 rounded-3xl bg-gradient-to-br from-red-500 to-rose-600 mx-auto mb-6 shadow-xl shadow-red-500/30 animate-shake">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-center bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent mb-3">Erreur</h3>
                    <p class="text-center text-slate-600 mb-8 text-lg">{{ errorMessage }}</p>

                    <button @click="closeError" class="w-full px-6 py-4 bg-gradient-to-r from-red-500 to-rose-600 text-white rounded-2xl font-semibold shadow-lg shadow-red-500/30 hover:shadow-xl hover:shadow-red-500/40 transition-all hover:scale-[1.02]">Fermer</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const services = ref([
    { id: 1, nom: 'Petit-déjeuner', categorie: 'Restauration', prix: 15, disponibilite: 'Horaires fixes', statut: 'Actif' },
    { id: 2, nom: 'Spa & Massage', categorie: 'Bien-être', prix: 80, disponibilite: 'Sur demande', statut: 'Actif' },
    { id: 3, nom: 'Navette aéroport', categorie: 'Transport', prix: 30, disponibilite: 'Sur demande', statut: 'Actif' },
    { id: 4, nom: 'Wi-Fi', categorie: 'Concierge', prix: 0, disponibilite: '24/7', statut: 'Actif' },
    { id: 5, nom: 'Room Service', categorie: 'Restauration', prix: 10, disponibilite: '24/7', statut: 'Actif' },
    { id: 6, nom: 'Parking', categorie: 'Transport', prix: 20, disponibilite: '24/7', statut: 'Actif' },
]);

const searchQuery = ref('');

const filteredServices = computed(() => {
    if (!searchQuery.value) return services.value;
    const query = searchQuery.value.toLowerCase();
    return services.value.filter(s => 
        s.nom.toLowerCase().includes(query) ||
        s.categorie.toLowerCase().includes(query)
    );
});

const totalServices = computed(() => services.value.length);
const servicesActifs = computed(() => services.value.filter(s => s.statut === 'Actif').length);
const servicesGratuits = computed(() => services.value.filter(s => s.prix === 0).length);
const revenuMensuel = computed(() => services.value.filter(s => s.prix > 0).reduce((sum, s) => sum + s.prix * 50, 0));

const showModal = ref(false);
const showDeleteModal = ref(false);
const showSuccess = ref(false);
const showError = ref(false);
const editingService = ref(null);
const deletingService = ref(null);
const successMessage = ref('');
const errorMessage = ref('');

const formData = ref({
    nom: '',
    categorie: 'Restauration',
    prix: '',
    disponibilite: '24/7',
    statut: 'Actif',
});

const openAddModal = () => {
    editingService.value = null;
    formData.value = { nom: '', categorie: 'Restauration', prix: '', disponibilite: '24/7', statut: 'Actif' };
    showModal.value = true;
};

const openEditModal = (service) => {
    editingService.value = service;
    formData.value = { ...service };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editingService.value = null;
};

const openDeleteModal = (service) => {
    deletingService.value = service;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    deletingService.value = null;
};

const saveService = () => {
    if (!formData.value.nom || !formData.value.categorie) {
        errorMessage.value = 'Veuillez remplir tous les champs';
        showError.value = true;
        return;
    }

    if (editingService.value) {
        const index = services.value.findIndex(s => s.id === editingService.value.id);
        if (index !== -1) {
            services.value[index] = { ...editingService.value, ...formData.value };
        }
        successMessage.value = 'Service modifié avec succès';
    } else {
        services.value.push({
            id: Math.max(...services.value.map(s => s.id), 0) + 1,
            ...formData.value,
        });
        successMessage.value = 'Service créé avec succès';
    }

    closeModal();
    showSuccess.value = true;
};

const confirmDelete = () => {
    const index = services.value.findIndex(s => s.id === deletingService.value.id);
    if (index !== -1) {
        services.value.splice(index, 1);
        successMessage.value = 'Service supprimé avec succès';
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

@keyframes modalIn {
    from {
        opacity: 0;
        transform: scale(0.9) translateY(20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

@keyframes bounceSlow {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}

@keyframes shake {
    0%, 100% {
        transform: translateX(0);
    }
    25% {
        transform: translateX(-5px);
    }
    75% {
        transform: translateX(5px);
    }
}

.animate-fade-in {
    animation: fadeIn 0.5s ease-out forwards;
    opacity: 0;
}

.animate-number {
    animation: countUp 0.5s ease-out;
}

.animate-modal-in {
    animation: modalIn 0.3s ease-out forwards;
}

.animate-bounce-slow {
    animation: bounceSlow 2s ease-in-out infinite;
}

.animate-shake {
    animation: shake 0.5s ease-in-out;
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
