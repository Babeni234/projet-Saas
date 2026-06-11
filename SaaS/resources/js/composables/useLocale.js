/**
 * useLocale — Système de traduction FR / EN global
 * Stocke la langue dans localStorage, réactif dans toute l'application.
 */
import { ref, readonly } from 'vue';

// ── State global partagé ────────────────────────────────────────────────────
const locale = ref(localStorage.getItem('app_locale') || 'fr');

// ── Dictionnaire de traductions ─────────────────────────────────────────────
const dict = {
    fr: {},   // FR = clé par défaut, on retourne la clé telle quelle
    en: {
        // Navigation — sections
        'Vue globale':  'Overview',
        'Gestion':      'Management',
        'Modules':      'Modules',

        // Navigation — items entreprise
        'Tableau de bord':            'Dashboard',
        'Immobilier':                 'Real Estate',
        "Vue d'ensemble":             'Overview',
        'Agences':                    'Agencies',
        'Batiments':                  'Buildings',
        'Biens Immobiliers':          'Properties',
        'Illustrations':              'Illustrations',
        'Affectation des biens':      'Property Assignment',
        'Affectation':                'Assignment',
        'Contrats de bail':           'Lease Contracts',
        'Locataires':                 'Tenants',
        'Facturation':                'Billing',
        'Paiements de loyer':         'Rent Payments',
        'Paiements':                  'Payments',
        'Renouvellements':            'Renewals',
        'Engagements':                'Commitments',
        'Etats des lieux':            'Inspections',
        'Historique':                 'History',

        // Navigation — hotellerie
        'Hotellerie':                 'Hospitality',
        'Reservations':               'Reservations',
        'Check-in / out':             'Check-in / out',
        'Chambres':                   'Rooms',
        'Clients':                    'Guests',
        'Services':                   'Services',
        'Nettoyage':                  'Housekeeping',
        'Tarifs':                     'Pricing',
        'Personnel':                  'Staff',
        'Equipements':                'Equipment',

        // Navigation — modules
        'Comptabilite':               'Accounting',
        'Comptabilite & facturation': 'Accounting & Billing',
        'Creer une facture':          'Create Invoice',
        'Creation de facture':        'Create Invoice',
        'Maintenance':                'Maintenance',
        'Maintenance / SAV':          'Maintenance / Support',
        'Rapports':                   'Reports',
        'Rapports & statistiques':    'Reports & Statistics',
        'Cartographie':               'Mapping',
        'Roles & permissions':        'Roles & Permissions',
        'Collaborateurs':             'Staff',

        // Route titles — entreprise
        'Portail entreprise':         'Enterprise Portal',
        'Gestion immobiliere':        'Real Estate Management',
        'Facturation des loyers':     'Rent Billing',
        'Gestion des agences':        'Agency Management',
        'Nouvelle agence':            'New Agency',
        'Detail de l agence':         'Agency Details',
        'Modifier l agence':          'Edit Agency',
        'Gestion des batiments':      'Buildings Management',
        'Gestion des biens immobiliers': 'Properties Management',
        'Gestion des illustrations':  'Illustrations Management',
        'Gestion des locataires':     'Tenants Management',
        'Renouvellements de contrat': 'Contract Renewals',
        'Historique immobilier':      'Real Estate History',
        'Gestion hoteliere':          'Hotel Management',
        'Check-in / Check-out':       'Check-in / Check-out',
        'Gestion des chambres':       'Room Management',
        'Gestion des clients':        'Guest Management',
        'Services hoteliers':         'Hotel Services',
        'Tableau de bord Agence':     'Agency Dashboard',

        // Route titles — agence
        'Portail agence':             'Agency Portal',

        // Header / boutons
        'Actualiser':                 'Refresh',
        'Profil':                     'Profile',
        'Deconnexion':                'Logout',
        'Deconnexion en cours...':    'Logging out...',
        'Annuler':                    'Cancel',
        'Se Deconnecter':             'Log Out',

        // Sidebar
        'Administrateur':             'Administrator',
        'Portail':                    'Portal',
        'Module Bloque':              'Locked Module',
        'Fermer':                     'Close',
        'Contacter l Administration': 'Contact Administration',
        'Bientot disponible':         'Coming soon',
        'Collaborateur':              'Collaborator',
        'Agent':                      'Agent',
        'Mon Agence':                 'My Agency',
        'votre entreprise':           'your company',
    },
};

/**
 * Normalise une chaine : supprime accents, remplace & par 'and', trim.
 * Sert de cle de lookup dans le dictionnaire en.
 */
function normalize(str) {
    return String(str)
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/&/g, '&')
        .trim();
}

// ── Composable ──────────────────────────────────────────────────────────────
export function useLocale() {
    /**
     * t(key) — retourne la traduction ou la cle telle quelle en FR.
     */
    const t = (key) => {
        if (!key) return key;
        if (locale.value === 'fr') return key;
        // Cherche exact d'abord, puis normalise
        const exact = dict.en[key];
        if (exact !== undefined) return exact;
        const normalized = normalize(key);
        return dict.en[normalized] ?? key;
    };

    const toggleLocale = () => {
        locale.value = locale.value === 'fr' ? 'en' : 'fr';
        localStorage.setItem('app_locale', locale.value);
    };

    const setLocale = (lang) => {
        locale.value = lang;
        localStorage.setItem('app_locale', lang);
    };

    return {
        locale: readonly(locale),
        t,
        toggleLocale,
        setLocale,
    };
}
