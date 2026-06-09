<script setup>
import { Head } from '@inertiajs/vue3';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

const tenants = [
    { id: 1, name: 'Julien Martin', email: 'julien@example.com', property: 'Appartement T3' },
    { id: 2, name: 'Aline Dubois', email: 'aline@example.com', property: 'Studio centre-ville' },
];
</script>

<template>
    <Head title="Locataires" />

    <PropertyLayout role="bailleur" title="Locataires" subtitle="Liste des locataires gérés">
        <div class="imo-page">
            <section class="imo-section">
                <div class="imo-section-header">
                    <h2 class="imo-section-heading">Locataires</h2>
                </div>
                <div class="imo-panel-compact space-y-3">
                    <div
                        v-for="tenant in tenants"
                        :key="tenant.id"
                        class="imo-panel"
                    >
                        <p class="font-semibold text-slate-900">{{ tenant.name }}</p>
                        <p class="text-sm text-slate-500">{{ tenant.email }}</p>
                        <p class="text-sm text-slate-500">Bien : {{ tenant.property }}</p>
                    </div>
                </div>
            </section>
        </div>
    </PropertyLayout>
</template>
