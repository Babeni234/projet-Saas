<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';
import StatCard from '@nangue/Components/StatCard.vue';
import QuickAction from '@nangue/Components/QuickAction.vue';
import PropertyCard from '@nangue/Components/PropertyCard.vue';
import PublicationRow from '@nangue/Components/PublicationRow.vue';

defineProps({
    stats: { type: Object, required: true },
    properties: { type: Array, default: () => [] },
    publications: { type: Array, default: () => [] },
    activities: { type: Array, default: () => [] },
});
</script>

<template>
    <Head title="Espace particulier" />

    <PropertyLayout
        role="particulier"
        title="Tableau de bord"
        subtitle="Gérez vos annonces et vos logements sur la plateforme"
    >
        <template #actions>
            <Link :href="route('immo.property.create')" class="imo-btn-primary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouvelle publication
            </Link>
        </template>

        <div class="imo-page">
            <div class="imo-stats-grid">
                <StatCard
                    label="Logements"
                    :value="stats.properties"
                    :change="stats.propertiesChange"
                    trend="up"
                    accent="emerald"
                />
                <StatCard
                    label="Publications actives"
                    :value="stats.publications"
                    :change="stats.publicationsChange"
                    trend="up"
                    accent="blue"
                />
                <StatCard
                    label="Vues ce mois"
                    :value="stats.views"
                    :change="stats.viewsChange"
                    trend="up"
                    accent="violet"
                />
                <StatCard
                    label="Messages"
                    :value="stats.messages"
                    :change="stats.messagesChange"
                    trend="neutral"
                    accent="amber"
                />
            </div>

            <section class="imo-section">
                <p class="imo-section-title">Actions rapides</p>
                <div class="imo-quick-grid">
                    <QuickAction
                        title="Créer une annonce"
                        description="Publier un bien à louer ou à vendre"
                        variant="primary"
                        badge="Nouveau"
                        :href="route('immo.property.create')"
                    >
                        <template #icon>
                            <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </template>
                    </QuickAction>
                    <QuickAction
                        title="Mes logements"
                        description="Ajouter ou modifier vos biens"
                        :href="route('immo.properties')"
                    >
                        <template #icon>
                            <svg class="h-5 w-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </template>
                    </QuickAction>
                    <QuickAction
                        title="Boîte de messages"
                        description="Répondre aux demandes de visite"
                        :badge="stats.messages > 0 ? String(stats.messages) : ''"
                        :href="route('immo.messages')"
                    >
                        <template #icon>
                            <svg class="h-5 w-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </template>
                    </QuickAction>
                    <QuickAction
                        title="Mes favoris"
                        description="Biens enregistrés pour plus tard"
                        :href="route('immo.favorites')"
                    >
                        <template #icon>
                            <svg class="h-5 w-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </template>
                    </QuickAction>
                </div>
            </section>

            <div class="imo-content-grid">
                <section class="imo-content-main">
                    <div class="imo-section-header">
                        <h2 class="imo-section-heading">Mes logements</h2>
                        <Link :href="route('immo.properties')" class="imo-link">Tout voir</Link>
                    </div>
                    <div class="space-y-3">
                        <PropertyCard
                            v-for="property in properties"
                            :key="property.id"
                            :property="property"
                        />
                        <div v-if="!properties.length" class="imo-empty-state">
                            <p>Aucun logement enregistré. Ajoutez votre premier bien.</p>
                        </div>
                    </div>
                </section>

                <section class="imo-content-aside">
                    <div class="imo-section-header">
                        <h2 class="imo-section-heading">Activité récente</h2>
                    </div>
                    <div class="imo-panel-compact">
                        <ul class="imo-list-divider">
                            <li
                                v-for="(activity, index) in activities"
                                :key="index"
                                class="imo-activity-item"
                            >
                                <span class="imo-activity-icon">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </span>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">{{ activity.title }}</p>
                                    <p class="text-xs text-slate-500">{{ activity.time }}</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </section>
            </div>

            <section class="imo-section">
                <div class="imo-section-header">
                    <h2 class="imo-section-heading">Mes publications</h2>
                    <button type="button" class="imo-link">Historique complet</button>
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
