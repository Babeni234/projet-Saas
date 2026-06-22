<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

defineProps({
    slots: { type: Array, default: () => [] },
});
</script>

<template>
    <Head title="Créneaux de visites" />
    <PropertyLayout role="bailleur" title="Visites en ligne" subtitle="Gérez les créneaux de visite public">
        <div v-if="slots.length === 0" class="text-center py-12 text-gray-500">Aucun créneau.</div>
        <div v-for="s in slots" :key="s.id" class="bg-white rounded-lg shadow p-4 mb-3">
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-semibold">{{ s.property?.title }}</p>
                    <p class="text-sm text-gray-500">{{ new Date(s.date).toLocaleDateString() }} {{ s.start_time }}-{{ s.end_time }}</p>
                    <p class="text-xs text-gray-400">{{ s.booked_count }}/{{ s.max_visitors }} réservé(s)</p>
                </div>
                <span :class="['px-2 py-1 text-xs rounded-full',
                    s.status === 'disponible' ? 'bg-green-100 text-green-800' :
                    s.status === 'complet' ? 'bg-orange-100 text-orange-800' :
                    'bg-red-100 text-red-800']">
                    {{ s.status }}
                </span>
            </div>
        </div>
    </PropertyLayout>
</template>
