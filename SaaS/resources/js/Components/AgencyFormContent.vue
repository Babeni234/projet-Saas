<template>
    <form @submit.prevent="handleSubmit" class="space-y-6 select-none">
        
        <!-- Premium 2-Column Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Column 1: Core Agency & General Information -->
            <div class="bg-slate-50/50 border border-slate-100 rounded-2xl p-5 space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2 mb-3 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Informations Générales
                </h3>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nom de l'Agence <span class="text-red-500">*</span></label>
                        <input
                            v-model="formData.name"
                            type="text"
                            required
                            placeholder="Ex: Agence Principale"
                            class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            :class="{'border-red-500 focus:ring-red-500': errors.name}"
                        />
                        <span v-if="errors.name" class="text-red-500 text-[11px] mt-1 block">{{ errors.name[0] }}</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Code Agence <span class="text-red-500">*</span></label>
                        <input
                            v-model="formData.code"
                            type="text"
                            required
                            placeholder="Ex: AG001"
                            class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            :class="{'border-red-500 focus:ring-red-500': errors.code}"
                        />
                        <span v-if="errors.code" class="text-red-500 text-[11px] mt-1 block">{{ errors.code[0] }}</span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Description & Notes</label>
                    <textarea
                        v-model="formData.description"
                        rows="3"
                        placeholder="Notes de service, description de l'agence..."
                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none"
                        :class="{'border-red-500 focus:ring-red-500': errors.description}"
                    ></textarea>
                    <span v-if="errors.description" class="text-red-500 text-[11px] mt-1 block">{{ errors.description[0] }}</span>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Statut opérationnel <span class="text-red-500">*</span></label>
                        <select
                            v-model="formData.status"
                            required
                            class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-slate-700 cursor-pointer"
                            :class="{'border-red-500 focus:ring-red-500': errors.status}"
                        >
                            <option value="active">Actif</option>
                            <option value="inactive">Inactif</option>
                            <option value="suspended">Suspendu</option>
                        </select>
                        <span v-if="errors.status" class="text-red-500 text-[11px] mt-1 block">{{ errors.status[0] }}</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Date d'établissement</label>
                        <input
                            v-model="formData.establishment_date"
                            type="date"
                            class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-slate-700"
                            :class="{'border-red-500 focus:ring-red-500': errors.establishment_date}"
                        />
                        <span v-if="errors.establishment_date" class="text-red-500 text-[11px] mt-1 block">{{ errors.establishment_date[0] }}</span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nombre d'Employés</label>
                    <input
                        v-model.number="formData.employee_count"
                        type="number"
                        min="0"
                        placeholder="Ex: 15"
                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        :class="{'border-red-500 focus:ring-red-500': errors.employee_count}"
                    />
                    <span v-if="errors.employee_count" class="text-red-500 text-[11px] mt-1 block">{{ errors.employee_count[0] }}</span>
                </div>
            </div>

            <!-- Column 2: Location, Contact & Management -->
            <div class="bg-slate-50/50 border border-slate-100 rounded-2xl p-5 space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2 mb-3 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Contact & Localisation
                </h3>

                <!-- Contact details -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Email professionnel</label>
                        <input
                            v-model="formData.email"
                            type="email"
                            placeholder="agence@contact.com"
                            class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            :class="{'border-red-500 focus:ring-red-500': errors.email}"
                        />
                        <span v-if="errors.email" class="text-red-500 text-[11px] mt-1 block">{{ errors.email[0] }}</span>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Téléphone de l'Agence</label>
                        <input
                            v-model="formData.phone"
                            type="tel"
                            placeholder="+33 1 23 45 67 89"
                            class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            :class="{'border-red-500 focus:ring-red-500': errors.phone}"
                        />
                        <span v-if="errors.phone" class="text-red-500 text-[11px] mt-1 block">{{ errors.phone[0] }}</span>
                    </div>
                </div>

                <!-- Address & Geolocation -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Adresse postale</label>
                    <input
                        v-model="formData.address"
                        type="text"
                        placeholder="123 Rue de l'Agence"
                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        :class="{'border-red-500 focus:ring-red-500': errors.address}"
                    />
                    <span v-if="errors.address" class="text-red-500 text-[11px] mt-1 block">{{ errors.address[0] }}</span>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Ville</label>
                        <input
                            v-model="formData.city"
                            type="text"
                            placeholder="Paris"
                            class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            :class="{'border-red-500 focus:ring-red-500': errors.city}"
                        />
                        <span v-if="errors.city" class="text-red-500 text-[11px] mt-1 block">{{ errors.city[0] }}</span>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Code Postal</label>
                        <input
                            v-model="formData.postal_code"
                            type="text"
                            placeholder="75000"
                            class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            :class="{'border-red-500 focus:ring-red-500': errors.postal_code}"
                        />
                        <span v-if="errors.postal_code" class="text-red-500 text-[11px] mt-1 block">{{ errors.postal_code[0] }}</span>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Pays</label>
                        <input
                            v-model="formData.country"
                            type="text"
                            placeholder="France"
                            class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            :class="{'border-red-500 focus:ring-red-500': errors.country}"
                        />
                        <span v-if="errors.country" class="text-red-500 text-[11px] mt-1 block">{{ errors.country[0] }}</span>
                    </div>
                </div>

                <!-- Geolocation Inputs -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Latitude</label>
                        <input
                            v-model.number="formData.latitude"
                            type="number"
                            step="0.00001"
                            placeholder="Ex: 48.8566"
                            class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            :class="{'border-red-500 focus:ring-red-500': errors.latitude}"
                        />
                        <span v-if="errors.latitude" class="text-red-500 text-[11px] mt-1 block">{{ errors.latitude[0] }}</span>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Longitude</label>
                        <input
                            v-model.number="formData.longitude"
                            type="number"
                            step="0.00001"
                            placeholder="Ex: 2.3522"
                            class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            :class="{'border-red-500 focus:ring-red-500': errors.longitude}"
                        />
                        <span v-if="errors.longitude" class="text-red-500 text-[11px] mt-1 block">{{ errors.longitude[0] }}</span>
                    </div>
                </div>

                <!-- Responsible Manager -->
                <div class="border-t border-slate-100 pt-3 mt-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Responsable de l'Agence</label>
                    <div class="grid grid-cols-1 gap-3">
                        <div>
                            <select
                                @change="handleManagerChange"
                                class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-slate-700 cursor-pointer"
                            >
                                <option value="">Sélectionner un responsable...</option>
                                <option 
                                    v-for="mgr in eligibleManagers" 
                                    :key="mgr.id" 
                                    :value="mgr.id"
                                    :selected="formData.manager_name === mgr.name || formData.manager_email === mgr.email"
                                >
                                    {{ mgr.name }} ({{ mgr.email }})
                                </option>
                            </select>
                            <span v-if="errors.manager_name" class="text-red-500 text-[11px] mt-1 block">{{ errors.manager_name[0] }}</span>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <input
                                    v-model="formData.manager_email"
                                    type="email"
                                    placeholder="Email du Manager"
                                    disabled
                                    class="w-full px-3 py-2 bg-slate-150 border border-slate-200 rounded-xl text-sm text-slate-500 focus:outline-none"
                                />
                            </div>
                            <div>
                                <input
                                    v-model="formData.manager_phone"
                                    type="tel"
                                    placeholder="Téléphone du Manager"
                                    disabled
                                    class="w-full px-3 py-2 bg-slate-150 border border-slate-200 rounded-xl text-sm text-slate-500 focus:outline-none"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Form Actions -->
        <div class="flex gap-3 justify-end pt-4 border-t border-slate-100 bg-white">
            <button
                type="button"
                @click="$emit('cancel')"
                class="px-5 py-2.5 border border-slate-200 text-slate-700 font-medium rounded-xl hover:bg-slate-100 transition text-sm"
            >
                Annuler
            </button>
            <button
                type="submit"
                :disabled="isSubmitting || !formData.name || !formData.code"
                class="px-6 py-2.5 bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600 text-white font-medium rounded-xl hover:from-blue-600 hover:to-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed shadow shadow-blue-500/20 transition text-sm"
            >
                <span v-if="!isSubmitting">{{ agency ? 'Mettre à jour' : 'Créer l\'Agence' }}</span>
                <span v-else class="inline-flex items-center gap-1.5">
                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Enregistrement...
                </span>
            </button>
        </div>
    </form>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    agency: {
        type: Object,
        default: null
    },
    errors: {
        type: Object,
        default: () => ({})
    },
    eligibleManagers: {
        type: Array,
        default: () => ([])
    }
});

