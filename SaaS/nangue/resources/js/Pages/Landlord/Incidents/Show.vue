<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

defineProps({
    incident: { type: Object, required: true },
});
</script>

<template>
    <Head :title="incident.title" />
    <PropertyLayout role="bailleur" title="Détail de l'incident" subtitle="Suivi et résolution">
        <div class="bg-white rounded-lg shadow p-6 max-w-3xl">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h2 class="text-2xl font-bold">{{ incident.title }}</h2>
                    <p class="text-gray-500 mt-1">{{ incident.property?.title }}</p>
                </div>
                <span :class="['px-3 py-1 text-sm font-semibold rounded-full',
                    incident.status === 'ouvert' ? 'bg-red-100 text-red-800' :
                    incident.status === 'en_cours' ? 'bg-yellow-100 text-yellow-800' :
                    incident.status === 'resolu' ? 'bg-green-100 text-green-800' :
                    'bg-gray-100 text-gray-800']">
                    {{ incident.status === 'en_cours' ? 'En cours' : incident.status }}
                </span>
            </div>
            <div class="prose max-w-none mb-6">
                <p>{{ incident.description }}</p>
            </div>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><span class="font-medium">Catégorie:</span> {{ incident.category }}</div>
                <div><span class="font-medium">Urgence:</span> {{ incident.urgency }}</div>
                <div><span class="font-medium">Créé le:</span> {{ new Date(incident.created_at).toLocaleDateString() }}</div>
                <div v-if="incident.resolved_at"><span class="font-medium">Résolu le:</span> {{ new Date(incident.resolved_at).toLocaleDateString() }}</div>
            </div>
            <div v-if="incident.comments" class="mt-6 border-t pt-4">
                <h3 class="font-semibold mb-2">Commentaires</h3>
                <div v-for="comment in incident.comments" :key="comment.id" class="bg-gray-50 rounded p-3 mb-2">
                    <p class="text-sm">{{ comment.content }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ comment.user?.name }} - {{ new Date(comment.created_at).toLocaleString() }}</p>
                </div>
            </div>
        </div>
    </PropertyLayout>
</template>
