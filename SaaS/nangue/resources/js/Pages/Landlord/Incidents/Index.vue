<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

const props = defineProps({
    incidents: { type: Array, default: () => [] },
    properties: { type: Array, default: () => [] },
    tenants: { type: Array, default: () => [] },
});

const search = ref('');
const filterStatus = ref('all');

const filteredIncidents = computed(() => {
    return props.incidents.filter(i => {
        const q = search.value.toLowerCase();
        const matchesSearch = !q || i.title.toLowerCase().includes(q);
        const matchesStatus = filterStatus.value === 'all' || i.status === filterStatus.value;
        return matchesSearch && matchesStatus;
    });
});
</script>

<template>
    <Head title="Incidents" />
    <PropertyLayout role="bailleur" title="Incidents" subtitle="Gérez les incidents et demandes d'intervention">
        <template #actions>
            <Link :href="route('landlord.incidents.create')" class="imo-btn-primary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Nouvel incident
            </Link>
        </template>
        <div class="space-y-4">
            <div class="flex gap-4">
                <input v-model="search" type="text" placeholder="Rechercher un incident..." class="imo-input flex-1" />
                <select v-model="filterStatus" class="imo-input w-48">
                    <option value="all">Tous</option>
                    <option value="ouvert">Ouvert</option>
                    <option value="en_cours">En cours</option>
                    <option value="resolu">Résolu</option>
                    <option value="ferme">Fermé</option>
                </select>
            </div>
            <div v-if="filteredIncidents.length === 0" class="text-center py-12 text-gray-500">Aucun incident trouvé.</div>
            <div v-for="incident in filteredIncidents" :key="incident.id" class="bg-white rounded-lg shadow p-4">
                <div class="flex items-start justify-between">
                    <div>
                        <Link :href="route('landlord.incidents.show', incident.id)" class="text-lg font-semibold text-gray-900 hover:text-indigo-600">
                            {{ incident.title }}
                        </Link>
                        <p class="text-sm text-gray-500 mt-1">{{ incident.property?.title }} - Urgence: {{ incident.urgency }}</p>
                    </div>
                    <span :class="['px-2 py-1 text-xs font-semibold rounded-full',
                        incident.status === 'ouvert' ? 'bg-red-100 text-red-800' :
                        incident.status === 'en_cours' ? 'bg-yellow-100 text-yellow-800' :
                        incident.status === 'resolu' ? 'bg-green-100 text-green-800' :
                        'bg-gray-100 text-gray-800']">
                        {{ incident.status === 'en_cours' ? 'En cours' : incident.status }}
                    </span>
                </div>
            </div>
        </div>
    </PropertyLayout>
</template>
