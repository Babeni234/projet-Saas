<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({ portfolios: Array });

const colorMap = {
    '#6366f1': 'bg-indigo-500',
    '#059669': 'bg-emerald-500',
    '#d97706': 'bg-amber-500',
    '#dc2626': 'bg-red-500',
    '#7c3aed': 'bg-violet-500',
    '#0891b2': 'bg-cyan-500',
    '#db2777': 'bg-pink-500',
};
</script>

<template>
    <Head title="Portfolios" />
    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Portfolios</h2>
                    <p class="text-sm text-gray-500">Organisez vos biens en groupes</p>
                </div>
                <Link :href="route('landlord.pro.portfolios.create')" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Nouveau portfolio
                </Link>
            </div>
        </template>

        <div v-if="portfolios.length" class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <div v-for="p in portfolios" :key="p.id"
                class="group rounded-xl border border-gray-200 bg-white overflow-hidden transition hover:shadow-lg">
                <div :class="[colorMap[p.color] || 'bg-indigo-500']" class="h-2" />
                <div class="p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ p.name }}</h3>
                            <p v-if="p.description" class="mt-1 text-xs text-gray-500 line-clamp-2">{{ p.description }}</p>
                        </div>
                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gray-50 text-sm font-bold text-gray-700">
                            {{ p.properties_count }}
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-3">
                        <Link :href="route('landlord.pro.portfolios.show', p.id)" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Voir</Link>
                        <Link :href="route('landlord.pro.portfolios.edit', p.id)" class="text-sm text-gray-500 hover:text-gray-700">Modifier</Link>
                        <span class="text-xs text-gray-400 ml-auto">Créé le {{ new Date(p.created_at).toLocaleDateString('fr-FR') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="rounded-xl border-2 border-dashed border-gray-300 p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
            <h3 class="mt-4 text-lg font-semibold text-gray-900">Aucun portfolio</h3>
            <p class="mt-2 text-sm text-gray-500">Créez votre premier portfolio pour organiser vos biens.</p>
            <Link :href="route('landlord.pro.portfolios.create')" class="mt-6 inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                Créer un portfolio
            </Link>
        </div>
    </AppLayout>
</template>
