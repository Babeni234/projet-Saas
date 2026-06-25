<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    workflow: Object,
    instances: [Array, Object],
    step_types: Object,
    step_type_icons: Object,
    step_type_colors: Object,
    triggers: Object,
});

function trigLabel(key) { return props.triggers[key] || key; }

function run() {
    router.post(route('landlord.pro.workflows.run', props.workflow.id), {}, { preserveScroll: true });
}

function destroy() {
    if (confirm(`Supprimer le workflow "${props.workflow.name}" ?`)) {
        router.delete(route('landlord.pro.workflows.destroy', props.workflow.id));
    }
}

function toggle() {
    router.put(route('landlord.pro.workflows.update', props.workflow.id), {
        name: props.workflow.name,
        description: props.workflow.description,
        trigger_type: props.workflow.trigger_type,
        is_active: !props.workflow.is_active,
    }, { preserveScroll: true });
}

function truncate(str, n = 60) {
    return str?.length > n ? str.slice(0, n) + '...' : str;
}

const hasInstances = Array.isArray(props.instances) ? props.instances.length : props.instances?.data?.length;
const instancesList = Array.isArray(props.instances) ? props.instances : (props.instances?.data || []);

const colors = { action: 'indigo', condition: 'amber', delay: 'cyan', approval: 'emerald', notification: 'blue', webhook: 'violet' };
const colorClasses = {
    indigo: 'border-indigo-200 bg-indigo-50',
    amber: 'border-amber-200 bg-amber-50',
    cyan: 'border-cyan-200 bg-cyan-50',
    emerald: 'border-emerald-200 bg-emerald-50',
    blue: 'border-blue-200 bg-blue-50',
    violet: 'border-violet-200 bg-violet-50',
};
</script>

<template>
    <Head :title="workflow.name" />
    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <Link :href="route('landlord.pro.workflows.index')" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </Link>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-lg font-semibold text-gray-900">{{ workflow.name }}</h2>
                            <span class="rounded-full px-2.5 py-0.5 text-xs font-medium"
                                :class="workflow.is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500'">
                                {{ workflow.is_active ? 'Actif' : 'Inactif' }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500">{{ trigLabel(workflow.trigger_type) }} — {{ workflow.steps?.length || 0 }} étape(s)</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button @click="run" class="rounded-lg bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-700">Exécuter</button>
                    <button @click="toggle" class="rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                        {{ workflow.is_active ? 'Désactiver' : 'Activer' }}
                    </button>
                    <Link :href="route('landlord.pro.workflows.edit', workflow.id)" class="rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Modifier</Link>
                </div>
            </div>
        </template>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-6">
                <!-- Pipeline visual -->
                <div class="rounded-xl border border-gray-200 bg-white p-5">
                    <h3 class="mb-4 text-sm font-semibold text-gray-900">Pipeline</h3>
                    <div v-if="workflow.steps?.length" class="space-y-0">
                        <div v-for="(step, idx) in workflow.steps" :key="step.id" class="relative">
                            <div v-if="idx > 0" class="flex justify-center py-1">
                                <svg class="h-5 w-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" /></svg>
                            </div>
                            <div class="rounded-lg border-2 p-4"
                                :class="colorClasses[colors[step.step_type]] || 'border-gray-200'">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-white text-sm shadow-sm"
                                        :class="'text-' + (colors[step.step_type] || 'gray') + '-600'">
                                        {{ step_type_icons?.[step.step_type] || '•' }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            <span class="font-medium text-gray-900">{{ step.name }}</span>
                                            <span class="text-[10px] text-gray-500 bg-white rounded-full px-1.5 py-0.5 border border-gray-200">{{ step_types?.[step.step_type] || step.step_type }}</span>
                                        </div>
                                        <p v-if="step.description" class="text-xs text-gray-500">{{ step.description }}</p>
                                    </div>
                                    <span class="text-[10px] text-gray-400">#{{ step.order }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p v-else class="text-sm text-gray-400">Aucune étape définie.</p>
                </div>

                <!-- Description -->
                <div v-if="workflow.description" class="rounded-xl border border-gray-200 bg-white p-5">
                    <h3 class="mb-2 text-sm font-semibold text-gray-900">Description</h3>
                    <p class="text-sm text-gray-600">{{ workflow.description }}</p>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Info -->
                <div class="rounded-xl border border-gray-200 bg-white p-5">
                    <h3 class="mb-3 text-sm font-semibold text-gray-900">Informations</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between"><span class="text-gray-500">Déclencheur</span><span class="font-medium text-gray-900">{{ trigLabel(workflow.trigger_type) }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Étapes</span><span class="font-medium text-gray-900">{{ workflow.steps?.length || 0 }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Statut</span><span class="font-medium" :class="workflow.is_active ? 'text-emerald-600' : 'text-gray-500'">{{ workflow.is_active ? 'Actif' : 'Inactif' }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Créé le</span><span class="font-medium text-gray-900">{{ new Date(workflow.created_at).toLocaleDateString('fr-FR') }}</span></div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="rounded-xl border border-gray-200 bg-white p-5">
                    <h3 class="mb-3 text-sm font-semibold text-gray-900">Actions</h3>
                    <div class="space-y-2">
                        <button @click="run" class="w-full rounded-lg bg-indigo-600 py-2 text-sm font-medium text-white hover:bg-indigo-700">Exécuter maintenant</button>
                        <Link :href="route('landlord.pro.workflows.edit', workflow.id)" class="block w-full rounded-lg border border-gray-300 py-2 text-center text-sm text-gray-700 hover:bg-gray-50">Modifier</Link>
                        <button @click="destroy" class="w-full rounded-lg border border-red-300 py-2 text-sm text-red-600 hover:bg-red-50">Supprimer</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instances -->
        <div class="mt-6 rounded-xl border border-gray-200 bg-white p-5">
            <h3 class="mb-4 text-sm font-semibold text-gray-900">Exécutions ({{ hasInstances || 0 }})</h3>
            <div v-if="hasInstances" class="space-y-2">
                <div v-for="inst in instancesList" :key="inst.id"
                    class="flex items-center justify-between rounded-lg px-4 py-3 text-sm"
                    :class="inst.status === 'completed' ? 'bg-emerald-50' : inst.status === 'failed' ? 'bg-red-50' : inst.status === 'running' ? 'bg-blue-50' : 'bg-gray-50'">
                    <div class="flex items-center gap-3">
                        <span>{{ inst.status === 'completed' ? '✅' : inst.status === 'failed' ? '❌' : inst.status === 'running' ? '🔄' : '⏹️' }}</span>
                        <div>
                            <p class="font-medium text-gray-900 capitalize">{{ inst.status }}</p>
                            <p v-if="inst.current_step" class="text-xs text-gray-500">Étape : {{ inst.current_step.name }}</p>
                        </div>
                    </div>
                    <span class="text-xs text-gray-400">{{ new Date(inst.created_at).toLocaleString('fr-FR') }}</span>
                </div>
            </div>
            <p v-else class="text-sm text-gray-400">Aucune exécution pour le moment.</p>
        </div>
    </AppLayout>
</template>
