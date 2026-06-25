<script setup>
import SuperAdminLayout from './layouts/SuperAdminLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, inject, computed } from 'vue';

const props = defineProps({
    users: {
        type: Array,
        default: () => [],
    },
    totalCollected: {
        type: Number,
        default: 0,
    },
    totalToReceive: {
        type: Number,
        default: 0,
    },
    selectedYear: {
        type: Number,
        default: () => new Date().getFullYear(),
    },
    availableYears: {
        type: Array,
        default: () => [new Date().getFullYear()],
    },
});

const theme = inject('theme');
const searchQuery = ref('');
const showPaymentModal = ref(false);
const selectedUser = ref(null);

// Payment Form
const paymentForm = useForm({
    billing_cycle: 'monthly',
    payment_method: 'Virement bancaire',
    amount: 0,
});

const openPaymentModal = (user) => {
    selectedUser.value = user;
    paymentForm.billing_cycle = user.billing_cycle;
    paymentForm.amount = user.price;
    paymentForm.payment_method = 'Virement bancaire';
    showPaymentModal.value = true;
};

const closePaymentModal = () => {
    showPaymentModal.value = false;
    selectedUser.value = null;
};

const submitPayment = () => {
    if (!selectedUser.value) return;
    
    paymentForm.post(route('superadmin.finance.record-payment', selectedUser.value.id), {
        onSuccess: () => {
            closePaymentModal();
        },
        preserveScroll: true,
    });
};

const handleYearChange = (year) => {
    router.get(route('superadmin.finance.index'), { year }, {
        preserveState: true,
        preserveScroll: true,
    });
};

const filteredUsers = computed(() => {
    if (!searchQuery.value) return props.users;
    const query = searchQuery.value.toLowerCase();
    return props.users.filter(u => 
        u.name.toLowerCase().includes(query) ||
        u.email.toLowerCase().includes(query) ||
        u.company_name.toLowerCase().includes(query) ||
        u.plan_name.toLowerCase().includes(query)
    );
});

const formatPrice = (amount) => {
    return Number(amount).toLocaleString('fr-FR') + ' FCFA';
};

const getStatusDetails = (status) => {
    switch (status) {
        case 'trial':
            return {
                label: "Période d'essai",
                class: 'bg-amber-500/10 border-amber-500/25 text-amber-400',
                dotClass: 'bg-amber-500 animate-pulse',
            };
        case 'paid':
            return {
                label: 'Abonné / À Jour',
                class: 'bg-emerald-500/10 border-emerald-500/25 text-emerald-400',
                dotClass: 'bg-emerald-500',
            };
        case 'unpaid':
        default:
            return {
                label: 'Expiré / Non Payé',
                class: 'bg-rose-500/10 border-rose-500/25 text-rose-400',
                dotClass: 'bg-rose-500 animate-pulse',
            };
    }
};
</script>

