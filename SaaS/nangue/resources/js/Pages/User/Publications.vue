<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

const props = defineProps({
    properties: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({}) },
});

const search = ref('');
const filterType = ref('all');
const filterStatus = ref('all');
const viewMode = ref('list');

const filteredProperties = computed(() => {
    return props.properties.filter(p => {
        const q = search.value.toLowerCase();
        const matchesSearch = !q ||
            (p.title || '').toLowerCase().includes(q) ||
            (p.address || '').toLowerCase().includes(q) ||
            (p.city || '').toLowerCase().includes(q);
        const matchesType = filterType.value === 'all' || (p.property_type || p.type) === filterType.value;
        const matchesStatus = filterStatus.value === 'all' || p.status === filterStatus.value;
        return matchesSearch && matchesType && matchesStatus;
    });
});

const propertyTypes = [
    { value: 'all', label: 'Tous les types' },
    { value: 'apartment', label: 'Appartement' },
    { value: 'house', label: 'Maison' },
    { value: 'studio', label: 'Studio' },
    { value: 'loft', label: 'Loft' },
    { value: 'villa', label: 'Villa' },
];

const statuses = [
    { value: 'all', label: 'Tous les statuts' },
    { value: 'active', label: 'Actif' },
    { value: 'rented', label: 'Loué' },
    { value: 'pending', label: 'En attente' },
    { value: 'inactive', label: 'Inactif' },
];
</script>

