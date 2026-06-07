<template>
    <div class="flex flex-col gap-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Users -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-slate-800">24</div>
                        <div class="text-sm text-slate-500">Utilisateurs Actifs</div>
                    </div>
                </div>
            </div>

            <!-- Total Roles -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-slate-800">5</div>
                        <div class="text-sm text-slate-500">Rôles Configurés</div>
                    </div>
                </div>
            </div>

            <!-- Pending Requests -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100 border-l-4 border-l-amber-500">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-slate-800">3</div>
                        <div class="text-sm text-slate-500">Demandes en Attente</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Roles Section -->
        <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-slate-800">Rôles et Permissions</h3>
                <button class="px-4 py-2 bg-blue-500 text-white rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors shadow-md shadow-blue-500/20">Ajouter un Rôle</button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Admin Role -->
                <div class="p-5 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl border border-purple-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                                <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="px-2 py-1 bg-purple-500 text-white rounded-full text-xs font-semibold">Admin</div>
                    </div>
                    <div class="font-semibold text-slate-800 mb-2">Administrateur</div>
                    <div class="text-sm text-slate-600 mb-4">Accès complet à toutes les fonctionnalités</div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500">3 utilisateurs</span>
                        <button class="text-xs text-purple-600 font-medium hover:text-purple-700">Gérer</button>
                    </div>
                </div>

                <!-- Manager Role -->
                <div class="p-5 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border border-blue-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="px-2 py-1 bg-blue-500 text-white rounded-full text-xs font-semibold">Manager</div>
                    </div>
                    <div class="font-semibold text-slate-800 mb-2">Gestionnaire</div>
                    <div class="text-sm text-slate-600 mb-4">Gestion des opérations quotidiennes</div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500">8 utilisateurs</span>
                        <button class="text-xs text-blue-600 font-medium hover:text-blue-700">Gérer</button>
                    </div>
                </div>

                <!-- Accountant Role -->
                <div class="p-5 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl border border-emerald-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 bg-emerald-500 rounded-lg flex items-center justify-center">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                                <path d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="px-2 py-1 bg-emerald-500 text-white rounded-full text-xs font-semibold">Comptable</div>
                    </div>
                    <div class="font-semibold text-slate-800 mb-2">Comptable</div>
                    <div class="text-sm text-slate-600 mb-4">Accès aux rapports financiers</div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500">4 utilisateurs</span>
                        <button class="text-xs text-emerald-600 font-medium hover:text-emerald-700">Gérer</button>
                    </div>
                </div>

                <!-- Maintenance Role -->
                <div class="p-5 bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl border border-amber-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 bg-amber-500 rounded-lg flex items-center justify-center">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                                <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="px-2 py-1 bg-amber-500 text-white rounded-full text-xs font-semibold">Maintenance</div>
                    </div>
                    <div class="font-semibold text-slate-800 mb-2">Maintenance</div>
                    <div class="text-sm text-slate-600 mb-4">Gestion des tickets de maintenance</div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500">5 utilisateurs</span>
                        <button class="text-xs text-amber-600 font-medium hover:text-amber-700">Gérer</button>
                    </div>
                </div>

                <!-- Receptionist Role -->
                <div class="p-5 bg-gradient-to-br from-pink-50 to-pink-100 rounded-xl border border-pink-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 bg-pink-500 rounded-lg flex items-center justify-center">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                                <path d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="px-2 py-1 bg-pink-500 text-white rounded-full text-xs font-semibold">Réception</div>
                    </div>
                    <div class="font-semibold text-slate-800 mb-2">Réceptionniste</div>
                    <div class="text-sm text-slate-600 mb-4">Gestion des réservations hôtelières</div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500">3 utilisateurs</span>
                        <button class="text-xs text-pink-600 font-medium hover:text-pink-700">Gérer</button>
                    </div>
                </div>

                <!-- Viewer Role -->
                <div class="p-5 bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl border border-slate-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 bg-slate-500 rounded-lg flex items-center justify-center">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="px-2 py-1 bg-slate-500 text-white rounded-full text-xs font-semibold">Lecteur</div>
                    </div>
                    <div class="font-semibold text-slate-800 mb-2">Lecteur Seulement</div>
                    <div class="text-sm text-slate-600 mb-4">Accès en lecture seule</div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500">1 utilisateur</span>
                        <button class="text-xs text-slate-600 font-medium hover:text-slate-700">Gérer</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-slate-800">Utilisateurs</h3>
                <button class="px-4 py-2 bg-blue-500 text-white rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors shadow-md shadow-blue-500/20">Ajouter un Utilisateur</button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Utilisateur</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Rôle</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Statut</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Dernière Connexion</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">AD</div>
                                    <div>
                                        <div class="font-semibold text-slate-800">Admin Principal</div>
                                        <div class="text-xs text-slate-500">admin@company.com</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-600">admin@company.com</td>
                            <td class="px-4 py-4"><span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-semibold">Admin</span></td>
                            <td class="px-4 py-4"><span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">Actif</span></td>
                            <td class="px-4 py-4 text-sm text-slate-600">Il y a 2h</td>
                            <td class="px-4 py-4">
                                <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">Modifier</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">ML</div>
                                    <div>
                                        <div class="font-semibold text-slate-800">Marie Lambert</div>
                                        <div class="text-xs text-slate-500">marie.lambert@company.com</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-600">marie.lambert@company.com</td>
                            <td class="px-4 py-4"><span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">Manager</span></td>
                            <td class="px-4 py-4"><span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">Actif</span></td>
                            <td class="px-4 py-4 text-sm text-slate-600">Il y a 5h</td>
                            <td class="px-4 py-4">
                                <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">Modifier</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">PD</div>
                                    <div>
                                        <div class="font-semibold text-slate-800">Pierre Dubois</div>
                                        <div class="text-xs text-slate-500">pierre.dubois@company.com</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-600">pierre.dubois@company.com</td>
                            <td class="px-4 py-4"><span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">Comptable</span></td>
                            <td class="px-4 py-4"><span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">Actif</span></td>
                            <td class="px-4 py-4 text-sm text-slate-600">Hier</td>
                            <td class="px-4 py-4">
                                <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">Modifier</button>
                            </td>
                        </tr>
                        <tr class="bg-amber-50">
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-amber-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">SL</div>
                                    <div>
                                        <div class="font-semibold text-slate-800">Sophie Laurent</div>
                                        <div class="text-xs text-slate-500">sophie.laurent@company.com</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-600">sophie.laurent@company.com</td>
                            <td class="px-4 py-4"><span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-semibold">Maintenance</span></td>
                            <td class="px-4 py-4"><span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-semibold">En attente</span></td>
                            <td class="px-4 py-4 text-sm text-slate-600">Jamais</td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    <button class="px-3 py-1.5 bg-emerald-500 text-white rounded-lg text-xs font-medium hover:bg-emerald-600 transition-colors">Activer</button>
                                    <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">Modifier</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pending Requests -->
        <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-slate-800">Demandes d'Accès en Attente</h3>
                <button class="px-4 py-2 bg-blue-500 text-white rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors shadow-md shadow-blue-500/20">Voir toutes</button>
            </div>
            <div class="space-y-3">
                <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">JD</div>
                    <div class="flex-1">
                        <div class="font-semibold text-slate-800">Jean Dupont</div>
                        <div class="text-sm text-slate-600">Demande le 15 Juin 2026 - Rôle: Manager</div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="px-3 py-1.5 bg-emerald-500 text-white rounded-lg text-xs font-medium hover:bg-emerald-600 transition-colors">Approuver</button>
                        <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-100 transition-colors">Rejeter</button>
                    </div>
                </div>

                <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <div class="w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">AR</div>
                    <div class="flex-1">
                        <div class="font-semibold text-slate-800">Antoine Roux</div>
                        <div class="text-sm text-slate-600">Demande le 14 Juin 2026 - Rôle: Comptable</div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="px-3 py-1.5 bg-emerald-500 text-white rounded-lg text-xs font-medium hover:bg-emerald-600 transition-colors">Approuver</button>
                        <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-100 transition-colors">Rejeter</button>
                    </div>
                </div>

                <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <div class="w-10 h-10 bg-pink-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">CB</div>
                    <div class="flex-1">
                        <div class="font-semibold text-slate-800">Claire Bernard</div>
                        <div class="text-sm text-slate-600">Demande le 13 Juin 2026 - Rôle: Réceptionniste</div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="px-3 py-1.5 bg-emerald-500 text-white rounded-lg text-xs font-medium hover:bg-emerald-600 transition-colors">Approuver</button>
                        <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-100 transition-colors">Rejeter</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
</script>
