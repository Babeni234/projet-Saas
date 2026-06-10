<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

const props = defineProps({
    contracts: { type: Array, default: () => [] },
});

const search = ref('');
const filterStatus = ref('all');
const viewMode = ref('list');

const filteredContracts = computed(() => {
    return props.contracts.filter(c => {
        const q = search.value.toLowerCase();
        const matchesSearch = !q ||
            (c.tenant_name || '').toLowerCase().includes(q) ||
            (c.property_name || '').toLowerCase().includes(q) ||
            (c.tenant_email || '').toLowerCase().includes(q);
        const matchesStatus = filterStatus.value === 'all' || c.status === filterStatus.value;
        return matchesSearch && matchesStatus;
    });
});

const contractStatuses = [
    { value: 'all', label: 'Tous les contrats' },
    { value: 'active', label: 'Actifs' },
    { value: 'pending', label: 'En attente de signature' },
    { value: 'expired', label: 'Expirés' },
    { value: 'terminated', label: 'Résiliés' },
];
</script>

<template>
    <Head title="Contrats & baux" />

    <PropertyLayout
        role="bailleur"
        title="Contrats & baux"
        subtitle="Gérez vos contrats de location et signatures électroniques"
    >
        <template #actions>
            <Link :href="route('landlord.contracts.create')" class="imo-btn-primary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouveau contrat
            </Link>
        </template>

        <div class="imo-page">
            <!-- Statistiques (Identique au dashboard particulier mais pour les contrats) -->
            <div class="imo-stats-grid-4 mb-8">
                <div class="imo-stat-card">
                    <div class="imo-stat-icon imo-stat-icon-blue">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="imo-stat-value">{{ contracts.length }}</p>
                        <p class="imo-stat-label">Total contrats</p>
                    </div>
                </div>

                <div class="imo-stat-card">
                    <div class="imo-stat-icon imo-stat-icon-emerald">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="imo-stat-value">{{ contracts.filter(c => c.status === 'active').length }}</p>
                        <p class="imo-stat-label">Baux actifs</p>
                    </div>
                </div>

                <div class="imo-stat-card">
                    <div class="imo-stat-icon imo-stat-icon-amber">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="imo-stat-value">{{ contracts.filter(c => c.status === 'pending').length }}</p>
                        <p class="imo-stat-label">En signature</p>
                    </div>
                </div>

                <div class="imo-stat-card">
                    <div class="imo-stat-icon imo-stat-icon-slate">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="imo-stat-value">{{ contracts.filter(c => c.status === 'expired').length }}</p>
                        <p class="imo-stat-label">Terminés</p>
                    </div>
                </div>
            </div>

            <!-- Filtres -->
            <div class="imo-filters-bar">
                <div class="flex flex-1 items-center gap-4">
                    <div class="relative flex-1 max-w-md">
                        <svg class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Rechercher un contrat..."
                            class="imo-search-input"
                        />
                    </div>

                    <select v-model="filterStatus" class="imo-form-select-sm">
                        <option v-for="status in contractStatuses" :key="status.value" :value="status.value">
                            {{ status.label }}
                        </option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button
                        type="button"
                        @click.prevent="viewMode = 'list'"
                        :class="['imo-btn-icon', { 'imo-btn-icon-active': viewMode === 'list' }]"
                        title="Vue liste"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                    </button>
                    <button
                        type="button"
                        @click.prevent="viewMode = 'grid'"
                        :class="['imo-btn-icon', { 'imo-btn-icon-active': viewMode === 'grid' }]"
                        title="Vue grille"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Liste des contrats -->
            <div v-if="contracts.length">
                <div v-if="filteredContracts.length === 0" class="imo-empty-state">
                    <p>Aucun contrat ne correspond à votre recherche.</p>
                </div>
                <div v-else-if="viewMode === 'list'" class="imo-table-wrap">
                    <div class="overflow-x-auto">
                        <table class="imo-table w-full min-w-[900px]">
                        <thead>
                            <tr>
                                <th>Locataire</th>
                                <th>Bien</th>
                                <th>Type de bail</th>
                                <th>Durée</th>
                                <th>Loyer</th>
                                <th>Statut</th>
                                <th>Date début</th>
                                <th>Date fin</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="contract in filteredContracts"
                                :key="contract.id"
                                class="hover:bg-slate-50"
                            >
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-full bg-slate-200">
                                            <img
                                                v-if="contract.tenant_avatar"
                                                :src="contract.tenant_avatar"
                                                :alt="contract.tenant_name"
                                                class="h-full w-full rounded-full object-cover"
                                            />
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-900">{{ contract.tenant_name }}</p>
                                            <p class="text-xs text-slate-500">{{ contract.tenant_email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="font-medium text-slate-900">{{ contract.property_name }}</p>
                                    <p class="text-xs text-slate-500">{{ contract.property_address }}</p>
                                </td>
                                <td>
                                    <span class="rounded-full bg-slate-100 px-2 py-1 text-xs font-medium text-slate-700">
                                        {{ contract.lease_type }}
                                    </span>
                                </td>
                                <td class="text-sm text-slate-600">{{ contract.duration }}</td>
                                <td>
                                    <p class="font-semibold text-slate-900">{{ contract.rent }}€</p>
                                    <p class="text-xs text-slate-500">+ {{ contract.charges }}€ charges</p>
                                </td>
                                <td>
                                    <span
                                        class="rounded-full px-2 py-1 text-xs font-semibold"
                                        :class="{
                                            'bg-emerald-100 text-emerald-800': contract.status === 'active',
                                            'bg-amber-100 text-amber-800': contract.status === 'pending',
                                            'bg-red-100 text-red-800': contract.status === 'expired',
                                            'bg-slate-100 text-slate-800': contract.status === 'terminated',
                                        }"
                                    >
                                        {{ contract.status === 'active' ? 'Actif' : contract.status === 'pending' ? 'En attente' : contract.status === 'expired' ? 'Expiré' : 'Résilié' }}
                                    </span>
                                </td>
                                <td class="text-sm text-slate-600">{{ contract.start_date }}</td>
                                <td class="text-sm text-slate-600">{{ contract.end_date }}</td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <Link
                                            :href="route('landlord.contracts.show', contract.id)"
                                            class="imo-btn-icon-sm"
                                            title="Voir"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </Link>
                                        <Link
                                            :href="route('landlord.contracts.show', contract.id)"
                                            class="imo-btn-icon-sm"
                                            title="Télécharger"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                        </Link>
                                        <Link
                                            :href="route('landlord.contracts.edit', contract.id)"
                                            class="imo-btn-icon-sm"
                                            title="Modifier"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                </div>    
                <div v-else class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                    <div
                        v-for="contract in filteredContracts"
                        :key="contract.id"
                        class="imo-property-card"
                    >
                        <div class="space-y-4">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-sm text-slate-500">{{ contract.property_name }}</p>
                                    <h3 class="text-lg font-semibold text-slate-900">{{ contract.tenant_name }}</h3>
                                    <p class="text-sm text-slate-500">{{ contract.tenant_email }}</p>
                                </div>
                                <span class="rounded-full bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-700">{{ contract.lease_type }}</span>
                            </div>
                            <div class="grid gap-2 sm:grid-cols-2 text-sm text-slate-600">
                                <div>
                                    <p class="font-semibold text-slate-900">Durée</p>
                                    <p>{{ contract.duration }}</p>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-900">Loyer</p>
                                    <p>{{ contract.rent }}€</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between gap-4 text-sm text-slate-600">
                                <span>{{ contract.start_date }}</span>
                                <span>{{ contract.end_date }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-2">
                                <Link :href="route('landlord.contracts.show', contract.id)" class="imo-btn-sm imo-btn-sm-primary">Voir</Link>
                                <Link :href="route('landlord.contracts.edit', contract.id)" class="imo-btn-sm imo-btn-sm-secondary">Modifier</Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- État vide -->
            <div v-else class="imo-empty-state-lg">
                <svg class="mx-auto h-16 w-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-4 text-lg font-semibold text-slate-900">Aucun contrat</h3>
                <p class="mt-2 text-sm text-slate-500">Créez votre premier contrat de location pour commencer.</p>
                <Link :href="route('landlord.contracts.create')" class="imo-btn-primary mt-4">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Créer un contrat
                </Link>
            </div>
        
        </div>
    </PropertyLayout>
</template>
