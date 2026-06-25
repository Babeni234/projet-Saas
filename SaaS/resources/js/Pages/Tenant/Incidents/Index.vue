<script setup>
import { Head, Link } from '@inertiajs/vue3';
import TenantLayout from '@/Layouts/Tenant/TenantLayout.vue';

defineProps({ incidents: Object });
</script>

<template>
    <Head title="Mes demandes" />
    <TenantLayout>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-900">Mes demandes d'intervention</h2>
            <Link :href="route('tenant.incidents.create')" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Nouvelle demande</Link>
        </div>

        <div v-if="incidents?.data?.length" class="space-y-3">
            <Link v-for="inc in incidents.data" :key="inc.id" :href="route('tenant.incidents.show', inc.id)"
                class="block rounded-xl border border-gray-200 bg-white p-4 hover:shadow-sm transition">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">{{ inc.title }}</h3>
                        <p class="text-xs text-gray-500 mt-0.5">{{ inc.property?.title }} — {{ inc.category }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ inc.description?.substring(0, 120) }}{{ inc.description?.length > 120 ? '...' : '' }}</p>
                    </div>
                    <span class="shrink-0 rounded-full px-2.5 py-0.5 text-xs font-medium"
                        :class="inc.status === 'reported' ? 'bg-blue-50 text-blue-700' : inc.status === 'in_progress' ? 'bg-amber-50 text-amber-700' : inc.status === 'resolved' ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-50 text-gray-600'">
                        {{ inc.status === 'reported' ? 'Signalé' : inc.status === 'in_progress' ? 'En cours' : inc.status === 'resolved' ? 'Résolu' : 'Fermé' }}
                    </span>
                </div>
            </Link>
        </div>

        <div v-else class="rounded-xl border-2 border-dashed border-gray-300 p-12 text-center">
            <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>
            <h3 class="mt-4 text-sm font-semibold text-gray-900">Aucune demande</h3>
            <p class="mt-1 text-sm text-gray-500">Vous n'avez pas encore fait de demande.</p>
            <Link :href="route('tenant.incidents.create')" class="mt-4 inline-flex rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Signaler un problème</Link>
        </div>
    </TenantLayout>
</template>
