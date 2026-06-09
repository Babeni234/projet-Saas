<script setup>
import { Head } from '@inertiajs/vue3';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

const collaborators = [
    { id: 1, name: 'Sophie Lemaire', role: 'Comptable', email: 'sophie@example.com' },
    { id: 2, name: 'Marc Petit', role: 'Agent immobilier', email: 'marc@example.com' },
];
</script>

<template>
    <Head title="Équipe" />

    <PropertyLayout role="bailleur" title="Équipe" subtitle="Gestion des accès et collaborateurs">
        <div class="imo-page">
            <section class="imo-section">
                <div class="imo-section-header">
                    <h2 class="imo-section-heading">Équipe</h2>
                    <p class="text-sm text-slate-500">Invitez et gérez les accès de vos collaborateurs.</p>
                </div>
                <div class="imo-panel-compact space-y-3">
                    <div
                        v-for="member in collaborators"
                        :key="member.id"
                        class="imo-panel"
                    >
                        <p class="font-semibold text-slate-900">{{ member.name }}</p>
                        <p class="text-sm text-slate-500">{{ member.role }}</p>
                        <p class="text-sm text-slate-500">{{ member.email }}</p>
                    </div>
                </div>
            </section>
        </div>
    </PropertyLayout>
</template>
