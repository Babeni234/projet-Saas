<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

defineProps({
    guarantees: { type: Array, default: () => [] },
});
</script>

<template>
    <Head title="Garanties" />
    <PropertyLayout role="bailleur" title="Garanties et Assurances" subtitle="Gérez les Visale, cautions et assurances">
        <div v-if="guarantees.length === 0" class="text-center py-12 text-gray-500">Aucune garantie.</div>
        <div v-for="g in guarantees" :key="g.id" class="bg-white rounded-lg shadow p-4 mb-3">
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-semibold">{{ g.type === 'visale' ? 'Visale' : g.type === 'assurance' ? 'Assurance' : g.type === 'garant' ? 'Garant' : 'Caution solidaire' }}</p>
                    <p class="text-sm text-gray-500">{{ g.provider }} - {{ g.reference_number }}</p>
                    <p v-if="g.covered_amount" class="text-sm text-gray-500">Montant: {{ g.covered_amount }}€</p>
                </div>
                <span :class="['px-2 py-1 text-xs rounded-full',
                    g.status === 'actif' ? 'bg-green-100 text-green-800' :
                    g.status === 'expire' ? 'bg-red-100 text-red-800' :
                    'bg-gray-100 text-gray-800']">
                    {{ g.status }}
                </span>
            </div>
        </div>
    </PropertyLayout>
</template>
