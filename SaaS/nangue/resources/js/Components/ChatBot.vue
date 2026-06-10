<script setup>
import { ref } from 'vue';
import faq from './chat/knowledgeBase.js';

const open = ref(false);
const messages = ref([
  { role: 'bot', text: '👋 Bonjour ! Je suis votre assistant Immo. Je peux répondre à toutes vos questions sur l\'application. Posez-moi une question !' }
]);
const input = ref('');

const normalize = (s) => s.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();

const findBestMatch = (question) => {
  const q = normalize(question);
  const words = q.split(/\s+/).filter(w => w.length > 2);

  let best = { answer: null, score: 0 };

  for (const entry of faq) {
    let score = 0;
    for (const kw of entry.keywords) {
      const nkw = normalize(kw);
      if (q.includes(nkw)) {
        score += nkw.length * 3;
      } else {
        const kwWords = nkw.split(/\s+/);
        const matchCount = kwWords.filter(w => words.includes(w)).length;
        if (matchCount > 0) {
          score += matchCount * 2;
        }
      }
    }
    if (score > best.score) {
      best = { answer: entry.answer, score };
    }
  }

  return best.score >= 4 ? best.answer : null;
};

const followUps = [
  'Que puis-je faire sur cette plateforme ?',
  'Comment devenir bailleur ?',
  'Comment créer une annonce ?',
  'Où sont mes messages ?',
  'Comment fonctionnent les visites ?',
];

const send = () => {
  const text = input.value.trim();
  if (!text) return;
  messages.value.push({ role: 'user', text });
  input.value = '';

  const answer = findBestMatch(text);
  const reply = answer || 'Je n\'ai pas trouvé de réponse spécifique. Essayez de reformuler votre question. Voici quelques thèmes : compte, propriétés, annonces, contrats, locataires, visites, quittances, favoris, messages, statistiques.';
  setTimeout(() => {
    messages.value.push({ role: 'bot', text: reply });
    if (answer && messages.value.length <= 4) {
      setTimeout(() => {
        messages.value.push({
          role: 'bot',
          text: 'Suggestions : ' + followUps.sort(() => Math.random() - 0.5).slice(0, 3).join(' • ')
        });
      }, 1000);
    }
  }, 400);
};
</script>

<template>
  <div class="fixed bottom-6 right-6 z-50">
    <Transition name="chat">
      <div v-if="open" class="mb-4 w-80 sm:w-96 rounded-2xl border border-slate-200 bg-white shadow-imo-lg dark:border-slate-700 dark:bg-slate-800 flex flex-col overflow-hidden">
        <div class="flex items-center justify-between bg-emerald-600 px-4 py-3 text-white">
          <div class="flex items-center gap-2">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <span class="font-semibold">Assistant Immo</span>
          </div>
          <button @click="open = false" class="text-white/80 hover:text-white transition">&times;</button>
        </div>
        <div class="flex-1 space-y-3 overflow-y-auto p-4" style="max-height:360px">
          <div v-for="(msg, i) in messages" :key="i" class="flex" :class="msg.role === 'user' ? 'justify-end' : 'justify-start'">
            <div v-if="msg.text.startsWith('Suggestions')" class="max-w-[95%] rounded-2xl px-4 py-2 text-xs text-slate-400 dark:text-slate-500 italic">
              {{ msg.text }}
            </div>
            <div v-else class="max-w-[88%] rounded-2xl px-4 py-2 text-sm leading-relaxed" :class="msg.role === 'user' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200'">
              {{ msg.text }}
            </div>
          </div>
        </div>
        <div class="flex gap-2 border-t border-slate-100 p-3 dark:border-slate-700">
          <input v-model="input" @keydown.enter.prevent="send" type="text" placeholder="Posez votre question..." class="flex-1 rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none focus:border-emerald-400 dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:placeholder-slate-400">
          <button @click="send" class="rounded-lg bg-emerald-600 px-3 py-2 text-white hover:bg-emerald-700 transition">&uarr;</button>
        </div>
      </div>
    </Transition>
    <button @click="open = !open" class="flex h-14 w-14 items-center justify-center rounded-full bg-emerald-600 text-white shadow-imo-lg hover:bg-emerald-700 transition-transform hover:scale-105">
      <svg v-if="!open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
      </svg>
      <svg v-else class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
  </div>
</template>

<style scoped>
.chat-enter-active, .chat-leave-active { transition: all 0.3s ease; }
.chat-enter-from, .chat-leave-to { opacity: 0; transform: translateY(20px) scale(0.95); }
</style>
