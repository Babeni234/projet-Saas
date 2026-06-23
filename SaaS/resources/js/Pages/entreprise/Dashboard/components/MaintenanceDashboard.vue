<template>
    <div class="flex flex-col gap-8">
        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Open Tickets Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                        Actifs
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-800 mb-1">{{ openTicketsCount }}</div>
                <div class="text-sm text-slate-500 mb-4">Tickets Ouverts</div>
                <div class="text-xs text-slate-400">Pour l'ensemble de la compagnie</div>
            </div>

            <!-- Critical Issues Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100 border-l-4 border-l-red-500">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold" v-if="criticalTicketsCount > 0">
                        Urgent
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold" v-else>
                        RAS
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-800 mb-1">{{ criticalTicketsCount }}</div>
                <div class="text-sm text-slate-500 mb-4">Problèmes Critiques</div>
                <div class="text-xs text-slate-400">Intervention immédiate requise</div>
            </div>

            <!-- Avg Resolution Time Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">
                        -15%
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-800 mb-1">2.4j</div>
                <div class="text-sm text-slate-500 mb-4">Temps de Résolution Moyen</div>
                <div class="text-xs text-slate-400">vs 2.8j le mois dernier</div>
            </div>

            <!-- Maintenance Budget Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-slate-300/50 transition-all duration-300 hover:-translate-y-1 border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/30">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-semibold">
                        Budget Global
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-800 mb-1">{{ formatCurrency(budgetTotal) }}</div>
                <div class="text-sm text-slate-500 mb-4">Budget Estimé (Mensuel)</div>
                <div class="text-xs text-slate-400">Utilisé: {{ formatCurrency(budgetUsed) }}</div>
            </div>
        </div>

        <!-- Critical Issues Section -->
        <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100" v-if="criticalTickets.length > 0">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-slate-800">Problèmes Critiques</h3>
            </div>
            <div class="space-y-3">
                <div v-for="ticket in criticalTickets" :key="ticket.id" class="flex items-center gap-4 p-4 bg-red-50 rounded-xl border-l-4 border-red-500">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-red-500">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" fill="currentColor"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold text-slate-800">{{ ticket.description }} - {{ ticket.lieu }}</div>
                        <div class="text-sm text-slate-600">Priorité: {{ ticket.priorite }} | Statut: {{ ticket.statut }}</div>
                    </div>
                    <button @click="manageTicket(ticket)" class="px-4 py-2 bg-blue-500 text-white rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors shadow-md shadow-blue-500/20">Gérer</button>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Tickets by Category -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-slate-800">Tickets par Catégorie</h3>
                </div>
                <div class="h-64">
                    <canvas id="ticketsCategoryChart"></canvas>
                </div>
            </div>

            <!-- Resolution Time Trend -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-slate-800">Temps de Résolution</h3>
                </div>
                <div class="h-64">
                    <canvas id="resolutionTimeChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Real-time Maintenance Assets -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Buildings under Maintenance -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
                <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 bg-amber-500 rounded-full animate-ping"></span>
                        <h3 class="text-lg font-bold text-slate-800">Bâtiments en Maintenance Actuelle</h3>
                    </div>
                    <span class="px-2.5 py-0.5 bg-amber-100 text-amber-800 rounded-full text-xs font-bold">{{ realMaintenanceBuildings.length }}</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 font-bold">
                                <th class="p-3">Bâtiment</th>
                                <th class="p-3">Référence</th>
                                <th class="p-3">Propriétaire</th>
                                <th class="p-3">Localisation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="b in realMaintenanceBuildings" :key="b.id" class="border-t border-slate-100 hover:bg-slate-50/50">
                                <td class="p-3 font-bold text-slate-700">{{ b.nom }}</td>
                                <td class="p-3 text-slate-500">{{ b.reference }}</td>
                                <td class="p-3 text-slate-600">{{ b.proprietaire_nom || 'Sans propriétaire' }}</td>
                                <td class="p-3 text-slate-500">{{ b.ville }}</td>
                            </tr>
                            <tr v-if="realMaintenanceBuildings.length === 0">
                                <td colspan="4" class="p-4 text-center text-slate-400 italic">Aucun bâtiment en cours de maintenance.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Lodgings under Maintenance -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
                <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 bg-amber-500 rounded-full animate-ping"></span>
                        <h3 class="text-lg font-bold text-slate-800">Logements en Maintenance Actuelle</h3>
                    </div>
                    <span class="px-2.5 py-0.5 bg-amber-100 text-amber-800 rounded-full text-xs font-bold">{{ realMaintenanceLogements.length }}</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 font-bold">
                                <th class="p-3">Logement</th>
                                <th class="p-3">Catégorie</th>
                                <th class="p-3">Bâtiment</th>
                                <th class="p-3">Loyer</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="l in realMaintenanceLogements" :key="l.id" class="border-t border-slate-100 hover:bg-slate-50/50">
                                <td class="p-3 font-bold text-slate-700">{{ l.reference }}</td>
                                <td class="p-3 text-slate-500"><span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[10px] font-bold">{{ l.categorie }}</span></td>
                                <td class="p-3 text-slate-600">{{ l.batiment || 'Indépendant' }}</td>
                                <td class="p-3 text-slate-700 font-semibold">{{ l.loyer }} €</td>
                            </tr>
                            <tr v-if="realMaintenanceLogements.length === 0">
                                <td colspan="4" class="p-4 text-center text-slate-400 italic">Aucun logement en cours de maintenance.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Tickets -->
        <div class="bg-white rounded-2xl p-6 shadow-lg shadow-slate-200/50 border border-slate-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-slate-800">Tickets Récents</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">ID</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Description</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Lieu / Immeuble</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Priorité</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Statut</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="ticket in recentTickets" :key="ticket.id" :class="{'bg-red-50/20': ticket.priorite === 'Critique'}">
                            <td class="px-4 py-4 text-sm text-slate-600">{{ ticket.id }}</td>
                            <td class="px-4 py-4 text-sm text-slate-600">{{ ticket.description }}</td>
                            <td class="px-4 py-4 text-sm text-slate-600">{{ ticket.lieu }} ({{ ticket.batiment }})</td>
                            <td class="px-4 py-4">
                                <span :class="[
                                    'px-3 py-1 rounded-full text-xs font-semibold text-white',
                                    ticket.priorite === 'Critique' ? 'bg-red-500' :
                                    ticket.priorite === 'Haute' ? 'bg-amber-500' : 'bg-slate-500'
                                ]">
                                    {{ ticket.priorite }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <span :class="[
                                    'px-3 py-1 rounded-full text-xs font-semibold',
                                    ticket.statut === 'En cours' ? 'bg-amber-100 text-amber-700' :
                                    ticket.statut === 'Assigné' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700'
                                ]">
                                    {{ ticket.statut }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <button @click="manageTicket(ticket)" class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">Gérer</button>
                            </td>
                        </tr>
                        <tr v-if="recentTickets.length === 0">
                            <td colspan="6" class="px-4 py-8 text-center text-slate-400 italic">Aucun ticket enregistré</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Chart } from 'chart.js/auto';

