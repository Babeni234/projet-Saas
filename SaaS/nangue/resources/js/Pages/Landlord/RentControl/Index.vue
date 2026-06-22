<script setup>
import { Head } from '@inertiajs/vue3';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

defineProps({
    zones: { type: Array, default: () => [] },
    compliance: { type: Array, default: () => [] },
});
</script>

<template>
    <Head title="Encadrement des loyers" />
    <PropertyLayout role="bailleur" title="Encadrement des loyers" subtitle="Vérifiez la conformité de vos loyers">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold mb-4">Zones d'encadrement</h3>
                <div v-if="zones.length === 0" class="text-gray-500">Aucune zone définie.</div>
                <div v-for="z in zones" :key="z.id" class="border-b py-2">
                    <p class="font-medium">{{ z.city }} - Zone {{ z.zone }}</p>
                    <p class="text-sm text-gray-500">Prix max: {{ z.max_price_per_sqm }}€/m²</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold mb-4">Conformité de vos biens</h3>
                <div v-if="compliance.length === 0" class="text-gray-500">Aucune vérification.</div>
                <div v-for="c in compliance" :key="c.id" class="border-b py-2">
                    <p class="font-medium">{{ c.property?.title }}</p>
                    <span :class="['text-xs px-2 py-1 rounded-full',
                        c.is_compliant ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800']">
                        {{ c.is_compliant ? 'Conforme' : 'Non conforme' }}
                    </span>
                    <p v-if="c.excess_amount > 0" class="text-sm text-red-600">Excès: {{ c.excess_amount }}€/m²</p>
                </div>
            </div>
        </div>
    </PropertyLayout>
</template>
