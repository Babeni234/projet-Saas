<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({ visits: Object });
</script>

<template>
    <Head title="Visites" />
    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-gray-900">Visites</h1>
                <Link :href="route('landlord.visits.create')" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Planifier une visite
                </Link>
            </div>
        </template>

        <div v-if="visits?.data?.length" class="overflow-hidden rounded-xl border border-gray-200 bg-white">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Visiteur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Bien</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Statut</th>
                        <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr v-for="visit in visits.data" :key="visit.id" class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ visit.visitor_name }}<br><span class="text-xs text-gray-500">{{ visit.visitor_phone }}</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ visit.property?.title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ visit.date }} à {{ visit.time }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium"
                                :class="visit.status === 'scheduled' ? 'bg-blue-50 text-blue-700' : visit.status === 'completed' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700'">
                                {{ visit.status === 'scheduled' ? 'Planifiée' : visit.status === 'completed' ? 'Effectuée' : 'Annulée' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <Link :href="route('landlord.visits.edit', visit.id)" class="font-medium text-indigo-600 hover:text-indigo-800">Modifier</Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-else class="rounded-xl border-2 border-dashed border-gray-300 p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
            <h3 class="mt-4 text-lg font-semibold text-gray-900">Aucune visite</h3>
            <p class="mt-2 text-sm text-gray-500">Vous n'avez pas encore planifié de visite.</p>
            <Link :href="route('landlord.visits.create')" class="mt-6 inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Planifier une visite</Link>
        </div>
    </AppLayout>
</template>
