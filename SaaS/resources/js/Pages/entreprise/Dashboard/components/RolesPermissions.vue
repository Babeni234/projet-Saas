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
                        <div class="text-3xl font-extrabold text-slate-800">{{ activeUsersCount }}</div>
                        <div class="text-sm text-slate-500 font-semibold mt-1">Utilisateurs Actifs</div>
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
                        <div class="text-3xl font-extrabold text-slate-800">{{ roles.length }}</div>
                        <div class="text-sm text-slate-500 font-semibold mt-1">Rôles Configurés</div>
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
                        <div class="text-3xl font-extrabold text-slate-800">{{ pendingUsers.length }}</div>
                        <div class="text-sm text-slate-500 font-semibold mt-1 font-sans">Demandes en Attente</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Roles Section -->
        <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">Rôles et Permissions</h3>
                    <p class="text-xs text-slate-500 mt-1">Définissez et configurez les droits d'accès des différents profils d'utilisateurs.</p>
                </div>
                <button
                    @click="openCreateRoleModal"
                    class="px-5 py-2.5 bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600 text-white rounded-xl text-sm font-bold hover:shadow-lg hover:shadow-blue-500/20 transition-all transform hover:scale-[1.02]"
                >
                    Ajouter un Rôle
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Dynamic Role Cards -->
                <div
                    v-for="role in roles"
                    :key="role.id"
                    :class="[
                        'p-5 rounded-2xl border transition-all duration-300 hover:shadow-md relative overflow-hidden bg-gradient-to-br flex flex-col min-h-[200px]',
                        getRoleTheme(role.slug).bg
                    ]"
                >
                    <div class="flex items-center justify-between mb-4">
                        <div :class="['w-10 h-10 rounded-xl flex items-center justify-center text-white shadow-sm', getRoleTheme(role.slug).iconBg]">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div :class="['px-2.5 py-1 text-white rounded-full text-[10px] font-extrabold uppercase tracking-wider', getRoleTheme(role.slug).iconBg]">
                            {{ role.slug }}
                        </div>
                    </div>
                    <div class="font-extrabold text-slate-800 text-lg mb-2">{{ role.name }}</div>
                    <div class="text-sm text-slate-600 mb-6 flex-1">{{ role.description || 'Pas de description fournie.' }}</div>
                    
                    <div class="flex items-center justify-between pt-3 border-t border-slate-200/50 mt-auto">
                        <span class="text-xs text-slate-500 font-bold">
                            {{ role.users_count || 0 }} {{ (role.users_count || 0) > 1 ? 'utilisateurs' : 'utilisateur' }}
                        </span>
                        <div class="flex gap-3">
                            <button
                                @click="openEditRoleModal(role)"
                                :class="['text-xs font-bold transition-colors', getRoleTheme(role.slug).text]"
                            >
                                Modifier
                            </button>
                            <button
                                v-if="role.slug !== 'admin'"
                                @click="deleteRole(role)"
                                class="text-xs font-bold text-rose-600 hover:text-rose-700 transition-colors"
                            >
                                Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">Membres et Affectation des Rôles</h3>
                    <p class="text-xs text-slate-500 mt-1">Associez vos collaborateurs aux rôles prédéfinis pour réguler leurs permissions d'accès.</p>
                </div>
                <button
                    @click="openCreateEmployeeModal"
                    class="px-5 py-2.5 bg-gradient-to-r from-emerald-500 via-emerald-600 to-teal-600 text-white rounded-xl text-sm font-bold hover:shadow-lg hover:shadow-emerald-500/20 transition-all transform hover:scale-[1.02] self-start sm:self-auto"
                >
                    Ajouter un Collaborateur
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Collaborateur</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Rôle Assigné</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Dernière Connexion</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="user in users" :key="user.id" class="hover:bg-slate-50/40 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div :class="['w-10 h-10 rounded-full flex items-center justify-center text-white font-extrabold text-sm bg-gradient-to-br', getRoleTheme(user.role?.slug).iconBg || 'from-blue-500 to-indigo-600']">
                                        {{ getUserInitials(user.name) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800">{{ user.name }}</div>
                                        <div class="text-xs text-slate-500 font-semibold mt-0.5" v-if="user.employee?.agency?.name || user.employee?.position">
                                            <span v-if="user.employee?.agency?.name" class="text-indigo-600 font-bold">{{ user.employee.agency.name }}</span>
                                            <span v-if="user.employee?.agency?.name && user.employee?.position" class="text-slate-300 mx-1">|</span>
                                            <span v-if="user.employee?.position" class="text-slate-500 font-medium">{{ user.employee.position }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 font-medium">{{ user.email }}</td>
                            <td class="px-6 py-4">
                                <select
                                    :value="user.role_id"
                                    @change="handleUserRoleChange(user, $event.target.value)"
                                    class="px-3 py-2 bg-white border-2 border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all cursor-pointer"
                                >
                                    <option :value="null">Aucun rôle</option>
                                    <option v-for="r in roles" :key="r.id" :value="r.id">
                                        {{ r.name }}
                                    </option>
                                </select>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    :class="[
                                        'px-3 py-1.5 rounded-full text-xs font-bold border shadow-sm transition-all',
                                        user.status === 'active'
                                            ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                                            : 'bg-slate-50 text-slate-600 border-slate-200'
                                    ]"
                                >
                                    {{ user.status === 'active' ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 font-medium">
                                {{ formatLastLogin(user.last_login_at) }}
                            </td>
                            <td class="px-6 py-4">
                                <button
                                    @click="toggleUserStatus(user)"
                                    :class="[
                                        'px-4 py-2 rounded-xl text-xs font-bold border transition-all shadow-sm transform hover:scale-105',
                                        user.status === 'active'
                                            ? 'bg-white border-slate-350 text-slate-700 hover:bg-slate-50'
                                            : 'bg-emerald-500 text-white border-transparent hover:bg-emerald-600'
                                    ]"
                                >
                                    {{ user.status === 'active' ? 'Désactiver' : 'Activer' }}
                                </button>
                            </td>
                        </tr>
                        <tr v-if="users.length === 0">
                            <td colspan="6" class="px-6 py-8 text-center text-slate-500 font-medium">
                                Aucun collaborateur enregistré pour le moment.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pending Requests -->
        <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
            <div class="mb-6">
                <h3 class="text-xl font-bold text-slate-900">Demandes d'Accès en Attente</h3>
                <p class="text-xs text-slate-500 mt-1">Gérez les demandes d'accès envoyées par les nouveaux membres rejoignant votre entreprise.</p>
            </div>
            
            <div class="space-y-4">
                <div
                    v-for="pUser in pendingUsers"
                    :key="pUser.id"
                    class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-5 bg-slate-50 rounded-2xl border border-slate-200/80"
                >
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-amber-500/10 text-amber-600 border border-amber-500/20 rounded-2xl flex items-center justify-center font-extrabold text-base">
                            {{ getUserInitials(pUser.name) }}
                        </div>
                        <div>
                            <div class="font-extrabold text-slate-800">{{ pUser.name }}</div>
                            <div class="text-sm text-slate-500 font-medium">Email: {{ pUser.email }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <button
                            @click="approveRequest(pUser)"
                            class="flex-1 sm:flex-none px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-xl text-xs font-bold hover:shadow-lg hover:shadow-emerald-500/20 transition-all transform hover:scale-105"
                        >
                            Approuver
                        </button>
                        <button
                            @click="rejectRequest(pUser)"
                            class="flex-1 sm:flex-none px-5 py-2.5 bg-white border-2 border-slate-300 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-50 transition-all transform hover:scale-105"
                        >
                            Rejeter
                        </button>
                    </div>
                </div>
                
                <div v-if="pendingUsers.length === 0" class="p-8 text-center bg-slate-50/50 rounded-2xl text-slate-400 border-2 border-dashed border-slate-200">
                    <svg class="w-10 h-10 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="font-semibold text-slate-500 text-sm">Aucune demande d'accès en attente.</p>
                </div>
            </div>
        </div>

        <!-- Types de Contrat Section -->
        <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">Types de Contrat de Location</h3>
                    <p class="text-xs text-slate-500 mt-1">Gérez les types de contrat (baux) applicables pour l'affectation de vos biens.</p>
                </div>
                <button
                    @click="openCreateTypeContratModal"
                    class="px-5 py-2.5 bg-gradient-to-r from-emerald-500 via-emerald-600 to-teal-600 text-white rounded-xl text-sm font-bold hover:shadow-lg hover:shadow-emerald-500/20 transition-all transform hover:scale-[1.02]"
                >
                    Ajouter un Type de Contrat
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="type in typeContrats"
                    :key="type.id"
                    class="p-5 rounded-2xl border border-slate-150 transition-all duration-300 hover:shadow-md relative overflow-hidden bg-slate-50/50 flex flex-col min-h-[160px]"
                >
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="font-extrabold text-slate-800 text-base mb-1">{{ type.nom }}</div>
                    <div class="text-xs text-slate-500 mb-4 flex-1">{{ type.description || 'Aucune description.' }}</div>
                    
                    <div class="flex justify-end gap-3 pt-3 border-t border-slate-200/50 mt-auto">
                        <button
                            @click="openEditTypeContratModal(type)"
                            class="text-xs font-bold text-emerald-600 hover:text-emerald-700 transition-colors"
                        >
                            Modifier
                        </button>
                        <button
                            @click="deleteTypeContrat(type)"
                            class="text-xs font-bold text-rose-600 hover:text-rose-700 transition-colors"
                        >
                            Supprimer
                        </button>
                    </div>
                </div>
                
                <div v-if="typeContrats.length === 0" class="col-span-full p-8 text-center bg-slate-50/50 rounded-2xl text-slate-400 border-2 border-dashed border-slate-200">
                    <svg class="w-10 h-10 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="font-semibold text-slate-500 text-sm">Aucun type de contrat configuré pour le moment.</p>
                </div>
            </div>
        </div>

        <!-- Categories Section -->
        <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">Catégories de Biens Immobiliers</h3>
                    <p class="text-xs text-slate-500 mt-1">Gérez les catégories utilisées pour classifier vos biens immobiliers.</p>
                </div>
                <button
                    @click="openCreateCategoryModal"
                    class="px-5 py-2.5 bg-gradient-to-r from-emerald-500 via-emerald-600 to-teal-600 text-white rounded-xl text-sm font-bold hover:shadow-lg hover:shadow-emerald-500/20 transition-all transform hover:scale-[1.02]"
                >
                    Ajouter une Catégorie
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="cat in categories"
                    :key="cat.id"
                    class="p-5 rounded-2xl border border-slate-150 transition-all duration-300 hover:shadow-md relative overflow-hidden bg-slate-50/50 flex flex-col min-h-[160px]"
                >
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                    </div>
                    <div class="font-extrabold text-slate-800 text-base mb-1">{{ cat.nom }}</div>
                    <div class="text-[10px] bg-indigo-50 text-indigo-750 font-bold px-2 py-0.5 rounded border border-indigo-200 self-start mb-2" v-if="cat.type_contrat">
                        Contrat : {{ cat.type_contrat.nom }}
                    </div>
                    <div class="text-xs text-slate-500 mb-4 flex-1">{{ cat.description || 'Aucune description.' }}</div>
                    
                    <div class="flex justify-end gap-3 pt-3 border-t border-slate-200/50 mt-auto">
                        <button
                            @click="openEditCategoryModal(cat)"
                            class="text-xs font-bold text-emerald-600 hover:text-emerald-700 transition-colors"
                        >
                            Modifier
                        </button>
                        <button
                            @click="deleteCategory(cat)"
                            class="text-xs font-bold text-rose-600 hover:text-rose-700 transition-colors"
                        >
                            Supprimer
                        </button>
                    </div>
                </div>
                
                <div v-if="categories.length === 0" class="col-span-full p-8 text-center bg-slate-50/50 rounded-2xl text-slate-400 border-2 border-dashed border-slate-200">
                    <svg class="w-10 h-10 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <p class="font-semibold text-slate-500 text-sm">Aucune catégorie configurée pour le moment.</p>
                </div>
            </div>
        </div>

        <!-- Types d'Engagement Section -->
        <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">Types d'Engagement</h3>
                    <p class="text-xs text-slate-500 mt-1">Gérez les types d'engagement (cautions, obligations de bail) applicables pour vos dossiers.</p>
                </div>
                <button
                    @click="openCreateTypeEngagementModal"
                    class="px-5 py-2.5 bg-gradient-to-r from-emerald-500 via-emerald-600 to-teal-600 text-white rounded-xl text-sm font-bold hover:shadow-lg hover:shadow-emerald-500/20 transition-all transform hover:scale-[1.02]"
                >
                    Ajouter un Type d'Engagement
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="type in typeEngagements"
                    :key="type.id"
                    class="p-5 rounded-2xl border border-slate-150 transition-all duration-300 hover:shadow-md relative overflow-hidden bg-slate-50/50 flex flex-col min-h-[160px]"
                >
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                    </div>
                    <div class="font-extrabold text-slate-800 text-base mb-1">{{ type.nom }}</div>
                    <div class="text-xs text-slate-500 mb-4 flex-1">{{ type.description || 'Aucune description.' }}</div>
                    
                    <div class="flex justify-end gap-3 pt-3 border-t border-slate-200/50 mt-auto">
                        <button
                            @click="openEditTypeEngagementModal(type)"
                            class="text-xs font-bold text-emerald-600 hover:text-emerald-700 transition-colors"
                        >
                            Modifier
                        </button>
                        <button
                            @click="deleteTypeEngagement(type)"
                            class="text-xs font-bold text-rose-600 hover:text-rose-700 transition-colors"
                        >
                            Supprimer
                        </button>
                    </div>
                </div>
                
                <div v-if="typeEngagements.length === 0" class="col-span-full p-8 text-center bg-slate-50/50 rounded-2xl text-slate-400 border-2 border-dashed border-slate-200">
                    <svg class="w-10 h-10 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="font-semibold text-slate-500 text-sm">Aucun type d'engagement configuré pour le moment.</p>
                </div>
            </div>
        </div>

        <!-- Types d'État des Lieux Section -->
        <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">Types d'État des Lieux</h3>
                    <p class="text-xs text-slate-500 mt-1">Configurez les différents types d'état des lieux (entrée, sortie, intermédiaire) pour vos logements.</p>
                </div>
                <button
                    @click="openCreateTypeEtatDesLieuxModal"
                    class="px-5 py-2.5 bg-gradient-to-r from-emerald-500 via-emerald-600 to-teal-600 text-white rounded-xl text-sm font-bold hover:shadow-lg hover:shadow-emerald-500/20 transition-all transform hover:scale-[1.02]"
                >
                    Ajouter un Type d'État des Lieux
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="type in typeEtatDesLieux"
                    :key="type.id"
                    class="p-5 rounded-2xl border border-slate-150 transition-all duration-300 hover:shadow-md relative overflow-hidden bg-slate-50/50 flex flex-col min-h-[160px]"
                >
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                    <div class="font-extrabold text-slate-800 text-base mb-1">{{ type.nom }}</div>
                    <div class="text-xs text-slate-500 mb-4 flex-1">{{ type.description || 'Aucune description.' }}</div>
                    
                    <div class="flex justify-end gap-3 pt-3 border-t border-slate-200/50 mt-auto">
                        <button
                            @click="openEditTypeEtatDesLieuxModal(type)"
                            class="text-xs font-bold text-emerald-600 hover:text-emerald-700 transition-colors"
                        >
                            Modifier
                        </button>
                        <button
                            @click="deleteTypeEtatDesLieux(type)"
                            class="text-xs font-bold text-rose-600 hover:text-rose-700 transition-colors"
                        >
                            Supprimer
                        </button>
                    </div>
                </div>
                
                <div v-if="typeEtatDesLieux.length === 0" class="col-span-full p-8 text-center bg-slate-50/50 rounded-2xl text-slate-400 border-2 border-dashed border-slate-200">
                    <svg class="w-10 h-10 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="font-semibold text-slate-500 text-sm">Aucun type d'état des lieux configuré pour le moment.</p>
                </div>
            </div>
        </div>

        <!-- Devise Configuration Section -->
        <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
            <div class="mb-6">
                <h3 class="text-xl font-bold text-slate-900">Configuration de la Devise</h3>
                <p class="text-xs text-slate-500 mt-1 font-medium">Définissez la devise monétaire principale utilisée pour la facturation et les rapports financiers.</p>
            </div>
            
            <div class="bg-slate-50/50 p-6 rounded-2xl border border-slate-150 max-w-xl">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Choisir la Devise Monétaire</label>
                        <div class="flex gap-4">
                            <select
                                v-model="selectedCurrency"
                                class="flex-1 px-5 py-3.5 bg-white border-2 border-slate-200 rounded-2xl text-slate-700 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all cursor-pointer"
                            >
                                <option value="" disabled>Sélectionner une devise</option>
                                <option v-for="opt in currencyOptions" :key="opt.label" :value="opt.label">
                                    {{ opt.label }}
                                </option>
                            </select>
                            
                            <button
                                @click="saveCurrency"
                                :disabled="!selectedCurrency || savingCurrency"
                                class="px-6 py-3.5 bg-gradient-to-r from-emerald-500 via-emerald-600 to-teal-600 text-white rounded-2xl text-sm font-bold hover:shadow-lg hover:shadow-emerald-500/20 transition-all transform hover:scale-[1.02] flex items-center justify-center gap-2"
                            >
                                <span v-if="savingCurrency" class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></span>
                                <span>Enregistrer</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Types de Factures Section -->
        <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">Types de Factures</h3>
                    <p class="text-xs text-slate-500 mt-1 font-medium">Définissez les différents types de factures (ex: Loyers, Charges, Travaux, Rénovations) utilisables par vos agences.</p>
                </div>
                <button
                    @click="openCreateTypeFactureModal"
                    class="px-5 py-2.5 bg-gradient-to-r from-emerald-500 via-emerald-600 to-teal-600 text-white rounded-xl text-sm font-bold hover:shadow-lg hover:shadow-emerald-500/20 transition-all transform hover:scale-[1.02]"
                >
                    Ajouter un Type de Facture
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="type in typeFactures"
                    :key="type.id"
                    class="p-5 rounded-2xl border border-slate-150 transition-all duration-300 hover:shadow-md relative overflow-hidden bg-slate-50/50 flex flex-col min-h-[160px]"
                >
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center text-violet-600 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="font-extrabold text-slate-800 text-base mb-1">{{ type.nom }}</div>
                    <div class="text-xs text-slate-500 mb-4 flex-1">{{ type.description || 'Aucune description.' }}</div>
                    
                    <div class="flex justify-end gap-3 pt-3 border-t border-slate-200/50 mt-auto">
                        <button
                            @click="openEditTypeFactureModal(type)"
                            class="text-xs font-bold text-emerald-600 hover:text-emerald-700 transition-colors"
                        >
                            Modifier
                        </button>
                        <button
                            @click="deleteTypeFacture(type)"
                            class="text-xs font-bold text-rose-600 hover:text-rose-700 transition-colors"
                        >
                            Supprimer
                        </button>
                    </div>
                </div>
                
                <div v-if="typeFactures.length === 0" class="col-span-full p-8 text-center bg-slate-50/50 rounded-2xl text-slate-400 border-2 border-dashed border-slate-200">
                    <svg class="w-10 h-10 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="font-semibold text-slate-500 text-sm">Aucun type de facture configuré pour le moment.</p>
                </div>
            </div>
        </div>

        <!-- Règles de gestion loyer / pénalités Section -->
        <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">Règles de gestion loyer & pénalités</h3>
                    <p class="text-xs text-slate-500 mt-1 font-medium">Définissez les jours de déclenchement et les taux de pénalités applicables en cas de retard de loyer.</p>
                </div>
                <button
                    @click="openCreateRegleLoyerModal"
                    class="px-5 py-2.5 bg-gradient-to-r from-emerald-500 via-emerald-600 to-teal-600 text-white rounded-xl text-sm font-bold hover:shadow-lg hover:shadow-emerald-500/20 transition-all transform hover:scale-[1.02]"
                >
                    Ajouter une Règle
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="rule in regleLoyers"
                    :key="rule.id"
                    class="p-5 rounded-2xl border border-slate-150 transition-all duration-300 hover:shadow-md relative overflow-hidden bg-slate-50/50 flex flex-col min-h-[140px]"
                >
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600 shadow-sm border border-amber-200">
                            <i class="fa-solid fa-clock-badge text-lg"></i>
                        </div>
                        <span 
                            :class="[
                                'text-[9px] font-extrabold uppercase px-2 py-0.5 rounded-full border',
                                rule.cycle === 'Trimestriel' 
                                    ? 'bg-amber-50 text-amber-700 border-amber-200'
                                    : rule.cycle === 'Annuel'
                                        ? 'bg-purple-50 text-purple-700 border-purple-200'
                                        : 'bg-blue-50 text-blue-700 border-blue-200'
                            ]"
                        >
                            {{ rule.cycle || 'Mensuel' }}
                        </span>
                    </div>
                    <div class="font-extrabold text-slate-800 text-base mb-1">
                        <span v-if="rule.cycle === 'Trimestriel'">Après {{ rule.jour_declenchement }} jours</span>
                        <span v-else-if="rule.cycle === 'Annuel'">Après {{ rule.jour_declenchement }} jours</span>
                        <span v-else>Le {{ rule.jour_declenchement }} du mois</span>
                    </div>
                    <div class="text-xs text-slate-500 mb-4 flex-1">Pénalité de retard appliquée : <strong class="text-red-500 font-extrabold text-sm">{{ rule.taux_penalite }}%</strong></div>
                    
                    <div class="flex justify-end gap-3 pt-3 border-t border-slate-200/50 mt-auto">
                        <button
                            @click="openEditRegleLoyerModal(rule)"
                            class="text-xs font-bold text-emerald-600 hover:text-emerald-700 transition-colors"
                        >
                            Modifier
                        </button>
                        <button
                            @click="deleteRegleLoyer(rule)"
                            class="text-xs font-bold text-rose-600 hover:text-rose-700 transition-colors"
                        >
                            Supprimer
                        </button>
                    </div>
                </div>
                
                <div v-if="regleLoyers.length === 0" class="col-span-full p-8 text-center bg-slate-50/50 rounded-2xl text-slate-400 border border-slate-200">
                    <i class="fa-solid fa-triangle-exclamation text-3xl mx-auto text-slate-300 mb-3 block"></i>
                    <p class="font-semibold text-slate-500 text-sm">Aucune règle de pénalité de loyer configurée pour le moment.</p>
                </div>
            </div>
        </div>

        <!-- Add/Edit Role Modal -->
        <ModalPremium
            :show="showRoleModal"
            :title="isEditing ? 'Modifier le Rôle' : 'Ajouter un Rôle'"
            :subtitle="isEditing ? 'Modifiez les informations et les permissions associées à ce rôle' : 'Créez un nouveau rôle personnalisé et attribuez-lui des permissions'"
            size="lg"
            type="default"
            @close="showRoleModal = false"
        >
            <form @submit.prevent="submitRoleForm" class="space-y-6">
                <!-- Basic Inputs -->
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nom du Rôle <span class="text-red-500">*</span></label>
                        <input
                            v-model="roleForm.name"
                            type="text"
                            required
                            placeholder="Ex: Chef de projet, Superviseur..."
                            class="w-full px-5 py-3.5 bg-slate-55 border-2 border-slate-200 rounded-2xl text-slate-700 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all"
                        />
                        <span v-if="errors.name" class="text-red-500 text-xs mt-1 block">{{ errors.name[0] }}</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Description</label>
                        <textarea
                            v-model="roleForm.description"
                            rows="3"
                            placeholder="Décrivez les responsabilités de ce rôle..."
                            class="w-full px-5 py-3.5 bg-slate-55 border-2 border-slate-200 rounded-2xl text-slate-700 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all"
                        ></textarea>
                        <span v-if="errors.description" class="text-red-500 text-xs mt-1 block">{{ errors.description[0] }}</span>
                    </div>
                </div>

                <!-- Permissions Selection -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Permissions Associées</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div
                            v-for="perm in availablePermissions"
                            :key="perm.id"
                            @click="togglePermission(perm.id)"
                            :class="[
                                'flex items-start gap-3.5 p-4 rounded-2xl border-2 transition-all cursor-pointer select-none',
                                roleForm.permissions.includes(perm.id)
                                    ? 'border-blue-500 bg-blue-50/20 shadow-sm'
                                    : 'border-slate-200 hover:border-slate-350 bg-slate-50/50 hover:bg-slate-50'
                            ]"
                        >
                            <input
                                type="checkbox"
                                :checked="roleForm.permissions.includes(perm.id)"
                                @click.stop
                                @change="togglePermission(perm.id)"
                                class="mt-1 w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer"
                            />
                            <div>
                                <span class="block text-sm font-bold text-slate-800">{{ perm.label }}</span>
                                <span class="block text-[11px] text-slate-500 mt-1 leading-relaxed">{{ perm.desc }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-4 justify-end pt-4 border-t border-slate-100">
                    <button
                        type="button"
                        @click="showRoleModal = false"
                        class="px-6 py-3.5 bg-white border-2 border-slate-300 text-slate-700 rounded-2xl text-sm font-bold hover:bg-slate-50 transition-all transform hover:scale-[1.02]"
                    >
                        Annuler
                    </button>
                    <button
                        type="submit"
                        :disabled="isSubmitting"
                        class="px-6 py-3.5 bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600 text-white rounded-2xl text-sm font-bold hover:shadow-lg hover:shadow-blue-500/20 transition-all transform hover:scale-[1.02] flex items-center justify-center gap-2"
                    >
                        <span v-if="isSubmitting" class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></span>
                        <span>{{ isEditing ? 'Mettre à Jour' : 'Créer le Rôle' }}</span>
                    </button>
                </div>
            </form>
        </ModalPremium>

        <!-- Add Employee Modal -->
        <ModalPremium
            :show="showEmployeeModal"
            title="Ajouter un Collaborateur"
            subtitle="Créez un nouvel utilisateur et son profil d'employé associé dans votre entreprise"
            size="lg"
            type="default"
            @close="showEmployeeModal = false"
        >
            <form @submit.prevent="submitEmployeeForm" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nom Complet <span class="text-red-500">*</span></label>
                        <input
                            v-model="employeeForm.name"
                            type="text"
                            required
                            placeholder="Ex: Jean Dupont"
                            class="w-full px-5 py-3.5 bg-slate-55 border-2 border-slate-200 rounded-2xl text-slate-700 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all"
                        />
                        <span v-if="employeeErrors.name" class="text-red-500 text-xs mt-1 block">{{ employeeErrors.name[0] }}</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Adresse Email <span class="text-red-500">*</span></label>
                        <input
                            v-model="employeeForm.email"
                            type="email"
                            required
                            placeholder="Ex: jean.dupont@entreprise.com"
                            class="w-full px-5 py-3.5 bg-slate-55 border-2 border-slate-200 rounded-2xl text-slate-700 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all"
                        />
                        <span v-if="employeeErrors.email" class="text-red-500 text-xs mt-1 block">{{ employeeErrors.email[0] }}</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Mot de Passe <span class="text-red-500">*</span></label>
                        <input
                            v-model="employeeForm.password"
                            type="password"
                            required
                            placeholder="Minimum 8 caractères"
                            class="w-full px-5 py-3.5 bg-slate-55 border-2 border-slate-200 rounded-2xl text-slate-700 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all"
                        />
                        <span v-if="employeeErrors.password" class="text-red-500 text-xs mt-1 block">{{ employeeErrors.password[0] }}</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Téléphone</label>
                        <input
                            v-model="employeeForm.phone"
                            type="text"
                            placeholder="Ex: +237 670 000 000"
                            class="w-full px-5 py-3.5 bg-slate-55 border-2 border-slate-200 rounded-2xl text-slate-700 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all"
                        />
                        <span v-if="employeeErrors.phone" class="text-red-500 text-xs mt-1 block">{{ employeeErrors.phone[0] }}</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Poste / Fonction</label>
                        <input
                            v-model="employeeForm.position"
                            type="text"
                            placeholder="Ex: Chef d'Agence, Comptable..."
                            class="w-full px-5 py-3.5 bg-slate-55 border-2 border-slate-200 rounded-2xl text-slate-700 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all"
                        />
                        <span v-if="employeeErrors.position" class="text-red-500 text-xs mt-1 block">{{ employeeErrors.position[0] }}</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Rôle <span class="text-red-500">*</span></label>
                        <select
                            v-model="employeeForm.role_id"
                            required
                            class="w-full px-5 py-3.5 bg-slate-55 border-2 border-slate-200 rounded-2xl text-slate-700 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all cursor-pointer"
                        >
                            <option value="" disabled>Sélectionner un rôle</option>
                            <option v-for="r in roles" :key="r.id" :value="r.id">{{ r.name }}</option>
                        </select>
                        <span v-if="employeeErrors.role_id" class="text-red-500 text-xs mt-1 block">{{ employeeErrors.role_id[0] }}</span>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Affectation à une Agence</label>
                        <select
                            v-model="employeeForm.agency_id"
                            class="w-full px-5 py-3.5 bg-slate-55 border-2 border-slate-200 rounded-2xl text-slate-700 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all cursor-pointer"
                        >
                            <option :value="null">Aucune agence (Siège / Non affecté)</option>
                            <option v-for="agency in agencies" :key="agency.id" :value="agency.id">{{ agency.name }}</option>
                        </select>
                        <span v-if="employeeErrors.agency_id" class="text-red-500 text-xs mt-1 block">{{ employeeErrors.agency_id[0] }}</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-4 justify-end pt-4 border-t border-slate-100">
                    <button
                        type="button"
                        @click="showEmployeeModal = false"
                        class="px-6 py-3.5 bg-white border-2 border-slate-300 text-slate-700 rounded-2xl text-sm font-bold hover:bg-slate-50 transition-all transform hover:scale-[1.02]"
                    >
                        Annuler
                    </button>
                    <button
                        type="submit"
                        :disabled="isEmployeeSubmitting"
                        class="px-6 py-3.5 bg-gradient-to-r from-emerald-500 via-emerald-600 to-teal-600 text-white rounded-2xl text-sm font-bold hover:shadow-lg hover:shadow-emerald-500/20 transition-all transform hover:scale-[1.02] flex items-center justify-center gap-2"
                    >
                        <span v-if="isEmployeeSubmitting" class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></span>
                        <span>Créer le Collaborateur</span>
                    </button>
                </div>
            </form>
        </ModalPremium>

        <!-- Add/Edit Category Modal -->
        <ModalPremium
            :show="showCategoryModal"
            :title="isEditingCategory ? 'Modifier la Catégorie' : 'Ajouter une Catégorie'"
            :subtitle="isEditingCategory ? 'Modifiez les détails de cette catégorie' : 'Créez une nouvelle catégorie pour classifier vos biens immobiliers'"
            size="md"
            type="default"
            @close="showCategoryModal = false"
        >
            <form @submit.prevent="submitCategoryForm" class="space-y-6">
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nom de la Catégorie <span class="text-red-500">*</span></label>
                        <input
                            v-model="categoryForm.nom"
                            type="text"
                            required
                            placeholder="Ex: Appartement, Bureau, Studio..."
                            class="w-full px-5 py-3.5 bg-slate-55 border-2 border-slate-200 rounded-2xl text-slate-700 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all"
                        />
                        <span v-if="categoryErrors.nom" class="text-red-500 text-xs mt-1 block">{{ categoryErrors.nom[0] }}</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Description</label>
                        <textarea
                            v-model="categoryForm.description"
                            rows="3"
                            placeholder="Décrivez cette catégorie..."
                            class="w-full px-5 py-3.5 bg-slate-55 border-2 border-slate-200 rounded-2xl text-slate-700 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all"
                        ></textarea>
                        <span v-if="categoryErrors.description" class="text-red-500 text-xs mt-1 block">{{ categoryErrors.description[0] }}</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Type de Contrat Associé <span class="text-red-500">*</span></label>
                        <select
                            v-model="categoryForm.type_contrat_id"
                            required
                            class="w-full px-5 py-3.5 bg-slate-55 border-2 border-slate-200 rounded-2xl text-slate-700 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all cursor-pointer"
                        >
                            <option value="" disabled>Sélectionner un type de contrat</option>
                            <option v-for="type in typeContrats" :key="type.id" :value="type.id">
                                {{ type.nom }}
                            </option>
                        </select>
                        <span v-if="categoryErrors.type_contrat_id" class="text-red-500 text-xs mt-1 block">{{ categoryErrors.type_contrat_id[0] }}</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-4 justify-end pt-4 border-t border-slate-100">
                    <button
                        type="button"
                        @click="showCategoryModal = false"
                        class="px-6 py-3.5 bg-white border-2 border-slate-300 text-slate-700 rounded-2xl text-sm font-bold hover:bg-slate-50 transition-all transform hover:scale-[1.02]"
                    >
                        Annuler
                    </button>
                    <button
                        type="submit"
                        :disabled="isCategorySubmitting"
                        class="px-6 py-3.5 bg-gradient-to-r from-emerald-500 via-emerald-600 to-teal-600 text-white rounded-2xl text-sm font-bold hover:shadow-lg hover:shadow-emerald-500/20 transition-all transform hover:scale-[1.02] flex items-center justify-center gap-2"
                    >
                        <span v-if="isCategorySubmitting" class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></span>
                        <span>{{ isEditingCategory ? 'Mettre à Jour' : 'Créer la Catégorie' }}</span>
                    </button>
                </div>
            </form>
        </ModalPremium>

        <!-- Add/Edit TypeContrat Modal -->
        <ModalPremium
            :show="showTypeContratModal"
            :title="isEditingTypeContrat ? 'Modifier le Type de Contrat' : 'Ajouter un Type de Contrat'"
            :subtitle="isEditingTypeContrat ? 'Modifiez les détails de ce type de contrat' : 'Créez un nouveau type de contrat pour vos locations'"
            size="md"
            type="default"
            @close="showTypeContratModal = false"
        >
            <form @submit.prevent="submitTypeContratForm" class="space-y-6">
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nom du Type de Contrat <span class="text-red-500">*</span></label>
                        <input
                            v-model="typeContratForm.nom"
                            type="text"
                            required
                            placeholder="Ex: Habitation, Commercial, Professionnel..."
                            class="w-full px-5 py-3.5 bg-slate-55 border-2 border-slate-200 rounded-2xl text-slate-700 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all"
                        />
                        <span v-if="typeContratErrors.nom" class="text-red-500 text-xs mt-1 block">{{ typeContratErrors.nom[0] }}</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Description</label>
                        <textarea
                            v-model="typeContratForm.description"
                            rows="3"
                            placeholder="Décrivez ce type de contrat..."
                            class="w-full px-5 py-3.5 bg-slate-55 border-2 border-slate-200 rounded-2xl text-slate-700 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all"
                        ></textarea>
                        <span v-if="typeContratErrors.description" class="text-red-500 text-xs mt-1 block">{{ typeContratErrors.description[0] }}</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-4 justify-end pt-4 border-t border-slate-100">
                    <button
                        type="button"
                        @click="showTypeContratModal = false"
                        class="px-6 py-3.5 bg-white border-2 border-slate-300 text-slate-700 rounded-2xl text-sm font-bold hover:bg-slate-50 transition-all transform hover:scale-[1.02]"
                    >
                        Annuler
                    </button>
                    <button
                        type="submit"
                        :disabled="isTypeContratSubmitting"
                        class="px-6 py-3.5 bg-gradient-to-r from-emerald-500 via-emerald-600 to-teal-600 text-white rounded-2xl text-sm font-bold hover:shadow-lg hover:shadow-emerald-500/20 transition-all transform hover:scale-[1.02] flex items-center justify-center gap-2"
                    >
                        <span v-if="isTypeContratSubmitting" class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></span>
                        <span>{{ isEditingTypeContrat ? 'Mettre à Jour' : 'Créer le Type' }}</span>
                    </button>
                </div>
            </form>
        </ModalPremium>

        <!-- Add/Edit TypeEngagement Modal -->
        <ModalPremium
            :show="showTypeEngagementModal"
            :title="isEditingTypeEngagement ? 'Modifier le Type d\'Engagement' : 'Ajouter un Type d\'Engagement'"
            :subtitle="isEditingTypeEngagement ? 'Modifiez les détails de ce type d\'engagement' : 'Créez un nouveau type d\'engagement pour vos baux'"
            size="md"
            type="default"
            @close="showTypeEngagementModal = false"
        >
            <form @submit.prevent="submitTypeEngagementForm" class="space-y-6">
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nom du Type d'Engagement <span class="text-red-500">*</span></label>
                        <input
                            v-model="typeEngagementForm.nom"
                            type="text"
                            required
                            placeholder="Ex: Caution solidaire, Garantie bancaire..."
                            class="w-full px-5 py-3.5 bg-slate-55 border-2 border-slate-200 rounded-2xl text-slate-700 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all"
                        />
                        <span v-if="typeEngagementErrors.nom" class="text-red-500 text-xs mt-1 block">{{ typeEngagementErrors.nom[0] }}</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Description</label>
                        <textarea
                            v-model="typeEngagementForm.description"
                            rows="3"
                            placeholder="Décrivez ce type d'engagement..."
                            class="w-full px-5 py-3.5 bg-slate-55 border-2 border-slate-200 rounded-2xl text-slate-700 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all"
                        ></textarea>
                        <span v-if="typeEngagementErrors.description" class="text-red-500 text-xs mt-1 block">{{ typeEngagementErrors.description[0] }}</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-4 justify-end pt-4 border-t border-slate-100">
                    <button
                        type="button"
                        @click="showTypeEngagementModal = false"
                        class="px-6 py-3.5 bg-white border-2 border-slate-300 text-slate-700 rounded-2xl text-sm font-bold hover:bg-slate-50 transition-all transform hover:scale-[1.02]"
                    >
                        Annuler
                    </button>
                    <button
                        type="submit"
                        :disabled="isTypeEngagementSubmitting"
                        class="px-6 py-3.5 bg-gradient-to-r from-emerald-500 via-emerald-600 to-teal-600 text-white rounded-2xl text-sm font-bold hover:shadow-lg hover:shadow-emerald-500/20 transition-all transform hover:scale-[1.02] flex items-center justify-center gap-2"
                    >
                        <span v-if="isTypeEngagementSubmitting" class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></span>
                        <span>{{ isEditingTypeEngagement ? 'Mettre à Jour' : 'Créer le Type' }}</span>
                    </button>
                </div>
            </form>
        </ModalPremium>

        <!-- Add/Edit TypeEtatDesLieux Modal -->
        <ModalPremium
            :show="showTypeEtatDesLieuxModal"
            :title="isEditingTypeEtatDesLieux ? 'Modifier le Type d\'État des Lieux' : 'Ajouter un Type d\'État des Lieux'"
            :subtitle="isEditingTypeEtatDesLieux ? 'Modifiez les détails de ce type d\'état des lieux' : 'Créez un nouveau type d\'état des lieux'"
            size="md"
            type="default"
            @close="showTypeEtatDesLieuxModal = false"
        >
            <form @submit.prevent="submitTypeEtatDesLieuxForm" class="space-y-6">
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nom du Type d'État des Lieux <span class="text-red-500">*</span></label>
                        <input
                            v-model="typeEtatDesLieuxForm.nom"
                            type="text"
                            required
                            placeholder="Ex: État des lieux d'entrée, de sortie..."
                            class="w-full px-5 py-3.5 bg-slate-55 border-2 border-slate-200 rounded-2xl text-slate-700 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all"
                        />
                        <span v-if="typeEtatDesLieuxErrors.nom" class="text-red-500 text-xs mt-1 block">{{ typeEtatDesLieuxErrors.nom[0] }}</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Description</label>
                        <textarea
                            v-model="typeEtatDesLieuxForm.description"
                            rows="3"
                            placeholder="Décrivez ce type d'état des lieux..."
                            class="w-full px-5 py-3.5 bg-slate-55 border-2 border-slate-200 rounded-2xl text-slate-700 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all"
                        ></textarea>
                        <span v-if="typeEtatDesLieuxErrors.description" class="text-red-500 text-xs mt-1 block">{{ typeEtatDesLieuxErrors.description[0] }}</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-4 justify-end pt-4 border-t border-slate-100">
                    <button
                        type="button"
                        @click="showTypeEtatDesLieuxModal = false"
                        class="px-6 py-3.5 bg-white border-2 border-slate-300 text-slate-700 rounded-2xl text-sm font-bold hover:bg-slate-50 transition-all transform hover:scale-[1.02]"
                    >
                        Annuler
                    </button>
                    <button
                        type="submit"
                        :disabled="isTypeEtatDesLieuxSubmitting"
                        class="px-6 py-3.5 bg-gradient-to-r from-emerald-500 via-emerald-600 to-teal-600 text-white rounded-2xl text-sm font-bold hover:shadow-lg hover:shadow-emerald-500/20 transition-all transform hover:scale-[1.02] flex items-center justify-center gap-2"
                    >
                        <span v-if="isTypeEtatDesLieuxSubmitting" class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></span>
                        <span>{{ isEditingTypeEtatDesLieux ? 'Mettre à Jour' : 'Créer le Type' }}</span>
                    </button>
                </div>
            </form>
        </ModalPremium>

        <!-- Add/Edit TypeFacture Modal -->
        <ModalPremium
            :show="showTypeFactureModal"
            :title="isEditingTypeFacture ? 'Modifier le Type de Facture' : 'Ajouter un Type de Facture'"
            :subtitle="isEditingTypeFacture ? 'Modifiez les détails de ce type de facture' : 'Créez un nouveau type de facture pour vos agences'"
            size="md"
            type="default"
            @close="showTypeFactureModal = false"
        >
            <form @submit.prevent="submitTypeFactureForm" class="space-y-6">
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nom du Type de Facture <span class="text-red-500">*</span></label>
                        <input
                            v-model="typeFactureForm.nom"
                            type="text"
                            required
                            placeholder="Ex: Loyer, Charges, Rénovation..."
                            class="w-full px-5 py-3.5 bg-slate-55 border-2 border-slate-200 rounded-2xl text-slate-700 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all"
                        />
                        <span v-if="typeFactureErrors.nom" class="text-red-500 text-xs mt-1 block">{{ typeFactureErrors.nom[0] }}</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Description</label>
                        <textarea
                            v-model="typeFactureForm.description"
                            rows="3"
                            placeholder="Décrivez ce type de facture..."
                            class="w-full px-5 py-3.5 bg-slate-55 border-2 border-slate-200 rounded-2xl text-slate-700 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all"
                        ></textarea>
                        <span v-if="typeFactureErrors.description" class="text-red-500 text-xs mt-1 block">{{ typeFactureErrors.description[0] }}</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-4 justify-end pt-4 border-t border-slate-100">
                    <button
                        type="button"
                        @click="showTypeFactureModal = false"
                        class="px-6 py-3.5 bg-white border-2 border-slate-300 text-slate-700 rounded-2xl text-sm font-bold hover:bg-slate-50 transition-all transform hover:scale-[1.02]"
                    >
                        Annuler
                    </button>
                    <button
                        type="submit"
                        :disabled="isTypeFactureSubmitting"
                        class="px-6 py-3.5 bg-gradient-to-r from-emerald-500 via-emerald-600 to-teal-600 text-white rounded-xl text-sm font-bold hover:shadow-lg hover:shadow-emerald-500/20 transition-all transform hover:scale-[1.02] flex items-center justify-center gap-2"
                    >
                        <span v-if="isTypeFactureSubmitting" class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></span>
                        <span>{{ isEditingTypeFacture ? 'Mettre à Jour' : 'Créer le Type' }}</span>
                    </button>
                </div>
            </form>
        </ModalPremium>

        <!-- Add/Edit RegleLoyer Modal -->
        <ModalPremium
            :show="showRegleLoyerModal"
            :title="isEditingRegleLoyer ? 'Modifier la règle de pénalité' : 'Ajouter une règle de pénalité'"
            @close="showRegleLoyerModal = false"
        >
            <form @submit.prevent="submitRegleLoyerForm" class="space-y-6">
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Cycle de Facturation <span class="text-red-500">*</span></label>
                        <select
                            v-model="regleLoyerForm.cycle"
                            required
                            class="w-full px-5 py-3.5 bg-slate-55 border-2 border-slate-200 rounded-2xl text-slate-705 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-semibold"
                        >
                            <option value="Mensuel">Mensuel</option>
                            <option value="Trimestriel">Trimestriel</option>
                            <option value="Annuel">Annuel</option>
                        </select>
                        <span v-if="regleLoyerErrors.cycle" class="text-red-500 text-xs mt-1 block">{{ regleLoyerErrors.cycle[0] }}</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                            <span v-if="regleLoyerForm.cycle === 'Mensuel'">Jour de déclenchement (du mois)</span>
                            <span v-else-if="regleLoyerForm.cycle === 'Trimestriel'">Jours de retard (depuis début trimestre)</span>
                            <span v-else>Jours de retard (depuis début année de bail)</span>
                            <span class="text-red-500"> *</span>
                        </label>
                        <input
                            v-model.number="regleLoyerForm.jour_declenchement"
                            type="number"
                            min="1"
                            :max="regleLoyerForm.cycle === 'Trimestriel' ? 90 : (regleLoyerForm.cycle === 'Annuel' ? 365 : 31)"
                            required
                            :placeholder="regleLoyerForm.cycle === 'Trimestriel' ? 'Ex: 15' : (regleLoyerForm.cycle === 'Annuel' ? 'Ex: 30' : 'Ex: 11')"
                            class="w-full px-5 py-3.5 bg-slate-55 border-2 border-slate-200 rounded-2xl text-slate-705 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-semibold"
                        />
                        <span v-if="regleLoyerErrors.jour_declenchement" class="text-red-500 text-xs mt-1 block">{{ regleLoyerErrors.jour_declenchement[0] }}</span>
                        <p class="text-[10px] text-slate-400 mt-1">
                            <span v-if="regleLoyerForm.cycle === 'Mensuel'">La pénalité s'appliquera automatiquement si le loyer du mois n'est pas réglé à cette date.</span>
                            <span v-else-if="regleLoyerForm.cycle === 'Trimestriel'">La pénalité s'appliquera si le trimestre de loyer est réglé après ce nombre de jours écoulés depuis le début du trimestre.</span>
                            <span v-else>La pénalité s'appliquera si l'année de loyer est réglée après ce nombre de jours écoulés depuis le début de l'année de bail.</span>
                        </p>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Taux de pénalité (%) <span class="text-red-500">*</span></label>
                        <input
                            v-model.number="regleLoyerForm.taux_penalite"
                            type="number"
                            step="0.01"
                            min="0"
                            max="100"
                            required
                            placeholder="Ex: 10"
                            class="w-full px-5 py-3.5 bg-slate-55 border-2 border-slate-200 rounded-2xl text-slate-705 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-semibold"
                        />
                        <span v-if="regleLoyerErrors.taux_penalite" class="text-red-500 text-xs mt-1 block">{{ regleLoyerErrors.taux_penalite[0] }}</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-4 justify-end pt-4 border-t border-slate-100">
                    <button
                        type="button"
                        @click="showRegleLoyerModal = false"
                        class="px-6 py-3.5 bg-white border-2 border-slate-300 text-slate-700 rounded-2xl text-sm font-bold hover:bg-slate-50 transition-all transform hover:scale-[1.02]"
                    >
                        Annuler
                    </button>
                    <button
                        type="submit"
                        :disabled="isRegleLoyerSubmitting"
                        class="px-6 py-3.5 bg-gradient-to-r from-emerald-500 via-emerald-600 to-teal-600 text-white rounded-xl text-sm font-bold hover:shadow-lg hover:shadow-emerald-500/20 transition-all transform hover:scale-[1.02] flex items-center justify-center gap-2"
                    >
                        <span v-if="isRegleLoyerSubmitting" class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></span>
                        <span>{{ isEditingRegleLoyer ? 'Mettre à Jour' : 'Créer la Règle' }}</span>
                    </button>
                </div>
            </form>
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
import { ref, watch, computed, onMounted } from 'vue';
import { usePage, router as inertiaRouter } from '@inertiajs/vue3';
import ModalPremium from '../../../../Components/ModalPremium.vue';
import NotificationPremium from '../../../../Components/NotificationPremium.vue';

const page = usePage();

const roles = ref(page.props.roles || []);
const users = ref(page.props.users || []);
const pendingUsers = ref(page.props.pendingUsers || []);

// Categories state
const categories = ref([]);
const showCategoryModal = ref(false);
const isEditingCategory = ref(false);
const editingCategoryId = ref(null);
const isCategorySubmitting = ref(false);
const categoryErrors = ref({});
const categoryForm = ref({
    nom: '',
    description: '',
    type_contrat_id: ''
});

// TypeContrats state
const typeContrats = ref([]);
const showTypeContratModal = ref(false);
const isEditingTypeContrat = ref(false);
const editingTypeContratId = ref(null);
const isTypeContratSubmitting = ref(false);
const typeContratErrors = ref({});
const typeContratForm = ref({
    nom: '',
    description: ''
});
const getAgenciesArray = (val) => {
    if (!val) return [];
    return Array.isArray(val) ? val : (val.data || []);
};
const agencies = ref(getAgenciesArray(page.props.agencies));

// TypeFactures state
const typeFactures = ref([]);
const showTypeFactureModal = ref(false);
const isEditingTypeFacture = ref(false);
const editingTypeFactureId = ref(null);
const isTypeFactureSubmitting = ref(false);
const typeFactureErrors = ref({});
const typeFactureForm = ref({
    nom: '',
    description: ''
});

// RegleLoyers state
const regleLoyers = ref([]);
const showRegleLoyerModal = ref(false);
const isEditingRegleLoyer = ref(false);
const editingRegleLoyerId = ref(null);
const isRegleLoyerSubmitting = ref(false);
const regleLoyerErrors = ref({});
const regleLoyerForm = ref({
    cycle: 'Mensuel',
    jour_declenchement: 11,
    taux_penalite: 10
});

// Modal & Form state
const showRoleModal = ref(false);
const isEditing = ref(false);
const editingRoleId = ref(null);
const isSubmitting = ref(false);
const errors = ref({});

const roleForm = ref({
    name: '',
    description: '',
    permissions: []
});

const showEmployeeModal = ref(false);
const isEmployeeSubmitting = ref(false);
const employeeErrors = ref({});
const employeeForm = ref({
    name: '',
    email: '',
    password: '',
    role_id: '',
    agency_id: null,
    position: '',
    phone: ''
});

const notification = ref({
    show: false,
    type: 'success',
    title: '',
    message: ''
});

// TypeEngagements state
const typeEngagements = ref([]);
const showTypeEngagementModal = ref(false);
const isEditingTypeEngagement = ref(false);
const editingTypeEngagementId = ref(null);
const isTypeEngagementSubmitting = ref(false);
const typeEngagementErrors = ref({});
const typeEngagementForm = ref({
    nom: '',
    description: ''
});

// TypeEtatDesLieux state
const typeEtatDesLieux = ref([]);
const showTypeEtatDesLieuxModal = ref(false);
const isEditingTypeEtatDesLieux = ref(false);
const editingTypeEtatDesLieuxId = ref(null);
const isTypeEtatDesLieuxSubmitting = ref(false);
const typeEtatDesLieuxErrors = ref({});
const typeEtatDesLieuxForm = ref({
    nom: '',
    description: ''
});

// Currency state
const selectedCurrency = ref('');
const savingCurrency = ref(false);

const currencyOptions = [
    { label: "France - Euro (€)", code: "EUR", symbole: "€" },
    { label: "États-Unis - Dollar ($)", code: "USD", symbole: "$" },
    { label: "Royaume-Uni - Livre Sterling (£)", code: "GBP", symbole: "£" },
    { label: "Cameroun - Franc CFA (FCFA)", code: "XAF", symbole: "FCFA" },
    { label: "Sénégal - Franc CFA (FCFA)", code: "XOF", symbole: "FCFA" },
    { label: "Côte d'Ivoire - Franc CFA (FCFA)", code: "XOF", symbole: "FCFA" },
    { label: "Gabon - Franc CFA (FCFA)", code: "XAF", symbole: "FCFA" },
    { label: "Congo - Franc CFA (FCFA)", code: "XAF", symbole: "FCFA" },
    { label: "Maroc - Dirham Marocain (DH)", code: "MAD", symbole: "DH" },
    { label: "Canada - Dollar Canadien (C$)", code: "CAD", symbole: "C$" },
    { label: "Suisse - Franc Suisse (CHF)", code: "CHF", symbole: "CHF" },
    { label: "Chine - Yuan (¥)", code: "CNY", symbole: "¥" },
    { label: "Japon - Yen (¥)", code: "JPY", symbole: "¥" },
];

// Available system permissions
const availablePermissions = [
    { id: 'manage_agencies', label: 'Gérer les agences', desc: 'Permet de créer, modifier et supprimer des agences' },
    { id: 'manage_properties', label: 'Gérer les biens immobiliers', desc: 'Permet de gérer les bâtiments, logements et contrats' },
    { id: 'manage_accounting', label: 'Gérer la comptabilité', desc: 'Accès complet aux factures et paiements' },
    { id: 'manage_maintenance', label: 'Gérer la maintenance', desc: 'Permet de gérer et attribuer des tickets de maintenance' },
    { id: 'manage_users', label: 'Gérer les utilisateurs', desc: 'Permet de gérer les utilisateurs, rôles et permissions' },
    { id: 'basic_access', label: 'Accès de base', desc: 'Accès limité en lecture aux fonctionnalités de base' },
];

const activeUsersCount = computed(() => {
    return users.value.filter(u => u.status === 'active').length;
});

// Watch for props updates
watch(
    () => page.props.roles,
    (val) => { if (val) roles.value = val; }
);

watch(
    () => page.props.users,
    (val) => { if (val) users.value = val; }
);

watch(
    () => page.props.pendingUsers,
    (val) => { if (val) pendingUsers.value = val; }
);

watch(
    () => page.props.agencies,
    (val) => { if (val) agencies.value = getAgenciesArray(val); }
);

onMounted(() => {
    if (!page.props.roles) {
        loadData();
    }
    fetchTypeContrats();
    fetchCategories();
    fetchTypeEngagements();
    fetchTypeEtatDesLieux();
    fetchCurrency();
    fetchTypeFactures();
    fetchRegleLoyers();
});

const loadData = () => {
    inertiaRouter.get(route('roles.index'), {}, {
        preserveState: true,
        preserveScroll: true,
        only: ['roles', 'users', 'pendingUsers', 'agencies'],
    });
};

const getRoleTheme = (slug) => {
    const themes = {
        admin: {
            bg: 'from-purple-50 to-purple-100/50 border-purple-200',
            badge: 'bg-purple-500 text-white',
            iconBg: 'bg-purple-500',
            text: 'text-purple-600 hover:text-purple-700',
            border: 'border-purple-200',
        },
        chef_agence: {
            bg: 'from-indigo-50 to-indigo-100/50 border-indigo-200',
            badge: 'bg-indigo-500 text-white',
            iconBg: 'bg-indigo-500',
            text: 'text-indigo-600 hover:text-indigo-700',
            border: 'border-indigo-200',
        },
        gestionnaire: {
            bg: 'from-blue-50 to-blue-100/50 border-blue-200',
            badge: 'bg-blue-500 text-white',
            iconBg: 'bg-blue-500',
            text: 'text-blue-600 hover:text-blue-700',
            border: 'border-blue-200',
        },
        comptable: {
            bg: 'from-emerald-50 to-emerald-100/50 border-emerald-200',
            badge: 'bg-emerald-500 text-white',
            iconBg: 'bg-emerald-500',
            text: 'text-emerald-600 hover:text-emerald-700',
            border: 'border-emerald-200',
        },
        maintenancier: {
            bg: 'from-amber-50 to-amber-100/50 border-amber-200',
            badge: 'bg-amber-500 text-white',
            iconBg: 'bg-amber-500',
            text: 'text-amber-600 hover:text-amber-700',
            border: 'border-amber-200',
        },
        employer_simple: {
            bg: 'from-slate-50 to-slate-100/50 border-slate-200',
            badge: 'bg-slate-500 text-white',
            iconBg: 'bg-slate-500',
            text: 'text-slate-600 hover:text-slate-700',
            border: 'border-slate-200',
        },
    };
    return themes[slug] || {
        bg: 'from-teal-50 to-teal-100/50 border-teal-200',
        badge: 'bg-teal-500 text-white',
        iconBg: 'bg-teal-500',
        text: 'text-teal-600 hover:text-teal-700',
        border: 'border-teal-200',
    };
};

const getUserInitials = (name) => {
    if (!name) return 'U';
    const parts = name.trim().split(' ');
    if (parts.length >= 2) {
        return (parts[0][0] + parts[1][0]).toUpperCase();
    }
    return name.substring(0, 2).toUpperCase();
};

const formatLastLogin = (date) => {
    if (!date) return 'Jamais';
    const diff = new Date() - new Date(date);
    const hours = Math.floor(diff / (1000 * 60 * 60));
    if (hours < 1) return 'Récemment';
    if (hours < 24) return `Il y a ${hours}h`;
    if (hours < 48) return 'Hier';
    return new Date(date).toLocaleDateString('fr-FR');
};

const openCreateRoleModal = () => {
    isEditing.value = false;
    editingRoleId.value = null;
    roleForm.value = {
        name: '',
        description: '',
        permissions: ['basic_access']
    };
    errors.value = {};
    showRoleModal.value = true;
};

const openEditRoleModal = (role) => {
    isEditing.value = true;
    editingRoleId.value = role.id;
    roleForm.value = {
        name: role.name,
        description: role.description,
        permissions: Array.isArray(role.permissions) ? [...role.permissions] : []
    };
    errors.value = {};
    showRoleModal.value = true;
};

const togglePermission = (id) => {
    const index = roleForm.value.permissions.indexOf(id);
    if (index > -1) {
        roleForm.value.permissions.splice(index, 1);
    } else {
        roleForm.value.permissions.push(id);
    }
};

const submitRoleForm = async () => {
    isSubmitting.value = true;
    errors.value = {};
    try {
        const url = isEditing.value
            ? route('roles.update', editingRoleId.value)
            : route('roles.store');

        const method = isEditing.value ? 'PUT' : 'POST';

        const response = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
            },
            body: JSON.stringify(roleForm.value),
        });

        if (response.ok) {
            showNotification(
                'success',
                'Succès',
                isEditing.value ? 'Le rôle a été mis à jour avec succès.' : 'Le rôle a été créé avec succès.'
            );
            showRoleModal.value = false;
            loadData();
        } else {
            const data = await response.json();
            if (response.status === 422 && data.errors) {
                errors.value = data.errors;
            } else {
                showNotification('error', 'Erreur', data.message || 'Une erreur est survenue.');
            }
        }
    } catch (error) {
        console.error(error);
        showNotification('error', 'Erreur', 'Impossible de contacter le serveur.');
    } finally {
        isSubmitting.value = false;
    }
};

