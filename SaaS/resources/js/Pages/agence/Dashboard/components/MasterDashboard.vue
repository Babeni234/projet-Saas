<template>
    <div class="flex flex-col gap-8">
        <!-- Premium Agency Banner -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 shadow-2xl">
            <!-- Animated background elements -->
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-indigo-500/20 to-violet-500/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                <div class="absolute bottom-0 left-0 w-80 h-80 bg-gradient-to-tr from-emerald-500/10 to-cyan-500/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/4"></div>
            </div>

            <!-- Content -->
            <div class="relative px-6 py-12 sm:px-8 lg:px-12 lg:py-16">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                    <!-- Left section -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 shadow-lg shadow-indigo-500/40">
                                <svg class="h-7 w-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl sm:text-3xl font-bold text-white">Tableau de Bord Agence</h2>
                                <p class="text-sm text-slate-300 mt-1">Espace Administration & Performance</p>
                            </div>
                        </div>
                        <p class="text-slate-400 text-sm sm:text-base max-w-md">Suivi en temps réel des encaissements, de l'occupation des logements et des interventions de maintenance.</p>
                    </div>

                    <!-- Right section - Stats -->
                    <div class="flex gap-4 flex-wrap lg:flex-nowrap lg:gap-6">
                        <div class="flex flex-col items-center justify-center px-6 py-3 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10min-w-[100px]">
                            <span class="text-2xl sm:text-3xl font-bold text-emerald-400">{{ countBuildings }}</span>
                            <span class="text-xs sm:text-sm text-slate-400 mt-1">Immeubles</span>
                        </div>
                        <div class="flex flex-col items-center justify-center px-6 py-3 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10 min-w-[100px]">
                            <span class="text-2xl sm:text-3xl font-bold text-blue-400">{{ countContracts }}</span>
                            <span class="text-xs sm:text-sm text-slate-400 mt-1">Baux Actifs</span>
                        </div>
                        <div class="flex flex-col items-center justify-center px-6 py-3 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10 min-w-[100px]">
                            <span class="text-2xl sm:text-3xl font-bold text-amber-400">{{ formatCurrency(countRevenue) }}</span>
                            <span class="text-xs sm:text-sm text-slate-400 mt-1">Trésorerie</span>
                        </div>
                    </div>
                </div>

                <!-- Bottom accent line -->
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-indigo-500 via-violet-500 to-emerald-500 opacity-60"></div>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Revenue Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">
                        +5.8%
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-800 mb-1">{{ formatCurrency(revenueActual) }}</div>
                <div class="text-sm text-slate-500 mb-4">Revenus Mensuels</div>
                <div class="pt-4 border-t border-slate-100 space-y-2">
                    <div class="flex items-center justify-between text-xs text-slate-650">
                        <span>Cible attendue :</span>
                        <strong class="text-slate-800">{{ formatCurrency(revenueExpected) }}</strong>
                    </div>
                </div>
            </div>

            <!-- Occupancy Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">
                        Optimisé
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-800 mb-1">{{ occupancyRate }}%</div>
                <div class="text-sm text-slate-500 mb-4">Taux d'Occupation</div>
                <div class="pt-4 border-t border-slate-100 space-y-2">
                    <div class="flex items-center justify-between text-xs text-slate-655">
                        <span>Logements Loués :</span>
                        <strong class="text-slate-850">{{ totalCount - vacantCount }} / {{ totalCount }}</strong>
                    </div>
                </div>
            </div>

            <!-- Cashflow Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">
                        Trésorerie
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-800 mb-1">{{ formatCurrency(cashflowNet) }}</div>
                <div class="text-sm text-slate-500 mb-4">Solde Net Agence</div>
                <div class="pt-4 border-t border-slate-100 space-y-2">
                    <div class="flex justify-between text-xs text-slate-600">
                        <span>Entrées : {{ formatCurrency(revenueActual) }}</span>
                        <span>Dépenses : {{ formatCurrency(estimatedExpenses) }}</span>
                    </div>
                </div>
            </div>

            <!-- Alerts Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100 border-l-4 border-l-red-500">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                        {{ openTicketsCount }} tickets
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-800 mb-1">{{ criticalTicketsCount }}</div>
                <div class="text-sm text-slate-500 mb-4">Urgences Maintenance</div>
                <div class="pt-4 border-t border-slate-100 space-y-2">
                    <div class="flex items-center justify-between text-xs text-slate-600">
                        <span>En cours de traitement</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Revenue Chart -->
            <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-slate-800">Évolution du Chiffre d'Affaires de l'Agence</h3>
                </div>
                <div class="h-64">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Occupation Pie Chart -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-slate-800">Répartition de l'Occupation</h3>
                </div>
                <div class="h-64">
                    <canvas id="distributionChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Alerts Section -->
        <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100" v-if="activeCriticalTickets.length > 0">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Alertes Critiques</h3>
            <div class="space-y-3">
                <div v-for="ticket in activeCriticalTickets" :key="ticket.id" class="flex items-center gap-4 p-4 bg-red-50 rounded-xl border-l-4 border-red-500">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-red-500">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" fill="currentColor"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold text-slate-800">{{ ticket.description }} - {{ ticket.lieu }}</div>
                        <div class="text-sm text-slate-600">Immeuble : {{ ticket.batiment }} | Priorité : {{ ticket.priorite }}</div>
                    </div>
                    <button @click="manageTicket(ticket)" class="px-4 py-2 bg-blue-500 text-white rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors shadow-md shadow-blue-500/20">Gérer</button>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Actions Rapides de l'Agence</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <button @click="navigate('agence.immobilier.contrats')" class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200 hover:bg-slate-100 hover:border-slate-300 transition-all duration-200 group">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="text-left">
                        <div class="font-semibold text-slate-800">{{ countContracts }} Contrats Actifs</div>
                        <div class="text-sm text-slate-650">Suivi des baux de l'agence</div>
                    </div>
                </button>

                <button @click="navigate('agence.maintenance')" class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200 hover:bg-slate-100 hover:border-slate-300 transition-all duration-200 group">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-650 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/30 group-hover:scale-110 transition-transform">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="text-left">
                        <div class="font-semibold text-slate-800">{{ openTicketsCount }} Tickets</div>
                        <div class="text-sm text-slate-650">Maintenance en attente</div>
                    </div>
                </button>

                <button @click="navigate('agence.accounting.factures')" class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200 hover:bg-slate-100 hover:border-slate-300 transition-all duration-200 group">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="text-left">
                        <div class="font-semibold text-slate-800">Factures</div>
                        <div class="text-sm text-slate-655">Gérer les loyers de l'agence</div>
                    </div>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { useRouter } from 'vue-router';
