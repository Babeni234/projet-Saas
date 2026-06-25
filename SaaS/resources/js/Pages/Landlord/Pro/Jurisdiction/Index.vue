<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    current_country: String,
    current_region: String,
    countries: Object,
    legal: Object,
    is_pro: Boolean,
});

const form = useForm({
    country: props.current_country || 'FR',
    region: props.current_region || '',
});

const previewForm = useForm({
    country: props.current_country || 'FR',
    region: props.current_region || '',
});

import { ref, computed } from 'vue';

const search = ref('');
const filtered = computed(() => {
    const q = search.value.toLowerCase().trim();
    if (!q) return props.countries;
    return Object.fromEntries(
        Object.entries(props.countries).filter(([code, info]) =>
            info.name.toLowerCase().includes(q) || code.toLowerCase().includes(q)
        )
    );
});

function save() {
    form.put(route('landlord.pro.jurisdiction.save'));
}

function preview() {
    previewForm.country = form.country;
    previewForm.region = form.region;
    previewForm.post(route('landlord.pro.jurisdiction.preview'));
}

function onCountryChange() {
    form.region = '';
    previewForm.region = '';
}
</script>

<template>
    <Head title="Juridiction" />
    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Juridiction</h2>
                    <p class="text-sm text-gray-500">Configurez le pays et la législation applicable à votre activité</p>
                </div>
            </div>
        </template>

        <div class="max-w-4xl space-y-6">
            <!-- Sélecteur de pays -->
            <div class="rounded-xl border border-gray-200 bg-white p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Pays d'exercice</h3>
                <p class="text-xs text-gray-500 mb-5">
                    La sélection du pays adapte automatiquement les contrats, quittances, diagnostics et règles légales
                    à la législation locale.
                </p>

                <form @submit.prevent="save" class="space-y-5">
                    <div class="relative">
                        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input v-model="search" type="text" placeholder="Rechercher un pays (nom ou code)..."
                            class="w-full rounded-lg border border-gray-300 pl-10 pr-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-96 overflow-y-auto pr-1">
                        <div v-for="(info, code) in filtered" :key="code"
                            @click="form.country = code; form.region = ''"
                            class="flex cursor-pointer items-center gap-3 rounded-xl border-2 p-3.5 transition"
                            :class="form.country === code
                                ? 'border-indigo-400 bg-indigo-50 shadow-sm'
                                : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50'">
                            <span class="text-xl">{{ info.flag }}</span>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ info.name }}</p>
                                <p class="text-[10px] text-gray-400 uppercase tracking-wider">{{ code }}</p>
                            </div>
                            <div v-if="form.country === code" class="shrink-0">
                                <svg class="h-5 w-5 text-indigo-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                </svg>
                            </div>
                        </div>
                        <div v-if="Object.keys(filtered).length === 0" class="col-span-full py-8 text-center text-sm text-gray-400">
                            Aucun pays trouvé pour "{{ search }}"
                        </div>
                    </div>

                    <!-- Région -->
                    <div class="pt-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Région / Province / État</label>
                        <template v-if="countries[form.country]?.regions">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                                <label v-for="(rname, rcode) in countries[form.country].regions" :key="rcode"
                                    class="flex cursor-pointer items-center gap-2 rounded-lg border p-3 text-sm transition"
                                    :class="form.region === rcode
                                        ? 'border-indigo-300 bg-indigo-50 text-indigo-700'
                                        : 'border-gray-200 hover:bg-gray-50 text-gray-600'">
                                    <input type="radio" :value="rcode" v-model="form.region" class="sr-only" />
                                    <svg class="h-4 w-4 shrink-0" :class="form.region === rcode ? 'text-indigo-500' : 'text-gray-300'" fill="currentColor" viewBox="0 0 24 24">
                                        <path v-if="form.region === rcode" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                        <circle v-else cx="12" cy="12" r="8" stroke="currentColor" stroke-width="2" fill="none" />
                                    </svg>
                                    {{ rname }}
                                </label>
                            </div>
                        </template>
                        <template v-else>
                            <input v-model="form.region" type="text"
                                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                                placeholder="Ex: Californie, Bavière, Tokyo..." />
                        </template>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" :disabled="form.processing"
                            class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50 transition">
                            {{ form.processing ? 'Enregistrement...' : 'Enregistrer la juridiction' }}
                        </button>
                        <button type="button" @click="preview" :disabled="previewForm.processing"
                            class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 transition">
                            Aperçu de la législation
                        </button>
                    </div>
                </form>
            </div>

            <!-- Cadre légal applicable -->
            <div class="rounded-xl border border-gray-200 bg-white p-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-sm font-semibold text-gray-900">
                        Cadre légal · {{ legal.name || 'Non configuré' }}
                    </h3>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        {{ legal.currency }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Lois applicables -->
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Lois applicables</h4>
                            <ul class="space-y-1">
                                <li v-for="(law, i) in legal.laws" :key="i" class="flex items-start gap-2 text-xs text-gray-600">
                                    <svg class="h-3.5 w-3.5 mt-0.5 shrink-0 text-indigo-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L1 21h22L12 2zm0 3.5L19.5 19h-15L12 5.5z" /></svg>
                                    {{ law }}
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Documents obligatoires</h4>
                            <ul class="space-y-1">
                                <li v-for="(doc, i) in legal.required_documents" :key="i" class="flex items-start gap-2 text-xs text-gray-600">
                                    <svg class="h-3.5 w-3.5 mt-0.5 shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    {{ doc }}
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Durée du bail</h4>
                            <p class="text-xs text-gray-600">{{ legal.lease_default_duration }}</p>
                        </div>

                        <div>
                            <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Dépôt de garantie</h4>
                            <p class="text-xs text-gray-600">{{ legal.security_deposit_rules }}</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Préavis</h4>
                            <div class="space-y-2">
                                <div class="rounded-lg bg-gray-50 p-3">
                                    <p class="text-xs font-medium text-gray-700">Locataire</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ legal.termination_notice_tenant }}</p>
                                </div>
                                <div class="rounded-lg bg-gray-50 p-3">
                                    <p class="text-xs font-medium text-gray-700">Bailleur</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ legal.termination_notice_landlord }}</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Règles fiscales</h4>
                            <div class="rounded-lg bg-gray-50 p-3 space-y-1">
                                <p v-for="(val, key) in legal.tax_rules" :key="key" class="text-xs text-gray-600">
                                    <span class="font-medium text-gray-700">{{ key.replace(/_/g, ' ') }} :</span> {{ val }}
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="rounded-lg bg-gray-50 p-3 text-center">
                                <p class="text-xs text-gray-500">Augmentation max</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ legal.rent_increase_rules?.max_increase_per_year || 'Variable' }}</p>
                            </div>
                            <div class="rounded-lg bg-gray-50 p-3 text-center">
                                <p class="text-xs text-gray-500">État des lieux</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ legal.inventory_required ? 'Obligatoire' : 'Recommandé' }}</p>
                            </div>
                            <div class="rounded-lg bg-gray-50 p-3 text-center">
                                <p class="text-xs text-gray-500">Encadrement loyers</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ legal.has_rent_control ? 'Oui' : 'Non' }}</p>
                            </div>
                            <div class="rounded-lg bg-gray-50 p-3 text-center">
                                <p class="text-xs text-gray-500">Caution autorisée</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ legal.guarantor_allowed ? 'Oui' : 'Non' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
