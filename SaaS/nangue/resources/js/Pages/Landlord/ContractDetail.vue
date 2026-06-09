<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

const props = defineProps({
    contract: { type: Object, default: () => ({}) },
});
</script>

<template>
    <Head title="Détail du contrat" />

    <PropertyLayout
        role="bailleur"
        title="Détail du contrat"
        subtitle="Consultez les informations de ce bail"
    >
        <template #actions>
            <Link :href="route('landlord.contracts.edit', contract.id)" class="imo-btn-secondary">
                Modifier
            </Link>
        </template>

        <div class="imo-page">
            <div class="imo-section">
                <div class="imo-section-header">
                    <h2 class="imo-section-heading">Contrat de location</h2>
                    <p class="text-sm text-slate-500">Résumé des termes et des locataires.</p>
                </div>

                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="imo-panel">
                        <h3 class="font-semibold text-slate-900">Locataire</h3>
                        <p class="mt-2 text-slate-600">{{ contract.tenant_name || 'Marie Dupont' }}</p>
                        <p class="text-sm text-slate-500">{{ contract.tenant_email || 'marie.dupont@email.com' }}</p>
                    </div>
                    <div class="imo-panel">
                        <h3 class="font-semibold text-slate-900">Bien</h3>
                        <p class="mt-2 text-slate-600">{{ contract.property_name || 'Appartement T3 centre-ville' }}</p>
                        <p class="text-sm text-slate-500">{{ contract.property_address || '15 Rue de la République, Lyon' }}</p>
                    </div>
                </div>

                <div class="mt-6 grid gap-6 lg:grid-cols-3">
                    <div class="imo-panel">
                        <p class="text-sm text-slate-500">Type de bail</p>
                        <p class="mt-2 font-semibold text-slate-900">{{ contract.lease_type || 'Bail habitation' }}</p>
                    </div>
                    <div class="imo-panel">
                        <p class="text-sm text-slate-500">Durée</p>
                        <p class="mt-2 font-semibold text-slate-900">{{ contract.duration || '12 mois' }}</p>
                    </div>
                    <div class="imo-panel">
                        <p class="text-sm text-slate-500">Période</p>
                        <p class="mt-2 font-semibold text-slate-900">{{ contract.start_date || '01/01/2024' }} - {{ contract.end_date || '31/12/2024' }}</p>
                    </div>
                </div>

                <div class="mt-6 grid gap-6 lg:grid-cols-2">
                    <div class="imo-panel">
                        <p class="text-sm text-slate-500">Loyer</p>
                        <p class="mt-2 text-2xl font-semibold text-emerald-600">{{ contract.rent ? `${contract.rent}€` : '1200€' }}</p>
                    </div>
                    <div class="imo-panel">
                        <p class="text-sm text-slate-500">Charges</p>
                        <p class="mt-2 text-2xl font-semibold text-slate-900">{{ contract.charges ? `${contract.charges}€` : '150€' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </PropertyLayout>
</template>
