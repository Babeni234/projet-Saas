<script setup>
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    rules: Array,
    recent_logs: Array,
    triggers: Object,
    actions: Object,
});

function toggle(rule) {
    router.post(route('landlord.pro.automation.toggle', rule.id), {}, { preserveScroll: true });
}

function runNow(rule) {
    router.post(route('landlord.pro.automation.run', rule.id), {}, { preserveScroll: true });
}

function destroy(rule) {
    if (confirm(`Supprimer la règle "${rule.name}" ?`)) {
        router.delete(route('landlord.pro.automation.destroy', rule.id), { preserveScroll: true });
    }
}
</script>

<template>
    <Head title="Automatisations" />
    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Automatisations</h2>
                    <p class="text-sm text-gray-500">Règles et workflows pour automatiser votre gestion</p>
                </div>
                <Link :href="route('landlord.pro.automation.create')" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Nouvelle règle
                </Link>
            </div>
        </template>

        <div v-if="rules.length" class="space-y-4">
            <div v-for="rule in rules" :key="rule.id"
                class="rounded-xl border border-gray-200 bg-white p-5 transition hover:shadow-sm">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <h3 class="font-semibold text-gray-900">{{ rule.name }}</h3>
                            <span class="rounded-full px-2 py-0.5 text-[10px] font-medium"
                                :class="rule.is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500'">
                                {{ rule.is_active ? 'Actif' : 'Inactif' }}
                            </span>
                        </div>
                        <p v-if="rule.description" class="mt-1 text-xs text-gray-500">{{ rule.description }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="runNow(rule)" class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs text-gray-600 hover:bg-gray-50">Exécuter</button>
                        <Link :href="route('landlord.pro.automation.show', rule.id)" class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs text-gray-600 hover:bg-gray-50">Voir</Link>
                        <button @click="toggle(rule)" class="rounded-lg p-1.5 text-gray-400 hover:text-gray-600">
                            <svg v-if="rule.is_active" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                            <svg v-else class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </button>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-4 text-xs text-gray-500">
                    <span class="flex items-center gap-1">
                        <span class="font-medium text-gray-700">Déclencheur :</span> {{ triggers[rule.trigger_type] || rule.trigger_type }}
                    </span>
                    <span class="flex items-center gap-1">
                        <span class="font-medium text-gray-700">Action :</span> {{ actions[rule.action_type] || rule.action_type }}
                    </span>
                    <span>{{ rule.logs_count }} exécution(s)</span>
                    <span v-if="rule.last_run_at">Dernière : {{ new Date(rule.last_run_at).toLocaleDateString('fr-FR') }}</span>
                </div>
            </div>
        </div>

        <div v-else class="rounded-xl border-2 border-dashed border-gray-300 p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
            <h3 class="mt-4 text-lg font-semibold text-gray-900">Aucune règle d'automatisation</h3>
            <p class="mt-2 text-sm text-gray-500">Automatisez vos tâches récurrentes (relances impayés, rappels visites...).</p>
            <Link :href="route('landlord.pro.automation.create')" class="mt-6 inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                Créer une règle
            </Link>
        </div>

        <!-- Recent logs -->
        <div v-if="recent_logs?.length" class="mt-6 rounded-xl border border-gray-200 bg-white p-5">
            <h3 class="mb-3 text-sm font-semibold text-gray-900">Historique d'exécution</h3>
            <div class="space-y-1.5">
                <div v-for="log in recent_logs" :key="log.id"
                    class="flex items-center justify-between rounded-lg px-3 py-2 text-xs"
                    :class="log.status === 'success' ? 'bg-emerald-50' : log.status === 'failed' ? 'bg-red-50' : 'bg-gray-50'">
                    <div class="flex items-center gap-2">
                        <span>{{ log.status === 'success' ? '✅' : log.status === 'failed' ? '❌' : '⏭️' }}</span>
                        <span class="font-medium">{{ log.rule?.name }}</span>
                        <span>{{ log.message }}</span>
                    </div>
                    <span class="text-gray-400">{{ new Date(log.created_at).toLocaleString('fr-FR') }}</span>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
