<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

const props = defineProps({
    receipt: { type: Object, default: () => ({}) },
});
</script>

<template>
    <Head title="Détail de la quittance" />

    <PropertyLayout
        role="bailleur"
        title="Détail de la quittance"
        subtitle="Consultez et partagez une quittance de loyer"
    >
        <template #actions>
            <div class="flex flex-wrap gap-2">
                <Link
                    :href="route('landlord.payments.download', receipt.id)"
                    class="imo-btn-secondary"
                >
                    Télécharger
                </Link>
                <Link
                    :href="route('landlord.payments.send', receipt.id)"
                    method="post"
                    class="imo-btn-primary"
                >
                    Envoyer
                </Link>
            </div>
        </template>

        <div class="imo-page">
            <div class="imo-section">
                <div class="imo-section-header">
                    <h2 class="imo-section-heading">Quittance {{ receipt.reference || 'QUI-2024-001' }}</h2>
                    <p class="text-sm text-slate-500">Détails de la période et du paiement.</p>
                </div>

                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="imo-panel">
                        <p class="text-sm text-slate-500">Locataire</p>
                        <p class="mt-2 font-semibold text-slate-900">{{ receipt.tenant_name || 'Marie Dupont' }}</p>
                    </div>
                    <div class="imo-panel">
                        <p class="text-sm text-slate-500">Bien</p>
                        <p class="mt-2 font-semibold text-slate-900">{{ receipt.property_name || 'Appartement T3 centre-ville' }}</p>
                    </div>
                    <div class="imo-panel">
                        <p class="text-sm text-slate-500">Période</p>
                        <p class="mt-2 font-semibold text-slate-900">{{ receipt.period || 'Janvier 2024' }}</p>
                    </div>
                    <div class="imo-panel">
                        <p class="text-sm text-slate-500">Statut</p>
                        <p class="mt-2 font-semibold text-slate-900">{{ receipt.status === 'paid' ? 'Payée' : receipt.status === 'pending' ? 'En attente' : 'En retard' }}</p>
                    </div>
                </div>

                <div class="mt-6 grid gap-6 lg:grid-cols-3">
                    <div class="imo-panel">
                        <p class="text-sm text-slate-500">Loyer</p>
                        <p class="mt-2 font-semibold text-slate-900">{{ receipt.rent ? receipt.rent + '€' : '1200€' }}</p>
                    </div>
                    <div class="imo-panel">
                        <p class="text-sm text-slate-500">Charges</p>
                        <p class="mt-2 font-semibold text-slate-900">{{ receipt.charges ? receipt.charges + '€' : '150€' }}</p>
                    </div>
                    <div class="imo-panel">
                        <p class="text-sm text-slate-500">Total</p>
                        <p class="mt-2 text-2xl font-semibold text-emerald-600">{{ receipt.total ? receipt.total + '€' : '1350€' }}</p>
                    </div>
                </div>

                <div class="mt-6">
                    <p class="text-sm text-slate-500">Date de paiement</p>
                    <p class="mt-2 text-slate-900">{{ receipt.payment_date || '05/01/2024' }}</p>
                </div>
            </div>
        </div>
    </PropertyLayout>
</template>
