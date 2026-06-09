<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import DarkToggle from '@nangue/Components/DarkToggle.vue';
import ChatBot from '@nangue/Components/ChatBot.vue';
import { useTestUser } from '@nangue/composables/useTestUser.js';

const props = defineProps({
    title: { type: String, default: 'Tableau de bord' },
    subtitle: { type: String, default: '' },
    role: { type: String, default: 'utilisateur' },
});

const page = usePage();
const user = computed(() => page.props.auth?.user);
const { isTestUser } = useTestUser();
const sidebarOpen = ref(false);

const navItems = computed(() => {
    if (props.role === 'bailleur') {
        return [
            { label: "Tableau de bord Pro", href: 'immo.bailleur', icon: 'home' },
            { label: 'Gestion du parc', href: 'immo.properties', icon: 'building' },
            { label: 'Mes Locataires', href: 'landlord.tenants.index', icon: 'users' },
            { label: 'Contrats & Baux', href: 'landlord.contracts.index', icon: 'document' },
            { label: 'Paiements & Quittances', href: 'landlord.payments.index', icon: 'receipt' },
            { label: 'Calendrier Visites', href: 'landlord.calendar.index', icon: 'calendar' },
            { label: 'Messagerie Client', href: 'immo.messages', icon: 'chat' },
            { label: 'Analyses & Rapports', href: 'landlord.reports.index', icon: 'chart' },
            { label: 'Paramètres Équipe', href: 'landlord.settings.team', icon: 'team' },
        ];
    }

    // Menu Particulier par défaut
    return [
        { label: "Mon Espace Perso", href: 'immo.particulier', icon: 'home' },
        { label: 'Mes logements', href: 'immo.properties', icon: 'building' },
        { label: 'Mes publications', href: 'immo.publications', icon: 'megaphone' },
        { label: 'Mes recherches', href: 'immo.saved_searches', icon: 'search' },
        { label: 'Mes messages', href: 'immo.messages', icon: 'chat' },
        { label: 'Mes favoris', href: 'immo.favorites', icon: 'heart' },
    ];
});

const icons = {
    home: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
    building: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
    megaphone: 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z',
    chat: 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
    heart: 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
    users: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
    document: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
    receipt: 'M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z',
    calendar: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
    chart: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
    team: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
    search: 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z',
};

const isActive = (href) => href !== '#' && route().current(href);
</script>

<template>
    <div class="imo-dashboard">
        <div
            v-if="sidebarOpen"
            class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden"
            @click="sidebarOpen = false"
        ></div>

        <aside
            :class="[
                'imo-sidebar fixed inset-y-0 left-0 z-50 flex w-64 flex-col transition-transform duration-300 ease-out lg:translate-x-0',
                sidebarOpen ? 'translate-x-0' : '-translate-x-full',
            ]"
        >
            <div class="imo-sidebar-brand">
                <div class="imo-logo">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-900">ImmoSaaS</p>
                    <p class="text-xs font-medium text-emerald-700/80">Gestion immobilière</p>
                </div>
            </div>

            <nav class="flex-1 space-y-0.5 overflow-y-auto p-3">
                <p class="imo-section-title mb-3 px-3">Menu principal</p>
                <Link
                    v-for="item in navItems"
                    :key="item.label"
                    :href="item.href.startsWith('#') ? '#' : route(item.href)"
                    class="imo-nav-link"
                    :class="{ 'imo-nav-link-active': isActive(item.href) }"
                >
                    <svg class="h-5 w-5 shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" :d="icons[item.icon]" />
                    </svg>
                    {{ item.label }}
                </Link>
            </nav>

            <div class="border-t border-slate-100 bg-slate-50/50 p-4">
                <div class="flex items-center gap-3">
                    <div class="imo-avatar-user">
                        {{ user?.name?.charAt(0) ?? 'U' }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-slate-900">{{ user?.name }}</p>
                        <p class="truncate text-xs text-slate-500">{{ user?.email }}</p>
                        <span v-if="isTestUser" class="mt-0.5 inline-block rounded bg-amber-100 px-1.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-amber-700">Compte test</span>
                    </div>
                </div>
                <div class="mt-3 flex gap-2">
                    <Link
                        :href="route('immo.particulier')"
                        class="imo-role-switch"
                        :class="role !== 'bailleur' ? 'imo-role-switch-active' : 'imo-role-switch-inactive'"
                    >
                        Particulier
                    </Link>
                    <Link
                        :href="route('immo.bailleur')"
                        class="imo-role-switch"
                        :class="role === 'bailleur' ? 'imo-role-switch-active' : 'imo-role-switch-inactive'"
                    >
                        Bailleur
                    </Link>
                </div>

                <!-- Bouton de déconnexion global -->
                <div class="mt-4 border-t border-slate-200 pt-4">
                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="flex w-full items-center justify-center gap-2 rounded-lg bg-red-50 px-3 py-2 text-xs font-bold text-red-600 transition hover:bg-red-100"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Déconnexion
                    </Link>
                </div>
            </div>
        </aside>

        <div class="lg:pl-64">
            <header class="imo-header">
                <div class="flex h-16 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center gap-3">
                        <button
                            type="button"
                            class="rounded-xl p-2 text-slate-500 transition hover:bg-slate-100 lg:hidden"
                            @click="sidebarOpen = true"
                        >
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <div>
                            <h1 class="text-lg font-bold text-slate-900 sm:text-xl">
                                <span :class="role === 'bailleur' ? 'text-emerald-700' : 'imo-text-gradient'">{{ title }}</span>
                                <span v-if="role === 'bailleur'" class="ml-2 rounded-md bg-emerald-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-emerald-800">Espace Pro</span>
                            </h1>
                            <p v-if="subtitle" class="text-sm text-slate-500">{{ subtitle }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 sm:gap-3">
                        <slot name="actions" />
                        <DarkToggle />
                        <button type="button" class="imo-notification-btn">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span class="imo-notification-dot" />
                        </button>
                        <Link
                            :href="route('profile.edit')"
                            class="imo-btn-secondary hidden sm:inline-flex"
                        >
                            Mon profil
                        </Link>
                    </div>
                </div>
            </header>

            <main class="imo-main">
                <slot />
            </main>
        </div>
        <ChatBot />
    </div>
</template>
