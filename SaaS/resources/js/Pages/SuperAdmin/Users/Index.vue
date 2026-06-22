<script setup>
import SuperAdminLayout from '../layouts/SuperAdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, computed, inject } from 'vue';

const props = defineProps({
    users: {
        type: Array,
        default: () => [],
    },
});

// Inject active theme
const theme = inject('theme');

const searchQuery = ref('');
const roleFilter = ref('');

const filteredUsers = computed(() => {
    return props.users.filter(u => {
        const matchesSearch = u.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                              u.email.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                              u.company_name.toLowerCase().includes(searchQuery.value.toLowerCase());
        const matchesRole = !roleFilter.value || u.account_type === roleFilter.value;

        return matchesSearch && matchesRole;
    });
});

// Summary counters
const totalCount = computed(() => props.users.length);
const activeCount = computed(() => props.users.filter(u => u.status === 'active').length);
const onlineCount = computed(() => props.users.filter(u => u.is_connected).length);

const getInitials = (name) => {
    if (!name) return 'US';
    return name.split(' ').map(n => n[0]).join('').slice(0, 2).toUpperCase();
};

const getGradient = (name) => {
    if (!name) return 'from-indigo-500 to-cyan-500';
    let hash = 0;
    for (let i = 0; i < name.length; i++) {
        hash = name.charCodeAt(i) + ((hash << 5) - hash);
    }
    const colors = [
        'from-blue-600 to-indigo-600',
        'from-purple-600 to-pink-600',
        'from-emerald-500 to-teal-600',
        'from-amber-500 to-orange-600',
        'from-rose-500 to-red-600',
        'from-cyan-500 to-blue-600'
    ];
    const index = Math.abs(hash) % colors.length;
    return colors[index];
};

const getRoleBadgeClass = (role) => {
    switch (role?.toLowerCase()) {
        case 'company': return 'bg-indigo-500/10 text-indigo-600 dark:text-indigo-500 border border-indigo-500/20';
        case 'locataire': return 'bg-amber-500/10 text-amber-600 dark:text-amber-500 border border-amber-500/20';
        default: return 'bg-sky-500/10 text-sky-600 dark:text-sky-500 border border-sky-500/20';
    }
};

const getRoleLabel = (role) => {
    switch (role?.toLowerCase()) {
        case 'company': return 'Entreprise (Owner)';
        case 'locataire': return 'Locataire';
        default: return 'Individuel / Employé';
    }
};

const getStatusBadgeClass = (status) => {
    switch (status?.toLowerCase()) {
        case 'active': return 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-500 border border-emerald-500/25';
        case 'suspended': return 'bg-rose-500/10 text-rose-600 dark:text-rose-500 border border-rose-500/25';
        default: return 'bg-slate-500/10 text-[var(--text-muted)] border border-[var(--border-color)]';
    }
};

const showConfirmModal = ref(false);
const userToDelete = ref(null);

const confirmDeleteUser = (user) => {
    userToDelete.value = user;
    showConfirmModal.value = true;
};

const performDeleteUser = () => {
    if (!userToDelete.value) return;
    router.delete(route('superadmin.users.delete', userToDelete.value.id), {
        onSuccess: () => {
            showConfirmModal.value = false;
            userToDelete.value = null;
        }
    });
};

const toggleUserStatus = (user) => {
    const newStatus = user.status === 'active' ? 'suspended' : 'active';
    router.post(route('superadmin.users.status', user.id), {
        status: newStatus
    }, {
        preserveScroll: true
    });
};
</script>

