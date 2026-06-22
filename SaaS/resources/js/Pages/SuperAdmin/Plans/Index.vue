<script setup>
import SuperAdminLayout from '../layouts/SuperAdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed, inject } from 'vue';

const props = defineProps({
    plans: {
        type: Array,
        default: () => [],
    },
});

// Inject active theme
const theme = inject('theme');

// Tab/filtering of plans table
const selectedAccountTypeTab = ref('company');

const filteredPlans = computed(() => {
    return props.plans.filter(p => p.account_type === selectedAccountTypeTab.value);
});

// Form handling
const newFeature = ref('');

const form = useForm({
    name: '',
    slug: '',
    account_type: 'company',
    price: '',
    max_logements: -1,
    max_locataires: -1,
    max_employees: -1,
    max_agencies: -1,
    max_buildings: -1,
    has_ai: true,
    billing_cycle: 'monthly',
    features: [],
    popular: false,
    color: 'from-indigo-500 to-indigo-650',
});

// Add feature bullet
const addFeature = () => {
    if (newFeature.value.trim()) {
        form.features.push(newFeature.value.trim());
        newFeature.value = '';
    }
};

// Remove feature bullet
const removeFeature = (index) => {
    form.features.splice(index, 1);
};

// Preset colors / gradients matching the theme aesthetics
const gradientPresets = [
    { name: 'Indigo / Bleu', value: 'from-indigo-500 to-indigo-650' },
    { name: 'Violet / Fuchsia', value: 'from-violet-500 to-fuchsia-600' },
    { name: 'Emeraude / Saphir', value: 'from-emerald-500 to-teal-600' },
    { name: 'Ambre / Orange', value: 'from-amber-500 to-orange-600' },
    { name: 'Slate / Ardoise', value: 'from-slate-500 to-slate-600' },
    { name: 'Rose / Rouge', value: 'from-rose-500 to-red-600' },
];

const submit = () => {
    // Basic slug generation if empty
    if (!form.slug) {
        form.slug = form.name.toLowerCase().replace(/[^a-z0-9]+/g, '_').replace(/(^_+|_+$)/g, '');
    }

    form.post(route('superadmin.plans.store'), {
        onSuccess: () => {
            form.reset();
            form.features = [];
        }
    });
};
</script>