const deleteRole = async (role) => {
    if (role.slug === 'admin') {
        showNotification('error', 'Erreur', 'Le rôle Admin ne peut pas être supprimé.');
        return;
    }

    if (!confirm(`Êtes-vous sûr de vouloir supprimer le rôle "${role.name}" ?`)) {
        return;
    }

    try {
        const response = await fetch(route('roles.destroy', role.id), {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
            }
        });

        if (response.ok) {
            showNotification('success', 'Succès', 'Le rôle a été supprimé avec succès.');
            loadData();
        } else {
            const data = await response.json();
            showNotification('error', 'Erreur', data.message || 'Une erreur est survenue.');
        }
    } catch (error) {
        console.error(error);
        showNotification('error', 'Erreur', 'Impossible de supprimer le rôle.');
    }
};

const handleUserRoleChange = async (user, roleId) => {
    try {
        const response = await fetch(route('users.update-role', user.id), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
            },
            body: JSON.stringify({ role_id: roleId ? parseInt(roleId) : null })
        });

        if (response.ok) {
            showNotification('success', 'Succès', `Le rôle de ${user.name} a été mis à jour.`);
            loadData();
        } else {
            const data = await response.json();
            showNotification('error', 'Erreur', data.message || 'Une erreur est survenue.');
        }
    } catch (error) {
        console.error(error);
        showNotification('error', 'Erreur', 'Impossible de modifier le rôle.');
    }
};

