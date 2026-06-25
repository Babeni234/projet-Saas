<template>
  <div class="p-6 bg-[#0f111a] min-h-screen text-white flex flex-col gap-6">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-white/5 pb-4">
      <div>
        <h1 class="text-2xl font-bold tracking-tight">Interactions ImmoTok</h1>
        <p class="text-sm text-gray-400 mt-1">
          Gérez les retours, likes, favoris et répondez aux commentaires de vos publications.
        </p>
      </div>
      <div class="flex items-center gap-3">
        <button @click="fetchInteractions" class="px-4 py-2 bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg text-sm font-semibold flex items-center gap-2 transition active:scale-95">
          <i class="fas fa-sync-alt"></i> Actualiser
        </button>
      </div>
    </div>

    <!-- Summary KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
      <div class="bg-[#181a26] border border-white/5 rounded-2xl p-5 flex items-center justify-between shadow-xl">
        <div class="flex flex-col gap-1">
          <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Likes</span>
          <span class="text-3xl font-extrabold text-red-500 mt-1">{{ likes.length }}</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-red-500/10 flex items-center justify-center text-red-500 text-2xl">
          <i class="fas fa-heart"></i>
        </div>
      </div>
      <div class="bg-[#181a26] border border-white/5 rounded-2xl p-5 flex items-center justify-between shadow-xl">
        <div class="flex flex-col gap-1">
          <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Mises en favoris</span>
          <span class="text-3xl font-extrabold text-yellow-500 mt-1">{{ favorites.length }}</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-yellow-500/10 flex items-center justify-center text-yellow-500 text-2xl">
          <i class="fas fa-bookmark"></i>
        </div>
      </div>
      <div class="bg-[#181a26] border border-white/5 rounded-2xl p-5 flex items-center justify-between shadow-xl">
        <div class="flex flex-col gap-1">
          <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Commentaires</span>
          <span class="text-3xl font-extrabold text-blue-500 mt-1">{{ comments.length }}</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-500 text-2xl">
          <i class="fas fa-comments"></i>
        </div>
      </div>
    </div>

    <!-- Tab Panels -->
    <div class="bg-[#181a26] border border-white/5 rounded-2xl flex-1 flex flex-col overflow-hidden shadow-xl">
      <!-- Tabs header -->
      <div class="flex border-b border-white/5 bg-black/10 px-4">
        <button 
          v-for="tab in ['comments', 'likes', 'favorites']" 
          :key="tab"
          @click="activeTab = tab"
          class="py-3 px-5 text-sm font-bold border-b-2 transition relative flex items-center gap-2"
          :class="activeTab === tab ? 'border-red-500 text-white' : 'border-transparent text-gray-400 hover:text-white'"
        >
          <i :class="tab === 'comments' ? 'fas fa-comments' : (tab === 'likes' ? 'fas fa-heart' : 'fas fa-bookmark')"></i>
          <span>{{ tab === 'comments' ? 'Commentaires' : (tab === 'likes' ? 'Likes' : 'Favoris') }}</span>
        </button>
      </div>

      <!-- Tab body -->
      <div class="flex-1 overflow-y-auto p-5">
        
        <!-- COMMENTS PANEL -->
        <div v-if="activeTab === 'comments'" class="flex flex-col gap-6">
          <div v-if="comments.length === 0" class="py-12 flex flex-col items-center justify-center text-center text-gray-500 gap-3">
            <i class="fas fa-comments text-5xl"></i>
            <p>Aucun commentaire publié sur vos illustrations pour le moment.</p>
          </div>

          <div 
            v-for="c in comments" 
            :key="c.id"
            class="bg-[#0f111a] border border-white/5 rounded-xl p-5 flex flex-col md:flex-row gap-4"
          >
            <!-- Comment Source Illustration Preview -->
            <div class="w-full md:w-40 h-28 rounded-lg overflow-hidden relative group bg-black flex items-center justify-center border border-white/10">
              <img 
                v-if="c.illustration?.media_type === 'image'" 
                :src="c.illustration.media_url" 
                class="w-full h-full object-cover" 
                alt="Preview"
              />
              <video 
                v-else-if="c.illustration?.media_type === 'video'" 
                :src="c.illustration.media_url" 
                class="w-full h-full object-cover"
                muted
              ></video>
              <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-xs font-bold text-gray-300 text-center p-2">
                {{ c.illustration?.target_name }}
              </div>
            </div>

            <!-- Comment Content & Replies -->
            <div class="flex-1 flex flex-col gap-3">
              <div class="flex items-start justify-between gap-4">
                <div class="flex items-center gap-3">
                  <img :src="c.avatar" class="w-10 h-10 rounded-full object-cover bg-gray-800" alt="avatar"/>
                  <div>
                    <h3 class="font-bold text-sm text-gray-200">{{ c.name }}</h3>
                    <span class="text-[10px] text-gray-500">{{ c.created_at }}</span>
                  </div>
                </div>
              </div>
              
              <p class="text-sm text-gray-200 bg-white/5 rounded-lg p-3 leading-relaxed border border-white/5">
                {{ c.text }}
              </p>

              <!-- Threaded Replies -->
              <div v-if="c.replies && c.replies.length > 0" class="flex flex-col gap-3 pl-8 border-l border-white/5 py-1">
                <div 
                  v-for="reply in c.replies" 
                  :key="reply.id"
                  class="flex gap-3 bg-white/[0.02] border border-white/5 rounded-lg p-3"
                >
                  <img :src="reply.avatar" class="w-8 h-8 rounded-full object-cover bg-gray-700" alt="avatar"/>
                  <div class="flex-1">
                    <div class="flex items-center justify-between">
                      <h4 class="text-xs font-bold text-gray-300">{{ reply.name }}</h4>
                      <span class="text-[10px] text-gray-500">{{ reply.created_at }}</span>
                    </div>
                    <p class="text-xs text-gray-200 mt-1 leading-relaxed">{{ reply.text }}</p>
                  </div>
                </div>
              </div>

              <!-- Reply Editor -->
              <div class="flex items-center gap-3 mt-2 pl-8">
                <div class="flex-1 bg-black/40 rounded-lg px-4 py-2 border border-white/10 flex items-center gap-2">
                  <input 
                    v-model="replyTexts[c.id]" 
                    type="text" 
                    class="flex-1 bg-transparent text-xs text-white focus:outline-none placeholder-gray-500" 
                    placeholder="Écrire une réponse publique..."
                    @keyup.enter="submitReply(c.id)"
                  />
                  <button @click="submitReply(c.id)" class="text-red-500 hover:text-red-400 active:scale-95 transition">
                    <i class="fas fa-paper-plane text-sm"></i>
                  </button>
                </div>
              </div>

            </div>
          </div>
        </div>

        <!-- LIKES PANEL -->
        <div v-if="activeTab === 'likes'" class="flex flex-col">
          <div v-if="likes.length === 0" class="py-12 flex flex-col items-center justify-center text-center text-gray-500 gap-3">
            <i class="fas fa-heart-broken text-5xl"></i>
            <p>Aucun like enregistré pour le moment.</p>
          </div>

          <div v-else class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="border-b border-white/5 text-gray-400 text-xs uppercase font-bold">
                  <th class="py-3 px-4">Utilisateur</th>
                  <th class="py-3 px-4">Illustration visée</th>
                  <th class="py-3 px-4">Type média</th>
                  <th class="py-3 px-4">Date</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-white/5 text-sm text-gray-200">
                <tr v-for="l in likes" :key="l.id" class="hover:bg-white/[0.02] transition">
                  <td class="py-3.5 px-4 font-semibold">{{ l.client_name }} <br><span class="text-xs text-gray-500 font-normal">{{ l.client_email }}</span></td>
                  <td class="py-3.5 px-4 text-gray-300 font-medium">{{ l.illustration.target_name }}</td>
                  <td class="py-3.5 px-4">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-red-500/10 text-red-400 border border-red-500/20">
                      {{ l.illustration.media_type }}
                    </span>
                  </td>
                  <td class="py-3.5 px-4 text-gray-500">{{ l.created_at }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- FAVORITES PANEL -->
        <div v-if="activeTab === 'favorites'" class="flex flex-col">
          <div v-if="favorites.length === 0" class="py-12 flex flex-col items-center justify-center text-center text-gray-500 gap-3">
            <i class="fas fa-bookmark text-5xl"></i>
            <p>Aucun utilisateur n'a sauvegardé vos publications dans ses favoris.</p>
          </div>

          <div v-else class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="border-b border-white/5 text-gray-400 text-xs uppercase font-bold">
                  <th class="py-3 px-4">Utilisateur</th>
                  <th class="py-3 px-4">Illustration visée</th>
                  <th class="py-3 px-4">Type média</th>
                  <th class="py-3 px-4">Date</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-white/5 text-sm text-gray-200">
                <tr v-for="f in favorites" :key="f.id" class="hover:bg-white/[0.02] transition">
                  <td class="py-3.5 px-4 font-semibold">{{ f.client_name }} <br><span class="text-xs text-gray-500 font-normal">{{ f.client_email }}</span></td>
                  <td class="py-3.5 px-4 text-gray-300 font-medium">{{ f.illustration.target_name }}</td>
                  <td class="py-3.5 px-4">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">
                      {{ f.illustration.media_type }}
                    </span>
                  </td>
                  <td class="py-3.5 px-4 text-gray-500">{{ f.created_at }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const activeTab = ref('comments');
const comments = ref([]);
const likes = ref([]);
const favorites = ref([]);
const replyTexts = ref({});

// Determine dashboard scope URL
const isAgency = window.location.pathname.startsWith('/agence') || window.location.hash.startsWith('#/agence');
const baseApiUrl = isAgency ? '/api/dashboard/immotok' : '/api/dashboard/immotok'; // backend controllers handle scope automatically based on request or Auth user

const fetchInteractions = async () => {
  try {
    const res = await axios.get(`${baseApiUrl}/interactions`);
    comments.value = res.data.comments || [];
    likes.value = res.data.likes || [];
    favorites.value = res.data.favorites || [];
  } catch (e) {
    console.error(e);
  }
};

const submitReply = async (commentId) => {
  const text = replyTexts.value[commentId];
  if (!text || !text.trim()) return;

  try {
    const res = await axios.post(`${baseApiUrl}/comments/${commentId}/reply`, { text });
    if (res.data.success) {
      // Find comment and append reply locally
      const parent = comments.value.find(item => item.id === commentId);
      if (parent) {
        if (!parent.replies) parent.replies = [];
        parent.replies.push(res.data.comment);
      }
      replyTexts.value[commentId] = '';
    }
  } catch (e) {
    alert("Une erreur s'est produite lors de l'envoi de la réponse.");
  }
};

onMounted(() => {
  fetchInteractions();
});
</script>