const ticketsCategoryChart = ref(null);
const resolutionTimeChart = ref(null);

let ticketsCategoryChartInstance = null;
let resolutionTimeChartInstance = null;

const tickets = ref([]);

const openTicketsCount = computed(() => tickets.value.filter(t => t.statut !== 'Résolu').length);
const criticalTicketsCount = computed(() => tickets.value.filter(t => t.priorite === 'Critique' && t.statut !== 'Résolu').length);

const budgetTotal = computed(() => 18500);
const budgetUsed = computed(() => {
    const resolved = tickets.value.filter(t => t.statut === 'Résolu').length;
    const active = tickets.value.filter(t => t.statut !== 'Résolu').length;
    return (resolved * 350) + (active * 150) || 12000;
});

const criticalTickets = computed(() => tickets.value.filter(t => t.priorite === 'Critique' && t.statut !== 'Résolu'));
const recentTickets = computed(() => tickets.value.slice(0, 10));

const formatCurrency = (val) => {
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR', maximumFractionDigits: 0 }).format(val);
};

const manageTicket = (ticket) => {
    alert(`Gestion du ticket ${ticket.id} (${ticket.description}) en cours...`);
};

const renderCharts = () => {
    if (ticketsCategoryChartInstance) {
        ticketsCategoryChartInstance.destroy();
    }
    if (resolutionTimeChartInstance) {
        resolutionTimeChartInstance.destroy();
    }

    // 1. Calculate categories from scoped tickets
    const categoriesCount = { Plomberie: 0, Electricite: 0, CVC: 0, Autres: 0 };
    tickets.value.forEach(t => {
        const cat = t.categorie;
        if (cat === 'Plomberie') categoriesCount.Plomberie++;
        else if (cat === 'Électricité' || cat === 'Electricite') categoriesCount.Electricite++;
        else if (cat === 'CVC' || cat === 'Climatisation' || cat === 'Ascenseur') categoriesCount.CVC++;
        else categoriesCount.Autres++;
    });

    const dataValues = [
        categoriesCount.Plomberie || 5,
        categoriesCount.Electricite || 3,
        categoriesCount.CVC || 4,
        categoriesCount.Autres || 2
    ];

    const categoryCtx = document.getElementById('ticketsCategoryChart');
    if (categoryCtx) {
        ticketsCategoryChartInstance = new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: ['Plomberie', 'Électricité', 'CVC & Ascenseur', 'Autres'],
                datasets: [{
                    data: dataValues,
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.9)',
                        'rgba(245, 158, 11, 0.9)',
                        'rgba(16, 185, 129, 0.9)',
                        'rgba(100, 116, 139, 0.9)'
                    ],
                    borderColor: ['#3b82f6', '#f59e0b', '#10b981', '#64748b'],
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            color: '#64748b',
                            font: { size: 11 }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    }

    const resolutionCtx = document.getElementById('resolutionTimeChart');
    if (resolutionCtx) {
        const gradient = resolutionCtx.getContext('2d').createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.4)');
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

        resolutionTimeChartInstance = new Chart(resolutionCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
                datasets: [{
                    label: 'Résolution (jours)',
                    data: [3.2, 3.0, 2.8, 2.6, 2.5, 2.4],
                    borderColor: '#3b82f6',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        ticks: { color: '#64748b' }
                    },
                    x: { ticks: { color: '#64748b' } }
                }
            }
        });
    }
};

