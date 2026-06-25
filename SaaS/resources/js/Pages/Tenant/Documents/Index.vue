<script setup>
import { Head } from '@inertiajs/vue3';
import TenantLayout from '@/Layouts/Tenant/TenantLayout.vue';

defineProps({ documents: Array });
</script>

<template>
    <Head title="Mes documents" />
    <TenantLayout>
        <h2 class="text-lg font-semibold text-gray-900 mb-6">Mes documents</h2>

        <div v-if="documents?.length" class="space-y-2">
            <div v-for="d in documents" :key="d.id" class="flex items-center justify-between rounded-xl border border-gray-200 bg-white px-5 py-4">
                <div>
                    <p class="text-sm font-medium text-gray-900">{{ d.name }}</p>
                    <p class="text-xs text-gray-500">
                        {{ d.category?.name || d.documentable_type?.replace('App\\Models\\', '') }}
                        <span v-if="d.expires_at" class="ml-2">Expire le {{ new Date(d.expires_at).toLocaleDateString('fr-FR') }}</span>
                    </p>
                </div>
                <a :href="'/' + d.file_path" target="_blank" class="rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-medium text-indigo-700 hover:bg-indigo-100">Voir</a>
            </div>
        </div>

        <div v-else class="rounded-xl border-2 border-dashed border-gray-300 p-12 text-center">
            <p class="text-sm text-gray-500">Aucun document disponible.</p>
        </div>
    </TenantLayout>
</template>
