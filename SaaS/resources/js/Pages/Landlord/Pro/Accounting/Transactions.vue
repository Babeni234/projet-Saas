<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    transactions: Object,
    accounts: Array,
    categories: Array,
    filters: Object,
    types: Object,
    statuses: Object,
    payment_methods: Object,
});

function fm(v) {
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR', minimumFractionDigits: 0 }).format(v || 0);
}

function statusClass(s) {
    return s === 'completed' ? 'bg-emerald-100 text-emerald-700' : s === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-500';
}

const form = useForm({
    bank_account_id: '',
    category_id: '',
    type: 'expense',
    amount: '',
    description: '',
    transaction_date: new Date().toISOString().split('T')[0],
    status: 'completed',
    reference: '',
    payment_method: '',
    property_id: '',
    tenant_id: '',
});

function submit() {
    form.post(route('landlord.pro.accounting.transactions.store'));
}

const filterForm = useForm({
    type: props.filters?.type || '',
    status: props.filters?.status || '',
    bank_account_id: props.filters?.bank_account_id || '',
    category_id: props.filters?.category_id || '',
    date_from: props.filters?.date_from || '',
    date_to: props.filters?.date_to || '',
    search: props.filters?.search || '',
});

function applyFilters() {
    filterForm.get(route('landlord.pro.accounting.transactions'), { preserveState: true });
}

function resetFilters() {
    filterForm.clearErrors();
    filterForm.type = '';
    filterForm.status = '';
    filterForm.bank_account_id = '';
    filterForm.category_id = '';
    filterForm.date_from = '';
    filterForm.date_to = '';
    filterForm.search = '';
    filterForm.get(route('landlord.pro.accounting.transactions'), { preserveState: true });
}

function destroy(id) {
    if (confirm('Supprimer cette transaction ?')) {
        useForm().delete(route('landlord.pro.accounting.transactions.destroy', id));
    }
}
</script>

<template>
    <Head title="Transactions" />
    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Transactions</h2>
                    <p class="text-sm text-gray-500">Toutes les entrées et sorties d'argent</p>
                </div>
                <Link :href="route('landlord.pro.accounting.index')"
                    class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    Tableau de bord
                </Link>
            </div>
        </template>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-4">
                <!-- Filters -->
                <div class="rounded-xl border border-gray-200 bg-white p-4">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <input v-model="filterForm.search" placeholder="Rechercher..."
                            class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                        <select v-model="filterForm.type"
                            class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            <option value="">Tous les types</option>
                            <option v-for="(l, k) in types" :key="k" :value="k">{{ l }}</option>
                        </select>
                        <select v-model="filterForm.status"
                            class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            <option value="">Tous les statuts</option>
                            <option v-for="(l, k) in statuses" :key="k" :value="k">{{ l }}</option>
                        </select>
                        <select v-model="filterForm.bank_account_id"
                            class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            <option value="">Tous les comptes</option>
                            <option v-for="a in accounts" :key="a.id" :value="a.id">{{ a.name }}</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2 mt-3">
                        <button @click="applyFilters"
                            class="rounded-lg bg-indigo-600 px-4 py-1.5 text-xs font-medium text-white hover:bg-indigo-700 transition">
                            Filtrer
                        </button>
                        <button @click="resetFilters"
                            class="rounded-lg border border-gray-300 px-4 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-50 transition">
                            Réinitialiser
                        </button>
                    </div>
                </div>

                <!-- List -->
                <div v-if="transactions.data.length === 0" class="rounded-xl border border-gray-200 bg-white p-12 text-center text-sm text-gray-400">
                    Aucune transaction trouvée.
                </div>
                <div v-else class="space-y-2">
                    <div v-for="tx in transactions.data" :key="tx.id"
                        class="rounded-xl border border-gray-200 bg-white p-4 flex items-center justify-between hover:border-gray-300 transition">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="h-9 w-9 rounded-full flex items-center justify-center shrink-0"
                                :class="tx.type === 'income' ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600'">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path v-if="tx.type === 'income'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ tx.description || 'Sans description' }}</p>
                                <div class="flex items-center gap-2 text-xs text-gray-400 mt-0.5">
                                    <span>{{ tx.bank_account?.name }}</span>
                                    <span v-if="tx.category" class="inline-flex items-center gap-1">
                                        <span class="h-2 w-2 rounded-full" :style="{ backgroundColor: tx.category.color }"></span>
                                        {{ tx.category.name }}
                                    </span>
                                    <span>{{ new Date(tx.transaction_date).toLocaleDateString('fr-FR') }}</span>
                                    <span v-if="tx.reference" class="font-mono">#{{ tx.reference }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium"
                                :class="statusClass(tx.status)">
                                {{ statuses[tx.status] || tx.status }}
                            </span>
                            <span class="text-sm font-bold"
                                :class="tx.type === 'income' ? 'text-emerald-600' : 'text-red-600'">
                                {{ tx.type === 'income' ? '+' : '-' }}{{ fm(tx.amount) }}
                            </span>
                            <button @click="destroy(tx.id)" class="text-gray-300 hover:text-red-500 transition">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div v-if="transactions.last_page > 1" class="flex items-center justify-center gap-2 pt-4">
                        <Link v-for="link in transactions.links" :key="link.label"
                            :href="link.url || '#'"
                            v-html="link.label"
                            class="rounded-lg px-3 py-1.5 text-xs font-medium transition"
                            :class="link.active ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200'" />
                    </div>
                </div>
            </div>

            <!-- Add form -->
            <div class="rounded-xl border border-gray-200 bg-white p-5 h-fit">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Nouvelle transaction</h3>
                <form @submit.prevent="submit" class="space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700">Type *</label>
                            <select v-model="form.type" required
                                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                <option v-for="(l, k) in types" :key="k" :value="k">{{ l }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700">Montant *</label>
                            <input v-model="form.amount" type="number" step="0.01" min="0.01" required
                                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Compte *</label>
                        <select v-model="form.bank_account_id" required
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            <option value="">Sélectionner...</option>
                            <option v-for="a in accounts" :key="a.id" :value="a.id">{{ a.name }}</option>
                        </select>
                        <p v-if="form.errors.bank_account_id" class="mt-1 text-xs text-red-600">{{ form.errors.bank_account_id }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Catégorie</label>
                        <select v-model="form.category_id"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            <option value="">Sélectionner...</option>
                            <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Description</label>
                        <input v-model="form.description" type="text"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                            placeholder="Ex: Loyer janvier" />
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700">Date *</label>
                            <input v-model="form.transaction_date" type="date" required
                                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700">Statut *</label>
                            <select v-model="form.status" required
                                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                <option v-for="(l, k) in statuses" :key="k" :value="k">{{ l }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700">Référence</label>
                            <input v-model="form.reference" type="text"
                                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700">Moyen de paiement</label>
                            <select v-model="form.payment_method"
                                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                <option value="">Sélectionner...</option>
                                <option v-for="(l, k) in payment_methods" :key="k" :value="k">{{ l }}</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" :disabled="form.processing"
                        class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50 transition">
                        {{ form.processing ? 'Enregistrement...' : 'Enregistrer' }}
                    </button>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
