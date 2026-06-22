<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

const props = defineProps({
    deposits: { type: Array, default: () => [] },
    contracts: { type: Array, default: () => [] },
});

const form = useForm({ contract_id: '', amount: '', received_at: '' });
const showForm = ref(false);

function store() {
    form.post(route('landlord.deposits.store'), { preserveScroll: true });
}
</script>

<template>
    <Head title="Cautions" />
    <PropertyLayout role="bailleur" title="Gestion des cautions" subtitle="Suivez les dépôts de garantie">
        <template #actions>
            <button @click="showForm = !showForm" class="imo-btn-primary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Nouvelle caution
            </button>
        </template>
        <form v-if="showForm" @submit.prevent="store" class="bg-white rounded-lg shadow p-6 mb-6 max-w-lg space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Contrat</label>
                <select v-model="form.contract_id" required class="imo-input w-full">
                    <option v-for="c in contracts" :key="c.id" :value="c.id">{{ c.property?.title }} - {{ c.tenant_name }}</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Montant</label>
                <input v-model="form.amount" type="number" step="0.01" required class="imo-input w-full" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Date de réception</label>
                <input v-model="form.received_at" type="date" required class="imo-input w-full" />
            </div>
            <button type="submit" class="imo-btn-primary">Enregistrer</button>
        </form>
        <div v-if="deposits.length === 0" class="text-center py-12 text-gray-500">Aucune caution.</div>
        <div v-for="d in deposits" :key="d.id" class="bg-white rounded-lg shadow p-4 mb-3">
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-semibold">{{ d.contract?.property?.title }}</p>
                    <p class="text-sm text-gray-500">{{ d.amount }}€ - Reçue le {{ new Date(d.received_at).toLocaleDateString() }}</p>
                </div>
                <span :class="['px-2 py-1 text-xs rounded-full',
                    d.status === 'retenu' ? 'bg-blue-100 text-blue-800' :
                    d.status === 'restitué' ? 'bg-green-100 text-green-800' :
                    'bg-red-100 text-red-800']">
                    {{ d.status === 'retenu' ? 'Retenue' : d.status }}
                </span>
            </div>
        </div>
    </PropertyLayout>
</template>
