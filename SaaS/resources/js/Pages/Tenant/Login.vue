<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({ email: '', password: '', remember: false });

function submit() {
    form.post(route('tenant.login.submit'));
}
</script>

<template>
    <Head title="Espace locataire" />
    <div class="flex min-h-screen items-center justify-center bg-gray-50 px-4">
        <div class="w-full max-w-sm">
            <div class="mb-8 text-center">
                <h1 class="text-2xl font-bold text-gray-900">Mon espace locataire</h1>
                <p class="mt-2 text-sm text-gray-500">Connectez-vous pour accéder à votre espace</p>
            </div>
            <form @submit.prevent="submit" class="rounded-xl border border-gray-200 bg-white p-6 space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input v-model="form.email" type="email" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                    <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Mot de passe</label>
                    <input v-model="form.password" type="password" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                </div>
                <div class="flex items-center gap-2">
                    <input v-model="form.remember" type="checkbox" id="remember" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                    <label for="remember" class="text-sm text-gray-600">Se souvenir de moi</label>
                </div>
                <button type="submit" :disabled="form.processing" class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50">
                    {{ form.processing ? 'Connexion...' : 'Se connecter' }}
                </button>
            </form>
        </div>
    </div>
</template>
