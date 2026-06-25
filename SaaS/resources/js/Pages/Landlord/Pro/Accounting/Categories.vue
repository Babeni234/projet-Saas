<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    categories: Array,
    types: Object,
});

const form = useForm({
    name: '',
    type: 'expense',
    color: '#6366f1',
    icon: '',
});

function submit() {
    form.post(route('landlord.pro.accounting.categories.store'));
}
</script>

<template>
    <Head title="Catégories comptables" />
    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Catégories comptables</h2>
                    <p class="text-sm text-gray-500">Catégorisez vos revenus et dépenses</p>
                </div>
                <Link :href="route('landlord.pro.accounting.index')"
                    class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    Tableau de bord
                </Link>
            </div>
        </template>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-4">
                <!-- Income categories -->
                <div class="rounded-xl border border-gray-200 bg-white p-5">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-400"></span>
                        Revenus
                    </h3>
                    <div v-if="categories.filter(c => c.type === 'income').length === 0" class="py-4 text-sm text-gray-400 text-center">
                        Aucune catégorie de revenu.
                    </div>
                    <div v-else class="space-y-2">
                        <div v-for="cat in categories.filter(c => c.type === 'income')" :key="cat.id"
                            class="flex items-center justify-between rounded-lg p-3 hover:bg-gray-50 transition">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-lg flex items-center justify-center"
                                    :style="{ backgroundColor: cat.color + '20', color: cat.color }">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ cat.name }}</span>
                            </div>
                            <span class="text-xs text-gray-400">Ordre: {{ cat.sort_order }}</span>
                        </div>
                    </div>
                </div>

                <!-- Expense categories -->
                <div class="rounded-xl border border-gray-200 bg-white p-5">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-red-400"></span>
                        Dépenses
                    </h3>
                    <div v-if="categories.filter(c => c.type === 'expense').length === 0" class="py-4 text-sm text-gray-400 text-center">
                        Aucune catégorie de dépense.
                    </div>
                    <div v-else class="space-y-2">
                        <div v-for="cat in categories.filter(c => c.type === 'expense')" :key="cat.id"
                            class="flex items-center justify-between rounded-lg p-3 hover:bg-gray-50 transition">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-lg flex items-center justify-center"
                                    :style="{ backgroundColor: cat.color + '20', color: cat.color }">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ cat.name }}</span>
                            </div>
                            <span class="text-xs text-gray-400">Ordre: {{ cat.sort_order }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add form -->
            <div class="rounded-xl border border-gray-200 bg-white p-5 h-fit">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Nouvelle catégorie</h3>
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Nom *</label>
                        <input v-model="form.name" type="text" required
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                            placeholder="Ex: Loyer, Charges, Travaux..." />
                        <p v-if="form.errors.name" class="mt-1 text-xs text-red-600">{{ form.errors.name }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Type *</label>
                        <div class="mt-1 grid grid-cols-2 gap-2">
                            <label v-for="(l, k) in types" :key="k"
                                class="flex cursor-pointer items-center justify-center rounded-lg border p-2 text-sm transition"
                                :class="form.type === k ? 'border-indigo-300 bg-indigo-50 text-indigo-700' : 'border-gray-200 hover:bg-gray-50 text-gray-600'">
                                <input type="radio" :value="k" v-model="form.type" class="sr-only" />
                                {{ l }}
                            </label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Couleur</label>
                        <input v-model="form.color" type="color"
                            class="mt-1 block w-full h-9 rounded-lg border border-gray-300 cursor-pointer" />
                    </div>
                    <button type="submit" :disabled="form.processing"
                        class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50 transition">
                        {{ form.processing ? 'Création...' : 'Créer la catégorie' }}
                    </button>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
