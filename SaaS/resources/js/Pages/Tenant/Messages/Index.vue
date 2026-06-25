<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import TenantLayout from '@/Layouts/Tenant/TenantLayout.vue';

const $page = usePage();
defineProps({ conversations: Object });

function otherParticipant(c) {
    return c.participants?.find(p => p.user_id !== $page.props.auth.tenant_user?.tenant?.user_id)?.user;
}

function timeAgo(date) {
    if (!date) return '';
    const d = new Date(date);
    const diff = Date.now() - d;
    const mins = Math.floor(diff / 60000);
    if (mins < 60) return `${mins} min`;
    const hours = Math.floor(mins / 60);
    if (hours < 24) return `${hours}h`;
    return d.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short' });
}
</script>

<template>
    <Head title="Mes messages" />
    <TenantLayout>
        <h2 class="text-lg font-semibold text-gray-900 mb-6">Mes messages</h2>

        <div v-if="conversations?.data?.length" class="space-y-1">
            <Link v-for="c in conversations.data" :key="c.id" :href="route('tenant.messages.show', c.id)"
                class="flex items-center gap-3 rounded-xl border border-gray-200 bg-white px-4 py-3 hover:bg-gray-50 transition">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-sm font-semibold text-indigo-700">
                    {{ otherParticipant(c)?.name?.charAt(0)?.toUpperCase() || '?' }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ otherParticipant(c)?.name || 'Bailleur' }}</p>
                        <span class="text-xs text-gray-400">{{ timeAgo(c.last_message_at || c.created_at) }}</span>
                    </div>
                    <p class="text-xs text-gray-500 truncate">{{ c.subject }}</p>
                    <p v-if="c.last_message" class="text-xs text-gray-400 truncate">{{ c.last_message.content }}</p>
                </div>
            </Link>
        </div>

        <div v-else class="rounded-xl border-2 border-dashed border-gray-300 p-12 text-center">
            <p class="text-sm text-gray-500">Aucune conversation.</p>
        </div>
    </TenantLayout>
</template>
