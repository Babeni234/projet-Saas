<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

const form = useForm({
  title: '',
  description: '',
  price: '',
  rooms: 1,
});

const submit = () => {
  form.post(route('immo.property.store'));
};
</script>

<template>
  <Head title="Création rapide d'annonce" />

  <PropertyLayout role="bailleur" title="Création rapide" subtitle="Formulaire simplifié pour publier un bien">
    <template #actions>
      <button @click="submit" :disabled="form.processing" class="imo-btn-primary">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        {{ form.processing ? 'Publication...' : 'Publier' }}
      </button>
    </template>

    <div class="imo-page">
      <section class="imo-section">
        <div class="imo-section-header">
          <h2 class="imo-section-heading">Créer une annonce rapide</h2>
          <p class="text-sm text-slate-500">Remplis les informations essentielles pour publier en quelques clics.</p>
        </div>

        <div class="imo-panel">
          <form @submit.prevent="submit" class="space-y-6">
            <div class="imo-form-grid">
              <div class="imo-form-group">
                <label class="imo-form-label">Titre *</label>
                <input v-model="form.title" type="text" class="imo-form-input" placeholder="Ex: T2 centre-ville" required />
                <p v-if="form.errors.title" class="imo-form-error">{{ form.errors.title }}</p>
              </div>

              <div class="imo-form-group">
                <label class="imo-form-label">Prix (€) *</label>
                <input v-model="form.price" type="number" class="imo-form-input" placeholder="1200" required />
                <p v-if="form.errors.price" class="imo-form-error">{{ form.errors.price }}</p>
              </div>

              <div class="imo-form-group">
                <label class="imo-form-label">Pièces</label>
                <input v-model="form.rooms" type="number" class="imo-form-input" min="1" />
              </div>

              <div class="imo-form-group md:col-span-2">
                <label class="imo-form-label">Description courte</label>
                <textarea v-model="form.description" class="imo-form-textarea" rows="4" placeholder="Une courte description..."></textarea>
              </div>
            </div>

            <div class="flex items-center gap-3">
              <button type="submit" :disabled="form.processing" class="imo-btn-primary">Publier</button>
              <button type="button" class="imo-btn-dashed" @click="form.reset('title','description','price','rooms')">Réinitialiser</button>
            </div>
          </form>
        </div>
      </section>
    </div>
  </PropertyLayout>
</template>
