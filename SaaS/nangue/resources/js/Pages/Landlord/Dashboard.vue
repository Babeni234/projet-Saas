<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';
import StatCard from '@nangue/Components/StatCard.vue';
import QuickAction from '@nangue/Components/QuickAction.vue';
import PropertyCard from '@nangue/Components/PropertyCard.vue';
import PublicationRow from '@nangue/Components/PublicationRow.vue';
import TenantRow from '@nangue/Components/TenantRow.vue';
import BarChart from '@nangue/Components/BarChart.vue';

defineProps({
    stats: { type: Object, required: true },
    company: { type: Object, required: true },
    properties: { type: Array, default: () => [] },
    publications: { type: Array, default: () => [] },
    tenants: { type: Array, default: () => [] },
    revenueChart: { type: Array, default: () => [] },
    alerts: { type: Array, default: () => [] },
});
</script>

<template>
    <Head title="Espace bailleur professionnel" />

    <PropertyLayout
        role="bailleur"
        title="Espace bailleur"
        :subtitle="`${company.name} · Compte vérifié`"
    >
        <template #actions>
            <span class="imo-badge-certified">
                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 2.452a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                Bailleur certifié
            </span>
            <Link :href="route('immo.property.create')" class="imo-btn-primary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouvelle publication
            </Link>
        </template>

        <div class="imo-page">
            <div class="imo-hero-bailleur relative z-10">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold text-emerald-800">{{ company.legalForm }}</p>
                        <p class="mt-1 text-xl font-extrabold tracking-tight text-slate-900 sm:text-2xl">{{ company.name }}</p>
                        <p class="mt-1 text-sm text-slate-600">SIRET {{ company.siret }} · {{ company.portfolio }} biens gérés</p>
                    </div>
                    <div class="flex gap-4">
                        <div class="imo-metric-pill">
                            <p class="imo-metric-pill-value">{{ company.rating }}</p>
                            <p class="imo-metric-pill-label">Note plateforme</p>
                        </div>
                        <div class="imo-metric-pill">
                            <p class="imo-metric-pill-value text-emerald-700">{{ company.occupancy }}%</p>
                            <p class="imo-metric-pill-label">Taux d'occupation</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="imo-stats-grid-5">
                <StatCard label="Biens" :value="stats.properties" :change="stats.propertiesChange" trend="up" accent="emerald" />
                <StatCard label="Publications" :value="stats.publications" :change="stats.publicationsChange" trend="up" accent="blue" />
                <StatCard label="Revenus mensuels" :value="stats.revenue" :change="stats.revenueChange" trend="up" accent="violet" />
                <StatCard label="Locataires actifs" :value="stats.tenants" :change="stats.tenantsChange" trend="neutral" accent="amber" />
                <StatCard label="Messages" :value="stats.messages" :change="stats.messagesChange" trend="up" accent="emerald" />
            </div>

            <div v-if="alerts.length" class="space-y-2">
                <div
                    v-for="(alert, i) in alerts"
                    :key="i"
                    :class="alert.type === 'warning' ? 'imo-alert-warning' : 'imo-alert-info'"
                >
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    {{ alert.message }}
                </div>
            </div>

            <section class="imo-section">
                <p class="imo-section-title">Actions rapides — tout le particulier, et plus</p>
                <div class="imo-quick-grid-6">
                    <QuickAction :href="route('immo.property.create')" title="Nouvelle annonce" variant="primary" description="Publier un bien">
                        <template #icon>
                            <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        </template>
                    </QuickAction>
                    <QuickAction :href="route('immo.properties')" title="Mes logements" description="Patrimoine">
                        <template #icon>
                            <svg class="h-5 w-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5" /></svg>
                        </template>
                    </QuickAction>
                    <QuickAction :href="route('landlord.contracts.index')" title="Contrats & baux" description="Signature électronique" variant="warning">
                        <template #icon>
                            <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        </template>
                    </QuickAction>
                    <QuickAction :href="route('landlord.payments.index')" title="Quittances" description="Loyer & charges">
                        <template #icon>
                            <svg class="h-5 w-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" /></svg>
                        </template>
                    </QuickAction>
                    <QuickAction :href="route('landlord.calendar.index')" title="Calendrier visites" description="Créneaux & rappels">
                        <template #icon>
                            <svg class="h-5 w-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </template>
                    </QuickAction>
                    <QuickAction :href="route('landlord.reports.index')" title="Analytiques" description="Performance par bien">
                        <template #icon>
                            <svg class="h-5 w-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                        </template>
                    </QuickAction>
                </div>
            </section>

            <div class="imo-content-grid">
                <div class="imo-content-main">
                    <section class="imo-section">
                        <div class="imo-section-header">
                            <h2 class="imo-section-heading">Revenus locatifs (6 mois)</h2>
                        </div>
                        <div class="imo-chart-panel">
                            <BarChart :data="revenueChart" labelKey="label" valueKey="percent" color="#10b981" label="Revenus" height="180px" />
                        </div>
                    </section>

                    <section class="imo-section">
                        <div class="imo-section-header">
                            <h2 class="imo-section-heading">Mes logements</h2>
                        </div>
                        <div class="space-y-3">
                            <PropertyCard
                                v-for="property in properties"
                                :key="property.id"
                                :property="property"
                            />
                        </div>
                    </section>
                </div>

                <aside class="imo-content-aside">
                    <section class="imo-section">
                        <div class="imo-section-header">
                            <h2 class="imo-section-heading">Locataires</h2>
                        </div>
                        <div class="imo-panel-compact space-y-2">
                            <TenantRow
                                v-for="tenant in tenants"
                                :key="tenant.id"
                                :tenant="tenant"
                            />
                            <Link :href="route('landlord.tenants.index')" class="imo-btn-dashed">
                                + Ajouter un locataire
                            </Link>
                        </div>
                    </section>

                    <section class="imo-section">
                        <div class="imo-section-header">
                            <h2 class="imo-section-heading">Équipe & accès</h2>
                        </div>
                        <div class="imo-panel-compact">
                            <p class="text-sm leading-relaxed text-slate-600">
                                Invitez des collaborateurs (comptable, agent) avec des rôles personnalisés.
                            </p>
                            <Link :href="route('landlord.settings.team')" class="imo-btn-dark mt-4">
                                Gérer l'équipe
                            </Link>
                        </div>
                    </section>
                </aside>
            </div>

            <section class="imo-section">
                <div class="imo-section-header">
                    <h2 class="imo-section-heading">Publications</h2>
                </div>
                <div class="imo-table-wrap">
                    <div class="overflow-x-auto">
                        <table class="imo-table w-full min-w-[640px]">
                            <thead>
                                <tr>
                                    <th>Annonce</th>
                                    <th>Type</th>
                                    <th>Statut</th>
                                    <th>Vues</th>
                                    <th>Date</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <PublicationRow
                                    v-for="pub in publications"
                                    :key="pub.id"
                                    :publication="pub"
                                />
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </PropertyLayout>
</template>
