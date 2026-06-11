<template>
    <aside
        class="enterprise-sidebar fixed inset-y-0 left-0 z-50 flex flex-col border-r border-slate-200 bg-yellow-50 text-slate-700 transition-all duration-300 ease-out"
        :class="[
            sidebarCollapsed ? 'w-[72px]' : 'w-72',
            mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
        ]"
    >
        <!-- Brand -->
        <div class="flex h-16 shrink-0 items-center justify-between border-b border-slate-200 bg-gradient-to-r from-yellow-100 to-yellow-50 px-4">
            <RouterLink
                :to="{ name: 'dashboard.master' }"
                class="flex min-w-0 items-center gap-3 animate-fade-in"
                @click="closeMobileSidebar"
            >
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white shadow-sm border border-slate-200/50 overflow-hidden">
                    <img v-if="companyLogo" :src="companyLogo" class="h-full w-full object-contain p-1" alt="Logo" />
                    <div v-else class="flex h-full w-full items-center justify-center bg-gradient-to-br from-indigo-500 to-violet-600 text-white text-xs font-black">
                        {{ companyInitials }}
                    </div>
                </div>
                <div v-show="!sidebarCollapsed" class="min-w-0 truncate text-left">
                    <p class="text-sm font-bold text-slate-900 truncate max-w-[140px]">{{ companyName }}</p>
                    <p class="truncate text-[10px] text-slate-500 uppercase tracking-wider font-semibold">{{ companyType }}</p>
                </div>
            </RouterLink>
            <button
                type="button"
                class="hidden rounded-lg p-2 text-slate-600 hover:bg-yellow-200/50 hover:text-slate-900 lg:inline-flex transition-colors"
                @click="toggleSidebar"
            >
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 6h16M4 12h10M4 18h16" stroke-linecap="round" />
                </svg>
            </button>
        </div>

        <!-- Nav -->
        <nav class="flex-1 overflow-y-auto px-3 py-4 scrollbar-thin">
            <div v-for="section in navigation" :key="section.id" class="mb-6">
                <p
                    v-show="!sidebarCollapsed"
                    class="mb-2 px-3 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-700"
                >
                    {{ t(section.label) }}
                </p>

                <template v-for="item in section.items" :key="item.id || item.name">
                    <!-- Expandable group -->
                    <div v-if="item.children" class="space-y-0.5">
                        <button
                            type="button"
                            class="nav-item w-full"
                            :class="[
                                groupActive(item) ? `nav-item-active nav-accent-${item.accent}` : '',
                                item.blocked ? 'opacity-65 cursor-not-allowed hover:bg-transparent' : ''
                            ]"
                            @click="item.blocked ? showBlockedModal = true : toggleGroup(item.id)"
                        >
                            <NavIcon :name="item.icon" />
                            <span v-show="!sidebarCollapsed" class="flex-1 truncate text-left text-sm font-medium">{{ t(item.label) }}</span>
                            
                            <!-- Lock Icon for Blocked Modules -->
                            <svg
                                v-if="item.blocked && !sidebarCollapsed"
                                class="h-4 w-4 shrink-0 text-slate-500"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                <path d="M7 11V7a5 5 0 0110 0v4" />
                            </svg>
                            
                            <span
                                v-else-if="!sidebarCollapsed && badgeCount(item.badgeKey)"
                                class="rounded-full bg-rose-500 px-2 py-0.5 text-[10px] font-bold text-white"
                            >{{ badgeCount(item.badgeKey) }}</span>
                            <svg
                                v-show="!sidebarCollapsed && !item.blocked"
                                class="h-4 w-4 shrink-0 transition-transform"
                                :class="openGroups[item.id] ? 'rotate-180' : ''"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path d="M6 9l6 6 6-6" stroke-linecap="round" />
                            </svg>
                        </button>

                        <div
                            v-show="openGroups[item.id] && !sidebarCollapsed && !item.blocked"
                            class="ml-3 space-y-0.5 border-l border-slate-200 pl-3"
                        >
                            <template v-for="child in item.children" :key="child.label">
                                <span
                                    v-if="child.disabled"
                                    class="flex cursor-not-allowed items-center gap-2 rounded-lg px-3 py-2 text-xs text-slate-600"
                                    :title="'Bientôt disponible'"
                                >
                                    {{ t(child.label) }}
                                    <span class="ml-auto rounded bg-white/5 px-1.5 py-0.5 text-[9px] uppercase">{{ t('Bientot disponible') }}</span>
                                </span>
                                <RouterLink
                                    v-else
                                    :to="{ name: child.name, params: child.params }"
                                    class="nav-subitem"
                                    active-class="nav-subitem-active"
                                    @click="closeMobileSidebar"
                                >
                                    {{ t(child.label) }}
                                </RouterLink>
                            </template>
                        </div>
                    </div>

                    <!-- Simple link -->
                    <RouterLink
                        v-else
                        :to="{ name: item.name }"
                        class="nav-item"
                        :class="route.name === item.name ? `nav-item-active nav-accent-${item.accent || 'indigo'}` : ''"
                        @click="closeMobileSidebar"
                    >
                        <NavIcon :name="item.icon" />
                        <span v-show="!sidebarCollapsed" class="flex-1 truncate text-sm font-medium">{{ t(item.label) }}</span>
                        <span
                            v-if="!sidebarCollapsed && badgeCount(item.badgeKey)"
                            class="ml-auto rounded-full bg-rose-500 px-2 py-0.5 text-[10px] font-bold text-white"
                        >{{ badgeCount(item.badgeKey) }}</span>
                    </RouterLink>
                </template>
            </div>
        </nav>

        <!-- User -->
        <div class="shrink-0 border-t border-slate-200 bg-yellow-100/40 p-4">
            <div class="flex items-center gap-3 rounded-xl bg-white/60 border border-slate-200 p-3 shadow-sm">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-400 to-violet-500 text-sm font-bold text-white">
                    {{ userInitials }}
                </div>
                <div v-show="!sidebarCollapsed" class="min-w-0 flex-1">
                    <p class="truncate text-sm font-medium text-slate-900">{{ userName }}</p>
                    <p class="truncate text-xs text-slate-600">{{ t(userRole) }}</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Mobile overlay -->
    <Transition name="fade">
        <div
            v-if="mobileSidebarOpen"
            class="fixed inset-0 z-40 bg-slate-950/60 backdrop-blur-sm lg:hidden"
            @click="closeMobileSidebar"
        />
    </Transition>

    <!-- Blocked Module Modal -->
    <div v-if="showBlockedModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 flex items-center justify-center p-4">
        <div class="bg-gradient-to-br from-white via-white to-amber-50/20 rounded-3xl shadow-2xl max-w-md w-full p-8 border border-amber-100/50 relative overflow-hidden animate-scale-up text-left">
            <div class="absolute -top-20 -right-20 w-40 h-40 bg-gradient-to-br from-amber-400/10 to-orange-500/10 rounded-full blur-3xl"></div>
            <div class="relative z-10 text-center">
                <div class="flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 mx-auto mb-5 shadow-lg shadow-amber-500/30">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                        <path d="M7 11V7a5 5 0 0110 0v4" />
                    </svg>
                </div>
                <h3 class="text-xl font-extrabold text-slate-800 mb-2">{{ t('Module Bloque') }}</h3>
                <p class="text-slate-650 text-sm mb-6 leading-relaxed">
                    {{ locale === 'fr' ? "L'accès au module Hôtellerie est restreint. Cette fonctionnalité n'est pas incluse dans votre abonnement actuel ou a été désactivée par votre administrateur." : "Access to the Hospitality module is restricted. This feature is not included in your current subscription or has been disabled by your administrator." }}
                </p>
                <div class="flex gap-4">
                    <button 
                        @click="showBlockedModal = false" 
                        class="flex-1 px-5 py-3.5 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition-all text-xs"
                    >
                        {{ t('Fermer') }}
                    </button>
                    <button 
                        @click="showBlockedModal = false" 
                        class="flex-1 px-5 py-3.5 bg-gradient-to-r from-amber-500 to-orange-600 text-white rounded-xl font-bold shadow-lg shadow-amber-500/30 hover:shadow-xl hover:shadow-amber-500/40 transition-all text-xs"
                    >
                        {{ t('Contacter l Administration') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, computed, watch } from 'vue';
import { RouterLink, useRoute } from 'vue-router';
import { usePage } from '@inertiajs/vue3';
import { navigation } from '../../config/navigation';
import { useEnterpriseLayout } from '../../composables/useEnterpriseLayout';
import { useLocale } from '../../../../../composables/useLocale';
import NavIcon from './NavIcon.vue';

const route = useRoute();
const page = usePage();
const { sidebarCollapsed, mobileSidebarOpen, toggleSidebar, closeMobileSidebar } = useEnterpriseLayout();
const { locale, t } = useLocale();

const showBlockedModal = ref(false);

const alerts = reactive({
    immobilier: 3,
    hotel: 5,
    maintenance: 2,
});

const openGroups = reactive({
    immobilier: true,
    hotel: false,
});

const userName = computed(() => page.props.auth?.user?.name || 'Administrateur');
const userRole = computed(() => locale.value === 'en' ? 'Administrator' : 'Administrateur');
const userInitials = computed(() => {
    const name = userName.value || 'A';
    return name.split(' ').map((n) => n[0]).join('').slice(0, 2).toUpperCase();
});

const companyName = computed(() => page.props.auth?.user?.company?.legal_name || 'Enterprise');
const companyType = computed(() => page.props.auth?.user?.company?.business_type || 'Portail');
const companyLogo = computed(() => page.props.auth?.user?.company?.logo_path ? '/storage/' + page.props.auth.user.company.logo_path : null);
const companyInitials = computed(() => {
    const name = companyName.value || 'E';
    return name.split(' ').map((n) => n[0]).join('').slice(0, 2).toUpperCase();
});

const badgeCount = (key) => (key ? alerts[key] : 0);

const toggleGroup = (id) => {
    if (sidebarCollapsed.value) {
        sidebarCollapsed.value = false;
    }
    openGroups[id] = !openGroups[id];
};

const groupActive = (item) => {
    if (!item.children) return false;
    return item.children.some((c) => !c.disabled && c.name === route.name);
};

watch(
    () => route.name,
    (name) => {
        if (String(name || '').includes('agencies') || String(name || '').includes('immobilier')) {
            openGroups.immobilier = true;
        }
        if (String(name || '').includes('hotel')) {
            openGroups.hotel = true;
        }
    },
    { immediate: true },
);
</script>
