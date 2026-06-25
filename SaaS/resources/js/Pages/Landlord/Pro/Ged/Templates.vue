<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    templates: Array,
    types: Object,
});

const form = useForm({
    name: '',
    type: 'other',
    description: '',
    color: '#6366f1',
});

function submit() {
    form.post(route('landlord.pro.ged.templates.store'));
}

function destroy(id) {
    if (confirm('Supprimer ce template ?')) {
        useForm().delete(route('landlord.pro.ged.templates.destroy', id));
    }
}
</script>

<template>
    <Head title="Templates de documents" />
    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Templates</h2>
                    <p class="text-sm text-gray-500">Modèles de documents prêts à l'emploi</p>
                </div>
                <Link :href="route('landlord.pro.ged.index')"
                    class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    Retour à la GED
                </Link>
            </div>
        </template>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-3">
                <div v-if="templates.length === 0" class="rounded-xl border border-gray-200 bg-white p-12 text-center text-sm text-gray-400">
                    Aucun template. Créez-en un.
                </div>
                <div v-for="t in templates" :key="t.id"
                    class="rounded-xl border border-gray-200 bg-white p-5 flex items-center justify-between hover:border-gray-300 transition">
                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 rounded-xl flex items-center justify-center text-white text-sm font-bold"
                            :style="{ backgroundColor: t.color || '#6366f1' }">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ t.name }}</p>
                            <p class="text-xs text-gray-400">{{ types[t.type] || t.type }} · {{ t.is_built_in ? 'Intégré' : 'Personnel' }}</p>
                            <p v-if="t.description" class="text-xs text-gray-400 mt-0.5">{{ t.description }}</p>
                        </div>
                    </div>
                    <button v-if="!t.is_built_in" @click="destroy(t.id)"
                        class="text-gray-300 hover:text-red-500 transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 h-fit">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Nouveau template</h3>
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Nom *</label>
                        <input v-model="form.name" type="text" required
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                            placeholder="Ex: Bail" />
                        <p v-if="form.errors.name" class="mt-1 text-xs text-red-600">{{ form.errors.name }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Type *</label>
                        <select v-model="form.type" required
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            <option v-for="(l, k) in types" :key="k" :value="k">{{ l }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Description</label>
                        <textarea v-model="form.description" rows="3"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Couleur</label>
                        <input v-model="form.color" type="color"
                            class="mt-1 block w-full h-9 rounded-lg border border-gray-300 cursor-pointer" />
                    </div>
                    <button type="submit" :disabled="form.processing"
                        class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50 transition">
                        {{ form.processing ? 'Création...' : 'Créer le template' }}
                    </button>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
