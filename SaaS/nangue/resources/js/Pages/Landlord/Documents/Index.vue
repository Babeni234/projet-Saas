<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

defineProps({
    documents: { type: Array, default: () => [] },
    categories: { type: Array, default: () => [] },
});
</script>

<template>
    <Head title="Documents" />
    <PropertyLayout role="bailleur" title="Dossier de location" subtitle="Gérez les documents de vos biens et locataires">
        <div class="space-y-4">
            <div v-if="documents.length === 0" class="text-center py-12 text-gray-500">Aucun document.</div>
            <div v-for="d in documents" :key="d.id" class="bg-white rounded-lg shadow p-4 flex items-start justify-between">
                <div>
                    <h3 class="font-semibold">{{ d.name }}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ d.category?.name }} - {{ d.file_type }}</p>
                    <p v-if="d.expires_at" class="text-xs text-orange-600 mt-1">Expire le {{ new Date(d.expires_at).toLocaleDateString() }}</p>
                </div>
                <span :class="['px-2 py-1 text-xs font-semibold rounded-full',
                    d.verified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800']">
                    {{ d.verified ? 'Vérifié' : 'En attente' }}
                </span>
            </div>
        </div>
    </PropertyLayout>
</template>
