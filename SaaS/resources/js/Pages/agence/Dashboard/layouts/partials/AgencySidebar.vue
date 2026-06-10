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
                :to="{ name: 'agence.master' }"
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
                    <p class="truncate text-[10px] text-slate-500 uppercase tracking-wider font-semibold">{{ agencyName }}</p>
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
                    {{ section.label }}
                </p>

                <template v-for="item in section.items" :key="item.id || item.name">
                    <!-- Expandable group -->
                    <div v-if="item.children" class="space-y-0.5">
                        <button
                            type="button"
                            class="nav-item w-full"
                            :class="[
                                groupActive(item) ? `nav-item-active nav-accent-${item.accent}` : '',
                            ]"
                            @click="toggleGroup(item.id)"
                        >
                            <NavIcon :name="item.icon" />
                            <span v-show="!sidebarCollapsed" class="flex-1 truncate text-left text-sm font-medium">{{ item.label }}</span>
                            
                            <span
                                v-if="!sidebarCollapsed && badgeCount(item.badgeKey)"
                                class="rounded-full bg-rose-500 px-2 py-0.5 text-[10px] font-bold text-white"
                            >{{ badgeCount(item.badgeKey) }}</span>
                            <svg
                                v-show="!sidebarCollapsed"
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
                            v-show="openGroups[item.id] && !sidebarCollapsed"
                            class="ml-3 space-y-0.5 border-l border-slate-200 pl-3"
                        >
                            <template v-for="child in item.children" :key="child.label">
                                <RouterLink
                                    :to="{ name: child.name, params: child.params }"
                                    class="nav-subitem"
                                    active-class="nav-subitem-active"
                                    @click="closeMobileSidebar"
                                >
                                    {{ child.label }}
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
                        <span v-show="!sidebarCollapsed" class="flex-1 truncate text-sm font-medium">{{ item.label }}</span>
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
                    <p class="truncate text-xs text-slate-600">{{ userRole }}</p>
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
</template>

<script setup>
import { ref, reactive, computed, watch } from 'vue';
import { RouterLink, useRoute } from 'vue-router';
import { usePage } from '@inertiajs/vue3';
import { navigation } from '../../config/navigation';
import { useEnterpriseLayout } from '../../../../entreprise/Dashboard/composables/useEnterpriseLayout';
import NavIcon from '../../../../entreprise/Dashboard/layouts/partials/NavIcon.vue';

const route = useRoute();
const page = usePage();
const { sidebarCollapsed, mobileSidebarOpen, toggleSidebar, closeMobileSidebar } = useEnterpriseLayout();

const alerts = reactive({
    immobilier: 2,
    maintenance: 1,
});

const openGroups = reactive({
    immobilier: true,
    accounting: false,
});

const userName = computed(() => page.props.auth?.user?.name || 'Collaborateur');
const userRole = computed(() => page.props.auth?.user?.role?.name || 'Agent');
const userInitials = computed(() => {
    const name = userName.value || 'A';
    return name.split(' ').map((n) => n[0]).join('').slice(0, 2).toUpperCase();
});

const companyName = computed(() => page.props.auth?.user?.company?.legal_name || 'Entreprise');
const agencyName = computed(() => page.props.auth?.user?.employee?.agency?.name || 'Mon Agence');
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
    return item.children.some((c) => c.name === route.name);
};

watch(
    () => route.name,
    (name) => {
        if (String(name || '').includes('immobilier')) {
            openGroups.immobilier = true;
        }
        if (String(name || '').includes('accounting')) {
            openGroups.accounting = true;
        }
    },
    { immediate: true },
);
</script>
