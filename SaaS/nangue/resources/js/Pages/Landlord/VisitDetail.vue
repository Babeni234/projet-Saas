<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

const props = defineProps({
    visit: { type: Object, default: () => ({}) },
});
</script>

<template>
    <Head title="Détail de la visite" />

    <PropertyLayout
        role="bailleur"
        title="Détail de la visite"
        subtitle="Consultez les informations de la visite"
    >
        <template #actions>
            <Link :href="route('landlord.calendar.edit', visit.id)" class="imo-btn-secondary">
                Modifier
            </Link>
        </template>

        <div class="imo-page">
            <div class="imo-section">
                <div class="imo-section-header">
                    <h2 class="imo-section-heading">Visite programmée</h2>
                    <p class="text-sm text-slate-500">Détails du visiteur et du bien.</p>
                </div>

                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="imo-panel">
                        <h3 class="font-semibold text-slate-900">Bien</h3>
                        <p class="mt-2 text-slate-600">{{ visit.property_name || 'Appartement T3 centre-ville' }}</p>
                        <p class="text-sm text-slate-500">{{ visit.property_address || '15 Rue de la République, Lyon' }}</p>
                    </div>
                    <div class="imo-panel">
                        <h3 class="font-semibold text-slate-900">Visiteur</h3>
                        <p class="mt-2 text-slate-600">{{ visit.visitor_name || 'Jean Durand' }}</p>
                        <p class="text-sm text-slate-500">{{ visit.visitor_phone || '06 12 34 56 78' }}</p>
                    </div>
                    <div class="imo-panel">
                        <h3 class="font-semibold text-slate-900">Créneau</h3>
                        <p class="mt-2 text-slate-600">{{ visit.date || '04/06/2024' }} à {{ visit.time || '14:00' }}</p>
                        <p class="text-sm text-slate-500">{{ visit.status === 'scheduled' ? 'Planifiée' : visit.status === 'completed' ? 'Terminée' : 'Annulée' }}</p>
                    </div>
                    <div class="imo-panel">
                        <h3 class="font-semibold text-slate-900">Notes</h3>
                        <p class="mt-2 text-slate-600">{{ visit.notes || 'Aucune remarque pour le moment.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </PropertyLayout>
</template>
