<template>
    <div class="space-y-8">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
            <div class="enterprise-card group p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm font-semibold uppercase tracking-wider">Total Agences</p>
                        <p class="text-4xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent mt-3">{{ stats.total }}</p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-white to-emerald-50/30 rounded-3xl shadow-xl shadow-emerald-200/50 p-8 border border-emerald-100/50 hover:shadow-2xl hover:shadow-emerald-200/60 transition-all duration-500 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm font-semibold uppercase tracking-wider">Actives</p>
                        <p class="text-4xl font-bold bg-gradient-to-r from-emerald-600 to-emerald-500 bg-clip-text text-transparent mt-3">{{ stats.active }}</p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 via-emerald-600 to-teal-600 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-white to-slate-50 rounded-3xl shadow-xl shadow-slate-200/50 p-8 border border-slate-100/50 hover:shadow-2xl hover:shadow-slate-200/60 transition-all duration-500 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm font-semibold uppercase tracking-wider">Inactives</p>
                        <p class="text-4xl font-bold bg-gradient-to-r from-slate-600 to-slate-500 bg-clip-text text-transparent mt-3">{{ stats.inactive }}</p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-slate-500 via-slate-600 to-gray-600 rounded-2xl flex items-center justify-center shadow-lg shadow-slate-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-white to-red-50/30 rounded-3xl shadow-xl shadow-red-200/50 p-8 border border-red-100/50 hover:shadow-2xl hover:shadow-red-200/60 transition-all duration-500 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm font-semibold uppercase tracking-wider">Suspendues</p>
                        <p class="text-4xl font-bold bg-gradient-to-r from-red-600 to-red-500 bg-clip-text text-transparent mt-3">{{ stats.suspended }}</p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-red-500 via-red-600 to-rose-600 rounded-2xl flex items-center justify-center shadow-lg shadow-red-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Premium Search and Controls -->
        <div class="bg-gradient-to-br from-white to-slate-50 rounded-3xl shadow-xl shadow-slate-200/50 p-8 border border-slate-100/50">
            <div class="flex flex-col lg:flex-row gap-6 items-center justify-between">
                <div class="flex-1 flex gap-4 w-full lg:w-auto">
                    <div class="relative flex-1">
                        <input
                            v-model="filters.search"
                            @input="handleSearch"
                            type="text"
                            placeholder="Rechercher par nom, code, email, ville..."
                            class="w-full px-6 py-4 bg-white border-2 border-slate-200 rounded-2xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 text-slate-700 placeholder-slate-400"
                        />
                        <svg class="absolute right-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <select
                        v-model="filters.status"
                        @change="handleFilter"
                        class="px-6 py-4 bg-white border-2 border-slate-200 rounded-2xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 text-slate-700 font-medium cursor-pointer"
                    >
                        <option value="">Tous les statuts</option>
                        <option value="active">Actif</option>
                        <option value="inactive">Inactif</option>
                        <option value="suspended">Suspendu</option>
                    </select>
                </div>

                <div class="flex gap-4 w-full lg:w-auto">
                    <button
                        @click="openCreateModal"
                        class="flex-1 lg:flex-none px-8 py-4 bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600 text-white rounded-2xl hover:shadow-2xl hover:shadow-blue-500/40 transition-all duration-300 font-semibold text-center transform hover:scale-105"
                    >
                        <span class="inline-flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Nouvelle Agence
                        </span>
                    </button>
                    <button
                        @click="exportData"
                        class="px-8 py-4 bg-white border-2 border-slate-200 text-slate-700 rounded-2xl hover:bg-slate-50 hover:border-slate-300 transition-all duration-300 font-semibold transform hover:scale-105"
                        title="Exporter en CSV"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Premium Agencies Table -->
        <div class="bg-gradient-to-br from-white to-slate-50 rounded-3xl shadow-xl shadow-slate-200/50 overflow-hidden border border-slate-100/50">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-slate-200/50 bg-gradient-to-r from-slate-50 to-slate-100/50">
                            <th class="px-8 py-5 text-left text-sm font-bold text-slate-700 uppercase tracking-wider">
                                <input
                                    type="checkbox"
                                    @change="toggleSelectAll"
                                    :checked="selectedIds.length === agencies.data.length"
                                    class="w-5 h-5 rounded-lg border-2 border-slate-300 text-blue-600 focus:ring-blue-500"
                                />
                            </th>
                            <th class="px-8 py-5 text-left text-sm font-bold text-slate-700 uppercase tracking-wider">Nom</th>
                            <th class="px-8 py-5 text-left text-sm font-bold text-slate-700 uppercase tracking-wider">Code</th>
                            <th class="px-8 py-5 text-left text-sm font-bold text-slate-700 uppercase tracking-wider">Contact</th>
                            <th class="px-8 py-5 text-left text-sm font-bold text-slate-700 uppercase tracking-wider">Localisation</th>
                            <th class="px-8 py-5 text-left text-sm font-bold text-slate-700 uppercase tracking-wider">Responsable</th>
                            <th class="px-8 py-5 text-left text-sm font-bold text-slate-700 uppercase tracking-wider">EmployÃ©s</th>
                            <th class="px-8 py-5 text-left text-sm font-bold text-slate-700 uppercase tracking-wider">Statut</th>
                            <th class="px-8 py-5 text-left text-sm font-bold text-slate-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/50">
                        <tr v-for="agency in agencies.data" :key="agency.id" class="hover:bg-gradient-to-r hover:from-blue-50/30 hover:to-indigo-50/30 transition-all duration-300 group">
                            <td class="px-8 py-5">
                                <input
                                    type="checkbox"
                                    :checked="selectedIds.includes(agency.id)"
                                    @change="toggleSelect(agency.id)"
                                    class="w-5 h-5 rounded-lg border-2 border-slate-300 text-blue-600 focus:ring-blue-500"
                                />
                            </td>
                            <td class="px-8 py-5">
                                <div class="font-bold text-slate-900 text-lg">{{ agency.name }}</div>
                                <div v-if="agency.description" class="text-sm text-slate-500 mt-1">{{ agency.description?.substring(0, 60) }}...</div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="inline-block px-4 py-2 bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 rounded-xl text-sm font-bold shadow-sm">{{ agency.code }}</span>
                            </td>
                            <td class="px-8 py-5">
                                <div class="text-sm text-slate-700 font-medium">{{ agency.email || '-' }}</div>
                                <div class="text-sm text-slate-600">{{ agency.phone || '-' }}</div>
                            </td>
                            <td class="px-8 py-5">
                                <div class="text-sm text-slate-700 font-medium">{{ agency.city || '-' }}</div>
                                <div class="text-sm text-slate-600">{{ agency.country || '-' }}</div>
                            </td>
                            <td class="px-8 py-5">
                                <div class="text-sm text-slate-700 font-medium">{{ agency.manager_name || '-' }}</div>
                                <div class="text-sm text-slate-600">{{ agency.manager_email || '-' }}</div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="inline-block px-4 py-2 bg-gradient-to-r from-slate-100 to-slate-200 text-slate-800 rounded-xl text-sm font-bold shadow-sm">{{ agency.employee_count || 0 }}</span>
                            </td>
                            <td class="px-8 py-5">
                                <span
                                    :class="[
                                        'inline-block px-4 py-2 rounded-xl text-sm font-bold shadow-sm',
                                        agency.status === 'active' ? 'bg-gradient-to-r from-emerald-100 to-emerald-200 text-emerald-800' :
                                        agency.status === 'inactive' ? 'bg-gradient-to-r from-slate-100 to-slate-200 text-slate-800' :
                                        'bg-gradient-to-r from-red-100 to-red-200 text-red-800'
                                    ]"
                                >
                                    {{ agency.status === 'active' ? 'Actif' : agency.status === 'inactive' ? 'Inactif' : 'Suspendu' }}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex gap-3">
                                    <button
                                        @click="viewAgency(agency.id)"
                                        class="p-3 bg-blue-100 text-blue-600 hover:bg-blue-200 rounded-xl transition-all duration-300 transform hover:scale-110 shadow-sm"
                                        title="Voir les dÃ©tails"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    <button
                                        @click="editAgency(agency.id)"
                                        class="p-3 bg-amber-100 text-amber-600 hover:bg-amber-200 rounded-xl transition-all duration-300 transform hover:scale-110 shadow-sm"
                                        title="Modifier"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button
                                        @click="confirmDelete(agency.id)"
                                        class="p-3 bg-red-100 text-red-600 hover:bg-red-200 rounded-xl transition-all duration-300 transform hover:scale-110 shadow-sm"
                                        title="Supprimer"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="agencies.data.length === 0">
                            <td colspan="9" class="px-8 py-16 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-20 h-20 bg-slate-100 rounded-3xl flex items-center justify-center mb-6">
                                        <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <p class="text-xl font-semibold text-slate-600 mb-2">Aucune agence trouvÃ©e</p>
                                    <p class="text-slate-500">Commencez par crÃ©er votre premiÃ¨re agence</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Premium Pagination -->
        <div v-if="agencies.last_page > 1" class="bg-gradient-to-br from-white to-slate-50 rounded-3xl shadow-xl shadow-slate-200/50 p-8 flex items-center justify-between border border-slate-100/50">
            <div class="text-sm font-semibold text-slate-600">
                Affichage de <span class="text-blue-600 font-bold">{{ agencies.from }}</span> Ã  <span class="text-blue-600 font-bold">{{ agencies.to }}</span> sur <span class="text-blue-600 font-bold">{{ agencies.total }}</span> agences
            </div>
            <div class="flex gap-3">
                <button
                    v-for="page in getPaginationPages()"
                    :key="page"
                    @click="goToPage(page)"
                    :class="[
                        'px-6 py-3 rounded-2xl font-bold transition-all duration-300 transform hover:scale-105',
                        page === agencies.current_page
                            ? 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-lg shadow-blue-500/30'
                            : 'bg-white border-2 border-slate-200 text-slate-700 hover:border-blue-500 hover:text-blue-600'
                    ]"
                >
                    {{ page }}
                </button>
            </div>
        </div>

        <!-- Premium Bulk Actions -->
        <Transition
            enter-active-class="transition duration-500 ease-out"
            enter-from-class="transform translate-y-full opacity-0"
            enter-to-class="transform translate-y-0 opacity-100"
            leave-active-class="transition duration-300 ease-in"
            leave-from-class="transform translate-y-0 opacity-100"
            leave-to-class="transform translate-y-full opacity-0"
        >
            <div v-if="selectedIds.length > 0" class="fixed bottom-8 left-1/2 transform -translate-x-1/2 bg-gradient-to-r from-white to-slate-50 rounded-3xl shadow-2xl shadow-slate-900/20 border-2 border-slate-200 p-6 flex items-center gap-6 z-50">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-lg font-bold text-slate-800">{{ selectedIds.length }} agence(s) sÃ©lectionnÃ©e(s)</span>
                </div>
                <div class="h-8 w-px bg-slate-300"></div>
                <button
                    @click="bulkMarkActive"
                    class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-2xl hover:shadow-lg hover:shadow-emerald-500/30 transition-all duration-300 font-bold transform hover:scale-105"
                >
                    Marquer Actives
                </button>
                <button
                    @click="bulkMarkInactive"
                    class="px-6 py-3 bg-gradient-to-r from-slate-500 to-slate-600 text-white rounded-2xl hover:shadow-lg hover:shadow-slate-500/30 transition-all duration-300 font-bold transform hover:scale-105"
                >
                    Marquer Inactives
                </button>
                <button
                    @click="confirmBulkDelete"
                    class="px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-2xl hover:shadow-lg hover:shadow-red-500/30 transition-all duration-300 font-bold transform hover:scale-105"
                >
                    Supprimer
                </button>
                <button
                    @click="clearSelection"
                    class="px-6 py-3 bg-white border-2 border-slate-300 text-slate-700 rounded-2xl hover:bg-slate-100 hover:border-slate-400 transition-all duration-300 font-bold transform hover:scale-105"
                >
                    Annuler
                </button>
            </div>
        </Transition>

        <!-- Create/Edit Modal -->
        <ModalPremium
            :show="showModal"
            :title="modalTitle"
            :subtitle="modalSubtitle"
            size="lg"
            type="default"
            @close="closeModal"
        >
            <AgencyFormContent
                :agency="editingAgency"
                @submit="handleFormSubmit"
                @cancel="closeModal"
            />
        </ModalPremium>

        <!-- Delete Confirmation Modal -->
        <ModalPremium
            :show="showDeleteModal"
            title="Confirmer la suppression"
            subtitle="ÃŠtes-vous sÃ»r de vouloir supprimer cette agence ?"
            size="sm"
            type="warning"
            @close="closeDeleteModal"
        >
            <div class="space-y-4">
                <p class="text-slate-600">Cette action est irrÃ©versible. L'agence sera dÃ©finitivement supprimÃ©e de la base de donnÃ©es.</p>
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
import { ref, onMounted, watch } from 'vue';
import { usePage, router as inertiaRouter } from '@inertiajs/vue3';
import ModalPremium from '../../../../components/ModalPremium.vue';
import NotificationPremium from '../../../../components/NotificationPremium.vue';
import AgencyFormContent from '../../../../components/AgencyFormContent.vue';

