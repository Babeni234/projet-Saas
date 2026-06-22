<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

defineProps({
    inspection: { type: Object, required: true },
});
</script>

<template>
    <Head title="État des lieux" />
    <PropertyLayout role="bailleur" title="Détail de l'état des lieux" subtitle="Consultez les informations">
        <div class="bg-white rounded-lg shadow p-6 max-w-3xl">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h2 class="text-xl font-bold">{{ inspection.type === 'entree' ? "État d'entrée" : inspection.type === 'sortie' ? 'État de sortie' : 'État périodique' }}</h2>
                    <p class="text-gray-500">{{ inspection.property?.title }}</p>
                </div>
                <span :class="['px-3 py-1 text-sm rounded-full',
                    inspection.status === 'planifie' ? 'bg-blue-100 text-blue-800' :
                    inspection.status === 'en_cours' ? 'bg-yellow-100 text-yellow-800' :
                    'bg-green-100 text-green-800']">
                    {{ inspection.status === 'planifie' ? 'Planifié' : inspection.status === 'en_cours' ? 'En cours' : 'Terminé' }}
                </span>
            </div>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><span class="font-medium">Date:</span> {{ new Date(inspection.inspection_date).toLocaleDateString() }}</div>
                <div><span class="font-medium">Contrat:</span> #{{ inspection.contract_id }}</div>
            </div>
            <div v-if="inspection.items" class="mt-6 border-t pt-4">
                <h3 class="font-semibold mb-2">Pièces et équipements</h3>
                <div v-for="item in inspection.items" :key="item.id" class="bg-gray-50 rounded p-3 mb-2">
                    <p><span class="font-medium">{{ item.room }}:</span> {{ item.item }} - {{ item.condition }}</p>
                    <p v-if="item.comment" class="text-sm text-gray-500 mt-1">{{ item.comment }}</p>
                </div>
            </div>
        </div>
    </PropertyLayout>
</template>
