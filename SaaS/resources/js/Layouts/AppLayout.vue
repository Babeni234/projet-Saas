<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const page = usePage();
const user = page.props.auth.user;
const sidebarOpen = ref(false);

const navigation = [
    { name: 'Tableau de bord', href: route('dashboard'), icon: 'dashboard', pattern: 'dashboard' },
    { name: 'Mes biens', href: route('landlord.properties.index'), icon: 'building', pattern: 'landlord.properties*' },
    { name: 'Locataires', href: route('landlord.tenants.index'), icon: 'users', pattern: 'landlord.tenants*' },
    { name: 'Contrats', href: '#', icon: 'document', pattern: null },
    { name: 'Visites', href: '#', icon: 'calendar', pattern: null },
    { name: 'Quittances', href: '#', icon: 'receipt', pattern: null },
    { name: 'Messages', href: '#', icon: 'chat', pattern: null },
];
</script>

<template>
    <div class="flex h-screen bg-gray-50">
        <div
            class="fixed inset-0 z-40 bg-gray-900/50 lg:hidden"
            v-show="sidebarOpen"
            @click="sidebarOpen = false"
        />

        <aside
            class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col border-r border-gray-200 bg-white transition-transform lg:static lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="flex h-16 items-center gap-2 border-b border-gray-100 px-6">
                <ApplicationLogo class="h-8 w-8" />
                <span class="text-lg font-bold text-gray-900">ImmoSaas</span>
            </div>

            <nav class="flex-1 space-y-1 px-3 py-4">
                <Link
                    v-for="item in navigation"
                    :key="item.name"
                    :href="item.href"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition"
                    :class="item.pattern && route().current(item.pattern)
                        ? 'bg-indigo-50 text-indigo-700'
                        : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'"
                >
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <template v-if="item.icon === 'dashboard'">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </template>
                        <template v-else-if="item.icon === 'building'">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </template>
                        <template v-else-if="item.icon === 'users'">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </template>
                        <template v-else-if="item.icon === 'document'">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </template>
                        <template v-else-if="item.icon === 'calendar'">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </template>
                        <template v-else-if="item.icon === 'receipt'">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                        </template>
                        <template v-else-if="item.icon === 'chat'">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </template>
                    </svg>
                    {{ item.name }}
                </Link>
            </nav>

            <div class="border-t border-gray-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-100 text-sm font-semibold text-indigo-700">
                        {{ user?.name?.charAt(0)?.toUpperCase() || 'U' }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="truncate text-sm font-medium text-gray-900">{{ user?.name }}</p>
                        <p class="truncate text-xs text-gray-500">{{ user?.email }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex flex-1 flex-col overflow-hidden">
            <header class="flex h-16 items-center gap-4 border-b border-gray-200 bg-white px-4 lg:px-8">
                <button
                    @click="sidebarOpen = true"
                    class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 lg:hidden"
                >
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div class="flex-1">
                    <slot name="header" />
                </div>

                <div class="flex items-center gap-3">
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <button class="flex items-center gap-2 rounded-lg p-1.5 text-sm text-gray-700 hover:bg-gray-100">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-100 text-sm font-semibold text-indigo-700">
                                    {{ user?.name?.charAt(0)?.toUpperCase() || 'U' }}
                                </div>
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </template>

                        <template #content>
                            <DropdownLink :href="route('profile.edit')">
                                Profil
                            </DropdownLink>
                            <DropdownLink :href="route('logout')" method="post" as="button">
                                Déconnexion
                            </DropdownLink>
                        </template>
                    </Dropdown>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto">
                <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>
