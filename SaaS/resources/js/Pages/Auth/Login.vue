<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { authTranslations } from '@/i18n/auth';
import { useLocale } from '@/composables/useLocale';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref, onMounted, onUnmounted, watch } from 'vue';

const props = defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    requires2fa: {
        type: Boolean,
        default: false,
    },
    email: {
        type: String,
        default: '',
    },
});

const { locale } = useLocale();
const t = computed(() => authTranslations[locale.value].login);

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const verifyForm = useForm({
    code: '',
});

const resendCountdown = ref(60);
let countdownTimer = null;

const startCountdown = () => {
    resendCountdown.value = 60;
    if (countdownTimer) clearInterval(countdownTimer);
    countdownTimer = setInterval(() => {
        if (resendCountdown.value > 0) {
            resendCountdown.value--;
        } else {
            clearInterval(countdownTimer);
        }
    }, 1000);
};

onMounted(() => {
    if (props.requires2fa) {
        startCountdown();
    }
});

onUnmounted(() => {
    if (countdownTimer) clearInterval(countdownTimer);
});

watch(() => props.requires2fa, (newVal) => {
    if (newVal) {
        startCountdown();
    }
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

const submit2fa = () => {
    verifyForm.post(route('login.verify-2fa'), {
        onError: () => {
            // Keep code or reset
        }
    });
};

const handleCodeInput = () => {
    // Only digits, max 6 chars
    verifyForm.code = verifyForm.code.replace(/\D/g, '').slice(0, 6);
    
    if (verifyForm.code.length === 6) {
        submit2fa();
    }
};

const resendForm = useForm({});
const resend2faCode = () => {
    if (resendCountdown.value > 0) return;
    resendForm.post(route('login.resend-2fa'), {
        onSuccess: () => {
            startCountdown();
        }
    });
};

const inputClass =
    'mt-2 block w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition duration-200 placeholder:text-slate-400 focus:border-amber-400 focus:ring-amber-400/20';
</script>

<template>
    <GuestLayout 
        :title="requires2fa ? 'Vérification de sécurité' : t.title" 
        :subtitle="requires2fa ? 'Entrez le code de vérification pour continuer' : t.subtitle"
    >
        <Head :title="requires2fa ? 'Double Facteur (2FA)' : t.metaTitle" />

        <div
            v-if="status"
            class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700"
        >
            {{ status }}
        </div>

        <!-- 2FA Saisie Code -->
        <div v-if="requires2fa" class="space-y-6">
            <div class="rounded-xl border border-amber-200/50 bg-amber-50/50 p-4 text-xs leading-relaxed text-amber-900 flex items-start gap-3 shadow-inner">
                <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <div>
                    <span class="font-bold">Sécurisation renforcée :</span>
                    Un code de validation unique a été envoyé à l'adresse <span class="font-bold underline">{{ email }}</span>. Ce code temporaire expire dans <span class="font-bold">1 minute</span>. 
                    La vérification est automatique dès la saisie complète ou manuelle en validant ci-dessous.
                </div>
            </div>

            <form class="space-y-5" @submit.prevent="submit2fa">
                <div>
                    <InputLabel for="code" value="Code de validation" class="text-xs font-semibold uppercase tracking-wider text-slate-500" />

                    <TextInput
                        id="code"
                        type="text"
                        class="mt-2 block w-full rounded-xl border-slate-200 bg-white px-4 py-3.5 text-center font-mono text-xl tracking-[10px] text-slate-900 shadow-sm transition duration-200 placeholder:text-slate-400 placeholder:tracking-normal focus:border-amber-400 focus:ring-amber-400/20"
                        v-model="verifyForm.code"
                        required
                        autofocus
                        autocomplete="one-time-code"
                        placeholder="------"
                        @input="handleCodeInput"
                        :disabled="verifyForm.processing"
                    />

                    <InputError class="mt-2 text-center" :message="verifyForm.errors.code" />
                </div>

                <div class="flex items-center justify-between gap-4">
                    <button
                        type="button"
                        @click="resend2faCode"
                        class="text-xs font-semibold text-slate-500 hover:text-slate-700 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="resendCountdown > 0 || resendForm.processing"
                    >
                        <span v-if="resendCountdown > 0" class="text-slate-400">
                            Renvoyer le code ({{ resendCountdown }}s)
                        </span>
                        <span v-else class="text-amber-700 hover:text-amber-800 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H12v3" />
                            </svg>
                            Renvoyer le code
                        </span>
                    </button>

                    <Link
                        :href="route('login')"
                        class="text-xs font-semibold text-amber-700 hover:text-amber-800 transition duration-200"
                    >
                        Retour à la connexion
                    </Link>
                </div>

                <button
                    type="submit"
                    class="group relative mt-2 w-full overflow-hidden rounded-xl px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-amber-500/20 transition-all duration-300 hover:shadow-xl hover:shadow-amber-500/30 disabled:cursor-not-allowed disabled:opacity-50"
                    :disabled="verifyForm.processing || verifyForm.code.length < 6"
                >
                    <span class="absolute inset-0 bg-gradient-to-r from-amber-500 via-amber-600 to-amber-700 transition-transform duration-300 group-hover:scale-[1.02]" />
                    <span class="relative flex items-center justify-center gap-2">
                        <svg
                            v-if="verifyForm.processing"
                            class="h-4 w-4 animate-spin"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                        </svg>
                        Valider le code
                    </span>
                </button>
            </form>
        </div>

        <!-- Connexion Standard -->
        <form v-else class="space-y-5" @submit.prevent="submit">
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

        <p v-if="!requires2fa" class="mt-8 text-center text-sm text-slate-500">
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