const toggleUserStatus = async (user) => {
    const newStatus = user.status === 'active' ? 'inactive' : 'active';
    await updateUserStatus(user, newStatus);
};

const approveRequest = async (user) => {
    await updateUserStatus(user, 'active');
};

const rejectRequest = async (user) => {
    await updateUserStatus(user, 'inactive');
};

const updateUserStatus = async (user, status) => {
    try {
        const response = await fetch(route('users.update-status', user.id), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
            },
            body: JSON.stringify({ status })
        });

        if (response.ok) {
            showNotification('success', 'Succès', `Le statut de ${user.name} a été mis à jour.`);
            loadData();
        } else {
            const data = await response.json();
            showNotification('error', 'Erreur', data.message || 'Une erreur est survenue.');
        }
    } catch (error) {
        console.error(error);
        showNotification('error', 'Erreur', 'Impossible de mettre à jour le statut.');
    }
};

const openCreateEmployeeModal = () => {
    employeeForm.value = {
        name: '',
        email: '',
        password: '',
        role_id: '',
        agency_id: null,
        position: '',
        phone: ''
    };
    employeeErrors.value = {};
    showEmployeeModal.value = true;
};

const submitEmployeeForm = async () => {
    isEmployeeSubmitting.value = true;
    employeeErrors.value = {};
    try {
        const response = await fetch(route('roles.store-employee'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
            },
            body: JSON.stringify(employeeForm.value),
        });

        if (response.ok) {
            showNotification(
                'success',
                'Succès',
                'Le collaborateur a été créé avec succès.'
            );
            showEmployeeModal.value = false;
            loadData();
        } else {
            const data = await response.json();
            if (response.status === 422 && data.errors) {
                employeeErrors.value = data.errors;
            } else {
                showNotification('error', 'Erreur', data.message || 'Une erreur est survenue.');
            }
        }
    } catch (error) {
        console.error(error);
        showNotification('error', 'Erreur', 'Impossible de contacter le serveur.');
    } finally {
        isEmployeeSubmitting.value = false;
    }
};

