<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { authTranslations } from '@/i18n/auth';
import { useLocale } from '@/composables/useLocale';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const { locale } = useLocale();
const t = computed(() => authTranslations[locale.value].login);

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

const inputClass =
    'mt-2 block w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition duration-200 placeholder:text-slate-400 focus:border-amber-400 focus:ring-amber-400/20';
</script>

<template>
    <GuestLayout :title="t.title" :subtitle="t.subtitle">
        <Head :title="t.metaTitle" />

        <div
            v-if="status"
            class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700"
        >
            {{ status }}
        </div>

        <form class="space-y-5" @submit.prevent="submit">
            <div>
                <InputLabel for="email" :value="t.email" class="text-xs font-semibold uppercase tracking-wider text-slate-500" />

                <TextInput
                    id="email"
                    type="email"
                    :class="inputClass"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    :placeholder="t.emailPlaceholder"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <InputLabel for="password" :value="t.password" class="text-xs font-semibold uppercase tracking-wider text-slate-500" />
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-xs font-medium text-amber-700 transition-colors hover:text-amber-800"
                    >
                        {{ t.forgotPassword }}
                    </Link>
                </div>

                <TextInput
                    id="password"
                    type="password"
                    :class="inputClass"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    :placeholder="t.passwordPlaceholder"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="flex items-center">
                <Checkbox name="remember" v-model:checked="form.remember" />
                <label class="ms-3 text-sm text-slate-600">{{ t.remember }}</label>
            </div>

            <button
                type="submit"
                class="group relative mt-2 w-full overflow-hidden rounded-xl px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-amber-500/20 transition-all duration-300 hover:shadow-xl hover:shadow-amber-500/30 disabled:cursor-not-allowed disabled:opacity-50"
                :disabled="form.processing"
            >
                <span class="absolute inset-0 bg-gradient-to-r from-amber-500 via-amber-600 to-amber-700 transition-transform duration-300 group-hover:scale-[1.02]" />
                <span class="relative flex items-center justify-center gap-2">
                    <svg
                        v-if="form.processing"
                        class="h-4 w-4 animate-spin"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                    </svg>
                    {{ form.processing ? t.submitting : t.submit }}
                </span>
            </button>
        </form>

        <p class="mt-8 text-center text-sm text-slate-500">
            {{ t.noAccount }}
            <Link
                :href="route('register')"
                class="font-semibold text-amber-700 transition-colors hover:text-amber-800"
            >
                {{ t.requestAccess }}
            </Link>
        </p>
    </GuestLayout>
</template>
