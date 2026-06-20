<script setup>
import SuperAdminLayout from './layouts/SuperAdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, inject } from 'vue';

const props = defineProps({
    stats: {
        type: Object,
        required: true,
    },
    plansDistribution: {
        type: Object,
        required: true,
    },
    recentCompanies: {
        type: Array,
        default: () => [],
    },
    recentUsers: {
        type: Array,
        default: () => [],
    },
    geoData: {
        type: Array,
        default: () => [],
    },
});

// Inject the active theme state with a dark mode fallback
const theme = inject('theme', ref('dark'));
const hoveredCity = ref(null);

const getPlanBadgeClass = (plan) => {
    switch (plan?.toLowerCase()) {
        case 'enterprise': return 'bg-cyan-500/10 text-cyan-500 border border-cyan-500/25';
        case 'professional': return 'bg-indigo-500/10 text-indigo-500 border border-indigo-500/25';
        default: return 'bg-slate-500/10 text-slate-500 border border-slate-500/25';
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

const getCityCoords = (city) => {
    switch (city?.toLowerCase()) {
        case 'paris': return { x: 50, y: 35 };
        case 'marseille': return { x: 70, y: 78 };
        case 'lyon': return { x: 68, y: 58 };
        case 'cannes': return { x: 77, y: 80 };
        case 'bordeaux': return { x: 34, y: 68 };
        default: return { x: 50, y: 50 };
    }
};
</script>

<template>
    <Head title="Administration Dashboard" />

    <SuperAdminLayout>
        <div class="space-y-8 animate-scale-up">
            <!-- 1. Header Page Title -->
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-black tracking-tight" :class="theme === 'light' ? 'text-stone-900' : 'text-white'">Panneau de Contrôle Global</h2>
                    <p class="text-sm text-[var(--text-muted)] mt-1">Gérez le parc applicatif, surveillez l'activité générale et suivez les souscriptions en temps réel.</p>
                </div>
            </div>

            <!-- 2. Stats Grid with Left Accent Strips & Prominent Alignments -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Companies Card -->
                <div 
                    class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-3xl p-6 shadow-[var(--card-shadow)] hover:-translate-y-1 hover:shadow-indigo-500/10 hover:border-indigo-500/30 transition-all duration-300 relative group overflow-hidden"
                >
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-gradient-to-b from-indigo-500 to-violet-650"></div>
                    <div class="flex items-center justify-between">
                        <div class="space-y-1.5">
                            <span class="text-xs font-bold text-[var(--text-muted)] uppercase tracking-wider">Entreprises</span>
                            <p class="text-4xl font-black tracking-tight" :class="theme === 'light' ? 'text-indigo-600' : 'text-white'">{{ stats.total_companies }}</p>
                        </div>
                        <div class="h-12 w-12 bg-indigo-500/10 text-indigo-550 border border-indigo-500/25 rounded-2xl flex items-center justify-center shadow-sm group-hover:scale-105 duration-300">
                            <i class="fa-solid fa-building text-base"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-[var(--border-color)] flex items-center justify-between text-[10px] text-[var(--text-muted-darker)] font-bold">
                        <span>Actives sur la plateforme</span>
                        <span class="text-indigo-550 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                            Live
                        </span>
                    </div>
                </div>

                <!-- Users Card -->
                <div 
                    class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-3xl p-6 shadow-[var(--card-shadow)] hover:-translate-y-1 hover:shadow-cyan-500/10 hover:border-cyan-500/30 transition-all duration-300 relative group overflow-hidden"
                >
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-gradient-to-b from-cyan-400 to-blue-500"></div>
                    <div class="flex items-center justify-between">
                        <div class="space-y-1.5">
                            <span class="text-xs font-bold text-[var(--text-muted)] uppercase tracking-wider">Utilisateurs</span>
                            <p class="text-4xl font-black tracking-tight" :class="theme === 'light' ? 'text-cyan-600' : 'text-white'">{{ stats.total_users }}</p>
                        </div>
                        <div class="h-12 w-12 bg-cyan-500/10 text-cyan-550 border border-cyan-500/25 rounded-2xl flex items-center justify-center shadow-sm group-hover:scale-105 duration-300">
                            <i class="fa-solid fa-users text-base"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-[var(--border-color)] flex items-center justify-between text-[10px] text-[var(--text-muted-darker)] font-bold">
                        <span>Comptes globaux créés</span>
                        <span class="text-cyan-550 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-500 animate-pulse"></span>
                            Actifs
                        </span>
                    </div>
                </div>

                <!-- Agencies Card -->
                <div 
                    class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-3xl p-6 shadow-[var(--card-shadow)] hover:-translate-y-1 hover:shadow-emerald-500/10 hover:border-emerald-500/30 transition-all duration-300 relative group overflow-hidden"
                >
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-gradient-to-b from-emerald-400 to-teal-550"></div>
                    <div class="flex items-center justify-between">
                        <div class="space-y-1.5">
                            <span class="text-xs font-bold text-[var(--text-muted)] uppercase tracking-wider">Agences</span>
                            <p class="text-4xl font-black tracking-tight" :class="theme === 'light' ? 'text-emerald-650' : 'text-white'">{{ stats.total_agencies }}</p>
                        </div>
                        <div class="h-12 w-12 bg-emerald-500/10 text-emerald-550 border border-emerald-500/25 rounded-2xl flex items-center justify-center shadow-sm group-hover:scale-105 duration-300">
                            <i class="fa-solid fa-house-chimney-window text-base"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-[var(--border-color)] flex items-center justify-between text-[10px] text-[var(--text-muted-darker)] font-bold">
                        <span>Bureaux enregistrés</span>
                        <span class="text-emerald-550 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-550 animate-pulse"></span>
                            En ligne
                        </span>
                    </div>
                </div>

                <!-- Tenants Card -->
                <div 
                    class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-3xl p-6 shadow-[var(--card-shadow)] hover:-translate-y-1 hover:shadow-amber-500/10 hover:border-amber-500/30 transition-all duration-300 relative group overflow-hidden"
                >
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-gradient-to-b from-amber-400 to-orange-550"></div>
                    <div class="flex items-center justify-between">
                        <div class="space-y-1.5">
                            <span class="text-xs font-bold text-[var(--text-muted)] uppercase tracking-wider">Locataires</span>
                            <p class="text-4xl font-black tracking-tight" :class="theme === 'light' ? 'text-amber-650' : 'text-white'">{{ stats.total_tenants }}</p>
                        </div>
                        <div class="h-12 w-12 bg-amber-500/10 text-amber-550 border border-amber-500/25 rounded-2xl flex items-center justify-center shadow-sm group-hover:scale-105 duration-300">
                            <i class="fa-solid fa-people-roof text-base"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-[var(--border-color)] flex items-center justify-between text-[10px] text-[var(--text-muted-darker)] font-bold">
                        <span>Logements loués : {{ stats.total_logements || 0 }}</span>
                        <span class="text-amber-550 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                            Résidents
                        </span>
                    </div>
                </div>
            </div>

            <!-- 3. Maps and Subscription Distribution Row -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Map Widget -->
                <div class="lg:col-span-2 bg-[var(--bg-card)] border border-[var(--border-color)] rounded-3xl p-6 shadow-[var(--card-shadow)] flex flex-col justify-between min-h-[440px]">
                    <div>
                        <h3 class="text-lg font-black tracking-tight" :class="theme === 'light' ? 'text-stone-900' : 'text-white'">Répartition Géographique</h3>
                        <p class="text-xs text-[var(--text-muted)] mt-0.5 font-semibold">Distribution géographique des entreprises partenaires enregistrées.</p>
                    </div>

                    <!-- France SVG Map and details widget split -->
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 my-4 items-center">
                        <!-- SVG Map (col-span-3) -->
                        <div class="md:col-span-3 relative bg-slate-500/5 dark:bg-slate-950/20 rounded-2xl border border-[var(--border-color)] flex items-center justify-center overflow-hidden h-72 shadow-inner">
                            <!-- Abstract Grid Overlay -->
                            <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.015]" style="background-image: radial-gradient(circle at 1px 1px, currentColor 1px, transparent 0); background-size: 16px 16px"></div>
                            
                            <!-- France Silhouette SVG -->
                            <svg viewBox="0 0 100 100" class="w-64 h-64 transition-all duration-300">
                                <!-- Radar decorative rings -->
                                <circle cx="50" cy="50" r="35" class="stroke-indigo-500/10 dark:stroke-indigo-550/15 fill-none" stroke-width="0.3" stroke-dasharray="1,4" />
                                <circle cx="50" cy="50" r="22" class="stroke-indigo-500/10 dark:stroke-indigo-550/15 fill-none" stroke-width="0.3" stroke-dasharray="1,4" />
                                <circle cx="50" cy="50" r="10" class="stroke-indigo-500/10 dark:stroke-indigo-550/15 fill-none" stroke-width="0.3" stroke-dasharray="1,4" />
                                <!-- Diagonal coordinates -->
                                <line x1="15" y1="15" x2="85" y2="85" class="stroke-indigo-500/5 dark:stroke-indigo-550/5" stroke-width="0.2" stroke-dasharray="2,2" />
                                <line x1="85" y1="15" x2="15" y2="85" class="stroke-indigo-500/5 dark:stroke-indigo-550/5" stroke-width="0.2" stroke-dasharray="2,2" />

                                <polygon 
                                    points="50,13 78,18 88,45 82,75 55,92 25,85 13,60 22,35 35,18" 
                                    class="transition-all duration-300"
                                    :class="theme === 'light' ? 'fill-indigo-100/60 stroke-indigo-400' : 'fill-indigo-950/30 stroke-indigo-500/60'"
                                    stroke-width="0.8"
                                />

                                <!-- Glowing Radar city markers INSIDE the SVG coordinates -->
                                <g 
                                    v-for="city in geoData" 
                                    :key="city.city" 
                                    class="cursor-pointer group/node"
                                    @mouseenter="hoveredCity = city"
                                    @mouseleave="hoveredCity = null"
                                >
                                    <!-- Sonar ring -->
                                    <circle 
                                        :cx="getCityCoords(city.city).x" 
                                        :cy="getCityCoords(city.city).y" 
                                        r="4" 
                                        class="fill-indigo-500/20 stroke-indigo-500/30 animate-pulse" 
                                    />
                                    <!-- Interactive hover circle -->
                                    <circle 
                                        :cx="getCityCoords(city.city).x" 
                                        :cy="getCityCoords(city.city).y" 
                                        r="6" 
                                        class="fill-indigo-500/10 opacity-0 group-hover/node:opacity-100 transition-opacity" 
                                    />
                                    <!-- Core node dot -->
                                    <circle 
                                        :cx="getCityCoords(city.city).x" 
                                        :cy="getCityCoords(city.city).y" 
                                        r="1.8" 
                                        class="fill-indigo-550 stroke-white dark:stroke-slate-900 group-hover/node:scale-125 transition-transform" 
                                        stroke-width="0.4"
                                    />
                                </g>
                            </svg>
                        </div>

                        <!-- Regions Detail Card Panel (col-span-2) -->
                        <div class="md:col-span-2 h-72 border border-[var(--border-color)] bg-[var(--bg-input)]/45 rounded-2xl p-5 flex flex-col justify-between shadow-sm">
                            <div v-if="hoveredCity" class="space-y-4 animate-scale-up">
                                <div>
                                    <span class="text-[9px] font-black text-indigo-500 uppercase tracking-widest bg-indigo-500/10 px-2.5 py-1 rounded-md border border-indigo-500/15 inline-block">Zone de Partenariat</span>
                                    <h4 class="text-base font-extrabold mt-2.5" :class="theme === 'light' ? 'text-stone-900' : 'text-white'">{{ hoveredCity.city }}</h4>
                                    <p class="text-xs text-[var(--text-muted)] mt-1 font-medium">Position : {{ hoveredCity.lat }}°N, {{ hoveredCity.lng }}°E</p>
                                </div>
                                <div class="border-t border-[var(--border-color)] pt-3">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-[var(--text-muted)] font-semibold">Entreprises Affiliées :</span>
                                        <span class="text-sm font-black" :class="theme === 'light' ? 'text-indigo-650' : 'text-white'">{{ hoveredCity.count }}</span>
                                    </div>
                                    <div class="w-full bg-slate-500/10 h-1.5 rounded-full mt-2 overflow-hidden">
                                        <div class="bg-indigo-550 h-full rounded-full" :style="{ width: `${stats.total_companies ? (hoveredCity.count / stats.total_companies * 100) : 0}%` }"></div>
                                    </div>
                                </div>
                                <div class="text-[10px] text-[var(--text-muted-darker)] font-bold flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-nodes text-indigo-500 animate-pulse"></i>
                                    <span>Réseau local sécurisé</span>
                                </div>
                            </div>
                            <div v-else class="h-full flex flex-col items-center justify-center text-center space-y-3 px-4">
                                <div class="h-10 w-10 bg-indigo-500/10 rounded-2xl flex items-center justify-center text-indigo-550 border border-indigo-500/15">
                                    <i class="fa-solid fa-map-location-dot text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold" :class="theme === 'light' ? 'text-stone-900' : 'text-white'">Indicateur Régional</h4>
                                    <p class="text-[10px] text-[var(--text-muted)] mt-1.5 leading-relaxed font-semibold">Survolez un nœud radar sur la carte pour explorer l'activité et le taux d'adoption de cette métropole.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Foot notes -->
                    <div class="flex flex-wrap gap-3 items-center justify-start text-[11px] text-[var(--text-muted)] border-t border-[var(--border-color)] pt-4">
                        <span v-for="city in geoData" :key="city.city" class="flex items-center gap-2 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-xl px-3 py-1 shadow-sm font-semibold">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                            <span :class="theme === 'light' ? 'text-stone-850' : 'text-slate-350'">{{ city.city }}</span> 
                            <span class="text-[10px] text-[var(--text-muted-darker)] font-extrabold">({{ city.count }})</span>
                        </span>
                    </div>
                </div>

                <!-- Subscriptions Distribution Card -->
                <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-3xl p-6 shadow-[var(--card-shadow)] flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-black tracking-tight" :class="theme === 'light' ? 'text-stone-900' : 'text-white'">Formules d'Abonnement</h3>
                        <p class="text-xs text-[var(--text-muted)] mt-0.5">Distribution des forfaits de facturation par entreprise.</p>
                    </div>

                    <!-- Progress meters -->
                    <div class="space-y-6 my-6">
                        <!-- Starter -->
                        <div class="space-y-2">
                            <div class="flex justify-between text-xs font-semibold">
                                <span class="text-[var(--text-muted)] flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded bg-slate-400"></span>
                                    <span>Starter</span>
                                </span>
                                <span class="font-extrabold text-[var(--text-main)]">{{ plansDistribution.starter }}</span>
                            </div>
                            <div class="w-full bg-slate-500/10 dark:bg-slate-950/40 border border-[var(--border-color)] h-3 rounded-full overflow-hidden p-0.5">
                                <div class="bg-gradient-to-r from-slate-400 to-slate-550 h-full rounded-full transition-all duration-500" :style="{ width: `${stats.total_companies ? (plansDistribution.starter / stats.total_companies * 100) : 0}%` }"></div>
                            </div>
                        </div>

                        <!-- Professional -->
                        <div class="space-y-2">
                            <div class="flex justify-between text-xs font-semibold">
                                <span class="text-indigo-500 flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded bg-indigo-500"></span>
                                    <span>Professional</span>
                                </span>
                                <span class="font-extrabold text-[var(--text-main)]">{{ plansDistribution.professional }}</span>
                            </div>
                            <div class="w-full bg-slate-500/10 dark:bg-slate-950/40 border border-[var(--border-color)] h-3 rounded-full overflow-hidden p-0.5">
                                <div class="bg-gradient-to-r from-indigo-550 to-indigo-650 h-full rounded-full transition-all duration-500 shadow-sm" :style="{ width: `${stats.total_companies ? (plansDistribution.professional / stats.total_companies * 100) : 0}%` }"></div>
                            </div>
                        </div>

                        <!-- Enterprise -->
                        <div class="space-y-2">
                            <div class="flex justify-between text-xs font-semibold">
                                <span class="text-cyan-500 flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded bg-cyan-400"></span>
                                    <span>Enterprise</span>
                                </span>
                                <span class="font-extrabold text-[var(--text-main)]">{{ plansDistribution.enterprise }}</span>
                            </div>
                            <div class="w-full bg-slate-500/10 dark:bg-slate-950/40 border border-[var(--border-color)] h-3 rounded-full overflow-hidden p-0.5">
                                <div class="bg-gradient-to-r from-cyan-400 to-blue-500 h-full rounded-full transition-all duration-500" :style="{ width: `${stats.total_companies ? (plansDistribution.enterprise / stats.total_companies * 100) : 0}%` }"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Conversion rate note -->
                    <div class="bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl p-4.5 text-[11px] text-[var(--text-muted)] flex items-start gap-3 shadow-inner">
                        <div class="h-7 w-7 bg-indigo-500/10 rounded-xl flex items-center justify-center text-indigo-500 shrink-0 border border-indigo-500/20">
                            <i class="fa-solid fa-sparkles text-xs"></i>
                        </div>
                        <span class="leading-relaxed">
                            Les abonnements à haute valeur ajoutée (Professional et Enterprise) représentent 
                            <strong :class="theme === 'light' ? 'text-indigo-600' : 'text-white'" class="font-bold">
                                {{ 
                                    stats.total_companies 
                                        ? Math.round(((plansDistribution.professional + plansDistribution.enterprise) / stats.total_companies) * 100)
                                        : 0 
                                }}%
                            </strong> des partenaires enregistrés.
                        </span>
                    </div>
                </div>
            </div>

            <!-- 4. Recent Activities Tables -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Companies Table -->
                <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-3xl p-6 shadow-[var(--card-shadow)]">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-base font-black tracking-tight" :class="theme === 'light' ? 'text-stone-900' : 'text-white'">Dernières Entreprises</h3>
                            <p class="text-xs text-[var(--text-muted)] mt-0.5">Dernières structures juridiques créées sur la plateforme.</p>
                        </div>
                        <a :href="route('superadmin.companies.index')" class="text-xs text-indigo-550 hover:text-indigo-650 font-extrabold transition-colors bg-indigo-500/5 px-3.5 py-2 rounded-xl border border-indigo-500/10">Voir tout</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-[var(--border-color)] text-[10px] uppercase font-bold text-[var(--text-muted-darker)] tracking-wider">
                                    <th class="pb-3.5">Nom</th>
                                    <th class="pb-3.5">Ville</th>
                                    <th class="pb-3.5 text-center">Plan</th>
                                    <th class="pb-3.5 text-right">Statut</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[var(--border-color)] text-xs">
                                <tr v-for="company in recentCompanies" :key="company.id" class="hover:bg-[var(--bg-table-hover)] transition-colors group">
                                    <td class="py-4 font-bold" :class="theme === 'light' ? 'text-stone-850' : 'text-slate-200'">{{ company.legal_name }}</td>
                                    <td class="py-4 text-[var(--text-muted)] font-semibold">{{ company.city }}</td>
                                    <td class="py-4 text-center">
                                        <span class="inline-block px-3 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wider" :class="getPlanBadgeClass(company.plan)">
                                            {{ company.plan }}
                                        </span>
                                    </td>
                                    <td class="py-4 text-right">
                                        <span class="inline-block px-3 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wider shadow-sm" :class="getStatusBadgeClass(company.verification_status)">
                                            {{ company.verification_status === 'approved' ? 'Agréé' : company.verification_status === 'suspended' ? 'Suspendu' : 'Attente' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr v-if="recentCompanies.length === 0">
                                    <td colspan="4" class="py-8 text-center text-[var(--text-muted-darker)] font-bold">Aucune entreprise enregistrée.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Recent Users Table -->
                <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-3xl p-6 shadow-[var(--card-shadow)]">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-base font-black tracking-tight" :class="theme === 'light' ? 'text-stone-900' : 'text-white'">Derniers Utilisateurs</h3>
                            <p class="text-xs text-[var(--text-muted)] mt-0.5">Dernières ouvertures de comptes d'utilisateurs.</p>
                        </div>
                        <a :href="route('superadmin.users.index')" class="text-xs text-indigo-550 hover:text-indigo-650 font-extrabold transition-colors bg-indigo-500/5 px-3.5 py-2 rounded-xl border border-indigo-500/10">Voir tout</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-[var(--border-color)] text-[10px] uppercase font-bold text-[var(--text-muted-darker)] tracking-wider">
                                    <th class="pb-3.5">Utilisateur</th>
                                    <th class="pb-3.5">Type</th>
                                    <th class="pb-3.5 text-right">Structure</th>
                                    <th class="pb-3.5 text-right">Inscrit</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[var(--border-color)] text-xs">
                                <tr v-for="user in recentUsers" :key="user.id" class="hover:bg-[var(--bg-table-hover)] transition-colors">
                                    <td class="py-4">
                                        <p class="font-bold leading-normal" :class="theme === 'light' ? 'text-stone-850' : 'text-slate-200'">{{ user.name }}</p>
                                        <p class="text-[10px] text-[var(--text-muted-darker)] font-semibold leading-none mt-1">{{ user.email }}</p>
                                    </td>
                                    <td class="py-4">
                                        <span class="inline-block px-2.5 py-0.5 rounded bg-slate-500/10 text-[var(--text-muted)] border border-[var(--border-color)] font-extrabold text-[9px] uppercase tracking-wider">
                                            {{ user.account_type }}
                                        </span>
                                    </td>
                                    <td class="py-4 text-right font-extrabold text-[var(--text-muted)]">{{ user.company_name }}</td>
                                    <td class="py-4 text-right text-[var(--text-muted-darker)] font-bold">{{ user.created_at }}</td>
                                </tr>
                                <tr v-if="recentUsers.length === 0">
                                    <td colspan="4" class="py-8 text-center text-[var(--text-muted-darker)] font-bold">Aucun utilisateur enregistré.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </SuperAdminLayout>
</template>

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
    animation: scaleUp 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
</style>
