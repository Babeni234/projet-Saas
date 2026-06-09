<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import PropertyLayout from '@nangue/Layouts/PropertyLayout.vue';

const form = useForm({
    document_identity: null,
    document_property: null,
    additional_info: '',
});

const submit = () => {
    form.post(route('landlord.verification.store'), {
        forceFormData: true, // Important pour l'envoi de fichiers
        onSuccess: () => {
            // Gérer le succès, par exemple afficher un message ou rediriger
            console.log('Documents soumis avec succès !');
        },
        onError: (errors) => {
            console.error('Erreur lors de la soumission :', errors);
        },
    });
};
</script>

<template>
    <Head title="Vérification Bailleur" />

    <PropertyLayout
        role="particulier"
        title="Devenir Bailleur"
        subtitle="Soumettez vos informations pour accéder à l'espace bailleur"
    >
        <div class="imo-page">
            <!-- Bouton de déconnexion (Ferme la session actuelle) -->
            <div class="flex justify-end mb-6">
                <Link 
                    :href="route('logout')" 
                    method="post" 
                    as="button" 
                    class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 shadow-sm transition-all"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Déconnexion
                </Link>
            </div>

            <div class="imo-section">
                <div class="imo-section-header">
                    <h2 class="imo-section-heading">Vérification de votre statut de Bailleur</h2>
                    <p class="text-sm text-slate-500">
                        Pour accéder à l'espace bailleur, veuillez fournir les documents nécessaires.
                        Votre demande sera examinée par notre équipe.
                    </p>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="imo-panel">
                        <h3 class="font-semibold text-slate-900">Pièce d'identité</h3>
                        <p class="mt-2 text-sm text-slate-600">
                            Veuillez télécharger une copie de votre pièce d'identité (carte d'identité, passeport, permis de conduire).
                        </p>
                        <input
                            type="file"
                            @input="form.document_identity = $event.target.files[0]"
                            class="mt-2 block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100"
                        />
                        <div v-if="form.errors.document_identity" class="text-red-500 text-sm mt-1">{{ form.errors.document_identity }}</div>
                    </div>

                    <div class="imo-panel">
                        <h3 class="font-semibold text-slate-900">Preuve de propriété</h3>
                        <p class="mt-2 text-sm text-slate-600">
                            Veuillez télécharger un document attestant de votre propriété (acte de propriété, taxe foncière, etc.).
                        </p>
                        <input
                            type="file"
                            @input="form.document_property = $event.target.files[0]"
                            class="mt-2 block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100"
                        />
                        <div v-if="form.errors.document_property" class="text-red-500 text-sm mt-1">{{ form.errors.document_property }}</div>
                    </div>

                    <div class="imo-panel">
                        <h3 class="font-semibold text-slate-900">Informations supplémentaires (facultatif)</h3>
                        <textarea v-model="form.additional_info" rows="4" class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                    </div>

                    <button type="submit" :disabled="form.processing" class="imo-btn-primary">Soumettre pour vérification</button>
                </form>
            </div>
        </div>
    </PropertyLayout>
</template>