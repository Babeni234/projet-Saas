<script setup>
import { ref, reactive, computed } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    workflow: Object,
    step_types: Object,
    step_type_icons: Object,
    step_type_colors: Object,
    triggers: Object,
    templates: Array,
});

const isEditing = !!props.workflow;

const form = useForm({
    name: props.workflow?.name || '',
    description: props.workflow?.description || '',
    trigger_type: props.workflow?.trigger_type || 'manual',
    is_active: props.workflow?.is_active ?? true,
    steps: props.workflow?.steps?.map((s, i) => ({
        id: s.id,
        name: s.name,
        description: s.description || '',
        step_type: s.step_type,
        config: s.config || {},
        order: s.order || (i + 1),
        _editing: false,
    })) || [],
});

const editingStepIndex = ref(-1);
const showAddStep = ref(false);
const newStep = reactive({ name: '', description: '', step_type: 'action', config: {} });

const stepTypesList = computed(() => {
    return Object.entries(props.step_types).map(([key, label]) => ({
        key, label,
        icon: props.step_type_icons?.[key] || '•',
        color: props.step_type_colors?.[key] || 'gray',
    }));
});

function addStep() {
    form.steps.push({
        id: null,
        name: newStep.name || `Étape ${form.steps.length + 1}`,
        description: newStep.description,
        step_type: newStep.step_type,
        config: newStep.config || {},
        order: form.steps.length + 1,
        _editing: false,
    });
    editingStepIndex.value = form.steps.length - 1;
    showAddStep.value = false;
    newStep.name = ''; newStep.description = ''; newStep.step_type = 'action';
}

function removeStep(idx) {
    form.steps.splice(idx, 1);
    form.steps.forEach((s, i) => s.order = i + 1);
    if (editingStepIndex.value === idx) editingStepIndex.value = -1;
}

function moveStep(idx, dir) {
    const target = idx + dir;
    if (target < 0 || target >= form.steps.length) return;
    [form.steps[idx], form.steps[target]] = [form.steps[target], form.steps[idx]];
    form.steps.forEach((s, i) => s.order = i + 1);
}

function submit() {
    if (isEditing) {
        form.put(route('landlord.pro.workflows.update', props.workflow.id), {
            preserveScroll: true,
        });
    } else {
        form.post(route('landlord.pro.workflows.store'), {
            preserveScroll: true,
        });
    }
}

const typeConfigFields = {
    action: [
        { key: 'action', label: 'Action', type: 'select', options: { log: 'Journaliser', mark_receipt_overdue: 'Marquer impayé', send_reminder: 'Envoyer rappel', update_status: 'Màj statut', create_task: 'Créer une tâche' } },
        { key: 'status', label: 'Statut (update_status)', type: 'text', if: { action: 'update_status' } },
        { key: 'task_name', label: 'Nom de la tâche', type: 'text', if: { action: 'create_task' } },
    ],
    condition: [
        { key: 'field', label: 'Champ à évaluer', type: 'text', placeholder: 'ex: payment_status' },
        { key: 'operator', label: 'Opérateur', type: 'select', options: { equals: 'Égal à', not_equals: 'Différent de', greater_than: 'Supérieur à', less_than: 'Inférieur à', contains: 'Contient', is_empty: 'Est vide', is_not_empty: 'N\'est pas vide' } },
        { key: 'value', label: 'Valeur', type: 'text', placeholder: 'overdue' },
    ],
    delay: [
        { key: 'minutes', label: 'Minutes', type: 'number', placeholder: '0' },
        { key: 'hours', label: 'Heures', type: 'number', placeholder: '0' },
        { key: 'days', label: 'Jours', type: 'number', placeholder: '0' },
    ],
    approval: [
        { key: 'assigned_to', label: 'Assigné à', type: 'select', options: { owner: 'Propriétaire', manager: 'Gestionnaire', agent: 'Agent' } },
    ],
    notification: [
        { key: 'channel', label: 'Canal', type: 'select', options: { email: 'Email', sms: 'SMS', both: 'Email + SMS' } },
        { key: 'to', label: 'Destinataire', type: 'select', options: { tenant: 'Locataire', owner: 'Propriétaire', agent: 'Agent', all: 'Tous' } },
        { key: 'template', label: 'Template', type: 'text', placeholder: 'default' },
    ],
    webhook: [
        { key: 'url', label: 'URL du webhook', type: 'url', placeholder: 'https://...' },
    ],
};

function getConfigFields(type) {
    return typeConfigFields[type] || [];
}

function showField(field, stepIdx) {
    if (!field.if) return true;
    const s = form.steps[stepIdx]?.config || {};
    return Object.entries(field.if).every(([k, v]) => s[k] === v);
}
</script>

