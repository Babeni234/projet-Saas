<template>
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 p-8">
            <h1 class="text-3xl font-bold text-slate-900 mb-2">{{ title }}</h1>
            <p class="text-slate-500">Complétez tous les champs obligatoires pour continuer</p>
        </div>

        <!-- Form -->
        <form @submit.prevent="submitForm" class="space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 p-8">
                <h2 class="text-xl font-bold text-slate-900 mb-6 pb-4 border-b border-slate-200">Informations de Base</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Nom de l'Agence <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="formData.name"
                            type="text"
                            required
                            placeholder="Ex: Agence Principale"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                        />
                        <span v-if="errors.name" class="text-red-500 text-xs mt-1">{{ errors.name[0] }}</span>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Code Agence <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="formData.code"
                            type="text"
                            required
                            placeholder="Ex: AG001"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                        />
                        <span v-if="errors.code" class="text-red-500 text-xs mt-1">{{ errors.code[0] }}</span>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                        <textarea
                            v-model="formData.description"
                            rows="3"
                            placeholder="Description détaillée de l'agence..."
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                        ></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Statut <span class="text-red-500">*</span>
                        </label>
                        <select
                            v-model="formData.status"
                            required
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                        >
                            <option value="active">Actif</option>
                            <option value="inactive">Inactif</option>
                            <option value="suspended">Suspendu</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Date d'Établissement</label>
                        <input
                            v-model="formData.establishment_date"
                            type="date"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                        />
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 p-8">
                <h2 class="text-xl font-bold text-slate-900 mb-6 pb-4 border-b border-slate-200">Informations de Contact</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                        <input
                            v-model="formData.email"
                            type="email"
                            placeholder="contact@agence.com"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                        />
                        <span v-if="errors.email" class="text-red-500 text-xs mt-1">{{ errors.email[0] }}</span>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Téléphone</label>
                        <input
                            v-model="formData.phone"
                            type="tel"
                            placeholder="+33 1 23 45 67 89"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Fax</label>
                        <input
                            v-model="formData.fax"
                            type="tel"
                            placeholder="+33 1 23 45 67 89"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nombre d'Employés</label>
                        <input
                            v-model.number="formData.employee_count"
                            type="number"
                            min="0"
                            placeholder="0"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                        />
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 p-8">
                <h2 class="text-xl font-bold text-slate-900 mb-6 pb-4 border-b border-slate-200">Localisation et Adresse</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Adresse</label>
                        <input
                            v-model="formData.address"
                            type="text"
                            placeholder="123 Rue de l'Agence"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Ville</label>
                        <input
                            v-model="formData.city"
                            type="text"
                            placeholder="Paris"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Code Postal</label>
                        <input
                            v-model="formData.postal_code"
                            type="text"
                            placeholder="75000"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Pays</label>
                        <input
                            v-model="formData.country"
                            type="text"
                            placeholder="France"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Latitude</label>
                        <input
                            v-model.number="formData.latitude"
                            type="number"
                            step="0.00001"
                            placeholder="48.8566"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Longitude</label>
                        <input
                            v-model.number="formData.longitude"
                            type="number"
                            step="0.00001"
                            placeholder="2.3522"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                        />
                    </div>
                </div>
            </div>

            <!-- Manager Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/50 p-8">
                <h2 class="text-xl font-bold text-slate-900 mb-6 pb-4 border-b border-slate-200">Informations du Responsable</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nom du Responsable</label>
                        <input
                            v-model="formData.manager_name"
                            type="text"
                            placeholder="Jean Dupont"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Email du Responsable</label>
                        <input
                            v-model="formData.manager_email"
                            type="email"
                            placeholder="manager@agence.com"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                        />
                        <span v-if="errors.manager_email" class="text-red-500 text-xs mt-1">{{ errors.manager_email[0] }}</span>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Téléphone du Responsable</label>
                        <input
                            v-model="formData.manager_phone"
                            type="tel"
                            placeholder="+33 6 12 34 56 78"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                        />
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex gap-4 justify-end">
                <Link
                    :href="route('agencies.index')"
                    class="px-8 py-3 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition-all duration-200 font-medium"
                >
                    Annuler
                </Link>
                <button
                    type="submit"
                    :disabled="isSubmitting"
                    class="px-8 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:shadow-lg hover:shadow-blue-500/30 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 font-medium"
                >
                    <span v-if="!isSubmitting">{{ agency ? 'Mettre à Jour' : 'Créer l\'Agence' }}</span>
                    <span v-else class="inline-flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        En cours...
                    </span>
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { usePage, router as inertiaRouter, Link } from '@inertiajs/vue3';
import { RouterLink } from 'vue-router';

const page = usePage();
const agency = ref(page.props.agency || null);
const title = ref(page.props.title || 'Nouvelle Agence');

const formData = ref({
    name: agency.value?.name || '',
    code: agency.value?.code || '',
    description: agency.value?.description || '',
    email: agency.value?.email || '',
    phone: agency.value?.phone || '',
    fax: agency.value?.fax || '',
    address: agency.value?.address || '',
    city: agency.value?.city || '',
    postal_code: agency.value?.postal_code || '',
    country: agency.value?.country || '',
    latitude: agency.value?.latitude || null,
    longitude: agency.value?.longitude || null,
    manager_name: agency.value?.manager_name || '',
    manager_email: agency.value?.manager_email || '',
    manager_phone: agency.value?.manager_phone || '',
    status: agency.value?.status || 'active',
    employee_count: agency.value?.employee_count || 0,
    establishment_date: agency.value?.establishment_date || '',
});

const errors = ref({});
const isSubmitting = ref(false);

const submitForm = async () => {
    isSubmitting.value = true;
    errors.value = {};

    try {
        const method = agency.value ? 'PUT' : 'POST';
        const url = agency.value ? route('agencies.update', agency.value.id) : route('agencies.store');

        const response = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
            },
            body: JSON.stringify(formData.value),
        });

        if (!response.ok) {
            const data = await response.json();
            if (data.errors) {
                errors.value = data.errors;
            }
            return;
        }

        inertiaRouter.visit(route('agencies.index'));
    } catch (error) {
        console.error('Erreur lors de la soumission:', error);
    } finally {
        isSubmitting.value = false;
    }
};
</script>
