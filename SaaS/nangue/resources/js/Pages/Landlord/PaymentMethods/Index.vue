<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

defineProps({
    methods: { type: Array, default: () => [] },
});

function setDefault(id) { useForm({}).post(route('landlord.payment-methods.default', id), { preserveScroll: true }); }
function destroy(id) { useForm({}).delete(route('landlord.payment-methods.destroy', id), { preserveScroll: true }); }
</script>

<template>
    <Head title="Moyens de paiement" />
    <PropertyLayout role="bailleur" title="Moyens de paiement" subtitle="Gérez vos cartes et IBAN pour les prélèvements">
        <div v-if="methods.length === 0" class="text-center py-12 text-gray-500">Aucun moyen de paiement.</div>
        <div v-for="m in methods" :key="m.id" class="bg-white rounded-lg shadow p-4 mb-3">
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-semibold">{{ m.type === 'card' ? m.brand + ' ****' + m.last_four : 'SEPA IBAN ****' + m.iban_last_four }}</p>
                    <p class="text-sm text-gray-500">{{ m.provider }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <span v-if="m.is_default" class="text-xs bg-indigo-100 text-indigo-800 px-2 py-1 rounded-full">Par défaut</span>
                    <button v-if="!m.is_default" @click="setDefault(m.id)" class="text-sm text-indigo-600">Définir par défaut</button>
                    <button @click="destroy(m.id)" class="text-sm text-red-600 ml-2">Supprimer</button>
                </div>
            </div>
        </div>
    </PropertyLayout>
</template>
