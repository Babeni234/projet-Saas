<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';
</script>

<template>
    <Head title="Vérification en attente" />

    <PropertyLayout
        role="particulier"
        title="Vérification en cours"
        subtitle="Votre demande est en cours d'examen"
    >
        <div class="imo-page">
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="mb-6 inline-flex items-center justify-center rounded-full bg-amber-100 p-4">
                    <svg class="h-12 w-12 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <h2 class="text-2xl font-bold text-slate-900">Demande en cours de vérification</h2>

                <p class="mt-4 max-w-lg text-slate-600">
                    Merci d'avoir soumis vos documents. Notre équipe examine actuellement votre demande
                    de vérification de statut de bailleur. Vous recevrez une notification dès que votre
                    compte sera vérifié.
                </p>

                <div class="mt-8 grid gap-4 sm:grid-cols-3">
                    <div class="rounded-xl border border-slate-200 bg-white p-4 text-center shadow-sm">
                        <div class="mx-auto mb-2 flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100">
                            <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-slate-900">Documents reçus</p>
                        <p class="mt-1 text-xs text-slate-500">Vos pièces ont bien été transmises</p>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-white p-4 text-center shadow-sm">
                        <div class="mx-auto mb-2 flex h-10 w-10 items-center justify-center rounded-full bg-amber-100">
                            <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-slate-900">Vérification en cours</p>
                        <p class="mt-1 text-xs text-slate-500">Notre équipe examine vos documents</p>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-white p-4 text-center shadow-sm">
                        <div class="mx-auto mb-2 flex h-10 w-10 items-center justify-center rounded-full bg-slate-100">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-slate-900">Validation finale</p>
                        <p class="mt-1 text-xs text-slate-500">Accès à l'espace bailleur</p>
                    </div>
                </div>

                <div class="mt-10">
                    <Link
                        :href="route('immo.particulier')"
                        class="imo-btn-secondary"
                    >
                        Retourner à mon espace particulier
                    </Link>
                </div>
            </div>
        </div>
    </PropertyLayout>
</template>
