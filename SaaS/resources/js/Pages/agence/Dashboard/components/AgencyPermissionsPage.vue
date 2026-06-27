<template>
    <div class="flex flex-col gap-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 flex items-center gap-2">
                    Gestion des Permissions
                    <span class="text-rose-500 text-sm font-semibold bg-rose-50 px-2.5 py-1 rounded-full border border-rose-200">Espace Agence</span>
                </h1>
                <p class="text-slate-650 mt-1">Configurez les droits opérationnels individuels des collaborateurs de votre agence.</p>
            </div>
        </div>

        <!-- Notification Banner -->
        <div v-if="notification.show" :class="[
            'p-4 rounded-xl border flex items-center justify-between shadow-sm transition-all',
            notification.type === 'success' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-rose-50 text-rose-800 border-rose-200'
        ]">
            <div class="flex items-center gap-2 text-sm font-medium">
                <i :class="notification.type === 'success' ? 'fas fa-check-circle text-emerald-500' : 'fas fa-exclamation-circle text-rose-500'"></i>
                <span>{{ notification.message }}</span>
            </div>
            <button @click="notification.show = false" class="text-slate-400 hover:text-slate-600 transition">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Employees List Card -->
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-5 border border-slate-100 flex flex-col gap-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Collaborateurs</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Sélectionnez un employé pour gérer ses accès.</p>
                </div>

                <div v-if="loading" class="flex flex-col items-center justify-center py-12">
                    <svg class="w-8 h-8 animate-spin text-emerald-600" viewBox="0 0 24 24" fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                    </svg>
                </div>

                <div v-else class="flex flex-col gap-2 max-h-[500px] overflow-y-auto pr-1">
                    <button
                        v-for="emp in employees"
                        :key="emp.id"
                        @click="selectEmployee(emp)"
                        :class="[
                            'w-full flex items-center justify-between p-3 rounded-xl border text-left transition-all',
                            selectedEmployee?.id === emp.id
                                ? 'border-emerald-500 bg-emerald-50/20 shadow-sm'
                                : 'border-slate-150 hover:border-slate-300 bg-slate-50/50 hover:bg-slate-50'
                        ]"
                    >
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold text-sm shrink-0">
                                {{ emp.name.charAt(0).toUpperCase() }}
                            </div>
                            <div class="min-w-0">
                                <div class="font-bold text-slate-800 text-sm truncate">{{ emp.name }}</div>
                                <div class="text-[11px] text-slate-500 truncate mt-0.5">{{ emp.employee?.position || 'Collaborateur' }}</div>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-xs text-slate-400"></i>
                    </button>

                    <div v-if="employees.length === 0" class="py-8 text-center text-slate-400 text-sm">
                        Aucun collaborateur trouvé pour cette agence.
                    </div>
                </div>
            </div>

            <!-- Permissions Config Card -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 flex flex-col gap-6">
                <div v-if="!selectedEmployee" class="flex flex-col items-center justify-center py-24 text-slate-400 text-center gap-3">
                    <i class="fas fa-user-shield text-4xl text-slate-300 animate-pulse"></i>
                    <p class="font-semibold text-slate-650">Veuillez sélectionner un collaborateur pour configurer ses permissions.</p>
                </div>

                <div v-else class="flex flex-col gap-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-3.5">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-emerald-500 to-teal-600 flex items-center justify-center text-white font-extrabold text-base shadow-sm">
                                {{ selectedEmployee.name.charAt(0).toUpperCase() }}
                            </div>
                            <div>
                                <h3 class="font-extrabold text-lg text-slate-900">{{ selectedEmployee.name }}</h3>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    Rôle par défaut : <span class="font-bold text-indigo-600">{{ selectedEmployee.role?.name || 'Aucun' }}</span>
                                    <span v-if="selectedEmployee.employee?.position"> · {{ selectedEmployee.employee.position }}</span>
                                </p>
                            </div>
                        </div>
                        <button
                            @click="savePermissions"
                            :disabled="saving"
                            class="px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-xl text-xs font-bold hover:shadow-lg hover:shadow-emerald-500/20 disabled:opacity-50 transition-all flex items-center justify-center gap-2"
                        >
                            <svg v-if="saving" class="w-3.5 h-3.5 animate-spin" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                            </svg>
                            <span>Enregistrer les Permissions</span>
                        </button>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-xs text-blue-800 flex gap-2">
                        <i class="fas fa-info-circle text-base shrink-0"></i>
                        <div>
                            Toutes les permissions activées ci-dessous s'appliqueront directement à ce collaborateur, en remplacement ou en complément de son rôle.
                        </div>
                    </div>

                    <!-- Permissions Grid -->
                    <div class="space-y-6 max-h-[500px] overflow-y-auto pr-1 scrollbar-thin">
                        <div v-for="group in finePermissions" :key="group.category" class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                            <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-3 pb-1.5 border-b border-slate-200">
                                {{ group.category }}
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div
                                    v-for="item in group.items"
                                    :key="item.id"
                                    class="flex items-start justify-between gap-4 p-3 bg-white border border-slate-150 rounded-lg shadow-sm"
                                >
                                    <div class="flex-1 min-w-0">
                                        <span class="block text-xs font-bold text-slate-700 truncate">{{ item.label }}</span>
                                        <span class="block text-[10px] text-slate-500 mt-1 leading-relaxed">{{ item.desc }}</span>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer mt-1 shrink-0">
                                        <input
                                            type="checkbox"
                                            v-model="permissionsForm[item.id]"
                                            class="sr-only peer"
                                        />
                                        <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const loading = ref(true);