const page = usePage();
const agencies = ref(page.props.agencies || { data: [], current_page: 1, last_page: 1, from: 0, to: 0, total: 0 });
const initialStats = page.props.stats || {};
const stats = ref(initialStats);

// Modal states
const showModal = ref(false);
const showDeleteModal = ref(false);
const editingAgency = ref(null);
const deletingAgencyId = ref(null);
const modalTitle = ref('');
const modalSubtitle = ref('');

// Notification state
const notification = ref({
    show: false,
    type: 'success',
    title: '',
    message: ''
});

const filters = ref({
    search: page.props.filters?.search || '',
    status: page.props.filters?.status || '',
    per_page: page.props.filters?.per_page || 10,
    sort_by: page.props.filters?.sort_by || 'created_at',
    sort_order: page.props.filters?.sort_order || 'desc',
});

const selectedIds = ref([]);

watch(
    () => page.props.agencies,
    (value) => {
        if (value) agencies.value = value;
    },
);

watch(
    () => page.props.stats,
    (value) => {
        if (value) stats.value = value;
    },
);

onMounted(() => {
    loadStateFromStorage();
    window.addEventListener('enterprise:refresh', loadAgencies);
});

const loadStateFromStorage = () => {
    try {
        const savedFilters = localStorage.getItem('agencies_filters');
        const savedSelectedIds = localStorage.getItem('agencies_selected_ids');
        
        if (savedFilters) {
            filters.value = JSON.parse(savedFilters);
        }
        if (savedSelectedIds) {
            selectedIds.value = JSON.parse(savedSelectedIds);
        }
    } catch (error) {
        console.error('Error loading state from localStorage:', error);
    }
};

