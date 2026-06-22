<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

defineProps({
    fiscalYears: { type: Array, default: () => [] },
});

function store() { useForm({ year: new Date().getFullYear().toString() }).post(route('landlord.fiscal.store'), { preserveScroll: true }); }
</script>

<template>
    <Head title="Fiscal" />
    <PropertyLayout role="bailleur" title="Déclaration fiscale" subtitle="Suivez vos revenus et dépenses fonciers">
        <template #actions>
            <button @click="store" class="imo-btn-primary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Nouvelle année fiscale
            </button>
        </template>
        <div v-if="fiscalYears.length === 0" class="text-center py-12 text-gray-500">Aucune année fiscale.</div>
        <div v-for="fy in fiscalYears" :key="fy.id" class="bg-white rounded-lg shadow p-4 mb-3">
            <div class="flex items-start justify-between">
                <div>
                    <Link :href="route('landlord.fiscal.show', fy.id)" class="text-lg font-semibold hover:text-indigo-600">
                        Année {{ fy.year }}
                    </Link>
                    <p class="text-sm text-gray-500">Revenus: {{ fy.total_revenue }}€ / Dépenses: {{ fy.total_expenses }}€ / Net: {{ fy.net_income }}€</p>
                </div>
                <span :class="['px-2 py-1 text-xs rounded-full',
                    fy.status === 'brouillon' ? 'bg-yellow-100 text-yellow-800' :
                    fy.status === 'finalise' ? 'bg-green-100 text-green-800' :
                    'bg-gray-100 text-gray-800']">
                    {{ fy.status === 'brouillon' ? 'Brouillon' : 'Finalisé' }}
                </span>
            </div>
        </div>
    </PropertyLayout>
</template>
