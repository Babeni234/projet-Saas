import { createRouter, createWebHashHistory } from 'vue-router';

const routes = [
    {
        path: '/',
        redirect: '/dashboard/master',
    },
    {
        path: '/dashboard/master',
        name: 'dashboard.master',
        component: () => import('../Pages/entreprise/Dashboard/components/MasterDashboard.vue'),
        meta: {
            title: 'Tableau de bord',
            breadcrumbs: [{ label: 'Accueil', to: { name: 'dashboard.master' } }],
        },
    },
    {
        path: '/dashboard/immobilier',
        name: 'immobilier.index',
        component: () => import('../Pages/entreprise/Dashboard/components/ImmobilierDashboard.vue'),
        meta: {
            title: 'Gestion immobilière',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'dashboard.master' } },
                { label: 'Immobilier', to: { name: 'immobilier.index' } },
            ],
        },
    },
    {
        path: '/dashboard/immobilier/batiments',
        name: 'immobilier.batiments',
        component: () => import('../Pages/entreprise/Dashboard/components/BatimentsPage.vue'),
        meta: {
            title: 'Gestion des bâtiments',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'dashboard.master' } },
                { label: 'Immobilier', to: { name: 'immobilier.index' } },
                { label: 'Bâtiments', to: { name: 'immobilier.batiments' } },
            ],
        },
    },
    {
        path: '/dashboard/immobilier/logements',
        name: 'immobilier.logements',
        component: () => import('../Pages/entreprise/Dashboard/components/LogementsPage.vue'),
        meta: {
            title: 'Gestion des logements',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'dashboard.master' } },
                { label: 'Immobilier', to: { name: 'immobilier.index' } },
                { label: 'Logements', to: { name: 'immobilier.logements' } },
            ],
        },
    },
    {
        path: '/dashboard/immobilier/contrats',
        name: 'immobilier.contrats',
        component: () => import('../Pages/entreprise/Dashboard/components/ContratsBailPage.vue'),
        meta: {
            title: 'Contrats de bail',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'dashboard.master' } },
                { label: 'Immobilier', to: { name: 'immobilier.index' } },
                { label: 'Contrats', to: { name: 'immobilier.contrats' } },
            ],
        },
    },
    {
        path: '/dashboard/immobilier/locataires',
        name: 'immobilier.locataires',
        component: () => import('../Pages/entreprise/Dashboard/components/LocatairesPage.vue'),
        meta: {
            title: 'Gestion des locataires',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'dashboard.master' } },
                { label: 'Immobilier', to: { name: 'immobilier.index' } },
                { label: 'Locataires', to: { name: 'immobilier.locataires' } },
            ],
        },
    },
    {
        path: '/dashboard/immobilier/renouvellements',
        name: 'immobilier.renouvellements',
        component: () => import('../Pages/entreprise/Dashboard/components/RenouvellementContratPage.vue'),
        meta: {
            title: 'Renouvellements de contrat',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'dashboard.master' } },
                { label: 'Immobilier', to: { name: 'immobilier.index' } },
                { label: 'Renouvellements', to: { name: 'immobilier.renouvellements' } },
            ],
        },
    },
    {
        path: '/dashboard/immobilier/engagements',
        name: 'immobilier.engagements',
        component: () => import('../Pages/entreprise/Dashboard/components/EngagementPage.vue'),
        meta: {
            title: 'Engagements',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'dashboard.master' } },
                { label: 'Immobilier', to: { name: 'immobilier.index' } },
                { label: 'Engagements', to: { name: 'immobilier.engagements' } },
            ],
        },
    },
    {
        path: '/dashboard/immobilier/etats-des-lieux',
        name: 'immobilier.etats-des-lieux',
        component: () => import('../Pages/entreprise/Dashboard/components/EtatDesLieuxPage.vue'),
        meta: {
            title: 'États des lieux',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'dashboard.master' } },
                { label: 'Immobilier', to: { name: 'immobilier.index' } },
                { label: 'États des lieux', to: { name: 'immobilier.etats-des-lieux' } },
            ],
        },
    },
    {
        path: '/dashboard/immobilier/historique',
        name: 'immobilier.historique',
        component: () => import('../Pages/entreprise/Dashboard/components/HistoriquePage.vue'),
        meta: {
            title: 'Historique immobilier',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'dashboard.master' } },
                { label: 'Immobilier', to: { name: 'immobilier.index' } },
                { label: 'Historique', to: { name: 'immobilier.historique' } },
            ],
        },
    },
    {
        path: '/dashboard/immobilier/agencies',
        name: 'dashboard.agencies',
        component: () => import('../Pages/entreprise/Dashboard/pages/AgenciesManagement.vue'),
        meta: {
            title: 'Gestion des agences',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'dashboard.master' } },
                { label: 'Agences', to: { name: 'dashboard.agencies' } },
            ],
        },
    },
    {
        path: '/dashboard/immobilier/agencies/create',
        name: 'dashboard.agency.create',
        component: () => import('../Pages/entreprise/Dashboard/pages/AgencyForm.vue'),
        meta: {
            title: 'Nouvelle agence',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'dashboard.master' } },
                { label: 'Agences', to: { name: 'dashboard.agencies' } },
                { label: 'Création', to: { name: 'dashboard.agency.create' } },
            ],
        },
    },
    {
        path: '/dashboard/immobilier/agencies/:id/edit',
        name: 'dashboard.agency.edit',
        component: () => import('../Pages/entreprise/Dashboard/pages/AgencyForm.vue'),
        props: true,
        meta: {
            title: "Modifier l'agence",
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'dashboard.master' } },
                { label: 'Agences', to: { name: 'dashboard.agencies' } },
            ],
        },
    },
    {
        path: '/dashboard/immobilier/agencies/:id',
        name: 'dashboard.agency.show',
        component: () => import('../Pages/entreprise/Dashboard/pages/AgencyDetails.vue'),
        props: true,
        meta: {
            title: "Détail de l'agence",
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'dashboard.master' } },
                { label: 'Agences', to: { name: 'dashboard.agencies' } },
            ],
        },
    },
    {
        path: '/dashboard/hotel/reservations',
        name: 'hotel.reservations',
        component: () => import('../Pages/entreprise/Dashboard/components/ReservationsPage.vue'),
        meta: {
            title: 'Réservations',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'dashboard.master' } },
                { label: 'Hôtellerie', to: { name: 'dashboard.hotel' } },
                { label: 'Réservations', to: { name: 'hotel.reservations' } },
            ],
        },
    },
    {
        path: '/dashboard/hotel/checkinout',
        name: 'hotel.checkinout',
        component: () => import('../Pages/entreprise/Dashboard/components/CheckInOutPage.vue'),
        meta: {
            title: 'Check-in / Check-out',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'dashboard.master' } },
                { label: 'Hôtellerie', to: { name: 'dashboard.hotel' } },
                { label: 'Check-in / Check-out', to: { name: 'hotel.checkinout' } },
            ],
        },
    },
    {
        path: '/dashboard/hotel/chambres',
        name: 'hotel.chambres',
        component: () => import('../Pages/entreprise/Dashboard/components/ChambresPage.vue'),
        meta: {
            title: 'Gestion des chambres',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'dashboard.master' } },
                { label: 'Hôtellerie', to: { name: 'dashboard.hotel' } },
                { label: 'Chambres', to: { name: 'hotel.chambres' } },
            ],
        },
    },
    {
        path: '/dashboard/hotel/clients',
        name: 'hotel.clients',
        component: () => import('../Pages/entreprise/Dashboard/components/ClientsPage.vue'),
        meta: {
            title: 'Gestion des clients',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'dashboard.master' } },
                { label: 'Hôtellerie', to: { name: 'dashboard.hotel' } },
                { label: 'Clients', to: { name: 'hotel.clients' } },
            ],
        },
    },
    {
        path: '/dashboard/hotel/services',
        name: 'hotel.services',
        component: () => import('../Pages/entreprise/Dashboard/components/ServicesPage.vue'),
        meta: {
            title: 'Services hôteliers',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'dashboard.master' } },
                { label: 'Hôtellerie', to: { name: 'dashboard.hotel' } },
                { label: 'Services', to: { name: 'hotel.services' } },
            ],
        },
    },
    {
        path: '/dashboard/hotel/nettoyage',
        name: 'hotel.nettoyage',
        component: () => import('../Pages/entreprise/Dashboard/components/NettoyagePage.vue'),
        meta: {
            title: 'Nettoyage',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'dashboard.master' } },
                { label: 'Hôtellerie', to: { name: 'dashboard.hotel' } },
                { label: 'Nettoyage', to: { name: 'hotel.nettoyage' } },
            ],
        },
    },
    {
        path: '/dashboard/hotel/tarifs',
        name: 'hotel.tarifs',
        component: () => import('../Pages/entreprise/Dashboard/components/TarifsPage.vue'),
        meta: {
            title: 'Tarifs',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'dashboard.master' } },
                { label: 'Hôtellerie', to: { name: 'dashboard.hotel' } },
                { label: 'Tarifs', to: { name: 'hotel.tarifs' } },
            ],
        },
    },
    {
        path: '/dashboard/hotel/personnel',
        name: 'hotel.personnel',
        component: () => import('../Pages/entreprise/Dashboard/components/PersonnelPage.vue'),
        meta: {
            title: 'Personnel',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'dashboard.master' } },
                { label: 'Hôtellerie', to: { name: 'dashboard.hotel' } },
                { label: 'Personnel', to: { name: 'hotel.personnel' } },
            ],
        },
    },
    {
        path: '/dashboard/hotel/equipements',
        name: 'hotel.equipements',
        component: () => import('../Pages/entreprise/Dashboard/components/EquipementsPage.vue'),
        meta: {
            title: 'Équipements',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'dashboard.master' } },
                { label: 'Hôtellerie', to: { name: 'dashboard.hotel' } },
                { label: 'Équipements', to: { name: 'hotel.equipements' } },
            ],
        },
    },
    {
        path: '/dashboard/hotel/:section?',
        name: 'dashboard.hotel',
        component: () => import('../Pages/entreprise/Dashboard/components/HotelDashboard.vue'),
        meta: { title: 'Gestion hôtelière' },
    },
    {
        path: '/dashboard/comptabilite',
        name: 'dashboard.accounting',
        component: () => import('../Pages/entreprise/Dashboard/components/AccountingDashboard.vue'),
        meta: { title: 'Comptabilité & facturation' },
    },
    {
        path: '/dashboard/comptabilite/factures',
        name: 'accounting.factures',
        component: () => import('../Pages/entreprise/Dashboard/components/FactureListPage.vue'),
        meta: {
            title: 'Factures',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'dashboard.master' } },
                { label: 'Comptabilité', to: { name: 'dashboard.accounting' } },
                { label: 'Factures', to: { name: 'accounting.factures' } },
            ],
        },
    },
    {
        path: '/dashboard/comptabilite/facture-creation',
        name: 'accounting.facture-creation',
        component: () => import('../Pages/entreprise/Dashboard/components/FactureCreationPage.vue'),
        meta: {
            title: 'Création de facture',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'dashboard.master' } },
                { label: 'Comptabilité', to: { name: 'dashboard.accounting' } },
                { label: 'Création de facture', to: { name: 'accounting.facture-creation' } },
            ],
        },
    },
    {
        path: '/dashboard/maintenance',
        name: 'dashboard.maintenance',
        component: () => import('../Pages/entreprise/Dashboard/components/MaintenanceDashboard.vue'),
        meta: { title: 'Maintenance / SAV' },
    },
    {
        path: '/dashboard/rapports',
        name: 'dashboard.reports',
        component: () => import('../Pages/entreprise/Dashboard/components/ReportsDashboard.vue'),
        meta: { title: 'Rapports & statistiques' },
    },
    {
        path: '/dashboard/maps',
        name: 'dashboard.maps',
        component: () => import('../Pages/entreprise/Dashboard/components/MapsDashboard.vue'),
        meta: { title: 'Cartographie' },
    },
    {
        path: '/dashboard/roles',
        name: 'dashboard.roles',
        component: () => import('../Pages/entreprise/Dashboard/components/RolesPermissions.vue'),
        meta: { title: 'Rôles & permissions' },
    },
];

const router = createRouter({
    history: createWebHashHistory(),
    routes,
    scrollBehavior() {
        return { top: 0 };
    },
});

export default router;
