<script setup>
import SuperAdminLayout from '../layouts/SuperAdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, inject } from 'vue';

const props = defineProps({
    currentAdmin: {
        type: Object,
        required: true,
    },
    admins: {
        type: Array,
        default: () => [],
    },
});

// Inject active theme
const theme = inject('theme');

// Form for updating current admin credentials
const profileForm = useForm({
    name: props.currentAdmin.name,
    email: props.currentAdmin.email,
    password: '',
    password_confirmation: '',
});

// Form for creating a new Super Admin account
const createAdminForm = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const updateProfile = () => {
    profileForm.post(route('superadmin.profile.update'), {
        onSuccess: () => {
            profileForm.reset('password', 'password_confirmation');
        },
    });
};

const createAdmin = () => {
    createAdminForm.post(route('superadmin.admins.store'), {
        onSuccess: () => {
            createAdminForm.reset();
        },
    });
};

const getInitials = (name) => {
    if (!name) return 'SA';
    return name.split(' ').map(n => n[0]).join('').slice(0, 2).toUpperCase();
};
</script>

<template>
    <Head title="Profil & Administration" />

    <SuperAdminLayout>
        <div class="space-y-8 page-entrance">
            <!-- Header section -->
            <div>
                <h2 class="text-2xl font-black tracking-tight text-[var(--text-main)]">Mon Profil & Administrateurs</h2>
                <p class="text-sm text-[var(--text-muted)] mt-1">
                    Gérez vos informations d'accès de sécurité et créez ou supervisez les autres comptes Super Admin de la plateforme.
                </p>
            </div>

            <!-- Success message toast / banner -->
            <div v-if="$page.props.flash?.success" class="p-4 bg-emerald-500/10 border border-emerald-500/25 rounded-2xl text-emerald-500 text-xs font-bold flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-base"></i>
                <span>{{ $page.props.flash.success }}</span>
            </div>
            
            <div v-if="$page.props.errors && Object.keys($page.props.errors).length > 0" class="p-4 bg-red-500/10 border border-red-500/25 rounded-2xl text-red-500 text-xs font-bold space-y-1">
                <div class="flex items-center gap-2 mb-1">
                    <i class="fa-solid fa-triangle-exclamation text-base"></i>
                    <span>Erreur de validation :</span>
                </div>
                <ul class="list-disc list-inside text-[11px] font-semibold pl-1">
                    <li v-for="(err, key) in $page.props.errors" :key="key">{{ err }}</li>
                </ul>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Col 1: Update Profile (5 cols) -->
                <div class="lg:col-span-5 space-y-8">
                    <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-3xl p-6 shadow-[var(--card-shadow)] space-y-6 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4 opacity-5">
                            <i class="fa-solid fa-user text-9xl"></i>
                        </div>
                        
                        <div>
                            <h3 class="text-base font-extrabold text-[var(--text-main)]">Mes Informations</h3>
                            <p class="text-xs text-[var(--text-muted)] mt-1">Mettez à jour vos identifiants de connexion et de sécurité.</p>
                        </div>

                        <form @submit.prevent="updateProfile" class="space-y-4">
                            <!-- Name -->
                            <div>
                                <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Nom Complet</label>
                                <input 
                                    type="text" 
                                    v-model="profileForm.name"
                                    required
                                    placeholder="Ex: Jean Dupont"
                                    class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] focus:border-indigo-500 outline-none transition-all"
                                />
                                <span v-if="profileForm.errors.name" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ profileForm.errors.name }}</span>
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Adresse E-mail</label>
                                <input 
                                    type="email" 
                                    v-model="profileForm.email"
                                    required
                                    placeholder="Ex: admin@propertyai.com"
                                    class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] focus:border-indigo-500 outline-none transition-all"
                                />
                                <span v-if="profileForm.errors.email" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ profileForm.errors.email }}</span>
                            </div>

                            <hr class="border-[var(--border-color)]/30 my-4" />

                            <!-- New Password -->
                            <div>
                                <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Nouveau Mot de Passe (Optionnel)</label>
                                <input 
                                    type="password" 
                                    v-model="profileForm.password"
                                    placeholder="Laisser vide pour ne pas changer"
                                    class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] focus:border-indigo-500 outline-none transition-all"
                                />
                                <span v-if="profileForm.errors.password" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ profileForm.errors.password }}</span>
                            </div>

                            <!-- Password Confirmation -->
                            <div>
                                <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Confirmer le Mot de Passe</label>
                                <input 
                                    type="password" 
                                    v-model="profileForm.password_confirmation"
                                    placeholder="Confirmer le nouveau mot de passe"
                                    class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] focus:border-indigo-500 outline-none transition-all"
                                />
                            </div>

                            <button 
                                type="submit" 
                                :disabled="profileForm.processing"
                                class="w-full py-3.5 bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-500 hover:to-indigo-400 text-white rounded-2xl font-bold text-xs shadow-lg shadow-indigo-600/10 active:scale-[0.98] transition-all disabled:opacity-50 mt-6 flex items-center justify-center gap-2"
                            >
                                <i class="fa-solid fa-floppy-disk text-xs"></i>
                                <span>Enregistrer les modifications</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Col 2: Multi admins list & Create Admin (7 cols) -->
                <div class="lg:col-span-7 space-y-8">
                    <!-- Create Admin Form -->
                    <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-3xl p-6 shadow-[var(--card-shadow)] space-y-6">
                        <div>
                            <h3 class="text-base font-extrabold text-[var(--text-main)]">Créer un Super Administrateur</h3>
                            <p class="text-xs text-[var(--text-muted)] mt-1">Enregistrez un nouveau compte ayant un accès complet au panneau d'administration global.</p>
                        </div>

                        <form @submit.prevent="createAdmin" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Name -->
                                <div>
                                    <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Nom Complet</label>
                                    <input 
                                        type="text" 
                                        v-model="createAdminForm.name"
                                        required
                                        placeholder="Ex: Marc Dubois"
                                        class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] focus:border-indigo-500 outline-none transition-all"
                                    />
                                    <span v-if="createAdminForm.errors.name" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ createAdminForm.errors.name }}</span>
                                </div>

                                <!-- Email -->
                                <div>
                                    <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Adresse E-mail</label>
                                    <input 
                                        type="email" 
                                        v-model="createAdminForm.email"
                                        required
                                        placeholder="Ex: mdubois@propertyai.com"
                                        class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] focus:border-indigo-500 outline-none transition-all"
                                    />
                                    <span v-if="createAdminForm.errors.email" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ createAdminForm.errors.email }}</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Password -->
                                <div>
                                    <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Mot de Passe</label>
                                    <input 
                                        type="password" 
                                        v-model="createAdminForm.password"
                                        required
                                        placeholder="Min. 8 caractères"
                                        class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] focus:border-indigo-500 outline-none transition-all"
                                    />
                                    <span v-if="createAdminForm.errors.password" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ createAdminForm.errors.password }}</span>
                                </div>

                                <!-- Password Confirmation -->
                                <div>
                                    <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Confirmer le Mot de Passe</label>
                                    <input 
                                        type="password" 
                                        v-model="createAdminForm.password_confirmation"
                                        required
                                        placeholder="Confirmer le mot de passe"
                                        class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] focus:border-indigo-500 outline-none transition-all"
                                    />
                                    <span v-if="createAdminForm.errors.password_confirmation" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ createAdminForm.errors.password_confirmation }}</span>
                                </div>
                            </div>

                            <button 
                                type="submit" 
                                :disabled="createAdminForm.processing"
                                class="w-full py-3.5 bg-gradient-to-r from-cyan-600 to-indigo-600 hover:from-cyan-500 hover:to-indigo-500 text-white rounded-2xl font-bold text-xs shadow-lg shadow-indigo-600/10 active:scale-[0.98] transition-all disabled:opacity-50 mt-4 flex items-center justify-center gap-2"
                            >
                                <i class="fa-solid fa-user-plus text-xs"></i>
                                <span>Créer le compte administrateur</span>
                            </button>
                        </form>
                    </div>

                    <!-- Admins List -->
                    <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-3xl shadow-[var(--card-shadow)] overflow-hidden">
                        <div class="px-6 py-5 border-b border-[var(--border-color)] flex items-center justify-between">
                            <h3 class="text-sm font-extrabold text-[var(--text-main)]">Administrateurs Actifs</h3>
                            <span class="px-2.5 py-1 bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 text-[10px] font-black rounded-lg uppercase">
                                {{ admins.length }} Comptes
                            </span>
                        </div>
                        
                        <div class="divide-y divide-[var(--border-color)]">
                            <div v-for="admin in admins" :key="admin.id" class="p-6 flex items-center justify-between hover:bg-[var(--bg-table-hover)] transition-colors">
                                <div class="flex items-center gap-4 min-w-0">
                                    <div class="h-10 w-10 rounded-2xl bg-indigo-500/10 text-indigo-400 font-extrabold border border-indigo-500/20 text-xs flex items-center justify-center shrink-0 shadow-inner">
                                        {{ getInitials(admin.name) }}
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="text-sm font-extrabold text-[var(--text-main)] truncate flex items-center gap-2">
                                            <span>{{ admin.name }}</span>
                                            <span v-if="admin.id === currentAdmin.id" class="px-2 py-0.5 rounded-full text-[8px] font-black uppercase bg-indigo-500/15 text-indigo-400 border border-indigo-500/30">Moi</span>
                                        </h4>
                                        <p class="text-xs text-[var(--text-muted)] truncate mt-0.5 font-semibold">{{ admin.email }}</p>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="inline-block px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider bg-emerald-500/10 text-emerald-500 border border-emerald-500/25">
                                        Actif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </SuperAdminLayout>
</template>
