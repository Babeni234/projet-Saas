<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    accounts: Array,
    types: Object,
});

function fm(v) {
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(v || 0);
}

const form = useForm({
    name: '',
    bank_name: '',
    iban: '',
    bic: '',
    type: 'checking',
    balance: '',
    color: '#6366f1',
});

function submit() {
    form.post(route('landlord.pro.accounting.accounts.store'));
}
</script>

<template>
    <Head title="Comptes bancaires" />
    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Comptes bancaires</h2>
                    <p class="text-sm text-gray-500">{{ accounts.length }} compte(s) enregistré(s)</p>
                </div>
                <Link :href="route('landlord.pro.accounting.index')"
                    class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    Tableau de bord
                </Link>
            </div>
        </template>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- List -->
            <div class="lg:col-span-2 space-y-3">
                <div v-if="accounts.length === 0" class="rounded-xl border border-gray-200 bg-white p-12 text-center">
                    <p class="text-sm text-gray-400">Aucun compte bancaire. Ajoutez-en un avec le formulaire.</p>
                </div>
                <div v-for="acc in accounts" :key="acc.id"
                    class="rounded-xl border border-gray-200 bg-white p-5 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 rounded-xl flex items-center justify-center text-white text-sm font-bold shrink-0"
                            :style="{ backgroundColor: acc.color || '#6366f1' }">
                            {{ acc.name.charAt(0).toUpperCase() }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ acc.name }}</p>
                            <p class="text-xs text-gray-400">{{ acc.bank_name }} · {{ types[acc.type] }} · {{ acc.transactions_count }} transaction(s)</p>
                            <p v-if="acc.iban" class="text-xs text-gray-400 font-mono">{{ acc.iban }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-bold text-gray-900">{{ fm(acc.balance) }}</p>
                    </div>
                </div>
            </div>

            <!-- Add form -->
            <div class="rounded-xl border border-gray-200 bg-white p-5 h-fit">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Nouveau compte</h3>
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Nom *</label>
                        <input v-model="form.name" type="text" required
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                            placeholder="Ex: Compte pro" />
                        <p v-if="form.errors.name" class="mt-1 text-xs text-red-600">{{ form.errors.name }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Banque</label>
                        <input v-model="form.bank_name" type="text"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                            placeholder="Ex: BNP Paribas" />
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Type *</label>
                        <select v-model="form.type" required
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            <option v-for="(label, key) in types" :key="key" :value="key">{{ label }}</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700">Solde initial</label>
                            <input v-model="form.balance" type="number" step="0.01" min="0"
                                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700">Couleur</label>
                            <input v-model="form.color" type="color"
                                class="mt-1 block w-full h-9 rounded-lg border border-gray-300 cursor-pointer" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700">IBAN</label>
                        <input v-model="form.iban" type="text"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-mono focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700">BIC</label>
                        <input v-model="form.bic" type="text"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-mono focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                    </div>
                    <button type="submit" :disabled="form.processing"
                        class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50 transition">
                        {{ form.processing ? 'Création...' : 'Créer le compte' }}
                    </button>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
