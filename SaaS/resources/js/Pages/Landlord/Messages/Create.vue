<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({ tenants: Array });

const form = useForm({
    subject: '',
    tenant_id: '',
    content: '',
});

function submit() {
    form.post(route('landlord.messages.store'));
}
</script>

<template>
    <Head title="Nouveau message" />
    <AppLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link :href="route('landlord.messages.index')" class="text-gray-400 hover:text-gray-600"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg></Link>
                <h1 class="text-2xl font-semibold text-gray-900">Nouveau message</h1>
            </div>
        </template>

        <div class="max-w-lg">
            <form @submit.prevent="submit" class="rounded-xl border border-gray-200 bg-white p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Destinataire</label>
                    <select v-model="form.tenant_id" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        <option value="">Sélectionner un locataire</option>
                        <option v-for="t in tenants" :key="t.id" :value="t.id">{{ t.name }} — {{ t.email }}</option>
                    </select>
                    <p v-if="form.errors.tenant_id" class="mt-1 text-sm text-red-600">{{ form.errors.tenant_id }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Sujet</label>
                    <input v-model="form.subject" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                    <p v-if="form.errors.subject" class="mt-1 text-sm text-red-600">{{ form.errors.subject }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Message</label>
                    <textarea v-model="form.content" rows="5" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                    <p v-if="form.errors.content" class="mt-1 text-sm text-red-600">{{ form.errors.content }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <button type="submit" :disabled="form.processing" class="rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50">{{ form.processing ? 'Envoi...' : 'Envoyer' }}</button>
                    <Link :href="route('landlord.messages.index')" class="text-sm font-medium text-gray-600 hover:text-gray-900">Annuler</Link>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