const emit = defineEmits(['submit', 'cancel']);

const formData = ref({
    name: props.agency?.name || '',
    code: props.agency?.code || '',
    description: props.agency?.description || '',
    email: props.agency?.email || '',
    phone: props.agency?.phone || '',
    fax: props.agency?.fax || '',
    address: props.agency?.address || '',
    city: props.agency?.city || '',
    postal_code: props.agency?.postal_code || '',
    country: props.agency?.country || '',
    latitude: props.agency?.latitude || null,
    longitude: props.agency?.longitude || null,
    manager_name: props.agency?.manager_name || '',
    manager_email: props.agency?.manager_email || '',
    manager_phone: props.agency?.manager_phone || '',
    status: props.agency?.status || 'active',
    employee_count: props.agency?.employee_count || 0,
    establishment_date: props.agency?.establishment_date || '',
});

const isSubmitting = ref(false);

watch(() => props.agency, (newAgency) => {
    if (newAgency) {
        formData.value = {
            name: newAgency.name || '',
            code: newAgency.code || '',
            description: newAgency.description || '',
            email: newAgency.email || '',
            phone: newAgency.phone || '',
            fax: newAgency.fax || '',
            address: newAgency.address || '',
            city: newAgency.city || '',
            postal_code: newAgency.postal_code || '',
            country: newAgency.country || '',
            latitude: newAgency.latitude || null,
            longitude: newAgency.longitude || null,
            manager_name: newAgency.manager_name || '',
            manager_email: newAgency.manager_email || '',
            manager_phone: newAgency.manager_phone || '',
            status: newAgency.status || 'active',
            employee_count: newAgency.employee_count || 0,
            establishment_date: newAgency.establishment_date || '',
        };
    }
});

const handleManagerChange = (event) => {
    const selectedId = parseInt(event.target.value);
    const manager = props.eligibleManagers.find(m => m.id === selectedId);
    if (manager) {
        formData.value.manager_name = manager.name;
        formData.value.manager_email = manager.email;
        formData.value.manager_phone = manager.phone || '';
    } else {
        formData.value.manager_name = '';
        formData.value.manager_email = '';
        formData.value.manager_phone = '';
    }
};

const handleSubmit = async () => {
    isSubmitting.value = true;
    try {
        emit('submit', formData.value);
    } finally {
        isSubmitting.value = false;
    }
};
</script>
