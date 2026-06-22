<script setup>
import SuperAdminLayout from '../layouts/SuperAdminLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref, computed, inject } from 'vue';

// Inject theme state
const theme = inject('theme');
const page = usePage();

// List of major countries for registration dropdown
const countries = [
    { name: "Cameroun", code: "CM" },
    { name: "Sénégal", code: "SN" },
    { name: "Côte d'Ivoire", code: "CI" },
    { name: "France", code: "FR" },
    { name: "Gabon", code: "GA" },
    { name: "Mali", code: "ML" },
    { name: "Bénin", code: "BJ" },
    { name: "Togo", code: "TG" },
    { name: "Niger", code: "NE" },
    { name: "RDC (Congo-Kinshasa)", code: "CD" },
    { name: "Congo-Brazzaville", code: "CG" },
    { name: "Burkina Faso", code: "BF" },
    { name: "Tchad", code: "TD" },
    { name: "République Centrafricaine", code: "CF" },
    { name: "Madagascar", code: "MG" },
    { name: "Guinée", code: "GN" },
    { name: "Maroc", code: "MA" },
    { name: "Algérie", code: "DZ" },
    { name: "Tunisie", code: "TN" },
    { name: "Canada", code: "CA" },
    { name: "Belgique", code: "BE" },
    { name: "Suisse", code: "CH" },
    { name: "États-Unis", code: "US" },
];

const form = useForm({
    account_type: 'individual',
    name: '',
    email: '',
    phone: '',
    
    // Company specific fields
    business_type: 'real_estate',
    legal_name: '',
    registration_number: '',
    tax_id: '',
    country: 'CM',
    address: '',
    city: '',
    postal_code: '',
    legal_representative_name: '',
    legal_representative_id_number: '',
    
    // File uploads
    company_logo: null,
    certificate_of_incorporation: null,
    tax_registration_document: null,
    representative_id_document: null,
    proof_of_address: null,
});

// Drag and drop states
const dragStates = ref({
    company_logo: false,
    certificate_of_incorporation: false,
    tax_registration_document: false,
    representative_id_document: false,
    proof_of_address: false,
});

const isCopied = ref(false);

const handleLogoChange = (e) => {
    form.company_logo = e.target.files[0];
};

const handleFileChange = (field, e) => {
    form[field] = e.target.files[0];
};

const handleDragOver = (field, e) => {
    e.preventDefault();
    dragStates.value[field] = true;
};

const handleDragLeave = (field, e) => {
    e.preventDefault();
    dragStates.value[field] = false;
};

const handleDrop = (field, e) => {
    e.preventDefault();
    dragStates.value[field] = false;
    if (e.dataTransfer.files && e.dataTransfer.files[0]) {
        form[field] = e.dataTransfer.files[0];
    }
};

const removeFile = (field) => {
    form[field] = null;
};

const copyPassword = (text) => {
    navigator.clipboard.writeText(text).then(() => {
        isCopied.value = true;
        setTimeout(() => {
            isCopied.value = false;
        }, 2000);
    });
};

const submit = () => {
    form.post(route('superadmin.accounts.store'), {
        onSuccess: () => {
            form.reset();
        }
    });
};

const getFileName = (file) => {
    if (!file) return '';
    return file.name.length > 25 ? file.name.substring(0, 22) + '...' : file.name;
};

const getFileSize = (file) => {
    if (!file) return '';
    const sizeInMb = file.size / (1024 * 1024);
    return sizeInMb.toFixed(2) + ' MB';
};
</script>

