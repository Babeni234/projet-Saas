<script setup>
import { Head, Link } from '@inertiajs/vue3';
import TenantLayout from '@/Layouts/Tenant/TenantLayout.vue';

defineProps({
    tenant: Object,
    activeContract: Object,
    receipts: Array,
    incidents: Array,
});
</script>

<template>
    <Head title="Mon espace locataire" />
    <TenantLayout>
        <div class="space-y-6">
            <div v-if="activeContract" class="rounded-xl border border-gray-200 bg-white p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Mon contrat actif</h2>
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                    <div>
                        <p class="text-xs text-gray-500">Bien</p>
                        <p class="text-sm font-medium text-gray-900">{{ activeContract.property?.title }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Adresse</p>
                        <p class="text-sm font-medium text-gray-900">{{ activeContract.property?.address }}, {{ activeContract.property?.city }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Loyer</p>
                        <p class="text-sm font-medium text-gray-900">{{ Number(activeContract.rent_amount).toLocaleString() }} €</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Charges</p>
                        <p class="text-sm font-medium text-gray-900">{{ Number(activeContract.charges).toLocaleString() }} €</p>
                    </div>
                </div>
            </div>

            <div v-if="receipts?.length" class="rounded-xl border border-gray-200 bg-white p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Dernières quittances</h2>
                    <Link :href="route('tenant.receipts.index')" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Voir tout</Link>
                </div>
                <div class="space-y-2">
                    <div v-for="r in receipts" :key="r.id" class="flex items-center justify-between rounded-lg bg-gray-50 px-4 py-3">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ r.period }}</p>
                            <p class="text-xs text-gray-500">{{ r.reference }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900">{{ Number(r.total).toLocaleString() }} €</p>
                            <span class="text-xs" :class="r.status === 'paid' ? 'text-emerald-600' : r.status === 'overdue' ? 'text-red-600' : 'text-amber-600'">
                                {{ r.status === 'paid' ? 'Payée' : r.status === 'pending' ? 'En attente' : 'En retard' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="incidents?.length" class="rounded-xl border border-gray-200 bg-white p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Mes demandes</h2>
                    <Link :href="route('tenant.incidents.index')" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Voir tout</Link>
                </div>
                <div class="space-y-2">
                    <div v-for="inc in incidents" :key="inc.id" class="flex items-center justify-between rounded-lg bg-gray-50 px-4 py-3">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ inc.title }}</p>
                            <p class="text-xs text-gray-500">{{ inc.category }}</p>
                        </div>
                        <span class="text-xs rounded-full px-2.5 py-0.5 font-medium"
                            :class="inc.status === 'reported' ? 'bg-blue-50 text-blue-700' : inc.status === 'resolved' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700'">
                            {{ inc.status === 'reported' ? 'Signalé' : inc.status === 'in_progress' ? 'En cours' : inc.status === 'resolved' ? 'Résolu' : inc.status }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </TenantLayout>
</template>