<template>
    <Head title="Mes publications" />

    <PropertyLayout
        role="particulier"
        title="Mes publications"
        subtitle="Gérez vos annonces et publications"
    >
        <template #actions>
            <Link
                :href="route('immo.property.create')"
                class="imo-btn-primary"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouvelle publication
            </Link>
        </template>

        <div class="imo-page">
            <div class="imo-stats-grid-4 mb-8">
                <div class="imo-stat-card">
                    <div class="imo-stat-icon imo-stat-icon-blue">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="imo-stat-value">{{ properties.length }}</p>
                        <p class="imo-stat-label">Publications totales</p>
                    </div>
                </div>

                <div class="imo-stat-card">
                    <div class="imo-stat-icon imo-stat-icon-emerald">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    <div>
                        <p class="imo-stat-value">{{ properties.filter(p => p.status === 'active').length }}</p>
                        <p class="imo-stat-label">Actives</p>
                    </div>
                </div>

                <div class="imo-stat-card">
                    <div class="imo-stat-icon imo-stat-icon-violet">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="imo-stat-value">{{ properties.filter(p => p.status === 'rented').length }}</p>
                        <p class="imo-stat-label">Louées</p>
                    </div>
                </div>

                <div class="imo-stat-card">
                    <div class="imo-stat-icon imo-stat-icon-amber">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="imo-stat-value">{{ properties.filter(p => p.status === 'pending').length }}</p>
                        <p class="imo-stat-label">En attente</p>
                    </div>
                </div>
            </div>

            <div class="imo-filters-bar">
                <div class="flex flex-1 items-center gap-4">
                    <div class="relative flex-1 max-w-md">
                        <svg class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Rechercher une publication..."
                            class="imo-search-input"
                        />
                    </div>

                    <select v-model="filterType" class="imo-form-select-sm">
                        <option v-for="type in propertyTypes" :key="type.value" :value="type.value">
                            {{ type.label }}
                        </option>
                    </select>

                    <select v-model="filterStatus" class="imo-form-select-sm">
                        <option v-for="status in statuses" :key="status.value" :value="status.value">
                            {{ status.label }}
                        </option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
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
                </div>
            </div>

            <div v-if="properties.length">
                <div v-if="filteredProperties.length === 0" class="imo-empty-state">
                    <p>Aucune publication ne correspond à votre recherche.</p>
                </div>
                <div v-else-if="viewMode === 'list'" class="space-y-4">
                    <div
                        v-for="property in filteredProperties"
                        :key="property.id"
                        class="imo-property-card"
                    >
                        <div class="flex gap-4">
                            <div class="relative h-32 w-48 shrink-0 overflow-hidden rounded-lg bg-slate-100">
                                <img
                                    :src="property.image || '/placeholder-property.jpg'"
                                    :alt="property.title"
                                    class="h-full w-full object-cover"
                                />
                                <span
                                    :class="{
                                        'imo-badge-active': property.status === 'active',
                                        'imo-badge-rented': property.status === 'rented',
                                        'imo-badge-pending': property.status === 'pending',
                                        'imo-badge-inactive': property.status === 'inactive',
                                    }"
                                    class="absolute left-2 top-2 rounded-full px-2 py-1 text-xs font-semibold"
                                >
                                    {{ property.status === 'active' ? 'Actif' : property.status === 'rented' ? 'Loué' : property.status === 'pending' ? 'En attente' : 'Inactif' }}
                                </span>
                            </div>

                            <div class="flex flex-1 flex-col justify-between">
                                <div>
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h3 class="text-lg font-semibold text-slate-900">{{ property.title }}</h3>
                                            <p class="mt-1 text-sm text-slate-500">
                                                {{ property.address }}, {{ property.city }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xl font-bold text-emerald-600">{{ property.price }}€</p>
                                            <p class="text-xs text-slate-500">{{ property.transaction_type === 'rent' ? '/mois' : '' }}</p>
                                        </div>
                                    </div>

                                    <div class="mt-3 flex flex-wrap gap-3 text-sm text-slate-600">
                                        <span class="flex items-center gap-1">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                                            </svg>
                                            {{ property.surface }} m²
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                            </svg>
                                            {{ property.rooms }} p.
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                            {{ property.bedrooms }} ch.
                                        </span>
                                    </div>
                                </div>

                                <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3">
                                    <div class="flex items-center gap-4 text-xs text-slate-500">
                                        <span class="flex items-center gap-1">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            {{ property.views || 0 }} vues
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                            </svg>
                                            {{ property.inquiries || 0 }} demandes
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ property.created_at }}
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <Link :href="route('immo.property.show', property.id)" class="imo-btn-sm imo-btn-sm-secondary">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Voir
                                        </Link>
                                        <Link :href="route('immo.property.edit', property.id)" class="imo-btn-sm imo-btn-sm-secondary">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Modifier
                                        </Link>
                                        <Link :href="route('immo.property.destroy', property.id)" method="delete" class="imo-btn-sm imo-btn-sm-danger">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Supprimer
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                    <div
                        v-for="property in filteredProperties"
                        :key="property.id"
                        class="imo-property-card overflow-hidden"
                    >
                        <div class="relative h-44 overflow-hidden bg-slate-100">
                            <img
                                :src="property.image || '/placeholder-property.jpg'"
                                :alt="property.title"
                                class="h-full w-full object-cover"
                            />
                            <span
                                :class="{
                                    'imo-badge-active': property.status === 'active',
                                    'imo-badge-rented': property.status === 'rented',
                                    'imo-badge-pending': property.status === 'pending',
                                    'imo-badge-inactive': property.status === 'inactive',
                                }"
                                class="absolute left-2 top-2 rounded-full px-2 py-1 text-xs font-semibold"
                            >
                                {{ property.status === 'active' ? 'Actif' : property.status === 'rented' ? 'Loué' : property.status === 'pending' ? 'En attente' : 'Inactif' }}
                            </span>
                        </div>
                        <div class="space-y-3 p-4">
                            <div>
                                <p class="text-sm text-slate-500">{{ property.address }}, {{ property.city }}</p>
                                <h3 class="text-lg font-semibold text-slate-900">{{ property.title }}</h3>
                            </div>
                            <div class="flex items-center justify-between gap-4 text-sm text-slate-600">
                                <span>{{ property.surface }} m²</span>
                                <span>{{ property.rooms }} p.</span>
                                <span>{{ property.bedrooms }} ch.</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xl font-bold text-emerald-600">{{ property.price }}€</p>
                                    <p class="text-xs text-slate-500">{{ property.transaction_type === 'rent' ? '/mois' : '' }}</p>
                                </div>
                                <Link :href="route('immo.property.show', property.id)" class="imo-btn-sm imo-btn-sm-primary">Voir</Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="imo-empty-state-lg">
                <svg class="mx-auto h-16 w-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <p class="mt-6 text-center text-sm text-slate-500">Aucune publication trouvée. Ajoutez votre première annonce.</p>
            </div>
        </div>
    </PropertyLayout>
</template>
