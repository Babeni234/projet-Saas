<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

const props = defineProps({
    receipts: { type: Array, default: () => [] },
    summary: { type: Object, default: () => ({}) },
});

const search = ref('');
const filterStatus = ref('all');
const filterPeriod = ref('all');

const filteredReceipts = computed(() => {
    const now = new Date();
    return props.receipts.filter(r => {
        const q = search.value.toLowerCase();
        const matchesSearch = !q ||
            (r.reference || '').toLowerCase().includes(q) ||
            (r.tenant_name || '').toLowerCase().includes(q) ||
            (r.property_name || '').toLowerCase().includes(q) ||
            (r.period || '').toLowerCase().includes(q);
        const matchesStatus = filterStatus.value === 'all' || r.status === filterStatus.value;
        let matchesPeriod = true;
        if (filterPeriod.value !== 'all') {
            if (filterPeriod.value === 'current') {
                const p = (r.period || '').toLowerCase();
                const m = now.toLocaleString('fr-FR', { month: 'long', year: 'numeric' }).toLowerCase();
                matchesPeriod = p.includes(m) || p.includes(now.toISOString().slice(0, 7));
            } else if (filterPeriod.value === 'last') {
                const d = new Date(now.getFullYear(), now.getMonth() - 1, 1);
                const m = d.toLocaleString('fr-FR', { month: 'long', year: 'numeric' }).toLowerCase();
                matchesPeriod = (r.period || '').toLowerCase().includes(m);
            } else if (filterPeriod.value === 'year') {
                matchesPeriod = (r.period || '').includes(String(now.getFullYear()));
            }
        }
        return matchesSearch && matchesStatus && matchesPeriod;
    });
});

const paymentStatuses = [
    { value: 'all', label: 'Tous les paiements' },
    { value: 'paid', label: 'Payés' },
    { value: 'pending', label: 'En attente' },
    { value: 'overdue', label: 'En retard' },
];

const months = [
    { value: 'all', label: 'Toutes les périodes' },
    { value: 'current', label: 'Ce mois' },
    { value: 'last', label: 'Mois dernier' },
    { value: 'quarter', label: 'Ce trimestre' },
    { value: 'year', label: 'Cette année' },
];
</script>

