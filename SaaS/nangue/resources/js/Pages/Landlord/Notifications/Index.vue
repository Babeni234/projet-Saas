<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

defineProps({
    logs: { type: Array, default: () => [] },
});

function resend(id) { useForm({}).post(route('landlord.notifications.resend', id), { preserveScroll: true }); }
</script>

<template>
    <Head title="Notifications" />
    <PropertyLayout role="bailleur" title="Historique des notifications" subtitle="Suivez les rappels et notifications envoyés">
        <div v-if="logs.length === 0" class="text-center py-12 text-gray-500">Aucune notification.</div>
        <div v-for="l in logs" :key="l.id" class="bg-white rounded-lg shadow p-4 mb-3">
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-semibold">{{ l.subject }}</p>
                    <p class="text-sm text-gray-500">{{ l.type }} - {{ l.recipient }}</p>
                    <p v-if="l.sent_at" class="text-xs text-gray-400 mt-1">Envoyé le {{ new Date(l.sent_at).toLocaleString() }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <span :class="['px-2 py-1 text-xs rounded-full',
                        l.status === 'envoye' ? 'bg-green-100 text-green-800' :
                        l.status === 'renvoye' ? 'bg-blue-100 text-blue-800' :
                        'bg-red-100 text-red-800']">
                        {{ l.status }}
                    </span>
                    <button @click="resend(l.id)" class="text-xs text-indigo-600 hover:text-indigo-800">Renvoyer</button>
                </div>
            </div>
        </div>
    </PropertyLayout>
</template>
