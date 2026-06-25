<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    contracts: Object,
});
</script>

<template>
    <Head title="Contrats" />

    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-gray-900">Contrats</h1>
                <Link :href="route('landlord.contracts.create')" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nouveau contrat
                </Link>
            </div>
        </template>

        <div v-if="contracts?.data?.length" class="overflow-hidden rounded-xl border border-gray-200 bg-white">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Bien</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Locataire</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Période</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Loyer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Statut</th>
                        <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr v-for="contract in contracts.data" :key="contract.id" class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ contract.property?.title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ contract.tenant?.name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ contract.start_date }} — {{ contract.end_date || 'Indéterminée' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ contract.rent_amount.toLocaleString() }} €</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium"
                                :class="contract.status === 'active' ? 'bg-emerald-50 text-emerald-700' : contract.status === 'terminated' ? 'bg-red-50 text-red-700' : 'bg-gray-50 text-gray-700'">
                                {{ contract.status === 'active' ? 'Actif' : contract.status === 'terminated' ? 'Résilié' : contract.status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <Link :href="route('landlord.contracts.edit', contract.id)" class="font-medium text-indigo-600 hover:text-indigo-800">Modifier</Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-else class="rounded-xl border-2 border-dashed border-gray-300 p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-4 text-lg font-semibold text-gray-900">Aucun contrat</h3>
            <p class="mt-2 text-sm text-gray-500">Vous n'avez pas encore créé de contrat de location.</p>
            <Link :href="route('landlord.contracts.create')" class="mt-6 inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                Créer un contrat
            </Link>
        </div>
    </AppLayout>
</template>
