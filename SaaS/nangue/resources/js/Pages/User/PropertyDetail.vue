<script setup>
import { Head } from '@inertiajs/vue3';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';
import PropertyMap from '@nangue/Components/PropertyMap.vue';

const props = defineProps({
  property: { type: Object, default: () => ({}) },
});
</script>

<template>
  <Head title="Détails du bien" />

  <PropertyLayout
    role="particulier"
    title="Détail du bien"
    subtitle="Informations complètes et actions"
  >
    <section class="imo-section">
      <div class="imo-section-header">
        <h2 class="imo-section-heading">{{ property.title || 'Bien immobilier' }}</h2>
        <p class="text-sm text-slate-500">{{ property.address || 'Adresse du bien' }}</p>
      </div>

      <div class="imo-panel space-y-4">
        <div class="grid gap-4 md:grid-cols-2">
          <div>
            <p class="text-sm text-slate-500">Prix</p>
            <p class="font-semibold text-slate-900">{{ property.price ? property.price + '€' : '—' }}</p>
          </div>
          <div>
            <p class="text-sm text-slate-500">Surface</p>
            <p class="font-semibold text-slate-900">{{ property.surface ? property.surface + ' m²' : '—' }}</p>
          </div>
        </div>

        <div>
          <p class="text-sm text-slate-500">Description</p>
          <p class="mt-2 text-slate-700">{{ property.description || 'Aucune description disponible.' }}</p>
        </div>
      </div>

      <!-- Carte interactive -->
      <div class="imo-section">
        <h3 class="imo-section-heading">Localisation</h3>
        <PropertyMap :address="property.address" :city="property.city" height="300px" />
      </div>
    </section>
  </PropertyLayout>
</template>
