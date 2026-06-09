<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

const props = defineProps({
    visits: { type: Array, default: () => [] },
    todayVisits: { type: Array, default: () => [] },
});

const search = ref('');
const filterStatus = ref('all');
const filterSlot = ref('all');
const viewMode = ref('calendar');

const filteredVisits = computed(() => {
    return props.visits.filter(v => {
        const q = search.value.toLowerCase();
        const matchesSearch = !q ||
            (v.property_name || '').toLowerCase().includes(q) ||
            (v.visitor_name || '').toLowerCase().includes(q) ||
            (v.property_address || '').toLowerCase().includes(q);
        const matchesStatus = filterStatus.value === 'all' || v.status === filterStatus.value;
        let matchesSlot = true;
        if (filterSlot.value !== 'all') {
            const hour = parseInt((v.time || '0:00').split(':')[0], 10);
            if (filterSlot.value === 'morning') matchesSlot = hour >= 8 && hour < 12;
            else if (filterSlot.value === 'afternoon') matchesSlot = hour >= 12 && hour < 18;
            else if (filterSlot.value === 'evening') matchesSlot = hour >= 18 && hour < 20;
        }
        return matchesSearch && matchesStatus && matchesSlot;
    });
});

const visitStatuses = [
    { value: 'all', label: 'Toutes les visites' },
    { value: 'scheduled', label: 'Planifiées' },
    { value: 'completed', label: 'Terminées' },
    { value: 'cancelled', label: 'Annulées' },
];

const timeSlots = [
    { value: 'all', label: 'Tous les créneaux' },
    { value: 'morning', label: 'Matin (8h-12h)' },
    { value: 'afternoon', label: 'Après-midi (12h-18h)' },
    { value: 'evening', label: 'Soir (18h-20h)' },
];
</script>

