<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

defineProps({
    subscriptions: { type: Array, default: () => [] },
    contracts: { type: Array, default: () => [] },
    paymentMethods: { type: Array, default: () => [] },
});
</script>

<template>
    <Head title="Prélèvements" />
    <PropertyLayout role="bailleur" title="Prélèvements automatiques" subtitle="Gérez les mandats SEPA et cartes">
        <template #actions>
            <Link :href="route('landlord.subscriptions.create')" class="imo-btn-primary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Nouveau prélèvement
            </Link>
        </template>
        <div v-if="subscriptions.length === 0" class="text-center py-12 text-gray-500">Aucun prélèvement.</div>
        <div v-for="s in subscriptions" :key="s.id" class="bg-white rounded-lg shadow p-4 mb-3">
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-semibold">{{ s.contract?.property?.title }}</p>
                    <p class="text-sm text-gray-500">{{ s.amount }}€ / {{ s.frequency }} - Prochain: {{ s.next_payment_date ? new Date(s.next_payment_date).toLocaleDateString() : '-' }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <span :class="['px-2 py-1 text-xs rounded-full',
                        s.status === 'actif' ? 'bg-green-100 text-green-800' :
                        s.status === 'annule' ? 'bg-red-100 text-red-800' :
                        'bg-gray-100 text-gray-800']">
                        {{ s.status === 'actif' ? 'Actif' : 'Annulé' }}
                    </span>
                    <Link v-if="s.status === 'actif'" :href="route('landlord.subscriptions.cancel', s.id)" method="post" as="button" class="text-sm text-red-600 hover:text-red-800">Annuler</Link>
                </div>
            </div>
        </div>
    </PropertyLayout>
</template>
