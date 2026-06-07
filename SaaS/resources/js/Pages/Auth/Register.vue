<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import DocumentAnalysisModal from '@/Components/DocumentAnalysisModal.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { authTranslations, countryOptions } from '@/i18n/auth';
import { useLocale } from '@/composables/useLocale';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const { locale } = useLocale();
const t = computed(() => authTranslations[locale.value].register);

const accountType = ref('individual');
const logoPreview = ref(null);
const analyzing = ref(false);
const analysisResult = ref(null);
const analysisError = ref('');
const showAnalysisModal = ref(false);

const form = useForm({
    account_type: 'individual',
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    business_type: '',
    legal_name: '',
    registration_number: '',
    tax_id: '',
    country: '',
    address: '',
    city: '',
    postal_code: '',
    legal_representative_name: '',
    legal_representative_id_number: '',
    phone: '',
    company_logo: null,
    certificate_of_incorporation: null,
    tax_registration_document: null,
    representative_id_document: null,
    proof_of_address: null,
});

const isCompany = computed(() => accountType.value === 'company');

const countries = computed(() =>
    countryOptions.map((c) => ({
        code: c.code,
        label: c[locale.value] ?? c.en,
    })),
);

const documentFields = [
    { key: 'certificate_of_incorporation', labelKey: 'docCertificate', required: true },
    { key: 'tax_registration_document', labelKey: 'docTax', required: true },
    { key: 'representative_id_document', labelKey: 'docRepresentativeId', required: true },
    { key: 'proof_of_address', labelKey: 'docProofOfAddress', required: false },
];

const canAnalyze = computed(() =>
    documentFields
        .filter((d) => d.required)
        .every((d) => form[d.key] instanceof File),
);

function setAccountType(type) {
    accountType.value = type;
    form.account_type = type;
    analysisResult.value = null;
    analysisError.value = '';
    showAnalysisModal.value = false;
}

function handleFileChange(field, event) {
    const file = event.target.files?.[0] ?? null;
    form[field] = file;
    if (field !== 'company_logo') {
        analysisResult.value = null;
    }
}

function handleLogoChange(event) {
    const file = event.target.files?.[0] ?? null;
    form.company_logo = file;

    if (logoPreview.value) {
        URL.revokeObjectURL(logoPreview.value);
    }

    logoPreview.value = file ? URL.createObjectURL(file) : null;
}

async function analyzeDocuments() {
    analysisError.value = '';
    analysisResult.value = null;
    showAnalysisModal.value = false;

    if (!canAnalyze.value) {
        analysisError.value = t.value.analysisError;
        return;
    }

    analyzing.value = true;

    const payload = new FormData();
    payload.append('legal_name', form.legal_name);
    payload.append('registration_number', form.registration_number);
    payload.append('tax_id', form.tax_id);
    payload.append('legal_representative_name', form.legal_representative_name);
    payload.append('business_type', form.business_type);
    payload.append('country', form.country);

    documentFields.forEach((doc) => {
        if (form[doc.key]) {
            payload.append(doc.key, form[doc.key]);
        }
    });

    if (form.company_logo) {
        payload.append('company_logo', form.company_logo);
    }

    try {
        const { data } = await window.axios.post(route('register.analyze'), payload);

        if (!data.success) {
            analysisError.value = data.message || t.value.analysisApiError;
            return;
        }

        analysisResult.value = data;
        showAnalysisModal.value = true;

        if (data.suggestions?.legal_name && !form.legal_name) {
            form.legal_name = data.suggestions.legal_name;
        }
        if (data.suggestions?.registration_number && !form.registration_number) {
            form.registration_number = data.suggestions.registration_number;
        }
        if (data.suggestions?.tax_id && !form.tax_id) {
            form.tax_id = data.suggestions.tax_id;
        }
        if (data.suggestions?.legal_representative_name && !form.legal_representative_name) {
            form.legal_representative_name = data.suggestions.legal_representative_name;
        }
    } catch (error) {
        analysisError.value = error.response?.data?.message || t.value.analysisApiError;
    } finally {
        analyzing.value = false;
    }
}

watch(accountType, () => {
    analysisResult.value = null;
    analysisError.value = '';
    showAnalysisModal.value = false;
});

