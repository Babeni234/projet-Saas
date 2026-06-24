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
                        <p class="text-xs font-black text-slate-900 flex flex-wrap items-center gap-2">
                            <span>Période d'essai active —</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-amber-500/20 text-amber-800 border border-amber-500/30 font-mono text-[10px] font-black tracking-wide animate-pulse">
                                <i class="fa-solid fa-clock-rotate-left mr-1"></i>
                                {{ trialCountdownText || (user.trial_days_left + ' jours') }}
                            </span>
                            <span>restants</span>
                        </p>
                        <p class="text-[10px] text-slate-600 mt-1">Explorez toutes les fonctionnalités. Après 14 jours, l'accès à certains modules sera restreint.</p>
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
import { ref, computed, onMounted, onUnmounted } from 'vue';
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

const trialCountdownText = ref('');
let timer = null;

const updateTrialCountdown = () => {
    if (!user.value?.trial_ends_at || !user.value?.is_trial_active) {
        trialCountdownText.value = '';
        return;
    }
    const end = new Date(user.value.trial_ends_at).getTime();
    const nowTime = new Date().getTime();
    const diff = end - nowTime;

    if (diff <= 0) {
        trialCountdownText.value = 'Expiré';
        return;
    }

    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
    const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((diff % (1000 * 60)) / 1000);

    let parts = [];
    if (days > 0) parts.push(`${days}j`);
    if (hours > 0 || days > 0) parts.push(`${hours}h`);
    parts.push(`${minutes}m`);
    parts.push(`${seconds}s`);

    trialCountdownText.value = parts.join(' ');
};

onMounted(() => {
    updateTrialCountdown();
    timer = setInterval(updateTrialCountdown, 1000);
});

onUnmounted(() => {
    if (timer) clearInterval(timer);
});

const refreshing = ref(false);

const handleRefresh = async () => {
    refreshing.value = true;
    window.dispatchEvent(new CustomEvent('enterprise:refresh'));
    await new Promise((r) => setTimeout(r, 600));
    refreshing.value = false;
};
</script>
