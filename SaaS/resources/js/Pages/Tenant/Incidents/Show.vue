<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import TenantLayout from '@/Layouts/Tenant/TenantLayout.vue';

defineProps({ incident: Object });

const form = useForm({ content: '' });

function submit() {
    form.post(route('tenant.incidents.comment', incident.id), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
}
</script>

<template>
    <Head :title="incident.title" />
    <TenantLayout>
        <div class="flex items-center gap-4 mb-6">
            <Link :href="route('tenant.incidents.index')" class="text-gray-400 hover:text-gray-600"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg></Link>
            <h2 class="text-lg font-semibold text-gray-900">{{ incident.title }}</h2>
        </div>

        <div class="max-w-2xl space-y-4">
            <div class="rounded-xl border border-gray-200 bg-white p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="rounded-full px-2.5 py-0.5 text-xs font-medium"
                        :class="incident.status === 'reported' ? 'bg-blue-50 text-blue-700' : incident.status === 'in_progress' ? 'bg-amber-50 text-amber-700' : 'bg-emerald-50 text-emerald-700'">
                        {{ incident.status === 'reported' ? 'Signalé' : incident.status === 'in_progress' ? 'En cours' : incident.status === 'resolved' ? 'Résolu' : 'Fermé' }}
                    </span>
                    <span class="text-xs text-gray-400">{{ incident.urgency }}</span>
                </div>
                <p class="text-sm text-gray-700">{{ incident.description }}</p>
                <p v-if="incident.property" class="mt-3 text-xs text-gray-500">Bien : {{ incident.property.title }}</p>
            </div>

            <div v-if="incident.comments?.length" class="space-y-2">
                <div v-for="c in incident.comments" :key="c.id" class="rounded-lg border border-gray-100 bg-gray-50 p-3">
                    <p class="text-sm text-gray-700">{{ c.content }}</p>
                    <p class="mt-1 text-xs text-gray-400">{{ c.user?.name }} · {{ new Date(c.created_at).toLocaleString('fr-FR') }}</p>
                </div>
            </div>

            <form v-if="incident.status !== 'resolved' && incident.status !== 'closed'" @submit.prevent="submit" class="flex gap-2">
                <textarea v-model="form.content" rows="2" placeholder="Ajouter un commentaire..." class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                <button type="submit" :disabled="form.processing || !form.content.trim()" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50">Envoyer</button>
            </form>
        </div>
    </TenantLayout>
</template>
