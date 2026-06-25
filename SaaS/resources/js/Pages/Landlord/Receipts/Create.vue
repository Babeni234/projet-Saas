<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({ contracts: Array });

const form = useForm({
    contract_id: '',
    period: '',
    rent: '',
    charges: 0,
    due_date: '',
    status: 'pending',
});

function submit() {
    form.post(route('landlord.receipts.store'));
}
</script>

<template>
    <Head title="Nouvelle quittance" />
    <AppLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link :href="route('landlord.receipts.index')" class="text-gray-400 hover:text-gray-600"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg></Link>
                <h1 class="text-2xl font-semibold text-gray-900">Nouvelle quittance</h1>
            </div>
        </template>

        <div class="max-w-lg">
            <form @submit.prevent="submit" class="rounded-xl border border-gray-200 bg-white p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Contrat</label>
                    <select v-model="form.contract_id" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        <option value="">Sélectionner un contrat</option>
                        <option v-for="c in contracts" :key="c.id" :value="c.id">{{ c.property?.title }} — {{ c.tenant?.name }}</option>
                    </select>
                    <p v-if="form.errors.contract_id" class="mt-1 text-sm text-red-600">{{ form.errors.contract_id }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Période</label>
                    <input v-model="form.period" type="text" placeholder="ex: Juin 2026" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                    <p v-if="form.errors.period" class="mt-1 text-sm text-red-600">{{ form.errors.period }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Loyer (€)</label>
                        <input v-model="form.rent" type="number" step="0.01" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Charges (€)</label>
                        <input v-model="form.charges" type="number" step="0.01" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Échéance</label>
                    <input v-model="form.due_date" type="date" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Statut</label>
                    <select v-model="form.status" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        <option value="pending">En attente</option>
                        <option value="paid">Payée</option>
                        <option value="overdue">En retard</option>
                    </select>
                </div>
                <div class="flex items-center gap-4">
                    <button type="submit" :disabled="form.processing" class="rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50">{{ form.processing ? 'Création...' : 'Créer' }}</button>
                    <Link :href="route('landlord.receipts.index')" class="text-sm font-medium text-gray-600 hover:text-gray-900">Annuler</Link>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
