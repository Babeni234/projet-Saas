<script setup>
import { ref, onMounted, nextTick, onUnmounted } from 'vue';

const emit = defineEmits(['close']);

const messages = ref([
    { role: 'assistant', text: '👋 Bonjour ! Je suis votre assistant ImmoSaas. Posez-moi des questions sur vos biens, locataires, finances ou incidents.' }
]);
const input = ref('');
const loading = ref(false);
const streaming = ref(false);
const chatBody = ref(null);
const streamText = ref('');

let abortController = null;

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
    streaming.value = false;
    streamText.value = '';
    scrollDown();

    // Add a placeholder for the streaming response
    const msgIdx = messages.value.length;
    messages.value.push({ role: 'assistant', text: '', streaming: true });
    scrollDown();

    try {
        const res = await fetch('/api/ai/chat/stream', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
            },
            body: JSON.stringify({
                message: msg,
                history: messages.value.slice(0, -1).filter(m => !m.streaming).map(m => ({ role: m.role, content: m.text })),
            }),
        });

        const reader = res.body.getReader();
        const decoder = new TextDecoder();
        let buffer = '';
        streaming.value = true;

        while (true) {
            const { done, value } = await reader.read();
            if (done) break;

            buffer += decoder.decode(value, { stream: true });
            const lines = buffer.split('\n');
            buffer = lines.pop() || '';

            for (const line of lines) {
                if (!line.startsWith('data: ')) continue;
                const data = JSON.parse(line.slice(6));

                if (data.type === 'text') {
                    streamText.value += data.content;
                    messages.value[msgIdx].text = streamText.value;
                    scrollDown();
                } else if (data.type === 'tool_start') {
                    streamText.value += '\n\n🔍 *Exécution d\'actions...*';
                    messages.value[msgIdx].text = streamText.value;
                    scrollDown();
                } else if (data.type === 'tool_call') {
                    streamText.value += `\n📋 *Action : ${data.name}*`;
                    messages.value[msgIdx].text = streamText.value;
                    scrollDown();
                } else if (data.type === 'tool_end') {
                    streamText.value += '\n✅ *Actions terminées*';
                    messages.value[msgIdx].text = streamText.value;
                    scrollDown();
                } else if (data.type === 'done') {
                    streaming.value = false;
                    messages.value[msgIdx].streaming = false;
                    scrollDown();
                }
            }
        }
    } catch (err) {
        if (err.name === 'AbortError') return;
        messages.value[msgIdx].text = 'Désolé, une erreur est survenue. Réessayez.';
        messages.value[msgIdx].streaming = false;
    }

    streaming.value = false;
    loading.value = false;
    scrollDown();
}

function formatText(text) {
    let html = text
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
        .replace(/\n/g, '<br>');
    return html;
}

const suggestions = [
    'Résumé de ma situation',
    'Quels sont les impayés ?',
    'Génère un rapport',
    'Qu\'est-ce qui nécessite mon attention ?',
    'Ajoute un bien (Appartement F3, Paris, 950€)',
];
</script>

<template>
    <div class="flex h-full flex-col bg-white">
        <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3">
            <div class="flex items-center gap-2">
                <div class="flex h-7 w-7 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-[10px] font-bold text-white">AI</div>
                <span class="text-sm font-semibold text-gray-900">Assistant IA</span>
                <span v-if="loading && streaming" class="text-[10px] text-green-600 font-medium bg-green-50 px-1.5 py-0.5 rounded-full">Streaming</span>
            </div>
            <div class="flex items-center gap-1">
                <button @click="emit('close')" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>

        <div ref="chatBody" class="flex-1 space-y-3 overflow-y-auto px-4 py-4 scroll-smooth">
            <div v-for="(m, i) in messages" :key="i" class="flex" :class="m.role === 'user' ? 'justify-end' : 'justify-start'">
                <div class="max-w-[88%] rounded-2xl px-4 py-2.5 text-sm leading-relaxed"
                    :class="m.role === 'user' ? 'bg-indigo-600 text-white' : 'bg-gray-50 border border-gray-100 text-gray-800'">
                    <div v-if="m.role === 'assistant' && !m.text && m.streaming">
                        <span class="inline-flex gap-1">
                            <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-gray-400" style="animation-delay:0ms" />
                            <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-gray-400" style="animation-delay:150ms" />
                            <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-gray-400" style="animation-delay:300ms" />
                        </span>
                    </div>
                    <div v-else v-html="formatText(m.text)" />
                </div>
            </div>
        </div>

        <!-- Suggestions -->
        <div v-if="messages.length === 1 && !loading" class="px-4 pb-2">
            <div class="flex flex-wrap gap-1.5">
                <button v-for="s in suggestions" :key="s" @click="input = s; send()"
                    class="rounded-full border border-gray-200 bg-gray-50 px-2.5 py-1 text-[11px] text-gray-600 hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700 transition">
                    {{ s }}
                </button>
            </div>
        </div>

        <div class="border-t border-gray-100 p-3">
            <form @submit.prevent="send" class="flex gap-2">
                <input v-model="input" type="text" placeholder="Posez votre question..."
                    class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                    :disabled="loading" />
                <button type="submit" :disabled="loading || !input.trim()"
                    class="rounded-lg bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50 transition">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7" /></svg>
                </button>
            </form>
            <p class="mt-1.5 text-[10px] text-gray-400 text-center">
                L'assistant peut exécuter des actions (créer, modifier) sur vos biens, incidents et quittances.
            </p>
        </div>
    </div>
</template>