<template>
    <Head :title="isEditing ? 'Modifier le workflow' : 'Nouveau workflow'" />
    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <Link :href="route('landlord.pro.workflows.index')" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </Link>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">{{ isEditing ? 'Modifier le workflow' : 'Nouveau workflow' }}</h2>
                        <p class="text-sm text-gray-500">Construisez visuellement votre pipeline d'automatisation</p>
                    </div>
                </div>
            </div>
        </template>

        <form @submit.prevent="submit" class="space-y-6">
            <!-- Info -->
            <div class="rounded-xl border border-gray-200 bg-white p-5">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Nom du workflow</label>
                        <input v-model="form.name" required
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                            placeholder="Ex: Relance impayés automatique" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Déclencheur</label>
                        <select v-model="form.trigger_type"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            <option v-for="(label, key) in triggers" :key="key" :value="key">{{ label }}</option>
                        </select>
                    </div>
                </div>
                <div class="mt-3">
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea v-model="form.description" rows="2"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                        placeholder="Description du workflow..." />
                </div>
                <div class="mt-3 flex items-center gap-2">
                    <input type="checkbox" id="is_active" v-model="form.is_active" class="h-4 w-4 rounded border-gray-300 text-indigo-600" />
                    <label for="is_active" class="text-sm text-gray-700">Activer immédiatement</label>
                </div>
            </div>

            <!-- Pipeline visual -->
            <div class="rounded-xl border border-gray-200 bg-white p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-900">Pipeline</h3>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-400">{{ form.steps.length }} étape(s)</span>
                        <button type="button" @click="showAddStep = !showAddStep"
                            class="rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-medium text-indigo-700 hover:bg-indigo-100">
                            + Ajouter une étape
                        </button>
                    </div>
                </div>

                <!-- Add step form -->
                <div v-if="showAddStep" class="mb-4 rounded-lg border border-indigo-200 bg-indigo-50 p-4">
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700">Type d'étape</label>
                            <select v-model="newStep.step_type" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                                <option v-for="st in stepTypesList" :key="st.key" :value="st.key">{{ st.icon }} {{ st.label }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700">Nom</label>
                            <input v-model="newStep.name" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" placeholder="Nom de l'étape" />
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="button" @click="addStep" class="rounded-lg bg-indigo-600 px-4 py-2 text-xs font-medium text-white hover:bg-indigo-700">Ajouter</button>
                            <button type="button" @click="showAddStep = false" class="rounded-lg px-4 py-2 text-xs text-gray-500 hover:bg-gray-100">Annuler</button>
                        </div>
                    </div>
                </div>

                <!-- Visual pipeline -->
                <div v-if="form.steps.length" class="space-y-0">
                    <div v-for="(step, idx) in form.steps" :key="idx" class="relative">
                        <!-- Connector arrow -->
                        <div v-if="idx > 0" class="flex justify-center py-1">
                            <svg class="h-5 w-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" /></svg>
                        </div>

                        <!-- Step card -->
                        <div class="group rounded-lg border-2 p-4 transition cursor-pointer"
                            :class="editingStepIndex === idx ? 'border-indigo-400 bg-indigo-50' : 'border-gray-200 hover:border-gray-300'"
                            @click="editingStepIndex = editingStepIndex === idx ? -1 : idx">

                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full text-sm"
                                        :class="step.step_type === 'condition' ? 'bg-amber-100 text-amber-700' : step.step_type === 'delay' ? 'bg-cyan-100 text-cyan-700' : step.step_type === 'approval' ? 'bg-emerald-100 text-emerald-700' : step.step_type === 'notification' ? 'bg-blue-100 text-blue-700' : 'bg-indigo-100 text-indigo-700'">
                                        {{ step_type_icons?.[step.step_type] || '•' }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-medium text-gray-900">{{ step.name }}</span>
                                            <span class="text-[10px] text-gray-400 bg-gray-100 rounded-full px-1.5 py-0.5">{{ step_types?.[step.step_type] || step.step_type }}</span>
                                        </div>
                                        <p v-if="step.description" class="text-xs text-gray-500">{{ step.description }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition">
                                    <button type="button" @click.stop="moveStep(idx, -1)" :disabled="idx === 0" class="p-1 text-gray-400 hover:text-gray-600 disabled:opacity-30">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                    </button>
                                    <button type="button" @click.stop="moveStep(idx, 1)" :disabled="idx === form.steps.length - 1" class="p-1 text-gray-400 hover:text-gray-600 disabled:opacity-30">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                    <button type="button" @click.stop="removeStep(idx)" class="p-1 text-red-400 hover:text-red-600">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Config editor (inline) -->
                            <div v-if="editingStepIndex === idx" class="mt-3 border-t border-gray-200 pt-3">
                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600">Nom</label>
                                        <input v-model="step.name" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-1.5 text-sm" />
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600">Description</label>
                                        <input v-model="step.description" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-1.5 text-sm" />
                                    </div>
                                </div>
                                <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2">
                                    <div v-for="field in getConfigFields(step.step_type)" :key="field.key">
                                        <div v-if="showField(field, idx)">
                                            <label class="block text-xs font-medium text-gray-600">{{ field.label }}</label>
                                            <select v-if="field.type === 'select'" v-model="step.config[field.key]"
                                                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-1.5 text-sm">
                                                <option v-for="(optLabel, optKey) in field.options" :key="optKey" :value="optKey">{{ optLabel }}</option>
                                            </select>
                                            <input v-else :type="field.type" v-model="step.config[field.key]" :placeholder="field.placeholder"
                                                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-1.5 text-sm" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else class="py-12 text-center">
                    <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                    <p class="mt-2 text-sm text-gray-400">Ajoutez des étapes pour construire votre workflow</p>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex items-center gap-3">
                <button type="submit" :disabled="form.processing || form.steps.length === 0"
                    class="rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50">
                    {{ isEditing ? 'Enregistrer' : 'Créer le workflow' }}
                </button>
                <Link :href="route('landlord.pro.workflows.index')" class="text-sm text-gray-500 hover:text-gray-700">Annuler</Link>
            </div>
        </form>
    </AppLayout>
</template>
