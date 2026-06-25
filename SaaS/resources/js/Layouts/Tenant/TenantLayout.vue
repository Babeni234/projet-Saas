<script setup>
import { ref, nextTick } from 'vue';
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

const chatOpen = ref(false);
const messages = ref([
    { role: 'assistant', text: '👋 Bonjour ! Je suis votre assistant. Posez-moi vos questions sur votre contrat, loyer, ou pour créer une demande.' }
]);
const input = ref('');
const loading = ref(false);
const chatBody = ref(null);

function scrollDown() {
    nextTick(() => {
        if (chatBody.value) chatBody.value.scrollTop = chatBody.value.scrollHeight;
    });
}

async function send() {
    const msg = input.value.trim();
    if (!msg || loading.value) return;
    messages.value.push({ role: 'user', text: msg });
    input.value = '';
    loading.value = true;
    scrollDown();

    try {
        const res = await fetch('/api/tenant/ai/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
            },
            body: JSON.stringify({
                message: msg,
                history: messages.value.slice(-20).map(m => ({ role: m.role, content: m.text })),
            }),
        });
        const data = await res.json();
        messages.value.push({ role: 'assistant', text: data.text || 'Merci de reformuler.' });
    } catch {
        messages.value.push({ role: 'assistant', text: 'Une erreur est survenue.' });
    }
    loading.value = false;
    scrollDown();
}
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
                <div class="flex items-center gap-3">
                    <button @click="chatOpen = !chatOpen" class="rounded-lg bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 hover:bg-indigo-100">
                        {{ chatOpen ? 'Fermer l\'assistant' : 'Assistant IA' }}
                    </button>
                    <Link :href="route('tenant.logout')" method="post" class="text-sm text-gray-500 hover:text-gray-700">Déconnexion</Link>
                </div>
            </div>

            <nav class="mb-6 flex gap-1 rounded-xl bg-white p-1.5 shadow-sm border border-gray-200">
                <Link v-for="item in navigation" :key="item.name" :href="item.href"
                    class="flex-1 rounded-lg px-4 py-2.5 text-center text-sm font-medium transition"
                    :class="route().current(item.href) ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100'">
                    {{ item.name }}
                </Link>
            </nav>

            <div class="flex gap-6">
                <main class="flex-1">
                    <slot />
                </main>

                <Transition name="slide-up">
                    <div v-if="chatOpen" class="w-80 shrink-0">
                        <div class="sticky top-6 flex h-[65vh] flex-col rounded-xl border border-gray-200 bg-white shadow-sm">
                            <div class="border-b border-gray-100 px-4 py-3">
                                <p class="text-sm font-semibold text-gray-900">Assistant</p>
                            </div>
                            <div ref="chatBody" class="flex-1 space-y-3 overflow-y-auto px-4 py-3">
                                <div v-for="(m, i) in messages" :key="i" class="flex" :class="m.role === 'user' ? 'justify-end' : 'justify-start'">
                                    <div class="max-w-[90%] rounded-2xl px-3.5 py-2 text-sm"
                                        :class="m.role === 'user' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-800'">
                                        {{ m.text }}
                                    </div>
                                </div>
                                <div v-if="loading" class="flex justify-start">
                                    <div class="rounded-2xl bg-gray-100 px-3.5 py-2 text-sm text-gray-400">Réflexion...</div>
                                </div>
                            </div>
                            <div class="border-t border-gray-100 p-3">
                                <form @submit.prevent="send" class="flex gap-2">
                                    <input v-model="input" placeholder="Votre question..." class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" :disabled="loading" />
                                    <button type="submit" :disabled="loading || !input.trim()" class="rounded-lg bg-indigo-600 px-3 py-2 text-white disabled:opacity-50 text-sm">Envoyer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </Transition>
            </div>
        </div>
    </div>
</template>

<style scoped>
.slide-up-enter-active, .slide-up-leave-active { transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1); }
.slide-up-enter-from, .slide-up-leave-to { opacity: 0; transform: translateY(10px); }
</style>
