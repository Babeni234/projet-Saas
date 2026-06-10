<template>
    <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/90 backdrop-blur-lg shadow-sm">
        <div class="flex h-16 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
            <div class="flex min-w-0 items-center gap-3">
                <button
                    type="button"
                    class="inline-flex rounded-xl border border-slate-200 bg-white p-2.5 text-slate-600 shadow-sm hover:bg-slate-50 lg:hidden"
                    @click="toggleMobileSidebar"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" />
                    </svg>
                </button>

                <div class="min-w-0">
                    <nav v-if="breadcrumbs.length" class="mb-0.5 flex flex-wrap items-center gap-1 text-xs text-slate-500">
                        <template v-for="(crumb, i) in breadcrumbs" :key="i">
                            <span v-if="i > 0" class="text-slate-300">/</span>
                            <RouterLink
                                :to="crumb.to"
                                class="hover:text-indigo-600"
                                :class="{ 'font-medium text-slate-800': i === breadcrumbs.length - 1 }"
                            >
                                {{ crumb.label }}
                            </RouterLink>
                        </template>
                    </nav>
                    <h1 class="truncate text-lg font-semibold text-slate-900 sm:text-xl">{{ pageTitle }}</h1>
                </div>
            </div>

            <div class="flex shrink-0 items-center gap-2 sm:gap-3">
                <div class="hidden items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 sm:flex">
                    <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" />
                        <path d="M16 2v4M8 2v4M3 10h18" />
                    </svg>
                    <span class="text-xs font-medium text-slate-600">{{ dateRange }}</span>
                </div>

                <button
                    type="button"
                    class="inline-flex items-center gap-2 rounded-xl border border-indigo-200 bg-indigo-50 px-3 py-2 text-sm font-medium text-indigo-700 shadow-sm transition hover:border-indigo-300 hover:bg-indigo-100"
                    :disabled="refreshing"
                    @click="$emit('refresh')"
                >
                    <svg
                        class="h-4 w-4"
                        :class="{ 'animate-spin': refreshing }"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M4 4v5h5M20 20v-5h-5M20 8A8 8 0 004 16" stroke-linecap="round" />
                    </svg>
                    <span class="hidden sm:inline">Actualiser</span>
                </button>

                <!-- Logout button -->
                <button
                    type="button"
                    class="inline-flex items-center gap-2 rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-sm font-medium text-red-700 shadow-sm transition hover:border-red-300 hover:bg-red-100"
                    @click="handleLogout"
                >
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M16 17l5-5-5-5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M21 12H9" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="hidden sm:inline">Déconnexion</span>
                </button>
            </div>
        </div>

        <!-- Logout loading popup -->
        <div v-if="logoutLoading" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="rounded-2xl bg-white px-8 py-6 shadow-2xl">
                <div class="flex items-center gap-3">
                    <svg class="h-6 w-6 animate-spin text-indigo-600" viewBox="0 0 24 24" fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                    </svg>
                    <span class="text-lg font-medium text-slate-900">Déconnexion en cours...</span>
                </div>
            </div>
        </div>

        <!-- Logout confirmation popup -->
        <Transition name="fade">
            <div v-if="showLogoutConfirm" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-md p-4">
                <div class="bg-gradient-to-br from-white via-white to-red-50/10 rounded-3xl shadow-2xl max-w-md w-full p-8 border border-red-100/50 relative overflow-hidden animate-scale-up text-left">
                    <div class="absolute -top-20 -right-20 w-40 h-40 bg-gradient-to-br from-red-400/10 to-rose-500/10 rounded-full blur-3xl"></div>
                    <div class="relative z-10 text-center">
                        <div class="flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-red-500 to-rose-650 mx-auto mb-5 shadow-lg shadow-red-500/30">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-extrabold text-slate-800 mb-2">Déconnexion</h3>
                        <p class="text-slate-650 text-sm mb-6 leading-relaxed">
                            Voulez-vous vraiment vous déconnecter de l'entreprise <strong class="text-slate-800 font-extrabold">{{ companyName }}</strong> ?
                        </p>
                        <div class="flex gap-4">
                            <button 
                                @click="showLogoutConfirm = false" 
                                class="flex-1 px-5 py-3.5 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition-all text-xs"
                            >
                                Annuler
                            </button>
                            <button 
                                @click="confirmLogout" 
                                class="flex-1 px-5 py-3.5 bg-gradient-to-r from-red-500 to-rose-600 text-white rounded-xl font-bold shadow-lg shadow-red-500/30 hover:shadow-xl hover:shadow-red-500/40 transition-all text-xs"
                            >
                                Se Déconnecter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </header>
</template>

<script setup>
import { computed, ref } from 'vue';
import { RouterLink, useRoute } from 'vue-router';
import { router, usePage } from '@inertiajs/vue3';
import { useEnterpriseLayout } from '../../../../entreprise/Dashboard/composables/useEnterpriseLayout';
import { usePageTitle } from '../../../../entreprise/Dashboard/composables/useEnterpriseLayout';

defineProps({
    refreshing: { type: Boolean, default: false },
});

defineEmits(['refresh']);

const page = usePage();
const vueRoute = useRoute();
const pageTitle = usePageTitle();
const { toggleMobileSidebar } = useEnterpriseLayout();

const logoutLoading = ref(false);
const showLogoutConfirm = ref(false);
const companyName = computed(() => page.props.auth?.user?.company?.legal_name || 'votre entreprise');

const handleLogout = () => {
    showLogoutConfirm.value = true;
};

const confirmLogout = () => {
    showLogoutConfirm.value = false;
    logoutLoading.value = true;
    router.post(window.route('logout'), {}, {
        onFinish: () => {
            logoutLoading.value = false;
        },
    });
};

const dateRange = computed(() => {
    const now = new Date();
    const first = new Date(now.getFullYear(), now.getMonth(), 1);
    const last = new Date(now.getFullYear(), now.getMonth() + 1, 0);
    const opts = { month: 'short', year: 'numeric' };
    return `${first.toLocaleDateString('fr-FR', opts)} – ${last.toLocaleDateString('fr-FR', opts)}`;
});

const breadcrumbs = computed(() => vueRoute.meta?.breadcrumbs || []);
</script>