const saving = ref(false);
const employees = ref([]);
const selectedEmployee = ref(null);
const permissionsForm = ref({});
const notification = ref({ show: false, type: 'success', message: '' });

// Fine-grained operations permissions list
const finePermissions = [
    {
        category: 'Locataires',
        items: [
            { id: 'locataires.view', label: 'Voir les locataires', desc: 'Permet de consulter la liste et les détails des locataires' },
            { id: 'locataires.create', label: 'Ajouter un locataire', desc: 'Permet d\'ajouter un nouveau locataire' },
            { id: 'locataires.edit', label: 'Modifier un locataire', desc: 'Permet de modifier les informations d\'un locataire' },
            { id: 'locataires.delete', label: 'Supprimer un locataire', desc: 'Permet de supprimer un locataire' },
        ]
    },
    {
        category: 'Bâtiments',
        items: [
            { id: 'batiments.view', label: 'Voir les bâtiments', desc: 'Permet de lister et voir les détails des bâtiments' },
            { id: 'batiments.create', label: 'Ajouter un bâtiment', desc: 'Permet de créer un nouveau bâtiment' },
            { id: 'batiments.edit', label: 'Modifier un bâtiment', desc: 'Permet de modifier les détails d\'un bâtiment' },
            { id: 'batiments.delete', label: 'Supprimer un bâtiment', desc: 'Permet de supprimer un bâtiment' },
        ]
    },
    {
        category: 'Logements (Biens)',
        items: [
            { id: 'logements.view', label: 'Voir les logements', desc: 'Permet de lister et consulter les caractéristiques des logements' },
            { id: 'logements.create', label: 'Ajouter un logement', desc: 'Permet de déclarer un nouveau logement dans un bâtiment' },
            { id: 'logements.edit', label: 'Modifier un logement', desc: 'Permet de modifier la description d\'un logement' },
            { id: 'logements.delete', label: 'Supprimer un logement', desc: 'Permet de supprimer un logement' },
        ]
    },
    {
        category: 'Contrats de bail',
        items: [
            { id: 'contrats.view', label: 'Voir les contrats', desc: 'Permet de lister et lire les contrats de bail' },
            { id: 'contrats.create', label: 'Créer un contrat', desc: 'Permet de générer et signer un nouveau contrat de bail' },
            { id: 'contrats.edit', label: 'Modifier un contrat', desc: 'Permet de modifier les clauses d\'un contrat existant' },
            { id: 'contrats.delete', label: 'Supprimer/Résilier', desc: 'Permet d\'archiver, supprimer ou résilier un contrat de bail' },
        ]
    },
    {
        category: 'Factures & Paiements',
        items: [
            { id: 'factures.view', label: 'Voir les factures', desc: 'Permet de lister et voir les factures/quittances générées' },
            { id: 'factures.create', label: 'Créer des factures', desc: 'Permet de générer des factures de loyer' },
            { id: 'factures.delete', label: 'Supprimer des factures', desc: 'Permet de rejeter ou supprimer une facture erronée' },
            { id: 'paiements.view', label: 'Voir les paiements', desc: 'Permet de consulter l\'historique des paiements de loyer' },
            { id: 'paiements.create', label: 'Enregistrer un paiement', desc: 'Permet de saisir un paiement de loyer manuel' },
        ]
    },
    {
        category: 'Comptabilité (Trésorerie)',
        items: [
            { id: 'depenses.view', label: 'Voir les dépenses', desc: 'Permet d\'afficher le journal des dépenses' },
            { id: 'depenses.create', label: 'Enregistrer une dépense', desc: 'Permet de déclarer une nouvelle dépense' },
            { id: 'depenses.delete', label: 'Supprimer une dépense', desc: 'Permet de supprimer un enregistrement de dépense' },
            { id: 'entrees.view', label: 'Voir les autres entrées', desc: 'Permet d\'afficher les autres rentrées de fonds' },
            { id: 'entrees.create', label: 'Enregistrer une entrée', desc: 'Permet d\'ajouter une entrée de fonds' },
        ]
    },
    {
        category: 'Maintenance & SAV',
        items: [
            { id: 'maintenance.view', label: 'Voir les pannes', desc: 'Permet de consulter la liste des interventions de maintenance' },
            { id: 'maintenance.create', label: 'Déclarer un incident', desc: 'Permet de créer un nouveau ticket de panne' },
            { id: 'maintenance.edit', label: 'Résoudre/Assigner', desc: 'Permet d\'assigner un maintenancier ou clore un incident' },
            { id: 'maintenance.delete', label: 'Supprimer un ticket', desc: 'Permet d\'effacer définitivement une demande de maintenance' },
        ]
    },
    {
        category: 'Collaborateurs & Rôles',
        items: [
            { id: 'employees.view', label: 'Voir le personnel', desc: 'Permet de lister les collaborateurs de l\'agence ou du siège' },
            { id: 'employees.create', label: 'Recruter', desc: 'Permet de créer des comptes d\'employés' },
            { id: 'employees.edit', label: 'Gérer les profils', desc: 'Permet d\'attribuer des postes et affecter des agences' },
            { id: 'employees.delete', label: 'Suspendre/Résilier', desc: 'Permet de désactiver un utilisateur' },
        ]
    },
    {
        category: 'Renouvellements',
        items: [
            { id: 'renouvellements.view', label: 'Voir les renouvellements', desc: 'Permet de consulter les demandes de renouvellement de bail' },
            { id: 'renouvellements.create', label: 'Créer un renouvellement', desc: 'Permet d\'enregistrer un renouvellement de contrat' },
            { id: 'renouvellements.edit', label: 'Modifier un renouvellement', desc: 'Permet d\'éditer les conditions d\'un renouvellement' },
            { id: 'renouvellements.delete', label: 'Supprimer un renouvellement', desc: 'Permet d\'annuler ou supprimer un renouvellement' },
        ]
    },
    {
        category: 'Engagements',
        items: [
            { id: 'engagements.view', label: 'Voir les engagements', desc: 'Permet de consulter le registre des engagements' },
            { id: 'engagements.create', label: 'Créer un engagement', desc: 'Permet d\'enregistrer un nouvel engagement' },
            { id: 'engagements.edit', label: 'Modifier un engagement', desc: 'Permet de modifier les détails d\'un engagement' },
            { id: 'engagements.delete', label: 'Supprimer un engagement', desc: 'Permet d\'effacer un engagement' },
        ]
    },
    {
        category: 'États des lieux',
        items: [
            { id: 'etats_lieux.view', label: 'Voir les états des lieux', desc: 'Permet de lister et lire les rapports d\'état des lieux' },
            { id: 'etats_lieux.create', label: 'Créer un état des lieux', desc: 'Permet de générer un état des lieux d\'entrée ou de sortie' },
            { id: 'etats_lieux.edit', label: 'Modifier un état des lieux', desc: 'Permet de modifier un rapport d\'état des lieux' },
            { id: 'etats_lieux.delete', label: 'Supprimer un état des lieux', desc: 'Permet de supprimer un état des lieux' },
        ]
    },
    {
        category: 'Rapports & Statistiques',
        items: [
            { id: 'reports.view', label: 'Accéder aux statistiques', desc: 'Donne accès aux rapports d\'analyse, graphiques et finances globales' },
        ]
    }
];

