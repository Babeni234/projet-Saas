<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

const form = useForm({
    contract_id: '',
    period: '',
    rent: '',
    charges: '',
    due_date: '',
});

const contracts = [
    { value: '1', label: 'Marie Dupont - Appartement T3' },
    { value: '2', label: 'Pierre Martin - Studio moderne' },
    { value: '3', label: 'Sophie Bernard - Maison avec jardin' },
];

const submit = () => {
    form.post(route('landlord.payments.store'));
};
</script>

<template>
    <Head title="Créer une quittance" />

    <PropertyLayout
        role="bailleur"
        title="Créer une quittance"
        subtitle="Générez une nouvelle quittance pour un locataire"
    >
        <template #actions>
            <button
                type="button"
                @click="submit"
                :disabled="form.processing"
                class="imo-btn-primary"
            >
                {{ form.processing ? 'Création...' : 'Créer la quittance' }}
            </button>
        </template>

        <div class="imo-page">
            <form @submit.prevent="submit" class="space-y-8">
                <section class="imo-section">
                    <p class="imo-section-title">Informations de la quittance</p>
                    <div class="imo-form-grid">
                        <div class="imo-form-group">
                            <label class="imo-form-label">Contrat *</label>
                            <select v-model="form.contract_id" class="imo-form-select" required>
                                <option v-for="contract in contracts" :key="contract.value" :value="contract.value">{{ contract.label }}</option>
                            </select>
                        </div>
                        <div class="imo-form-group">
                            <label class="imo-form-label">Période *</label>
                            <input v-model="form.period" type="text" class="imo-form-input" placeholder="Ex: Avril 2024" required />
                        </div>
                        <div class="imo-form-group">
                            <label class="imo-form-label">Date d'échéance *</label>
                            <input v-model="form.due_date" type="date" class="imo-form-input" required />
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
                    </div>
                </section>
            </form>
        </div>
    </PropertyLayout>
</template>
