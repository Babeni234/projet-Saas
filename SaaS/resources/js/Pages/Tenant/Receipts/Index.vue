<script setup>
import { Head, Link } from '@inertiajs/vue3';
import TenantLayout from '@/Layouts/Tenant/TenantLayout.vue';

defineProps({ receipts: Object });
</script>

<template>
    <Head title="Mes quittances" />
    <TenantLayout>
        <h2 class="text-lg font-semibold text-gray-900 mb-6">Mes quittances</h2>

        <div v-if="receipts?.data?.length" class="space-y-2">
            <div v-for="r in receipts.data" :key="r.id" class="flex items-center justify-between rounded-xl border border-gray-200 bg-white px-5 py-4">
                <div>
                    <p class="text-sm font-medium text-gray-900">{{ r.period }}</p>
                    <p class="text-xs text-gray-500">{{ r.reference }} · {{ r.contract?.property?.title }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-900">{{ Number(r.total).toLocaleString() }} €</p>
                        <span class="text-xs" :class="r.status === 'paid' ? 'text-emerald-600' : r.status === 'overdue' ? 'text-red-600' : 'text-amber-600'">
                            {{ r.status === 'paid' ? 'Payée' : r.status === 'pending' ? 'En attente' : 'En retard' }}
                        </span>
                    </div>
                    <Link v-if="r.status === 'paid'"
                        :href="route('tenant.receipts.pdf', r.id)"
                        class="rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-medium text-indigo-700 hover:bg-indigo-100">
                        PDF
                    </Link>
                </div>
            </div>
        </div>

        <div v-else class="rounded-xl border-2 border-dashed border-gray-300 p-12 text-center">
            <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" /></svg>
            <h3 class="mt-4 text-sm font-semibold text-gray-900">Aucune quittance</h3>
            <p class="mt-1 text-sm text-gray-500">Vous n'avez pas encore de quittance.</p>
        </div>
    </TenantLayout>
</template>
