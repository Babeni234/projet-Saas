<script setup>
import SuperAdminLayout from './layouts/SuperAdminLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, inject } from 'vue';

const props = defineProps({
    transactions: {
        type: Object,
        default: () => ({ data: [] }),
    },
    blockedFeatures: {
        type: Array,
        default: () => ['hotel', 'accounting', 'maintenance'],
    },
});

const theme = inject('theme');
const activeTab = ref('transactions');

// Form for saving settings
const settingsForm = useForm({
    blocked_features: [...props.blockedFeatures],
});

const featuresList = [
    { key: 'hotel', label: 'Gestion Hôtelière / Réservations', icon: 'fa-hotel', description: 'Accès aux réservations, chambres et services d\'hôtel.' },
    { key: 'accounting', label: 'Comptabilité & Facturation', icon: 'fa-file-invoice-dollar', description: 'Saisie de dépenses, encaissement de loyers et rapports de trésorerie.' },
    { key: 'maintenance', label: 'Gestion de Maintenance', icon: 'fa-screwdriver-wrench', description: 'Création et suivi des tickets de panne et intervention des maintenanciers.' },
    { key: 'reports', label: 'Rapports & Analyses IA', icon: 'fa-chart-line', description: 'Export de rapports PDF et assistant IA d\'analyse.' },
    { key: 'agencies', label: 'Gestion Multi-Agences', icon: 'fa-network-wired', description: 'Possibilité de créer et gérer plusieurs agences immobilières.' },
];

const toggleFeature = (key) => {
    const idx = settingsForm.blocked_features.indexOf(key);
    if (idx > -1) {
        settingsForm.blocked_features.splice(idx, 1);
    } else {
        settingsForm.blocked_features.push(key);
    }
};

const saveSettings = () => {
    settingsForm.post(route('superadmin.trial-settings.save'), {
        preserveScroll: true,
    });
};

const validateTransaction = (id) => {
    if (confirm('Êtes-vous sûr de vouloir valider manuellement ce paiement ? Cela activera l\'abonnement correspondant.')) {
        router.post(route('superadmin.transactions.validate', id), {}, {
            preserveScroll: true,
        });
    }
};

const formatAmount = (amount) => {
    return Number(amount).toLocaleString('fr-FR') + ' FCFA';
};
</script>

