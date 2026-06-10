<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

const props = defineProps({
    conversations: { type: Array, default: () => [] },
    selectedConversation: { type: Object, default: null },
    messages: { type: Array, default: () => [] },
});

const search = ref('');
const messageTypeFilter = ref('all');
const selectedConversationId = ref(null);
const newMessageText = ref('');

const filteredConversations = computed(() => {
    return props.conversations.filter(c => {
        const q = search.value.toLowerCase();
        const matchesSearch = !q ||
            (c.name || '').toLowerCase().includes(q) ||
            (c.last_message || '').toLowerCase().includes(q) ||
            (c.property || '').toLowerCase().includes(q);
        const matchesType = messageTypeFilter.value === 'all' || c.type === messageTypeFilter.value;
        return matchesSearch && matchesType;
    });
});

const currentConversation = computed(() => {
    if (!selectedConversationId.value) return props.selectedConversation;
    return props.conversations.find(c => c.id === selectedConversationId.value) || null;
});

const currentMessages = computed(() => {
    return props.messages || [];
});

const selectConversation = (conversation) => {
    selectedConversationId.value = conversation.id;
};

const sendMessage = () => {
    const text = newMessageText.value.trim();
    if (!text) return;
    newMessageText.value = '';
};

const messageTypes = [
    { value: 'all', label: 'Tous les messages' },
    { value: 'inquiry', label: 'Demandes de visite' },
    { value: 'application', label: 'Candidatures' },
    { value: 'general', label: 'Messages généraux' },
];
</script>

