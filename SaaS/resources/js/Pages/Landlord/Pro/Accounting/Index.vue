<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    accounts: Array,
    total_balance: Number,
    recent_transactions: Array,
    monthly_income: Number,
    monthly_expense: Number,
    pending_count: Number,
    unreconciled_count: Number,
    trend: Array,
    budgets: Array,
});

function fm(v) {
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR', minimumFractionDigits: 0 }).format(v || 0);
}

function pct(v) {
    return v > 0 ? v.toFixed(1) : '0';
}
</script>

<template>
    <Head title="Comptabilité" />
    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Comptabilité</h2>
                    <p class="text-sm text-gray-500">Gestion financière, comptes bancaires et budget</p>
                </div>
                <div class="flex items-center gap-2">
                    <Link :href="route('landlord.pro.accounting.transactions')"
                        class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                        Voir les transactions
                    </Link>
                </div>
            </div>
        </template>

        <div class="space-y-6">
            <!-- KPI Cards -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-xl border border-gray-200 bg-white p-5">
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-500">Solde total</p>
                    <p class="mt-1 text-2xl font-bold text-gray-900">{{ fm(total_balance) }}</p>
                    <p class="mt-0.5 text-xs text-gray-400">{{ accounts.length }} compte(s)</p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-5">
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-500">Revenus du mois</p>
                    <p class="mt-1 text-2xl font-bold text-emerald-600">{{ fm(monthly_income) }}</p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-5">
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-500">Dépenses du mois</p>
                    <p class="mt-1 text-2xl font-bold text-red-600">{{ fm(monthly_expense) }}</p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-5">
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-500">En attente / Non rapprochés</p>
                    <p class="mt-1 text-2xl font-bold text-amber-600">{{ pending_count }} <span class="text-sm font-normal text-gray-400">/ {{ unreconciled_count }}</span></p>
                </div>
            </div>

            <!-- Trend Chart + Recent Transactions -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="rounded-xl border border-gray-200 bg-white p-5">
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Revenus / Dépenses (6 mois)</h3>
                    <div class="flex items-end gap-2 h-40">
                        <div v-for="(item, i) in trend" :key="i" class="flex-1 flex flex-col items-center gap-1 h-full justify-end">
                            <div class="w-full flex flex-col gap-0.5 items-center justify-end" style="height: 100%">
                                <div class="w-5 rounded-t bg-emerald-400 transition-all"
                                    :style="{ height: Math.max(4, (item.income / Math.max(...trend.map(t => Math.max(t.income, t.expense, 1))) * 100)) + '%' }">
                                </div>
                                <div class="w-5 rounded-t bg-red-400 transition-all"
                                    :style="{ height: Math.max(4, (item.expense / Math.max(...trend.map(t => Math.max(t.income, t.expense, 1))) * 100)) + '%' }">
                                </div>
                            </div>
                            <span class="text-[10px] text-gray-400">{{ item.month }}</span>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-5">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Dernières transactions</h3>
                    <div v-if="recent_transactions.length === 0" class="py-8 text-center text-sm text-gray-400">
                        Aucune transaction pour le moment.
                    </div>
                    <div v-else class="space-y-2">
                        <div v-for="tx in recent_transactions" :key="tx.id"
                            class="flex items-center justify-between rounded-lg p-2 hover:bg-gray-50 transition">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="h-8 w-8 rounded-full flex items-center justify-center shrink-0"
                                    :class="tx.type === 'income' ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600'">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path v-if="tx.type === 'income'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ tx.description || 'Sans description' }}</p>
                                    <p class="text-xs text-gray-400">{{ tx.bank_account?.name }} · {{ new Date(tx.transaction_date).toLocaleDateString('fr-FR') }}</p>
                                </div>
                            </div>
                            <span class="text-sm font-semibold shrink-0 ml-3"
                                :class="tx.type === 'income' ? 'text-emerald-600' : 'text-red-600'">
                                {{ tx.type === 'income' ? '+' : '-' }}{{ fm(tx.amount) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comptes bancaires + Budgets -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="rounded-xl border border-gray-200 bg-white p-5">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-900">Comptes bancaires</h3>
                        <Link :href="route('landlord.pro.accounting.accounts')"
                            class="text-xs font-medium text-indigo-600 hover:text-indigo-800">Gérer</Link>
                    </div>
                    <div v-if="accounts.length === 0" class="py-6 text-center text-sm text-gray-400">
                        Aucun compte bancaire. <Link :href="route('landlord.pro.accounting.accounts')" class="text-indigo-600 underline">Ajoutez-en un</Link>.
                    </div>
                    <div v-else class="space-y-2">
                        <div v-for="acc in accounts" :key="acc.id"
                            class="flex items-center justify-between rounded-lg p-3 border border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-lg flex items-center justify-center text-white text-xs font-bold shrink-0"
                                    :style="{ backgroundColor: acc.color || '#6366f1' }">
                                    {{ acc.name.charAt(0).toUpperCase() }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ acc.name }}</p>
                                    <p class="text-xs text-gray-400">{{ acc.bank_name || acc.type }}</p>
                                </div>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">{{ fm(acc.balance) }}</span>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-5">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-900">Budgets du mois</h3>
                        <Link :href="route('landlord.pro.accounting.budgets')"
                            class="text-xs font-medium text-indigo-600 hover:text-indigo-800">Gérer</Link>
                    </div>
                    <div v-if="budgets.length === 0" class="py-6 text-center text-sm text-gray-400">
                        Aucun budget défini.
                    </div>
                    <div v-else class="space-y-3">
                        <div v-for="b in budgets" :key="b.id">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-700">{{ b.category?.name || 'Sans catégorie' }}</span>
                                <span class="text-gray-500">{{ fm(b.spent) }} / {{ fm(b.amount) }}</span>
                            </div>
                            <div class="mt-1 h-2 rounded-full bg-gray-100 overflow-hidden">
                                <div class="h-full rounded-full transition-all"
                                    :class="(b.spent / b.amount) > 1 ? 'bg-red-400' : 'bg-indigo-400'"
                                    :style="{ width: Math.min(100, (b.spent / b.amount) * 100) + '%' }">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
