<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    budgets: Array,
    categories: Array,
    month: Number,
    year: Number,
});

function fm(v) {
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR', minimumFractionDigits: 0 }).format(v || 0);
}

const form = useForm({
    category_id: '',
    month: props.month,
    year: props.year,
    amount: '',
});

function submit() {
    form.post(route('landlord.pro.accounting.budgets.store'));
}

function destroyBudget(id) {
    if (confirm('Supprimer ce budget ?')) {
        useForm().delete(route('landlord.pro.accounting.budgets.destroy', id));
    }
}

const months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
</script>

<template>
    <Head title="Budgets" />
    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Budgets</h2>
                    <p class="text-sm text-gray-500">Planification budgétaire mensuelle</p>
                </div>
                <div class="flex items-center gap-2">
                    <Link :href="route('landlord.pro.accounting.budgets', { month: month - 1 || 12, year: month === 1 ? year - 1 : year })"
                        class="rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-50 transition">
                        &larr; Mois précédent
                    </Link>
                    <Link :href="route('landlord.pro.accounting.budgets', { month: month + 1 > 12 ? 1 : month + 1, year: month === 12 ? year + 1 : year })"
                        class="rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-50 transition">
                        Mois suivant &rarr;
                    </Link>
                </div>
            </div>
        </template>

        <div class="mb-4">
            <span class="text-lg font-semibold text-gray-900">{{ months[month - 1] }} {{ year }}</span>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-3">
                <div v-if="budgets.length === 0" class="rounded-xl border border-gray-200 bg-white p-12 text-center text-sm text-gray-400">
                    Aucun budget défini pour ce mois.
                </div>
                <div v-for="b in budgets" :key="b.id"
                    class="rounded-xl border border-gray-200 bg-white p-5">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="h-3 w-3 rounded-full" :style="{ backgroundColor: b.category?.color || '#6366f1' }"></div>
                            <span class="text-sm font-semibold text-gray-900">{{ b.category?.name || 'Sans catégorie' }}</span>
                        </div>
                        <button @click="destroyBudget(b.id)" class="text-xs text-red-400 hover:text-red-600 transition">
                            Supprimer
                        </button>
                    </div>
                    <div class="flex items-center justify-between text-sm mb-1.5">
                        <span class="text-gray-500">Dépensé</span>
                        <span class="font-medium" :class="b.spent > b.amount ? 'text-red-600' : 'text-gray-900'">
                            {{ fm(b.spent) }} / {{ fm(b.amount) }}
                        </span>
                    </div>
                    <div class="h-2.5 rounded-full bg-gray-100 overflow-hidden">
                        <div class="h-full rounded-full transition-all"
                            :class="(b.spent / b.amount) > 1 ? 'bg-red-400' : 'bg-indigo-400'"
                            :style="{ width: Math.min(100, (b.spent / b.amount) * 100) + '%' }">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add form -->
            <div class="rounded-xl border border-gray-200 bg-white p-5 h-fit">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Ajouter un budget</h3>
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Catégorie *</label>
                        <select v-model="form.category_id" required
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            <option value="">Sélectionner...</option>
                            <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Montant budgété *</label>
                        <input v-model="form.amount" type="number" step="0.01" min="0" required
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                        <p v-if="form.errors.amount" class="mt-1 text-xs text-red-600">{{ form.errors.amount }}</p>
                    </div>
                    <button type="submit" :disabled="form.processing"
                        class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50 transition">
                        {{ form.processing ? 'Ajout...' : 'Ajouter le budget' }}
                    </button>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
