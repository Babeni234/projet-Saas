<script setup>
defineProps({
    property: { type: Object, required: true },
});

const statusClass = {
    publié: 'imo-badge-success',
    brouillon: 'imo-badge-warning',
    loué: 'imo-badge-info',
    archivé: 'imo-badge-muted',
};
</script>

<template>
    <article class="imo-property-card">
        <div
            class="imo-property-thumb"
            :style="property.image ? { backgroundImage: `url(${property.image})`, backgroundSize: 'cover' } : {}"
        >
            <div
                v-if="!property.image"
                class="flex h-full items-center justify-center text-white/60"
            >
                <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
        </div>
        <div class="min-w-0 flex-1">
            <div class="flex flex-wrap items-start justify-between gap-2">
                <div>
                    <h3 class="font-bold text-slate-900">{{ property.title }}</h3>
                    <p class="mt-0.5 text-sm text-slate-500">{{ property.address }}</p>
                </div>
                <span :class="statusClass[property.status] ?? 'imo-badge-muted'">
                    {{ property.status }}
                </span>
            </div>
            <div class="mt-3 flex flex-wrap items-center gap-2 text-sm">
                <span class="rounded-md bg-slate-100 px-2 py-0.5 font-medium text-slate-600">{{ property.type }}</span>
                <span class="text-slate-300">·</span>
                <span class="text-slate-600">{{ property.rooms }} pièces</span>
                <span class="text-slate-300">·</span>
                <span class="font-bold text-emerald-700">{{ property.price }}</span>
            </div>
            <div class="mt-4 flex flex-wrap gap-2">
                <button type="button" class="imo-btn-primary !py-2 !text-xs">
                    Modifier
                </button>
                <button type="button" class="imo-btn-secondary !py-2 !text-xs">
                    Voir l'annonce
                </button>
            </div>
        </div>
    </article>
</template>