const fetchCategories = async () => {
    try {
        const response = await fetch('/api/categories', {
            headers: {
                'Accept': 'application/json',
            }
        });
        if (response.ok) {
            categories.value = await response.json();
        }
    } catch (error) {
        console.error("Erreur lors de la récupération des catégories:", error);
    }
};

const openCreateCategoryModal = () => {
    isEditingCategory.value = false;
    editingCategoryId.value = null;
    categoryForm.value = {
        nom: '',
        description: '',
        type_contrat_id: ''
    };
    categoryErrors.value = {};
    showCategoryModal.value = true;
};

const openEditCategoryModal = (cat) => {
    isEditingCategory.value = true;
    editingCategoryId.value = cat.id;
    categoryForm.value = {
        nom: cat.nom,
        description: cat.description || '',
        type_contrat_id: cat.type_contrat_id || ''
    };
    categoryErrors.value = {};
    showCategoryModal.value = true;
};

const fetchTypeContrats = async () => {
    try {
        const response = await fetch('/api/type-contrats', {
            headers: {
                'Accept': 'application/json',
            }
        });
        if (response.ok) {
            typeContrats.value = await response.json();
        }
    } catch (error) {
        console.error("Erreur lors de la récupération des types de contrat:", error);
    }
};

const openCreateTypeContratModal = () => {
    isEditingTypeContrat.value = false;
    editingTypeContratId.value = null;
    typeContratForm.value = {
        nom: '',
        description: ''
    };
    typeContratErrors.value = {};
    showTypeContratModal.value = true;
};

