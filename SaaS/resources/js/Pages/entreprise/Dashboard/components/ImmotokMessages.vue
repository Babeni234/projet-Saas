<template>
  <div class="bg-[#0f111a] min-h-screen text-white flex flex-col h-[calc(100vh-64px)]">
    
    <!-- Top Bar Controls -->
    <header class="bg-[#181a26] border-b border-white/5 px-6 py-4 flex items-center justify-between shadow-md">
      <div>
        <h1 class="text-xl font-bold tracking-tight">Messagerie Clients ImmoTok</h1>
        <p class="text-xs text-gray-400 mt-0.5">Communiquez en direct avec vos prospects ou laissez l'IA répondre.</p>
      </div>
      
      <!-- AI Activation Toggle Switch -->
      <div class="flex items-center gap-3 bg-black/30 border border-white/5 rounded-full px-4 py-2 shadow-inner">
        <div class="flex items-center gap-2">
          <i class="fas fa-robot text-lg text-red-500 animate-pulse"></i>
          <span class="text-xs font-bold uppercase tracking-wider text-gray-200">Service IA Gemini</span>
        </div>
        <button 
          @click="toggleAi"
          class="w-12 h-6 rounded-full relative transition-colors duration-200 focus:outline-none shadow-inner"
          :class="aiEnabled ? 'bg-green-600' : 'bg-gray-700'"
        >
          <div 
            class="w-5 h-5 rounded-full bg-white absolute top-0.5 transition-transform duration-200 shadow-md"
            :class="aiEnabled ? 'translate-x-[26px]' : 'translate-x-0.5'"
          ></div>
        </button>
      </div>
    </header>

    <!-- Main Messenger Panel -->
    <div class="flex-1 flex overflow-hidden">
      
      <!-- Left sidebar: Chats List -->
      <aside class="w-80 bg-[#141520] border-r border-white/5 flex flex-col">
        <!-- Search bar -->
        <div class="p-4 border-b border-white/5">
          <div class="relative flex items-center bg-black/20 border border-white/10 rounded-lg px-3 py-2 text-sm text-gray-400">
            <i class="fas fa-search mr-2 text-xs"></i>
            <input 
              v-model="searchQuery"
              type="text" 
              class="bg-transparent border-none outline-none focus:ring-0 text-xs w-full text-white placeholder-gray-500" 
              placeholder="Rechercher un prospect..."
            />
          </div>
        </div>

        <!-- Scrollable Conversations List -->
        <div class="flex-1 overflow-y-auto">
          <div v-if="chats.length === 0" class="p-8 text-center text-gray-500 flex flex-col gap-2 items-center">
            <i class="fas fa-comment-slash text-3xl"></i>
            <span class="text-xs">Aucune conversation.</span>
          </div>

          <div 
            v-else
            v-for="c in filteredChats" 
            :key="c.client.id"
            @click="selectChat(c)"
            class="px-4 py-3.5 border-b border-white/[0.03] cursor-pointer transition flex items-center gap-3"
            :class="activeChat?.client.id === c.client.id ? 'bg-white/[0.04] border-l-4 border-red-500' : 'hover:bg-white/[0.02]'"
          >
            <!-- Avatar -->
            <div class="relative">
              <img 
                :src="c.client.avatar || 'https://ui-avatars.com/api/?name=' + urlencode(c.client.name) + '&background=random&color=fff'" 
                class="w-10 h-10 rounded-full object-cover bg-gray-800"
                alt="avatar"
              />
              <span v-if="c.unread_count > 0" class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-red-500 text-[10px] font-bold flex items-center justify-center border-2 border-[#141520]">
                {{ c.unread_count }}
              </span>
            </div>
            
            <!-- Context Info -->
            <div class="flex-1 min-w-0 flex flex-col">
              <div class="flex items-center justify-between">
                <h4 class="font-bold text-xs text-gray-200 truncate">{{ c.client.name }}</h4>
                <span class="text-[9px] text-gray-500 font-mono">{{ c.created_at }}</span>
              </div>
              <p class="text-[11px] text-gray-400 truncate mt-1 leading-relaxed">
                <span v-if="c.sender !== 'client'" class="text-red-400 font-semibold">Vous : </span>
                {{ c.latest_message }}
              </p>
            </div>
          </div>
        </div>
      </aside>

      <!-- Right Pane: Active Thread -->
      <section class="flex-1 bg-[#0c0d15] flex flex-col overflow-hidden relative">
        <div v-if="!activeChat" class="flex-1 flex flex-col items-center justify-center text-center text-gray-500 gap-3">
          <i class="fas fa-paper-plane text-5xl animate-bounce"></i>
          <p class="text-sm">Sélectionnez une discussion à gauche pour afficher les messages.</p>
        </div>

        <div v-else class="flex-1 flex flex-col h-full overflow-hidden">
          <!-- Chat Subheader -->
          <div class="bg-[#141520] border-b border-white/5 px-6 py-3 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
              <img 
                :src="activeChat.client.avatar || 'https://ui-avatars.com/api/?name=' + urlencode(activeChat.client.name) + '&background=random&color=fff'" 
                class="w-9 h-9 rounded-full object-cover bg-gray-800"
                alt="avatar"
              />
              <div>
                <h3 class="text-sm font-bold text-gray-100">{{ activeChat.client.name }}</h3>
                <span class="text-[10px] text-gray-400 font-mono">{{ activeChat.client.email }} {{ activeChat.client.phone ? '· ' + activeChat.client.phone : '' }}</span>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <span v-if="aiEnabled" class="px-2.5 py-0.5 rounded-full bg-green-500/10 text-green-400 border border-green-500/20 text-[10px] font-bold uppercase tracking-wider flex items-center gap-1.5 animate-pulse">
                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> IA Gemini Actived
              </span>
              <span v-else class="px-2.5 py-0.5 rounded-full bg-gray-800 text-gray-400 border border-white/5 text-[10px] font-bold uppercase tracking-wider flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-gray-500"></span> Mode Manuel
              </span>
            </div>
          </div>

          <!-- Messages scroll block -->
          <div ref="msgContainerRef" class="flex-1 overflow-y-auto p-6 flex flex-col gap-4">
            <div 
              v-for="msg in messages" 
              :key="msg.id"
              class="max-w-[70%] rounded-2xl px-4 py-2.5 text-sm leading-relaxed"
              :class="msg.sender === 'client' ? 'self-start bg-[#181a26] text-gray-200 rounded-bl-none' : 'self-end bg-red-600 text-white rounded-br-none'"
            >
              {{ msg.message }}
              
              <!-- Badges for sender details & AI automated status -->
              <div class="flex items-center justify-between gap-4 mt-1 border-t border-white/5 pt-1 text-[8px] text-white/40">
                <span>{{ msg.sender === 'client' ? 'Prospect' : (msg.is_ai_reply ? 'IA Assistant' : 'Manuel') }}</span>
                <span>{{ formatTime(msg.created_at) }}</span>
              </div>
            </div>
          </div>

          <!-- Message Composer -->
          <div class="p-4 bg-[#141520] border-t border-white/5 flex items-center gap-3">
            <div class="flex-1 bg-black/40 rounded-xl px-4 py-3 border border-white/10 flex items-center gap-2">
              <input 
                v-model="replyText" 
                type="text" 
                class="flex-1 bg-transparent text-sm text-white focus:outline-none placeholder-gray-600" 
                placeholder="Rédiger un message de réponse..."
                @keyup.enter="submitManualReply"
              />
              <button @click="submitManualReply" class="text-red-500 hover:text-red-400 active:scale-95 transition">
                <i class="fas fa-paper-plane text-base"></i>
              </button>
            </div>
          </div>

        </div>
      </section>

    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted, nextTick } from 'vue';
