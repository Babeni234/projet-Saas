<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    property: Object,
});

const form = useForm({
    title: props.property.title,
    description: props.property.description || '',
    property_type: props.property.property_type,
    transaction_type: props.property.transaction_type,
    address: props.property.address,
    city: props.property.city,
    postal_code: props.property.postal_code || '',
    price: props.property.price,
    surface: props.property.surface || '',
    rooms: props.property.rooms,
    bedrooms: props.property.bedrooms || 0,
    bathrooms: props.property.bathrooms || 0,
    furnished: props.property.furnished,
    available_from: props.property.available_from || '',
    charges_included: props.property.charges_included,
    deposit: props.property.deposit || '',
    min_lease_duration: props.property.min_lease_duration || '',
    status: props.property.status,
    latitude: props.property.latitude || '',
    longitude: props.property.longitude || '',
});

const propertyTypes = [
    { value: 'apartment', label: 'Appartement' },
    { value: 'house', label: 'Maison' },
    { value: 'studio', label: 'Studio' },
    { value: 'loft', label: 'Loft' },
    { value: 'villa', label: 'Villa' },
];

const transactionTypes = [
    { value: 'rent', label: 'Location' },
    { value: 'sale', label: 'Vente' },
];

const statuses = [
    { value: 'draft', label: 'Brouillon' },
    { value: 'active', label: 'Publié' },
    { value: 'rented', label: 'Loué' },
    { value: 'sold', label: 'Vendu' },
    { value: 'pending', label: 'En attente' },
];

function submit() {
    form.put(route('landlord.properties.update', props.property.id));
}
</script>

<template>
    <Head title="Modifier le bien" />

    <AppLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link :href="route('landlord.properties.index')" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </Link>
                <h1 class="text-2xl font-semibold text-gray-900">Modifier le bien</h1>
            </div>
        </template>

        <div class="max-w-3xl">
            <form @submit.prevent="submit" class="space-y-8">
                <div class="rounded-xl border border-gray-200 bg-white p-6 space-y-6">
                    <h2 class="text-lg font-semibold text-gray-900">Informations générales</h2>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Titre</label>
                            <input v-model="form.title" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                            <p v-if="form.errors.title" class="mt-1 text-sm text-red-600">{{ form.errors.title }}</p>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea v-model="form.description" rows="4" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Type de bien</label>
                            <select v-model="form.property_type" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                <option v-for="t in propertyTypes" :key="t.value" :value="t.value">{{ t.label }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Transaction</label>
                            <select v-model="form.transaction_type" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                <option v-for="t in transactionTypes" :key="t.value" :value="t.value">{{ t.label }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Statut</label>
                            <select v-model="form.status" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                <option v-for="s in statuses" :key="s.value" :value="s.value">{{ s.label }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 space-y-6">
                    <h2 class="text-lg font-semibold text-gray-900">Localisation</h2>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Adresse</label>
                            <input v-model="form.address" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ville</label>
                            <input v-model="form.city" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Code postal</label>
                            <input v-model="form.postal_code" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 space-y-6">
                    <h2 class="text-lg font-semibold text-gray-900">Caractéristiques</h2>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                        <div><label class="block text-sm font-medium text-gray-700">Prix (€)</label><input v-model="form.price" type="number" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" /></div>
                        <div><label class="block text-sm font-medium text-gray-700">Surface (m²)</label><input v-model="form.surface" type="number" step="0.01" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" /></div>
                        <div><label class="block text-sm font-medium text-gray-700">Pièces</label><input v-model="form.rooms" type="number" min="0" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" /></div>
                        <div><label class="block text-sm font-medium text-gray-700">Chambres</label><input v-model="form.bedrooms" type="number" min="0" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" /></div>
                        <div><label class="block text-sm font-medium text-gray-700">Salles de bain</label><input v-model="form.bathrooms" type="number" min="0" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" /></div>
                        <div><label class="block text-sm font-medium text-gray-700">Caution (€)</label><input v-model="form.deposit" type="number" step="0.01" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" /></div>
                    </div>
                    <div class="flex flex-wrap gap-6">
                        <label class="flex items-center gap-2"><input v-model="form.furnished" type="checkbox" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" /><span class="text-sm text-gray-700">Meublé</span></label>
                        <label class="flex items-center gap-2"><input v-model="form.charges_included" type="checkbox" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" /><span class="text-sm text-gray-700">Charges incluses</span></label>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" :disabled="form.processing" class="rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50">
                        {{ form.processing ? 'Enregistrement...' : 'Enregistrer les modifications' }}
                    </button>
                    <Link :href="route('landlord.properties.index')" class="text-sm font-medium text-gray-600 hover:text-gray-900">Annuler</Link>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
