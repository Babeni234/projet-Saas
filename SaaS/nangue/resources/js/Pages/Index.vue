<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="ImmoSaaS" />

    <div class="flex min-h-screen items-center justify-center bg-slate-50 p-6">
        <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-8 shadow-lg">
            <h1 class="text-xl font-bold text-slate-900">Choisir un espace</h1>
            <p class="mt-2 text-sm text-slate-500">
                Accédez au tableau de bord adapté à votre profil.
            </p>
            <div class="mt-6 space-y-3">
                <Link
                    :href="route('immo.particulier')"
                    class="block rounded-xl border border-slate-200 p-4 transition hover:border-emerald-300 hover:bg-emerald-50"
                >
                    <p class="font-semibold text-slate-900">Particulier</p>
                    <p class="text-sm text-slate-500">Publications & logements</p>
                </Link>
                <Link
                    :href="route('immo.bailleur')"
                    class="block rounded-xl border border-emerald-200 bg-emerald-50 p-4 transition hover:bg-emerald-100"
                >
                    <p class="font-semibold text-emerald-900">Bailleur professionnel</p>
                    <p class="text-sm text-emerald-700">Gestion locative avancée</p>
                </Link>
            </div>
        </div>
    </div>
</template>
