<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

const props = defineProps({
    contract: { type: Object, default: () => ({}) },
});

const form = useForm({
    tenant_id: props.contract.tenant_id || '1',
    property_id: props.contract.property_id || '1',
    lease_type: props.contract.lease_type || 'Bail habitation',
    duration: props.contract.duration || '12 mois',
    rent: props.contract.rent || '',
    charges: props.contract.charges || '',
    start_date: props.contract.start_date || '',
    end_date: props.contract.end_date || '',
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
    form.put(route('landlord.contracts.update', props.contract.id));
};
</script>

<template>
    <Head title="Modifier le contrat" />

    <PropertyLayout
        role="bailleur"
        title="Modifier le contrat"
        subtitle="Ajustez les détails du bail"
    >
        <template #actions>
            <button
                type="button"
                @click="submit"
                :disabled="form.processing"
                class="imo-btn-primary"
            >
                {{ form.processing ? 'Enregistrement...' : 'Enregistrer les modifications' }}
            </button>
        </template>

        <div class="imo-page">
            <form @submit.prevent="submit" class="space-y-8">
                <section class="imo-section">
                    <p class="imo-section-title">Détails du contrat</p>
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
                    <p class="imo-section-title">Montants</p>
                    <div class="imo-form-grid">
                        <div class="imo-form-group">
                            <label class="imo-form-label">Loyer (€) *</label>
                            <input v-model="form.rent" type="number" class="imo-form-input" required />
                        </div>
                        <div class="imo-form-group">
                            <label class="imo-form-label">Charges (€) *</label>
                            <input v-model="form.charges" type="number" class="imo-form-input" required />
                        </div>
                        <div class="imo-form-group">
                            <label class="imo-form-label">Début *</label>
                            <input v-model="form.start_date" type="date" class="imo-form-input" required />
                        </div>
                        <div class="imo-form-group">
                            <label class="imo-form-label">Fin *</label>
                            <input v-model="form.end_date" type="date" class="imo-form-input" required />
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </PropertyLayout>
</template>
