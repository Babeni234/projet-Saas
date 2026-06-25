<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    properties: Array,
    tenants: Array,
});

const form = useForm({
    property_id: '',
    tenant_id: '',
    start_date: '',
    end_date: '',
    rent_amount: '',
    charges: '',
    deposit: '',
    status: 'active',
});

function submit() {
    form.post(route('landlord.contracts.store'));
}
</script>

<template>
    <Head title="Nouveau contrat" />

    <AppLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link :href="route('landlord.contracts.index')" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </Link>
                <h1 class="text-2xl font-semibold text-gray-900">Nouveau contrat</h1>
            </div>
        </template>

        <div class="max-w-2xl">
            <form @submit.prevent="submit" class="space-y-6">
                <div class="rounded-xl border border-gray-200 bg-white p-6 space-y-6">
                    <h2 class="text-lg font-semibold text-gray-900">Informations du contrat</h2>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Bien</label>
                            <select v-model="form.property_id" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                <option value="">Sélectionner un bien</option>
                                <option v-for="p in properties" :key="p.id" :value="p.id">{{ p.title }} — {{ p.city }}</option>
                            </select>
                            <p v-if="form.errors.property_id" class="mt-1 text-sm text-red-600">{{ form.errors.property_id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Locataire</label>
                            <select v-model="form.tenant_id" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                <option value="">Sélectionner un locataire</option>
                                <option v-for="t in tenants" :key="t.id" :value="t.id">{{ t.name }}</option>
                            </select>
                            <p v-if="form.errors.tenant_id" class="mt-1 text-sm text-red-600">{{ form.errors.tenant_id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date de début</label>
                            <input v-model="form.start_date" type="date" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                            <p v-if="form.errors.start_date" class="mt-1 text-sm text-red-600">{{ form.errors.start_date }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date de fin</label>
                            <input v-model="form.end_date" type="date" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Loyer mensuel (€)</label>
                            <input v-model="form.rent_amount" type="number" step="0.01" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                            <p v-if="form.errors.rent_amount" class="mt-1 text-sm text-red-600">{{ form.errors.rent_amount }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Charges (€)</label>
                            <input v-model="form.charges" type="number" step="0.01" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Dépôt de garantie (€)</label>
                            <input v-model="form.deposit" type="number" step="0.01" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" :disabled="form.processing" class="rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50">
                        {{ form.processing ? 'Création...' : 'Créer le contrat' }}
                    </button>
                    <Link :href="route('landlord.contracts.index')" class="text-sm font-medium text-gray-600 hover:text-gray-900">Annuler</Link>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
