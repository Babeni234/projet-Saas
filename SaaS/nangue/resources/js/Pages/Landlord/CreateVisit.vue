<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

const form = useForm({
    property_id: '',
    visitor_name: '',
    visitor_phone: '',
    visitor_email: '',
    date: '',
    time: '',
    notes: '',
});

const properties = [
    { value: '1', label: 'Appartement T3 centre-ville' },
    { value: '2', label: 'Studio moderne' },
    { value: '3', label: 'Maison avec jardin' },
];

const submit = () => {
    form.post(route('landlord.calendar.store'));
};
</script>

<template>
    <Head title="Planifier une visite" />

    <PropertyLayout
        role="bailleur"
        title="Planifier une visite"
        subtitle="Organisez une visite pour un bien disponible"
    >
        <template #actions>
            <button
                type="button"
                @click="submit"
                :disabled="form.processing"
                class="imo-btn-primary"
            >
                {{ form.processing ? 'Planification...' : 'Planifier' }}
            </button>
        </template>

        <div class="imo-page">
            <form @submit.prevent="submit" class="space-y-8">
                <section class="imo-section">
                    <p class="imo-section-title">Détails de la visite</p>
                    <div class="imo-form-grid">
                        <div class="imo-form-group">
                            <label class="imo-form-label">Bien *</label>
                            <select v-model="form.property_id" class="imo-form-select" required>
                                <option v-for="property in properties" :key="property.value" :value="property.value">{{ property.label }}</option>
                            </select>
                        </div>
                        <div class="imo-form-group">
                            <label class="imo-form-label">Nom du visiteur *</label>
                            <input v-model="form.visitor_name" type="text" class="imo-form-input" required />
                        </div>
                        <div class="imo-form-group">
                            <label class="imo-form-label">Téléphone *</label>
                            <input v-model="form.visitor_phone" type="tel" class="imo-form-input" required />
                        </div>
                        <div class="imo-form-group">
                            <label class="imo-form-label">Email</label>
                            <input v-model="form.visitor_email" type="email" class="imo-form-input" />
                        </div>
                    </div>
                </section>

                <section class="imo-section">
                    <p class="imo-section-title">Créneau</p>
                    <div class="imo-form-grid">
                        <div class="imo-form-group">
                            <label class="imo-form-label">Date *</label>
                            <input v-model="form.date" type="date" class="imo-form-input" required />
                        </div>
                        <div class="imo-form-group">
                            <label class="imo-form-label">Heure *</label>
                            <input v-model="form.time" type="time" class="imo-form-input" required />
                        </div>
                        <div class="imo-form-group md:col-span-2">
                            <label class="imo-form-label">Notes</label>
                            <textarea v-model="form.notes" rows="4" class="imo-form-textarea" placeholder="Informations complémentaires pour le visiteur"></textarea>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </PropertyLayout>
</template>
