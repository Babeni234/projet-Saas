<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

const props = defineProps({
  property: { type: Object, default: () => ({}) },
});

const form = useForm({
  title: props.property.title || '',
  description: props.property.description || '',
  price: props.property.price || '',
  surface: props.property.surface || '',
  rooms: props.property.rooms || '',
});

const submit = () => {
  form.put(route('immo.property.update', props.property.id));
};
</script>

<template>
  <Head title="Modifier le bien" />

  <PropertyLayout
    role="particulier"
    title="Modifier le bien"
    subtitle="Mettez à jour les informations de l'annonce"
  >
    <section class="imo-section">
      <form @submit.prevent="submit" class="space-y-6">
        <div class="imo-panel space-y-4">
          <div class="imo-form-group">
            <label class="imo-form-label">Titre</label>
            <input v-model="form.title" class="imo-form-input" />
          </div>
          <div class="imo-form-group">
            <label class="imo-form-label">Prix</label>
            <input type="number" v-model="form.price" class="imo-form-input" />
          </div>
          <div class="imo-form-group">
            <label class="imo-form-label">Surface</label>
            <input type="number" v-model="form.surface" class="imo-form-input" />
          </div>
          <div class="imo-form-group">
            <label class="imo-form-label">Pièces</label>
            <input type="number" v-model="form.rooms" class="imo-form-input" />
          </div>
          <div class="imo-form-group">
            <label class="imo-form-label">Description</label>
            <textarea v-model="form.description" class="imo-form-textarea" rows="4" />
          </div>
        </div>

        <div class="flex items-center gap-3">
          <button type="submit" class="imo-btn-primary">Enregistrer</button>
          <button type="button" class="imo-btn-dashed" @click="form.reset()">Annuler</button>
        </div>
      </form>
    </section>
  </PropertyLayout>
</template>
