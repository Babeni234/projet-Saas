<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

const props = defineProps({
    favorites: { type: Array, default: () => [] },
    role: { type: String, default: 'particulier' },
});

const search = ref('');
const filterType = ref('all');
const filterPrice = ref('all');
const filterCity = ref('all');
const viewMode = ref('grid');

const filteredFavorites = computed(() => {
    return props.favorites.filter(f => {
        const q = search.value.toLowerCase();
        const matchesSearch = !q ||
            (f.title || '').toLowerCase().includes(q) ||
            (f.address || '').toLowerCase().includes(q) ||
            (f.city || '').toLowerCase().includes(q);
        const matchesType = filterType.value === 'all' || (f.property_type || f.type) === filterType.value;
        let matchesPrice = true;
        if (filterPrice.value !== 'all') {
            const price = Number(f.price) || 0;
            if (filterPrice.value === '2000+') matchesPrice = price >= 2000;
            else {
                const [min, max] = filterPrice.value.split('-').map(Number);
                matchesPrice = price >= min && price <= max;
            }
        }
        const matchesCity = filterCity.value === 'all' || (f.city || '').toLowerCase() === filterCity.value;
        return matchesSearch && matchesType && matchesPrice && matchesCity;
    });
});

const propertyTypes = [
    { value: 'all', label: 'Tous les types' },
    { value: 'apartment', label: 'Appartement' },
    { value: 'house', label: 'Maison' },
    { value: 'studio', label: 'Studio' },
    { value: 'loft', label: 'Loft' },
    { value: 'villa', label: 'Villa' },
];

const priceRanges = [
    { value: 'all', label: 'Tous les prix' },
    { value: '0-500', label: '0 - 500€' },
    { value: '500-1000', label: '500 - 1000€' },
    { value: '1000-1500', label: '1000 - 1500€' },
    { value: '1500-2000', label: '1500 - 2000€' },
    { value: '2000+', label: '2000€ et plus' },
];

const cities = [
    { value: 'all', label: 'Toutes les villes' },
    { value: 'paris', label: 'Paris' },
    { value: 'lyon', label: 'Lyon' },
    { value: 'marseille', label: 'Marseille' },
    { value: 'bordeaux', label: 'Bordeaux' },
    { value: 'toulouse', label: 'Toulouse' },
];
</script>

