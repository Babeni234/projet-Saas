<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

const props = defineProps({
    contracts: { type: Array, default: () => [] },
    paymentMethods: { type: Array, default: () => [] },
});

const form = useForm({ contract_id: '', payment_method_id: '', amount: '', frequency: 'mensuel', day_of_month: '' });

function store() { form.post(route('landlord.subscriptions.store')); }
</script>

<template>
    <Head title="Nouveau prélèvement" />
    <PropertyLayout role="bailleur" title="Nouveau prélèvement" subtitle="Mettre en place un mandat de prélèvement">
        <form @submit.prevent="store" class="max-w-2xl space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Contrat</label>
                <select v-model="form.contract_id" required class="imo-input w-full">
                    <option v-for="c in contracts" :key="c.id" :value="c.id">{{ c.property?.title }} - {{ c.tenant_name }}</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Moyen de paiement</label>
                <select v-model="form.payment_method_id" required class="imo-input w-full">
                    <option v-for="pm in paymentMethods" :key="pm.id" :value="pm.id">{{ pm.brand || pm.type }} - {{ pm.last_four ? '****'+pm.last_four : '' }}</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Montant</label>
                <input v-model="form.amount" type="number" step="0.01" required class="imo-input w-full" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Fréquence</label>
                <select v-model="form.frequency" class="imo-input w-full">
                    <option value="mensuel">Mensuel</option>
                    <option value="trimestriel">Trimestriel</option>
                    <option value="semestriel">Semestriel</option>
                    <option value="annuel">Annuel</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Jour du mois</label>
                <input v-model="form.day_of_month" type="number" min="1" max="31" required class="imo-input w-full" />
            </div>
            <button type="submit" class="imo-btn-primary" :disabled="form.processing">Créer le prélèvement</button>
        </form>
    </PropertyLayout>
</template>