const loadData = async () => {
    loading.value = true;
    try {
        const response = await axios.get(route('agence.employees.data'));
        employees.value = response.data.employees;
    } catch (err) {
        console.error('Error loading employees:', err);
    } finally {
        loading.value = false;
    }
};

const selectEmployee = (user) => {
    selectedEmployee.value = user;
    const basePermissions = {};

    finePermissions.forEach(cat => {
        cat.items.forEach(item => {
            let active = false;
            // Check custom override
            if (user.permissions && typeof user.permissions === 'object' && user.permissions[item.id] !== undefined) {
                active = !!user.permissions[item.id];
            } else if (user.role) {
                const rolePerms = user.role.permissions || [];
                active = rolePerms.includes('*') || rolePerms.includes(item.id) ||
                         (rolePerms.includes('manage_properties') && (
                             item.id.startsWith('batiments') || 
                             item.id.startsWith('logements') || 
                             item.id.startsWith('contrats') || 
                             item.id.startsWith('locataires') ||
                             item.id.startsWith('renouvellements') ||
                             item.id.startsWith('engagements') ||
                             item.id.startsWith('etats_lieux')
                         )) ||
                         (rolePerms.includes('manage_accounting') && (item.id.startsWith('factures') || item.id.startsWith('paiements') || item.id.startsWith('depenses') || item.id.startsWith('entrees'))) ||
                         (rolePerms.includes('manage_maintenance') && item.id.startsWith('maintenance')) ||
                         (rolePerms.includes('manage_users') && item.id.startsWith('employees'));
            }
            basePermissions[item.id] = active;
        });
    });

    permissionsForm.value = basePermissions;
};

const savePermissions = async () => {
    if (!selectedEmployee.value) return;
    saving.value = true;
    notification.value.show = false;

    try {
        await axios.post(route('users.update-permissions', { user: selectedEmployee.value.id }), {
            permissions: permissionsForm.value
        });
        
        // Success
        notification.value = {
            show: true,
            type: 'success',
            message: 'Les permissions du collaborateur ont été enregistrées avec succès.'
        };
        
        // Update local copy
        const emp = employees.value.find(e => e.id === selectedEmployee.value.id);
        if (emp) {
            emp.permissions = { ...permissionsForm.value };
        }
    } catch (err) {
        console.error('Error saving permissions:', err);
        notification.value = {
            show: true,
            type: 'error',
            message: 'Erreur lors de la sauvegarde des permissions.'
        };
    } finally {
        saving.value = false;
    }
};

onMounted(() => {
    loadData();
});
</script>