<template>
    <Head title="Boîte de messages" />

    <PropertyLayout
        role="particulier"
        title="Boîte de messages"
        subtitle="Gérez vos communications avec les locataires potentiels"
    >
        <template #actions>
            <button class="imo-btn-primary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouveau message
            </button>
        </template>

        <div class="imo-page">
            <div class="flex h-[calc(100vh-12rem)] gap-6">
                <!-- Liste des conversations -->
                <div class="flex w-96 flex-col rounded-xl border border-slate-200 bg-white">
                    <!-- Filtres -->
                    <div class="border-b border-slate-100 p-4">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input
                                v-model="search"
                                type="text"
                                placeholder="Rechercher..."
                                class="imo-search-input-sm"
                            />
                        </div>
                        <select v-model="messageTypeFilter" class="imo-form-select-sm mt-3 w-full">
                            <option v-for="type in messageTypes" :key="type.value" :value="type.value">
                                {{ type.label }}
                            </option>
                        </select>
                    </div>

                    <!-- Conversations -->
                    <div v-if="filteredConversations.length === 0" class="flex items-center justify-center p-8 text-sm text-slate-500">
                        Aucune conversation trouvée.
                    </div>
                    <div v-else class="flex-1 overflow-y-auto">
                        <div
                            v-for="conversation in filteredConversations"
                            :key="conversation.id"
                            @click="selectConversation(conversation)"
                            class="border-b border-slate-50 p-4 hover:bg-slate-50 cursor-pointer transition"
                            :class="{ 'bg-emerald-50 border-l-4 border-l-emerald-500': (selectedConversationId || selectedConversation?.id) === conversation.id }"
                        >
                            <div class="flex items-start gap-3">
                                <div class="relative h-10 w-10 shrink-0 overflow-hidden rounded-full bg-slate-200">
                                    <img
                                        :src="conversation.avatar || '/placeholder-avatar.jpg'"
                                        :alt="conversation.name"
                                        class="h-full w-full object-cover"
                                    />
                                    <span
                                        v-if="conversation.online"
                                        class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-white bg-emerald-500"
                                    ></span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <h4 class="font-semibold text-slate-900 truncate">{{ conversation.name }}</h4>
                                        <span class="text-xs text-slate-500">{{ conversation.time }}</span>
                                    </div>
                                    <p class="mt-1 text-sm text-slate-600 truncate">{{ conversation.last_message }}</p>
                                    <div class="mt-2 flex items-center gap-2">
                                        <span
                                            class="rounded-full px-2 py-0.5 text-xs font-medium"
                                            :class="{
                                                'bg-blue-100 text-blue-800': conversation.type === 'inquiry',
                                                'bg-violet-100 text-violet-800': conversation.type === 'application',
                                                'bg-slate-100 text-slate-800': conversation.type === 'general',
                                            }"
                                        >
                                            {{ conversation.type === 'inquiry' ? 'Demande de visite' : conversation.type === 'application' ? 'Candidature' : 'Message' }}
                                        </span>
                                        <span v-if="conversation.property" class="text-xs text-slate-500">
                                            {{ conversation.property }}
                                        </span>
                                    </div>
                                </div>
                                <div v-if="conversation.unread" class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-xs font-semibold text-white">
                                    {{ conversation.unread }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Zone de conversation -->
                <div class="flex-1 flex-col rounded-xl border border-slate-200 bg-white">
                    <div v-if="currentConversation" class="flex h-full flex-col">
                        <!-- En-tête de conversation -->
                        <div class="border-b border-slate-100 p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="relative h-12 w-12 overflow-hidden rounded-full bg-slate-200">
                                        <img
                                            :src="currentConversation.avatar || '/placeholder-avatar.jpg'"
                                            :alt="currentConversation.name"
                                            class="h-full w-full object-cover"
                                        />
                                        <span
                                            v-if="currentConversation.online"
                                            class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-white bg-emerald-500"
                                        ></span>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-slate-900">{{ currentConversation.name }}</h3>
                                        <p class="text-sm text-slate-500">{{ currentConversation.online ? 'En ligne' : 'Hors ligne' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div v-if="currentConversation.property" class="mt-3 rounded-lg bg-slate-50 p-3">
                                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Bien concerné</p>
                                <p class="mt-1 text-sm font-medium text-slate-900">{{ currentConversation.property }}</p>
                                <Link :href="currentConversation.property_url" class="mt-1 inline-flex items-center gap-1 text-sm text-emerald-600 hover:text-emerald-700">
                                    Voir le bien
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </Link>
                            </div>
                        </div>

                        <!-- Messages -->
                        <div class="flex-1 overflow-y-auto p-4 space-y-4">
                            <div
                                v-for="message in currentMessages"
                                :key="message.id"
                                class="flex"
                                :class="{ 'justify-end': message.is_me }"
                            >
                                <div
                                    class="max-w-[70%] rounded-2xl px-4 py-3"
                                    :class="{
                                        'bg-emerald-600 text-white': message.is_me,
                                        'bg-slate-100 text-slate-900': !message.is_me,
                                    }"
                                >
                                    <p class="text-sm">{{ message.content }}</p>
                                    <p
                                        class="mt-1 text-xs"
                                        :class="{
                                            'text-emerald-200': message.is_me,
                                            'text-slate-500': !message.is_me,
                                        }"
                                    >
                                        {{ message.time }}
                                        <span v-if="message.is_me" class="ml-1">
                                            {{ message.read ? 'Lu' : 'Envoyé' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Zone de saisie -->
                        <div class="border-t border-slate-100 p-4">
                            <div class="flex items-end gap-3">
                                <button class="imo-btn-icon imo-btn-icon-secondary" title="Joindre un fichier">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                </button>
                                <div class="flex-1">
                                    <textarea
                                        v-model="newMessageText"
                                        rows="1"
                                        placeholder="Écrivez votre message..."
                                        class="imo-message-input"
                                        @keydown.enter.prevent="sendMessage"
                                    ></textarea>
                                </div>
                                <button @click="sendMessage" class="imo-btn-primary imo-btn-icon-only">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- État vide (pas de conversation sélectionnée) -->
                    <div v-else class="flex h-full items-center justify-center">
                        <div class="text-center">
                            <svg class="mx-auto h-16 w-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <h3 class="mt-4 text-lg font-semibold text-slate-900">Sélectionnez une conversation</h3>
                            <p class="mt-2 text-sm text-slate-500">Choisissez une conversation dans la liste pour commencer à discuter.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PropertyLayout>
</template>
