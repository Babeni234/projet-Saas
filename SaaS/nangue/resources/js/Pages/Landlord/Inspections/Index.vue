<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

defineProps({
    inspections: { type: Array, default: () => [] },
});
</script>

<template>
    <Head title="États des lieux" />
    <PropertyLayout role="bailleur" title="États des lieux" subtitle="Gérez les entrées, sorties et états périodiques">
        <template #actions>
            <Link :href="route('landlord.inspections.create')" class="imo-btn-primary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Nouvel état des lieux
            </Link>
        </template>
        <div v-if="inspections.length === 0" class="text-center py-12 text-gray-500">Aucun état des lieux.</div>
        <div v-for="i in inspections" :key="i.id" class="bg-white rounded-lg shadow p-4 mb-3">
            <div class="flex items-start justify-between">
                <div>
                    <Link :href="route('landlord.inspections.show', i.id)" class="text-lg font-semibold text-gray-900 hover:text-indigo-600">
                        {{ i.type === 'entree' ? "État d'entrée" : i.type === 'sortie' ? 'État de sortie' : 'État périodique' }}
                    </Link>
                    <p class="text-sm text-gray-500 mt-1">{{ i.property?.title }} - {{ new Date(i.inspection_date).toLocaleDateString() }}</p>
                </div>
                <span :class="['px-2 py-1 text-xs font-semibold rounded-full',
                    i.status === 'planifie' ? 'bg-blue-100 text-blue-800' :
                    i.status === 'en_cours' ? 'bg-yellow-100 text-yellow-800' :
                    'bg-green-100 text-green-800']">
                    {{ i.status === 'planifie' ? 'Planifié' : i.status === 'en_cours' ? 'En cours' : 'Terminé' }}
                </span>
            </div>
        </div>
    </PropertyLayout>
</template>
