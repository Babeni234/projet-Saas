<script setup>
import SuperAdminLayout from '../layouts/SuperAdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed, inject } from 'vue';

// Inject theme state
const theme = inject('theme');

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

// File change handlers
const handleLogoChange = (e) => {
    form.company_logo = e.target.files[0];
};

const handleFileChange = (field, e) => {
    form[field] = e.target.files[0];
};

// Form submit
const submit = () => {
    form.post(route('superadmin.accounts.store'), {
        onSuccess: () => {
            form.reset();
        }
    });
};
</script>

<template>
    <Head title="Créer un Compte" />

    <SuperAdminLayout>
        <div class="space-y-8 page-entrance">
            <!-- Header section -->
            <div>
                <h2 class="text-2xl font-black tracking-tight text-[var(--text-main)]">Création Directe de Compte</h2>
                <p class="text-sm text-[var(--text-muted)] mt-1">
                    Enregistrez une entreprise ou un particulier manuellement. Un mot de passe temporaire sera généré et envoyé automatiquement par email.
                </p>
            </div>

            <!-- Main Form Card -->
            <div class="max-w-4xl bg-[var(--bg-card)] border border-[var(--border-color)] rounded-3xl p-8 shadow-[var(--card-shadow)] relative overflow-hidden">
                <form @submit.prevent="submit" class="space-y-8">
                    
                    <!-- Account Type Selector Tabs -->
                    <div class="space-y-3">
                        <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)]">Type de Compte</label>
                        <div class="flex bg-[var(--bg-input)] p-1.5 rounded-2xl border border-[var(--border-color)]/60 max-w-md">
                            <button 
                                type="button"
                                @click="form.account_type = 'individual'"
                                class="flex-1 py-3 text-xs font-black rounded-xl transition-all uppercase tracking-wider flex items-center justify-center gap-2"
                                :class="form.account_type === 'individual' ? 'bg-gradient-to-r from-indigo-600 to-indigo-500 text-white shadow-md' : 'text-[var(--text-muted)] hover:text-[var(--text-main)]'"
                            >
                                <i class="fa-solid fa-user"></i>
                                <span>Particulier</span>
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
                                    class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] focus:border-indigo-500 outline-none transition-all"
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
                                    class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] focus:border-indigo-500 outline-none transition-all"
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
                                    class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] focus:border-indigo-500 outline-none transition-all"
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
                                    class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] focus:border-indigo-500 outline-none transition-all"
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

                            <div>
                                <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Logo de l'entreprise (Optionnel)</label>
                                <input 
                                    type="file" 
                                    accept="image/*"
                                    @change="handleLogoChange"
                                    class="w-full text-xs text-[var(--text-muted)] file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-indigo-500/10 file:text-indigo-400 hover:file:bg-indigo-500/20 cursor-pointer"
                                />
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
                                    class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] focus:border-indigo-500 outline-none transition-all"
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
                                    class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] focus:border-indigo-500 outline-none transition-all"
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
                                    class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] focus:border-indigo-500 outline-none transition-all"
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
                                    class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] focus:border-indigo-500 outline-none transition-all"
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
                                    class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] focus:border-indigo-500 outline-none transition-all"
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
                                    class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] focus:border-indigo-500 outline-none transition-all"
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
                                    class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] focus:border-indigo-500 outline-none transition-all"
                                />
                                <span v-if="form.errors.legal_representative_id_number" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ form.errors.legal_representative_id_number }}</span>
                            </div>
                        </div>

                        <!-- Required legal documents uploads slot -->
                        <div class="space-y-4">
                            <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)]">Documents Légaux Obligatoires (PDF, JPG, PNG)</label>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Cert of Incorporation -->
                                <div class="bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl p-4 flex flex-col justify-between min-h-24">
                                    <span class="text-[10px] uppercase font-black text-[var(--text-main)] mb-2">1. Registre du commerce (RCCM) *</span>
                                    <input 
                                        type="file" 
                                        required
                                        @change="handleFileChange('certificate_of_incorporation', $event)"
                                        class="w-full text-xs text-[var(--text-muted)] file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[9px] file:font-black file:uppercase file:bg-indigo-500/10 file:text-indigo-400 hover:file:bg-indigo-500/20 cursor-pointer"
                                    />
                                    <span v-if="form.errors.certificate_of_incorporation" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ form.errors.certificate_of_incorporation }}</span>
                                </div>

                                <!-- Tax Registration -->
                                <div class="bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl p-4 flex flex-col justify-between min-h-24">
                                    <span class="text-[10px] uppercase font-black text-[var(--text-main)] mb-2">2. Attestation d'immatriculation fiscale *</span>
                                    <input 
                                        type="file" 
                                        required
                                        @change="handleFileChange('tax_registration_document', $event)"
                                        class="w-full text-xs text-[var(--text-muted)] file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[9px] file:font-black file:uppercase file:bg-indigo-500/10 file:text-indigo-400 hover:file:bg-indigo-500/20 cursor-pointer"
                                    />
                                    <span v-if="form.errors.tax_registration_document" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ form.errors.tax_registration_document }}</span>
                                </div>

                                <!-- Rep ID -->
                                <div class="bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl p-4 flex flex-col justify-between min-h-24">
                                    <span class="text-[10px] uppercase font-black text-[var(--text-main)] mb-2">3. Pièce d'identité du représentant *</span>
                                    <input 
                                        type="file" 
                                        required
                                        @change="handleFileChange('representative_id_document', $event)"
                                        class="w-full text-xs text-[var(--text-muted)] file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[9px] file:font-black file:uppercase file:bg-indigo-500/10 file:text-indigo-400 hover:file:bg-indigo-500/20 cursor-pointer"
                                    />
                                    <span v-if="form.errors.representative_id_document" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ form.errors.representative_id_document }}</span>
                                </div>

                                <!-- Proof of Address -->
                                <div class="bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl p-4 flex flex-col justify-between min-h-24">
                                    <span class="text-[10px] uppercase font-black text-[var(--text-main)] mb-2">4. Justificatif de domicile (Optionnel)</span>
                                    <input 
                                        type="file" 
                                        @change="handleFileChange('proof_of_address', $event)"
                                        class="w-full text-xs text-[var(--text-muted)] file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[9px] file:font-black file:uppercase file:bg-indigo-500/10 file:text-indigo-400 hover:file:bg-indigo-500/20 cursor-pointer"
                                    />
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
