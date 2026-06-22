<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

const props = defineProps({
    properties: { type: Array, default: () => [] },
    contracts: { type: Array, default: () => [] },
});

const form = useForm({ property_id: '', contract_id: '', type: 'entree', inspection_date: '' });

function store() { form.post(route('landlord.inspections.store')); }
</script>

<template>
    <Head title="Nouvel état des lieux" />
    <PropertyLayout role="bailleur" title="Nouvel état des lieux" subtitle="Planifier ou réaliser un état des lieux">
        <form @submit.prevent="store" class="max-w-2xl space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Bien</label>
                <select v-model="form.property_id" required class="imo-input w-full">
                    <option value="">Sélectionner un bien</option>
                    <option v-for="p in properties" :key="p.id" :value="p.id">{{ p.title }}</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Contrat</label>
                <select v-model="form.contract_id" required class="imo-input w-full">
                    <option value="">Sélectionner un contrat</option>
                    <option v-for="c in contracts" :key="c.id" :value="c.id">{{ c.property?.title }} - {{ c.tenant_name }}</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Type</label>
                <select v-model="form.type" class="imo-input w-full">
                    <option value="entree">État d'entrée</option>
                    <option value="sortie">État de sortie</option>
                    <option value="periodique">État périodique</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Date</label>
                <input v-model="form.inspection_date" type="date" required class="imo-input w-full" />
            </div>
            <button type="submit" class="imo-btn-primary" :disabled="form.processing">Créer</button>
        </form>
    </PropertyLayout>
</template>
