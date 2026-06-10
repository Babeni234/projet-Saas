<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
<<<<<<< HEAD
import { Head, Link } from '@inertiajs/vue3'; // 1. On importe "Link" d'Inertia ici
=======
>>>>>>> feature/nangue
</script>

<template>
    <Head title="Redirection" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Gestion immobilière
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
<<<<<<< HEAD
                        <p class="mb-4">You're logged in!</p>
                        
                        <!-- 2. On insère le lien vers la route du dashboard locataire -->
                        <div class="mt-4">
                            <Link 
                                href="dashboard-locataire" 
                                class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow transition duration-150 ease-in-out"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                Accéder à mon Espace Locataire Premium
                            </Link>
                        </div>

=======
                        <p class="mb-4">Choisissez votre espace de travail :</p>
                        <div class="flex flex-wrap gap-3">
                            <Link
                                :href="route('immo.particulier')"
                                class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700"
                            >
                                Espace particulier
                            </Link>
                            <Link
                                :href="route('immo.bailleur')"
                                class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                            >
                                Espace bailleur
                            </Link>
                        </div>
>>>>>>> feature/nangue
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>