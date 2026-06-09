<template>
    <div class="fixed bottom-6 right-6 z-[100] flex flex-col items-end select-none">
        
        <!-- Chat Window (Glassmorphic modern design) -->
        <div 
            v-if="isOpen"
            class="w-96 max-w-[calc(100vw-2rem)] h-[550px] mb-4 bg-white rounded-3xl border border-slate-200/80 shadow-2xl flex flex-col overflow-hidden animate-scale-up z-[101]"
        >
            <!-- Header -->
            <div class="px-5 py-4 bg-gradient-to-r from-slate-900 via-slate-950 to-slate-900 text-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold tracking-wide">Assistant de Gestion</h3>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">En ligne (Groq Llama3)</span>
                        </div>
                    </div>
                </div>
                <button 
                    @click="toggleChat" 
                    class="w-8 h-8 rounded-lg hover:bg-white/10 flex items-center justify-center transition-colors"
                >
                    <svg class="w-4 h-4 text-slate-400 hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Messages Log -->
            <div 
                ref="messageBox"
                class="flex-1 overflow-y-auto p-4 bg-slate-50 space-y-4 scrollbar-thin"
            >
                <div 
                    v-for="(msg, idx) in messages" 
                    :key="idx"
                    :class="['flex', msg.role === 'user' ? 'justify-end' : 'justify-start']"
                >
                    <div 
                        :class="[
                            'max-w-[85%] rounded-2xl px-4 py-3 text-sm shadow-sm',
                            msg.role === 'user' 
                                ? 'bg-indigo-600 text-white rounded-br-none font-medium' 
                                : 'bg-white text-slate-800 border border-slate-150 rounded-bl-none leading-relaxed'
                        ]"
                    >
                        <div v-if="msg.role === 'user'">
                            {{ msg.content }}
                        </div>
                        <div v-else class="rich-text-content" v-html="msg.content"></div>
                    </div>
                </div>

                <!-- Loading animation -->
                <div v-if="loading" class="flex justify-start">
                    <div class="bg-white border border-slate-150 rounded-2xl rounded-bl-none px-4 py-3 shadow-sm flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-indigo-500 animate-bounce"></span>
                        <span class="w-2 h-2 rounded-full bg-indigo-500 animate-bounce [animation-delay:0.2s]"></span>
                        <span class="w-2 h-2 rounded-full bg-indigo-500 animate-bounce [animation-delay:0.4s]"></span>
                    </div>
                </div>
            </div>

            <!-- Suggestion row -->
            <div class="px-4 py-2 bg-slate-100/50 border-t border-slate-150 overflow-x-auto flex gap-2 no-scrollbar scroll-smooth">
                <button 
                    v-for="(chip, idx) in suggestions" 
                    :key="idx" 
                    @click="sendPreset(chip.prompt)"
                    class="whitespace-nowrap px-3 py-1.5 bg-white border border-slate-200 rounded-full text-xs font-semibold text-slate-600 hover:border-indigo-500 hover:text-indigo-600 transition-all shadow-sm"
                >
                    {{ chip.label }}
                </button>
            </div>

            <!-- Footer / Input Form -->
            <form 
                @submit.prevent="sendMessage"
                class="p-3 bg-white border-t border-slate-150 flex items-center gap-2"
            >
                <input 
                    v-model="inputMessage"
                    type="text"
                    placeholder="Posez votre question à l'assistant..."
                    class="flex-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:border-indigo-500 focus:bg-white transition-all shadow-inner"
                    :disabled="loading"
                />
                <button 
                    type="submit"
                    class="w-10 h-10 rounded-xl bg-indigo-650 hover:bg-indigo-600 text-white flex items-center justify-center shadow-md transition-all transform active:scale-95 disabled:opacity-55 disabled:cursor-not-allowed"
                    :disabled="loading || !inputMessage.trim()"
                >
                    <svg class="w-5 h-5 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </button>
            </form>
        </div>

        <!-- Floating Chat Trigger Button -->
        <button 
            @click="toggleChat"
            class="w-14 h-14 rounded-full bg-gradient-to-br from-indigo-600 to-violet-700 hover:from-indigo-500 hover:to-violet-600 text-white flex items-center justify-center shadow-lg shadow-indigo-650/35 hover:scale-105 transition-all duration-300 relative border border-white/20 group"
            :title="isOpen ? 'Fermer l\'assistant' : 'Ouvrir l\'assistant de gestion'"
        >
            <!-- Pulse ring effect -->
            <span class="absolute inset-0 rounded-full bg-indigo-500/30 animate-ping opacity-75 group-hover:animate-none pointer-events-none"></span>
            
            <svg v-if="!isOpen" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
            </svg>
            <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

    </div>
</template>

<script setup>
import { ref, nextTick } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();

