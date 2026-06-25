<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    rule: Object,
    triggers: Object,
    actions: Object,
});

function toggle() {
    router.post(route('landlord.pro.automation.toggle', props.rule.id), {}, { preserveScroll: true });
}

function runNow() {
    router.post(route('landlord.pro.automation.run', props.rule.id), {}, { preserveScroll: true });
}

function destroy() {
    if (confirm(`Supprimer la règle "${props.rule.name}" ?`)) {
        router.delete(route('landlord.pro.automation.destroy', props.rule.id));
    }
}
</script>

<template>
    <Head :title="rule.name" />
    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <Link :href="route('landlord.pro.automation.index')" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </Link>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-lg font-semibold text-gray-900">{{ rule.name }}</h2>
                            <span class="rounded-full px-2.5 py-0.5 text-xs font-medium"
                                :class="rule.is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500'">
                                {{ rule.is_active ? 'Actif' : 'Inactif' }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500">{{ rule.description || 'Règle d\'automatisation' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button @click="toggle" class="rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                        {{ rule.is_active ? 'Désactiver' : 'Activer' }}
                    </button>
                    <button @click="runNow" class="rounded-lg bg-indigo-600 px-3 py-2 text-sm text-white hover:bg-indigo-700">Exécuter maintenant</button>
                </div>
            </div>
        </template>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-xl border border-gray-200 bg-white p-6">
                    <h3 class="text-sm font-semibold text-gray-900">Configuration</h3>
                    <div class="mt-4 space-y-4">
                        <div class="flex items-center gap-3 rounded-lg bg-indigo-50 p-4">
                            <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            <div>
                                <p class="text-sm font-medium text-indigo-900">Déclencheur</p>
                                <p class="text-xs text-indigo-700">{{ triggers[rule.trigger_type] || rule.trigger_type }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 rounded-lg bg-emerald-50 p-4">
                            <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <div>
                                <p class="text-sm font-medium text-emerald-900">Action</p>
                                <p class="text-xs text-emerald-700">{{ actions[rule.action_type] || rule.action_type }}</p>
                            </div>
                        </div>
                        <div v-if="rule.last_run_at" class="flex items-center gap-3 rounded-lg bg-gray-50 p-4">
                            <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Dernière exécution</p>
                                <p class="text-xs text-gray-500">{{ new Date(rule.last_run_at).toLocaleString('fr-FR') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6">
                    <h3 class="mb-4 text-sm font-semibold text-gray-900">Historique ({{ rule.logs.length }})</h3>
                    <div v-if="rule.logs.length" class="space-y-2">
                        <div v-for="log in rule.logs" :key="log.id"
                            class="flex items-center justify-between rounded-lg px-4 py-2.5 text-sm"
                            :class="log.status === 'success' ? 'bg-emerald-50' : log.status === 'failed' ? 'bg-red-50' : 'bg-gray-50'">
                            <div class="flex items-center gap-2">
                                <span>{{ log.status === 'success' ? '✅' : log.status === 'failed' ? '❌' : '⏭️' }}</span>
                                <span>{{ log.message }}</span>
                            </div>
                            <span class="text-xs text-gray-400">{{ new Date(log.created_at).toLocaleString('fr-FR') }}</span>
                        </div>
                    </div>
                    <p v-else class="text-sm text-gray-400">Aucune exécution pour le moment.</p>
                </div>
            </div>

            <div>
                <div class="rounded-xl border border-gray-200 bg-white p-5">
                    <h3 class="mb-3 text-sm font-semibold text-gray-900">Actions</h3>
                    <div class="space-y-2">
                        <button @click="toggle" class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            {{ rule.is_active ? 'Désactiver' : 'Activer' }}
                        </button>
                        <button @click="runNow" class="w-full rounded-lg bg-indigo-600 px-4 py-2 text-sm text-white hover:bg-indigo-700">
                            Exécuter maintenant
                        </button>
                        <button @click="destroy" class="w-full rounded-lg border border-red-300 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                            Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
