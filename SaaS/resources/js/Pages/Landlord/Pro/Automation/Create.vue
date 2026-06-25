<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    triggers: Object,
    actions: Object,
});

const form = useForm({
    name: '',
    description: '',
    trigger_type: 'receipt_overdue',
    trigger_config: {},
    action_type: 'notify_tenant',
    action_config: {},
    is_active: true,
});

const triggerDescriptions = {
    receipt_overdue: 'Se déclenche quand une quittance est en retard de paiement',
    contract_ending: 'Se déclenche quand un contrat approche de sa date de fin',
    visit_reminder: 'Se déclenche 24h avant une visite programmée',
    incident_reported: 'Se déclenche quand un incident urgent est signalé',
    rent_due: 'Se déclenche 3 jours avant l\'échéance du loyer',
};

function submit() {
    form.post(route('landlord.pro.automation.store'));
}
</script>

<template>
    <Head title="Nouvelle règle d'automatisation" />
    <AppLayout>
        <template #header>
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Nouvelle règle d'automatisation</h2>
                <p class="text-sm text-gray-500">Créez une règle "Quand X arrive, faire Y"</p>
            </div>
        </template>

        <form @submit.prevent="submit" class="max-w-2xl space-y-6">
            <div class="rounded-xl border border-gray-200 bg-white p-6 space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nom de la règle</label>
                    <input v-model="form.name" type="text" required
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                        placeholder="Ex: Relance automatique des impayés" />
                    <p v-if="form.errors.name" class="mt-1 text-xs text-red-600">{{ form.errors.name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Description (optionnelle)</label>
                    <textarea v-model="form.description" rows="2"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                        placeholder="Quand une quittance est en retard, envoyer une notification..." />
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-6">
                <h3 class="mb-4 text-sm font-semibold text-gray-900">Déclencheur</h3>
                <div class="space-y-2">
                    <label v-for="(label, key) in triggers" :key="key"
                        class="flex cursor-pointer items-start gap-3 rounded-lg border p-3 transition"
                        :class="form.trigger_type === key ? 'border-indigo-300 bg-indigo-50' : 'border-gray-200 hover:bg-gray-50'">
                        <input type="radio" :value="key" v-model="form.trigger_type" class="mt-0.5 h-4 w-4 text-indigo-600" />
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ label }}</p>
                            <p class="text-xs text-gray-500">{{ triggerDescriptions[key] }}</p>
                        </div>
                    </label>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-6">
                <h3 class="mb-4 text-sm font-semibold text-gray-900">Action à exécuter</h3>
                <div class="space-y-2">
                    <label v-for="(label, key) in actions" :key="key"
                        class="flex cursor-pointer items-start gap-3 rounded-lg border p-3 transition"
                        :class="form.action_type === key ? 'border-indigo-300 bg-indigo-50' : 'border-gray-200 hover:bg-gray-50'">
                        <input type="radio" :value="key" v-model="form.action_type" class="mt-0.5 h-4 w-4 text-indigo-600" />
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ label }}</p>
                        </div>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" v-model="form.is_active" class="h-4 w-4 rounded border-gray-300 text-indigo-600" />
                    <span class="text-sm text-gray-700">Activer immédiatement</span>
                </label>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" :disabled="form.processing"
                    class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50">
                    Créer la règle
                </button>
                <Link :href="route('landlord.pro.automation.index')" class="text-sm text-gray-500 hover:text-gray-700">Annuler</Link>
            </div>
        </form>
    </AppLayout>
</template>
