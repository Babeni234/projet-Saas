<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

const form = useForm({
    title: '',
    description: '',
    property_type: 'apartment',
    transaction_type: 'rent',
    address: '',
    city: '',
    postal_code: '',
    price: '',
    surface: '',
    rooms: '',
    bedrooms: '',
    bathrooms: '',
    furnished: false,
    amenities: [],
    images: [],
    available_from: '',
    charges_included: false,
    deposit: '',
    min_lease_duration: '',
});

const propertyTypes = [
    { value: 'apartment', label: 'Appartement' },
    { value: 'house', label: 'Maison' },
    { value: 'studio', label: 'Studio' },
    { value: 'loft', label: 'Loft' },
    { value: 'villa', label: 'Villa' },
];

const transactionTypes = [
    { value: 'rent', label: 'Location' },
    { value: 'sale', label: 'Vente' },
    { value: 'both', label: 'Location & Vente' },
];

const amenityOptions = [
    { value: 'parking', label: 'Parking' },
    { value: 'balcony', label: 'Balcon' },
    { value: 'terrace', label: 'Terrasse' },
    { value: 'garden', label: 'Jardin' },
    { value: 'pool', label: 'Piscine' },
    { value: 'elevator', label: 'Ascenseur' },
    { value: 'air_conditioning', label: 'Climatisation' },
    { value: 'heating', label: 'Chauffage' },
    { value: 'washer', label: 'Lave-linge' },
    { value: 'dishwasher', label: 'Lave-vaisselle' },
    { value: 'internet', label: 'Internet' },
    { value: 'security', label: 'Sécurité' },
];

