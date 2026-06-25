<script setup>
import { Head, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    invitations: Array,
    members: Array,
});

const form = useForm({
    email: '',
    name: '',
    role: 'agent',
});

function invite() {
    form.post(route('landlord.pro.team.invite'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
}

function cancel(inv) {
    if (confirm(`Annuler l'invitation de ${inv.email} ?`)) {
        router.delete(route('landlord.pro.team.cancel', inv.id), { preserveScroll: true });
    }
}

function remove(member) {
    if (confirm(`Retirer ${member.member?.name} de l'équipe ?`)) {
        router.delete(route('landlord.pro.team.remove', member.id), { preserveScroll: true });
    }
}

const roles = { agent: 'Agent', viewer: 'Consultation', manager: 'Gestionnaire' };
</script>

<template>
    <Head title="Équipe" />
    <AppLayout>
        <template #header>
            <h2 class="text-lg font-semibold text-gray-900">Gestion de l'équipe</h2>
            <p class="text-sm text-gray-500">Invitez des collaborateurs à gérer vos biens</p>
        </template>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-6">
                <!-- Members -->
                <div class="rounded-xl border border-gray-200 bg-white p-5">
                    <h3 class="mb-4 text-sm font-semibold text-gray-900">Membres ({{ members.length }})</h3>
                    <div v-if="members.length" class="space-y-3">
                        <div v-for="m in members" :key="m.id"
                            class="flex items-center justify-between rounded-lg bg-gray-50 px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-100 text-sm font-semibold text-indigo-700">
                                    {{ m.member?.name?.charAt(0)?.toUpperCase() || '?' }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ m.member?.name || 'Inconnu' }}</p>
                                    <p class="text-xs text-gray-500">{{ m.member?.email }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="rounded-full bg-gray-200 px-2.5 py-0.5 text-xs font-medium text-gray-700">{{ roles[m.role] || m.role }}</span>
                                <button @click="remove(m)" class="text-xs text-red-600 hover:text-red-800">Retirer</button>
                            </div>
                        </div>
                    </div>
                    <p v-else class="text-sm text-gray-400">Aucun membre pour le moment.</p>
                </div>

                <!-- Pending invitations -->
                <div v-if="invitations.length" class="rounded-xl border border-gray-200 bg-white p-5">
                    <h3 class="mb-4 text-sm font-semibold text-gray-900">Invitations en attente ({{ invitations.length }})</h3>
                    <div class="space-y-2">
                        <div v-for="inv in invitations" :key="inv.id"
                            class="flex items-center justify-between rounded-lg bg-amber-50 px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-amber-100 text-sm font-semibold text-amber-700">
                                    {{ inv.email?.charAt(0)?.toUpperCase() || '?' }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ inv.name || inv.email }}</p>
                                    <p class="text-xs text-amber-600">{{ inv.email }} — {{ roles[inv.role] || inv.role }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-amber-600">Expire le {{ new Date(inv.expires_at).toLocaleDateString('fr-FR') }}</span>
                                <button @click="cancel(inv)" class="text-xs text-red-600 hover:text-red-800">Annuler</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invite form -->
            <div class="rounded-xl border border-gray-200 bg-white p-5 h-fit">
                <h3 class="mb-4 text-sm font-semibold text-gray-900">Inviter un membre</h3>
                <form @submit.prevent="invite" class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Email</label>
                        <input v-model="form.email" type="email" required
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                            placeholder="collaborateur@email.com" />
                        <p v-if="form.errors.email" class="mt-1 text-xs text-red-600">{{ form.errors.email }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Nom (optionnel)</label>
                        <input v-model="form.name" type="text"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                            placeholder="Jean Dupont" />
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Rôle</label>
                        <select v-model="form.role"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            <option value="agent">Agent — Gère les biens et locataires</option>
                            <option value="manager">Gestionnaire — Accès complet sauf paramètres</option>
                            <option value="viewer">Consultation — Lecture seule</option>
                        </select>
                    </div>
                    <button type="submit" :disabled="form.processing"
                        class="w-full rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50">
                        Envoyer l'invitation
                    </button>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
