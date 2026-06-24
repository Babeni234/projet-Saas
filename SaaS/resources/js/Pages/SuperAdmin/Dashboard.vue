<script setup>
import SuperAdminLayout from './layouts/SuperAdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, inject, onMounted, onUnmounted, watch, nextTick } from 'vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

const props = defineProps({
    stats: {
        type: Object,
        required: true,
    },
    subscriptionPlans: {
        type: Object,
        default: () => ({ individual: [], company: [] }),
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
const selectedCountry = ref(null);
const mapContainer = ref(null);
let mapInstance = null;

// Initialize Leaflet map
onMounted(async () => {
    console.log('Initializing map, container:', mapContainer.value);
    console.log('geoData:', props.geoData);
    
    // Wait for DOM to be fully rendered
    await nextTick();
    
    // Additional delay to ensure container is fully rendered
    setTimeout(() => {
        if (mapContainer.value) {
            // Ensure container has dimensions before initializing map
            const container = mapContainer.value;
            container.style.width = '100%';
            container.style.height = '100%';
            
            console.log('Container dimensions:', container.clientWidth, container.clientHeight);
            console.log('Container offset dimensions:', container.offsetWidth, container.offsetHeight);
            
            try {
                mapInstance = L.map(mapContainer.value, {
                    center: [48.0, 10.0],
                    zoom: 4,
                    zoomControl: true
                });
                console.log('Map instance created successfully');

                let tileLayerInstance = null;

                const updateTileLayer = (currentTheme) => {
                    if (tileLayerInstance) {
                        mapInstance.removeLayer(tileLayerInstance);
                    }
                    const tileUrl = currentTheme === 'light'
                        ? 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png'
                        : 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png';

                    tileLayerInstance = L.tileLayer(tileUrl, {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                        subdomains: 'abcd',
                        maxZoom: 20
                    }).addTo(mapInstance);
                };

                updateTileLayer(theme.value);

                // Dynamically update map tiles when theme changes
                watch(theme, (newTheme) => {
                    updateTileLayer(newTheme);
                });

                // Add markers for each country
                if (props.geoData && props.geoData.length > 0) {
                    props.geoData.forEach(country => {
                        const coords = { lat: country.lat, lng: country.lng };
                        const marker = L.circleMarker([coords.lat, coords.lng], {
                            radius: 12 + (country.count * 0.5),
                            fillColor: '#6366f1',
                            color: '#818cf8',
                            weight: 2,
                            opacity: 1,
                            fillOpacity: 0.8
                        }).addTo(mapInstance);

                        marker.bindPopup(`
                            <div style="color: #1e293b; font-family: sans-serif;">
                                <strong style="color: #6366f1;">${country.country_name}</strong><br>
                                <span style="font-size: 12px;">Entreprises: ${country.count}</span>
                            </div>
                        `);

                        marker.on('click', () => {
                            selectedCountry.value = country;
                            marker.setStyle({
                                radius: 16 + (country.count * 0.5),
                                fillColor: '#818cf8',
                                fillOpacity: 1
                            });
                        });

                        marker.on('mouseover', () => {
                            marker.setStyle({
                                radius: 14 + (country.count * 0.5),
                                fillColor: '#818cf8',
                                fillOpacity: 1
                            });
                        });

                        marker.on('mouseout', () => {
                            if (selectedCountry.value?.country !== country.country) {
                                marker.setStyle({
                                    radius: 12 + (country.count * 0.5),
                                    fillColor: '#6366f1',
                                    fillOpacity: 0.8
                                });
                            }
                        });
                    });
                }

                // Invalidate map size after adding markers
                setTimeout(() => {
                    mapInstance.invalidateSize();
                }, 100);
            } catch (error) {
                console.error('Error initializing map:', error);
            }
        }
    }, 200);
});

onUnmounted(() => {
    if (mapInstance) {
        mapInstance.remove();
        mapInstance = null;
    }
});

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
</script>

<template>
    <Head title="Administration Dashboard" />

    <SuperAdminLayout>
        <div class="space-y-8 page-entrance">
            <!-- 1. Header Page Title -->
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-black tracking-tight text-[var(--text-main)]">Panneau de Contrôle Global</h2>
                    <p class="text-sm text-[var(--text-muted)] mt-1">Gérez le parc applicatif, surveillez l'activité générale et suivez les souscriptions en temps réel.</p>
                </div>
            </div>

            <!-- 2. Stats Grid with Left Accent Strips & Prominent Alignments -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Companies Card -->
                <div 
                    class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-3xl p-6 shadow-[var(--card-shadow)] hover:-translate-y-1 hover:shadow-indigo-500/10 hover:border-indigo-500/30 transition-all duration-300 relative group overflow-hidden"
                >
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-gradient-to-b from-indigo-500 to-violet-600"></div>
                    <div class="flex items-center justify-between">
                        <div class="space-y-1.5">
                            <span class="text-xs font-bold text-[var(--text-muted)] uppercase tracking-wider">Entreprises</span>
                            <p class="text-4xl font-black tracking-tight"  :class="theme === 'light' ? 'text-slate-900' : 'text-white' ">{{ stats.total_companies }}</p>
                        </div>
                        <div class="h-12 w-12 bg-indigo-500/10 border border-indigo-500/25 rounded-2xl flex items-center justify-center shadow-sm group-hover:scale-105 duration-300" :class="theme === 'light' ? 'text-indigo-600' : 'text-indigo-400'">
                            <i class="fa-solid fa-building text-base"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-[var(--border-color)] flex items-center justify-between text-[10px] text-[var(--text-muted)] font-bold">
                        <span>Actives sur la plateforme</span>
                        <span class="flex items-center gap-1.5" :class="theme === 'light' ? 'text-indigo-600' : 'text-indigo-400'">
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
                            <p class="text-4xl font-black tracking-tight" :class="theme === 'light' ? 'text-slate-900' : 'text-white'">{{ stats.total_users }}</p>
                        </div>
                        <div class="h-12 w-12 bg-cyan-500/10 border border-cyan-500/25 rounded-2xl flex items-center justify-center shadow-sm group-hover:scale-105 duration-300" :class="theme === 'light' ? 'text-cyan-600' : 'text-cyan-400'">
                            <i class="fa-solid fa-users text-base"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-[var(--border-color)] flex items-center justify-between text-[10px] text-[var(--text-muted)] font-bold">
                        <span>Comptes globaux créés</span>
                        <span class="flex items-center gap-1.5" :class="theme === 'light' ? 'text-cyan-600' : 'text-cyan-400'">
                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-500 animate-pulse"></span>
                            Actifs
                        </span>
                    </div>
                </div>

                <!-- Agencies Card -->
                <div 
                    class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-3xl p-6 shadow-[var(--card-shadow)] hover:-translate-y-1 hover:shadow-emerald-500/10 hover:border-emerald-500/30 transition-all duration-300 relative group overflow-hidden"
                >
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-gradient-to-b from-emerald-400 to-teal-600"></div>
                    <div class="flex items-center justify-between">
                        <div class="space-y-1.5">
                            <span class="text-xs font-bold text-[var(--text-muted)] uppercase tracking-wider">Agences</span>
                            <p class="text-4xl font-black tracking-tight" :class="theme === 'light' ? 'text-slate-900' : 'text-white'">{{ stats.total_agencies }}</p>
                        </div>
                        <div class="h-12 w-12 bg-emerald-500/10 border border-emerald-500/25 rounded-2xl flex items-center justify-center shadow-sm group-hover:scale-105 duration-300" :class="theme === 'light' ? 'text-emerald-600' : 'text-emerald-400'">
                            <i class="fa-solid fa-house-chimney-window text-base"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-[var(--border-color)] flex items-center justify-between text-[10px] text-[var(--text-muted)] font-bold">
                        <span>Bureaux enregistrés</span>
                        <span class="flex items-center gap-1.5" :class="theme === 'light' ? 'text-emerald-600' : 'text-emerald-400'">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            En ligne
                        </span>
                    </div>
                </div>

                <!-- Tenants Card -->
                <div 
                    class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-3xl p-6 shadow-[var(--card-shadow)] hover:-translate-y-1 hover:shadow-amber-500/10 hover:border-amber-500/30 transition-all duration-300 relative group overflow-hidden"
                >
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-gradient-to-b from-amber-400 to-orange-500"></div>
                    <div class="flex items-center justify-between">
                        <div class="space-y-1.5">
                            <span class="text-xs font-bold text-[var(--text-muted)] uppercase tracking-wider">Locataires</span>
                            <p class="text-4xl font-black tracking-tight" :class="theme === 'light' ? 'text-slate-900' : 'text-white'">{{ stats.total_tenants }}</p>
                        </div>
                        <div class="h-12 w-12 bg-amber-500/10 border border-amber-500/25 rounded-2xl flex items-center justify-center shadow-sm group-hover:scale-105 duration-300" :class="theme === 'light' ? 'text-amber-600' : 'text-amber-400'">
                            <i class="fa-solid fa-people-roof text-base"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-[var(--border-color)] flex items-center justify-between text-[10px] text-[var(--text-muted)] font-bold">
                        <span>Logements loués : {{ stats.total_logements || 0 }}</span>
                        <span class="flex items-center gap-1.5" :class="theme === 'light' ? 'text-amber-600' : 'text-amber-400'">
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
                        <h3 class="text-lg font-black tracking-tight text-[var(--text-main)]">Répartition Géographique</h3>
                        <p class="text-xs text-[var(--text-muted)] mt-0.5 font-semibold">Distribution géographique des entreprises partenaires enregistrées.</p>
                    </div>

                    <!-- Leaflet Map Container -->
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 my-4 items-center">
                        <!-- Interactive Map (col-span-3) -->
                        <div class="md:col-span-3 relative rounded-2xl border border-[var(--border-color)] shadow-inner premium-glow" style="height: 288px;">
                            <div ref="mapContainer" class="w-full h-full"></div>
                        </div>

                        <!-- Regions Detail Card Panel (col-span-2) -->
                        <div class="md:col-span-2 h-72 border border-[var(--border-color)] bg-[var(--bg-input)]/45 rounded-2xl p-5 flex flex-col shadow-sm overflow-hidden">
                            <div class="overflow-hidden flex-1">
                                <div v-if="selectedCountry" class="space-y-4 h-full overflow-y-auto scrollbar-hide">
                                    <div>
                                        <span class="text-[9px] font-black text-indigo-500 uppercase tracking-widest bg-indigo-500/10 px-2.5 py-1 rounded-md border border-indigo-500/15 block w-fit">Zone de Partenariat</span>
                                        <h4 class="text-base font-extrabold mt-2.5 text-[var(--text-main)]">{{ selectedCountry.country_name }}</h4>
                                        <p class="text-xs text-[var(--text-muted)] mt-1 font-medium">Code : {{ selectedCountry.country }}</p>
                                    </div>
                                    <div class="border-t border-[var(--border-color)] pt-3">
                                        <div class="flex items-center justify-between text-xs mb-2">
                                            <span class="text-[var(--text-muted)] font-semibold">Total Entreprises :</span>
                                            <span class="text-sm font-black" :class="theme === 'light' ? 'text-indigo-600' : 'text-white'">{{ selectedCountry.count }}</span>
                                        </div>
                                        <div class="w-full bg-slate-500/10 h-1.5 rounded-full overflow-hidden">
                                            <div class="bg-indigo-600 h-full rounded-full" :style="{ width: `${stats.total_companies ? (selectedCountry.count / stats.total_companies * 100) : 0}%` }"></div>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <h5 class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-wider">Liste des Entreprises</h5>
                                        <div class="space-y-2 max-h-32 overflow-y-auto scrollbar-hide">
                                            <div v-for="company in selectedCountry.companies" :key="company.id" class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-lg p-2.5">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-xs font-bold text-[var(--text-main)] truncate">{{ company.legal_name }}</p>
                                                        <p class="text-[10px] text-[var(--text-muted)] font-semibold">{{ company.city }}</p>
                                                    </div>
                                                    <span class="text-[9px] font-bold text-indigo-500 ml-2">{{ company.agencies_count }} agences</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="h-full flex flex-col items-center justify-center text-center space-y-3 px-4">
                                    <div class="h-10 w-10 bg-indigo-500/10 rounded-2xl flex items-center justify-center text-indigo-600 border border-indigo-500/15">
                                        <i class="fa-solid fa-map-location-dot text-lg"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-[var(--text-main)]">Indicateur Régional</h4>
                                        <p class="text-[10px] text-[var(--text-muted)] mt-1.5 leading-relaxed font-semibold">Cliquez sur un marqueur sur la carte pour voir la liste des entreprises dans ce pays ({{ geoData.reduce((sum, c) => sum + c.count, 0) }} entreprises au total).</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Foot notes -->
                    <div class="flex flex-wrap gap-3 items-center justify-start text-[11px] text-[var(--text-muted)] border-t border-[var(--border-color)] pt-4">
                        <span v-for="country in geoData" :key="country.country" class="flex items-center gap-2 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-xl px-3 py-1 shadow-sm font-semibold cursor-pointer hover:bg-indigo-500/10 transition-colors" @click="selectedCountry = country">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                            <span :class="theme === 'light' ? 'text-slate-800' : 'text-slate-400'">{{ country.country_name }}</span> 
                            <span class="text-[10px] text-[var(--text-muted)] font-extrabold">({{ country.count }})</span>
                        </span>
                    </div>
                </div>

                <!-- Subscriptions Distribution Card -->
                <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-3xl p-6 shadow-[var(--card-shadow)] flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-black tracking-tight text-[var(--text-main)]">Formules d'Abonnement</h3>
                        <p class="text-xs text-[var(--text-muted)] mt-0.5">Distribution des forfaits de facturation par entreprise.</p>
                    </div>

                    <!-- Plans by category -->
                    <div class="space-y-5 my-6 max-h-[320px] overflow-y-auto scrollbar-hide">
                        <!-- Individual Plans -->
                        <div v-if="subscriptionPlans.individual && subscriptionPlans.individual.length > 0">
                            <h4 class="text-xs font-black text-indigo-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                                <i class="fa-solid fa-user"></i>
                                Particulier
                            </h4>
                            <div class="space-y-3">
                                <div v-for="plan in subscriptionPlans.individual" :key="plan.id" class="space-y-2">
                                    <div class="flex justify-between text-xs font-semibold">
                                        <span class="text-[var(--text-muted)] flex items-center gap-2">
                                            <span class="w-2.5 h-2.5 rounded-full" :style="{ backgroundColor: plan.color || '#6366f1' }"></span>
                                            <span>{{ plan.name }}</span>
                                            <span v-if="plan.popular" class="text-[9px] bg-amber-500/10 text-amber-500 px-1.5 py-0.5 rounded border border-amber-500/25">Populaire</span>
                                        </span>
                                        <span class="font-extrabold text-[var(--text-main)]">{{ plan.user_count }}</span>
                                    </div>
                                    <div class="w-full bg-slate-500/10 dark:bg-slate-950/40 border border-[var(--border-color)] h-2.5 rounded-full overflow-hidden p-0.5">
                                        <div class="h-full rounded-full transition-all duration-500" 
                                            :style="{ 
                                                width: `${stats.total_users ? (plan.user_count / stats.total_users * 100) : 0}%`,
                                                background: `linear-gradient(to right, ${plan.color || '#6366f1'}, ${plan.color || '#818cf8'})`
                                            }">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Company Plans -->
                        <div v-if="subscriptionPlans.company && subscriptionPlans.company.length > 0">
                            <h4 class="text-xs font-black text-cyan-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                                <i class="fa-solid fa-building"></i>
                                Entreprise
                            </h4>
                            <div class="space-y-3">
                                <div v-for="plan in subscriptionPlans.company" :key="plan.id" class="space-y-2">
                                    <div class="flex justify-between text-xs font-semibold">
                                        <span class="text-[var(--text-muted)] flex items-center gap-2">
                                            <span class="w-2.5 h-2.5 rounded-full" :style="{ backgroundColor: plan.color || '#06b6d4' }"></span>
                                            <span>{{ plan.name }}</span>
                                            <span v-if="plan.popular" class="text-[9px] bg-amber-500/10 text-amber-500 px-1.5 py-0.5 rounded border border-amber-500/25">Populaire</span>
                                        </span>
                                        <span class="font-extrabold text-[var(--text-main)]">{{ plan.user_count }}</span>
                                    </div>
                                    <div class="w-full bg-slate-500/10 dark:bg-slate-950/40 border border-[var(--border-color)] h-2.5 rounded-full overflow-hidden p-0.5">
                                        <div class="h-full rounded-full transition-all duration-500" 
                                            :style="{ 
                                                width: `${stats.total_companies ? (plan.user_count / stats.total_companies * 100) : 0}%`,
                                                background: `linear-gradient(to right, ${plan.color || '#06b6d4'}, ${plan.color || '#22d3ee'})`
                                            }">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total summary -->
                    <div class="pt-4 border-t border-[var(--border-color)]">
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-[var(--text-muted)] font-semibold">Total abonnés</span>
                            <span class="font-extrabold text-[var(--text-main)]">
                                {{ (subscriptionPlans.individual?.reduce((sum, p) => sum + p.user_count, 0) || 0) + 
                                   (subscriptionPlans.company?.reduce((sum, p) => sum + p.user_count, 0) || 0) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Recent Activities Tables -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Companies Table -->
                <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-3xl p-6 shadow-[var(--card-shadow)]">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-base font-black tracking-tight text-[var(--text-main)]">Dernières Entreprises</h3>
                            <p class="text-xs text-[var(--text-muted)] mt-0.5">Dernières structures juridiques créées sur la plateforme.</p>
                        </div>
                        <a :href="route('superadmin.companies.index')" class="text-xs font-extrabold transition-colors bg-indigo-500/5 px-3.5 py-2 rounded-xl border border-indigo-500/10" :class="theme === 'light' ? 'text-indigo-600 hover:text-indigo-700' : 'text-indigo-400 hover:text-indigo-300'">Voir tout</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-[var(--border-color)] text-[10px] uppercase font-bold text-[var(--text-muted)] tracking-wider">
                                    <th class="pb-3.5">Nom</th>
                                    <th class="pb-3.5">Ville</th>
                                    <th class="pb-3.5 text-center">Plan</th>
                                    <th class="pb-3.5 text-right">Statut</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[var(--border-color)] text-xs">
                                <tr v-for="company in recentCompanies" :key="company.id" class="hover:bg-[var(--bg-table-hover)] transition-colors group">
                                    <td class="py-4 font-bold text-[var(--text-main)]">{{ company.legal_name }}</td>
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
                                    <td colspan="4" class="py-8 text-center text-[var(--text-muted)] font-bold">Aucune entreprise enregistrée.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Recent Users Table -->
                <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-3xl p-6 shadow-[var(--card-shadow)]">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-base font-black tracking-tight text-[var(--text-main)]">Derniers Utilisateurs</h3>
                            <p class="text-xs text-[var(--text-muted)] mt-0.5">Dernières ouvertures de comptes d'utilisateurs.</p>
                        </div>
                        <a :href="route('superadmin.users.index')" class="text-xs font-extrabold transition-colors bg-indigo-500/5 px-3.5 py-2 rounded-xl border border-indigo-500/10" :class="theme === 'light' ? 'text-indigo-600 hover:text-indigo-700' : 'text-indigo-400 hover:text-indigo-300'">Voir tout</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-[var(--border-color)] text-[10px] uppercase font-bold text-[var(--text-muted)] tracking-wider">
                                    <th class="pb-3.5">Utilisateur</th>
                                    <th class="pb-3.5">Type</th>
                                    <th class="pb-3.5 text-right">Structure</th>
                                    <th class="pb-3.5 text-right">Inscrit</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[var(--border-color)] text-xs">
                                <tr v-for="user in recentUsers" :key="user.id" class="hover:bg-[var(--bg-table-hover)] transition-colors">
                                    <td class="py-4">
                                        <p class="font-bold leading-normal text-[var(--text-main)]">{{ user.name }}</p>
                                        <p class="text-[10px] text-[var(--text-muted)] font-semibold leading-none mt-1">{{ user.email }}</p>
                                    </td>
                                    <td class="py-4">
                                        <span class="inline-block px-2.5 py-0.5 rounded bg-slate-500/10 text-[var(--text-muted)] border border-[var(--border-color)] font-extrabold text-[9px] uppercase tracking-wider">
                                            {{ user.account_type }}
                                        </span>
                                    </td>
                                    <td class="py-4 text-right font-extrabold text-[var(--text-muted)]">{{ user.company_name }}</td>
                                    <td class="py-4 text-right text-[var(--text-muted)] font-bold">{{ user.created_at }}</td>
                                </tr>
                                <tr v-if="recentUsers.length === 0">
                                    <td colspan="4" class="py-8 text-center text-[var(--text-muted)] font-bold">Aucun utilisateur enregistré.</td>
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
/* Page-specific animation is loaded via .page-entrance class in layout */

:deep(.leaflet-control-zoom-in),
:deep(.leaflet-control-zoom-out) {
  background-color: var(--bg-input) !important;
  color: var(--text-main) !important;
  border-color: var(--border-color) !important;
  transition: all 0.3s ease;
}

:deep(.leaflet-control-zoom-in:hover),
:deep(.leaflet-control-zoom-out:hover) {
  background-color: var(--bg-btn-secondary) !important;
  color: var(--text-main) !important;
}

:deep(.leaflet-bar) {
  border: 1px solid var(--border-color) !important;
  box-shadow: var(--card-shadow) !important;
}

:deep(.leaflet-container) {
  background-color: var(--bg-app) !important;
}

/* Hide scrollbar but keep functionality */
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}

.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>
