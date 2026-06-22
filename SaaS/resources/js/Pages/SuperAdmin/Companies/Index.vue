<script setup>
import SuperAdminLayout from '../layouts/SuperAdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, computed, inject } from 'vue';

const props = defineProps({
    companies: {
        type: Array,
        default: () => [],
    },
});

// Inject active theme
const theme = inject('theme');

const searchQuery = ref('');
const statusFilter = ref('');
const planFilter = ref('');

const filteredCompanies = computed(() => {
    return props.companies.filter(c => {
        const matchesSearch = c.legal_name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                              c.city.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                              c.owner.email.toLowerCase().includes(searchQuery.value.toLowerCase());
        const matchesStatus = !statusFilter.value || c.verification_status === statusFilter.value;
        const matchesPlan = !planFilter.value || c.owner.subscription_plan === planFilter.value;

        return matchesSearch && matchesStatus && matchesPlan;
    });
});

// Summary counters
const totalCount = computed(() => props.companies.length);
const approvedCount = computed(() => props.companies.filter(c => c.verification_status === 'approved').length);
const pendingCount = computed(() => props.companies.filter(c => c.verification_status === 'pending' || !c.verification_status || c.verification_status === 'waiting').length);

const getInitials = (name) => {
    if (!name) return 'CO';
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

const getPlanBadgeClass = (plan) => {
    switch (plan?.toLowerCase()) {
        case 'enterprise': return 'bg-cyan-500/10 text-cyan-500 border border-cyan-500/25';
        case 'professional': return 'bg-indigo-500/10 text-indigo-500 border border-indigo-500/25';
        default: return 'bg-slate-500/10 text-slate-600 border border-slate-500/25';
    }
};

const getStatusBadgeClass = (status) => {
    switch (status?.toLowerCase()) {
        case 'approved': return 'bg-emerald-500/10 text-emerald-500 border border-emerald-500/25';
        case 'suspended': return 'bg-rose-500/10 text-rose-500 border border-rose-500/25';
        case 'rejected': return 'bg-red-500/10 text-red-500 border border-red-500/25';
        default: return 'bg-amber-500/10 text-amber-500 border border-amber-500/25';
    }
};

const updateVerificationStatus = (companyId, newStatus) => {
    router.post(route('superadmin.companies.verify', companyId), {
        status: newStatus
    }, {
        preserveScroll: true
    });
};

const updateSubscriptionPlan = (companyId, newPlan) => {
    router.post(route('superadmin.companies.plan', companyId), {
        plan: newPlan
    }, {
        preserveScroll: true
    });
};
</script>

<template>
    <Head title="Gestion des Entreprises" />

    <SuperAdminLayout>
        <div class="space-y-8 page-entrance">
            <!-- Header section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-black tracking-tight text-[var(--text-main)]">Parc des Entreprises</h2>
                    <p class="text-sm text-[var(--text-muted)] mt-1">Supervisez les comptes d'entreprises partenaires, modifiez les abonnements et gérez les agréments.</p>
                </div>
            </div>

            <!-- Mini Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Total -->
                <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-2xl p-5 shadow-[var(--card-shadow)] flex items-center gap-4 relative overflow-hidden group">
                    <div class="h-10 w-10 bg-indigo-500/10 rounded-xl flex items-center justify-center text-indigo-500 border border-indigo-500/15">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <div>
                        <span class="block text-[10px] text-[var(--text-muted)] uppercase tracking-wider font-extrabold">Total Entreprises</span>
                        <span class="text-xl font-black text-[var(--text-main)]">{{ totalCount }}</span>
                    </div>
                </div>

                <!-- Approved -->
                <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-2xl p-5 shadow-[var(--card-shadow)] flex items-center gap-4 relative overflow-hidden group">
                    <div class="h-10 w-10 bg-emerald-500/10 rounded-xl flex items-center justify-center text-emerald-500 border border-emerald-500/15">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div>
                        <span class="block text-[10px] text-[var(--text-muted)] uppercase tracking-wider font-extrabold">Structures Agréées</span>
                        <span class="text-xl font-black text-[var(--text-main)]">{{ approvedCount }}</span>
                    </div>
                </div>

                <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-2xl p-5 shadow-[var(--card-shadow)] flex items-center gap-4 relative overflow-hidden group">
                    <div class="h-10 w-10 bg-amber-500/10 rounded-xl flex items-center justify-center text-amber-600 border border-amber-500/15">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <div>
                        <span class="block text-[10px] text-[var(--text-muted)] uppercase tracking-wider font-extrabold">En Attente / Autres</span>
                        <span class="text-xl font-black text-[var(--text-main)]">{{ pendingCount }}</span>
                    </div>
                </div>
            </div>

            <!-- Filters Bar -->
            <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-3xl p-6 shadow-[var(--card-shadow)] flex flex-col md:flex-row items-center gap-4 justify-between">
                <!-- Search input -->
                <div class="relative w-full md:w-96">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input 
                        v-model="searchQuery"
                        type="text" 
                        placeholder="Rechercher par nom, ville ou e-mail..."
                        class="w-full pl-10 pr-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] placeholder-slate-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-all shadow-inner"
                    />
                </div>

                <!-- Filters -->
                <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                    <!-- Status Filter -->
                    <select 
                        v-model="statusFilter"
                        class="bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl px-4 py-3 text-xs text-[var(--text-muted)] outline-none focus:border-indigo-500 transition-all cursor-pointer shadow-sm font-semibold"
                    >
                        <option value="">Tous les agréments</option>
                        <option value="approved">Agréés uniquement</option>
                        <option value="pending">En attente d'avis</option>
                        <option value="suspended">Suspendus uniquement</option>
                    </select>

                    <!-- Plan Filter -->
                    <select 
                        v-model="planFilter"
                        class="bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl px-4 py-3 text-xs text-[var(--text-muted)] outline-none focus:border-indigo-500 transition-all cursor-pointer shadow-sm font-semibold"
                    >
                        <option value="">Tous les forfaits</option>
                        <option value="starter">Forfait Starter</option>
                        <option value="professional">Forfait Professional</option>
                        <option value="enterprise">Forfait Enterprise</option>
                    </select>
                </div>
            </div>

            <!-- Companies Table -->
            <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-3xl shadow-[var(--card-shadow)] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-[var(--border-color)] text-[10px] uppercase font-bold text-[var(--text-muted)] tracking-wider">
                                <th class="p-6">Entreprise</th>
                                <th class="p-6">Contact / Propriétaire</th>
                                <th class="p-6">Localisation</th>
                                <th class="p-6 text-center">Réseau</th>
                                <th class="p-6">Forfait Actif</th>
                                <th class="p-6">Agrément</th>
                                <th class="p-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--border-color)] text-xs">
                            <tr v-for="company in filteredCompanies" :key="company.id" class="hover:bg-[var(--bg-table-hover)] transition-colors">
                                <!-- Company details -->
                                <td class="p-6">
                                    <div class="flex items-center gap-3.5">
                                        <img 
                                            v-if="company.logo_url" 
                                            :src="company.logo_url" 
                                            class="h-11 w-11 rounded-2xl object-cover border border-[var(--border-color)] bg-[var(--bg-app)] p-1 shadow-inner shrink-0" 
                                            alt="Logo"
                                        />
                                        <div 
                                            v-else 
                                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br text-white text-xs font-black shadow-lg shadow-black/10"
                                            :class="getGradient(company.legal_name)"
                                        >
                                            {{ getInitials(company.legal_name) }}
                                        </div>
                                        <div>
                                            <p class="font-extrabold text-sm leading-normal text-[var(--text-main)]">{{ company.legal_name }}</p>
                                            <p class="text-[9px] text-indigo-500 uppercase tracking-widest font-black mt-1 bg-indigo-500/5 px-2 py-0.5 rounded border border-indigo-500/10 inline-block">{{ company.business_type }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Owner Details -->
                                <td class="p-6">
                                    <p class="font-bold leading-normal text-sm text-[var(--text-main)]">{{ company.owner.name }}</p>
                                    <a :href="`mailto:${company.owner.email}`" class="text-[10px] text-[var(--text-muted)] hover:text-indigo-600 transition-colors font-semibold flex items-center gap-1 mt-1">
                                        <i class="fa-regular fa-envelope"></i>
                                        <span>{{ company.owner.email }}</span>
                                    </a>
                                </td>

                                <!-- Location -->
                                <td class="p-6 font-semibold text-[var(--text-muted)]">
                                    <div class="flex items-center gap-1.5">
                                        <i class="fa-solid fa-location-dot text-indigo-500/60"></i>
                                        <span>{{ company.city }}, {{ company.country }}</span>
                                    </div>
                                </td>

                                <!-- Stats Count in network -->
                                <td class="p-6 text-center">
                                    <div class="inline-flex flex-col items-center bg-[var(--bg-input)] border border-[var(--border-color)] px-3 py-1.5 rounded-xl shadow-sm min-w-16">
                                        <span class="font-black text-sm text-[var(--text-main)]">{{ company.agencies_count || 0 }}</span>
                                        <span class="text-[8px] text-[var(--text-muted)] uppercase tracking-wider font-extrabold mt-0.5">Agences</span>
                                    </div>
                                </td>

                                <!-- Subscription Plan dropdown modification -->
                                <td class="p-6">
                                    <div class="relative inline-block text-left">
                                        <select 
                                            :value="company.owner.subscription_plan"
                                            @change="updateSubscriptionPlan(company.id, $event.target.value)"
                                            class="bg-[var(--bg-input)] border border-[var(--border-color)] hover:border-indigo-500/35 text-[var(--text-main)] rounded-xl px-3 py-2 text-[10px] font-extrabold outline-none cursor-pointer transition-all shadow-sm focus:ring-1 focus:ring-indigo-500"
                                        >
                                            <option value="starter">Starter Plan</option>
                                            <option value="professional">Professional Plan</option>
                                            <option value="enterprise">Enterprise Plan</option>
                                        </select>
                                    </div>
                                </td>

                                <!-- Status Badge -->
                                <td class="p-6">
                                    <span class="inline-block px-3 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wider" :class="getStatusBadgeClass(company.verification_status)">
                                        {{ 
                                            company.verification_status === 'approved' ? 'Agréé' : 
                                            company.verification_status === 'suspended' ? 'Suspendu' : 
                                            company.verification_status === 'rejected' ? 'Rejeté' : 'En attente' 
                                        }}
                                    </span>
                                </td>

                                <!-- Action Buttons -->
                                <td class="p-6 text-right space-x-2">
                                    <button 
                                        v-if="company.verification_status !== 'approved'"
                                        @click="updateVerificationStatus(company.id, 'approved')"
                                        class="px-3 py-2 bg-emerald-500/10 hover:bg-emerald-500 text-emerald-600 hover:text-white border border-emerald-500/20 rounded-xl font-black text-[10px] transition-all shadow-sm active:scale-95"
                                        title="Approuver l'agrément"
                                    >
                                        <i class="fa-solid fa-check mr-1.5"></i>
                                        <span>Agréer</span>
                                    </button>
                                    <button 
                                        v-if="company.verification_status !== 'suspended'"
                                        @click="updateVerificationStatus(company.id, 'suspended')"
                                        class="px-3 py-2 bg-rose-500/10 hover:bg-rose-500 text-rose-600 hover:text-white border border-rose-500/20 rounded-xl font-black text-[10px] transition-all shadow-sm active:scale-95"
                                        title="Suspendre l'entreprise"
                                    >
                                        <i class="fa-solid fa-ban mr-1.5"></i>
                                        <span>Suspendre</span>
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="filteredCompanies.length === 0">
                                <td colspan="7" class="p-12 text-center text-[var(--text-muted)] font-bold">Aucune entreprise trouvée dans les filtres actifs.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </SuperAdminLayout>
</template>
