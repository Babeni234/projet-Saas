<template>
  <div class="p-6 bg-[#0f111a] min-h-screen text-white flex flex-col gap-6">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-white/5 pb-4">
      <div>
        <h1 class="text-2xl font-bold tracking-tight">Abonnés ImmoTok</h1>
        <p class="text-sm text-gray-400 mt-1">
          Visualisez la liste des prospects abonnés à vos publications et à votre profil entreprise sur ImmoTok.
        </p>
      </div>
      <div class="flex items-center gap-3">
        <button @click="fetchSubscribers" class="px-4 py-2 bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg text-sm font-semibold flex items-center gap-2 transition active:scale-95">
          <i class="fas fa-sync-alt animate-hover"></i> Actualiser
        </button>
      </div>
    </div>

    <!-- Summary KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
      <div class="bg-[#181a26] border border-white/5 rounded-2xl p-5 flex items-center justify-between shadow-xl">
        <div class="flex flex-col gap-1">
          <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Abonnés</span>
          <span class="text-3xl font-extrabold text-red-500 mt-1">{{ subscribersCount }}</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-red-500/10 flex items-center justify-center text-red-500 text-2xl">
          <i class="fas fa-users"></i>
        </div>
      </div>
    </div>

    <!-- Subscribers Table -->
    <div class="bg-[#181a26] border border-white/5 rounded-2xl flex-1 flex flex-col overflow-hidden shadow-xl">
      <div class="p-5 flex-1 overflow-y-auto">
        <div v-if="subscribers.length === 0" class="py-16 flex flex-col items-center justify-center text-center text-gray-500 gap-3">
          <i class="fas fa-users-slash text-5xl"></i>
          <p>Aucun abonné enregistré pour le moment. Engagez plus de prospects en publiant de nouvelles illustrations !</p>
        </div>

        <div v-else class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="border-b border-white/5 text-gray-400 text-xs uppercase font-bold">
                <th class="py-3 px-4">Utilisateur</th>
                <th class="py-3 px-4">Email</th>
                <th class="py-3 px-4">Téléphone</th>
                <th class="py-3 px-4">Abonné depuis</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-white/5 text-sm text-gray-200">
              <tr v-for="s in subscribers" :key="s.id" class="hover:bg-white/[0.02] transition">
                <td class="py-3.5 px-4 flex items-center gap-3">
                  <img 
                    :src="s.client.avatar || 'https://ui-avatars.com/api/?name=' + urlencode(s.client.name) + '&background=random&color=fff'" 
                    class="w-10 h-10 rounded-full object-cover bg-gray-800 shadow-md border border-white/10" 
                    alt="avatar"
                  />
                  <span class="font-semibold">{{ s.client.name }}</span>
                </td>
                <td class="py-3.5 px-4 text-gray-300 font-mono text-xs">{{ s.client.email }}</td>
                <td class="py-3.5 px-4 text-gray-300 font-mono text-xs">{{ s.client.phone || 'Non renseigné' }}</td>
                <td class="py-3.5 px-4 text-gray-500">{{ s.subscribed_at }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const subscribers = ref([]);
const subscribersCount = ref(0);

const fetchSubscribers = async () => {
  try {
    const res = await axios.get('/api/dashboard/immotok/subscribers');
    subscribers.value = res.data.subscribers;
    subscribersCount.value = res.data.count;
  } catch (e) {
    console.error("Erreur lors de la récupération des abonnés ImmoTok:", e);
  }
};

const urlencode = (str) => {
  return encodeURIComponent(str);
};

onMounted(() => {
  fetchSubscribers();
});
</script>

<style scoped>
.animate-hover:hover {
  transform: rotate(18deg);
  transition: transform 0.2s ease-in-out;
}
</style>
