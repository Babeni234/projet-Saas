<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({ visit: Object, properties: Array });

const form = useForm({
    property_id: props.visit.property_id,
    visitor_name: props.visit.visitor_name,
    visitor_phone: props.visit.visitor_phone,
    visitor_email: props.visit.visitor_email ?? '',
    date: props.visit.date,
    time: props.visit.time,
    notes: props.visit.notes ?? '',
    status: props.visit.status,
});

function submit() {
    form.put(route('landlord.visits.update', props.visit.id));
}
</script>

<template>
    <Head title="Modifier la visite" />
    <AppLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link :href="route('landlord.visits.index')" class="text-gray-400 hover:text-gray-600"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg></Link>
                <h1 class="text-2xl font-semibold text-gray-900">Modifier la visite</h1>
            </div>
        </template>

        <div class="max-w-lg">
            <form @submit.prevent="submit" class="rounded-xl border border-gray-200 bg-white p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Bien</label>
                    <select v-model="form.property_id" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        <option v-for="p in properties" :key="p.id" :value="p.id">{{ p.title }} — {{ p.city }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nom du visiteur</label>
                    <input v-model="form.visitor_name" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                        <input v-model="form.visitor_phone" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input v-model="form.visitor_email" type="email" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date</label>
                        <input v-model="form.date" type="date" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Heure</label>
                        <input v-model="form.time" type="time" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea v-model="form.notes" rows="3" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Statut</label>
                    <select v-model="form.status" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        <option value="scheduled">Planifiée</option>
                        <option value="completed">Effectuée</option>
                        <option value="cancelled">Annulée</option>
                    </select>
                </div>
                <div class="flex items-center gap-4">
                    <button type="submit" :disabled="form.processing" class="rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50">{{ form.processing ? 'Mise à jour...' : 'Enregistrer' }}</button>
                    <Link :href="route('landlord.visits.index')" class="text-sm font-medium text-gray-600 hover:text-gray-900">Annuler</Link>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