const openEditTypeContratModal = (type) => {
    isEditingTypeContrat.value = true;
    editingTypeContratId.value = type.id;
    typeContratForm.value = {
        nom: type.nom,
        description: type.description || ''
    };
    typeContratErrors.value = {};
    showTypeContratModal.value = true;
};

const submitTypeContratForm = async () => {
    isTypeContratSubmitting.value = true;
    typeContratErrors.value = {};
    try {
        const url = isEditingTypeContrat.value
            ? `/api/type-contrats/${editingTypeContratId.value}`
            : '/api/type-contrats';
        const method = isEditingTypeContrat.value ? 'PUT' : 'POST';

        const response = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
            },
            body: JSON.stringify(typeContratForm.value)
        });

        if (response.ok) {
            showNotification(
                'success',
                'Succès',
                isEditingTypeContrat.value ? 'Le type de contrat a été mis à jour avec succès.' : 'Le type de contrat a été créé avec succès.'
            );
            showTypeContratModal.value = false;
            fetchTypeContrats();
            fetchCategories(); // Refresh list to reflect potential name changes
        } else {
            const data = await response.json();
            if (response.status === 422 && data.errors) {
                typeContratErrors.value = data.errors;
            } else {
                showNotification('error', 'Erreur', data.message || 'Une erreur est survenue.');
            }
        }
    } catch (error) {
        console.error(error);
        showNotification('error', 'Erreur', 'Impossible de contacter le serveur.');
    } finally {
        isTypeContratSubmitting.value = false;
    }
};

