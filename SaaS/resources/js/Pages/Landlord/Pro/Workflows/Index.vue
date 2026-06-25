<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    workflows: Array,
    templates: Array,
    triggers: Object,
});

function trigLabel(key) { return props.triggers[key] || key; }

const catColors = {
    general: 'bg-gray-100 text-gray-700',
    financial: 'bg-emerald-100 text-emerald-700',
    incident: 'bg-red-100 text-red-700',
    visit: 'bg-blue-100 text-blue-700',
    contract: 'bg-purple-100 text-purple-700',
};
</script>

<template>
    <Head title="Workflows" />
    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Workflows</h2>
                    <p class="text-sm text-gray-500">Automatisations multi-étapes avec conditions et approbations</p>
                </div>
                <Link :href="route('landlord.pro.workflows.create')" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Nouveau workflow
                </Link>
            </div>
        </template>

        <!-- Templates -->
        <div v-if="templates?.length" class="mb-6">
            <h3 class="mb-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Démarrer depuis un template</h3>
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5">
                <Link v-for="t in templates" :key="t.id"
                    :href="route('landlord.pro.workflows.from-template', { template_id: t.id })" method="post" as="button"
                    class="rounded-xl border border-gray-200 bg-white p-4 text-left transition hover:border-indigo-200 hover:shadow-sm">
                    <span class="text-lg">{{ t.category === 'financial' ? '💰' : t.category === 'incident' ? '🔧' : t.category === 'visit' ? '📅' : t.category === 'contract' ? '📄' : '⚙️' }}</span>
                    <p class="mt-1 text-sm font-medium text-gray-900">{{ t.name }}</p>
                    <p class="text-xs text-gray-500 line-clamp-2">{{ t.description }}</p>
                </Link>
            </div>
        </div>

        <!-- Workflow list -->
        <div v-if="workflows.length" class="space-y-3">
            <div v-for="w in workflows" :key="w.id"
                class="rounded-xl border border-gray-200 bg-white p-5 transition hover:shadow-sm">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <h3 class="font-semibold text-gray-900">{{ w.name }}</h3>
                            <span class="rounded-full px-2 py-0.5 text-[10px] font-medium"
                                :class="w.is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500'">
                                {{ w.is_active ? 'Actif' : 'Inactif' }}
                            </span>
                            <span class="rounded-full bg-indigo-50 px-2 py-0.5 text-[10px] font-medium text-indigo-600">{{ trigLabel(w.trigger_type) }}</span>
                        </div>
                        <p v-if="w.description" class="mt-1 text-xs text-gray-500">{{ w.description }}</p>
                    </div>
                    <div class="flex items-center gap-3 text-xs text-gray-500">
                        <span>{{ w.steps_count }} étape(s)</span>
                        <span>{{ w.instances_count }} exécution(s)</span>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-2">
                    <Link :href="route('landlord.pro.workflows.show', w.id)" class="rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-medium text-indigo-700 hover:bg-indigo-100">Voir</Link>
                    <Link :href="route('landlord.pro.workflows.edit', w.id)" class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs text-gray-600 hover:bg-gray-50">Modifier</Link>
                    <span class="text-xs text-gray-400 ml-auto">Créé le {{ new Date(w.created_at).toLocaleDateString('fr-FR') }}</span>
                </div>
            </div>
        </div>

        <div v-else class="rounded-xl border-2 border-dashed border-gray-300 p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3" /></svg>
            <h3 class="mt-4 text-lg font-semibold text-gray-900">Aucun workflow</h3>
            <p class="mt-2 text-sm text-gray-500">Créez votre premier workflow multi-étapes pour automatiser vos processus.</p>
            <div class="mt-6 flex items-center justify-center gap-3">
                <Link :href="route('landlord.pro.workflows.create')" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                    Créer un workflow
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