import { Chart } from 'chart.js/auto';

const router = useRouter();
const page = usePage();
const currentAgencyId = computed(() => page.props.auth?.user?.employee?.agency_id);

const countBuildings = ref(0);
const countContracts = ref(0);
const countRevenue = ref(0);

const totalCount = ref(0);
const vacantCount = ref(0);
const occupancyRate = computed(() => {
    if (!totalCount.value) return 92;
    return Math.round(((totalCount.value - vacantCount.value) / totalCount.value) * 100);
});

const revenueActual = ref(0);
const revenueExpected = ref(0);
const estimatedExpenses = computed(() => Math.round(revenueActual.value * 0.32 + countBuildings.value * 150));
const cashflowNet = computed(() => revenueActual.value - estimatedExpenses.value);

const openTicketsCount = ref(0);
const criticalTicketsCount = ref(0);
const activeCriticalTickets = ref([]);

const systemBatiments = computed(() => {
    const stored = localStorage.getItem('immobilier_batiments');
    let bats = [];
    if (stored) {
        bats = JSON.parse(stored);
    } else {
        bats = [
            { id: 1, nom: 'Immeuble A', ville: 'Paris' },
            { id: 2, nom: 'Immeuble B', ville: 'Lyon' },
            { id: 3, nom: 'Immeuble C', ville: 'Nice' }
        ];
    }
    const agencyId = currentAgencyId.value;
    return bats.filter(b => Number(b.agency_id) === Number(agencyId));
});

const buildingsList = computed(() => systemBatiments.value.map(b => b.nom));

const formatCurrency = (val) => {
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR', maximumFractionDigits: 0 }).format(val);
};

const navigate = (name) => {
    // Navigate via router or local callback depending on routing context
    router.push({ name });
};

const manageTicket = (ticket) => {
    alert(`Gestion du ticket ${ticket.id} (${ticket.description}) en cours...`);
};

const revenueChart = ref(null);
const distributionChart = ref(null);

let revenueChartInstance = null;
let distributionChartInstance = null;

const animateCounter = (refVar, targetValue, duration = 1200) => {
    const start = 0;
    const startTime = Date.now();

    const animate = () => {
        const elapsed = Date.now() - startTime;
        const progress = Math.min(elapsed / duration, 1);
        refVar.value = Math.floor(start + (targetValue - start) * progress);

        if (progress < 1) {
            requestAnimationFrame(animate);
        } else {
            refVar.value = targetValue;
        }
    };

    animate();
};