const submit = () => {
    form.account_type = accountType.value;
    form.post(route('register'), {
        forceFormData: true,
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

const inputClass =
    'mt-1.5 block w-full rounded-xl border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm transition duration-200 placeholder:text-slate-400 focus:border-amber-400 focus:ring-amber-400/20';

const selectClass =
    'mt-1.5 block w-full rounded-xl border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm transition duration-200 focus:border-amber-400 focus:ring-amber-400/20';

const labelClass = 'text-xs font-semibold uppercase tracking-wider text-slate-500';
</script>

<template>
    <GuestLayout :title="t.title" :subtitle="t.subtitle" scrollable>
        <Head :title="t.metaTitle" />

        <!-- Account type swipe toggle -->
        <div class="mb-6">
            <div
                class="relative grid grid-cols-2 rounded-xl border border-slate-200 bg-slate-100/80 p-1"
                role="tablist"
            >
                <div
                    class="absolute inset-y-1 w-[calc(50%-4px)] rounded-lg bg-white shadow-sm ring-1 ring-slate-200/80 transition-transform duration-300 ease-out"
                    :class="isCompany ? 'translate-x-[calc(100%+4px)]' : 'translate-x-1'"
                />
                <button
                    type="button"
                    role="tab"
                    class="relative z-10 rounded-lg px-4 py-2 text-sm font-semibold transition-colors duration-200"
                    :class="!isCompany ? 'text-amber-700' : 'text-slate-500 hover:text-slate-700'"
                    @click="setAccountType('individual')"
                >
                    {{ t.accountTypeIndividual }}
                </button>
                <button
                    type="button"
                    role="tab"
                    class="relative z-10 rounded-lg px-4 py-2 text-sm font-semibold transition-colors duration-200"
                    :class="isCompany ? 'text-amber-700' : 'text-slate-500 hover:text-slate-700'"
                    @click="setAccountType('company')"
                >
                    {{ t.accountTypeCompany }}
                </button>
            </div>
        </div>

        <form class="space-y-4" @submit.prevent="submit">
            <!-- Common fields -->
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <InputLabel for="name" :value="t.name" :class="labelClass" />
                    <TextInput
                        id="name"
                        type="text"
                        :class="inputClass"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="name"
                        :placeholder="t.namePlaceholder"
                    />
                    <InputError class="mt-1.5" :message="form.errors.name" />
                </div>

                <div class="sm:col-span-2">
                    <InputLabel for="email" :value="t.email" :class="labelClass" />
                    <TextInput
                        id="email"
                        type="email"
                        :class="inputClass"
                        v-model="form.email"
                        required
                        autocomplete="username"
                        :placeholder="t.emailPlaceholder"
                    />
                    <InputError class="mt-1.5" :message="form.errors.email" />
                </div>
            </div>

            <!-- Company fields -->
            <Transition
                enter-active-class="transition duration-300 ease-out"
                enter-from-class="opacity-0 -translate-y-2"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition duration-200 ease-in"
                leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 -translate-y-2"
            >
                <div v-if="isCompany" class="space-y-4 border-t border-slate-100 pt-4">
                    <div class="flex items-center gap-2">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-100">
                            <svg class="h-3.5 w-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </span>
                        <h3 class="text-sm font-semibold text-slate-900">{{ t.companySection }}</h3>
                    </div>

                    <!-- Logo upload -->
                    <div class="flex flex-col gap-4 rounded-xl border border-slate-100 bg-slate-50/50 p-4 sm:flex-row sm:items-center">
                        <div
                            class="flex h-20 w-20 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-dashed border-slate-200 bg-white shadow-sm"
                        >
                            <img
                                v-if="logoPreview"
                                :src="logoPreview"
                                alt="Logo preview"
                                class="h-full w-full object-contain p-1"
                            />
                            <svg v-else class="h-8 w-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <InputLabel for="company_logo" :value="t.companyLogo" class="text-xs font-medium text-slate-700" />
                            <p class="mt-0.5 text-xs text-slate-400">{{ t.companyLogoHint }}</p>
                            <label
                                for="company_logo"
                                class="mt-2 inline-flex cursor-pointer items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-amber-700 transition hover:border-amber-300 hover:bg-amber-50"
                            >
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                {{ form.company_logo ? form.company_logo.name : t.uploadLogo }}
                                <input
                                    id="company_logo"
                                    type="file"
                                    class="sr-only"
                                    accept=".jpg,.jpeg,.png,.svg,.webp"
                                    @change="handleLogoChange"
                                />
                            </label>
                            <InputError class="mt-1.5" :message="form.errors.company_logo" />
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <InputLabel for="business_type" :value="t.businessType" :class="labelClass" />
                            <select
                                id="business_type"
                                v-model="form.business_type"
                                :class="selectClass"
                                :required="isCompany"
                            >
                                <option value="" disabled>{{ t.businessTypePlaceholder }}</option>
                                <option value="real_estate">{{ t.businessRealEstate }}</option>
                                <option value="hotel">{{ t.businessHotel }}</option>
                            </select>
                            <InputError class="mt-1.5" :message="form.errors.business_type" />
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="legal_name" :value="t.legalName" :class="labelClass" />
                            <TextInput
                                id="legal_name"
                                type="text"
                                :class="inputClass"
                                v-model="form.legal_name"
                                :required="isCompany"
                                :placeholder="t.legalNamePlaceholder"
                            />
                            <InputError class="mt-1.5" :message="form.errors.legal_name" />
                        </div>

                        <div>
                            <InputLabel for="registration_number" :value="t.registrationNumber" :class="labelClass" />
                            <TextInput
                                id="registration_number"
                                type="text"
                                :class="inputClass"
                                v-model="form.registration_number"
                                :required="isCompany"
                                :placeholder="t.registrationNumberPlaceholder"
                            />
                            <InputError class="mt-1.5" :message="form.errors.registration_number" />
                        </div>
                        <div>
                            <InputLabel for="tax_id" :value="t.taxId" :class="labelClass" />
                            <TextInput
                                id="tax_id"
                                type="text"
                                :class="inputClass"
                                v-model="form.tax_id"
                                :required="isCompany"
                                :placeholder="t.taxIdPlaceholder"
                            />
                            <InputError class="mt-1.5" :message="form.errors.tax_id" />
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="country" :value="t.country" :class="labelClass" />
                            <select
                                id="country"
                                v-model="form.country"
                                :class="selectClass"
                                :required="isCompany"
                            >
                                <option value="" disabled>{{ t.countryPlaceholder }}</option>
                                <option v-for="c in countries" :key="c.code" :value="c.code">
                                    {{ c.label }}
                                </option>
                            </select>
                            <InputError class="mt-1.5" :message="form.errors.country" />
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="address" :value="t.address" :class="labelClass" />
                            <TextInput
                                id="address"
                                type="text"
                                :class="inputClass"
                                v-model="form.address"
                                :required="isCompany"
                                :placeholder="t.addressPlaceholder"
                            />
                            <InputError class="mt-1.5" :message="form.errors.address" />
                        </div>

                        <div>
                            <InputLabel for="city" :value="t.city" :class="labelClass" />
                            <TextInput
                                id="city"
                                type="text"
                                :class="inputClass"
                                v-model="form.city"
                                :required="isCompany"
                                :placeholder="t.cityPlaceholder"
                            />
                            <InputError class="mt-1.5" :message="form.errors.city" />
                        </div>
                        <div>
                            <InputLabel for="postal_code" :value="t.postalCode" :class="labelClass" />
                            <TextInput
                                id="postal_code"
                                type="text"
                                :class="inputClass"
                                v-model="form.postal_code"
                                :required="isCompany"
                                :placeholder="t.postalCodePlaceholder"
                            />
                            <InputError class="mt-1.5" :message="form.errors.postal_code" />
                        </div>

                        <div>
                            <InputLabel for="legal_representative_name" :value="t.representativeName" :class="labelClass" />
                            <TextInput
                                id="legal_representative_name"
                                type="text"
                                :class="inputClass"
                                v-model="form.legal_representative_name"
                                :required="isCompany"
                                :placeholder="t.representativeNamePlaceholder"
                            />
                            <InputError class="mt-1.5" :message="form.errors.legal_representative_name" />
                        </div>
                        <div>
                            <InputLabel for="legal_representative_id_number" :value="t.representativeId" :class="labelClass" />
                            <TextInput
                                id="legal_representative_id_number"
                                type="text"
                                :class="inputClass"
                                v-model="form.legal_representative_id_number"
                                :required="isCompany"
                                :placeholder="t.representativeIdPlaceholder"
                            />
                            <InputError class="mt-1.5" :message="form.errors.legal_representative_id_number" />
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="phone" :value="t.phone" :class="labelClass" />
                            <TextInput
                                id="phone"
                                type="tel"
                                :class="inputClass"
                                v-model="form.phone"
                                :required="isCompany"
                                :placeholder="t.phonePlaceholder"
                            />
                            <InputError class="mt-1.5" :message="form.errors.phone" />
                        </div>
                    </div>

                    <!-- Legal documents -->
                    <div class="space-y-3 rounded-xl border border-amber-200/60 bg-amber-50/30 p-4">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div class="flex items-start gap-2.5">
                                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-amber-100">
                                    <svg class="h-3.5 w-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </span>
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-900">{{ t.documentsSection }}</h3>
                                    <p class="mt-0.5 text-xs leading-relaxed text-slate-500">{{ t.documentsHint }}</p>
                                </div>
                            </div>

                            <button
                                type="button"
                                class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl border border-amber-300 bg-white px-4 py-2 text-xs font-semibold text-amber-800 shadow-sm transition hover:bg-amber-50 disabled:cursor-not-allowed disabled:opacity-50"
                                :disabled="analyzing || !canAnalyze"
                                @click="analyzeDocuments"
                            >
                                <svg
                                    class="h-3.5 w-3.5"
                                    :class="analyzing ? 'animate-spin' : ''"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        v-if="analyzing"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                                    />
                                    <path
                                        v-else
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"
                                    />
                                </svg>
                                {{ analyzing ? t.analyzing : t.analyzeButton }}
                            </button>
                        </div>

                        <p v-if="analysisError" class="rounded-lg bg-red-50 px-3 py-2 text-xs text-red-600">
                            {{ analysisError }}
                        </p>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <div
                                v-for="doc in documentFields"
                                :key="doc.key"
                                class="rounded-lg border border-white/80 bg-white/70 p-3"
                            >
                                <InputLabel :for="doc.key" :value="t[doc.labelKey]" class="text-[11px] font-medium leading-tight text-slate-700" />
                                <label
                                    :for="doc.key"
                                    class="mt-1.5 flex cursor-pointer items-center justify-between gap-2 rounded-lg border border-dashed border-slate-200 bg-white px-3 py-2 transition hover:border-amber-300 hover:bg-amber-50/30"
                                >
                                    <span class="truncate text-xs" :class="form[doc.key] ? 'font-medium text-slate-900' : 'text-slate-400'">
                                        {{ form[doc.key] ? form[doc.key].name : t.chooseFile }}
                                    </span>
                                    <span class="shrink-0 text-[10px] font-semibold text-amber-700">PDF</span>
                                    <input
                                        :id="doc.key"
                                        type="file"
                                        class="sr-only"
                                        accept=".pdf,.jpg,.jpeg,.png"
                                        :required="isCompany && doc.required"
                                        @change="handleFileChange(doc.key, $event)"
                                    />
                                </label>
                                <InputError class="mt-1" :message="form.errors[doc.key]" />
                            </div>
                        </div>

                        <p class="flex items-start gap-2 text-[11px] leading-relaxed text-slate-500">
                            <svg class="mt-0.5 h-3.5 w-3.5 shrink-0 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            {{ t.aiProcessingNote }}
                        </p>
                    </div>
                </div>
            </Transition>

            <!-- Password fields -->
            <div class="grid gap-4 border-t border-slate-100 pt-4 sm:grid-cols-2">
                <div>
                    <InputLabel for="password" :value="t.password" :class="labelClass" />
                    <TextInput
                        id="password"
                        type="password"
                        :class="inputClass"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                        :placeholder="t.passwordPlaceholder"
                    />
                    <InputError class="mt-1.5" :message="form.errors.password" />
                </div>
                <div>
                    <InputLabel for="password_confirmation" :value="t.confirmPassword" :class="labelClass" />
                    <TextInput
                        id="password_confirmation"
                        type="password"
                        :class="inputClass"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                        :placeholder="t.confirmPlaceholder"
                    />
                    <InputError class="mt-1.5" :message="form.errors.password_confirmation" />
                </div>
            </div>

            <p class="text-[11px] leading-relaxed text-slate-400">
                {{ t.legalPrefix }}
                <a href="#" class="text-amber-700 hover:text-amber-800">{{ t.terms }}</a>
                {{ t.legalAnd }}
                <a href="#" class="text-amber-700 hover:text-amber-800">{{ t.privacy }}</a>.
            </p>

            <button
                type="submit"
                class="group relative w-full overflow-hidden rounded-xl px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-amber-500/20 transition-all duration-300 hover:shadow-xl hover:shadow-amber-500/30 disabled:cursor-not-allowed disabled:opacity-50"
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

        <p class="mt-6 text-center text-sm text-slate-500">
            {{ t.hasAccount }}
            <Link
                :href="route('login')"
                class="font-semibold text-amber-700 transition-colors hover:text-amber-800"
            >
                {{ t.signIn }}
            </Link>
        </p>

        <DocumentAnalysisModal
            :show="showAnalysisModal"
            :result="analysisResult"
            :locale="locale"
            :labels="t"
            @close="showAnalysisModal = false"
        />
    </GuestLayout>
</template>
