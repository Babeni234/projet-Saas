<template>
    <form @submit.prevent="handleSubmit" class="space-y-6">
        <!-- Basic Information -->
        <div class="space-y-4">
            <h3 class="text-lg font-bold text-slate-800 pb-3 border-b-2 border-slate-200">Informations de Base</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        Nom de l'Agence <span class="text-red-500">*</span>
                    </label>
                    <input
                        v-model="formData.name"
                        type="text"
                        required
                        placeholder="Ex: Agence Principale"
                        class="w-full px-5 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 text-slate-700"
                    />
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        Code Agence <span class="text-red-500">*</span>
                    </label>
                    <input
                        v-model="formData.code"
                        type="text"
                        required
                        placeholder="Ex: AG001"
                        class="w-full px-5 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 text-slate-700"
                    />
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Description</label>
                    <textarea
                        v-model="formData.description"
                        rows="3"
                        placeholder="Description détaillée de l'agence..."
                        class="w-full px-5 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 text-slate-700 resize-none"
                    ></textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        Statut <span class="text-red-500">*</span>
                    </label>
                    <select
                        v-model="formData.status"
                        required
                        class="w-full px-5 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 text-slate-700 font-medium cursor-pointer"
                    >
                        <option value="active">Actif</option>
                        <option value="inactive">Inactif</option>
                        <option value="suspended">Suspendu</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Date d'Établissement</label>
                    <input
                        v-model="formData.establishment_date"
                        type="date"
                        class="w-full px-5 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 text-slate-700"
                    />
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="space-y-4">
            <h3 class="text-lg font-bold text-slate-800 pb-3 border-b-2 border-slate-200">Informations de Contact</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Email</label>
                    <input
                        v-model="formData.email"
                        type="email"
                        placeholder="contact@agence.com"
                        class="w-full px-5 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 text-slate-700"
                    />
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Téléphone</label>
                    <input
                        v-model="formData.phone"
                        type="tel"
                        placeholder="+33 1 23 45 67 89"
                        class="w-full px-5 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 text-slate-700"
                    />
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Fax</label>
                    <input
                        v-model="formData.fax"
                        type="tel"
                        placeholder="+33 1 23 45 67 89"
                        class="w-full px-5 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 text-slate-700"
                    />
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nombre d'Employés</label>
                    <input
                        v-model.number="formData.employee_count"
                        type="number"
                        min="0"
                        placeholder="0"
                        class="w-full px-5 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 text-slate-700"
                    />
                </div>
            </div>
        </div>

        <!-- Address Information -->
        <div class="space-y-4">
            <h3 class="text-lg font-bold text-slate-800 pb-3 border-b-2 border-slate-200">Localisation et Adresse</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Adresse</label>
                    <input
                        v-model="formData.address"
                        type="text"
                        placeholder="123 Rue de l'Agence"
                        class="w-full px-5 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 text-slate-700"
                    />
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Ville</label>
                    <input
                        v-model="formData.city"
                        type="text"
                        placeholder="Paris"
                        class="w-full px-5 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 text-slate-700"
                    />
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Code Postal</label>
                    <input
                        v-model="formData.postal_code"
                        type="text"
                        placeholder="75000"
                        class="w-full px-5 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 text-slate-700"
                    />
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Pays</label>
                    <input
                        v-model="formData.country"
                        type="text"
                        placeholder="France"
                        class="w-full px-5 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 text-slate-700"
                    />
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Latitude</label>
                    <input
                        v-model.number="formData.latitude"
                        type="number"
                        step="0.00001"
                        placeholder="48.8566"
                        class="w-full px-5 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 text-slate-700"
                    />
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Longitude</label>
                    <input
                        v-model.number="formData.longitude"
                        type="number"
                        step="0.00001"
                        placeholder="2.3522"
                        class="w-full px-5 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 text-slate-700"
                    />
                </div>
            </div>
        </div>

        <!-- Manager Information -->
        <div class="space-y-4">
            <h3 class="text-lg font-bold text-slate-800 pb-3 border-b-2 border-slate-200">Informations du Responsable</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nom du Responsable</label>
                    <input
                        v-model="formData.manager_name"
                        type="text"
                        placeholder="Jean Dupont"
                        class="w-full px-5 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 text-slate-700"
                    />
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Email du Responsable</label>
                    <input
                        v-model="formData.manager_email"
                        type="email"
                        placeholder="manager@agence.com"
                        class="w-full px-5 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 text-slate-700"
                    />
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Téléphone du Responsable</label>
                    <input
                        v-model="formData.manager_phone"
                        type="tel"
                        placeholder="+33 6 12 34 56 78"
                        class="w-full px-5 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 text-slate-700"
                    />
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex gap-4 justify-end pt-6 border-t-2 border-slate-200">
            <button
                type="button"
                @click="$emit('cancel')"
                class="px-8 py-4 bg-white border-2 border-slate-300 text-slate-700 rounded-2xl hover:bg-slate-100 hover:border-slate-400 transition-all duration-300 font-bold transform hover:scale-105"
            >
                Annuler
            </button>
            <button
                type="submit"
                :disabled="isSubmitting"
                class="px-8 py-4 bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600 text-white rounded-2xl hover:shadow-2xl hover:shadow-blue-500/40 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 font-bold transform hover:scale-105 disabled:hover:scale-100"
            >
                <span v-if="!isSubmitting">{{ agency ? 'Mettre à Jour' : 'Créer l\'Agence' }}</span>
                <span v-else class="inline-flex items-center gap-2">
                    <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    En cours...
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

const handleSubmit = async () => {
    isSubmitting.value = true;
    try {
        emit('submit', formData.value);
    } finally {
        isSubmitting.value = false;
    }
};
</script>