const isOpen = ref(false);
const loading = ref(false);
const inputMessage = ref('');
const messageBox = ref(null);

const messages = ref([
    { role: 'assistant', content: '<p>Bonjour <strong>Administrateur</strong> ! Je suis votre assistant de gestion intelligent Enterprise Property Corp.</p><p>Je peux vous aider à analyser la base de données (locataires, factures, loyers impayés) et vous rediriger vers les modules appropriés de l\'application. Que voulez-vous savoir ?</p>' }
]);

const suggestions = [
    { label: '📊 Loyers Impayés', prompt: 'Qui n\'a pas payé ses factures ou ses loyers ?' },
    { label: '💶 Revenu Mensuel', prompt: 'Combien gagne-t-on en loyers mensuels au total ?' },
    { label: '📁 Voir les Contrats', prompt: 'Affiche-moi la page des contrats de bail.' },
    { label: '👥 Locataires Actifs', prompt: 'Quels sont nos locataires actifs et leurs logements ?' },
    { label: '⚠️ Baux Expirés', prompt: 'Quels baux sont expirés ?' }
];

const toggleChat = () => {
    isOpen.value = !isOpen.value;
    if (isOpen.value) {
        scrollToBottom();
    }
};

const scrollToBottom = () => {
    nextTick(() => {
        if (messageBox.value) {
            messageBox.value.scrollTop = messageBox.value.scrollHeight;
        }
    });
};

const sendPreset = (promptText) => {
    inputMessage.value = promptText;
    sendMessage();
};

const sendMessage = async () => {
    const text = inputMessage.value.trim();
    if (!text || loading.value) return;

    // Add user message
    messages.value.push({ role: 'user', content: text });
    inputMessage.value = '';
    loading.value = true;
    scrollToBottom();

    // Collect all local database context
    const context = {
        contrats: getLocalJSON('immobilier_contrats'),
        locataires: getLocalJSON('immobilier_locataires'),
        renouvellements: getLocalJSON('immobilier_renouvellements'),
        logements: getLocalJSON('immobilier_logements'),
        batiments: getLocalJSON('immobilier_batiments'),
        factures: getLocalJSON('immobilier_factures'),
        paiements: getLocalJSON('immobilier_paiements'),
        affectations: getLocalJSON('immobilier_affectations')
    };

    try {
        const response = await axios.post('/api/ai/assistant', {
            message: text,
            context: context
        });

        if (response.data && response.data.success) {
            let reply = response.data.response;
            
            // Check for navigation command: [NAVIGATE: /path]
            const navRegex = /\[NAVIGATE:\s*([^[\]]+)\]/i;
            const match = reply.match(navRegex);
            if (match && match[1]) {
                const targetPath = match[1].trim();
                // Strip the command from the displayed message
                reply = reply.replace(navRegex, '').trim();
                
                // Programmatic navigation
                setTimeout(() => {
                    router.push(targetPath);
                }, 1000);
            }

            // Convert raw newlines to paragraph tags if not structured HTML already
            if (!reply.includes('<p>') && !reply.includes('<li>')) {
                reply = reply
                    .split('\n\n')
                    .map(p => `<p>${p.replace(/\n/g, '<br>')}</p>`)
                    .join('');
            }

            messages.value.push({ role: 'assistant', content: reply });
        } else {
            messages.value.push({ 
                role: 'assistant', 
                content: '<p class="text-rose-600 font-bold">Erreur : Impossible de traiter la demande.</p>' 
            });
        }
    } catch (e) {
        console.error(e);
        messages.value.push({ 
            role: 'assistant', 
            content: '<p class="text-rose-600 font-semibold">Une erreur réseau s\'est produite lors de la communication avec le serveur.</p>' 
        });
    } finally {
        loading.value = false;
        scrollToBottom();
    }
};

const getLocalJSON = (key) => {
    try {
        const item = localStorage.getItem(key);
        return item ? JSON.parse(item) : [];
    } catch (e) {
        console.error(`Error reading key ${key} from localStorage`, e);
        return [];
    }
};
</script>

<style scoped>
@keyframes scaleUp {
    from {
        opacity: 0;
        transform: scale(0.95) translateY(10px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.animate-scale-up {
    animation: scaleUp 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.rich-text-content :deep(p) {
    margin-bottom: 0.5rem;
}
.rich-text-content :deep(p:last-child) {
    margin-bottom: 0;
}
.rich-text-content :deep(ul) {
    list-style-type: disc;
    padding-left: 1.25rem;
    margin-bottom: 0.5rem;
}
.rich-text-content :deep(li) {
    margin-bottom: 0.25rem;
}
.rich-text-content :deep(strong) {
    font-weight: 700;
}

.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;  /* Firefox */
}
</style>