const saveStateToStorage = () => {
    try {
        localStorage.setItem('agencies_filters', JSON.stringify(filters.value));
        localStorage.setItem('agencies_selected_ids', JSON.stringify(selectedIds.value));
    } catch (error) {
        console.error('Error saving state to localStorage:', error);
    }
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

const openCreateModal = () => {
    editingAgency.value = null;
    modalTitle.value = 'CrÃ©er une nouvelle agence';
    modalSubtitle.value = 'Remplissez les informations ci-dessous pour crÃ©er une nouvelle agence';
    showModal.value = true;
};

const openEditModal = (agency) => {
    editingAgency.value = agency;
    modalTitle.value = 'Modifier l\'agence';
    modalSubtitle.value = `Modifiez les informations de l'agence ${agency.name}`;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editingAgency.value = null;
};

const confirmDelete = (id) => {
    deletingAgencyId.value = id;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    deletingAgencyId.value = null;
};

const confirmDeleteAction = async () => {
    try {
        const response = await fetch(`/agencies/${deletingAgencyId.value}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
            },
        });

        if (response.ok) {
            showNotification('success', 'SuccÃ¨s', 'L\'agence a Ã©tÃ© supprimÃ©e avec succÃ¨s');
            closeDeleteModal();
            loadAgencies();
        } else {
            showNotification('error', 'Erreur', 'Une erreur est survenue lors de la suppression');
        }
    } catch (error) {
        console.error('Error deleting agency:', error);
        showNotification('error', 'Erreur', 'Une erreur est survenue lors de la suppression');
    }
};

const handleFormSubmit = async (formData) => {
    try {
        const method = editingAgency.value ? 'PUT' : 'POST';
        const url = editingAgency.value ? `/agencies/${editingAgency.value.id}` : '/agencies';

        const response = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
            },
            body: JSON.stringify(formData),
        });

        if (response.ok) {
            showNotification('success', 'SuccÃ¨s', editingAgency.value ? 'L\'agence a Ã©tÃ© mise Ã  jour avec succÃ¨s' : 'L\'agence a Ã©tÃ© crÃ©Ã©e avec succÃ¨s');
            closeModal();
            loadAgencies();
        } else {
            const data = await response.json();
            showNotification('error', 'Erreur', data.message || 'Une erreur est survenue');
        }
    } catch (error) {
        console.error('Error submitting form:', error);
        showNotification('error', 'Erreur', 'Une erreur est survenue');
    }
};

const handleSearch = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        saveStateToStorage();
        loadAgencies();
    }, 500);
};

const handleFilter = () => {
    saveStateToStorage();
    loadAgencies();
};

const loadAgencies = () => {
    const params = new URLSearchParams(filters.value);
    inertiaRouter.get(`/agencies?${params.toString()}`, {}, {
        preserveState: true,
        preserveScroll: true,
        only: ['agencies', 'stats', 'filters'],
    });
};

const toggleSelectAll = () => {
    if (selectedIds.value.length === agencies.value.data.length) {
        selectedIds.value = [];
    } else {
        selectedIds.value = agencies.value.data.map(a => a.id);
    }
    saveStateToStorage();
};

const toggleSelect = (id) => {
    const index = selectedIds.value.indexOf(id);
    if (index > -1) {
        selectedIds.value.splice(index, 1);
    } else {
        selectedIds.value.push(id);
    }
    saveStateToStorage();
};

const clearSelection = () => {
    selectedIds.value = [];
    saveStateToStorage();
};

const viewAgency = (id) => {
    inertiaRouter.visit(`/agencies/${id}`);
};

const editAgency = (id) => {
    const agency = agencies.value.data.find(a => a.id === id);
    if (agency) {
        openEditModal(agency);
    }
};

const bulkMarkActive = async () => {
    await bulkUpdateStatus('active');
};

const bulkMarkInactive = async () => {
    await bulkUpdateStatus('inactive');
};

const bulkUpdateStatus = async (status) => {
    try {
        const response = await fetch('/agencies/bulk/status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
            },
            body: JSON.stringify({
                ids: selectedIds.value,
                status
            }),
        });

        if (response.ok) {
            showNotification('success', 'SuccÃ¨s', `${selectedIds.value.length} agence(s) marquÃ©e(s) comme ${status === 'active' ? 'active(s)' : 'inactive(s)'}`);
            clearSelection();
            loadAgencies();
        } else {
            showNotification('error', 'Erreur', 'Une erreur est survenue lors de la mise Ã  jour');
        }
    } catch (error) {
        console.error('Error bulk updating status:', error);
        showNotification('error', 'Erreur', 'Une erreur est survenue');
    }
};

const confirmBulkDelete = () => {
    if (confirm(`ÃŠtes-vous sÃ»r de vouloir supprimer ${selectedIds.value.length} agence(s) ?`)) {
        bulkDelete();
    }
};

const bulkDelete = async () => {
    try {
        const response = await fetch('/agencies/bulk/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
            },
            body: JSON.stringify({
                ids: selectedIds.value
            }),
        });

        if (response.ok) {
            showNotification('success', 'SuccÃ¨s', `${selectedIds.value.length} agence(s) supprimÃ©e(s) avec succÃ¨s`);
            clearSelection();
            loadAgencies();
        } else {
            showNotification('error', 'Erreur', 'Une erreur est survenue lors de la suppression');
        }
    } catch (error) {
        console.error('Error bulk deleting:', error);
        showNotification('error', 'Erreur', 'Une erreur est survenue');
    }
};

const exportData = () => {
    window.location.href = '/agencies/export';
};

const getPaginationPages = () => {
    const current = agencies.value.current_page;
    const last = agencies.value.last_page;
    const pages = [];

    if (last <= 7) {
        for (let i = 1; i <= last; i++) {
            pages.push(i);
        }
    } else {
        pages.push(1, 2, 3, '...', last - 2, last - 1, last);
    }

    return pages;
};

const goToPage = (page) => {
    if (page === '...') return;
    filters.value.page = page;
    saveStateToStorage();
    loadAgencies();
};

let searchTimeout;
</script>
