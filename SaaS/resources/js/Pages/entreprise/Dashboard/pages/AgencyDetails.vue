<template>
    <div class="space-y-8">
        <!-- Premium Header with Actions -->
        <div class="bg-gradient-to-br from-white to-slate-50 rounded-3xl shadow-xl shadow-slate-200/50 p-10 border border-slate-100/50 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-600 rounded-3xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">{{ agency.name }}</h1>
                    <p class="text-slate-500 mt-2 font-medium">Code: <span class="text-blue-600 font-bold">{{ agency.code }}</span></p>
                </div>
            </div>
            <div class="flex gap-4">
                <button
                    @click="openEditModal"
                    class="px-8 py-4 bg-gradient-to-r from-amber-500 to-amber-600 text-white rounded-2xl hover:shadow-2xl hover:shadow-amber-500/40 transition-all duration-300 font-bold transform hover:scale-105"
                >
                    <span class="inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Modifier
                    </span>
                </button>
                <button
                    @click="confirmDelete"
                    class="px-8 py-4 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-2xl hover:shadow-2xl hover:shadow-red-500/40 transition-all duration-300 font-bold transform hover:scale-105"
                >
                    <span class="inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Supprimer
                    </span>
                </button>
                <Link
                    :href="route('agencies.index')"
                    class="px-8 py-4 bg-white border-2 border-slate-300 text-slate-700 rounded-2xl hover:bg-slate-100 hover:border-slate-400 transition-all duration-300 font-bold transform hover:scale-105"
                >
                    Retour
                </Link>
            </div>
        </div>

        <!-- Premium Status Badge -->
        <div class="bg-gradient-to-br from-white to-slate-50 rounded-3xl shadow-xl shadow-slate-200/50 p-8 border border-slate-100/50">
            <div class="flex items-center gap-6">
                <span class="text-slate-600 font-bold text-lg">Statut:</span>
                <span
                    :class="[
                        'inline-block px-6 py-3 rounded-2xl text-lg font-bold shadow-sm',
                        agency.status === 'active' ? 'bg-gradient-to-r from-emerald-100 to-emerald-200 text-emerald-800' :
                        agency.status === 'inactive' ? 'bg-gradient-to-r from-slate-100 to-slate-200 text-slate-800' :
                        'bg-gradient-to-r from-red-100 to-red-200 text-red-800'
                    ]"
                >
                    {{ agency.status === 'active' ? 'Actif' : agency.status === 'inactive' ? 'Inactif' : 'Suspendu' }}
                </span>
            </div>
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Information -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Basic Information -->
                <div class="bg-gradient-to-br from-white to-slate-50 rounded-3xl shadow-xl shadow-slate-200/50 p-10 border border-slate-100/50">
                    <h2 class="text-2xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent mb-8 pb-4 border-b-2 border-slate-200">Informations Générales</h2>
                    
                    <div class="space-y-6">
                        <div v-if="agency.description" class="border-b-2 border-slate-200 pb-6">
                            <p class="text-slate-600 font-bold mb-3">Description</p>
                            <p class="text-slate-700 text-lg">{{ agency.description }}</p>
                        </div>
                        <div>
                            <p class="text-slate-600 font-bold mb-3">Date d'Établissement</p>
                            <p class="text-slate-700 text-lg">{{ agency.establishment_date || '-' }}</p>
                        </div>
                        <div>
                            <p class="text-slate-600 font-bold mb-3">Nombre d'Employés</p>
                            <span class="inline-block px-6 py-3 bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 rounded-2xl text-lg font-bold shadow-sm">{{ agency.employee_count || 0 }}</span>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-gradient-to-br from-white to-slate-50 rounded-3xl shadow-xl shadow-slate-200/50 p-10 border border-slate-100/50">
                    <h2 class="text-2xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent mb-8 pb-4 border-b-2 border-slate-200">Informations de Contact</h2>
                    
                    <div class="space-y-5">
                        <div class="flex items-center gap-4 p-4 bg-white rounded-2xl border border-slate-200 hover:border-blue-300 transition-all duration-300">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <a :href="`mailto:${agency.email}`" class="text-blue-600 hover:text-blue-700 font-semibold text-lg">{{ agency.email || '-' }}</a>
                        </div>
                        <div class="flex items-center gap-4 p-4 bg-white rounded-2xl border border-slate-200 hover:border-blue-300 transition-all duration-300">
                            <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <a :href="`tel:${agency.phone}`" class="text-blue-600 hover:text-blue-700 font-semibold text-lg">{{ agency.phone || '-' }}</a>
                        </div>
                        <div class="flex items-center gap-4 p-4 bg-white rounded-2xl border border-slate-200 hover:border-blue-300 transition-all duration-300">
                            <div class="w-12 h-12 bg-gradient-to-br from-slate-500 to-slate-600 rounded-xl flex items-center justify-center shadow-lg shadow-slate-500/30">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <span class="text-slate-700 font-semibold text-lg">{{ agency.fax || '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="bg-gradient-to-br from-white to-slate-50 rounded-3xl shadow-xl shadow-slate-200/50 p-10 border border-slate-100/50">
                    <h2 class="text-2xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent mb-8 pb-4 border-b-2 border-slate-200">Localisation</h2>
                    
                    <div class="space-y-5">
                        <div class="flex items-start gap-4 p-4 bg-white rounded-2xl border border-slate-200 hover:border-blue-300 transition-all duration-300">
                            <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/30 flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-slate-700 font-semibold text-lg">{{ agency.address || '-' }}</p>
                                <p class="text-slate-700 font-semibold text-lg">{{ agency.postal_code }} {{ agency.city }}</p>
                                <p class="text-slate-700 font-semibold text-lg">{{ agency.country }}</p>
                            </div>
                        </div>
                        <div v-if="agency.latitude && agency.longitude" class="p-4 bg-slate-100 rounded-2xl">
                            <p class="text-slate-600 font-bold mb-2">Coordonnées GPS</p>
                            <p class="text-slate-700 font-medium">Latitude: {{ agency.latitude }}</p>
                            <p class="text-slate-700 font-medium">Longitude: {{ agency.longitude }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-8">
                <!-- Manager Information -->
                <div class="bg-gradient-to-br from-white to-slate-50 rounded-3xl shadow-xl shadow-slate-200/50 p-10 border border-slate-100/50">
                    <h2 class="text-xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent mb-6 pb-4 border-b-2 border-slate-200">Responsable</h2>
                    
                    <div class="space-y-5">
                        <div class="p-4 bg-white rounded-2xl border border-slate-200">
                            <p class="text-slate-600 font-bold text-sm mb-2">Nom</p>
                            <p class="text-slate-700 font-semibold text-lg">{{ agency.manager_name || '-' }}</p>
                        </div>
                        <div class="p-4 bg-white rounded-2xl border border-slate-200">
                            <p class="text-slate-600 font-bold text-sm mb-2">Email</p>
                            <a v-if="agency.manager_email" :href="`mailto:${agency.manager_email}`" class="text-blue-600 hover:text-blue-700 font-semibold text-lg">{{ agency.manager_email }}</a>
                            <span v-else class="text-slate-700 font-semibold text-lg">-</span>
                        </div>
                        <div class="p-4 bg-white rounded-2xl border border-slate-200">
                            <p class="text-slate-600 font-bold text-sm mb-2">Téléphone</p>
                            <a v-if="agency.manager_phone" :href="`tel:${agency.manager_phone}`" class="text-blue-600 hover:text-blue-700 font-semibold text-lg">{{ agency.manager_phone }}</a>
                            <span v-else class="text-slate-700 font-semibold text-lg">-</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-gradient-to-br from-white to-slate-50 rounded-3xl shadow-xl shadow-slate-200/50 p-10 border border-slate-100/50">
                    <h2 class="text-xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent mb-6 pb-4 border-b-2 border-slate-200">Statistiques</h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-white rounded-2xl border border-slate-200">
                            <span class="text-slate-600 font-bold text-sm">Créée le</span>
                            <span class="text-slate-700 font-bold">{{ formatDate(agency.created_at) }}</span>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-white rounded-2xl border border-slate-200">
                            <span class="text-slate-600 font-bold text-sm">Mise à jour</span>
                            <span class="text-slate-700 font-bold">{{ formatDate(agency.updated_at) }}</span>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-white rounded-2xl border border-slate-200">
                            <span class="text-slate-600 font-bold text-sm">ID Agence</span>
                            <span class="text-slate-700 font-bold text-sm">#{{ agency.id }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-3xl border-2 border-blue-200 p-8 shadow-xl shadow-blue-200/50">
                    <h3 class="text-lg font-bold text-blue-900 mb-6">Actions Rapides</h3>
                    <div class="space-y-3">
                        <a
                            href="/"
                            class="block w-full px-6 py-4 bg-white text-blue-600 rounded-2xl hover:bg-blue-50 hover:shadow-lg hover:shadow-blue-500/20 transition-all duration-300 text-center font-bold transform hover:scale-105"
                        >
                            Voir les employés
                        </a>
                        <a
                            href="/"
                            class="block w-full px-6 py-4 bg-white text-blue-600 rounded-2xl hover:bg-blue-50 hover:shadow-lg hover:shadow-blue-500/20 transition-all duration-300 text-center font-bold transform hover:scale-105"
                        >
                            Voir les propriétés
                        </a>
                        <a
                            href="/"
                            class="block w-full px-6 py-4 bg-white text-blue-600 rounded-2xl hover:bg-blue-50 hover:shadow-lg hover:shadow-blue-500/20 transition-all duration-300 text-center font-bold transform hover:scale-105"
                        >
                            Historique d'activité
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <ModalPremium
            :show="showEditModal"
            title="Modifier l'agence"
            subtitle="Modifiez les informations de l'agence"
            size="lg"
            type="default"
            @close="closeEditModal"
        >
            <AgencyFormContent
                :agency="agency"
                :errors="formErrors"
                @submit="handleEditSubmit"
                @cancel="closeEditModal"
            />
        </ModalPremium>

        <!-- Delete Confirmation Modal -->
        <ModalPremium
            :show="showDeleteModal"
            title="Confirmer la suppression"
            subtitle="Êtes-vous sûr de vouloir supprimer cette agence ?"
            size="sm"
            type="warning"
            @close="closeDeleteModal"
        >
            <div class="space-y-4">
                <p class="text-slate-600">Cette action est irréversible. L'agence sera définitivement supprimée de la base de données.</p>
                <div class="flex gap-4 justify-end pt-4">
                    <button
                        @click="closeDeleteModal"
                        class="px-6 py-3 bg-white border-2 border-slate-300 text-slate-700 rounded-2xl hover:bg-slate-100 hover:border-slate-400 transition-all duration-300 font-bold transform hover:scale-105"
                    >
                        Annuler
                    </button>
                    <button
                        @click="confirmDeleteAction"
                        class="px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-2xl hover:shadow-lg hover:shadow-red-500/30 transition-all duration-300 font-bold transform hover:scale-105"
                    >
                        Supprimer
                    </button>
                </div>
            </div>
        </ModalPremium>

        <!-- Notification -->
        <NotificationPremium
            :show="notification.show"
            :type="notification.type"
            :title="notification.title"
            :message="notification.message"
            @close="closeNotification"
        />
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { RouterLink } from 'vue-router';
import { usePage, router as inertiaRouter, Link } from '@inertiajs/vue3';
import ModalPremium from '../../../../components/ModalPremium.vue';
import NotificationPremium from '../../../../components/NotificationPremium.vue';
import AgencyFormContent from '../../../../components/AgencyFormContent.vue';

const page = usePage();
const agency = ref(page.props.agency);

watch(
    () => page.props.agency,
    (value) => {
        if (value) agency.value = value;
    },
);

// Modal states
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const formErrors = ref({});

// Notification state
const notification = ref({
    show: false,
    type: 'success',
    title: '',
    message: ''
});

const formatDate = (date) => {
    if (!date) return '-';
    return new Date(date).toLocaleDateString('fr-FR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

const showNotification = (type, title, message) => {
    notification.value = {
        show: true,
        type,
        title,
        message
    };
};

const closeNotification = () => {
    notification.value.show = false;
};

const openEditModal = () => {
    formErrors.value = {};
    showEditModal.value = true;
};

const closeEditModal = () => {
    showEditModal.value = false;
    formErrors.value = {};
};

const handleEditSubmit = async (formData) => {
    formErrors.value = {};
    try {
        const response = await fetch(route('agencies.update', agency.value.id), {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
            },
            body: JSON.stringify(formData),
        });

        if (response.ok) {
            showNotification('success', 'Succès', 'L\'agence a été mise à jour avec succès');
            closeEditModal();
            // Reload the page to show updated data
            window.location.reload();
        } else {
            const data = await response.json();
            if (response.status === 422 && data.errors) {
                formErrors.value = data.errors;
            }
            showNotification('error', 'Erreur', data.message || 'Une erreur est survenue');
        }
    } catch (error) {
        console.error('Error updating agency:', error);
        showNotification('error', 'Erreur', 'Une erreur est survenue');
    }
};

const confirmDelete = () => {
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
};

const confirmDeleteAction = async () => {
    try {
        const response = await fetch(route('agencies.destroy', agency.value.id), {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
            },
        });

        if (response.ok) {
            showNotification('success', 'Succès', 'L\'agence a été supprimée avec succès');
            closeDeleteModal();
            setTimeout(() => {
                inertiaRouter.visit(route('agencies.index'));
            }, 1500);
        } else {
            showNotification('error', 'Erreur', 'Une erreur est survenue lors de la suppression');
        }
    } catch (error) {
        console.error('Error deleting agency:', error);
        showNotification('error', 'Erreur', 'Une erreur est survenue lors de la suppression');
    }
};
</script>
