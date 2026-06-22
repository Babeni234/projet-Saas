<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

const props = defineProps({
    fiscalYear: { type: Object, required: true },
});

const expenseForm = useForm({ category: '', description: '', amount: '', expense_date: '', deductible: false });
const finalizeForm = useForm({});

function addExpense() { expenseForm.post(route('landlord.fiscal.expenses.store', props.fiscalYear.id), { preserveScroll: true }); }
function finalize() { finalizeForm.post(route('landlord.fiscal.finalize', props.fiscalYear.id), { preserveScroll: true }); }
</script>

<template>
    <Head :title="'Fiscal ' + fiscalYear.year" />
    <PropertyLayout role="bailleur" :title="'Année ' + fiscalYear.year" subtitle="Détail des revenus et dépenses">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-sm text-gray-500">Revenus</p>
                <p class="text-2xl font-bold text-green-600">{{ fiscalYear.total_revenue }}€</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-sm text-gray-500">Dépenses</p>
                <p class="text-2xl font-bold text-red-600">{{ fiscalYear.total_expenses }}€</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-sm text-gray-500">Revenu net</p>
                <p class="text-2xl font-bold" :class="fiscalYear.net_income >= 0 ? 'text-green-600' : 'text-red-600'">
                    {{ fiscalYear.net_income }}€
                </p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold mb-4">Dépenses</h3>
            <form @submit.prevent="addExpense" class="grid grid-cols-2 gap-4 mb-6 p-4 bg-gray-50 rounded">
                <div>
                    <label class="block text-xs font-medium text-gray-700">Catégorie</label>
                    <input v-model="expenseForm.category" required class="imo-input w-full" placeholder="Ex: Travaux, Charges..." />
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700">Montant</label>
                    <input v-model="expenseForm.amount" type="number" step="0.01" required class="imo-input w-full" />
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-medium text-gray-700">Description</label>
                    <input v-model="expenseForm.description" required class="imo-input w-full" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700">Date</label>
                    <input v-model="expenseForm.expense_date" type="date" required class="imo-input w-full" />
                </div>
                <div class="flex items-end">
                    <label class="flex items-center gap-2">
                        <input v-model="expenseForm.deductible" type="checkbox" />
                        <span class="text-xs">Déductible</span>
                    </label>
                </div>
                <button type="submit" :disabled="expenseForm.processing" class="imo-btn-primary col-span-2">Ajouter la dépense</button>
            </form>

            <div v-if="fiscalYear.expenses.length === 0" class="text-gray-500">Aucune dépense.</div>
            <div v-for="e in fiscalYear.expenses" :key="e.id" class="border-b py-2 flex justify-between">
                <div>
                    <p class="font-medium">{{ e.category }}</p>
                    <p class="text-sm text-gray-500">{{ e.description }}</p>
                    <p class="text-xs text-gray-400">{{ new Date(e.expense_date).toLocaleDateString() }}</p>
                </div>
                <div class="text-right">
                    <p class="font-semibold">{{ e.amount }}€</p>
                    <span v-if="e.deductible" class="text-xs text-green-600">Déductible</span>
                </div>
            </div>
        </div>

        <div v-if="fiscalYear.status === 'brouillon'" class="mt-4">
            <button @click="finalize" :disabled="finalizeForm.processing" class="imo-btn-primary">Finaliser l'année fiscale</button>
        </div>
    </PropertyLayout>
</template>
