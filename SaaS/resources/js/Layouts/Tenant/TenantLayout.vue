<script setup>
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const tenantUser = page.props.auth?.tenant_user;

const navigation = [
    { name: 'Tableau de bord', href: route('tenant.dashboard'), icon: 'dashboard' },
    { name: 'Mes quittances', href: route('tenant.receipts.index'), icon: 'receipt' },
    { name: 'Mes demandes', href: route('tenant.incidents.index'), icon: 'ticket' },
    { name: 'Documents', href: route('tenant.documents.index'), icon: 'document' },
    { name: 'Messages', href: route('tenant.messages.index'), icon: 'chat' },
];
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-semibold text-gray-900">
                        Mon espace locataire
                    </h1>
                    <p class="text-sm text-gray-500">{{ tenantUser?.tenant?.name || tenantUser?.email }}</p>
                </div>
                <Link :href="route('tenant.logout')" method="post" class="text-sm text-gray-500 hover:text-gray-700">Déconnexion</Link>
            </div>

            <nav class="mb-6 flex gap-1 rounded-xl bg-white p-1.5 shadow-sm border border-gray-200">
                <Link v-for="item in navigation" :key="item.name" :href="item.href"
                    class="flex-1 rounded-lg px-4 py-2.5 text-center text-sm font-medium transition"
                    :class="route().current(item.href) ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100'">
                    {{ item.name }}
                </Link>
            </nav>

            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