const submit = () => {
    form.post(route('immo.property.create'), {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <Head title="Créer une annonce" />

    <PropertyLayout
        role="particulier"
        title="Créer une annonce"
        subtitle="Publiez votre bien sur la plateforme"
    >
        <template #actions>
            <button
                type="button"
                @click="submit"
                :disabled="form.processing"
                class="imo-btn-primary"
            >
                <svg v-if="!form.processing" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <svg v-else class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ form.processing ? 'Publication en cours...' : 'Publier l\'annonce' }}
            </button>
        </template>

        <div class="imo-page">
            <form @submit.prevent="submit" class="space-y-8">
                <!-- Informations générales -->
                <section class="imo-section">
                    <p class="imo-section-title">Informations générales</p>
                    <div class="imo-form-grid">
                        <div class="imo-form-group">
                            <label class="imo-form-label">Titre de l'annonce *</label>
                            <input
                                v-model="form.title"
                                type="text"
                                class="imo-form-input"
                                placeholder="Ex: Appartement T3 lumineux centre-ville"
                                required
                            />
                            <p v-if="form.errors.title" class="imo-form-error">{{ form.errors.title }}</p>
                        </div>

                        <div class="imo-form-group">
                            <label class="imo-form-label">Type de bien *</label>
                            <select v-model="form.property_type" class="imo-form-select" required>
                                <option v-for="type in propertyTypes" :key="type.value" :value="type.value">
                                    {{ type.label }}
                                </option>
                            </select>
                        </div>

                        <div class="imo-form-group">
                            <label class="imo-form-label">Type de transaction *</label>
                            <select v-model="form.transaction_type" class="imo-form-select" required>
                                <option v-for="type in transactionTypes" :key="type.value" :value="type.value">
                                    {{ type.label }}
                                </option>
                            </select>
                        </div>

                        <div class="imo-form-group md:col-span-2">
                            <label class="imo-form-label">Description *</label>
                            <textarea
                                v-model="form.description"
                                rows="4"
                                class="imo-form-textarea"
                                placeholder="Décrivez votre bien en détail..."
                                required
                            ></textarea>
                            <p v-if="form.errors.description" class="imo-form-error">{{ form.errors.description }}</p>
                        </div>
                    </div>
                </section>

                <!-- Localisation -->
                <section class="imo-section">
                    <p class="imo-section-title">Localisation</p>
                    <div class="imo-form-grid">
                        <div class="imo-form-group md:col-span-2">
                            <label class="imo-form-label">Adresse *</label>
                            <input
                                v-model="form.address"
                                type="text"
                                class="imo-form-input"
                                placeholder="123 Rue de la République"
                                required
                            />
                        </div>

                        <div class="imo-form-group">
                            <label class="imo-form-label">Ville *</label>
                            <input
                                v-model="form.city"
                                type="text"
                                class="imo-form-input"
                                placeholder="Paris"
                                required
                            />
                        </div>

                        <div class="imo-form-group">
                            <label class="imo-form-label">Code postal *</label>
                            <input
                                v-model="form.postal_code"
                                type="text"
                                class="imo-form-input"
                                placeholder="75001"
                                required
                            />
                        </div>
                    </div>
                </section>

                <!-- Caractéristiques -->
                <section class="imo-section">
                    <p class="imo-section-title">Caractéristiques</p>
                    <div class="imo-form-grid">
                        <div class="imo-form-group">
                            <label class="imo-form-label">Prix (€) *</label>
                            <input
                                v-model="form.price"
                                type="number"
                                class="imo-form-input"
                                placeholder="1200"
                                required
                            />
                            <p v-if="form.errors.price" class="imo-form-error">{{ form.errors.price }}</p>
                        </div>

                        <div class="imo-form-group">
                            <label class="imo-form-label">Surface (m²) *</label>
                            <input
                                v-model="form.surface"
                                type="number"
                                class="imo-form-input"
                                placeholder="75"
                                required
                            />
                        </div>

                        <div class="imo-form-group">
                            <label class="imo-form-label">Pièces *</label>
                            <input
                                v-model="form.rooms"
                                type="number"
                                class="imo-form-input"
                                placeholder="3"
                                required
                            />
                        </div>

                        <div class="imo-form-group">
                            <label class="imo-form-label">Chambres</label>
                            <input
                                v-model="form.bedrooms"
                                type="number"
                                class="imo-form-input"
                                placeholder="2"
                            />
                        </div>

                        <div class="imo-form-group">
                            <label class="imo-form-label">Salles de bain</label>
                            <input
                                v-model="form.bathrooms"
                                type="number"
                                class="imo-form-input"
                                placeholder="1"
                            />
                        </div>

                        <div class="imo-form-group">
                            <label class="imo-form-label">Meublé</label>
                            <div class="imo-toggle">
                                <input
                                    v-model="form.furnished"
                                    type="checkbox"
                                    id="furnished"
                                    class="imo-toggle-input"
                                />
                                <label for="furnished" class="imo-toggle-label"></label>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Équipements -->
                <section class="imo-section">
                    <p class="imo-section-title">Équipements</p>
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4">
                        <label
                            v-for="amenity in amenityOptions"
                            :key="amenity.value"
                            class="imo-checkbox-card"
                        >
                            <input
                                v-model="form.amenities"
                                type="checkbox"
                                :value="amenity.value"
                                class="sr-only"
                            />
                            <span class="imo-checkbox-indicator"></span>
                            <span class="text-sm font-medium text-slate-700">{{ amenity.label }}</span>
                        </label>
                    </div>
                </section>

                <!-- Détails de location -->
                <section class="imo-section">
                    <p class="imo-section-title">Détails de location</p>
                    <div class="imo-form-grid">
                        <div class="imo-form-group">
                            <label class="imo-form-label">Disponible à partir du</label>
                            <input
                                v-model="form.available_from"
                                type="date"
                                class="imo-form-input"
                            />
                        </div>

                        <div class="imo-form-group">
                            <label class="imo-form-label">Charges incluses</label>
                            <div class="imo-toggle">
                                <input
                                    v-model="form.charges_included"
                                    type="checkbox"
                                    id="charges_included"
                                    class="imo-toggle-input"
                                />
                                <label for="charges_included" class="imo-toggle-label"></label>
                            </div>
                        </div>

                        <div class="imo-form-group">
                            <label class="imo-form-label">Caution (€)</label>
                            <input
                                v-model="form.deposit"
                                type="number"
                                class="imo-form-input"
                                placeholder="2400"
                            />
                        </div>

                        <div class="imo-form-group">
                            <label class="imo-form-label">Durée minimale (mois)</label>
                            <input
                                v-model="form.min_lease_duration"
                                type="number"
                                class="imo-form-input"
                                placeholder="12"
                            />
                        </div>
                    </div>
                </section>

                <!-- Photos avec Drag & Drop -->
                <section class="imo-section">
                    <p class="imo-section-title">Photos</p>
                    <div
                        class="imo-upload-zone relative"
                        @dragover.prevent="(e) => e.currentTarget.classList.add('border-emerald-500', 'bg-emerald-50/50')"
                        @dragleave.prevent="(e) => e.currentTarget.classList.remove('border-emerald-500', 'bg-emerald-50/50')"
                        @drop.prevent="(e) => { e.currentTarget.classList.remove('border-emerald-500', 'bg-emerald-50/50'); const files = Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/')); if (files.length) form.images = [...form.images, ...files]; }"
                    >
                        <input
                            type="file"
                            multiple
                            accept="image/*"
                            @change="(e) => form.images = [...form.images, ...Array.from(e.target.files)]"
                            class="sr-only"
                            id="images"
                        />
                        <label for="images" class="imo-upload-label cursor-pointer">
                            <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="mt-2 text-sm text-slate-600">Cliquez ou glissez-déposez vos photos ici</p>
                            <p class="mt-1 text-xs text-slate-500">PNG, JPG jusqu'à 10MB</p>
                        </label>
                    </div>
                    <div v-if="form.images.length" class="mt-4 grid grid-cols-2 gap-4 sm:grid-cols-4">
                        <div
                            v-for="(image, index) in form.images"
                            :key="index"
                            class="group relative aspect-square overflow-hidden rounded-lg border border-slate-200 transition hover:shadow-lg"
                            draggable="true"
                            @dragstart="(e) => e.dataTransfer.setData('text/plain', index)"
                            @dragover.prevent="(e) => e.currentTarget.classList.add('ring-2', 'ring-emerald-400')"
                            @dragleave="(e) => e.currentTarget.classList.remove('ring-2', 'ring-emerald-400')"
                            @drop.prevent="(e) => { e.currentTarget.classList.remove('ring-2', 'ring-emerald-400'); const fromIdx = +e.dataTransfer.getData('text/plain'); const toIdx = index; if (fromIdx !== toIdx) { const arr = [...form.images]; const [item] = arr.splice(fromIdx, 1); arr.splice(toIdx, 0, item); form.images = arr; } }"
                        >
                            <img :src="URL.createObjectURL(image)" :alt="`Photo ${index + 1}`" class="h-full w-full object-cover" />
                            <div class="absolute inset-0 flex items-center justify-center gap-2 bg-black/40 opacity-0 transition group-hover:opacity-100">
                                <button
                                    type="button"
                                    @click="form.images.splice(index, 1)"
                                    class="rounded-full bg-red-500 p-2 text-white hover:bg-red-600"
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <span class="absolute left-2 top-2 rounded bg-black/60 px-2 py-0.5 text-xs text-white">{{ index + 1 }}</span>
                        </div>
                    </div>
                </section>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-4 border-t border-slate-200 pt-6">
                    <button
                        type="button"
                        @click="$inertia.visit(route('immo.particulier'))"
                        class="imo-btn-secondary"
                    >
                        Annuler
                    </button>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="imo-btn-primary"
                    >
                        <svg v-if="!form.processing" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <svg v-else class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ form.processing ? 'Publication en cours...' : 'Publier l\'annonce' }}
                    </button>
                </div>
            </form>
        </div>
    </PropertyLayout>
</template>