<template>
    <Head title="Gestion des Utilisateurs" />

    <SuperAdminLayout>
        <div class="space-y-8 page-entrance">
            <!-- Header section -->
            <div>
                <h2 class="text-2xl font-black tracking-tight text-[var(--text-main)]">Répertoire des Utilisateurs</h2>
                <p class="text-sm text-[var(--text-muted)] mt-1">Gérez tous les comptes d'utilisateurs (locataires, gérants et employés) enregistrés sur la plateforme.</p>
            </div>

            <!-- Mini Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Total -->
                <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-2xl p-5 shadow-[var(--card-shadow)] flex items-center gap-4 relative overflow-hidden group">
                    <div class="h-10 w-10 bg-indigo-500/10 rounded-xl flex items-center justify-center text-indigo-500 border border-indigo-500/15">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>
                        <span class="block text-[10px] text-[var(--text-muted)] uppercase tracking-wider font-extrabold">Utilisateurs Enregistrés</span>
                        <span class="text-xl font-black text-[var(--text-main)]">{{ totalCount }}</span>
                    </div>
                </div>

                <!-- Active -->
                <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-2xl p-5 shadow-[var(--card-shadow)] flex items-center gap-4 relative overflow-hidden group">
                    <div class="h-10 w-10 bg-emerald-500/10 rounded-xl flex items-center justify-center text-emerald-500 border border-emerald-500/15">
                        <i class="fa-solid fa-user-check"></i>
                    </div>
                    <div>
                        <span class="block text-[10px] text-[var(--text-muted)] uppercase tracking-wider font-extrabold">Comptes Actifs</span>
                        <span class="text-xl font-black text-[var(--text-main)]">{{ activeCount }}</span>
                    </div>
                </div>

                <!-- Sessions en ligne -->
                <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-2xl p-5 shadow-[var(--card-shadow)] flex items-center gap-4 relative overflow-hidden group">
                    <div class="h-10 w-10 bg-indigo-500/10 rounded-xl flex items-center justify-center text-indigo-600 border border-indigo-500/15">
                        <i class="fa-solid fa-signal text-sm animate-pulse"></i>
                    </div>
                    <div>
                        <span class="block text-[10px] text-[var(--text-muted)] uppercase tracking-wider font-extrabold">En Session Active</span>
                        <span class="text-xl font-black text-[var(--text-main)]">{{ onlineCount }}</span>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Bar -->
            <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-3xl p-6 shadow-[var(--card-shadow)] flex flex-col lg:flex-row items-center gap-6 justify-between">
                <!-- Search input -->
                <div class="relative w-full lg:w-96">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input 
                        v-model="searchQuery"
                        type="text" 
                        placeholder="Rechercher par nom, e-mail ou structure..."
                        class="w-full pl-10 pr-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] placeholder-slate-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-all shadow-inner"
                    />
                </div>

                <!-- Role Filter Pills -->
                <div class="flex flex-wrap gap-2 w-full lg:w-auto">
                    <button 
                        @click="roleFilter = ''" 
                        class="px-4 py-2 rounded-xl text-xs font-bold border transition-all active:scale-95 shadow-sm"
                        :class="[
                            roleFilter === '' 
                                ? 'bg-indigo-600 text-white border-indigo-600' 
                                : 'bg-[var(--bg-input)] text-[var(--text-muted)] border-[var(--border-color)] hover:text-[var(--text-main)]'
                        ]"
                    >
                        Tous
                    </button>
                    <button 
                        @click="roleFilter = 'company'" 
                        class="px-4 py-2 rounded-xl text-xs font-bold border transition-all active:scale-95 shadow-sm"
                        :class="[
                            roleFilter === 'company' 
                                ? 'bg-indigo-600 text-white border-indigo-600' 
                                : 'bg-[var(--bg-input)] text-[var(--text-muted)] border-[var(--border-color)] hover:text-[var(--text-main)]'
                        ]"
                    >
                        Entreprises (Owners)
                    </button>
                    <button 
                        @click="roleFilter = 'individual'" 
                        class="px-4 py-2 rounded-xl text-xs font-bold border transition-all active:scale-95 shadow-sm"
                        :class="[
                            roleFilter === 'individual' 
                                ? 'bg-indigo-600 text-white border-indigo-600' 
                                : 'bg-[var(--bg-input)] text-[var(--text-muted)] border-[var(--border-color)] hover:text-[var(--text-main)]'
                        ]"
                    >
                        Employés
                    </button>
                    <button 
                        @click="roleFilter = 'Locataire'" 
                        class="px-4 py-2 rounded-xl text-xs font-bold border transition-all active:scale-95 shadow-sm"
                        :class="[
                            roleFilter === 'Locataire' 
                                ? 'bg-indigo-600 text-white border-indigo-600' 
                                : 'bg-[var(--bg-input)] text-[var(--text-muted)] border-[var(--border-color)] hover:text-[var(--text-main)]'
                        ]"
                    >
                        Locataires
                    </button>
                </div>
            </div>

            <!-- Users Table -->
            <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-3xl shadow-[var(--card-shadow)] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-[var(--border-color)] text-[10px] uppercase font-bold text-[var(--text-muted)] tracking-wider">
                                <th class="p-6">Utilisateur</th>
                                <th class="p-6">Type de compte</th>
                                <th class="p-6">Structure Rattachée</th>
                                <th class="p-6 text-center">Session</th>
                                <th class="p-6">Statut</th>
                                <th class="p-6">Inscrit le</th>
                                <th class="p-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--border-color)] text-xs">
                            <tr v-for="user in filteredUsers" :key="user.id" class="hover:bg-[var(--bg-table-hover)] transition-colors">
                                <!-- User Identity -->
                                <td class="p-6">
                                    <div class="flex items-center gap-3.5">
                                        <div 
                                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br text-white text-xs font-black shadow-lg shadow-black/10"
                                            :class="getGradient(user.name)"
                                        >
                                            {{ getInitials(user.name) }}
                                        </div>
                                        <div>
                                            <p class="font-extrabold text-sm leading-normal text-[var(--text-main)]">{{ user.name }}</p>
                                            <a :href="`mailto:${user.email}`" class="text-[10px] text-[var(--text-muted)] hover:text-indigo-600 transition-colors font-semibold leading-none mt-1.5 block">
                                                <i class="fa-regular fa-envelope mr-1"></i>{{ user.email }}
                                            </a>
                                        </div>
                                    </div>
                                </td>

                                <!-- Account Type -->
                                <td class="p-6">
                                    <span class="inline-block px-3 py-0.5 rounded-full border font-extrabold text-[9px] uppercase tracking-wider" :class="getRoleBadgeClass(user.account_type)">
                                        {{ getRoleLabel(user.account_type) }}
                                    </span>
                                </td>

                                <!-- Associated Structure -->
                                <td class="p-6 text-[var(--text-muted)] font-extrabold">
                                    <div class="flex items-center gap-1.5">
                                        <i class="fa-solid fa-briefcase text-indigo-500/40"></i>
                                        <span>{{ user.company_name }}</span>
                                    </div>
                                </td>

                                <!-- Online Status -->
                                <td class="p-6 text-center">
                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[9px] font-extrabold uppercase tracking-wider"
                                          :class="user.is_connected ? 'text-emerald-500 bg-emerald-500/5 border border-emerald-500/15' : 'text-[var(--text-muted)] bg-slate-500/5 border border-[var(--border-color)]'">
                                        <span class="w-1.5 h-1.5 rounded-full" :class="user.is_connected ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400'"></span>
                                        {{ user.is_connected ? 'En ligne' : 'Hors ligne' }}
                                    </span>
                                </td>

                                <!-- Active Status -->
                                <td class="p-6">
                                    <span class="inline-block px-3 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wider" :class="getStatusBadgeClass(user.status)">
                                        {{ user.status === 'active' ? 'Actif' : 'Suspendu' }}
                                    </span>
                                </td>

                                <!-- Registered date -->
                                <td class="p-6 text-[var(--text-muted)] font-bold">
                                    {{ user.created_at }}
                                </td>

                                <!-- Actions -->
                                <td class="p-6 text-right space-x-2">
                                    <!-- Suspend/Reactivate button -->
                                    <button 
                                        @click="toggleUserStatus(user)"
                                        class="px-3 py-2 border rounded-xl font-extrabold text-[10px] transition-all shadow-sm active:scale-95"
                                        :class="user.status === 'active' 
                                            ? 'bg-rose-500/10 hover:bg-rose-600 border-rose-500/20 text-rose-600 hover:text-white' 
                                            : 'bg-emerald-500/10 hover:bg-emerald-600 border-emerald-500/20 text-emerald-600 hover:text-white'"
                                        :title="user.status === 'active' ? 'Suspendre le compte' : 'Réactiver le compte'"
                                    >
                                        <i class="fa-solid mr-1" :class="user.status === 'active' ? 'fa-user-slash' : 'fa-user-check'"></i>
                                        <span>{{ user.status === 'active' ? 'Suspendre' : 'Activer' }}</span>
                                    </button>

                                    <!-- Delete button -->
                                    <button 
                                        @click="confirmDeleteUser(user)"
                                        class="px-3 py-2 bg-red-500/10 hover:bg-red-500 text-red-600 hover:text-white border border-red-500/20 rounded-xl font-extrabold text-[10px] transition-all shadow-sm active:scale-95"
                                        title="Supprimer définitivement"
                                    >
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="filteredUsers.length === 0">
                                <td colspan="7" class="p-12 text-center text-[var(--text-muted)] font-bold">Aucun utilisateur ne correspond aux filtres.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Safe deletion confirm modal -->
            <Transition name="fade">
                <div v-if="showConfirmModal && userToDelete" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/75 backdrop-blur-sm" @click.self="showConfirmModal = false">
                    <div class="bg-[var(--bg-sidebar)] border border-red-500/20 shadow-2xl max-w-md w-full overflow-hidden p-6 text-center animate-scale-up rounded-3xl">
                        <div class="w-14 h-14 bg-red-500/10 text-red-500 border border-red-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                        </div>
                        <h4 class="text-lg font-black mb-2" :class="theme === 'light' ? 'text-slate-900' : 'text-white'">Confirmer la suppression</h4>
                        <p class="text-xs text-[var(--text-muted)] leading-relaxed mb-6">
                            Êtes-vous sûr de vouloir supprimer définitivement le compte de <strong :class="theme === 'light' ? 'text-slate-800' : 'text-slate-200'">{{ userToDelete.name }}</strong> ({{ userToDelete.email }}) ? Cette action est irréversible et pourrait impacter d'autres données rattachées.
                        </p>
                        <div class="flex gap-3">
                            <button 
                                class="flex-1 py-3 bg-[var(--bg-btn-secondary)] hover:bg-slate-400/20 text-[var(--text-muted)] font-bold rounded-2xl text-xs transition-colors" 
                                @click="showConfirmModal = false"
                            >
                                Annuler
                            </button>
                            <button 
                                class="flex-1 py-3 bg-red-600 hover:bg-red-500 text-white font-bold rounded-2xl text-xs transition-colors shadow-lg shadow-red-600/15" 
                                @click="performDeleteUser"
                            >
                                Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </div>
    </SuperAdminLayout>
</template>

<style scoped>
/* Page-specific animation is loaded via .page-entrance class in layout */

.fade-enter-active, .fade-leave-active {
    transition: opacity 0.2s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
</style>
