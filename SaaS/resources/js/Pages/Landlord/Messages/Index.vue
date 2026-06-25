<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const $page = usePage();
defineProps({ conversations: Object });

function timeAgo(date) {
    if (!date) return '';
    const d = new Date(date);
    const now = new Date();
    const diff = now - d;
    const mins = Math.floor(diff / 60000);
    if (mins < 60) return `${mins} min`;
    const hours = Math.floor(mins / 60);
    if (hours < 24) return `${hours}h`;
    return d.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short' });
}

function otherParticipant(conversation) {
    return conversation.participants?.find(p => p.user_id !== $page.props.auth.user.id)?.user;
}
</script>

<template>
    <Head title="Messages" />
    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-gray-900">Messages</h1>
                <Link :href="route('landlord.messages.create')" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Nouveau message
                </Link>
            </div>
        </template>

        <div v-if="conversations?.data?.length" class="overflow-hidden rounded-xl border border-gray-200 bg-white">
            <div v-for="c in conversations.data" :key="c.id">
                <Link :href="route('landlord.messages.show', c.id)" class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50 border-b border-gray-100 last:border-0">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-sm font-semibold text-indigo-700">
                        {{ otherParticipant(c)?.name?.charAt(0)?.toUpperCase() || '?' }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ otherParticipant(c)?.name || 'Inconnu' }}</p>
                            <span class="text-xs text-gray-400 shrink-0">{{ timeAgo(c.last_message_at || c.created_at) }}</span>
                        </div>
                        <p class="text-sm text-gray-500 truncate">{{ c.subject }}</p>
                        <p v-if="c.last_message" class="text-xs text-gray-400 truncate">{{ c.last_message.content }}</p>
                        <p v-if="c.property" class="text-xs text-gray-400 mt-0.5">Bien : {{ c.property.title }}</p>
                    </div>
                </Link>
            </div>
        </div>

        <div v-else class="rounded-xl border-2 border-dashed border-gray-300 p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
            <h3 class="mt-4 text-lg font-semibold text-gray-900">Aucun message</h3>
            <p class="mt-2 text-sm text-gray-500">Vous n'avez pas encore de conversation.</p>
            <Link :href="route('landlord.messages.create')" class="mt-6 inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Nouveau message</Link>
        </div>
    </AppLayout>
</template>
