<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

const props = defineProps({
    properties: { type: Array, default: () => [] },
    tenants: { type: Array, default: () => [] },
});

const form = useForm({ property_id: '', contract_id: '', tenant_id: '', title: '', description: '', category: 'plomberie', urgency: 'basse' });

function store() { form.post(route('landlord.incidents.store')); }
</script>

<template>
    <Head title="Nouvel incident" />
    <PropertyLayout role="bailleur" title="Nouvel incident" subtitle="Signaler un incident">
        <form @submit.prevent="store" class="max-w-2xl space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Bien</label>
                <select v-model="form.property_id" required class="imo-input w-full">
                    <option value="">Sélectionner un bien</option>
                    <option v-for="p in properties" :key="p.id" :value="p.id">{{ p.title }}</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Titre</label>
                <input v-model="form.title" required class="imo-input w-full" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea v-model="form.description" required rows="4" class="imo-input w-full"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Catégorie</label>
                    <select v-model="form.category" class="imo-input w-full">
                        <option value="plomberie">Plomberie</option>
                        <option value="electricite">Électricité</option>
                        <option value="chauffage">Chauffage</option>
                        <option value="toiture">Toiture</option>
                        <option value="fenetres">Fenêtres</option>
                        <option value="murs">Murs</option>
                        <option value="sols">Sols</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Urgence</label>
                    <select v-model="form.urgency" class="imo-input w-full">
                        <option value="basse">Basse</option>
                        <option value="moyenne">Moyenne</option>
                        <option value="haute">Haute</option>
                        <option value="urgente">Urgente</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="imo-btn-primary" :disabled="form.processing">Créer l'incident</button>
        </form>
    </PropertyLayout>
</template>
