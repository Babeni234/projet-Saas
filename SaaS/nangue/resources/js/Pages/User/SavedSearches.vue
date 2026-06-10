<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

const saved = ref([
  { id: 1, q: '2 chambres, < 1000€', created: '2026-05-10' },
  { id: 2, q: 'Studio proche métro', created: '2026-04-02' },
]);

const removeSearch = (id) => {
  if (confirm('Supprimer cette recherche ?')) {
    saved.value = saved.value.filter(s => s.id !== id);
  }
};
</script>

<template>
  <Head title="Recherches enregistrées" />
  <PropertyLayout role="particulier" title="Recherches enregistrées">
    <section class="imo-section">
      <div class="imo-section-header">
        <h2 class="imo-section-heading">Recherches enregistrées</h2>
        <p class="text-sm text-slate-500">Gère tes alertes et recherches sauvegardées.</p>
      </div>

      <div class="imo-panel-compact space-y-3">
        <div v-for="s in saved" :key="s.id" class="imo-panel">
          <div class="flex justify-between items-center">
            <div>
              <p class="font-semibold">{{ s.q }}</p>
              <p class="text-sm text-slate-500">Enregistré le {{ s.created }}</p>
            </div>
            <div class="flex gap-2">
              <Link :href="route('immo.properties')" class="imo-btn-sm imo-btn-sm-primary">Voir</Link>
              <button @click="removeSearch(s.id)" class="imo-btn-sm imo-btn-sm-danger">
                Supprimer
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>
  </PropertyLayout>
</template>
