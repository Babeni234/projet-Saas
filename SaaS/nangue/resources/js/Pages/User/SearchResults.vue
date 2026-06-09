<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

const searchQuery = ref('');
const listening = ref(false);
const recognition = ref(null);

const initSpeech = () => {
  const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
  if (!SpeechRecognition) return;
  recognition.value = new SpeechRecognition();
  recognition.value.lang = 'fr-FR';
  recognition.value.interimResults = false;
  recognition.value.onresult = (e) => {
    searchQuery.value = e.results[0][0].transcript;
    listening.value = false;
  };
  recognition.value.onerror = () => { listening.value = false; };
  recognition.value.onend = () => { listening.value = false; };
};

const toggleVoice = () => {
  if (!recognition.value) initSpeech();
  if (!recognition.value) return;
  if (listening.value) {
    recognition.value.stop();
    listening.value = false;
  } else {
    listening.value = true;
    recognition.value.start();
  }
};

const allProperties = [
    {
        id: 1,
        title: 'Appartement T2 lumineux',
        address: '12 Rue du Faubourg, Paris',
        city: 'Paris',
        price: 1200,
        surface: 48,
        bedrooms: 1,
        rooms: 2,
        transaction_type: 'rent',
        image: '/placeholder-property.jpg',
    },
    {
        id: 2,
        title: 'Maison familiale avec jardin',
        address: '5 Rue des Acacias, Lyon',
        city: 'Lyon',
        price: 250000,
        surface: 130,
        bedrooms: 4,
        rooms: 5,
        transaction_type: 'sale',
        image: '/placeholder-property.jpg',
    },
];

const filteredResults = computed(() => {
    const q = searchQuery.value.toLowerCase().trim();
    if (!q) return allProperties;
    return allProperties.filter(p =>
        (p.title || '').toLowerCase().includes(q) ||
        (p.city || '').toLowerCase().includes(q) ||
        (p.address || '').toLowerCase().includes(q)
    );
});
</script>

<template>
    <Head title="Rechercher des biens" />

    <PropertyLayout
        role="particulier"
        title="Rechercher des biens"
        subtitle="Explorez les dernières annonces sauvegardées dans vos recherches"
    >
        <div class="imo-page">
            <div class="imo-filters-bar">
                <div class="flex flex-1 items-center gap-4">
                    <div class="relative flex-1 max-w-md">
                        <svg class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input v-model="searchQuery" type="text" placeholder="Rechercher des biens..." class="imo-search-input" />
                        <button @click="toggleVoice" type="button" class="absolute right-3 top-1/2 -translate-y-1/2" :class="listening ? 'text-red-500' : 'text-slate-400 hover:text-emerald-600'" title="Recherche vocale">
                          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                          </svg>
                        </button>
                    </div>
                    <span class="text-sm text-slate-500">{{ filteredResults.length }} résultat(s)</span>
                </div>
            </div>

            <div v-if="filteredResults.length" class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                <div v-for="property in filteredResults" :key="property.id" class="imo-property-card">
                    <div class="relative h-48 overflow-hidden rounded-xl bg-slate-100">
                        <img :src="property.image" :alt="property.title" class="h-full w-full object-cover" />
                    </div>
                    <div class="mt-4 space-y-3">
                        <div>
                            <p class="text-sm text-slate-500">{{ property.city }}</p>
                            <h3 class="text-lg font-semibold text-slate-900">{{ property.title }}</h3>
                            <p class="text-sm text-slate-500">{{ property.address }}</p>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-xl font-semibold text-emerald-600">{{ property.price }}€</p>
                                <p class="text-xs text-slate-500">{{ property.transaction_type === 'rent' ? '/mois' : 'Achat' }}</p>
                            </div>
                            <Link :href="route('immo.property.show', property.id)" class="imo-btn-sm imo-btn-sm-primary">Voir</Link>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else class="imo-empty-state-lg">
                <svg class="mx-auto h-16 w-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <h3 class="mt-4 text-lg font-semibold text-slate-900">Aucun résultat</h3>
                <p class="mt-2 text-sm text-slate-500">Essayez de modifier vos critères de recherche.</p>
            </div>
        </div>
    </PropertyLayout>
</template>
