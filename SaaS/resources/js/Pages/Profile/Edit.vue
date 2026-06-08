<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const activeTab = ref('profile');
const showLogoutModal = ref(false);

const tabs = [
    { id: 'profile', label: 'Profil', icon: 'user' },
    { id: 'security', label: 'Sécurité', icon: 'lock' },
    { id: 'danger', label: 'Zone de danger', icon: 'alert-triangle' },
];

const handleLogout = () => {
    showLogoutModal.value = true;
};

const confirmLogout = () => {
    window.location.href = window.route('logout');
};

const cancelLogout = () => {
    showLogoutModal.value = false;
};
</script>

<template>
    <Head title="Profil" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100">
            <!-- Header Section -->
            <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">
                <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4xIj48cGF0aCBkPSJNMzYgMzRjMC0yIDItNCAyLTRzLTItMi0yLTRjMC0yLTItNC0yLTRzLTItMi0yLTRjMC0yLTItNC0yLTRzLTItMi0yLTRcIi8+PC9nPjwvZz48L3N2Zz4=')] opacity-30"></div>
                <div class="relative px-6 py-12 sm:px-8 lg:px-12">
                    <div class="mx-auto max-w-7xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="text-3xl font-bold text-white sm:text-4xl">
                                    Mon Profil
                                </h1>
                                <p class="mt-2 text-indigo-100">
                                    Gérez vos informations personnelles et votre sécurité
                                </p>
                            </div>
                            <button
                                @click="handleLogout"
                                class="inline-flex items-center gap-2 rounded-xl border border-white/30 bg-white/10 px-5 py-2.5 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white/20 hover:border-white/50"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Déconnexion
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="relative px-6 py-8 sm:px-8 lg:px-12">
                <div class="mx-auto max-w-7xl">
                    <!-- Tab Navigation -->
                    <div class="mb-8 overflow-hidden rounded-2xl bg-white shadow-lg">
                        <div class="flex border-b border-slate-200">
                            <button
                                v-for="tab in tabs"
                                :key="tab.id"
                                @click="activeTab = tab.id"
                                class="relative flex-1 px-6 py-4 text-sm font-medium transition-colors"
                                :class="activeTab === tab.id ? 'text-indigo-600' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                            >
                                <span class="flex items-center justify-center gap-2">
                                    <svg v-if="tab.icon === 'user'" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <svg v-if="tab.icon === 'lock'" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    <svg v-if="tab.icon === 'alert-triangle'" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    {{ tab.label }}
                                </span>
                                <div
                                    v-if="activeTab === tab.id"
                                    class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-indigo-600 to-purple-600"
                                ></div>
                            </button>
                        </div>
                    </div>

                    <!-- Tab Content -->
                    <div class="space-y-6">
                        <!-- Profile Tab -->
                        <div
                            v-show="activeTab === 'profile'"
                            class="overflow-hidden rounded-2xl bg-white shadow-xl ring-1 ring-slate-900/5 transition-all duration-300"
                        >
                            <div class="border-b border-slate-200 bg-gradient-to-r from-indigo-50 to-purple-50 px-6 py-4">
                                <h3 class="text-lg font-semibold text-slate-900">Informations du profil</h3>
                                <p class="mt-1 text-sm text-slate-600">Mettez à jour vos informations personnelles</p>
                            </div>
                            <div class="p-6 sm:p-8">
                                <UpdateProfileInformationForm
                                    :must-verify-email="mustVerifyEmail"
                                    :status="status"
                                    class="max-w-3xl"
                                />
                            </div>
                        </div>

                        <!-- Security Tab -->
                        <div
                            v-show="activeTab === 'security'"
                            class="overflow-hidden rounded-2xl bg-white shadow-xl ring-1 ring-slate-900/5 transition-all duration-300"
                        >
                            <div class="border-b border-slate-200 bg-gradient-to-r from-amber-50 to-orange-50 px-6 py-4">
                                <h3 class="text-lg font-semibold text-slate-900">Sécurité</h3>
                                <p class="mt-1 text-sm text-slate-600">Gérez votre mot de passe</p>
                            </div>
                            <div class="p-6 sm:p-8">
                                <UpdatePasswordForm class="max-w-3xl" />
                            </div>
                        </div>

                        <!-- Danger Zone Tab -->
                        <div
                            v-show="activeTab === 'danger'"
                            class="overflow-hidden rounded-2xl bg-white shadow-xl ring-1 ring-red-900/5 transition-all duration-300"
                        >
                            <div class="border-b border-red-200 bg-gradient-to-r from-red-50 to-pink-50 px-6 py-4">
                                <h3 class="text-lg font-semibold text-red-900">Zone de danger</h3>
                                <p class="mt-1 text-sm text-red-600">Actions irréversibles sur votre compte</p>
                            </div>
                            <div class="p-6 sm:p-8">
                                <DeleteUserForm class="max-w-3xl" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logout Confirmation Modal -->
        <div
            v-if="showLogoutModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity duration-300"
        >
            <div class="mx-4 max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl transition-all duration-300 transform scale-100">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white">Confirmer la déconnexion</h3>
                </div>
                <div class="p-6">
                    <p class="text-slate-600">
                        Êtes-vous sûr de vouloir vous déconnecter ? Vous devrez vous reconnecter pour accéder à votre compte.
                    </p>
                    <div class="mt-6 flex justify-end gap-3">
                        <button
                            @click="cancelLogout"
                            class="rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 hover:border-slate-400"
                        >
                            Annuler
                        </button>
                        <button
                            @click="confirmLogout"
                            class="rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition hover:shadow-xl hover:shadow-indigo-500/40"
                        >
                            Se déconnecter
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