<template>
    <Head title="Quittances" />

    <PropertyLayout
        role="bailleur"
        title="Quittances"
        subtitle="Gérez les loyers, charges et paiements"
    >
        <template #actions>
            <Link :href="route('landlord.payments.create')" class="imo-btn-primary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouvelle quittance
            </Link>
        </template>

        <div class="imo-page">
            <!-- Résumé -->
            <div class="imo-stats-grid-4">
                <div class="imo-stat-card">
                    <p class="imo-stat-label">Total perçu ce mois</p>
                    <p class="imo-stat-value">{{ summary.total_collected }}€</p>
                    <p class="imo-stat-change imo-stat-change-positive">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                        </svg>
                        +{{ summary.collected_change }}%
                    </p>
                </div>
                <div class="imo-stat-card">
                    <p class="imo-stat-label">En attente</p>
                    <p class="imo-stat-value">{{ summary.pending }}€</p>
                    <p class="imo-stat-change imo-stat-change-neutral">
                        {{ summary.pending_count }} paiements
                    </p>
                </div>
                <div class="imo-stat-card">
                    <p class="imo-stat-label">En retard</p>
                    <p class="imo-stat-value imo-stat-value-warning">{{ summary.overdue }}€</p>
                    <p class="imo-stat-change imo-stat-change-negative">
                        {{ summary.overdue_count }} paiements
                    </p>
                </div>
                <div class="imo-stat-card">
                    <p class="imo-stat-label">Taux de paiement</p>
                    <p class="imo-stat-value">{{ summary.payment_rate }}%</p>
                    <p class="imo-stat-change imo-stat-change-positive">
                        +{{ summary.rate_change }}%
                    </p>
                </div>
            </div>

            <!-- Filtres -->
            <div class="imo-filters-bar">
                <div class="flex flex-1 items-center gap-4">
                    <div class="relative flex-1 max-w-md">
                        <svg class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Rechercher une quittance..."
                            class="imo-search-input"
                        />
                    </div>

                    <select v-model="filterStatus" class="imo-form-select-sm">
                        <option v-for="status in paymentStatuses" :key="status.value" :value="status.value">
                            {{ status.label }}
                        </option>
                    </select>

                    <select v-model="filterPeriod" class="imo-form-select-sm">
                        <option v-for="month in months" :key="month.value" :value="month.value">
                            {{ month.label }}
                        </option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <Link :href="route('landlord.payments.export')" method="post" class="imo-btn-secondary">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Exporter
                    </Link>
                </div>
            </div>

            <!-- Liste des quittances -->
            <div v-if="receipts.length && filteredReceipts.length === 0" class="imo-empty-state">
                <p>Aucune quittance ne correspond à votre recherche.</p>
            </div>
            <div v-else-if="receipts.length" class="imo-table-wrap">
                <div class="overflow-x-auto">
                    <table class="imo-table w-full min-w-[1000px]">
                        <thead>
                            <tr>
                                <th>Référence</th>
                                <th>Locataire</th>
                                <th>Bien</th>
                                <th>Période</th>
                                <th>Loyer</th>
                                <th>Charges</th>
                                <th>Total</th>
                                <th>Statut</th>
                                <th>Date paiement</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="receipt in filteredReceipts"
                                :key="receipt.id"
                                class="hover:bg-slate-50"
                            >
                                <td>
                                    <p class="font-mono font-medium text-slate-900">{{ receipt.reference }}</p>
                                </td>
                                <td>
                                    <p class="font-medium text-slate-900">{{ receipt.tenant_name }}</p>
                                </td>
                                <td>
                                    <p class="text-sm text-slate-600">{{ receipt.property_name }}</p>
                                </td>
                                <td class="text-sm text-slate-600">{{ receipt.period }}</td>
                                <td class="text-sm text-slate-600">{{ receipt.rent }}€</td>
                                <td class="text-sm text-slate-600">{{ receipt.charges }}€</td>
                                <td>
                                    <p class="font-semibold text-slate-900">{{ receipt.total }}€</p>
                                </td>
                                <td>
                                    <span
                                        class="rounded-full px-2 py-1 text-xs font-semibold"
                                        :class="{
                                            'bg-emerald-100 text-emerald-800': receipt.status === 'paid',
                                            'bg-amber-100 text-amber-800': receipt.status === 'pending',
                                            'bg-red-100 text-red-800': receipt.status === 'overdue',
                                        }"
                                    >
                                        {{ receipt.status === 'paid' ? 'Payé' : receipt.status === 'pending' ? 'En attente' : 'En retard' }}
                                    </span>
                                </td>
                                <td class="text-sm text-slate-600">{{ receipt.payment_date || '-' }}</td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <Link
                                            :href="route('landlord.payments.show', receipt.id)"
                                            class="imo-btn-icon-sm"
                                            title="Voir"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </Link>
                                        <Link
                                            :href="route('landlord.payments.download', receipt.id)"
                                            class="imo-btn-icon-sm"
                                            title="Télécharger PDF"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                        </Link>
                                        <Link
                                            :href="route('landlord.payments.send', receipt.id)"
                                            method="post"
                                            class="imo-btn-icon-sm"
                                            title="Envoyer"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- État vide -->
            <div v-else class="imo-empty-state-lg">
                <svg class="mx-auto h-16 w-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                </svg>
                <h3 class="mt-4 text-lg font-semibold text-slate-900">Aucune quittance</h3>
                <p class="mt-2 text-sm text-slate-500">Les quittances apparaîtront ici une fois les paiements enregistrés.</p>
            </div>
        </div>
    </PropertyLayout>
</template>
