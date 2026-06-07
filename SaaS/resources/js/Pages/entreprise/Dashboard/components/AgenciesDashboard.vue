<template>
    <div class="space-y-6">
        <!-- Statistics Row -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg shadow-blue-500/30 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Agences</p>
                        <p class="text-3xl font-bold mt-2">{{ stats.total }}</p>
                    </div>
                    <svg class="w-12 h-12 text-blue-300 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg shadow-green-500/30 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Actives</p>
                        <p class="text-3xl font-bold mt-2">{{ stats.active }}</p>
                    </div>
                    <svg class="w-12 h-12 text-green-300 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-br from-gray-500 to-gray-600 rounded-2xl shadow-lg shadow-gray-500/30 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-100 text-sm font-medium">Inactives</p>
                        <p class="text-3xl font-bold mt-2">{{ stats.inactive }}</p>
                    </div>
                    <svg class="w-12 h-12 text-gray-300 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl shadow-lg shadow-red-500/30 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm font-medium">Suspendues</p>
                        <p class="text-3xl font-bold mt-2">{{ stats.suspended }}</p>
                    </div>
                    <svg class="w-12 h-12 text-red-300 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Recent Agencies Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 overflow-hidden">
            <div class="p-6 border-b border-slate-200/50 flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-900">Agences Récentes</h2>
                <RouterLink :to="{ name: 'dashboard.agencies' }" class="text-indigo-600 hover:text-indigo-700 font-medium text-sm">Voir tout →</RouterLink>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200/50 bg-slate-50/50">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Agence</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Contact</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Localisation</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Responsable</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Employés</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Statut</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/50">
                        <tr v-for="agency in recentAgencies" :key="agency.id" class="hover:bg-slate-50/50 transition-all duration-200">
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-900">{{ agency.name }}</div>
                                <div class="text-xs text-slate-500">{{ agency.code }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-600">{{ agency.email || '-' }}</div>
                                <div class="text-sm text-slate-600">{{ agency.phone || '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-600">{{ agency.city || '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-600">{{ agency.manager_name || '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 bg-slate-100 text-slate-800 rounded-full text-xs font-semibold">{{ agency.employee_count || 0 }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    :class="[
                                        'inline-block px-3 py-1 rounded-full text-xs font-semibold',
                                        agency.status === 'active' ? 'bg-green-100 text-green-800' :
                                        agency.status === 'inactive' ? 'bg-gray-100 text-gray-800' :
                                        'bg-red-100 text-red-800'
                                    ]"
                                >
                                    {{ agency.status === 'active' ? 'Actif' : agency.status === 'inactive' ? 'Inactif' : 'Suspendu' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <button type="button" @click="viewAgency(agency.id)" class="text-indigo-600 hover:text-indigo-700 font-medium text-xs">Voir</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Agences par Statut</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <span class="text-green-700 font-medium">Actives</span>
                        <span class="text-green-700 font-bold text-lg">{{ stats.active }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-gray-700 font-medium">Inactives</span>
                        <span class="text-gray-700 font-bold text-lg">{{ stats.inactive }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                        <span class="text-red-700 font-medium">Suspendues</span>
                        <span class="text-red-700 font-bold text-lg">{{ stats.suspended }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Actions Rapides</h3>
                <div class="space-y-3">
                    <RouterLink :to="{ name: 'dashboard.agencies' }" class="block w-full px-4 py-3 bg-gradient-to-r from-indigo-500 to-violet-600 text-white rounded-lg hover:shadow-lg hover:shadow-indigo-500/30 transition-all duration-200 font-medium text-center">
                        <span class="inline-flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Gérer les Agences
                        </span>
                    </RouterLink>
                    <button type="button" @click="createAgency" class="block w-full px-4 py-3 bg-white border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-all duration-200 font-medium text-center">
                        <span class="inline-flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Nouvelle Agence
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { RouterLink } from 'vue-router';
import { router as inertiaRouter } from '@inertiajs/vue3';

const viewAgency = (id) => inertiaRouter.visit(`/agencies/${id}`);
const createAgency = () => inertiaRouter.visit('/agencies/create');

const stats = ref({
    total: 0,
    active: 0,
    inactive: 0,
    suspended: 0,
});

const agencies = ref([]);

const recentAgencies = computed(() => {
    return agencies.value.slice(0, 5);
});

const loadStatistics = async () => {
    try {
        const response = await fetch('/agencies/statistics');
        stats.value = await response.json();
    } catch (error) {
        console.error('Error loading statistics:', error);
    }
};

const loadAgencies = async () => {
    try {
        const response = await fetch('/agencies?per_page=5');
        const data = await response.json();
        agencies.value = data.agencies?.data || [];
    } catch (error) {
        console.error('Error loading agencies:', error);
    }
};

onMounted(() => {
    loadStatistics();
    loadAgencies();
});
</script>