<template>
    <Head title="Calendrier visites" />

    <PropertyLayout
        role="bailleur"
        title="Calendrier visites"
        subtitle="Gérez les créneaux de visite et les rappels"
    >
        <template #actions>
            <Link :href="route('landlord.calendar.create')" class="imo-btn-primary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouvelle visite
            </Link>
        </template>

        <div class="imo-page">
            <!-- Visites du jour -->
            <div v-if="viewMode === 'calendar'">
                <div v-if="todayVisits.length" class="mb-6">
                    <h3 class="mb-4 text-lg font-semibold text-slate-900">Visites du jour</h3>
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="visit in todayVisits"
                        :key="visit.id"
                        class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm"
                    >
                        <div class="mb-3 flex items-start justify-between">
                            <span
                                class="rounded-full px-2 py-1 text-xs font-semibold"
                                :class="{
                                    'bg-emerald-100 text-emerald-800': visit.status === 'scheduled',
                                    'bg-blue-100 text-blue-800': visit.status === 'completed',
                                    'bg-red-100 text-red-800': visit.status === 'cancelled',
                                }"
                            >
                                {{ visit.status === 'scheduled' ? 'Planifiée' : visit.status === 'completed' ? 'Terminée' : 'Annulée' }}
                            </span>
                            <span class="text-sm font-semibold text-slate-900">{{ visit.time }}</span>
                        </div>
                        <h4 class="font-semibold text-slate-900">{{ visit.property_name }}</h4>
                        <p class="mt-1 text-sm text-slate-500">{{ visit.property_address }}</p>
                        <div class="mt-3 flex items-center gap-2">
                            <div class="h-8 w-8 rounded-full bg-slate-200">
                                <img
                                    v-if="visit.visitor_avatar"
                                    :src="visit.visitor_avatar"
                                    :alt="visit.visitor_name"
                                    class="h-full w-full rounded-full object-cover"
                                />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-900">{{ visit.visitor_name }}</p>
                                <p class="text-xs text-slate-500">{{ visit.visitor_phone }}</p>
                            </div>
                        </div>
                        <div class="mt-4 flex gap-2">
                            <button class="imo-btn-sm imo-btn-sm-secondary flex-1">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                Appeler
                            </button>
                            <button class="imo-btn-sm imo-btn-sm-secondary flex-1">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Message
                            </button>
                        </div>
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
                            placeholder="Rechercher une visite..."
                            class="imo-search-input"
                        />
                    </div>

                    <select v-model="filterStatus" class="imo-form-select-sm">
                        <option v-for="status in visitStatuses" :key="status.value" :value="status.value">
                            {{ status.label }}
                        </option>
                    </select>

                    <select v-model="filterSlot" class="imo-form-select-sm">
                        <option v-for="slot in timeSlots" :key="slot.value" :value="slot.value">
                            {{ slot.label }}
                        </option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button
                        type="button"
                        @click.prevent="viewMode = 'calendar'"
                        :class="['imo-btn-icon', { 'imo-btn-icon-active': viewMode === 'calendar' }]"
                        title="Vue calendrier"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
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

            <!-- Liste des visites -->
            <div v-if="viewMode === 'calendar'">
            <!-- Contenu Principal -->
            <div v-if="viewMode === 'calendar'" class="space-y-6">
                <!-- Liste des visites sous forme de tableau -->
                <div v-if="filteredVisits.length === 0" class="imo-empty-state">
                    <p>Aucune visite ne correspond à votre recherche.</p>
                </div>
                <div v-else-if="visits.length" class="imo-table-wrap">
                    <div class="overflow-x-auto">
                        <table class="imo-table w-full min-w-[900px]">
                            <thead>
                                <tr>
                                    <th>Date & heure</th>
                                    <th>Bien</th>
                                    <th>Visiteur</th>
                                    <th>Contact</th>
                                    <th>Statut</th>
                                    <th>Notes</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="visit in filteredVisits"
                                    :key="visit.id"
                                    class="hover:bg-slate-50"
                                >
                                    <td>
                                        <p class="font-semibold text-slate-900">{{ visit.date }}</p>
                                        <p class="text-sm text-slate-500">{{ visit.time }}</p>
                                    </td>
                                    <td>
                                        <p class="font-medium text-slate-900">{{ visit.property_name }}</p>
                                        <p class="text-xs text-slate-500">{{ visit.property_address }}</p>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="h-8 w-8 rounded-full bg-slate-200">
                                                <img
                                                    v-if="visit.visitor_avatar"
                                                    :src="visit.visitor_avatar"
                                                    :alt="visit.visitor_name"
                                                    class="h-full w-full rounded-full object-cover"
                                                />
                                            </div>
                                            <p class="font-medium text-slate-900">{{ visit.visitor_name }}</p>
                                        </div>
                                    </td>
                                    <td class="text-sm text-slate-600">{{ visit.visitor_phone }}</td>
                                    <td>
                                        <span
                                            class="rounded-full px-2 py-1 text-xs font-semibold"
                                            :class="{
                                                'bg-emerald-100 text-emerald-800': visit.status === 'scheduled',
                                                'bg-blue-100 text-blue-800': visit.status === 'completed',
                                                'bg-red-100 text-red-800': visit.status === 'cancelled',
                                            }"
                                        >
                                            {{ visit.status === 'scheduled' ? 'Planifiée' : visit.status === 'completed' ? 'Terminée' : 'Annulée' }}
                                        </span>
                                    </td>
                                    <td class="text-sm text-slate-600 max-w-xs truncate">{{ visit.notes || '-' }}</td>
                                    <td class="text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <Link
                                                :href="route('landlord.calendar.edit', visit.id)"
                                                class="imo-btn-icon-sm"
                                                title="Modifier"
                                            >
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </Link>
                                            <Link
                                                :href="route('landlord.calendar.cancel', visit.id)"
                                                method="post"
                                                class="imo-btn-icon-sm"
                                                title="Annuler"
                                            >
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </Link>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div v-else class="imo-empty-state-lg">
                    <svg class="mx-auto h-16 w-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">Aucune visite planifiée</h3>
                    <p class="mt-2 text-sm text-slate-500">Planifiez votre première visite pour commencer à gérer vos créneaux.</p>
                    <Link :href="route('landlord.calendar.create')" class="imo-btn-primary mt-4">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Planifier une visite
                    </Link>
                </div>
            </div>
        </div>

            <div v-else class="space-y-6">
                <div
                    v-for="visit in filteredVisits"
                    :key="visit.id"
                    class="imo-property-card"
                >
                    <div class="flex gap-4">
                        <div class="relative h-32 w-48 shrink-0 overflow-hidden rounded-lg bg-slate-100">
                            <img
                                v-if="visit.property_image"
                                :src="visit.property_image"
                                :alt="visit.property_name"
                                class="h-full w-full object-cover"
                            />
                        </div>
                        <div class="flex flex-1 flex-col justify-between">
                            <div>
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold text-slate-900">{{ visit.property_name }}</h3>
                                        <p class="mt-1 text-sm text-slate-500">{{ visit.property_address }}</p>
                                    </div>
                                    <span class="rounded-full bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-700">
                                        {{ visit.status === 'scheduled' ? 'Planifiée' : visit.status === 'completed' ? 'Terminée' : 'Annulée' }}
                                    </span>
                                </div>
                                <p class="mt-2 text-sm text-slate-600">{{ visit.visitor_name }} • {{ visit.visitor_phone }}</p>
                            </div>
                            <div class="mt-4 flex items-center justify-between">
                                <span class="text-sm text-slate-500">{{ visit.date }} · {{ visit.time }}</span>
                                <div class="flex items-center gap-2">
                                    <Link :href="route('landlord.calendar.edit', visit.id)" class="imo-btn-sm imo-btn-sm-secondary">Modifier</Link>
                                    <Link :href="route('landlord.calendar.cancel', visit.id)" method="post" class="imo-btn-sm imo-btn-sm-danger">Annuler</Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </PropertyLayout>
</template>