<template>
    <Head title="Contrôle des Paiements & Essai" />

    <SuperAdminLayout>
        <div class="space-y-8 page-entrance">
            <!-- Header section -->
            <div>
                <h2 class="text-2xl font-black tracking-tight text-[var(--text-main)]">Contrôle des Paiements & Essai</h2>
                <p class="text-sm text-[var(--text-muted)] mt-1">
                    Supervisez les transactions de paiement Orange Money, validez les paiements manuellement, et configurez les modules bloqués pour les comptes dont l'essai de 14 jours a expiré.
                </p>
            </div>

            <!-- Flash alerts -->
            <div v-if="$page.props.flash?.success" class="p-4 bg-emerald-500/10 border border-emerald-500/25 rounded-2xl flex items-center gap-3 text-emerald-400 text-xs font-semibold">
                <i class="fa-solid fa-circle-check text-base"></i>
                <span>{{ $page.props.flash.success }}</span>
            </div>
            <div v-if="$page.props.errors && Object.keys($page.props.errors).length > 0" class="p-4 bg-rose-500/10 border border-rose-500/25 rounded-2xl flex flex-col gap-1 text-rose-400 text-xs font-semibold">
                <div v-for="(error, key) in $page.props.errors" :key="key" class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation text-base"></i>
                    <span>{{ error }}</span>
                </div>
            </div>

            <!-- Tab switcher -->
            <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-3xl p-2 shadow-[var(--card-shadow)] flex gap-2 max-w-lg">
                <button 
                    @click="activeTab = 'transactions'"
                    class="flex-1 py-3 text-xs font-black rounded-2xl transition-all uppercase tracking-wider flex items-center justify-center gap-2"
                    :class="activeTab === 'transactions' ? 'bg-gradient-to-r from-indigo-600 to-indigo-500 text-white shadow-md' : 'text-[var(--text-muted)] hover:bg-[var(--bg-btn-secondary)]'"
                >
                    <i class="fa-solid fa-receipt"></i>
                    <span>Transactions</span>
                </button>
                <button 
                    @click="activeTab = 'settings'"
                    class="flex-1 py-3 text-xs font-black rounded-2xl transition-all uppercase tracking-wider flex items-center justify-center gap-2"
                    :class="activeTab === 'settings' ? 'bg-gradient-to-r from-indigo-600 to-indigo-500 text-white shadow-md' : 'text-[var(--text-muted)] hover:bg-[var(--bg-btn-secondary)]'"
                >
                    <i class="fa-solid fa-sliders"></i>
                    <span>Configuration d'Essai</span>
                </button>
            </div>

            <!-- Tab 1: Transactions Log -->
            <div v-if="activeTab === 'transactions'" class="space-y-6">
                <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-3xl shadow-[var(--card-shadow)] overflow-hidden">
                    <div class="p-6 border-b border-[var(--border-color)]/30 flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-extrabold text-[var(--text-main)]">Transactions Historiques</h3>
                            <p class="text-xs text-[var(--text-muted)] mt-1">Liste chronologique de toutes les tentatives d'abonnement et de paiement initiées.</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-[var(--border-color)]/30 text-[10px] font-black uppercase tracking-wider text-[var(--text-muted)] bg-[var(--bg-input)]/10">
                                    <th class="py-4 px-6">ID / Réf</th>
                                    <th class="py-4 px-6">Entreprise / Client</th>
                                    <th class="py-4 px-6">Forfait / Cycle</th>
                                    <th class="py-4 px-6 text-right">Montant</th>
                                    <th class="py-4 px-6">Moyen / Téléphone</th>
                                    <th class="py-4 px-6">Statut</th>
                                    <th class="py-4 px-6">Créé le</th>
                                    <th class="py-4 px-6 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[var(--border-color)]/20 text-xs">
                                <tr 
                                    v-for="trans in transactions.data" 
                                    :key="trans.id"
                                    class="hover:bg-[var(--bg-table-hover)] transition-colors text-[var(--text-main)]"
                                >
                                    <td class="py-4 px-6 font-mono text-[10px]">
                                        <div class="font-bold">#{{ trans.id }}</div>
                                        <div class="text-[var(--text-muted)] text-[8px] truncate max-w-[120px]">{{ trans.payment_ref }}</div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="font-bold">{{ trans.company_name }}</div>
                                        <div class="text-[10px] text-[var(--text-muted)]">{{ trans.user_name }} ({{ trans.user_email }})</div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="font-semibold">{{ trans.plan_name }}</div>
                                        <div class="text-[10px] text-[var(--text-muted)] uppercase">{{ trans.billing_cycle === 'yearly' ? 'Annuel' : 'Mensuel' }}</div>
                                    </td>
                                    <td class="py-4 px-6 text-right font-mono font-bold text-amber-600">
                                        {{ formatAmount(trans.amount) }}
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-1.5 font-bold uppercase">
                                            <i class="fa-solid fa-mobile-screen-button text-indigo-500"></i>
                                            <span>{{ trans.payment_method }}</span>
                                        </div>
                                        <div class="text-[10px] text-[var(--text-muted)] font-mono">{{ trans.phone_number || '—' }}</div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span 
                                            class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider border shadow-sm inline-block"
                                            :class="{
                                                'bg-emerald-500/10 border-emerald-500/20 text-emerald-400': trans.status === 'success',
                                                'bg-amber-500/10 border-amber-500/20 text-amber-400': trans.status === 'pending',
                                                'bg-rose-500/10 border-rose-500/20 text-rose-400': trans.status === 'failed',
                                            }"
                                        >
                                            {{ trans.status === 'success' ? 'Validé' : trans.status === 'pending' ? 'En attente' : 'Échoué' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-[var(--text-muted)] font-mono">
                                        {{ trans.created_at }}
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <button 
                                            v-if="trans.status === 'pending'"
                                            @click="validateTransaction(trans.id)"
                                            class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-[10px] font-bold shadow-sm transition-all hover:scale-105 active:scale-95 flex items-center gap-1 mx-auto"
                                        >
                                            <i class="fa-solid fa-check"></i>
                                            <span>Valider</span>
                                        </button>
                                        <span v-else class="text-[10px] text-[var(--text-muted)] italic">—</span>
                                    </td>
                                </tr>
                                <tr v-if="transactions.data.length === 0">
                                    <td colspan="8" class="py-12 text-center text-[var(--text-muted)] italic font-bold">
                                        Aucune transaction enregistrée pour le moment.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="transactions.links && transactions.links.length > 3" class="p-6 border-t border-[var(--border-color)]/30 flex justify-between items-center bg-[var(--bg-input)]/5">
                        <div class="text-xs text-[var(--text-muted)]">
                            Affichage de {{ transactions.from || 0 }} à {{ transactions.to || 0 }} sur {{ transactions.total || 0 }} transactions.
                        </div>
                        <div class="flex gap-1.5">
                            <Link 
                                v-for="(link, k) in transactions.links" 
                                :key="k"
                                :href="link.url || '#'"
                                class="px-3.5 py-2 text-xs rounded-xl border font-bold transition-all"
                                :class="[
                                    link.active 
                                        ? 'bg-indigo-600 border-indigo-600 text-white shadow-md' 
                                        : 'bg-[var(--bg-input)] border-[var(--border-color)] text-[var(--text-muted)] hover:text-[var(--text-main)] hover:border-indigo-500/50',
                                    !link.url ? 'opacity-50 pointer-events-none' : ''
                                ]"
                                v-html="link.label"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Trial settings -->
            <div v-if="activeTab === 'settings'" class="max-w-2xl">
                <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-3xl p-6 shadow-[var(--card-shadow)] space-y-6">
                    <div>
                        <h3 class="text-base font-extrabold text-[var(--text-main)]">Restrictions Post-Essai</h3>
                        <p class="text-xs text-[var(--text-muted)] mt-1">Sélectionnez les modules fonctionnels qui doivent être bloqués pour les entreprises une fois que les 14 jours d'essai gratuit ont expiré sans souscription active.</p>
                    </div>

                    <form @submit.prevent="saveSettings" class="space-y-6">
                        <div class="space-y-3">
                            <div 
                                v-for="feat in featuresList" 
                                :key="feat.key"
                                class="flex items-start gap-4 p-4 rounded-2xl border cursor-pointer transition-all duration-300 relative group"
                                :class="[
                                    settingsForm.blocked_features.includes(feat.key)
                                        ? 'bg-rose-500/5 border-rose-500/25'
                                        : 'bg-[var(--bg-input)]/20 border-[var(--border-color)] hover:border-slate-500/40'
                                ]"
                                @click="toggleFeature(feat.key)"
                            >
                                <div 
                                    class="h-10 w-10 shrink-0 rounded-xl flex items-center justify-center text-sm border transition-all"
                                    :class="[
                                        settingsForm.blocked_features.includes(feat.key)
                                            ? 'bg-rose-500/10 border-rose-500/30 text-rose-400'
                                            : 'bg-[var(--bg-input)] border-[var(--border-color)] text-indigo-400'
                                    ]"
                                >
                                    <i class="fa-solid" :class="feat.icon"></i>
                                </div>
                                <div class="flex-1 min-w-0 pr-4 select-none">
                                    <p class="text-xs font-bold text-[var(--text-main)]">{{ feat.label }}</p>
                                    <p class="text-[10px] text-[var(--text-muted)] mt-1 leading-relaxed">{{ feat.description }}</p>
                                </div>
                                <div class="self-center">
                                    <div 
                                        class="h-5 w-5 rounded border flex items-center justify-center text-[10px] transition-all"
                                        :class="[
                                            settingsForm.blocked_features.includes(feat.key)
                                                ? 'bg-rose-500 border-rose-600 text-white'
                                                : 'bg-[var(--bg-input)] border-[var(--border-color)]'
                                        ]"
                                    >
                                        <i v-if="settingsForm.blocked_features.includes(feat.key)" class="fa-solid fa-lock text-[9px]"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-[var(--border-color)]/20 pt-6 flex justify-end">
                            <button 
                                type="submit"
                                :disabled="settingsForm.processing"
                                class="px-6 py-3.5 bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-500 hover:to-indigo-400 text-white rounded-2xl font-bold text-xs shadow-lg shadow-indigo-600/10 active:scale-95 transition-all disabled:opacity-50 flex items-center gap-2"
                            >
                                <i class="fa-solid fa-floppy-disk text-xs"></i>
                                <span>Enregistrer les restrictions</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </SuperAdminLayout>
</template>
