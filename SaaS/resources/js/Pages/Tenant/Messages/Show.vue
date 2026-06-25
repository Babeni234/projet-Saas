<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import TenantLayout from '@/Layouts/Tenant/TenantLayout.vue';

const $page = usePage();
defineProps({ conversation: Object, messages: Array });

const form = useForm({ content: '' });

function submit() {
    form.post(route('tenant.messages.reply', conversation.id), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
}

function otherParticipant() {
    return conversation.participants?.find(p => p.user_id !== $page.props.auth.tenant_user?.tenant?.user_id)?.user;
}
</script>

<template>
    <Head :title="conversation.subject" />
    <TenantLayout>
        <div class="flex items-center gap-4 mb-6">
            <Link :href="route('tenant.messages.index')" class="text-gray-400 hover:text-gray-600"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg></Link>
            <div>
                <h2 class="text-lg font-semibold text-gray-900">{{ conversation.subject }}</h2>
                <p class="text-xs text-gray-500">avec {{ otherParticipant()?.name || 'Bailleur' }}</p>
            </div>
        </div>

        <div class="max-w-2xl space-y-4 mb-4 max-h-[55vh] overflow-y-auto">
            <div v-for="m in messages" :key="m.id" class="flex" :class="m.user_id === $page.props.auth.tenant_user?.tenant?.user_id ? 'justify-end' : 'justify-start'">
                <div class="max-w-[75%] rounded-2xl px-4 py-3 text-sm"
                    :class="m.user_id === $page.props.auth.tenant_user?.tenant?.user_id ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-900'">
                    <p>{{ m.content }}</p>
                    <p class="mt-1 text-xs" :class="m.user_id === $page.props.auth.tenant_user?.tenant?.user_id ? 'text-indigo-200' : 'text-gray-400'">
                        {{ new Date(m.created_at).toLocaleString('fr-FR', { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' }) }}
                    </p>
                </div>
            </div>
        </div>

        <form @submit.prevent="submit" class="flex items-end gap-3 max-w-2xl">
            <div class="flex-1">
                <textarea v-model="form.content" rows="2" placeholder="Écrivez votre message..." class="block w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
            </div>
            <button type="submit" :disabled="form.processing || !form.content.trim()" class="rounded-lg bg-indigo-600 px-5 py-3 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50">Envoyer</button>
        </form>
    </TenantLayout>
</template>