<template>
    <Head title="Créer un Compte | CPanel" />

    <SuperAdminLayout>
        <div class="space-y-8 page-entrance">
            <!-- Header section -->
            <div>
                <h2 class="text-2xl font-black tracking-tight text-[var(--text-main)]">Création Directe de Compte</h2>
                <p class="text-sm text-[var(--text-muted)] mt-1">
                    Enregistrez une entreprise ou un particulier manuellement. Un mot de passe temporaire sera généré, affiché à l'écran et envoyé par e-mail.
                </p>
            </div>

            <!-- Credentials Display Card (shown on success) -->
            <div v-if="$page.props.flash?.temp_password" class="max-w-4xl bg-emerald-500/10 border-2 border-emerald-500/30 rounded-3xl p-6 shadow-xl relative overflow-hidden page-entrance">
                <div class="absolute -right-10 -bottom-10 opacity-10 text-emerald-500">
                    <i class="fa-solid fa-shield-halved text-[150px]"></i>
                </div>
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative z-10">
                    <div class="space-y-2">
                        <span class="px-3 py-1 bg-emerald-500/20 text-emerald-500 border border-emerald-500/30 text-[9px] font-black rounded-lg uppercase tracking-wider">
                            Compte créé avec succès
                        </span>
                        <h3 class="text-lg font-extrabold text-[var(--text-main)]">Identifiants de Connexion de {{ $page.props.flash.created_name }}</h3>
                        <p class="text-xs text-[var(--text-muted)] font-medium">Copiez les identifiants ci-dessous pour les transmettre à l'utilisateur.</p>
                        
                        <div class="flex flex-col sm:flex-row gap-4 mt-4 bg-[var(--bg-input)] border border-[var(--border-color)]/40 p-4 rounded-2xl">
                            <div>
                                <span class="block text-[9px] uppercase font-black text-[var(--text-muted)] tracking-wider">E-mail</span>
                                <span class="text-xs font-bold text-[var(--text-main)] font-mono">{{ $page.props.flash.created_email }}</span>
                            </div>
                            <div class="sm:border-l border-[var(--border-color)]/30 sm:pl-4">
                                <span class="block text-[9px] uppercase font-black text-[var(--text-muted)] tracking-wider">Mot de passe temporaire</span>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-xs font-bold text-indigo-400 font-mono select-all bg-indigo-500/5 px-2 py-0.5 rounded border border-indigo-500/10">
                                        {{ $page.props.flash.temp_password }}
                                    </span>
                                    <button 
                                        @click="copyPassword($page.props.flash.temp_password)"
                                        class="text-[10px] bg-indigo-500 hover:bg-indigo-600 text-white font-extrabold px-2.5 py-1 rounded-lg transition-colors flex items-center gap-1 active:scale-95"
                                    >
                                        <i class="fa-regular" :class="isCopied ? 'fa-circle-check' : 'fa-copy'"></i>
                                        <span>{{ isCopied ? 'Copié !' : 'Copier' }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Validation Errors Card -->
            <div v-if="Object.keys($page.props.errors).length > 0" class="max-w-4xl p-4 bg-red-500/10 border border-red-500/25 rounded-2xl text-red-500 text-xs font-bold space-y-1 page-entrance">
                <div class="flex items-center gap-2 mb-1">
                    <i class="fa-solid fa-triangle-exclamation text-base"></i>
                    <span>Veuillez corriger les erreurs de validation suivantes :</span>
                </div>
                <ul class="list-disc list-inside text-[11px] font-semibold pl-1 space-y-0.5">
                    <li v-for="(err, key) in $page.props.errors" :key="key">{{ err }}</li>
                </ul>
            </div>

            <!-- Main Form Card with premium Glassmorphism styling -->
            <div class="max-w-4xl bg-[var(--bg-card)] border border-[var(--border-color)] rounded-3xl p-8 shadow-[var(--card-shadow)] relative overflow-hidden glass-effect">
                <form @submit.prevent="submit" class="space-y-8">
                    
                    <!-- Account Type Selector Tabs -->
                    <div class="space-y-3">
                        <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)]">Type de Compte</label>
                        <div class="flex bg-[var(--bg-input)] p-1.5 rounded-2xl border border-[var(--border-color)] max-w-md">
                            <button 
                                type="button"
                                @click="form.account_type = 'individual'"
                                class="flex-1 py-3 text-xs font-black rounded-xl transition-all uppercase tracking-wider flex items-center justify-center gap-2"
                                :class="form.account_type === 'individual' ? 'bg-gradient-to-r from-indigo-600 to-indigo-500 text-white shadow-md' : 'text-[var(--text-muted)] hover:text-[var(--text-main)]'"
                            >
                                <i class="fa-solid fa-user"></i>
                                <span>Particulier (Bailleur)</span>
                            </button>
                            <button 
                                type="button"
                                @click="form.account_type = 'company'"
                                class="flex-1 py-3 text-xs font-black rounded-xl transition-all uppercase tracking-wider flex items-center justify-center gap-2"
                                :class="form.account_type === 'company' ? 'bg-gradient-to-r from-indigo-600 to-indigo-500 text-white shadow-md' : 'text-[var(--text-muted)] hover:text-[var(--text-main)]'"
                            >
                                <i class="fa-solid fa-building"></i>
                                <span>Entreprise / Agence</span>
                            </button>
                        </div>
                    </div>

                    <hr class="border-[var(--border-color)]/30" />

                    <!-- Primary Admin Account Details -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-black text-[var(--text-main)] uppercase tracking-wider">Informations de Connexion Administrateur</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Nom Complet</label>
                                <input 
                                    type="text" 
                                    v-model="form.name"
                                    required
                                    placeholder="Ex: Jean Dupont"
                                    class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] placeholder-slate-500 focus:border-indigo-500 outline-none transition-all"
                                />
                                <span v-if="form.errors.name" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ form.errors.name }}</span>
                            </div>
                            
                            <div>
                                <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Adresse E-mail</label>
                                <input 
                                    type="email" 
                                    v-model="form.email"
                                    required
                                    placeholder="Ex: jean.dupont@email.com"
                                    class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] placeholder-slate-500 focus:border-indigo-500 outline-none transition-all"
                                />
                                <span v-if="form.errors.email" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ form.errors.email }}</span>
                            </div>

                            <div>
                                <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Téléphone</label>
                                <input 
                                    type="text" 
                                    v-model="form.phone"
                                    required
                                    placeholder="Ex: +237 677 000 000"
                                    class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] placeholder-slate-500 focus:border-indigo-500 outline-none transition-all"
                                />
                                <span v-if="form.errors.phone" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ form.errors.phone }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Company Specific Section (Hidden for Individual) -->
                    <div v-if="form.account_type === 'company'" class="space-y-6 page-entrance">
                        <hr class="border-[var(--border-color)]/30" />
                        <h4 class="text-sm font-black text-[var(--text-main)] uppercase tracking-wider">Dossier et Coordonnées de l'Entreprise</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Nom Légal de l'Entreprise</label>
                                <input 
                                    type="text" 
                                    v-model="form.legal_name"
                                    required
                                    placeholder="Ex: Property Development SARL"
                                    class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] placeholder-slate-500 focus:border-indigo-500 outline-none transition-all"
                                />
                                <span v-if="form.errors.legal_name" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ form.errors.legal_name }}</span>
                            </div>

                            <div>
                                <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Secteur d'Activité</label>
                                <select 
                                    v-model="form.business_type"
                                    class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] focus:border-indigo-500 outline-none transition-all cursor-pointer font-bold"
                                >
                                    <option value="real_estate">Agence Immobilière / Promoteur</option>
                                    <option value="hotel">Gestion Hôtelière / Résidence</option>
                                </select>
                                <span v-if="form.errors.business_type" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ form.errors.business_type }}</span>
                            </div>

                            <!-- Logo drop zone -->
                            <div>
                                <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Logo de l'entreprise (Optionnel)</label>
                                <div 
                                    class="relative border border-dashed rounded-2xl p-4 flex flex-col items-center justify-center min-h-[50px] transition-all cursor-pointer bg-[var(--bg-input)]/40 hover:bg-[var(--bg-input)]"
                                    :class="[
                                        dragStates.company_logo ? 'border-indigo-500 bg-indigo-500/5' : 'border-[var(--border-color)]',
                                        form.company_logo ? 'border-emerald-500 bg-emerald-500/5' : ''
                                    ]"
                                    @dragover="handleDragOver('company_logo', $event)"
                                    @dragleave="handleDragLeave('company_logo', $event)"
                                    @drop="handleDrop('company_logo', $event)"
                                    @click="$refs.logoInput.click()"
                                >
                                    <input 
                                        type="file" 
                                        ref="logoInput"
                                        accept="image/*"
                                        @change="handleLogoChange"
                                        class="hidden"
                                    />
                                    <div class="flex items-center gap-3 w-full" v-if="form.company_logo">
                                        <div class="h-8 w-8 rounded-xl bg-emerald-500/10 border border-emerald-500/25 flex items-center justify-center text-emerald-400">
                                            <i class="fa-solid fa-circle-check"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-[11px] font-bold text-[var(--text-main)] truncate">{{ getFileName(form.company_logo) }}</p>
                                            <p class="text-[9px] text-[var(--text-muted)] font-mono font-bold mt-0.5">{{ getFileSize(form.company_logo) }}</p>
                                        </div>
                                        <button type="button" @click.stop="removeFile('company_logo')" class="text-slate-500 hover:text-red-500 transition-colors p-1">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </div>
                                    <div class="flex items-center gap-3 w-full justify-center py-1 text-slate-500" v-else>
                                        <i class="fa-solid fa-cloud-arrow-up text-sm text-indigo-500"></i>
                                        <span class="text-[10px] font-bold">Sélectionner ou glisser</span>
                                    </div>
                                </div>
                                <span v-if="form.errors.company_logo" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ form.errors.company_logo }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Numéro d'Enregistrement (RCCM)</label>
                                <input 
                                    type="text" 
                                    v-model="form.registration_number"
                                    required
                                    placeholder="Ex: RC/DLA/2026/B/1234"
                                    class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] placeholder-slate-500 focus:border-indigo-500 outline-none transition-all"
                                />
                                <span v-if="form.errors.registration_number" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ form.errors.registration_number }}</span>
                            </div>

                            <div>
                                <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Identifiant Fiscal Unique (NUI / IFU)</label>
                                <input 
                                    type="text" 
                                    v-model="form.tax_id"
                                    required
                                    placeholder="Ex: M012345678901Z"
                                    class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] placeholder-slate-500 focus:border-indigo-500 outline-none transition-all"
                                />
                                <span v-if="form.errors.tax_id" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ form.errors.tax_id }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div>
                                <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Pays d'Exploitation</label>
                                <select 
                                    v-model="form.country"
                                    class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] focus:border-indigo-500 outline-none transition-all cursor-pointer font-bold"
                                >
                                    <option v-for="c in countries" :key="c.code" :value="c.code">{{ c.name }} ({{ c.code }})</option>
                                </select>
                                <span v-if="form.errors.country" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ form.errors.country }}</span>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Adresse</label>
                                <input 
                                    type="text" 
                                    v-model="form.address"
                                    required
                                    placeholder="Ex: Rue 123, Quartier Akwa"
                                    class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] placeholder-slate-500 focus:border-indigo-500 outline-none transition-all"
                                />
                                <span v-if="form.errors.address" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ form.errors.address }}</span>
                            </div>

                            <div>
                                <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Ville</label>
                                <input 
                                    type="text" 
                                    v-model="form.city"
                                    required
                                    placeholder="Ex: Douala"
                                    class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] placeholder-slate-500 focus:border-indigo-500 outline-none transition-all"
                                />
                                <span v-if="form.errors.city" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ form.errors.city }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Code Postal</label>
                                <input 
                                    type="text" 
                                    v-model="form.postal_code"
                                    required
                                    placeholder="Ex: BP 1234"
                                    class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] placeholder-slate-500 focus:border-indigo-500 outline-none transition-all"
                                />
                                <span v-if="form.errors.postal_code" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ form.errors.postal_code }}</span>
                            </div>

                            <div>
                                <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Nom du Représentant Légal</label>
                                <input 
                                    type="text" 
                                    v-model="form.legal_representative_name"
                                    required
                                    placeholder="Ex: Robert Parker"
                                    class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] placeholder-slate-500 focus:border-indigo-500 outline-none transition-all"
                                />
                                <span v-if="form.errors.legal_representative_name" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ form.errors.legal_representative_name }}</span>
                            </div>

                            <div>
                                <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Numéro de pièce d'identité du représentant</label>
                                <input 
                                    type="text" 
                                    v-model="form.legal_representative_id_number"
                                    required
                                    placeholder="Ex: CNI / Passeport"
                                    class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] placeholder-slate-500 focus:border-indigo-500 outline-none transition-all"
                                />
                                <span v-if="form.errors.legal_representative_id_number" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ form.errors.legal_representative_id_number }}</span>
                            </div>
                        </div>

                        <!-- Required legal documents uploads slot -->
                        <div class="space-y-4">
                            <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)]">Documents Légaux Obligatoires (PDF, JPG, PNG)</label>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Cert of Incorporation -->
                                <div class="bg-[var(--bg-input)]/40 border border-[var(--border-color)] rounded-2xl p-4 flex flex-col justify-between min-h-[110px] relative">
                                    <span class="text-[10px] uppercase font-black text-[var(--text-main)] mb-2">1. Registre du commerce (RCCM) *</span>
                                    <div 
                                        class="border border-dashed rounded-xl p-3 flex flex-col items-center justify-center transition-all cursor-pointer hover:bg-[var(--bg-input)]"
                                        :class="[
                                            dragStates.certificate_of_incorporation ? 'border-indigo-500 bg-indigo-500/5' : 'border-[var(--border-color)]/60',
                                            form.certificate_of_incorporation ? 'border-emerald-500 bg-emerald-500/5' : ''
                                        ]"
                                        @dragover="handleDragOver('certificate_of_incorporation', $event)"
                                        @dragleave="handleDragLeave('certificate_of_incorporation', $event)"
                                        @drop="handleDrop('certificate_of_incorporation', $event)"
                                        @click="$refs.certInput.click()"
                                    >
                                        <input 
                                            type="file" 
                                            ref="certInput"
                                            required
                                            @change="handleFileChange('certificate_of_incorporation', $event)"
                                            class="hidden"
                                        />
                                        <div class="flex items-center gap-3 w-full" v-if="form.certificate_of_incorporation">
                                            <div class="h-6 w-6 rounded-lg bg-emerald-500/10 border border-emerald-500/25 flex items-center justify-center text-emerald-400">
                                                <i class="fa-solid fa-file-shield text-xs"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-[10px] font-bold text-[var(--text-main)] truncate">{{ getFileName(form.certificate_of_incorporation) }}</p>
                                            </div>
                                            <button type="button" @click.stop="removeFile('certificate_of_incorporation')" class="text-slate-500 hover:text-red-500 transition-colors p-1">
                                                <i class="fa-solid fa-xmark text-xs"></i>
                                            </button>
                                        </div>
                                        <div class="flex items-center gap-2 text-slate-500" v-else>
                                            <i class="fa-solid fa-file-arrow-up text-xs text-indigo-500"></i>
                                            <span class="text-[9px] font-bold">Sélectionner ou déposer</span>
                                        </div>
                                    </div>
                                    <span v-if="form.errors.certificate_of_incorporation" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ form.errors.certificate_of_incorporation }}</span>
                                </div>

                                <!-- Tax Registration -->
                                <div class="bg-[var(--bg-input)]/40 border border-[var(--border-color)] rounded-2xl p-4 flex flex-col justify-between min-h-[110px] relative">
                                    <span class="text-[10px] uppercase font-black text-[var(--text-main)] mb-2">2. Attestation d'immatriculation fiscale *</span>
                                    <div 
                                        class="border border-dashed rounded-xl p-3 flex flex-col items-center justify-center transition-all cursor-pointer hover:bg-[var(--bg-input)]"
                                        :class="[
                                            dragStates.tax_registration_document ? 'border-indigo-500 bg-indigo-500/5' : 'border-[var(--border-color)]/60',
                                            form.tax_registration_document ? 'border-emerald-500 bg-emerald-500/5' : ''
                                        ]"
                                        @dragover="handleDragOver('tax_registration_document', $event)"
                                        @dragleave="handleDragLeave('tax_registration_document', $event)"
                                        @drop="handleDrop('tax_registration_document', $event)"
                                        @click="$refs.taxInput.click()"
                                    >
                                        <input 
                                            type="file" 
                                            ref="taxInput"
                                            required
                                            @change="handleFileChange('tax_registration_document', $event)"
                                            class="hidden"
                                        />
                                        <div class="flex items-center gap-3 w-full" v-if="form.tax_registration_document">
                                            <div class="h-6 w-6 rounded-lg bg-emerald-500/10 border border-emerald-500/25 flex items-center justify-center text-emerald-400">
                                                <i class="fa-solid fa-file-shield text-xs"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-[10px] font-bold text-[var(--text-main)] truncate">{{ getFileName(form.tax_registration_document) }}</p>
                                            </div>
                                            <button type="button" @click.stop="removeFile('tax_registration_document')" class="text-slate-500 hover:text-red-500 transition-colors p-1">
                                                <i class="fa-solid fa-xmark text-xs"></i>
                                            </button>
                                        </div>
                                        <div class="flex items-center gap-2 text-slate-500" v-else>
                                            <i class="fa-solid fa-file-arrow-up text-xs text-indigo-500"></i>
                                            <span class="text-[9px] font-bold">Sélectionner ou déposer</span>
                                        </div>
                                    </div>
                                    <span v-if="form.errors.tax_registration_document" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ form.errors.tax_registration_document }}</span>
                                </div>

                                <!-- Rep ID -->
                                <div class="bg-[var(--bg-input)]/40 border border-[var(--border-color)] rounded-2xl p-4 flex flex-col justify-between min-h-[110px] relative">
                                    <span class="text-[10px] uppercase font-black text-[var(--text-main)] mb-2">3. Pièce d'identité du représentant *</span>
                                    <div 
                                        class="border border-dashed rounded-xl p-3 flex flex-col items-center justify-center transition-all cursor-pointer hover:bg-[var(--bg-input)]"
                                        :class="[
                                            dragStates.representative_id_document ? 'border-indigo-500 bg-indigo-500/5' : 'border-[var(--border-color)]/60',
                                            form.representative_id_document ? 'border-emerald-500 bg-emerald-500/5' : ''
                                        ]"
                                        @dragover="handleDragOver('representative_id_document', $event)"
                                        @dragleave="handleDragLeave('representative_id_document', $event)"
                                        @drop="handleDrop('representative_id_document', $event)"
                                        @click="$refs.repInput.click()"
                                    >
                                        <input 
                                            type="file" 
                                            ref="repInput"
                                            required
                                            @change="handleFileChange('representative_id_document', $event)"
                                            class="hidden"
                                        />
                                        <div class="flex items-center gap-3 w-full" v-if="form.representative_id_document">
                                            <div class="h-6 w-6 rounded-lg bg-emerald-500/10 border border-emerald-500/25 flex items-center justify-center text-emerald-400">
                                                <i class="fa-solid fa-file-shield text-xs"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-[10px] font-bold text-[var(--text-main)] truncate">{{ getFileName(form.representative_id_document) }}</p>
                                            </div>
                                            <button type="button" @click.stop="removeFile('representative_id_document')" class="text-slate-500 hover:text-red-500 transition-colors p-1">
                                                <i class="fa-solid fa-xmark text-xs"></i>
                                            </button>
                                        </div>
                                        <div class="flex items-center gap-2 text-slate-500" v-else>
                                            <i class="fa-solid fa-file-arrow-up text-xs text-indigo-500"></i>
                                            <span class="text-[9px] font-bold">Sélectionner ou déposer</span>
                                        </div>
                                    </div>
                                    <span v-if="form.errors.representative_id_document" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ form.errors.representative_id_document }}</span>
                                </div>

                                <!-- Proof of Address -->
                                <div class="bg-[var(--bg-input)]/40 border border-[var(--border-color)] rounded-2xl p-4 flex flex-col justify-between min-h-[110px] relative">
                                    <span class="text-[10px] uppercase font-black text-[var(--text-main)] mb-2">4. Justificatif de domicile (Optionnel)</span>
                                    <div 
                                        class="border border-dashed rounded-xl p-3 flex flex-col items-center justify-center transition-all cursor-pointer hover:bg-[var(--bg-input)]"
                                        :class="[
                                            dragStates.proof_of_address ? 'border-indigo-500 bg-indigo-500/5' : 'border-[var(--border-color)]/60',
                                            form.proof_of_address ? 'border-emerald-500 bg-emerald-500/5' : ''
                                        ]"
                                        @dragover="handleDragOver('proof_of_address', $event)"
                                        @dragleave="handleDragLeave('proof_of_address', $event)"
                                        @drop="handleDrop('proof_of_address', $event)"
                                        @click="$refs.proofInput.click()"
                                    >
                                        <input 
                                            type="file" 
                                            ref="proofInput"
                                            @change="handleFileChange('proof_of_address', $event)"
                                            class="hidden"
                                        />
                                        <div class="flex items-center gap-3 w-full" v-if="form.proof_of_address">
                                            <div class="h-6 w-6 rounded-lg bg-emerald-500/10 border border-emerald-500/25 flex items-center justify-center text-emerald-400">
                                                <i class="fa-solid fa-file-shield text-xs"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-[10px] font-bold text-[var(--text-main)] truncate">{{ getFileName(form.proof_of_address) }}</p>
                                            </div>
                                            <button type="button" @click.stop="removeFile('proof_of_address')" class="text-slate-500 hover:text-red-500 transition-colors p-1">
                                                <i class="fa-solid fa-xmark text-xs"></i>
                                            </button>
                                        </div>
                                        <div class="flex items-center gap-2 text-slate-500" v-else>
                                            <i class="fa-solid fa-file-arrow-up text-xs text-indigo-500"></i>
                                            <span class="text-[9px] font-bold">Sélectionner ou déposer</span>
                                        </div>
                                    </div>
                                    <span v-if="form.errors.proof_of_address" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ form.errors.proof_of_address }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
 
                    <button 
                        type="submit" 
                        :disabled="form.processing"
                        class="w-full py-4 bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-500 hover:to-indigo-400 text-white rounded-2xl font-bold text-xs shadow-lg shadow-indigo-600/15 active:scale-[0.98] transition-all disabled:opacity-50 flex items-center justify-center gap-2"
                    >
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                        <span>Créer le compte et envoyer l'e-mail de confirmation</span>
                    </button>
                </form>
            </div>
        </div>
    </SuperAdminLayout>
</template>
