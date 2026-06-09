<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

const form = useForm({
    tenant_id: '',
    property_id: '',
    lease_type: 'Bail habitation',
    duration: '12 mois',
    rent: '',
    charges: '',
    start_date: '',
    end_date: '',
});

const tenants = [
    { value: '1', label: 'Marie Dupont' },
    { value: '2', label: 'Pierre Martin' },
    { value: '3', label: 'Sophie Bernard' },
];

const properties = [
    { value: '1', label: 'Appartement T3 centre-ville' },
    { value: '2', label: 'Studio moderne' },
    { value: '3', label: 'Maison avec jardin' },
];

const submit = () => {
    form.post(route('landlord.contracts.store'));
};
</script>

<template>
    <Head title="Créer un contrat" />

    <PropertyLayout
        role="bailleur"
        title="Créer un contrat"
        subtitle="Formalisez un nouveau bail pour l'un de vos biens"
    >
        <template #actions>
            <button
                type="button"
                @click="submit"
                :disabled="form.processing"
                class="imo-btn-primary"
            >
                <svg v-if="!form.processing" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <svg v-else class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ form.processing ? 'Création en cours...' : 'Créer le contrat' }}
            </button>
        </template>

        <div class="imo-page">
            <form @submit.prevent="submit" class="space-y-8">
                <section class="imo-section">
                    <p class="imo-section-title">Infos contractuelles</p>
                    <div class="imo-form-grid">
                        <div class="imo-form-group">
                            <label class="imo-form-label">Locataire *</label>
                            <select v-model="form.tenant_id" class="imo-form-select" required>
                                <option v-for="tenant in tenants" :key="tenant.value" :value="tenant.value">{{ tenant.label }}</option>
                            </select>
                        </div>
                        <div class="imo-form-group">
                            <label class="imo-form-label">Bien *</label>
                            <select v-model="form.property_id" class="imo-form-select" required>
                                <option v-for="property in properties" :key="property.value" :value="property.value">{{ property.label }}</option>
                            </select>
                        </div>
                        <div class="imo-form-group">
                            <label class="imo-form-label">Type de bail *</label>
                            <input v-model="form.lease_type" type="text" class="imo-form-input" required />
                        </div>
                        <div class="imo-form-group">
                            <label class="imo-form-label">Durée *</label>
                            <input v-model="form.duration" type="text" class="imo-form-input" required />
                        </div>
                    </div>
                </section>

                <section class="imo-section">
                    <p class="imo-section-title">Finances</p>
                    <div class="imo-form-grid">
                        <div class="imo-form-group">
                            <label class="imo-form-label">Loyer mensuel (€) *</label>
                            <input v-model="form.rent" type="number" class="imo-form-input" required />
                        </div>
                        <div class="imo-form-group">
                            <label class="imo-form-label">Charges (€) *</label>
                            <input v-model="form.charges" type="number" class="imo-form-input" required />
                        </div>
                        <div class="imo-form-group">
                            <label class="imo-form-label">Date de début *</label>
                            <input v-model="form.start_date" type="date" class="imo-form-input" required />
                        </div>
                        <div class="imo-form-group">
                            <label class="imo-form-label">Date de fin *</label>
                            <input v-model="form.end_date" type="date" class="imo-form-input" required />
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </PropertyLayout>
</template>
