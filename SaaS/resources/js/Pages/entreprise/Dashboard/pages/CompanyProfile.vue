<template>
    <div class="space-y-6 max-w-5xl mx-auto pb-12">
        <!-- Success Toast -->
        <Transition name="fade">
            <div v-if="showSuccessToast" class="fixed bottom-6 right-6 z-50 flex items-center gap-3 rounded-2xl bg-emerald-500 px-5 py-4 text-white shadow-xl shadow-emerald-500/20 border border-emerald-400/30">
                <svg class="h-6 w-6 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-sm font-semibold">Profil de l'entreprise enregistré avec succès !</div>
            </div>
        </Transition>

        <!-- Header Banner -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-950 p-8 text-white shadow-xl border border-slate-800">
            <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-gradient-to-br from-indigo-500/20 to-purple-600/20 blur-3xl"></div>
            <div class="absolute -left-20 -bottom-20 h-64 w-64 rounded-full bg-gradient-to-tr from-violet-500/10 to-indigo-600/10 blur-2xl"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-center gap-6 justify-between">
                <div class="flex flex-col md:flex-row items-center gap-6 text-center md:text-left">
                    <!-- Logo Upload Zone -->
                    <div class="relative group cursor-pointer">
                        <div class="relative h-24 w-24 shrink-0 overflow-hidden rounded-2xl border-2 border-slate-700/50 bg-slate-900/60 transition-all duration-300 group-hover:border-indigo-400 group-hover:shadow-lg group-hover:shadow-indigo-500/20 flex items-center justify-center">
                            <img v-if="computedLogo" :src="computedLogo" class="h-full w-full object-contain p-2" alt="Logo preview" />
                            <div v-else class="flex flex-col items-center justify-center text-slate-400 p-2">
                                <svg class="h-8 w-8 text-slate-500 group-hover:text-indigo-400 transition-colors" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H2.25A1.5 1.5 0 00.75 6v12.75a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                </svg>
                                <span class="text-[9px] mt-1 font-medium group-hover:text-indigo-400">Ajouter Logo</span>
                            </div>
                            <!-- Overlay on hover -->
                            <div class="absolute inset-0 bg-slate-950/70 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all duration-200">
                                <span class="text-xs font-semibold text-indigo-300">Modifier</span>
                            </div>
                        </div>
                        <input type="file" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer" @change="handleFileChange" />
                    </div>

                    <div class="space-y-1">
                        <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight bg-gradient-to-r from-white via-slate-100 to-indigo-200 bg-clip-text text-transparent">
                            {{ companyName || 'Nouvelle Entreprise' }}
                        </h1>
                        <p class="text-sm text-slate-400 flex items-center justify-center md:justify-start gap-2">
                            <span class="inline-flex items-center rounded-md bg-indigo-500/10 px-2 py-1 text-xs font-medium text-indigo-300 ring-1 ring-inset ring-indigo-500/20">
                                {{ companyType || 'SaaS Client' }}
                            </span>
                            <span class="text-slate-500">•</span>
                            <span>Promoteur : <strong class="text-slate-200">{{ promoterName }}</strong></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Forms Grid -->
        <form @submit.prevent="submitForm" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Left Details / Promoter Card -->
                <div class="md:col-span-1 space-y-6">
                    <!-- Promoter Details -->
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/60 relative overflow-hidden text-left">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-50 rounded-full blur-xl -z-10"></div>
                        <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                            Promoteur de l'Entreprise
                        </h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Nom du Promoteur <span class="text-red-500">*</span></label>
                                <input
                                    v-model="form.promoter_name"
                                    type="text"
                                    required
                                    placeholder="Ex: Jean Dupont"
                                    class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-slate-700 font-medium bg-slate-50/50"
                                    :class="{'border-red-500 focus:ring-red-500': errors.promoter_name}"
                                />
                                <span v-if="errors.promoter_name" class="text-red-500 text-[11px] mt-1 block">{{ errors.promoter_name[0] }}</span>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Adresse Email</label>
                                <input
                                    :value="user.email"
                                    type="email"
                                    disabled
                                    class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm bg-slate-100 text-slate-400 cursor-not-allowed font-medium"
                                />
                                <p class="text-[10px] text-slate-400 mt-1">L'adresse e-mail est liée au compte et ne peut être modifiée.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Phone Contact -->
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/60 text-left">
                        <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-1.514 2.019a16.5 16.5 0 01-7.962-7.962l2.019-1.514a1.161 1.161 0 00.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                            </svg>
                            Contact Principal
                        </h2>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Numéro de Téléphone <span class="text-red-500">*</span></label>
                            <input
                                v-model="form.phone"
                                type="tel"
                                required
                                placeholder="Ex: +33 1 23 45 67 89"
                                class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-slate-700 font-medium bg-slate-50/50"
                                :class="{'border-red-500 focus:ring-red-500': errors.phone}"
                            />
                            <span v-if="errors.phone" class="text-red-500 text-[11px] mt-1 block">{{ errors.phone[0] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Right Company Profile Form -->
                <div class="md:col-span-2 space-y-6 text-left">
                    <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-slate-200/60 space-y-6">
                        <!-- Company Details Section -->
                        <div>
                            <h2 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.33-1A2.25 2.25 0 0018 9v12m-12 0V9a2.25 2.25 0 00-2.25-2.25H4.5A2.25 2.25 0 002.25 9v12" />
                                </svg>
                                Fiche Signalétique de l'Entreprise
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Dénomination Sociale <span class="text-red-500">*</span></label>
                                    <input
                                        v-model="form.legal_name"
                                        type="text"
                                        required
                                        placeholder="Ex: Babeni Immobilier"
                                        class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-slate-700 font-medium"
                                        :class="{'border-red-500 focus:ring-red-500': errors.legal_name}"
                                    />
                                    <span v-if="errors.legal_name" class="text-red-500 text-[11px] mt-1 block">{{ errors.legal_name[0] }}</span>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Forme Juridique <span class="text-red-500">*</span></label>
                                    <input
                                        v-model="form.business_type"
                                        type="text"
                                        required
                                        placeholder="Ex: SAS, SARL, SA"
                                        class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-slate-700 font-medium"
                                        :class="{'border-red-500 focus:ring-red-500': errors.business_type}"
                                    />
                                    <span v-if="errors.business_type" class="text-red-500 text-[11px] mt-1 block">{{ errors.business_type[0] }}</span>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Numéro d'Immatriculation / SIRET <span class="text-red-500">*</span></label>
                                    <input
                                        v-model="form.registration_number"
                                        type="text"
                                        required
                                        placeholder="Ex: RCS Paris B 123 456 789"
                                        class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-slate-700 font-medium"
                                        :class="{'border-red-500 focus:ring-red-500': errors.registration_number}"
                                    />
                                    <span v-if="errors.registration_number" class="text-red-500 text-[11px] mt-1 block">{{ errors.registration_number[0] }}</span>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Numéro de TVA / Identifiant Fiscal <span class="text-red-500">*</span></label>
                                    <input
                                        v-model="form.tax_id"
                                        type="text"
                                        required
                                        placeholder="Ex: FR 12 345678901"
                                        class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-slate-700 font-medium"
                                        :class="{'border-red-500 focus:ring-red-500': errors.tax_id}"
                                    />
                                    <span v-if="errors.tax_id" class="text-red-500 text-[11px] mt-1 block">{{ errors.tax_id[0] }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Legal Representative Section -->
                        <div>
                            <h2 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                                </svg>
                                Représentant Légal
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Nom du Représentant Légal <span class="text-red-500">*</span></label>
                                    <input
                                        v-model="form.legal_representative_name"
                                        type="text"
                                        required
                                        placeholder="Ex: Jean Dupont"
                                        class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-slate-700 font-medium"
                                        :class="{'border-red-500 focus:ring-red-500': errors.legal_representative_name}"
                                    />
                                    <span v-if="errors.legal_representative_name" class="text-red-500 text-[11px] mt-1 block">{{ errors.legal_representative_name[0] }}</span>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">N° de Pièce d'Identité <span class="text-red-500">*</span></label>
                                    <input
                                        v-model="form.legal_representative_id_number"
                                        type="text"
                                        required
                                        placeholder="Ex: CNI 123456789"
                                        class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-slate-700 font-medium"
                                        :class="{'border-red-500 focus:ring-red-500': errors.legal_representative_id_number}"
                                    />
                                    <span v-if="errors.legal_representative_id_number" class="text-red-500 text-[11px] mt-1 block">{{ errors.legal_representative_id_number[0] }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Address Section -->
                        <div>
                            <h2 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25s-7.5-4.108-7.5-11.25a7.5 7.5 0 1115 0z" />
                                </svg>
                                Adresse & Localisation
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Adresse <span class="text-red-500">*</span></label>
                                    <input
                                        v-model="form.address"
                                        type="text"
                                        required
                                        placeholder="Ex: 15 Rue de l'Immobilier"
                                        class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-slate-700 font-medium"
                                        :class="{'border-red-500 focus:ring-red-500': errors.address}"
                                    />
                                    <span v-if="errors.address" class="text-red-500 text-[11px] mt-1 block">{{ errors.address[0] }}</span>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Ville <span class="text-red-500">*</span></label>
                                    <input
                                        v-model="form.city"
                                        type="text"
                                        required
                                        placeholder="Ex: Paris"
                                        class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-slate-700 font-medium"
                                        :class="{'border-red-500 focus:ring-red-500': errors.city}"
                                    />
                                    <span v-if="errors.city" class="text-red-500 text-[11px] mt-1 block">{{ errors.city[0] }}</span>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Code Postal <span class="text-red-500">*</span></label>
                                    <input
                                        v-model="form.postal_code"
                                        type="text"
                                        required
                                        placeholder="Ex: 75001"
                                        class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-slate-700 font-medium"
                                        :class="{'border-red-500 focus:ring-red-500': errors.postal_code}"
                                    />
                                    <span v-if="errors.postal_code" class="text-red-500 text-[11px] mt-1 block">{{ errors.postal_code[0] }}</span>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Pays (Code ISO) <span class="text-red-500">*</span></label>
                                    <input
                                        v-model="form.country"
                                        type="text"
                                        required
                                        maxlength="2"
                                        placeholder="Ex: FR, CM, BE"
                                        class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-slate-700 font-medium uppercase"
                                        :class="{'border-red-500 focus:ring-red-500': errors.country}"
                                    />
                                    <span v-if="errors.country" class="text-red-500 text-[11px] mt-1 block">{{ errors.country[0] }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end pt-4 border-t border-slate-100">
                            <button
                                type="submit"
                                :disabled="isSubmitting"
                                class="px-8 py-3 bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white rounded-xl text-sm font-bold shadow-md shadow-indigo-500/10 hover:shadow-lg hover:shadow-indigo-500/20 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
                            >
                                <span v-if="!isSubmitting">Enregistrer les Modifications</span>
                                <span v-else class="inline-flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Enregistrement...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { usePage, router } from '@inertiajs/vue3';

const page = usePage();
const user = computed(() => page.props.auth?.user || {});
const company = computed(() => user.value?.company || {});

const companyName = computed(() => company.value?.legal_name || '');
const companyType = computed(() => company.value?.business_type || '');
const promoterName = computed(() => user.value?.name || '');

const form = ref({
    legal_name: '',
    business_type: '',
    registration_number: '',
    tax_id: '',
    country: 'FR',
    address: '',
    city: '',
    postal_code: '',
    legal_representative_name: '',
    legal_representative_id_number: '',
    phone: '',
    promoter_name: '',
    logo: null,
});

const logoPreview = ref(null);
const computedLogo = computed(() => {
    if (logoPreview.value) return logoPreview.value;
    return company.value?.logo_path ? '/storage/' + company.value.logo_path : null;
});

const isSubmitting = ref(false);
const errors = ref({});
const showSuccessToast = ref(false);

watch(() => [company.value, user.value], () => {
    form.value.legal_name = company.value?.legal_name || '';
    form.value.business_type = company.value?.business_type || '';
    form.value.registration_number = company.value?.registration_number || '';
    form.value.tax_id = company.value?.tax_id || '';
    form.value.country = company.value?.country || 'FR';
    form.value.address = company.value?.address || '';
    form.value.city = company.value?.city || '';
    form.value.postal_code = company.value?.postal_code || '';
    form.value.legal_representative_name = company.value?.legal_representative_name || '';
    form.value.legal_representative_id_number = company.value?.legal_representative_id_number || '';
    form.value.phone = company.value?.phone || '';
    form.value.promoter_name = user.value?.name || '';
}, { deep: true, immediate: true });

const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (!file) return;
    form.value.logo = file;

    const reader = new FileReader();
    reader.onload = (event) => {
        logoPreview.value = event.target.result;
    };
    reader.readAsDataURL(file);
};

const submitForm = () => {
    isSubmitting.value = true;
    errors.value = {};

    const formData = new FormData();
    Object.keys(form.value).forEach((key) => {
        if (form.value[key] !== null && form.value[key] !== undefined) {
            formData.append(key, form.value[key]);
        }
    });

    router.post(window.route('company.profile.update'), formData, {
        forceFormData: true,
        onSuccess: () => {
            showSuccessToast.value = true;
            setTimeout(() => {
                showSuccessToast.value = false;
            }, 4000);
        },
        onError: (errs) => {
            errors.value = errs;
        },
        onFinish: () => {
            isSubmitting.value = false;
        },
    });
};
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
    transform: translateY(20px);
}
</style>
