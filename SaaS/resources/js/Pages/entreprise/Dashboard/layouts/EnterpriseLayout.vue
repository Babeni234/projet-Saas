<template>
    <div class="enterprise-shell min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 font-sans text-slate-900 antialiased">
        <EnterpriseSidebar />

        <div
            class="flex min-h-screen flex-col transition-[margin] duration-300 ease-out"
            :class="sidebarCollapsed ? 'lg:ml-[72px]' : 'lg:ml-72'"
        >
            <EnterpriseHeader :refreshing="refreshing" @refresh="handleRefresh" />

            <!-- Trial Active Banner -->
            <div 
                v-if="user && user.trial_days_left !== null && user.is_trial_active" 
                class="mx-4 sm:mx-6 lg:mx-8 mt-4 p-4 rounded-2xl bg-gradient-to-r from-amber-500/10 via-orange-500/10 to-indigo-500/10 border border-amber-500/20 backdrop-blur-md flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-slate-800 animate-fade-in"
            >
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 text-white flex items-center justify-center shadow-md shadow-amber-500/25 shrink-0">
                        <i class="fa-solid fa-hourglass-half text-sm"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black text-slate-900">Période d'essai active — {{ user.trial_days_left }} jours restants</p>
                        <p class="text-[10px] text-slate-600 mt-0.5">Explorez toutes les fonctionnalités. Après 14 jours, l'accès à certains modules sera restreint.</p>
                    </div>
                </div>
                <RouterLink 
                    :to="{ name: 'dashboard.company.upgrade' }" 
                    class="px-4 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white text-center rounded-xl font-bold text-xs shadow-md shadow-amber-500/20 hover:shadow-lg hover:shadow-amber-500/30 transition-all shrink-0 active:scale-95 flex items-center justify-center gap-1.5"
                >
                    <i class="fa-solid fa-credit-card"></i>
                    <span>S'abonner maintenant</span>
                </RouterLink>
            </div>

            <!-- Trial Expired Warning Banner -->
            <div 
                v-else-if="user && user.is_trial_expired" 
                class="mx-4 sm:mx-6 lg:mx-8 mt-4 p-4 rounded-2xl bg-gradient-to-r from-rose-500/10 via-red-500/10 to-orange-500/10 border border-rose-500/20 backdrop-blur-md flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-slate-800 animate-fade-in"
            >
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-rose-500 to-red-600 text-white flex items-center justify-center shadow-md shadow-rose-500/25 shrink-0">
                        <i class="fa-solid fa-lock text-sm"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black text-slate-900">Période d'essai expirée — Accès restreint</p>
                        <p class="text-[10px] text-slate-650 mt-0.5">Vos 14 jours d'essai gratuit sont terminés. Les modules verrouillés ne sont plus accessibles. Abonnez-vous pour restaurer l'accès.</p>
                    </div>
                </div>
                <RouterLink 
                    :to="{ name: 'dashboard.company.upgrade' }" 
                    class="px-4 py-2.5 bg-gradient-to-r from-rose-500 to-red-650 hover:from-rose-600 hover:to-red-700 text-white text-center rounded-xl font-bold text-xs shadow-md shadow-rose-500/20 hover:shadow-lg hover:shadow-rose-500/30 transition-all shrink-0 active:scale-95 flex items-center justify-center gap-1.5"
                >
                    <i class="fa-solid fa-credit-card"></i>
                    <span>Débloquer mon compte</span>
                </RouterLink>
            </div>

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                <RouterView v-slot="{ Component, route: activeRoute }">
                    <Transition name="page" mode="out-in">
                        <component
                            :is="Component"
                            :key="activeRoute.fullPath"
                            @refresh="handleRefresh"
                        />
                    </Transition>
                </RouterView>
            </main>
        </div>

        <EnterpriseAssistant />
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { RouterView, RouterLink } from 'vue-router';
import { usePage } from '@inertiajs/vue3';
import EnterpriseSidebar from './partials/EnterpriseSidebar.vue';
import EnterpriseHeader from './partials/EnterpriseHeader.vue';
import EnterpriseAssistant from './partials/EnterpriseAssistant.vue';
import { provideEnterpriseLayout } from '../composables/useEnterpriseLayout';
import { provideEnterpriseProps } from '../composables/useEnterpriseProps';

const { sidebarCollapsed } = provideEnterpriseLayout();
provideEnterpriseProps();

const page = usePage();
const user = computed(() => page.props.auth?.user);

const refreshing = ref(false);

const handleRefresh = async () => {
    refreshing.value = true;
    window.dispatchEvent(new CustomEvent('enterprise:refresh'));
    await new Promise((r) => setTimeout(r, 600));
    refreshing.value = false;
};
</script>