<template>
    <Head title="Gestion des Forfaits" />

    <SuperAdminLayout>
        <div class="space-y-8 page-entrance">
            <!-- Header section -->
            <div>
                <h2 class="text-2xl font-black tracking-tight text-[var(--text-main)]">Forfaits d'Abonnement</h2>
                <p class="text-sm text-[var(--text-muted)] mt-1">
                    Gérez et concevez les forfaits d'abonnement pour les entreprises et les particuliers. Vos changements prendront effet instantanément.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Create Plan Form Column -->
                <div class="lg:col-span-1">
                    <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-3xl p-6 shadow-[var(--card-shadow)] space-y-6">
                        <div>
                            <h3 class="text-base font-extrabold text-[var(--text-main)]">Créer un Forfait</h3>
                            <p class="text-xs text-[var(--text-muted)] mt-1">Remplissez les détails du nouveau forfait pour l'ajouter au portail public.</p>
                        </div>

                        <form @submit.prevent="submit" class="space-y-4">
                            <!-- Name -->
                            <div>
                                <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Nom du Forfait</label>
                                <input 
                                    type="text" 
                                    v-model="form.name"
                                    required
                                    placeholder="Ex: Gold Particulier"
                                    class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] focus:border-indigo-500 outline-none transition-all"
                                />
                                <span v-if="form.errors.name" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ form.errors.name }}</span>
                            </div>

                            <!-- Slug / Code -->
                            <div>
                                <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Slug / Identifiant unique (Optionnel)</label>
                                <input 
                                    type="text" 
                                    v-model="form.slug"
                                    placeholder="Ex: gold_particulier"
                                    class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] focus:border-indigo-500 outline-none transition-all"
                                />
                                <span v-if="form.errors.slug" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ form.errors.slug }}</span>
                            </div>

                            <!-- Account Type & Billing Cycle -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Type de Compte</label>
                                    <select 
                                        v-model="form.account_type"
                                        class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] focus:border-indigo-500 outline-none transition-all cursor-pointer font-bold"
                                    >
                                        <option value="company">Entreprise</option>
                                        <option value="individual">Individuel</option>
                                    </select>
                                    <span v-if="form.errors.account_type" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ form.errors.account_type }}</span>
                                </div>
                                <div>
                                    <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Facturation</label>
                                    <select 
                                        v-model="form.billing_cycle"
                                        class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] focus:border-indigo-500 outline-none transition-all cursor-pointer font-bold"
                                    >
                                        <option value="monthly">Mensuel</option>
                                        <option value="yearly">Annuel</option>
                                    </select>
                                    <span v-if="form.errors.billing_cycle" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ form.errors.billing_cycle }}</span>
                                </div>
                            </div>

                            <!-- Price in FCFA -->
                            <div>
                                <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Prix (FCFA)</label>
                                <input 
                                    type="number" 
                                    v-model="form.price"
                                    required
                                    min="0"
                                    placeholder="Ex: 50000"
                                    class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] focus:border-indigo-500 outline-none transition-all font-mono font-bold"
                                />
                                <span v-if="form.errors.price" class="text-[10px] text-red-500 mt-1 font-semibold block">{{ form.errors.price }}</span>
                            </div>

                            <!-- Resource limits -->
                            <div class="grid grid-cols-2 gap-4 border-t border-[var(--border-color)]/30 pt-4">
                                <div>
                                    <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Logements Max</label>
                                    <input 
                                        type="number" 
                                        v-model="form.max_logements"
                                        required
                                        min="-1"
                                        class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] focus:border-indigo-500 outline-none transition-all font-mono"
                                    />
                                    <p class="text-[9px] text-[var(--text-muted)] mt-1 font-semibold">-1 pour illimité</p>
                                </div>
                                <div>
                                    <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Locataires Max</label>
                                    <input 
                                        type="number" 
                                        v-model="form.max_locataires"
                                        required
                                        min="-1"
                                        class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] focus:border-indigo-500 outline-none transition-all font-mono"
                                    />
                                    <p class="text-[9px] text-[var(--text-muted)] mt-1 font-semibold">-1 pour illimité</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-[9px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Collaborateurs</label>
                                    <input 
                                        type="number" 
                                        v-model="form.max_employees"
                                        required
                                        min="-1"
                                        class="w-full px-3 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] focus:border-indigo-500 outline-none transition-all font-mono"
                                    />
                                    <p class="text-[8px] text-[var(--text-muted)] mt-1 font-semibold">-1=illimité, 0=aucun</p>
                                </div>
                                <div>
                                    <label class="block text-[9px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Agences Max</label>
                                    <input 
                                        type="number" 
                                        v-model="form.max_agencies"
                                        required
                                        min="-1"
                                        class="w-full px-3 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] focus:border-indigo-500 outline-none transition-all font-mono"
                                    />
                                    <p class="text-[8px] text-[var(--text-muted)] mt-1 font-semibold">-1=illimité, 0=aucun</p>
                                </div>
                                <div>
                                    <label class="block text-[9px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Bâtiments Max</label>
                                    <input 
                                        type="number" 
                                        v-model="form.max_buildings"
                                        required
                                        min="-1"
                                        class="w-full px-3 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] focus:border-indigo-500 outline-none transition-all font-mono"
                                    />
                                    <p class="text-[8px] text-[var(--text-muted)] mt-1 font-semibold">-1 pour illimité</p>
                                </div>
                            </div>

                            <!-- AI toggle checkbox -->
                            <div class="flex items-center gap-2 py-1">
                                <input 
                                    type="checkbox" 
                                    id="has_ai" 
                                    v-model="form.has_ai"
                                    class="h-4 w-4 rounded border-slate-700 bg-slate-900 text-indigo-600 focus:ring-indigo-500"
                                />
                                <label for="has_ai" class="text-xs font-bold text-[var(--text-main)] cursor-pointer select-none">Activer les fonctionnalités d'IA</label>
                            </div>

                            <!-- Gradient / Color Selection -->
                            <div>
                                <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-2">Thème de Dégradé</label>
                                <select 
                                    v-model="form.color"
                                    class="w-full px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] focus:border-indigo-500 outline-none transition-all cursor-pointer font-bold"
                                >
                                    <option v-for="preset in gradientPresets" :key="preset.value" :value="preset.value">
                                        {{ preset.name }}
                                    </option>
                                </select>
                            </div>

                            <!-- Popular Toggle checkbox -->
                            <div class="flex items-center gap-2 py-2">
                                <input 
                                    type="checkbox" 
                                    id="popular" 
                                    v-model="form.popular"
                                    class="h-4 w-4 rounded border-slate-700 bg-slate-900 text-indigo-600 focus:ring-indigo-500"
                                />
                                <label for="popular" class="text-xs font-bold text-[var(--text-main)] cursor-pointer select-none">Forfait populaire (Recommandé)</label>
                            </div>

                            <hr class="border-[var(--border-color)]/30 my-4" />

                            <!-- Dynamic Features Bullet List Editor -->
                            <div class="space-y-3">
                                <label class="block text-[10px] uppercase font-black tracking-wider text-[var(--text-muted)] mb-1">Avantages & Fonctionnalités</label>
                                <div class="flex gap-2">
                                    <input 
                                        type="text" 
                                        v-model="newFeature"
                                        placeholder="Ex: Rapports financiers avancés"
                                        @keyup.enter.prevent="addFeature"
                                        class="flex-1 px-4 py-3 bg-[var(--bg-input)] border border-[var(--border-color)] rounded-2xl text-xs text-[var(--text-main)] focus:border-indigo-500 outline-none transition-all"
                                    />
                                    <button 
                                        type="button"
                                        @click="addFeature"
                                        class="px-4 bg-[var(--bg-input)] hover:bg-indigo-500 hover:text-white border border-[var(--border-color)] hover:border-indigo-500 rounded-2xl text-xs font-black transition-all flex items-center justify-center"
                                    >
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </div>

                                <!-- Features list visualization -->
                                <ul v-if="form.features.length > 0" class="space-y-2 max-h-40 overflow-y-auto pr-1">
                                    <li 
                                        v-for="(feat, index) in form.features" 
                                        :key="index"
                                        class="flex items-center justify-between bg-indigo-500/5 border border-indigo-500/10 px-3.5 py-2 rounded-xl text-xs"
                                    >
                                        <span class="text-[var(--text-muted)] truncate max-w-[200px]">{{ feat }}</span>
                                        <button 
                                            type="button" 
                                            @click="removeFeature(index)"
                                            class="text-rose-500 hover:text-rose-400 p-1 transition-colors"
                                        >
                                            <i class="fa-solid fa-trash text-[10px]"></i>
                                        </button>
                                    </li>
                                </ul>
                                <p v-else class="text-[10px] text-[var(--text-muted)] italic">Aucune fonctionnalité ajoutée.</p>
                            </div>

                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="w-full py-3.5 bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-500 hover:to-indigo-400 text-white rounded-2xl font-bold text-xs shadow-lg shadow-indigo-600/10 active:scale-[0.98] transition-all disabled:opacity-50 mt-6 flex items-center justify-center gap-2"
                            >
                                <i class="fa-solid fa-check text-xs"></i>
                                <span>Créer le forfait</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Plans Catalog Section -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Tabs for Account Type switching -->
                    <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-3xl p-2 shadow-[var(--card-shadow)] flex gap-2">
                        <button 
                            @click="selectedAccountTypeTab = 'company'"
                            class="flex-1 py-3 text-xs font-black rounded-2xl transition-all uppercase tracking-wider"
                            :class="selectedAccountTypeTab === 'company' ? 'bg-gradient-to-r from-indigo-600 to-indigo-500 text-white shadow-md' : 'text-[var(--text-muted)] hover:bg-[var(--bg-btn-secondary)]'"
                        >
                            Entreprises / Agences
                        </button>
                        <button 
                            @click="selectedAccountTypeTab = 'individual'"
                            class="flex-1 py-3 text-xs font-black rounded-2xl transition-all uppercase tracking-wider"
                            :class="selectedAccountTypeTab === 'individual' ? 'bg-gradient-to-r from-indigo-600 to-indigo-500 text-white shadow-md' : 'text-[var(--text-muted)] hover:bg-[var(--bg-btn-secondary)]'"
                        >
                            Particuliers / Individuels
                        </button>
                    </div>

                    <!-- Plans List cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div 
                            v-for="plan in filteredPlans" 
                            :key="plan.id"
                            class="bg-[var(--bg-card)] border rounded-3xl p-6 shadow-[var(--card-shadow)] relative flex flex-col justify-between"
                            :class="plan.popular ? 'border-amber-400/80 shadow-amber-500/5' : 'border-[var(--border-color)]'"
                        >
                            <span 
                                v-if="plan.popular"
                                class="absolute top-4 right-4 bg-gradient-to-r from-amber-500 to-amber-600 text-white text-[9px] font-black uppercase tracking-wider px-3 py-1 rounded-full shadow-sm"
                            >
                                Populaire
                            </span>

                            <div>
                                <h4 class="text-lg font-black text-[var(--text-main)]">{{ plan.name }}</h4>
                                <p class="text-[10px] text-indigo-400 font-bold font-mono tracking-wide mt-1 uppercase">{{ plan.slug }}</p>
                                
                                <div class="mt-4 flex items-baseline gap-1">
                                    <span class="text-2xl font-black text-amber-600">{{ Number(plan.price).toLocaleString('fr-FR') }}</span>
                                    <span class="text-[10px] text-[var(--text-muted)] font-bold">FCFA / {{ plan.billing_cycle === 'monthly' ? 'mois' : 'an' }}</span>
                                </div>

                                <hr class="border-[var(--border-color)]/30 my-4" />

                                <!-- Resource Limits Badges -->
                                <div class="grid grid-cols-2 gap-2 mb-4 bg-[var(--bg-input)]/20 p-3 rounded-2xl border border-[var(--border-color)]/10">
                                    <span class="text-[10px] text-[var(--text-muted)] font-bold flex items-center gap-1.5">
                                        <i class="fa-solid fa-house text-indigo-500 text-[10px]"></i>
                                        Biens: <strong class="text-[var(--text-main)]">{{ plan.max_logements === -1 ? 'Illimité' : plan.max_logements }}</strong>
                                    </span>
                                    <span class="text-[10px] text-[var(--text-muted)] font-bold flex items-center gap-1.5">
                                        <i class="fa-solid fa-user-group text-indigo-500 text-[10px]"></i>
                                        Locataires: <strong class="text-[var(--text-main)]">{{ plan.max_locataires === -1 ? 'Illimité' : plan.max_locataires }}</strong>
                                    </span>
                                    <span class="text-[10px] text-[var(--text-muted)] font-bold flex items-center gap-1.5">
                                        <i class="fa-solid fa-user-tie text-indigo-500 text-[10px]"></i>
                                        Collab.: <strong class="text-[var(--text-main)]">{{ plan.max_employees === -1 ? 'Illimité' : plan.max_employees }}</strong>
                                    </span>
                                    <span class="text-[10px] text-[var(--text-muted)] font-bold flex items-center gap-1.5">
                                        <i class="fa-solid fa-building text-indigo-500 text-[10px]"></i>
                                        Bâtiments: <strong class="text-[var(--text-main)]">{{ plan.max_buildings === -1 ? 'Illimité' : plan.max_buildings }}</strong>
                                    </span>
                                    <span class="text-[10px] text-[var(--text-muted)] font-bold flex items-center gap-1.5 col-span-2">
                                        <i class="fa-solid fa-robot text-indigo-500 text-[10px]"></i>
                                        Intelligence Artificielle: <strong :class="plan.has_ai ? 'text-emerald-400' : 'text-rose-400'">{{ plan.has_ai ? 'Inclus' : 'Non Inclus' }}</strong>
                                    </span>
                                </div>

                                <hr class="border-[var(--border-color)]/30 my-4" />

                                <!-- Features list bullet -->
                                <ul class="space-y-2 mb-6">
                                    <li 
                                        v-for="(feature, index) in plan.features" 
                                        :key="index"
                                        class="flex items-start gap-2.5 text-xs text-[var(--text-muted)]"
                                    >
                                        <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 shrink-0"></i>
                                        <span>{{ feature }}</span>
                                    </li>
                                </ul>
                            </div>

                            <!-- Styled Button indicator showing its gradient badge -->
                            <div 
                                class="w-full py-3 rounded-2xl text-center font-bold text-xs text-white bg-gradient-to-r"
                                :class="plan.color || 'from-indigo-500 to-indigo-650'"
                            >
                                {{ plan.name }}
                            </div>
                        </div>

                        <div v-if="filteredPlans.length === 0" class="col-span-2 p-12 text-center text-[var(--text-muted)] bg-[var(--bg-card)] border border-[var(--border-color)] rounded-3xl font-bold">
                            Aucun forfait d'abonnement trouvé pour cette catégorie.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </SuperAdminLayout>
</template>