const deleteTypeContrat = async (type) => {
    if (!confirm(`Êtes-vous sûr de vouloir supprimer le type de contrat "${type.nom}" ?`)) {
        return;
    }
    try {
        const response = await fetch(`/api/type-contrats/${type.id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
            }
        });

        if (response.ok) {
            showNotification('success', 'Succès', 'Le type de contrat a été supprimé avec succès.');
            fetchTypeContrats();
            fetchCategories();
        } else {
            const data = await response.json();
            showNotification('error', 'Erreur', data.message || 'Une erreur est survenue.');
        }
    } catch (error) {
        console.error(error);
        showNotification('error', 'Erreur', 'Impossible de supprimer le type de contrat.');
    }
};

const submitCategoryForm = async () => {
    isCategorySubmitting.value = true;
    categoryErrors.value = {};
    try {
        const url = isEditingCategory.value
            ? `/api/categories/${editingCategoryId.value}`
            : '/api/categories';
        const method = isEditingCategory.value ? 'PUT' : 'POST';

        const response = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
            },
            body: JSON.stringify(categoryForm.value)
        });

        if (response.ok) {
            showNotification(
                'success',
                'Succès',
                isEditingCategory.value ? 'La catégorie a été mise à jour avec succès.' : 'La catégorie a été créée avec succès.'
            );
            showCategoryModal.value = false;
            fetchCategories();
        } else {
            const data = await response.json();
            if (response.status === 422 && data.errors) {
                categoryErrors.value = data.errors;
            } else {
                showNotification('error', 'Erreur', data.message || 'Une erreur est survenue.');
            }
        }
    } catch (error) {
        console.error(error);
        showNotification('error', 'Erreur', 'Impossible de contacter le serveur.');
    } finally {
        isCategorySubmitting.value = false;
    }
};

const deleteCategory = async (cat) => {
    if (!confirm(`Êtes-vous sûr de vouloir supprimer la catégorie "${cat.nom}" ?`)) {
        return;
    }
    try {
        const response = await fetch(`/api/categories/${cat.id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
            }
        });

        if (response.ok) {
            showNotification('success', 'Succès', 'La catégorie a été supprimée avec succès.');
            fetchCategories();
        } else {
            const data = await response.json();
            showNotification('error', 'Erreur', data.message || 'Une erreur est survenue.');
        }
    } catch (error) {
        console.error(error);
        showNotification('error', 'Erreur', 'Impossible de supprimer la catégorie.');
    }
};

