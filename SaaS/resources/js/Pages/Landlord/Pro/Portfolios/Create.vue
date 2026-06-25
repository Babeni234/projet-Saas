<script setup>
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    portfolio: Object,
    properties: Array,
    selected_ids: Array,
});

const form = useForm({
    name: props.portfolio?.name || '',
    description: props.portfolio?.description || '',
    color: props.portfolio?.color || '#6366f1',
    property_ids: props.selected_ids || [],
});

function toggleProperty(id) {
    const idx = form.property_ids.indexOf(id);
    if (idx >= 0) {
        form.property_ids.splice(idx, 1);
    } else {
        form.property_ids.push(id);
    }
}

function submit() {
    if (props.portfolio) {
        form.put(route('landlord.pro.portfolios.update', props.portfolio.id));
    } else {
        form.post(route('landlord.pro.portfolios.store'));
    }
}

const colors = ['#6366f1', '#059669', '#d97706', '#dc2626', '#7c3aed', '#0891b2', '#db2777', '#0f766e'];
</script>

<template>
    <Head :title="portfolio ? 'Modifier le portfolio' : 'Nouveau portfolio'" />
    <AppLayout>
        <template #header>
            <div>
                <h2 class="text-lg font-semibold text-gray-900">{{ portfolio ? 'Modifier le portfolio' : 'Nouveau portfolio' }}</h2>
                <p class="text-sm text-gray-500">{{ portfolio ? 'Modifiez les informations du groupe de biens' : 'Créez un groupe pour organiser vos biens' }}</p>
            </div>
        </template>

        <form @submit.prevent="submit" class="max-w-2xl space-y-6">
            <div class="rounded-xl border border-gray-200 bg-white p-6 space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nom du portfolio</label>
                    <input v-model="form.name" type="text" required
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                        placeholder="Ex: Résidences principales, Investissements locatifs..." />
                    <p v-if="form.errors.name" class="mt-1 text-xs text-red-600">{{ form.errors.name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea v-model="form.description" rows="3"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                        placeholder="Description optionnelle de ce portfolio..." />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Couleur</label>
                    <div class="flex gap-2">
                        <button v-for="c in colors" :key="c" type="button" @click="form.color = c"
                            class="h-8 w-8 rounded-full border-2 transition"
                            :class="form.color === c ? 'border-gray-900 scale-110' : 'border-transparent'"
                            :style="{ backgroundColor: c }" />
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-6">
                <h3 class="mb-3 text-sm font-semibold text-gray-900">Biens dans ce portfolio</h3>
                <p class="mb-3 text-xs text-gray-500">{{ properties.length }} bien(s) disponible(s)</p>

                <div class="space-y-1.5 max-h-64 overflow-y-auto">
                    <label v-for="p in properties" :key="p.id"
                        class="flex cursor-pointer items-center gap-3 rounded-lg px-3 py-2 text-sm hover:bg-gray-50 transition"
                        :class="form.property_ids.includes(p.id) ? 'bg-indigo-50' : ''">
                        <input type="checkbox" :checked="form.property_ids.includes(p.id)" @change="toggleProperty(p.id)"
                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                        <div class="flex-1">
                            <span class="font-medium text-gray-900">{{ p.title }}</span>
                            <span class="ml-2 text-xs text-gray-500">{{ p.city }} — {{ p.property_type }}</span>
                        </div>
                    </label>
                    <p v-if="!properties.length" class="text-sm text-gray-400 text-center py-4">
                        Aucun bien disponible. <Link :href="route('landlord.properties.create')" class="text-indigo-600 underline">Créez-en un</Link> d'abord.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" :disabled="form.processing"
                    class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50">
                    {{ portfolio ? 'Enregistrer' : 'Créer le portfolio' }}
                </button>
                <Link :href="route('landlord.pro.portfolios.index')" class="text-sm text-gray-500 hover:text-gray-700">Annuler</Link>
            </div>
        </form>
    </AppLayout>
</template>