import axios from 'axios';

const chats = ref([]);
const messages = ref([]);
const activeChat = ref(null);
const replyText = ref('');
const aiEnabled = ref(false);
const searchQuery = ref('');
const msgContainerRef = ref(null);

// Determine API path
const isAgency = window.location.pathname.startsWith('/agence') || window.location.hash.startsWith('#/agence');
const baseApiUrl = '/api/dashboard/immotok';

const filteredChats = computed(() => {
  if (!searchQuery.value) return chats.value;
  const q = searchQuery.value.toLowerCase();
  return chats.value.filter(c => c.client.name.toLowerCase().includes(q) || c.client.email.toLowerCase().includes(q));
});

const fetchChats = async () => {
  try {
    const res = await axios.get(`${baseApiUrl}/chats`);
    chats.value = res.data.chats || [];
    aiEnabled.value = res.data.ai_enabled || false;
  } catch (e) {
    console.error(e);
  }
};

const selectChat = async (c) => {
  activeChat.value = c;
  c.unread_count = 0; // mark read locally
  try {
    const res = await axios.get(`${baseApiUrl}/chats/${c.client.id}`);
    messages.value = res.data || [];
    scrollToBottom();
  } catch (e) {
    console.error(e);
  }
};

const submitManualReply = async () => {
  if (!activeChat.value || !replyText.value || !replyText.value.trim()) return;

  const payload = { message: replyText.value };
  replyText.value = '';

  try {
    const res = await axios.post(`${baseApiUrl}/chats/${activeChat.value.client.id}/reply`, payload);
    if (res.data.success) {
      messages.value.push(res.data.message);
      scrollToBottom();
      // Refresh list to pull latest info
      fetchChats();
    }
  } catch (e) {
    alert("Une erreur s'est produite lors de l'envoi de la réponse.");
  }
};

const toggleAi = async () => {
  const nextState = !aiEnabled.value;
  try {
    const res = await axios.post(`${baseApiUrl}/chat-settings/toggle-ai`, { enabled: nextState });
    if (res.data.success) {
      aiEnabled.value = res.data.ai_enabled;
    }
  } catch (e) {
    alert("Impossible de modifier les réglages de l'IA.");
  }
};

const scrollToBottom = () => {
  nextTick(() => {
    if (msgContainerRef.value) {
      msgContainerRef.value.scrollTop = msgContainerRef.value.scrollHeight;
    }
  });
};

const formatTime = (dateStr) => {
  if (!dateStr) return '';
  const date = new Date(dateStr);
  return date.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
};

const urlencode = (str) => {
  return encodeURIComponent(str || '');
};

onMounted(() => {
  fetchChats();
  // Poll periodically
  const timer = setInterval(fetchChats, 8000);
  return () => clearInterval(timer);
});
</script>