// TypeEngagement CRUD methods
const fetchTypeEngagements = async () => {
    try {
        const response = await fetch('/api/type-engagements', {
            headers: { 'Accept': 'application/json' }
        });
        if (response.ok) {
            typeEngagements.value = await response.json();
        }
    } catch (error) {
        console.error("Erreur récupération engagements:", error);
    }
};

const openCreateTypeEngagementModal = () => {
    isEditingTypeEngagement.value = false;
    editingTypeEngagementId.value = null;
    typeEngagementForm.value = { nom: '', description: '' };
    typeEngagementErrors.value = {};
    showTypeEngagementModal.value = true;
};

const openEditTypeEngagementModal = (type) => {
    isEditingTypeEngagement.value = true;
    editingTypeEngagementId.value = type.id;
    typeEngagementForm.value = {
        nom: type.nom,
        description: type.description || ''
    };
    typeEngagementErrors.value = {};
    showTypeEngagementModal.value = true;
};

const submitTypeEngagementForm = async () => {
    isTypeEngagementSubmitting.value = true;
    typeEngagementErrors.value = {};
    try {
        const url = isEditingTypeEngagement.value
            ? `/api/type-engagements/${editingTypeEngagementId.value}`
            : '/api/type-engagements';
        const method = isEditingTypeEngagement.value ? 'PUT' : 'POST';

        const response = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
            },
            body: JSON.stringify(typeEngagementForm.value)
        });

        if (response.ok) {
            showNotification(
                'success',
                'Succès',
                isEditingTypeEngagement.value ? 'Le type d\'engagement a été mis à jour.' : 'Le type d\'engagement a été créé.'
            );
            showTypeEngagementModal.value = false;
            fetchTypeEngagements();
        } else {
            const data = await response.json();
            if (response.status === 422 && data.errors) {
                typeEngagementErrors.value = data.errors;
            } else {
                showNotification('error', 'Erreur', data.message || 'Une erreur est survenue.');
            }
        }
    } catch (error) {
        console.error(error);
        showNotification('error', 'Erreur', 'Impossible de contacter le serveur.');
    } finally {
        isTypeEngagementSubmitting.value = false;
    }
};

