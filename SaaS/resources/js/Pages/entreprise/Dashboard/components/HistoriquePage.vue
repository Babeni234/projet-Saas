<template>
    <div class="flex flex-col gap-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Historique Immobilier</h1>
                <p class="text-slate-600 mt-1">Journal des activités et opérations</p>
            </div>
            <button
                @click="exportHistory"
                class="flex items-center gap-2 px-4 py-3 bg-gradient-to-r from-slate-600 to-slate-700 text-white rounded-xl font-medium shadow-lg shadow-slate-500/30 hover:shadow-xl hover:shadow-slate-500/40 transition-all"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Exporter
            </button>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-slate-500/20 animate-fade-in" style="animation-delay: 0ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Total Événements</p>
                        <p class="text-3xl font-bold text-slate-900 mt-1 animate-number">{{ totalEvenements }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-slate-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-emerald-500/20 animate-fade-in" style="animation-delay: 100ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Ce Mois</p>
                        <p class="text-3xl font-bold text-emerald-600 mt-1 animate-number">{{ ceMois }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-blue-500/20 animate-fade-in" style="animation-delay: 200ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Créations</p>
                        <p class="text-3xl font-bold text-blue-600 mt-1 animate-number">{{ creations }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-amber-500/20 animate-fade-in" style="animation-delay: 300ms">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Modifications</p>
                        <p class="text-3xl font-bold text-amber-600 mt-1 animate-number">{{ modifications }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center transform transition-transform duration-300 hover:rotate-12">
                        <svg class="w-6 h-6 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-4 border border-slate-100">
            <div class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Rechercher..."
                            class="w-full pl-12 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-500 focus:border-transparent"
                        >
                    </div>
                </div>
                <div class="min-w-[180px]">
                    <select v-model="filterType" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-500 focus:border-transparent">
                        <option value="">Tous les types</option>
                        <option value="Création">Création</option>
                        <option value="Modification">Modification</option>
                        <option value="Suppression">Suppression</option>
                        <option value="Renouvellement">Renouvellement</option>
                        <option value="État des lieux">État des lieux</option>
                    </select>
                </div>
                <div class="min-w-[180px]">
                    <select v-model="filterCategorie" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-500 focus:border-transparent">
                        <option value="">Toutes catégories</option>
                        <option value="Contrat">Contrat</option>
                        <option value="Locataire">Locataire</option>
                        <option value="Logement">Logement</option>
                        <option value="Bâtiment">Bâtiment</option>
                        <option value="Engagement">Engagement</option>
                    </select>
                </div>
                <div class="min-w-[180px]">
                    <input v-model="filterDate" type="date" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-500 focus:border-transparent">
                </div>
            </div>
        </div>

        <!-- Timeline Section -->
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100">
            <div class="space-y-6">
                <div v-for="evenement in filteredHistorique" :key="evenement.id" class="flex gap-4">
                    <div class="flex flex-col items-center">
                        <div :class="{
                            'w-10 h-10 rounded-full flex items-center justify-center': true,
                            'bg-emerald-100': evenement.type === 'Création',
                            'bg-amber-100': evenement.type === 'Modification',
                            'bg-red-100': evenement.type === 'Suppression',
                            'bg-blue-100': evenement.type === 'Renouvellement',
                            'bg-purple-100': evenement.type === 'État des lieux'
                        }">
                            <svg v-if="evenement.type === 'Création'" class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            <svg v-else-if="evenement.type === 'Modification'" class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            <svg v-else-if="evenement.type === 'Suppression'" class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <svg v-else-if="evenement.type === 'Renouvellement'" class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                            </svg>
                            <svg v-else class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div v-if="evenement.id !== historique.length" class="w-0.5 flex-1 bg-slate-200 mt-2"></div>
                    </div>
                    <div class="flex-1 pb-6">
                        <div class="bg-slate-50 rounded-xl p-4 hover:bg-slate-100 transition-colors">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="font-semibold text-slate-900">{{ evenement.titre }}</h3>
                                    <p class="text-sm text-slate-600 mt-1">{{ evenement.description }}</p>
                                    <div class="flex items-center gap-3 mt-2">
                                        <span :class="{
                                            'px-2 py-1 rounded-full text-xs font-medium': true,
                                            'bg-emerald-100 text-emerald-700': evenement.type === 'Création',
                                            'bg-amber-100 text-amber-700': evenement.type === 'Modification',
                                            'bg-red-100 text-red-700': evenement.type === 'Suppression',
                                            'bg-blue-100 text-blue-700': evenement.type === 'Renouvellement',
                                            'bg-purple-100 text-purple-700': evenement.type === 'État des lieux'
                                        }">
                                            {{ evenement.type }}
                                        </span>
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-slate-200 text-slate-700">
                                            {{ evenement.categorie }}
                                        </span>
                                        <span class="text-xs text-slate-500">{{ evenement.date }}</span>
                                        <span class="text-xs text-slate-500">{{ evenement.heure }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-medium text-slate-700">{{ evenement.utilisateur }}</span>
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-slate-400 to-slate-600 flex items-center justify-center text-white text-xs font-semibold">
                                        {{ evenement.utilisateur.charAt(0) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const historique = ref([
    { id: 1, titre: 'Nouveau contrat créé', description: 'Contrat CT-2024-001 créé pour Jean Dupont', type: 'Création', categorie: 'Contrat', date: '2024-01-15', heure: '09:30', utilisateur: 'Admin' },
    { id: 2, titre: 'Modification du logement', description: 'Appartement 101 mis à jour', type: 'Modification', categorie: 'Logement', date: '2024-01-15', heure: '10:15', utilisateur: 'Admin' },
    { id: 3, titre: 'Nouveau locataire ajouté', description: 'Marie Martin enregistrée comme locataire', type: 'Création', categorie: 'Locataire', date: '2024-01-16', heure: '14:20', utilisateur: 'Admin' },
    { id: 4, titre: 'État des lieux entrée', description: 'EDL-2024-001 effectué pour Appartement 102', type: 'État des lieux', categorie: 'Logement', date: '2024-01-17', heure: '11:00', utilisateur: 'Admin' },
    { id: 5, titre: 'Renouvellement de contrat', description: 'Contrat CT-2023-015 renouvelé pour Pierre Bernard', type: 'Renouvellement', categorie: 'Contrat', date: '2024-01-18', heure: '16:45', utilisateur: 'Admin' },
    { id: 6, titre: 'Suppression de bâtiment', description: 'Bâtiment BAT-003 supprimé du système', type: 'Suppression', categorie: 'Bâtiment', date: '2024-01-19', heure: '08:30', utilisateur: 'Admin' },
    { id: 7, titre: 'Nouvel engagement signé', description: 'Convention ENG-2024-004 signée avec Sophie Richard', type: 'Création', categorie: 'Engagement', date: '2024-01-20', heure: '13:00', utilisateur: 'Admin' },
    { id: 8, titre: 'Modification du loyer', description: 'Loyer du contrat CT-2024-002 modifié', type: 'Modification', categorie: 'Contrat', date: '2024-01-21', heure: '15:30', utilisateur: 'Admin' },
    { id: 9, titre: 'État des lieux sortie', description: 'EDL-2024-003 effectué pour Appartement 101', type: 'État des lieux', categorie: 'Logement', date: '2024-01-22', heure: '10:00', utilisateur: 'Admin' },
    { id: 10, titre: 'Nouveau bâtiment ajouté', description: 'Bâtiment BAT-004 créé dans le système', type: 'Création', categorie: 'Bâtiment', date: '2024-01-23', heure: '09:15', utilisateur: 'Admin' },
]);

const searchQuery = ref('');
const filterType = ref('');
const filterCategorie = ref('');
const filterDate = ref('');

const filteredHistorique = computed(() => {
    let filtered = historique.value;
    
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(e => 
            e.titre.toLowerCase().includes(query) ||
            e.description.toLowerCase().includes(query) ||
            e.utilisateur.toLowerCase().includes(query)
        );
    }
    
    if (filterType.value) {
        filtered = filtered.filter(e => e.type === filterType.value);
    }
    
    if (filterCategorie.value) {
        filtered = filtered.filter(e => e.categorie === filterCategorie.value);
    }
    
    if (filterDate.value) {
        filtered = filtered.filter(e => e.date === filterDate.value);
    }
    
    return filtered;
});

const totalEvenements = computed(() => historique.value.length);
const ceMois = computed(() => {
    const now = new Date();
    const currentMonth = now.getMonth();
    const currentYear = now.getFullYear();
    return historique.value.filter(e => {
        const eventDate = new Date(e.date);
        return eventDate.getMonth() === currentMonth && eventDate.getFullYear() === currentYear;
    }).length;
});
const creations = computed(() => historique.value.filter(e => e.type === 'Création').length);
const modifications = computed(() => historique.value.filter(e => e.type === 'Modification').length);

const exportHistory = () => {
    alert('Export de l\'historique en cours...');
};
</script>

<style scoped>
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeIn 0.5s ease-out forwards;
    opacity: 0;
}

.animate-number {
    animation: countUp 0.5s ease-out;
}

@keyframes countUp {
    from {
        opacity: 0;
        transform: scale(0.5);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
</style>
