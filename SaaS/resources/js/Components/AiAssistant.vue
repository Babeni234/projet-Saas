<script setup>
import { ref, onMounted, nextTick } from 'vue';

const emit = defineEmits(['close']);

const messages = ref([
    { role: 'assistant', text: '👋 Bonjour ! Je suis votre assistant ImmoSaas. Posez-moi des questions sur vos biens, locataires, finances ou incidents.' }
]);
const input = ref('');
const loading = ref(false);
const chatBody = ref(null);

function scrollDown() {
    nextTick(() => {
        if (chatBody.value) {
            chatBody.value.scrollTop = chatBody.value.scrollHeight;
        }
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
        const res = await fetch('/api/ai/chat', {
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
        messages.value.push({
            role: 'assistant',
            text: data.text || 'Je n\'ai pas pu traiter votre demande.',
        });
    } catch {
        messages.value.push({
            role: 'assistant',
            text: 'Désolé, une erreur est survenue. Réessayez.',
        });
    }

    loading.value = false;
    scrollDown();
}

function formatText(text) {
    return text
        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
        .replace(/\n/g, '<br>');
}
</script>

<template>
    <div class="flex h-full flex-col bg-white">
        <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3">
            <div class="flex items-center gap-2">
                <div class="flex h-7 w-7 items-center justify-center rounded-full bg-indigo-100 text-xs font-bold text-indigo-700">AI</div>
                <span class="text-sm font-semibold text-gray-900">Assistant IA</span>
            </div>
            <button @click="emit('close')" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <div ref="chatBody" class="flex-1 space-y-3 overflow-y-auto px-4 py-4">
            <div v-for="(m, i) in messages" :key="i" class="flex" :class="m.role === 'user' ? 'justify-end' : 'justify-start'">
                <div class="max-w-[85%] rounded-2xl px-4 py-2.5 text-sm"
                    :class="m.role === 'user' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-800'">
                    <div v-html="formatText(m.text)" />
                </div>
            </div>
            <div v-if="loading" class="flex justify-start">
                <div class="max-w-[85%] rounded-2xl bg-gray-100 px-4 py-2.5 text-sm text-gray-500">
                    <span class="inline-flex gap-1">
                        <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-gray-400" style="animation-delay:0ms" />
                        <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-gray-400" style="animation-delay:150ms" />
                        <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-gray-400" style="animation-delay:300ms" />
                    </span>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-100 p-3">
            <form @submit.prevent="send" class="flex gap-2">
                <input v-model="input" type="text" placeholder="Posez votre question..."
                    class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                    :disabled="loading" />
                <button type="submit" :disabled="loading || !input.trim()"
                    class="rounded-lg bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7" /></svg>
                </button>
            </form>
        </div>
    </div>
</template>
