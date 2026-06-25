<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import TenantLayout from '@/Layouts/Tenant/TenantLayout.vue';

defineProps({ properties: Array });

const form = useForm({
    property_id: '',
    title: '',
    description: '',
    category: 'other',
    urgency: 'medium',
});

function submit() {
    form.post(route('tenant.incidents.store'));
}
</script>

<template>
    <Head title="Nouvelle demande" />
    <TenantLayout>
        <div class="flex items-center gap-4 mb-6">
            <Link :href="route('tenant.incidents.index')" class="text-gray-400 hover:text-gray-600"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg></Link>
            <h2 class="text-lg font-semibold text-gray-900">Nouvelle demande</h2>
        </div>

        <form @submit.prevent="submit" class="max-w-lg rounded-xl border border-gray-200 bg-white p-6 space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700">Bien concerné</label>
                <select v-model="form.property_id" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    <option value="">Sélectionner</option>
                    <option v-for="p in properties" :key="p.id" :value="p.id">{{ p.title }} — {{ p.city }}</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Titre</label>
                <input v-model="form.title" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Catégorie</label>
                <select v-model="form.category" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    <option value="plumbing">Plomberie</option>
                    <option value="electrical">Électricité</option>
                    <option value="heating">Chauffage</option>
                    <option value="appliance">Électroménager</option>
                    <option value="structure">Structure / Bâtiment</option>
                    <option value="other">Autre</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Urgence</label>
                <select v-model="form.urgency" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    <option value="low">Faible</option>
                    <option value="medium">Moyenne</option>
                    <option value="high">Haute</option>
                    <option value="emergency">Urgence</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea v-model="form.description" rows="4" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">{{ form.errors.description }}</p>
            </div>
            <div class="flex items-center gap-3">
                <button type="submit" :disabled="form.processing" class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50">Envoyer</button>
                <Link :href="route('tenant.incidents.index')" class="text-sm text-gray-600 hover:text-gray-900">Annuler</Link>
            </div>
        </form>
    </TenantLayout>
</template>