<template>
    <Head title="Gestion Financière & Abonnements" />

    <SuperAdminLayout>
        <div class="space-y-8 page-entrance">
            <!-- Header section -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-black tracking-tight text-[var(--text-main)]">Gestion des Finances</h2>
                    <p class="text-sm text-[var(--text-muted)] mt-1 font-medium">
                        Suivez les souscriptions aux abonnements, enregistrez les encaissements et gérez les statuts de paiement.
                    </p>
                </div>

                <!-- Year Filter Dropdown -->
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-[var(--text-muted)] uppercase tracking-wider">Filtrer par année :</span>
                    <select 
                        :value="selectedYear"
                        @change="handleYearChange($event.target.value)"
                        class="bg-[var(--bg-card)] border border-[var(--border-color)] text-[var(--text-main)] px-4 py-2 rounded-2xl text-xs font-bold outline-none focus:border-indigo-500 transition-all font-mono shadow-sm"
                    >
                        <option v-for="yr in availableYears" :key="yr" :value="yr">
                            {{ yr }}
                        </option>
                    </select>
                </div>
            </div>

            <!-- KPIs section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- KPI Total Collected -->
                <div 
                    class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-3xl p-6 shadow-[var(--card-shadow)] hover:-translate-y-1 hover:shadow-emerald-500/10 hover:border-emerald-500/30 transition-all duration-300 relative group overflow-hidden"
                >
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-gradient-to-b from-emerald-400 to-teal-500"></div>
                    <div class="flex items-center justify-between">
                        <div class="space-y-2">
                            <span class="text-xs font-bold text-[var(--text-muted)] uppercase tracking-wider block">Total perçu ({{ selectedYear }})</span>
                            <h3 class="text-3xl font-black tracking-tight text-[var(--text-main)] group-hover:scale-[1.01] transition-transform duration-300 font-mono">
                                {{ formatPrice(totalCollected) }}
                            </h3>
                        </div>
                        <div class="h-14 w-14 bg-emerald-500/10 border border-emerald-500/25 rounded-2xl flex items-center justify-center shadow-sm group-hover:scale-105 duration-300 text-emerald-400">
                            <i class="fa-solid fa-money-bill-trend-up text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- KPI Total To Receive -->
                <div 
                    class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-3xl p-6 shadow-[var(--card-shadow)] hover:-translate-y-1 hover:shadow-amber-500/10 hover:border-amber-500/30 transition-all duration-300 relative group overflow-hidden"
                >
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-gradient-to-b from-amber-400 to-orange-500"></div>
                    <div class="flex items-center justify-between">
                        <div class="space-y-2">
                            <span class="text-xs font-bold text-[var(--text-muted)] uppercase tracking-wider block">Total à percevoir / Reste à recouvrer</span>
                            <h3 class="text-3xl font-black tracking-tight text-[var(--text-main)] group-hover:scale-[1.01] transition-transform duration-300 font-mono">
                                {{ formatPrice(totalToReceive) }}
                            </h3>
                        </div>
                        <div class="h-14 w-14 bg-amber-500/10 border border-amber-500/25 rounded-2xl flex items-center justify-center shadow-sm group-hover:scale-105 duration-300 text-amber-400">
                            <i class="fa-solid fa-wallet text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Flash alerts -->
            <div v-if="$page.props.flash?.success" class="p-4 bg-emerald-500/10 border border-emerald-500/25 rounded-2xl flex items-center gap-3 text-emerald-400 text-xs font-semibold">
                <i class="fa-solid fa-circle-check text-base"></i>
                <span>{{ $page.props.flash.success }}</span>
            </div>

            <!-- Table Search and List -->
            <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-3xl shadow-[var(--card-shadow)] overflow-hidden">
                <!-- Search bar header -->
                <div class="p-6 border-b border-[var(--border-color)]/30 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-base font-extrabold text-[var(--text-main)]">Abonnements & Échéances</h3>
                        <p class="text-xs text-[var(--text-muted)] mt-1">Liste de tous les clients ayant sélectionné un forfait d'abonnement.</p>
                    </div>
                    <div class="relative max-w-md w-full">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[var(--text-muted)]">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </span>
                        <input 
                            v-model="searchQuery"
                            type="text" 
                            placeholder="Rechercher par nom, e-mail, entreprise..."
                            class="w-full pl-9 pr-4 py-2.5 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] outline-none focus:border-indigo-500 transition-all font-semibold shadow-inner"
                        />
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-[var(--border-color)]/30 text-[10px] font-black uppercase tracking-wider text-[var(--text-muted)] bg-[var(--bg-input)]/10">
                                <th class="py-4 px-6">Client / Entreprise</th>
                                <th class="py-4 px-6">Forfait</th>
                                <th class="py-4 px-6">Cycle</th>
                                <th class="py-4 px-6">Montant</th>
                                <th class="py-4 px-6">Échéance de paiement</th>
                                <th class="py-4 px-6">Statut de paiement</th>
                                <th class="py-4 px-6 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--border-color)]/20 text-xs">
                            <tr 
                                v-for="item in filteredUsers" 
                                :key="item.id"
                                class="hover:bg-[var(--bg-table-hover)] transition-colors text-[var(--text-main)]"
                                :class="{
                                    'bg-rose-500/[0.02]': item.status === 'unpaid',
                                    'bg-amber-500/[0.02]': item.status === 'trial',
                                    'bg-emerald-500/[0.01]': item.status === 'paid',
                                }"
                            >
                                <!-- Name and Email -->
                                <td class="py-4 px-6">
                                    <div class="font-bold text-sm">{{ item.name }}</div>
                                    <div class="text-[10px] text-[var(--text-muted)] mt-0.5">{{ item.email }}</div>
                                    <div class="text-[10px] text-indigo-500 font-semibold mt-1 flex items-center gap-1">
                                        <i class="fa-solid fa-building text-[8px]"></i>
                                        <span>{{ item.company_name }}</span>
                                    </div>
                                </td>
                                
                                <!-- Plan Name -->
                                <td class="py-4 px-6 uppercase font-bold text-[10px] tracking-wide">
                                    <span class="px-2.5 py-1 bg-slate-500/10 border border-slate-500/20 rounded-md">
                                        {{ item.plan_name }}
                                    </span>
                                </td>

                                <!-- Billing Cycle -->
                                <td class="py-4 px-6">
                                    <span class="font-semibold uppercase text-[10px]">
                                        {{ item.billing_cycle === 'yearly' ? 'Annuel' : 'Mensuel' }}
                                    </span>
                                </td>

                                <!-- Price -->
                                <td class="py-4 px-6 font-mono font-bold text-amber-600">
                                    {{ formatPrice(item.price) }}
                                </td>

                                <!-- Due Date -->
                                <td class="py-4 px-6 font-mono font-bold">
                                    <span :class="item.status === 'unpaid' ? 'text-rose-500' : (item.status === 'trial' ? 'text-amber-500' : 'text-[var(--text-main)]')">
                                        {{ item.due_date }}
                                    </span>
                                </td>

                                <!-- Payment Status badge -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-2">
                                        <span 
                                            class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider border shadow-sm flex items-center gap-1.5"
                                            :class="getStatusDetails(item.status).class"
                                        >
                                            <span class="w-1.5 h-1.5 rounded-full" :class="getStatusDetails(item.status).dotClass"></span>
                                            <span>{{ getStatusDetails(item.status).label }}</span>
                                        </span>
                                        <span 
                                            v-if="item.is_blocked" 
                                            class="px-2.5 py-0.5 rounded bg-red-500 text-white text-[8px] font-black uppercase tracking-wider flex items-center gap-1 shadow-sm"
                                        >
                                            <i class="fa-solid fa-lock"></i>
                                            <span>Bloqué</span>
                                        </span>
                                    </div>
                                </td>

                                <!-- Action Buttons -->
                                <td class="py-4 px-6 text-center">
                                    <button 
                                        @click="openPaymentModal(item)"
                                        class="px-3.5 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider flex items-center gap-1.5 shadow-sm transition-all hover:scale-105 active:scale-95 mx-auto"
                                        :class="item.status === 'paid' 
                                            ? 'bg-[var(--bg-btn-secondary)] border border-[var(--border-color)] text-[var(--text-main)] hover:bg-indigo-500/10'
                                            : 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-indigo-600/10'
                                        "
                                    >
                                        <i class="fa-solid fa-cash-register"></i>
                                        <span>Enregistrer paiement</span>
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="filteredUsers.length === 0">
                                <td colspan="7" class="py-12 text-center text-[var(--text-muted)] italic font-bold">
                                    Aucun abonné correspondant trouvé.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Manual Payment Modal (Premium design) -->
        <Transition name="fade">
            <div v-if="showPaymentModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
                <div 
                    class="bg-[var(--bg-card)] border border-[var(--border-color)] w-full max-w-lg rounded-3xl p-6 shadow-2xl space-y-6 relative page-entrance"
                    :class="theme === 'light' ? 'text-slate-900' : 'text-white'"
                >
                    <!-- Close button -->
                    <button 
                        @click="closePaymentModal" 
                        class="absolute top-4 right-4 h-8 w-8 rounded-full border border-[var(--border-color)] flex items-center justify-center hover:bg-[var(--bg-btn-secondary)] transition-colors text-[var(--text-muted)]"
                    >
                        <i class="fa-solid fa-xmark"></i>
                    </button>

                    <!-- Modal Header -->
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 bg-indigo-500/10 rounded-2xl flex items-center justify-center text-indigo-400 border border-indigo-500/15">
                            <i class="fa-solid fa-file-invoice-dollar text-base"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold">Enregistrer un Paiement Manuel</h3>
                            <p class="text-[10px] text-[var(--text-muted)] mt-0.5">Enregistrez un paiement reçu pour réactiver ou prolonger l'abonnement du client.</p>
                        </div>
                    </div>

                    <!-- Client Summary Details -->
                    <div class="bg-[var(--bg-input)]/25 border border-[var(--border-color)] rounded-2xl p-4 space-y-2">
                        <div class="flex justify-between text-xs">
                            <span class="text-[var(--text-muted)] font-medium">Client :</span>
                            <span class="font-bold">{{ selectedUser?.name }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-[var(--text-muted)] font-medium">Entreprise :</span>
                            <span class="font-bold text-indigo-500">{{ selectedUser?.company_name }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-[var(--text-muted)] font-medium">Forfait :</span>
                            <span class="font-extrabold uppercase text-[10px]">{{ selectedUser?.plan_name }}</span>
                        </div>
                    </div>

                    <!-- Payment Form -->
                    <form @submit.prevent="submitPayment" class="space-y-4">
                        <!-- Billing Cycle -->
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black uppercase tracking-wider text-[var(--text-muted)]">Cycle de facturation</label>
                            <select 
                                v-model="paymentForm.billing_cycle"
                                class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] outline-none focus:border-indigo-500 transition-all font-semibold"
                            >
                                <option value="monthly">Mensuel (Prochaine échéance dans 1 mois)</option>
                                <option value="yearly">Annuel (Prochaine échéance dans 1 an)</option>
                            </select>
                        </div>

                        <!-- Amount -->
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black uppercase tracking-wider text-[var(--text-muted)]">Montant reçu (FCFA)</label>
                            <input 
                                type="number" 
                                v-model="paymentForm.amount"
                                class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] outline-none focus:border-indigo-500 transition-all font-mono font-bold"
                                required
                                min="0"
                            />
                        </div>

                        <!-- Method -->
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black uppercase tracking-wider text-[var(--text-muted)]">Mode de paiement</label>
                            <select 
                                v-model="paymentForm.payment_method"
                                class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] outline-none focus:border-indigo-500 transition-all font-semibold"
                            >
                                <option value="Virement bancaire">Virement bancaire</option>
                                <option value="Cash / Espèces">Cash / Espèces</option>
                                <option value="Orange Money">Orange Money</option>
                                <option value="MTN Mobile Money">MTN Mobile Money</option>
                                <option value="Chèque">Chèque</option>
                            </select>
                        </div>

                        <!-- Modal Actions -->
                        <div class="border-t border-[var(--border-color)]/20 pt-4 flex justify-end gap-2.5">
                            <button 
                                type="button" 
                                @click="closePaymentModal"
                                class="px-5 py-3 bg-[var(--bg-btn-secondary)] border border-[var(--border-color)] text-[var(--text-main)] hover:bg-slate-500/10 rounded-2xl text-xs font-bold transition-all active:scale-95"
                            >
                                Annuler
                            </button>
                            <button 
                                type="submit"
                                :disabled="paymentForm.processing"
                                class="px-5 py-3 bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-500 hover:to-indigo-400 text-white rounded-2xl text-xs font-black shadow-lg shadow-indigo-600/10 active:scale-95 transition-all disabled:opacity-50 flex items-center gap-1.5"
                            >
                                <i class="fa-solid fa-check"></i>
                                <span>Valider et Enregistrer</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </Transition>
    </SuperAdminLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