const deleteTypeEngagement = async (type) => {
    if (!confirm(`Êtes-vous sûr de vouloir supprimer le type d'engagement "${type.nom}" ?`)) return;
    try {
        const response = await fetch(`/api/type-engagements/${type.id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
            }
        });
        if (response.ok) {
            showNotification('success', 'Succès', 'Le type d\'engagement a été supprimé.');
            fetchTypeEngagements();
        } else {
            const data = await response.json();
            showNotification('error', 'Erreur', data.message || 'Une erreur est survenue.');
        }
    } catch (error) {
        console.error(error);
        showNotification('error', 'Erreur', 'Impossible de supprimer le type d\'engagement.');
    }
};

// TypeEtatDesLieux CRUD methods
const fetchTypeEtatDesLieux = async () => {
    try {
        const response = await fetch('/api/type-etat-des-lieux', {
            headers: { 'Accept': 'application/json' }
        });
        if (response.ok) {
            typeEtatDesLieux.value = await response.json();
        }
    } catch (error) {
        console.error("Erreur récupération états des lieux:", error);
    }
};

const openCreateTypeEtatDesLieuxModal = () => {
    isEditingTypeEtatDesLieux.value = false;
    editingTypeEtatDesLieuxId.value = null;
    typeEtatDesLieuxForm.value = { nom: '', description: '' };
    typeEtatDesLieuxErrors.value = {};
    showTypeEtatDesLieuxModal.value = true;
};

const openEditTypeEtatDesLieuxModal = (type) => {
    isEditingTypeEtatDesLieux.value = true;
    editingTypeEtatDesLieuxId.value = type.id;
    typeEtatDesLieuxForm.value = {
        nom: type.nom,
        description: type.description || ''
    };
    typeEtatDesLieuxErrors.value = {};
    showTypeEtatDesLieuxModal.value = true;
};

const submitTypeEtatDesLieuxForm = async () => {
    isTypeEtatDesLieuxSubmitting.value = true;
    typeEtatDesLieuxErrors.value = {};
    try {
        const url = isEditingTypeEtatDesLieux.value
            ? `/api/type-etat-des-lieux/${editingTypeEtatDesLieuxId.value}`
            : '/api/type-etat-des-lieux';
        const method = isEditingTypeEtatDesLieux.value ? 'PUT' : 'POST';

        const response = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
            },
            body: JSON.stringify(typeEtatDesLieuxForm.value)
        });

        if (response.ok) {
            showNotification(
                'success',
                'Succès',
                isEditingTypeEtatDesLieux.value ? 'Le type d\'état des lieux a été mis à jour.' : 'Le type d\'état des lieux a été créé.'
            );
            showTypeEtatDesLieuxModal.value = false;
            fetchTypeEtatDesLieux();
        } else {
            const data = await response.json();
            if (response.status === 422 && data.errors) {
                typeEtatDesLieuxErrors.value = data.errors;
            } else {
                showNotification('error', 'Erreur', data.message || 'Une erreur est survenue.');
            }
        }
    } catch (error) {
        console.error(error);
        showNotification('error', 'Erreur', 'Impossible de contacter le serveur.');
    } finally {
        isTypeEtatDesLieuxSubmitting.value = false;
    }
};

const deleteTypeEtatDesLieux = async (type) => {
    if (!confirm(`Êtes-vous sûr de vouloir supprimer le type d'état des lieux "${type.nom}" ?`)) return;
    try {
        const response = await fetch(`/api/type-etat-des-lieux/${type.id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
            }
        });
        if (response.ok) {
            showNotification('success', 'Succès', 'Le type d\'état des lieux a été supprimé.');
            fetchTypeEtatDesLieux();
        } else {
            const data = await response.json();
            showNotification('error', 'Erreur', data.message || 'Une erreur est survenue.');
        }
    } catch (error) {
        console.error(error);
        showNotification('error', 'Erreur', 'Impossible de supprimer le type d\'état des lieux.');
    }
};

// TypeFacture CRUD methods
const fetchTypeFactures = async () => {
    try {
        const response = await fetch('/api/type-factures', {
            headers: { 'Accept': 'application/json' }
        });
        if (response.ok) {
            typeFactures.value = await response.json();
        }
    } catch (error) {
        console.error("Erreur récupération type factures:", error);
    }
};

const openCreateTypeFactureModal = () => {
    isEditingTypeFacture.value = false;
    editingTypeFactureId.value = null;
    typeFactureForm.value = { nom: '', description: '' };
    typeFactureErrors.value = {};
    showTypeFactureModal.value = true;
};

const openEditTypeFactureModal = (type) => {
    isEditingTypeFacture.value = true;
    editingTypeFactureId.value = type.id;
    typeFactureForm.value = {
        nom: type.nom,
        description: type.description || ''
    };
    typeFactureErrors.value = {};
    showTypeFactureModal.value = true;
};

const submitTypeFactureForm = async () => {
    isTypeFactureSubmitting.value = true;
    typeFactureErrors.value = {};
    try {
        const url = isEditingTypeFacture.value
            ? `/api/type-factures/${editingTypeFactureId.value}`
            : '/api/type-factures';
        const method = isEditingTypeFacture.value ? 'PUT' : 'POST';

        const response = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
            },
            body: JSON.stringify(typeFactureForm.value)
        });

        if (response.ok) {
            showNotification(
                'success',
                'Succès',
                isEditingTypeFacture.value ? 'Le type de facture a été mis à jour.' : 'Le type de facture a été créé.'
            );
            showTypeFactureModal.value = false;
            fetchTypeFactures();
        } else {
            const data = await response.json();
            if (response.status === 422 && data.errors) {
                typeFactureErrors.value = data.errors;
            } else {
                showNotification('error', 'Erreur', data.message || 'Une erreur est survenue.');
            }
        }
    } catch (error) {
        console.error(error);
        showNotification('error', 'Erreur', 'Impossible de contacter le serveur.');
    } finally {
        isTypeFactureSubmitting.value = false;
    }
};

const deleteTypeFacture = async (type) => {
    if (!confirm(`Êtes-vous sûr de vouloir supprimer le type de facture "${type.nom}" ?`)) return;
    try {
        const response = await fetch(`/api/type-factures/${type.id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
            }
        });
        if (response.ok) {
            showNotification('success', 'Succès', 'Le type de facture a été supprimé.');
            fetchTypeFactures();
        } else {
            const data = await response.json();
            showNotification('error', 'Erreur', data.message || 'Une erreur est survenue.');
        }
    } catch (error) {
        console.error(error);
        showNotification('error', 'Erreur', 'Impossible de supprimer le type de facture.');
    }
};

// Currency Config methods
const fetchCurrency = async () => {
    try {
        const response = await fetch('/api/devise', {
            headers: { 'Accept': 'application/json' }
        });
        if (response.ok) {
            const data = await response.json();
            if (data && data.devise) {
                selectedCurrency.value = data.devise;
            }
        }
    } catch (error) {
        console.error("Erreur récupération devise:", error);
    }
};

const saveCurrency = async () => {
    if (!selectedCurrency.value) return;
    savingCurrency.value = true;
    try {
        const option = currencyOptions.find(o => o.label === selectedCurrency.value);
        if (!option) return;

        const response = await fetch('/api/devise', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
            },
            body: JSON.stringify({
                devise: option.label,
                code: option.code,
                symbole: option.symbole
            })
        });

        if (response.ok) {
            showNotification('success', 'Succès', 'La devise de l\'entreprise a été configurée avec succès.');
        } else {
            const data = await response.json();
            showNotification('error', 'Erreur', data.message || 'Une erreur est survenue.');
        }
    } catch (error) {
        console.error(error);
        showNotification('error', 'Erreur', 'Impossible de sauvegarder la devise.');
    } finally {
        savingCurrency.value = false;
    }
};

// RegleLoyers CRUD methods
const fetchRegleLoyers = async () => {
    try {
        const response = await fetch('/api/regle-loyers', {
            headers: { 'Accept': 'application/json' }
        });
        if (response.ok) {
            regleLoyers.value = await response.json();
        }
    } catch (error) {
        console.error("Erreur récupération règles de loyer:", error);
    }
};

const openCreateRegleLoyerModal = () => {
    isEditingRegleLoyer.value = false;
    editingRegleLoyerId.value = null;
    regleLoyerForm.value = {
        cycle: 'Mensuel',
        jour_declenchement: 11,
        taux_penalite: 10
    };
    regleLoyerErrors.value = {};
    showRegleLoyerModal.value = true;
};

const openEditRegleLoyerModal = (rule) => {
    isEditingRegleLoyer.value = true;
    editingRegleLoyerId.value = rule.id;
    regleLoyerForm.value = {
        cycle: rule.cycle || 'Mensuel',
        jour_declenchement: rule.jour_declenchement,
        taux_penalite: rule.taux_penalite
    };
    regleLoyerErrors.value = {};
    showRegleLoyerModal.value = true;
};

const submitRegleLoyerForm = async () => {
    isRegleLoyerSubmitting.value = true;
    regleLoyerErrors.value = {};
    try {
        const url = isEditingRegleLoyer.value
            ? `/api/regle-loyers/${editingRegleLoyerId.value}`
            : '/api/regle-loyers';
        const method = isEditingRegleLoyer.value ? 'PUT' : 'POST';

        const response = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
            },
            body: JSON.stringify(regleLoyerForm.value)
        });

        if (response.ok) {
            showNotification(
                'success',
                'Succès',
                isEditingRegleLoyer.value 
                    ? 'La règle de pénalité a été mise à jour.' 
                    : 'La règle de pénalité a été créée.'
            );
            showRegleLoyerModal.value = false;
            fetchRegleLoyers();
        } else {
            const data = await response.json();
            if (response.status === 422) {
                if (data.errors) {
                    regleLoyerErrors.value = data.errors;
                } else {
                    showNotification('error', 'Erreur', data.message || 'Jour de déclenchement invalide.');
                }
            } else {
                showNotification('error', 'Erreur', data.message || 'Une erreur est survenue.');
            }
        }
    } catch (error) {
        console.error(error);
        showNotification('error', 'Erreur', 'Impossible de contacter le serveur.');
    } finally {
        isRegleLoyerSubmitting.value = false;
    }
};

const deleteRegleLoyer = async (rule) => {
    if (!confirm(`Etes-vous sûr de vouloir supprimer cette règle pour le ${rule.jour_declenchement} du mois ?`)) return;
    try {
        const response = await fetch(`/api/regle-loyers/${rule.id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
            }
        });
        if (response.ok) {
            showNotification('success', 'Succès', 'La règle de pénalité a été supprimée.');
            fetchRegleLoyers();
        } else {
            const data = await response.json();
            showNotification('error', 'Erreur', data.message || 'Une erreur est survenue.');
        }
    } catch (error) {
        console.error(error);
        showNotification('error', 'Erreur', 'Impossible de supprimer la règle.');
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
</script>