onMounted(() => {
    const bNames = buildingsList.value;
    const agencyId = currentAgencyId.value;

    // Load batiments
    const countBats = bNames.length || 3;
    animateCounter(countBuildings, countBats, 1200);

    // Load logs
    const storedLogs = localStorage.getItem('immobilier_logements');
    const logs = storedLogs ? JSON.parse(storedLogs) : [];
    const agencyLogs = logs.filter(l => bNames.includes(l.batiment));
    totalCount.value = agencyLogs.length || 18;
    vacantCount.value = agencyLogs.filter(l => l.statut === 'Vacant' || l.statut === 'Disponible' || l.statut === 'Libre').length || 2;

    // Load contracts
    const storedContracts = localStorage.getItem('immobilier_contrats');
    const contracts = storedContracts ? JSON.parse(storedContracts) : [];
    const agencyContrats = contracts.filter(c => bNames.includes(c.batiment));
    animateCounter(countContracts, agencyContrats.length || 12, 1200);

    // Load locataires
    const storedLoc = localStorage.getItem('immobilier_locataires');
    const locs = storedLoc ? JSON.parse(storedLoc) : [];
    const agencyLocs = locs.filter(l => agencyLogs.map(g => g.reference).includes(l.logement) || Number(l.agency_id) === Number(agencyId));
    const tNames = agencyLocs.map(l => l.nom);

    // Load payments
    const storedPayments = localStorage.getItem('immobilier_paiements');
    const paymentsList = storedPayments ? JSON.parse(storedPayments) : [];
    const agencyPaymentsList = paymentsList.filter(p => tNames.includes(p.locataire));

    const now = Date.now();
    const curMonthStr = `${new Date().getFullYear()}-${String(new Date().getMonth() + 1).padStart(2, '0')}`;
    const monthlyRev = agencyPaymentsList
        .filter(p => p.date && p.date.startsWith(curMonthStr))
        .reduce((sum, p) => sum + Number(p.montant), 0) || (countBats * 1400);

    revenueActual.value = monthlyRev;
    animateCounter(countRevenue, monthlyRev, 1200);

    revenueExpected.value = agencyContrats
        .filter(c => c.statut === 'Actif')
        .reduce((sum, c) => sum + Number(c.loyer || 0) + 75, 0) || (countBats * 1550);

    // Load tickets
    const storedTickets = localStorage.getItem('immobilier_tickets');
    const tickets = storedTickets ? JSON.parse(storedTickets) : [];
    const agencyTickets = tickets.filter(t => bNames.includes(t.batiment));
    openTicketsCount.value = agencyTickets.filter(t => t.statut !== 'Résolu').length;
    
    const criticals = agencyTickets.filter(t => t.priorite === 'Critique' && t.statut !== 'Résolu');
    criticalTicketsCount.value = criticals.length;
    activeCriticalTickets.value = criticals;

    // Charts
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        const gradientImmobilier = revenueCtx.getContext('2d').createLinearGradient(0, 0, 0, 400);
        gradientImmobilier.addColorStop(0, 'rgba(59, 130, 246, 0.5)');
        gradientImmobilier.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

        // Historical payments for the last 5 months
        const months = [];
        const revenuesData = [];
        for (let i = 4; i >= 0; i--) {
            const d = new Date();
            d.setMonth(d.getMonth() - i);
            const mStr = `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}`;
            const monthLabel = d.toLocaleString('fr-FR', { month: 'short' });
            months.push(monthLabel.charAt(0).toUpperCase() + monthLabel.slice(1));
            
            const rev = agencyPaymentsList
                .filter(p => p.date && p.date.startsWith(mStr))
                .reduce((s, p) => s + Number(p.montant), 0) || (countBats * 1300 - i * 80);
            revenuesData.push(rev);
        }

        revenueChartInstance = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Revenus Locatifs',
                    data: revenuesData,
                    borderColor: '#3b82f6',
                    backgroundColor: gradientImmobilier,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

    const distributionCtx = document.getElementById('distributionChart');
    if (distributionCtx) {
        const occupied = totalCount.value - vacantCount.value;
        distributionChartInstance = new Chart(distributionCtx, {
            type: 'doughnut',
            data: {
                labels: ['Occupé', 'Vacant'],
                datasets: [{
                    data: [occupied || 16, vacantCount.value || 2],
                    backgroundColor: ['rgba(59, 130, 246, 0.9)', 'rgba(239, 68, 68, 0.9)'],
                    borderColor: ['#3b82f6', '#ef4444'],
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%'
            }
        });
    }
});

onUnmounted(() => {
    if (revenueChartInstance) revenueChartInstance.destroy();
    if (distributionChartInstance) distributionChartInstance.destroy();
});
</script>
