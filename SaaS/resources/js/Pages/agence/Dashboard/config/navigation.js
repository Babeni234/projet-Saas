/**
 * Configuration de navigation du portail agence
 */
export const navigation = [
    {
        id: 'overview',
        label: 'Vue globale',
        items: [
            {
                name: 'agence.master',
                label: 'Tableau de bord',
                icon: 'layout-dashboard',
                badge: null,
            },
        ],
    },
    {
        id: 'management',
        label: 'Gestion',
        items: [
            {
                id: 'immobilier',
                label: 'Immobilier',
                icon: 'building',
                accent: 'emerald',
                badgeKey: 'immobilier',
                children: [
                    { name: 'agence.immobilier.index', label: "Vue d'ensemble" },
                    { name: 'agence.immobilier.batiments', label: 'Bâtiments' },
                    { name: 'agence.immobilier.logements', label: 'Logements' },
                    { name: 'agence.immobilier.affectations', label: 'Affectation' },
                    { name: 'agence.immobilier.contrats', label: 'Contrats de bail' },
                    { name: 'agence.immobilier.locataires', label: 'Locataires' },
                    { name: 'agence.immobilier.factures', label: 'Facturation' },
                    { name: 'agence.immobilier.paiements', label: 'Paiements' },
                    { name: 'agence.immobilier.renouvellements', label: 'Renouvellements' },
                    { name: 'agence.immobilier.engagements', label: 'Engagements' },
                    { name: 'agence.immobilier.etats-des-lieux', label: 'États des lieux' },
                    { name: 'agence.immobilier.historique', label: 'Historique' },
                ],
            },
        ],
    },
    {
        id: 'modules',
        label: 'Modules',
        items: [
            {
                id: 'accounting',
                label: 'Comptabilité',
                icon: 'calculator',
                accent: 'violet',
                children: [
                    { name: 'agence.accounting', label: "Vue d'ensemble" },
                    { name: 'agence.accounting.facture-creation', label: 'Créer une facture' },
                ],
            },
            { name: 'agence.maintenance', label: 'Maintenance', icon: 'wrench', accent: 'orange', badgeKey: 'maintenance' },
            { name: 'agence.reports', label: 'Rapports', icon: 'chart', accent: 'cyan' },
            { name: 'agence.employees', label: 'Collaborateurs', icon: 'users', accent: 'emerald' },
        ],
    },
];

export const routeTitles = {
    'agence.master': 'Tableau de bord Agence',
    'agence.immobilier.index': 'Gestion immobilière',
    'agence.immobilier.factures': 'Facturation des loyers',
    'agence.immobilier.paiements': 'Paiements de loyer',
    'agence.immobilier.batiments': 'Gestion des bâtiments',
    'agence.immobilier.logements': 'Gestion des logements',
    'agence.immobilier.affectations': 'Affectation des logements',
    'agence.immobilier.contrats': 'Contrats de bail',
    'agence.immobilier.locataires': 'Gestion des locataires',
    'agence.immobilier.renouvellements': 'Renouvellements de contrat',
    'agence.immobilier.engagements': 'Engagements',
    'agence.immobilier.etats-des-lieux': 'États des lieux',
    'agence.immobilier.historique': 'Historique immobilier',
    'agence.accounting': 'Comptabilité & facturation',
    'agence.accounting.facture-creation': 'Création de facture',
    'agence.maintenance': 'Maintenance / SAV',
    'agence.reports': 'Rapports & statistiques',
    'agence.employees': 'Collaborateurs',
};
