<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

defineProps({
    revisions: { type: Array, default: () => [] },
    contracts: { type: Array, default: () => [] },
});

const form = useForm({ contract_id: '', previous_rent: '', new_rent: '', index_name: 'IRL', index_value_old: '', index_value_new: '', effective_date: '' });
const applyForm = useForm({});
const showForm = ref(false);

function store() { form.post(route('landlord.rent-revisions.store'), { preserveScroll: true }); }
function apply(id) { applyForm.post(route('landlord.rent-revisions.apply', id), { preserveScroll: true }); }
</script>

<template>
    <Head title="Révisions de loyers" />
    <PropertyLayout role="bailleur" title="Révision des loyers (IRL)" subtitle="Calculez et appliquez les révisions annuelles">
        <template #actions>
            <button @click="showForm = !showForm" class="imo-btn-primary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Nouvelle révision
            </button>
        </template>
        <form v-if="showForm" @submit.prevent="store" class="bg-white rounded-lg shadow p-6 mb-6 max-w-2xl space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Contrat</label>
                <select v-model="form.contract_id" required class="imo-input w-full">
                    <option value="">Sélectionner un contrat</option>
                    <option v-for="c in contracts" :key="c.id" :value="c.id">{{ c.property?.title }} - {{ c.tenant_name }}</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Loyer actuel</label>
                    <input v-model="form.previous_rent" type="number" step="0.01" required class="imo-input w-full" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nouveau loyer</label>
                    <input v-model="form.new_rent" type="number" step="0.01" required class="imo-input w-full" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Indice</label>
                    <input v-model="form.index_name" class="imo-input w-full" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Ancien indice</label>
                    <input v-model="form.index_value_old" type="number" step="0.01" required class="imo-input w-full" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nouvel indice</label>
                    <input v-model="form.index_value_new" type="number" step="0.01" required class="imo-input w-full" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date d'effet</label>
                    <input v-model="form.effective_date" type="date" required class="imo-input w-full" />
                </div>
            </div>
            <button type="submit" class="imo-btn-primary">Créer la révision</button>
        </form>
        <div v-if="revisions.length === 0" class="text-center py-12 text-gray-500">Aucune révision.</div>
        <div v-for="r in revisions" :key="r.id" class="bg-white rounded-lg shadow p-4 mb-3">
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-semibold">{{ r.contract?.property?.title }} - {{ r.index_name }}</p>
                    <p class="text-sm text-gray-500">{{ r.previous_rent }}€ → {{ r.new_rent }}€ ({{ r.percentage_change }}%)</p>
                </div>
                <div class="flex items-center gap-2">
                    <span :class="['px-2 py-1 text-xs rounded-full',
                        r.status === 'projet' ? 'bg-yellow-100 text-yellow-800' :
                        r.status === 'appliquee' ? 'bg-green-100 text-green-800' :
                        'bg-gray-100 text-gray-800']">
                        {{ r.status === 'appliquee' ? 'Appliquée' : 'Projet' }}
                    </span>
                    <button v-if="r.status === 'projet'" @click="apply(r.id)" class="text-sm text-indigo-600 hover:text-indigo-800">Appliquer</button>
                </div>
            </div>
        </div>
    </PropertyLayout>
</template>
