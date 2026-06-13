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
        path: '/dashboard/immobilier/proprietaires',
        name: 'immobilier.proprietaires',
        component: () => import('../Pages/entreprise/Dashboard/components/ProprietairesPage.vue'),
        meta: {
            title: 'Gestion des propriétaires',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'dashboard.master' } },
                { label: 'Immobilier', to: { name: 'immobilier.index' } },
                { label: 'Propriétaires', to: { name: 'immobilier.proprietaires' } },
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
        path: '/dashboard/immobilier/illustrations',
        name: 'immobilier.illustrations',
        component: () => import('../Pages/entreprise/Dashboard/components/IllustrationPage.vue'),
        meta: {
            title: 'Gestion des illustrations',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'dashboard.master' } },
                { label: 'Immobilier', to: { name: 'immobilier.index' } },
                { label: 'Illustrations', to: { name: 'immobilier.illustrations' } },
            ],
        },
    },
    {
        path: '/dashboard/immobilier/affectations',
        name: 'immobilier.affectations',
        component: () => import('../Pages/entreprise/Dashboard/components/AffectationPage.vue'),
        meta: {
            title: 'Affectation des logements',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'dashboard.master' } },
                { label: 'Immobilier', to: { name: 'immobilier.index' } },
                { label: 'Affectations', to: { name: 'immobilier.affectations' } },
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
        component: () => import('../Pages/entreprise/Dashboard/components/RenouvellementPage.vue'),
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
        path: '/dashboard/immobilier/factures',
        name: 'immobilier.factures',
        component: () => import('../Pages/entreprise/Dashboard/components/FacturationLoyerPage.vue'),
        meta: {
            title: 'Facturation des loyers',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'dashboard.master' } },
                { label: 'Immobilier', to: { name: 'immobilier.index' } },
                { label: 'Facturation', to: { name: 'immobilier.factures' } },
            ],
        },
    },
    {
        path: '/dashboard/immobilier/paiements',
        name: 'immobilier.paiements',
        component: () => import('../Pages/entreprise/Dashboard/components/PaiementsLoyerPage.vue'),
        meta: {
            title: 'Paiements de loyer',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'dashboard.master' } },
                { label: 'Immobilier', to: { name: 'immobilier.index' } },
                { label: 'Paiements', to: { name: 'immobilier.paiements' } },
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
        path: '/dashboard/comptabilite/depenses',
        name: 'accounting.depenses',
        component: () => import('../Pages/entreprise/Dashboard/components/DepensesPage.vue'),
        meta: {
            title: 'Dépenses',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'dashboard.master' } },
                { label: 'Comptabilité', to: { name: 'dashboard.accounting' } },
                { label: 'Dépenses', to: { name: 'accounting.depenses' } },
            ],
        },
    },
    {
        path: '/dashboard/comptabilite/entrees-fonds',
        name: 'accounting.entrees-fonds',
        component: () => import('../Pages/entreprise/Dashboard/components/EntreesFondsPage.vue'),
        meta: {
            title: 'Autres entrées de fonds',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'dashboard.master' } },
                { label: 'Comptabilité', to: { name: 'dashboard.accounting' } },
                { label: 'Autres entrées de fonds', to: { name: 'accounting.entrees-fonds' } },
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
    {
        path: '/dashboard/company/profile',
        name: 'dashboard.company.profile',
        component: () => import('../Pages/entreprise/Dashboard/pages/CompanyProfile.vue'),
        meta: {
            title: 'Profil de l\'entreprise',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'dashboard.master' } },
                { label: 'Profil de l\'entreprise', to: { name: 'dashboard.company.profile' } },
            ],
        },
    },
    {
        path: '/agence/dashboard',
        redirect: '/agence/dashboard/master',
    },
    {
        path: '/agence/dashboard/master',
        name: 'agence.master',
        component: () => import('../Pages/agence/Dashboard/components/MasterDashboard.vue'),
        meta: {
            title: 'Tableau de bord Agence',
            breadcrumbs: [{ label: 'Accueil', to: { name: 'agence.master' } }],
        },
    },
    {
        path: '/agence/dashboard/immobilier',
        name: 'agence.immobilier.index',
        component: () => import('../Pages/agence/Dashboard/components/ImmobilierDashboard.vue'),
        meta: {
            title: 'Gestion immobilière',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'agence.master' } },
                { label: 'Immobilier', to: { name: 'agence.immobilier.index' } },
            ],
        },
    },
    {
        path: '/agence/dashboard/immobilier/batiments',
        name: 'agence.immobilier.batiments',
        component: () => import('../Pages/agence/Dashboard/components/BatimentsPage.vue'),
        meta: {
            title: 'Gestion des bâtiments',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'agence.master' } },
                { label: 'Immobilier', to: { name: 'agence.immobilier.index' } },
                { label: 'Bâtiments', to: { name: 'agence.immobilier.batiments' } },
            ],
        },
    },
    {
        path: '/agence/dashboard/immobilier/logements',
        name: 'agence.immobilier.logements',
        component: () => import('../Pages/agence/Dashboard/components/LogementsPage.vue'),
        meta: {
            title: 'Gestion des logements',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'agence.master' } },
                { label: 'Immobilier', to: { name: 'agence.immobilier.index' } },
                { label: 'Logements', to: { name: 'agence.immobilier.logements' } },
            ],
        },
    },
    {
        path: '/agence/dashboard/immobilier/illustrations',
        name: 'agence.immobilier.illustrations',
        component: () => import('../Pages/entreprise/Dashboard/components/IllustrationPage.vue'),
        meta: {
            title: 'Gestion des illustrations',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'agence.master' } },
                { label: 'Immobilier', to: { name: 'agence.immobilier.index' } },
                { label: 'Illustrations', to: { name: 'agence.immobilier.illustrations' } },
            ],
        },
    },
    {
        path: '/agence/dashboard/immobilier/affectations',
        name: 'agence.immobilier.affectations',
        component: () => import('../Pages/agence/Dashboard/components/AffectationPage.vue'),
        meta: {
            title: 'Affectation des logements',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'agence.master' } },
                { label: 'Immobilier', to: { name: 'agence.immobilier.index' } },
                { label: 'Affectations', to: { name: 'agence.immobilier.affectations' } },
            ],
        },
    },
    {
        path: '/agence/dashboard/immobilier/contrats',
        name: 'agence.immobilier.contrats',
        component: () => import('../Pages/agence/Dashboard/components/ContratsBailPage.vue'),
        meta: {
            title: 'Contrats de bail',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'agence.master' } },
                { label: 'Immobilier', to: { name: 'agence.immobilier.index' } },
                { label: 'Contrats', to: { name: 'agence.immobilier.contrats' } },
            ],
        },
    },
    {
        path: '/agence/dashboard/immobilier/locataires',
        name: 'agence.immobilier.locataires',
        component: () => import('../Pages/agence/Dashboard/components/LocatairesPage.vue'),
        meta: {
            title: 'Gestion des locataires',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'agence.master' } },
                { label: 'Immobilier', to: { name: 'agence.immobilier.index' } },
                { label: 'Locataires', to: { name: 'agence.immobilier.locataires' } },
            ],
        },
    },
    {
        path: '/agence/dashboard/immobilier/renouvellements',
        name: 'agence.immobilier.renouvellements',
        component: () => import('../Pages/agence/Dashboard/components/RenouvellementPage.vue'),
        meta: {
            title: 'Renouvellements de contrat',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'agence.master' } },
                { label: 'Immobilier', to: { name: 'agence.immobilier.index' } },
                { label: 'Renouvellements', to: { name: 'agence.immobilier.renouvellements' } },
            ],
        },
    },
    {
        path: '/agence/dashboard/immobilier/engagements',
        name: 'agence.immobilier.engagements',
        component: () => import('../Pages/agence/Dashboard/components/EngagementPage.vue'),
        meta: {
            title: 'Engagements',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'agence.master' } },
                { label: 'Immobilier', to: { name: 'agence.immobilier.index' } },
                { label: 'Engagements', to: { name: 'agence.immobilier.engagements' } },
            ],
        },
    },
    {
        path: '/agence/dashboard/immobilier/etats-des-lieux',
        name: 'agence.immobilier.etats-des-lieux',
        component: () => import('../Pages/agence/Dashboard/components/EtatDesLieuxPage.vue'),
        meta: {
            title: 'États des lieux',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'agence.master' } },
                { label: 'Immobilier', to: { name: 'agence.immobilier.index' } },
                { label: 'États des lieux', to: { name: 'agence.immobilier.etats-des-lieux' } },
            ],
        },
    },
    {
        path: '/agence/dashboard/immobilier/historique',
        name: 'agence.immobilier.historique',
        component: () => import('../Pages/agence/Dashboard/components/HistoriquePage.vue'),
        meta: {
            title: 'Historique immobilier',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'agence.master' } },
                { label: 'Immobilier', to: { name: 'agence.immobilier.index' } },
                { label: 'Historique', to: { name: 'agence.immobilier.historique' } },
            ],
        },
    },
    {
        path: '/agence/dashboard/immobilier/factures',
        name: 'agence.immobilier.factures',
        component: () => import('../Pages/agence/Dashboard/components/FacturationLoyerPage.vue'),
        meta: {
            title: 'Facturation des loyers',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'agence.master' } },
                { label: 'Immobilier', to: { name: 'agence.immobilier.index' } },
                { label: 'Facturation', to: { name: 'agence.immobilier.factures' } },
            ],
        },
    },
    {
        path: '/agence/dashboard/immobilier/paiements',
        name: 'agence.immobilier.paiements',
        component: () => import('../Pages/agence/Dashboard/components/PaiementsLoyerPage.vue'),
        meta: {
            title: 'Paiements de loyer',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'agence.master' } },
                { label: 'Immobilier', to: { name: 'agence.immobilier.index' } },
                { label: 'Paiements', to: { name: 'agence.immobilier.paiements' } },
            ],
        },
    },
    {
        path: '/agence/dashboard/comptabilite',
        name: 'agence.accounting',
        component: () => import('../Pages/agence/Dashboard/components/AccountingDashboard.vue'),
        meta: { title: 'Comptabilité & facturation' },
    },
    {
        path: '/agence/dashboard/comptabilite/factures',
        name: 'agence.accounting.factures',
        component: () => import('../Pages/agence/Dashboard/components/FactureListPage.vue'),
        meta: {
            title: 'Factures',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'agence.master' } },
                { label: 'Comptabilité', to: { name: 'agence.accounting' } },
                { label: 'Factures', to: { name: 'agence.accounting.factures' } },
            ],
        },
    },
    {
        path: '/agence/dashboard/comptabilite/depenses',
        name: 'agence.accounting.depenses',
        component: () => import('../Pages/agence/Dashboard/components/DepensesPage.vue'),
        meta: {
            title: 'Dépenses',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'agence.master' } },
                { label: 'Comptabilité', to: { name: 'agence.accounting' } },
                { label: 'Dépenses', to: { name: 'agence.accounting.depenses' } },
            ],
        },
    },
    {
        path: '/agence/dashboard/comptabilite/entrees-fonds',
        name: 'agence.accounting.entrees-fonds',
        component: () => import('../Pages/agence/Dashboard/components/EntreesFondsPage.vue'),
        meta: {
            title: 'Autres entrées de fonds',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'agence.master' } },
                { label: 'Comptabilité', to: { name: 'agence.accounting' } },
                { label: 'Autres entrées de fonds', to: { name: 'agence.accounting.entrees-fonds' } },
            ],
        },
    },
    {
        path: '/agence/dashboard/maintenance',
        name: 'agence.maintenance',
        component: () => import('../Pages/agence/Dashboard/components/MaintenanceDashboard.vue'),
        meta: { title: 'Maintenance / SAV' },
    },
    {
        path: '/agence/dashboard/rapports',
        name: 'agence.reports',
        component: () => import('../Pages/agence/Dashboard/components/ReportsDashboard.vue'),
        meta: { title: 'Rapports & statistiques' },
    },
    {
        path: '/agence/dashboard/employees',
        name: 'agence.employees',
        component: () => import('../Pages/agence/Dashboard/components/EmployeesPage.vue'),
        meta: {
            title: 'Collaborateurs',
            breadcrumbs: [
                { label: 'Accueil', to: { name: 'agence.master' } },
                { label: 'Collaborateurs', to: { name: 'agence.employees' } },
            ],
        },
    },
];

const router = createRouter({
    history: createWebHashHistory(),
    routes,
    scrollBehavior() {
        return { top: 0 };
    },
});

router.beforeEach((to, from, next) => {
    if (to.path.startsWith('/dashboard/hotel')) {
        alert("🔒 Accès interdit : Le module Hôtellerie est bloqué pour votre entreprise.");
        next({ name: 'dashboard.master' });
    } else {
        next();
    }
});

export default router;