<template>
    <Head title="Mes favoris" />

    <PropertyLayout
        :role="role"
        title="Mes favoris"
        subtitle="Retrouvez tous les biens que vous avez sauvegardés"
    >
        <template v-slot:actions>
            <Link :href="route('immo.favorites.export')" method="post" class="imo-btn-secondary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                Exporter
            </Link>
        </template>

        <div class="imo-page">
            <!-- Filtres -->
            <div class="imo-filters-bar">
                <div class="flex flex-1 items-center gap-4">
                    <div class="relative flex-1 max-w-md">
                        <svg class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Rechercher dans vos favoris..."
                            class="imo-search-input" />
                    </div>

                    <select v-model="filterType" class="imo-form-select-sm">
                        <option v-for="type in propertyTypes" :key="type.value" :value="type.value">
                            {{ type.label }}
                        </option>
                    </select>

                    <select v-model="filterPrice" class="imo-form-select-sm">
                        <option v-for="range in priceRanges" :key="range.value" :value="range.value">
                            {{ range.label }}
                        </option>
                    </select>

                    <select v-model="filterCity" class="imo-form-select-sm">
                        <option v-for="city in cities" :key="city.value" :value="city.value">
                            {{ city.label }}
                        </option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button
                        type="button"
                        @click.prevent="viewMode = 'grid'"
                        :class="['imo-btn-icon', { 'imo-btn-icon-active': viewMode === 'grid' }]"
                        title="Vue grille"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        </button>
                    <button
                        type="button"
                        @click.prevent="viewMode = 'list'"
                        :class="['imo-btn-icon', { 'imo-btn-icon-active': viewMode === 'list' }]"
                        title="Vue liste"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Favoris -->
            <div v-if="favorites.length">
                <div v-if="filteredFavorites.length === 0" class="imo-empty-state">
                    <p>Aucun favori ne correspond à votre recherche.</p>
                </div>
                <div v-else-if="viewMode === 'grid'" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <div
                        v-for="favorite in filteredFavorites"
                        :key="favorite.id"
                        class="imo-favorite-card group"
                    >
                    <div class="relative aspect-[4/3] overflow-hidden rounded-t-xl bg-slate-100">
                        <img
                            :src="favorite.image || '/placeholder-property.jpg'"
                            :alt="favorite.title"
                            class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                        />
                        <Link
                            :href="route('immo.favorites.destroy', favorite.id)"
                            method="delete"
                            class="absolute right-3 top-3 rounded-full bg-white/90 p-2 text-red-500 backdrop-blur-sm transition hover:bg-white hover:scale-110"
                            title="Retirer des favoris"
                        >
                            <svg class="h-5 w-5 fill-current" viewBox="0 0 24 24">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                            </svg>
                        </Link>
                        <span
                            v-if="favorite.badge"
                            class="absolute left-3 top-3 rounded-full px-2 py-1 text-xs font-semibold"
                            :class="{
                                'bg-emerald-500 text-white': favorite.badge === 'Nouveau',
                                'bg-blue-500 text-white': favorite.badge === 'Récent',
                                'bg-violet-500 text-white': favorite.badge === 'Populaire',
                            }"
                        >
                            {{ favorite.badge }}
                        </span>
                    </div>

                    <div class="rounded-b-xl border border-t-0 border-slate-200 bg-white p-4">
                        <div class="mb-2 flex items-start justify-between">
                            <h3 class="font-semibold text-slate-900 line-clamp-2">{{ favorite.title }}</h3>
                            <button class="ml-2 shrink-0 text-slate-400 hover:text-slate-600">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                </svg>
                            </button>
                        </div>

                        <p class="text-sm text-slate-500 line-clamp-1">{{ favorite.address }}, {{ favorite.city }}</p>

                        <div class="mt-3 flex flex-wrap gap-2 text-xs text-slate-600">
                            <span class="flex items-center gap-1 rounded-full bg-slate-100 px-2 py-1">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                                </svg>
                                {{ favorite.surface }} m²
                            </span>
                            <span class="flex items-center gap-1 rounded-full bg-slate-100 px-2 py-1">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                {{ favorite.rooms }} p.
                            </span>
                            <span class="flex items-center gap-1 rounded-full bg-slate-100 px-2 py-1">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                {{ favorite.bedrooms }} ch.
                            </span>
                        </div>

                        <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3">
                            <div>
                                <p class="text-xl font-bold text-emerald-600">{{ favorite.price }}€</p>
                                <p class="text-xs text-slate-500">{{ favorite.transaction_type === 'rent' ? '/mois' : '' }}</p>
                            </div>
                            <Link
                                :href="favorite.url"
                                class="imo-btn-sm imo-btn-sm-primary"
                            >
                                Voir détails
                            </Link>
                        </div>

                        <div class="mt-3 flex items-center justify-between text-xs text-slate-500">
                            <span class="flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Ajouté {{ favorite.added_at }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                {{ favorite.views }} vues
                            </span>
                        </div>
                    </div>
                </div>
            </div>

                <div v-else class="space-y-4">
                    <div
                        v-for="favorite in filteredFavorites"
                        :key="favorite.id"
                        class="imo-property-card"
                    >
                        <div class="flex gap-4">
                            <div class="relative h-32 w-48 shrink-0 overflow-hidden rounded-lg bg-slate-100">
                                <img
                                    :src="favorite.image || '/placeholder-property.jpg'"
                                    :alt="favorite.title"
                                    class="h-full w-full object-cover"
                                />
                            </div>
                            <div class="flex flex-1 flex-col justify-between">
                                <div>
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <h3 class="text-lg font-semibold text-slate-900">{{ favorite.title }}</h3>
                                            <p class="mt-1 text-sm text-slate-500">{{ favorite.address }}, {{ favorite.city }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xl font-bold text-emerald-600">{{ favorite.price }}€</p>
                                            <p class="text-xs text-slate-500">{{ favorite.transaction_type === 'rent' ? '/mois' : '' }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-3 flex flex-wrap gap-2 text-xs text-slate-600">
                                        <span class="rounded-full bg-slate-100 px-2 py-1">{{ favorite.surface }} m²</span>
                                        <span class="rounded-full bg-slate-100 px-2 py-1">{{ favorite.rooms }} p.</span>
                                        <span class="rounded-full bg-slate-100 px-2 py-1">{{ favorite.bedrooms }} ch.</span>
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center justify-between">
                                    <span class="text-xs text-slate-500">Ajouté {{ favorite.added_at }}</span>
                                    <Link :href="favorite.url" class="imo-btn-sm imo-btn-sm-primary">Voir détails</Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- État vide -->
            <div v-else class="imo-empty-state-lg">
                <svg class="mx-auto h-16 w-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <h3 class="mt-4 text-lg font-semibold text-slate-900">Aucun favori pour le moment</h3>
                <p class="mt-2 text-sm text-slate-500">Sauvegardez les biens qui vous intéressent pour les retrouver facilement.</p>
                <Link
                    :href="route('immo.search')"
                    class="imo-btn-primary mt-4"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Rechercher des biens
                </Link>
            </div>
        </div>
    </PropertyLayout>
</template>