const realMaintenanceBuildings = ref([]);
const realMaintenanceLogements = ref([]);
const loadingReal = ref(false);

const fetchRealMaintenanceData = async () => {
    loadingReal.value = true;
    try {
        const [resBats, resLogs] = await Promise.all([
            fetch('/api/batiments'),
            fetch('/api/logements')
        ]);
        if (resBats.ok) {
            const bats = await resBats.json();
            realMaintenanceBuildings.value = bats.filter(b => b.statut === 'Maintenance');
        }
        if (resLogs.ok) {
            const logs = await resLogs.json();
            realMaintenanceLogements.value = logs.filter(l => l.statut === 'Maintenance');
        }

        // Dynamically add to tickets.value
        tickets.value = tickets.value.filter(t => !t.isReal);

        // Add real buildings in maintenance
        realMaintenanceBuildings.value.forEach(b => {
            tickets.value.unshift({
                id: `MNT-BAT-${b.id}`,
                description: `Bâtiment complet sous travaux / maintenance`,
                lieu: b.nom,
                batiment: b.nom,
                priorite: 'Critique',
                statut: 'En cours',
                categorie: 'CVC',
                isReal: true
            });
        });

        // Add real lodgings in maintenance
        realMaintenanceLogements.value.forEach(l => {
            tickets.value.unshift({
                id: `MNT-LOG-${l.id}`,
                description: `Logement sous maintenance : ${l.reference} (${l.categorie || 'Appartement'})`,
                lieu: l.reference,
                batiment: l.batiment || 'Indépendant',
                priorite: 'Haute',
                statut: 'En cours',
                categorie: 'Plomberie',
                isReal: true
            });
        });

        // Re-render charts
        renderCharts();

    } catch (e) {
        console.error("Error fetching real maintenance data:", e);
    } finally {
        loadingReal.value = false;
    }
};

onMounted(async () => {
    const storedTickets = localStorage.getItem('immobilier_tickets');
    if (storedTickets) {
        tickets.value = JSON.parse(storedTickets);
    } else {
        const defaultTickets = [
            { id: '#MT-089', description: 'Ascenseur en panne', lieu: 'Immeuble A', batiment: 'Immeuble A', priorite: 'Critique', statut: 'En cours', categorie: 'Ascenseur' },
            { id: '#MT-088', description: "Fuite d'eau", lieu: 'Suite 305', batiment: 'Hôtel Principal', priorite: 'Critique', statut: 'Assigné', categorie: 'Plomberie' },
            { id: '#MT-087', description: 'Climatisation défaillante', lieu: 'Bureau 204', batiment: 'Immeuble B', priorite: 'Haute', statut: 'Assigné', categorie: 'CVC' },
            { id: '#MT-086', description: 'Remplacement ampoule', lieu: 'Couloir 3', batiment: 'Immeuble A', priorite: 'Normale', statut: 'Résolu', categorie: 'Électricité' },
            { id: '#MT-085', description: 'Nettoyage gouttières', lieu: 'Immeuble C', batiment: 'Immeuble C', priorite: 'Normale', statut: 'Résolu', categorie: 'Autres' }
        ];
        tickets.value = defaultTickets;
        localStorage.setItem('immobilier_tickets', JSON.stringify(defaultTickets));
    }

    renderCharts();
    await fetchRealMaintenanceData();
});

onUnmounted(() => {
    if (ticketsCategoryChartInstance) ticketsCategoryChartInstance.destroy();
    if (resolutionTimeChartInstance) resolutionTimeChartInstance.destroy();
});
</script>
