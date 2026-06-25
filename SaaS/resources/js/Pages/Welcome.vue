<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed, watch, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
    partners: {
        type: Array,
        default: () => [],
    },
    userCount: {
        type: Number,
        default: 0,
    },
});

const mobileMenuOpen = ref(false);
const locale = ref('fr');

const activePartner = ref(null);
const showModal = ref(false);
const contactForm = ref({
    name: '',
    email: '',
    message: '',
});
const sending = ref(false);
const success = ref(false);
const formError = ref('');

const openPartnerDetails = (partner) => {
    activePartner.value = partner;
    showModal.value = true;
    contactForm.value = { name: '', email: '', message: '' };
    success.value = false;
    formError.value = '';
};

const sendContactEmail = async () => {
    if (!contactForm.value.name || !contactForm.value.email || !contactForm.value.message) {
        formError.value = locale.value === 'fr' ? 'Veuillez remplir tous les champs.' : 'Please fill in all fields.';
        return;
    }
    sending.value = true;
    formError.value = '';
    try {
        await axios.post('/api/partners/contact', {
            partner_id: activePartner.value.id,
            name: contactForm.value.name,
            email: contactForm.value.email,
            message: contactForm.value.message,
        });
        success.value = true;
    } catch (err) {
        formError.value = err.response?.data?.message || (locale.value === 'fr' ? "Erreur lors de l'envoi." : 'Error sending message.');
    } finally {
        sending.value = false;
    }
};

const infoModalOpen = ref(false);
const infoModalType = ref('');
const generalForm = ref({ name: '', email: '', message: '' });
const generalSending = ref(false);
const generalSuccess = ref(false);
const generalError = ref('');

const openInfoModal = (type) => {
    infoModalType.value = type;
    infoModalOpen.value = true;
    generalForm.value = { name: '', email: '', message: '' };
    generalSuccess.value = false;
    generalError.value = '';
};

const sendGeneralContactEmail = async () => {
    if (!generalForm.value.name || !generalForm.value.email || !generalForm.value.message) {
        generalError.value = locale.value === 'fr' ? 'Veuillez remplir tous les champs.' : 'Please fill in all fields.';
        return;
    }
    generalSending.value = true;
    generalError.value = '';
    try {
        await axios.post('/api/contact', {
            name: generalForm.value.name,
            email: generalForm.value.email,
            message: generalForm.value.message,
        });
        generalSuccess.value = true;
    } catch (err) {
        generalError.value = err.response?.data?.message || (locale.value === 'fr' ? "Erreur lors de l'envoi." : 'Error sending message.');
    } finally {
        generalSending.value = false;
    }
};

const infoModalContent = computed(() => {
    const isFr = locale.value === 'fr';
    switch (infoModalType.value) {
        case 'about':
            return {
                title: isFr ? 'À propos de Property AI' : 'About Property AI',
                subtitle: isFr ? "L'intelligence artificielle au service de l'immobilier d'exception" : 'AI-native operating system for elite real estate',
                body: isFr 
                    ? "Fondée en 2024, Property AI est la plateforme technologique de référence pour les gestionnaires d'actifs immobiliers les plus exigeants. Notre mission est de transformer la gestion immobilière en automatisant les tâches répétitives, en maximisant les rendements opérationnels et en offrant une expérience utilisateur sans compromis.\n\nGrâce à nos modèles de Deep Learning brevetés et notre intégration native d'algorithmes prédictifs, nous aidons les agences, les groupes hôteliers de luxe et les investisseurs privés à piloter des portefeuilles de niveau institutionnel avec une efficacité inégalée."
                    : "Founded in 2024, Property AI is the leading technology platform for discerning real estate asset managers. Our mission is to transform property operations by automating repetitive workflows, maximizing yield, and delivering an uncompromising user experience.\n\nWith proprietary deep learning models and native predictive analytics, we empower agencies, luxury hospitality groups, and private investors to command institutional-grade portfolios with unparalleled velocity."
            };
        case 'careers':
            return {
                title: isFr ? 'Carrières chez Property AI' : 'Careers at Property AI',
                subtitle: isFr ? "Rejoignez la révolution de la PropTech" : 'Join the future of property intelligence',
                body: isFr
                    ? "Nous sommes toujours à la recherche de talents exceptionnels pour repousser les limites de la technologie immobilière. Que vous soyez ingénieur IA, développeur Full-Stack, designer UI/UX ou expert en gestion de la relation client, votre place est peut-être chez nous.\n\nConsultez nos opportunités ouvertes en nous écrivant directement ou découvrez nos valeurs axées sur l'innovation continue, la sécurité sans faille et l'excellence du service."
                    : "We are constantly seeking outstanding talent to redefine property tech. Whether you are an AI researcher, full-stack engineer, product designer, or customer success specialist, we would love to hear from you.\n\nExplore our open positions by writing to us, and join a team dedicated to continuous innovation, institutional-grade security, and operating excellence."
            };
        case 'press':
            return {
                title: isFr ? 'Espace Presse' : 'Press Room',
                subtitle: isFr ? 'Actualités et ressources Property AI' : 'Latest news and media assets',
                body: isFr
                    ? "Retrouvez tous nos communiqués de presse, notre kit de marque officiel et nos dernières annonces de partenariat. Pour toute demande d'interview ou de reportage sur le futur de l'intelligence artificielle appliquée à l'immobilier de luxe, contactez notre équipe média.\n\nNos récentes annonces :\n- Levée de fonds de série A pour accélérer notre moteur IA.\n- Lancement du module d'analyse prédictive de rendement.\n- Partenariats stratégiques avec des acteurs clés de l'hôtellerie."
                    : "Access our official press releases, brand kits, and recent announcement details. For media inquiries or interviews regarding the future of artificial intelligence in high-end real estate, please reach out to our communications team.\n\nRecent highlights:\n- Closed Series A funding to accelerate our proprietary AI engine.\n- Launched next-generation predictive maintenance algorithms.\n- Strategic partnerships with global boutique hospitality leaders."
            };
        case 'privacy':
            return {
                title: isFr ? 'Politique de Confidentialité' : 'Privacy Policy',
                subtitle: isFr ? 'Protection de vos données personnelles' : 'Data protection and compliance standards',
                body: isFr
                    ? "Chez Property AI, la confidentialité de vos données est notre priorité absolue. Nous mettons en œuvre des mesures de sécurité de niveau bancaire (chiffrement AES-256 en transit et au repos) et sommes entièrement conformes au Règlement Général sur la Protection des Données (RGPD).\n\nNous ne vendons ni ne partageons vos données opérationnelles avec des tiers. Toutes les données financières, d'identification et de communication sont stockées sur des serveurs hautement sécurisés situés en Union Européenne."
                    : "At Property AI, protecting your operational data is our highest priority. We employ banking-grade security protocols (AES-256 encryption in transit and at rest) and maintain full compliance with European GDPR frameworks.\n\nWe do not sell or monetize your portfolio data. All tenant records, transaction logs, and system metrics are isolated and hosted on secure cloud infrastructure located within the EU."
            };
        case 'terms':
            return {
                title: isFr ? "Conditions Générales d'Utilisation" : 'Terms of Service',
                subtitle: isFr ? "Cadre juridique d'utilisation de la plateforme" : 'Software subscription agreements',
                body: isFr
                    ? "L'utilisation de Property AI est régie par nos Conditions Générales d'Utilisation (CGU). En souscrivant à nos services, vous acceptez le modèle d'abonnement annuel ou mensuel sélectionné, notre politique d'utilisation acceptable et nos engagements de niveau de service (SLA de 99,99% de disponibilité).\n\nTout manquement à la sécurité du système ou utilisation frauduleuse de nos API entraînera la suspension immédiate du compte sans préavis."
                    : "The usage of Property AI services is governed by our Terms of Service (ToS). By activating a subscription plan, you agree to the billing cycles, acceptable usage guidelines, and our Service Level Agreements (99.99% uptime guarantee).\n\nAny unauthorized penetration testing, system abuse, or API misuse will result in immediate termination of access without liability."
            };
        case 'security':
            return {
                title: isFr ? 'Sécurité de la Plateforme' : 'Platform Security',
                subtitle: isFr ? 'Normes de sécurité et de conformité' : 'Enterprise-grade protection frameworks',
                body: isFr
                    ? "Notre plateforme est auditée et certifiée SOC 2 Type II. Nous effectuons des audits de sécurité indépendants trimestriels, des tests de pénétration continus et gérons un programme de Bug Bounty actif.\n\nCaractéristiques clés :\n- Authentification multifacteur (MFA) obligatoire pour tous les comptes administratifs.\n- Pare-feu applicatifs (WAF) avancés contre les attaques DDoS.\n- Sauvegardes automatisées en continu avec redondance géographique."
                    : "Our infrastructure is SOC 2 Type II certified and continuously monitored. We run quarterly third-party audits, continuous penetration testing, and operate an active bug bounty program.\n\nKey Security Pillars:\n- Mandatory Multi-Factor Authentication (MFA) for administrative control panels.\n- Advanced Web Application Firewalls (WAF) defending against DDoS vectors.\n- Real-time geographical database replication and automated disaster recovery plans."
            };
        case 'contact':
            return {
                title: isFr ? 'Contactez Property AI' : 'Contact Property AI',
                subtitle: isFr ? 'Une question ? Notre équipe est à votre écoute.' : 'Have a question? We are here to help.',
                body: ''
            };
        default:
            return { title: '', subtitle: '', body: '' };
    }
});

const getInitials = (name) => {
    if (!name) return 'PA';
    return name.split(' ').map(n => n[0]).join('').slice(0, 2).toUpperCase();
};

const getGradient = (name) => {
    if (!name) return 'from-indigo-500 to-cyan-500';
    let hash = 0;
    for (let i = 0; i < name.length; i++) {
        hash = name.charCodeAt(i) + ((hash << 5) - hash);
    }
    const colors = [
        'from-blue-500 to-indigo-600',
        'from-purple-500 to-pink-600',
        'from-emerald-500 to-teal-600',
        'from-amber-500 to-orange-600',
        'from-rose-500 to-red-600',
        'from-cyan-500 to-blue-600'
    ];
    const index = Math.abs(hash) % colors.length;
    return colors[index];
};

const translations = {
    en: {
        meta: {
            title: 'PropertyAI — Intelligent Property Management for Elite Portfolios',
        },
        nav: {
            platform: 'Platform',
            industries: 'Industries',
            aiEngine: 'AI Engine',
            results: 'Results',
            dashboard: 'Dashboard',
            signIn: 'Sign In',
            requestAccess: 'Request Access',
        },
        hero: {
            badge: 'AI-Native Property Intelligence',
            titleLine1: 'Command your portfolio with',
            titleHighlight: 'institutional precision',
            description:
                'PropertyAI is the operating system for elite real estate. Agencies, luxury hospitality groups, and private investors rely on our AI to automate operations, maximize yield, and deliver white-glove experiences—at scale.',
            ctaPrimary: 'Start Your Free Trial',
            ctaSecondary: 'Watch Platform Demo',
        },
        trustSignals: ['SOC 2 Type II', 'GDPR Compliant', '256-bit Encryption', '99.99% Uptime SLA'],
        dashboard: {
            title: 'PropertyAI Command Center',
            portfolioValue: 'Portfolio Value',
            occupancy: 'Occupancy',
            aiSavings: 'AI Savings',
            ytd: 'YTD',
            revenueForecast: 'Revenue Forecast — AI Model v3',
            live: 'Live',
            aiInsight: 'AI Insight',
            insightText: '3 lease renewals flagged for proactive outreach — projected retention +18%',
        },
        socialProof: 'Trusted by leading firms across Europe & the Middle East',
        brands: ['Prestige Estates', 'Whitfield Group', 'Lumière Hotels', 'Atlas Capital', 'Maison & Co.'],
        audiences: {
            eyebrow: 'Built for discerning operators',
            title: 'One platform. Three worlds of excellence.',
            description:
                'Whether you manage a global agency, a portfolio of boutique hotels, or private holdings—PropertyAI adapts to your operating model.',
            items: [
                {
                    title: 'Real Estate Agencies',
                    description:
                        'Orchestrate multi-portfolio operations from a single command center. AI handles lead routing, lease renewals, and compliance—your team focuses on closing.',
                    metric: '62% faster deal cycles',
                },
                {
                    title: 'Luxury Hotels & Residences',
                    description:
                        'Deliver five-star guest experiences at scale. Dynamic pricing, concierge automation, and predictive housekeeping keep occupancy and NPS at peak.',
                    metric: '28% RevPAR uplift',
                },
                {
                    title: 'Private Investors',
                    description:
                        'Institutional-grade visibility across every asset class. Portfolio intelligence, automated reporting, and risk alerts—without the overhead.',
                    metric: '40% lower operating costs',
                },
            ],
        },
        ai: {
            eyebrow: 'The AI Engine',
            title: 'Intelligence that never sleeps',
            description:
                'Proprietary models trained on millions of property transactions—working 24/7 to protect and grow your assets.',
            explore: 'Explore the full platform',
            capabilities: [
                {
                    title: 'Predictive Portfolio Intelligence',
                    description:
                        'Machine learning models forecast occupancy, cash flow, and market shifts 90 days ahead—so you act before competitors react.',
                    span: 'lg:col-span-2',
                },
                {
                    title: 'Autonomous Revenue Engine',
                    description:
                        'AI-driven dynamic pricing across short-term, long-term, and hybrid assets. Maximizes yield without manual rate tables.',
                    span: '',
                },
                {
                    title: 'Intelligent Tenant Lifecycle',
                    description:
                        'From AI-powered screening to automated lease workflows and sentiment-aware communications—every touchpoint, optimized.',
                    span: '',
                },
                {
                    title: 'Predictive Maintenance',
                    description:
                        'IoT and historical data combine to flag failures before they happen. Cut emergency repairs and protect asset value.',
                    span: '',
                },
                {
                    title: 'Document Intelligence',
                    description:
                        'OCR and NLP extract clauses, deadlines, and obligations from contracts instantly. Never miss a critical date again.',
                    span: 'lg:col-span-2',
                },
            ],
        },
        stats: [
            { value: '40%', label: 'Average cost reduction' },
            { value: '3.5×', label: 'Operational velocity' },
            { value: '98%', label: 'Tenant & guest satisfaction' },
            { value: '500+', label: 'Premium properties managed' },
        ],
        testimonials: {
            eyebrow: 'Client voices',
            title: 'Results that speak for themselves',
            items: [
                {
                    quote: 'PropertyAI transformed how we manage our €200M portfolio. What took our team weeks now happens overnight—with better accuracy.',
                    author: 'Sophie Laurent',
                    role: 'Managing Director, Prestige Estates Paris',
                },
                {
                    quote: 'Our boutique hotel group saw RevPAR climb within the first quarter. The AI pricing alone paid for the platform ten times over.',
                    author: 'James Whitfield',
                    role: 'CEO, Whitfield Hospitality Group',
                },
            ],
        },
        cta: {
            title: 'Elevate your portfolio today',
            description:
                "Join the operators who've replaced spreadsheets and guesswork with AI-driven certainty. Your 14-day trial includes full platform access—no credit card required.",
            primary: 'Claim Your Free Trial',
            secondary: 'Book a Private Demo',
        },
        footer: {
            tagline:
                'The AI-assisted property management platform for agencies, luxury hospitality, and private investors who demand excellence.',
            product: 'Product',
            company: 'Company',
            legal: 'Legal',
            aiEngine: 'AI Engine',
            platform: 'Platform',
            integrations: 'Integrations',
            pricing: 'Pricing',
            about: 'About',
            careers: 'Careers',
            press: 'Press',
            contact: 'Contact',
            privacy: 'Privacy',
            terms: 'Terms',
            security: 'Security',
            rights: 'All rights reserved.',
        },
    },
    fr: {
        meta: {
            title: 'PropertyAI — Gestion immobilière intelligente pour portfolios d\'exception',
        },
        nav: {
            platform: 'Plateforme',
            industries: 'Secteurs',
            aiEngine: 'Moteur IA',
            results: 'Résultats',
            dashboard: 'Tableau de bord',
            signIn: 'Connexion',
            requestAccess: 'Demander l\'accès',
        },
        hero: {
            badge: 'Intelligence immobilière native IA',
            titleLine1: 'Pilotez votre portfolio avec une',
            titleHighlight: 'précision institutionnelle',
            description:
                'PropertyAI est le système d\'exploitation de l\'immobilier d\'exception. Agences, groupes hôteliers de luxe et investisseurs privés s\'appuient sur notre IA pour automatiser les opérations, maximiser les rendements et offrir une expérience sur mesure — à grande échelle.',
            ctaPrimary: 'Essai gratuit',
            ctaSecondary: 'Voir la démo',
        },
        trustSignals: ['SOC 2 Type II', 'Conforme RGPD', 'Chiffrement 256 bits', 'SLA 99,99 % de disponibilité'],
        dashboard: {
            title: 'Centre de commande PropertyAI',
            portfolioValue: 'Valeur du portfolio',
            occupancy: 'Taux d\'occupation',
            aiSavings: 'Économies IA',
            ytd: 'Depuis le début de l\'année',
            revenueForecast: 'Prévision de revenus — Modèle IA v3',
            live: 'En direct',
            aiInsight: 'Insight IA',
            insightText: '3 renouvellements de bail identifiés pour une relance proactive — rétention projetée +18 %',
        },
        socialProof: 'Plébiscité par les leaders en Europe et au Moyen-Orient',
        brands: ['Prestige Estates', 'Whitfield Group', 'Lumière Hotels', 'Atlas Capital', 'Maison & Co.'],
        audiences: {
            eyebrow: 'Conçu pour les opérateurs exigeants',
            title: 'Une plateforme. Trois univers d\'excellence.',
            description:
                'Que vous dirigiez une agence internationale, un portfolio d\'hôtels boutique ou des actifs privés — PropertyAI s\'adaptte à votre modèle opérationnel.',
            items: [
                {
                    title: 'Agences immobilières',
                    description:
                        'Orchestrez vos opérations multi-portfolios depuis un centre de commande unique. L\'IA gère le routage des leads, les renouvellements et la conformité — votre équipe se concentre sur la conclusion.',
                    metric: '62 % de cycles de vente accélérés',
                },
                {
                    title: 'Hôtels & résidences de luxe',
                    description:
                        'Offrez une expérience cinq étoiles à grande échelle. Tarification dynamique, conciergerie automatisée et housekeeping prédictif maintiennent occupation et NPS au sommet.',
                    metric: '+28 % de RevPAR',
                },
                {
                    title: 'Investisseurs privés',
                    description:
                        'Visibilité de niveau institutionnel sur chaque classe d\'actifs. Intelligence portfolio, reporting automatisé et alertes risques — sans la lourdeur administrative.',
                    metric: '40 % de coûts opérationnels en moins',
                },
            ],
        },
        ai: {
            eyebrow: 'Le moteur IA',
            title: 'Une intelligence qui ne dort jamais',
            description:
                'Des modèles propriétaires entraînés sur des millions de transactions immobilières — actifs 24 h/24 pour protéger et faire croître vos actifs.',
            explore: 'Explorer la plateforme',
            capabilities: [
                {
                    title: 'Intelligence portfolio prédictive',
                    description:
                        'Des modèles de machine learning anticipent l\'occupation, les flux de trésorerie et les évolutions du marché à 90 jours — pour agir avant vos concurrents.',
                    span: 'lg:col-span-2',
                },
                {
                    title: 'Moteur de revenus autonome',
                    description:
                        'Tarification dynamique pilotée par l\'IA sur actifs courte durée, longue durée et hybrides. Maximisez le rendement sans tableaux de tarifs manuels.',
                    span: '',
                },
                {
                    title: 'Cycle de vie locataire intelligent',
                    description:
                        'Du screening IA aux workflows de bail automatisés et communications sensibles au sentiment — chaque point de contact, optimisé.',
                    span: '',
                },
                {
                    title: 'Maintenance prédictive',
                    description:
                        'IoT et données historiques combinés pour signaler les pannes avant qu\'elles surviennent. Réduisez les réparations d\'urgence et protégez la valeur de vos actifs.',
                    span: '',
                },
                {
                    title: 'Intelligence documentaire',
                    description:
                        'OCR et NLP extraient instantanément clauses, échéances et obligations des contrats. Ne manquez plus jamais une date critique.',
                    span: 'lg:col-span-2',
                },
            ],
        },
        stats: [
            { value: '40 %', label: 'Réduction moyenne des coûts' },
            { value: '3,5×', label: 'Vélocité opérationnelle' },
            { value: '98 %', label: 'Satisfaction locataires & clients' },
            { value: '500+', label: 'Biens premium gérés' },
        ],
        testimonials: {
            eyebrow: 'Témoignages clients',
            title: 'Des résultats qui parlent d\'eux-mêmes',
            items: [
                {
                    quote: 'PropertyAI a transformé la gestion de notre portfolio de 200 M€. Ce qui prenait des semaines à notre équipe se fait désormais en une nuit — avec une précision accrue.',
                    author: 'Sophie Laurent',
                    role: 'Directrice générale, Prestige Estates Paris',
                },
                {
                    quote: 'Notre groupe hôtelier boutique a vu son RevPAR grimper dès le premier trimestre. La tarification IA à elle seule a rentabilisé la plateforme dix fois.',
                    author: 'James Whitfield',
                    role: 'PDG, Whitfield Hospitality Group',
                },
            ],
        },
        cta: {
            title: 'Élevez votre portfolio dès aujourd\'hui',
            description:
                'Rejoignez les opérateurs qui ont remplacé tableurs et approximations par la certitude de l\'IA. Votre essai de 14 jours inclut l\'accès complet — sans carte bancaire.',
            primary: 'Commencer l\'essai gratuit',
            secondary: 'Réserver une démo privée',
        },
        footer: {
            tagline:
                'La plateforme de gestion immobilière assistée par IA pour agences, hôtellerie de luxe et investisseurs privés exigeants.',
            product: 'Produit',
            company: 'Entreprise',
            legal: 'Légal',
            aiEngine: 'Moteur IA',
            platform: 'Plateforme',
            integrations: 'Intégrations',
            pricing: 'Tarifs',
            about: 'À propos',
            careers: 'Carrières',
            press: 'Presse',
            contact: 'Contact',
            privacy: 'Confidentialité',
            terms: 'Conditions',
            security: 'Sécurité',
            rights: 'Tous droits réservés.',
        },
    },
};

const t = computed(() => translations[locale.value]);

const dashboardStats = computed(() => [
    { label: t.value.dashboard.portfolioValue, value: '€847M', change: '+12,4 %' },
    { label: t.value.dashboard.occupancy, value: '94,2 %', change: '+3,1 %' },
    { label: locale.value === 'fr' ? 'Membres du réseau' : 'Network Members', value: `+${props.userCount || 0}`, change: locale.value === 'fr' ? 'Utilisateurs connectés' : 'Active users' },
]);

const allStats = computed(() => [
    ...t.value.stats,
    {
        value: `+${props.userCount || 0}`,
        label: locale.value === 'fr' ? 'Membres du réseau' : 'Active network members'
    }
]);

function setLocale(lang) {
    locale.value = lang;
    mobileMenuOpen.value = false;
}

onMounted(() => {
    const saved = localStorage.getItem('propertyai-locale');
    if (saved === 'en' || saved === 'fr') {
        locale.value = saved;
    }
    document.documentElement.lang = locale.value;
});

watch(locale, (val) => {
    localStorage.setItem('propertyai-locale', val);
    document.documentElement.lang = val;
});
</script>

<template>
    <Head :title="t.meta.title">
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link
            href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700|playfair-display:500,600,700&display=swap"
            rel="stylesheet"
        />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </Head>

    <div
        class="min-h-screen overflow-x-hidden bg-[#FAFAF8] text-slate-900 antialiased"
        style="font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif"
    >
        <!-- Background Gradients -->
        <div class="pointer-events-none fixed inset-0 z-0">
            <div class="absolute -right-32 top-0 h-[600px] w-[600px] rounded-full bg-amber-100/60 blur-[120px]" />
            <div class="absolute -left-32 top-1/3 h-[500px] w-[500px] rounded-full bg-sky-100/50 blur-[100px]" />
            <div class="absolute bottom-0 right-1/4 h-[400px] w-[400px] rounded-full bg-rose-50/80 blur-[100px]" />
            <div
                class="absolute inset-0 opacity-[0.35]"
                style="background-image: radial-gradient(circle at 1px 1px, #e2e8f0 1px, transparent 0); background-size: 32px 32px"
            />
        </div>

        <!-- Navigation Bar -->
        <nav class="sticky top-0 z-40 border-b border-slate-200/70 bg-white/75 backdrop-blur-xl backdrop-saturate-150">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-6 py-4 lg:px-8">
                <Link href="/" class="group flex shrink-0 items-center gap-3">
                    <div
                        class="relative flex h-10 w-10 items-center justify-center overflow-hidden rounded-xl bg-gradient-to-br from-indigo-500 to-cyan-500 shadow-md shadow-indigo-500/20 transition-transform duration-300 group-hover:scale-105"
                    >
                        <!-- Property AI Icon representation -->
                        <span class="text-sm font-bold tracking-tight text-white">PA</span>
                    </div>
                    <span class="text-lg font-semibold tracking-tight text-slate-900">Property<span class="text-indigo-600">AI</span></span>
                </Link>

                <div class="hidden items-center gap-8 lg:flex">
                    <a href="#platform" class="text-sm text-slate-500 transition-colors duration-200 hover:text-slate-900">{{ t.nav.platform }}</a>
                    <a href="#audiences" class="text-sm text-slate-500 transition-colors duration-200 hover:text-slate-900">{{ t.nav.industries }}</a>
                    <a href="#ai" class="text-sm text-slate-500 transition-colors duration-200 hover:text-slate-900">{{ t.nav.aiEngine }}</a>
                    <a href="#results" class="text-sm text-slate-500 transition-colors duration-200 hover:text-slate-900">{{ t.nav.results }}</a>
                    <Link :href="route('immotok.feed')" class="text-sm font-bold text-red-500 hover:text-red-700 transition-colors duration-200 flex items-center gap-1.5">
                        <i class="fab fa-tiktok"></i>
                        <span>ImmoTok</span>
                    </Link>
                </div>

                <div class="flex items-center gap-3">
                    <div
                        class="flex items-center rounded-full border border-slate-200 bg-slate-50/80 p-0.5 shadow-sm"
                        role="group"
                        :aria-label="locale === 'fr' ? 'Changer de langue' : 'Change language'"
                    >
                        <button
                            type="button"
                            class="rounded-full px-3 py-1.5 text-xs font-semibold transition-all duration-200"
                            :class="locale === 'fr' ? 'bg-white text-indigo-700 shadow-sm ring-1 ring-slate-200/80' : 'text-slate-500 hover:text-slate-700'"
                            @click="setLocale('fr')"
                        >
                            FR
                        </button>
                        <button
                            type="button"
                            class="rounded-full px-3 py-1.5 text-xs font-semibold transition-all duration-200"
                            :class="locale === 'en' ? 'bg-white text-indigo-700 shadow-sm ring-1 ring-slate-200/80' : 'text-slate-500 hover:text-slate-700'"
                            @click="setLocale('en')"
                        >
                            EN
                        </button>
                    </div>

                    <div v-if="canLogin" class="hidden items-center gap-4 md:flex">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="text-sm font-medium text-slate-600 transition-colors duration-200 hover:text-slate-900"
                        >
                            {{ t.nav.dashboard }}
                        </Link>
                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="text-sm font-medium text-slate-600 transition-colors duration-200 hover:text-slate-900"
                            >
                                {{ t.nav.signIn }}
                            </Link>
                            <Link
                                v-if="canRegister"
                                :href="route('subscription')"
                                class="group relative overflow-hidden rounded-full px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-indigo-500/20 transition-all duration-300 hover:shadow-lg hover:shadow-indigo-500/30"
                            >
                                <span class="absolute inset-0 bg-gradient-to-r from-indigo-500 via-indigo-600 to-indigo-700 transition-opacity duration-300 group-hover:opacity-90" />
                                <span class="relative">{{ t.nav.requestAccess }}</span>
                            </Link>
                        </template>
                    </div>

                    <button
                        class="flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-700 shadow-sm lg:hidden"
                        :aria-label="locale === 'fr' ? 'Ouvrir le menu' : 'Toggle menu'"
                        @click="mobileMenuOpen = !mobileMenuOpen"
                    >
                        <svg v-if="!mobileMenuOpen" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg v-else class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <div
                v-if="mobileMenuOpen"
                class="border-t border-slate-200/70 bg-white/95 px-6 py-6 backdrop-blur-xl lg:hidden"
            >
                <div class="flex flex-col gap-4">
                    <a href="#platform" class="text-sm text-slate-600" @click="mobileMenuOpen = false">{{ t.nav.platform }}</a>
                    <a href="#audiences" class="text-sm text-slate-600" @click="mobileMenuOpen = false">{{ t.nav.industries }}</a>
                    <a href="#ai" class="text-sm text-slate-600" @click="mobileMenuOpen = false">{{ t.nav.aiEngine }}</a>
                    <a href="#results" class="text-sm text-slate-600" @click="mobileMenuOpen = false">{{ t.nav.results }}</a>
                    <Link :href="route('immotok.feed')" class="text-sm font-bold text-red-550 flex items-center gap-2" @click="mobileMenuOpen = false">
                        <i class="fab fa-tiktok"></i>
                        <span>ImmoTok Feed</span>
                    </Link>
                    <div v-if="canLogin" class="flex flex-col gap-3 border-t border-slate-200 pt-4">
                        <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="text-sm font-medium">{{ t.nav.dashboard }}</Link>
                        <template v-else>
                            <Link :href="route('login')" class="text-sm font-medium text-slate-600">{{ t.nav.signIn }}</Link>
                            <Link
                                v-if="canRegister"
                                :href="route('subscription')"
                                class="rounded-full bg-gradient-to-r from-indigo-500 to-indigo-600 px-5 py-2.5 text-center text-sm font-semibold text-white shadow-md"
                            >
                                {{ t.nav.requestAccess }}
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </nav>

        <!-- 1. HERO SECTION -->
        <header id="platform" class="relative z-10 mx-auto max-w-7xl px-6 pb-16 pt-16 lg:px-8 lg:pt-24">
            <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
                <!-- Left Column: Hero Text -->
                <div class="flex flex-col gap-8">
                    <div
                        class="inline-flex w-fit items-center gap-2.5 rounded-full border border-indigo-200/80 bg-white/80 px-4 py-2 shadow-sm backdrop-blur-md"
                    >
                        <span class="relative flex h-2 w-2">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-indigo-500 opacity-40" />
                            <span class="relative inline-flex h-2 w-2 rounded-full bg-indigo-500" />
                        </span>
                        <span class="text-xs font-medium uppercase tracking-widest text-indigo-700">{{ t.hero.badge }}</span>
                    </div>

                    <div class="space-y-6">
                        <h1
                            class="text-4xl font-semibold leading-[1.1] tracking-tight text-slate-900 sm:text-5xl lg:text-6xl"
                            style="font-family: 'Playfair Display', Georgia, serif"
                        >
                            {{ t.hero.titleLine1 }}
                            <span class="block bg-gradient-to-r from-indigo-600 via-blue-500 to-cyan-600 bg-clip-text text-transparent">
                                {{ t.hero.titleHighlight }}
                            </span>
                        </h1>
                        <p class="max-w-xl text-lg leading-relaxed text-slate-600">
                            {{ t.hero.description }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                        <Link
                            v-if="canRegister"
                            :href="route('subscription')"
                            class="group relative inline-flex items-center justify-center gap-2 overflow-hidden rounded-full px-8 py-4 text-sm font-semibold text-white shadow-lg shadow-indigo-500/25 transition-all duration-300 hover:shadow-xl hover:shadow-indigo-500/30"
                        >
                            <span class="absolute inset-0 bg-gradient-to-r from-indigo-500 via-indigo-600 to-indigo-700 transition-transform duration-300 group-hover:scale-105" />
                            <span class="relative">{{ t.hero.ctaPrimary }}</span>
                            <svg class="relative h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </Link>
                        <a
                            href="#demo-pane"
                            class="inline-flex items-center justify-center gap-2 rounded-full border border-slate-200 bg-white px-8 py-4 text-sm font-semibold text-slate-700 shadow-sm transition-all duration-300 hover:border-indigo-300 hover:bg-indigo-50/50 hover:text-indigo-900"
                        >
                            <svg class="h-4 w-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                            </svg>
                            {{ t.hero.ctaSecondary }}
                        </a>
                        <Link
                            :href="route('immotok.feed')"
                            class="group relative inline-flex items-center justify-center gap-2 overflow-hidden rounded-full bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-650 px-8 py-4 text-sm font-bold text-white shadow-lg shadow-red-500/20 transition-all duration-300 hover:shadow-xl hover:shadow-red-500/35 active:scale-95 cursor-pointer"
                        >
                            <i class="fab fa-tiktok"></i>
                            <span>Découvrir ImmoTok</span>
                        </Link>
                    </div>

                    <div class="flex flex-wrap items-center gap-x-6 gap-y-3 pt-2">
                        <div v-for="signal in t.trustSignals" :key="signal" class="flex items-center gap-2 text-xs text-slate-500">
                            <svg class="h-3.5 w-3.5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            {{ signal }}
                        </div>
                    </div>
                </div>

                <!-- Right Column: Interactive Dashboard Mockup -->
                <div id="demo-pane" class="relative">
                    <div class="absolute -inset-4 rounded-3xl bg-gradient-to-br from-indigo-200/40 via-transparent to-sky-200/30 blur-2xl" />
                    <div class="relative rounded-2xl bg-gradient-to-br from-indigo-300/50 via-slate-200/50 to-sky-300/40 p-[1px] shadow-2xl shadow-slate-300/30">
                        <div class="overflow-hidden rounded-2xl border border-white/80 bg-white/90 backdrop-blur-2xl">
                            <!-- Windows title bar controls -->
                            <div class="flex items-center gap-2 border-b border-slate-100 bg-slate-50/80 px-5 py-3">
                                <div class="flex gap-1.5">
                                    <div class="h-2.5 w-2.5 rounded-full bg-red-300/80" />
                                    <div class="h-2.5 w-2.5 rounded-full bg-amber-300/80" />
                                    <div class="h-2.5 w-2.5 rounded-full bg-emerald-300/80" />
                                </div>
                                <span class="ml-2 text-xs text-slate-400 font-medium">{{ t.dashboard.title }}</span>
                            </div>
                            <!-- Mock Data Cards -->
                            <div class="space-y-4 p-5">
                                <div class="grid grid-cols-3 gap-3">
                                    <div
                                        v-for="(stat, i) in dashboardStats"
                                        :key="i"
                                        class="rounded-xl border border-slate-100 bg-slate-50/80 p-3 transition-all duration-300 hover:border-indigo-200 hover:bg-indigo-50/40 hover:shadow-sm"
                                    >
                                        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">{{ stat.label }}</p>
                                        <p class="mt-1 text-base font-bold text-slate-800">{{ stat.value }}</p>
                                        <p class="text-[10px] text-indigo-600 font-medium mt-0.5">{{ stat.change }}</p>
                                    </div>
                                </div>
                                <!-- Mock Forecast Chart -->
                                <div class="rounded-xl border border-slate-100 bg-slate-50/80 p-4">
                                    <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                                        <span class="text-xs font-semibold text-slate-700">{{ t.dashboard.revenueForecast }}</span>
                                        <span class="inline-flex items-center gap-1 rounded-full bg-indigo-50 px-2 py-0.5 text-[9px] font-medium text-indigo-800 ring-1 ring-inset ring-indigo-600/20 animate-pulse">
                                            {{ t.dashboard.live }}
                                        </span>
                                    </div>
                                    <div class="mt-3 h-24 w-full bg-gradient-to-t from-slate-100/50 to-slate-200/40 rounded-lg flex items-end px-4 pb-2 justify-between gap-1">
                                        <div class="w-full bg-indigo-500/20 h-8 rounded-t-sm" />
                                        <div class="w-full bg-indigo-500/30 h-12 rounded-t-sm" />
                                        <div class="w-full bg-indigo-500/40 h-16 rounded-t-sm" />
                                        <div class="w-full bg-indigo-600 h-20 rounded-t-sm shadow-md shadow-indigo-500/20" />
                                    </div>
                                </div>
                                <!-- Mock AI Insights block -->
                                <div class="flex items-start gap-3 rounded-xl border border-indigo-100 bg-indigo-50/40 p-3.5">
                                    <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-md bg-indigo-500 text-white shadow-sm">
                                        <span class="text-[10px] font-bold">✨</span>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-indigo-900">{{ t.dashboard.aiInsight }}</p>
                                        <p class="mt-0.5 text-xs text-indigo-800/90 leading-normal">{{ t.dashboard.insightText }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- 2. PARTNERS CAROUSEL SECTION -->
        <section v-if="partners && partners.length > 0" class="relative z-10 py-16 border-y border-slate-200/50 bg-slate-50/30 backdrop-blur-sm overflow-hidden">
            <div class="mx-auto max-w-7xl px-6 mb-8 text-center lg:px-8">
                <span class="text-xs font-bold tracking-widest text-indigo-600 uppercase">{{ locale === 'fr' ? 'Nos Partenaires' : 'Our Partners' }}</span>
                <h2 class="text-3xl font-semibold leading-tight text-slate-900 mt-2" style="font-family: 'Playfair Display', Georgia, serif">
                    {{ locale === 'fr' ? 'Les entreprises qui nous font confiance' : 'Companies that trust our platform' }}
                </h2>
                <p class="text-slate-500 text-sm mt-2 max-w-xl mx-auto">
                    {{ locale === 'fr' ? 'Cliquez sur l\'une de nos entreprises partenaires pour découvrir leur profil et entrer en relation directe.' : 'Click on one of our partner companies to discover their profile and establish contact.' }}
                </p>
            </div>
            
            <div class="partners-marquee-container relative overflow-hidden py-4">
                <div class="partners-marquee flex gap-8 items-center animate-scroll">
                    <!-- Repeated multiple times to ensure infinite seamless scrolling loop -->
                    <div 
                        v-for="partner in [...partners, ...partners, ...partners, ...partners]" 
                        :key="partner.id + '-' + Math.random()"
                        class="partner-card select-none cursor-pointer flex items-center gap-3.5 bg-white border border-slate-200/60 hover:border-indigo-300 px-6 py-4.5 rounded-2xl shadow-sm hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 hover:bg-slate-50/50 transition-all duration-300 backdrop-blur-md"
                        @click="openPartnerDetails(partner)"
                    >
                        <img 
                            v-if="partner.logo_url && !partner.logo_url.includes('property-ai-logo.svg')" 
                            :src="partner.logo_url" 
                            class="h-10 w-10 rounded-xl object-cover border border-slate-200/50 bg-white p-1" 
                            alt="Logo"
                        />
                        <div 
                            v-else 
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br text-white text-xs font-black shadow-inner"
                            :class="getGradient(partner.name)"
                        >
                            {{ getInitials(partner.name) }}
                        </div>
                        <div class="text-left min-w-0">
                            <p class="font-extrabold text-sm text-slate-800 truncate max-w-[140px]">{{ partner.name }}</p>
                            <p class="text-[9px] text-indigo-600 font-bold uppercase tracking-wider">
                                {{ partner.business_type === 'hotel' ? (locale === 'fr' ? 'Hôtellerie' : 'Hospitality') : (locale === 'fr' ? 'Immobilier' : 'Real Estate') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. AUDIENCES/SECTEURS SECTION -->
        <section id="audiences" class="relative z-10 mx-auto max-w-7xl px-6 py-24 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <p class="text-xs font-bold uppercase tracking-widest text-indigo-600">{{ t.audiences.eyebrow }}</p>
                <h2 class="text-3xl font-semibold leading-tight text-slate-900 sm:text-4xl mt-3" style="font-family: 'Playfair Display', Georgia, serif">
                    {{ t.audiences.title }}
                </h2>
                <p class="mt-4 text-base text-slate-500 leading-relaxed">
                    {{ t.audiences.description }}
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div 
                    v-for="(item, index) in t.audiences.items" 
                    :key="index"
                    class="group relative rounded-3xl border border-slate-200/80 bg-white/60 p-8 shadow-sm hover:shadow-xl hover:border-indigo-200 hover:-translate-y-1 transition-all duration-300 backdrop-blur-md text-left flex flex-col justify-between"
                >
                    <div class="space-y-4">
                        <div class="h-12 w-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300 shadow-sm">
                            <i class="fa-solid" :class="index === 0 ? 'fa-building-shield' : index === 1 ? 'fa-hotel' : 'fa-chart-line'"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">{{ item.title }}</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">{{ item.description }}</p>
                    </div>
                    <div class="mt-8 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-400">{{ locale === 'fr' ? 'Performance' : 'Impact Metric' }}</span>
                        <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full">{{ item.metric }}</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. AI ENGINE CAPABILITIES SECTION -->
        <section id="ai" class="relative z-10 mx-auto max-w-7xl px-6 py-24 lg:px-8 border-t border-slate-200/60">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <p class="text-xs font-bold uppercase tracking-widest text-indigo-600">{{ t.ai.eyebrow }}</p>
                <h2 class="text-3xl font-semibold leading-tight text-slate-900 sm:text-4xl mt-3" style="font-family: 'Playfair Display', Georgia, serif">
                    {{ t.ai.title }}
                </h2>
                <p class="mt-4 text-base text-slate-500 leading-relaxed">
                    {{ t.ai.description }}
                </p>
            </div>

            <div class="grid lg:grid-cols-3 gap-6">
                <div 
                    v-for="(cap, idx) in t.ai.capabilities" 
                    :key="idx"
                    :class="cap.span ? cap.span : ''"
                    class="group relative rounded-3xl border border-slate-200/80 bg-white/60 p-8 shadow-sm hover:shadow-lg hover:border-indigo-100 hover:scale-[1.01] transition-all duration-300 backdrop-blur-md text-left flex flex-col justify-between"
                >
                    <div class="space-y-4">
                        <div class="h-10 w-10 rounded-xl bg-indigo-50/50 text-indigo-600 flex items-center justify-center group-hover:bg-indigo-500 group-hover:text-white transition-colors duration-300">
                            <i class="fa-solid" :class="idx === 0 ? 'fa-brain' : idx === 1 ? 'fa-wand-magic-sparkles' : idx === 2 ? 'fa-users-viewfinder' : idx === 3 ? 'fa-screwdriver-wrench' : 'fa-file-invoice'"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">{{ cap.title }}</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">{{ cap.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 5. STATISTICS SECTION -->
        <section class="relative z-10 py-16 bg-gradient-to-br from-indigo-600 via-blue-600 to-cyan-600 text-white rounded-3xl mx-6 lg:mx-8 shadow-xl shadow-indigo-500/10">
            <div class="mx-auto max-w-7xl px-8">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8 text-center">
                    <div v-for="(stat, idx) in allStats" :key="idx" class="space-y-2">
                        <p class="text-4xl font-extrabold tracking-tight sm:text-5xl" style="font-family: 'Playfair Display', Georgia, serif">
                            {{ stat.value }}
                        </p>
                        <p class="text-xs font-medium text-indigo-100 uppercase tracking-wider">
                            {{ stat.label }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 6. TESTIMONIALS SECTION -->
        <section id="results" class="relative z-10 mx-auto max-w-7xl px-6 py-24 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <p class="text-xs font-bold uppercase tracking-widest text-indigo-600">{{ t.testimonials.eyebrow }}</p>
                <h2 class="text-3xl font-semibold leading-tight text-slate-900 sm:text-4xl mt-3" style="font-family: 'Playfair Display', Georgia, serif">
                    {{ t.testimonials.title }}
                </h2>
            </div>

            <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                <div 
                    v-for="(item, idx) in t.testimonials.items" 
                    :key="idx"
                    class="rounded-3xl border border-slate-200/80 bg-white/60 p-8 shadow-sm backdrop-blur-md text-left flex flex-col justify-between relative"
                >
                    <span class="absolute top-6 right-8 text-6xl text-indigo-100 font-serif pointer-events-none select-none">“</span>
                    <p class="text-sm text-slate-650 italic leading-relaxed relative z-10 mb-6">
                        {{ item.quote }}
                    </p>
                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 text-white font-bold flex items-center justify-center text-sm">
                            {{ item.author.charAt(0) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900">{{ item.author }}</p>
                            <p class="text-xs text-slate-400">{{ item.role }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 7. CTA SECTION -->
        <section class="relative z-10 mx-auto max-w-7xl px-6 py-12 lg:px-8 text-center mb-12">
            <div class="relative overflow-hidden rounded-3xl bg-slate-900 text-white py-16 px-8 shadow-2xl">
                <!-- Background decorative glowing circle -->
                <div class="absolute -right-32 -bottom-32 h-80 w-80 rounded-full bg-indigo-500/25 blur-3xl" />
                <div class="absolute -left-32 -top-32 h-80 w-80 rounded-full bg-cyan-500/25 blur-3xl" />

                <div class="relative z-10 max-w-2xl mx-auto space-y-6">
                    <h2 class="text-3xl font-bold tracking-tight sm:text-4xl" style="font-family: 'Playfair Display', Georgia, serif">
                        {{ t.cta.title }}
                    </h2>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-xl mx-auto">
                        {{ t.cta.description }}
                    </p>
                    <div class="flex flex-col gap-4 sm:flex-row justify-center items-center pt-4">
                        <Link
                            v-if="canRegister"
                            :href="route('subscription')"
                            class="w-full sm:w-auto px-8 py-4 bg-white text-slate-900 hover:bg-slate-100 rounded-full font-bold shadow-lg transition-all duration-300"
                        >
                            {{ t.cta.primary }}
                        </Link>
                        <a
                            href="#platform"
                            class="w-full sm:w-auto px-8 py-4 border border-slate-700 hover:border-slate-500 rounded-full font-bold transition-all duration-300"
                        >
                            {{ t.cta.secondary }}
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- 8. FOOTER -->
        <footer class="relative z-10 border-t border-slate-200/80 bg-white/60 py-16 backdrop-blur-md">
            <div class="mx-auto max-w-7xl px-6 lg:px-8 grid grid-cols-2 md:grid-cols-5 gap-8 text-left">
                <!-- Logo & Tagline column -->
                <div class="col-span-2 space-y-4">
                    <div class="flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-indigo-500 to-cyan-500 text-white font-bold text-xs">
                            PA
                        </div>
                        <span class="font-bold text-slate-900">Property<span class="text-indigo-600">AI</span></span>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed max-w-sm">
                        {{ t.footer.tagline }}
                    </p>
                    
                    <!-- Social Networks -->
                    <div class="flex items-center gap-3 pt-2">
                        <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer" class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 hover:bg-indigo-50 text-slate-500 hover:text-indigo-600 border border-slate-200/60 transition-all shadow-sm">
                            <i class="fa-brands fa-linkedin-in text-xs"></i>
                        </a>
                        <a href="https://twitter.com" target="_blank" rel="noopener noreferrer" class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 hover:bg-indigo-50 text-slate-500 hover:text-indigo-600 border border-slate-200/60 transition-all shadow-sm">
                            <i class="fa-brands fa-x-twitter text-xs"></i>
                        </a>
                        <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 hover:bg-indigo-50 text-slate-500 hover:text-indigo-600 border border-slate-200/60 transition-all shadow-sm">
                            <i class="fa-brands fa-facebook-f text-xs"></i>
                        </a>
                        <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 hover:bg-indigo-50 text-slate-500 hover:text-indigo-600 border border-slate-200/60 transition-all shadow-sm">
                            <i class="fa-brands fa-instagram text-xs"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Product links -->
                <div>
                    <h4 class="text-xs font-bold text-slate-800 uppercase tracking-widest mb-4">{{ locale === 'fr' ? 'Plateforme' : 'Platform' }}</h4>
                    <ul class="space-y-2 text-xs text-slate-500">
                        <li><a href="#platform" class="hover:text-indigo-600 transition-colors">{{ locale === 'fr' ? 'Plateforme' : 'Platform' }}</a></li>
                        <li><a href="#ai" class="hover:text-indigo-600 transition-colors">{{ locale === 'fr' ? 'Moteur IA' : 'AI Engine' }}</a></li>
                        <li><a href="#audiences" class="hover:text-indigo-600 transition-colors">{{ locale === 'fr' ? 'Intégrations' : 'Integrations' }}</a></li>
                        <li><a href="#results" class="hover:text-indigo-600 transition-colors">{{ locale === 'fr' ? 'Tarifs' : 'Pricing' }}</a></li>
                    </ul>
                </div>

                <!-- Company links -->
                <div>
                    <h4 class="text-xs font-bold text-slate-800 uppercase tracking-widest mb-4">{{ t.footer.company }}</h4>
                    <ul class="space-y-2 text-xs text-slate-500">
                        <li><a href="#" @click.prevent="openInfoModal('about')" class="hover:text-indigo-600 transition-colors">{{ t.footer.about }}</a></li>
                        <li><a href="#" @click.prevent="openInfoModal('careers')" class="hover:text-indigo-600 transition-colors">{{ t.footer.careers }}</a></li>
                        <li><a href="#" @click.prevent="openInfoModal('press')" class="hover:text-indigo-600 transition-colors">{{ t.footer.press }}</a></li>
                        <li><a href="#" @click.prevent="openInfoModal('contact')" class="hover:text-indigo-600 transition-colors">{{ t.footer.contact }}</a></li>
                    </ul>
                </div>

                <!-- Legal links -->
                <div>
                    <h4 class="text-xs font-bold text-slate-800 uppercase tracking-widest mb-4">{{ t.footer.legal }}</h4>
                    <ul class="space-y-2 text-xs text-slate-500">
                        <li><a href="#" @click.prevent="openInfoModal('privacy')" class="hover:text-indigo-600 transition-colors">{{ t.footer.privacy }}</a></li>
                        <li><a href="#" @click.prevent="openInfoModal('terms')" class="hover:text-indigo-600 transition-colors">{{ t.footer.terms }}</a></li>
                        <li><a href="#" @click.prevent="openInfoModal('security')" class="hover:text-indigo-600 transition-colors">{{ t.footer.security }}</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="mx-auto max-w-7xl px-6 lg:px-8 mt-12 pt-6 border-t border-slate-100 flex flex-col sm:flex-row justify-between items-center text-xs text-slate-400 gap-4">
                <p>© {{ new Date().getFullYear() }} Property AI. {{ t.footer.rights }}</p>
                <div class="flex gap-4">
                    <span>v{{ laravelVersion }}</span>
                    <span>PHP {{ phpVersion }}</span>
                </div>
            </div>
        </footer>

        <!-- 9. PARTNER DETAILS POPUP MODAL -->
        <Transition name="fade">
            <div v-if="showModal && activePartner" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md" @click.self="showModal = false">
                <div class="bg-gradient-to-br from-white via-white to-indigo-50/5 rounded-3xl shadow-2xl max-w-4xl w-full border border-slate-200/80 overflow-hidden relative animate-scale-up grid md:grid-cols-2 text-left">
                    <!-- Close Button -->
                    <button class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 text-2xl font-semibold z-10" @click="showModal = false">×</button>

                    <!-- Left Column: Company Profile Details -->
                    <div class="p-8 border-r border-slate-100 flex flex-col justify-between bg-slate-50/40">
                        <div>
                            <!-- Company Logo / Initials -->
                            <div class="flex items-center gap-4 mb-6">
                                <img 
                                    v-if="activePartner.logo_url && !activePartner.logo_url.includes('property-ai-logo.svg')" 
                                    :src="activePartner.logo_url" 
                                    class="h-16 w-16 rounded-2xl object-cover border border-slate-200 p-1 bg-white shadow-sm" 
                                    alt="Logo"
                                />
                                <div 
                                    v-else 
                                    class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br text-white text-xl font-bold shadow-md"
                                    :class="getGradient(activePartner.name)"
                                >
                                    {{ getInitials(activePartner.name) }}
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-slate-950">{{ activePartner.name }}</h3>
                                    <p class="text-xs text-indigo-600 font-semibold uppercase tracking-wider">
                                        {{ activePartner.business_type === 'hotel' ? (locale === 'fr' ? 'Hôtellerie' : 'Hospitality') : (locale === 'fr' ? 'Immobilier' : 'Real Estate') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Details list -->
                            <div class="space-y-4 text-sm text-slate-600">
                                <div>
                                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Adresse</span>
                                    <span class="font-medium text-slate-800">{{ activePartner.address || '—' }}, {{ activePartner.city }}, {{ activePartner.country }}</span>
                                </div>
                                <div>
                                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Téléphone</span>
                                    <a :href="`tel:${activePartner.phone}`" class="font-medium text-slate-800 hover:text-indigo-600 transition-colors">{{ activePartner.phone || '—' }}</a>
                                </div>
                                <div>
                                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Email</span>
                                    <a :href="`mailto:${activePartner.email}`" class="font-medium text-slate-800 hover:text-indigo-600 transition-colors">{{ activePartner.email || '—' }}</a>
                                </div>
                            </div>
                        </div>

                        <!-- Trust stamp -->
                        <div class="mt-8 border-t border-slate-200/60 pt-4 flex items-center gap-2.5 text-xs text-slate-400">
                            <svg class="h-4 w-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            {{ locale === 'fr' ? 'Partenaire agréé Property AI' : 'Verified Property AI Partner' }}
                        </div>
                    </div>

                    <!-- Right Column: Interactive Email Form -->
                    <div class="p-8 flex flex-col justify-center relative">
                        <!-- Success Animation Overlay -->
                        <div v-if="success" class="absolute inset-0 bg-white z-10 flex flex-col items-center justify-center p-8 text-center animate-fade-in">
                            <div class="w-16 h-16 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mb-4 border border-emerald-100 shadow-sm">
                                <svg class="w-8 h-8 animate-scale-up" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-slate-900 mb-1">
                                {{ locale === 'fr' ? 'Message envoyé !' : 'Message Sent!' }}
                            </h4>
                            <p class="text-xs text-slate-500 leading-relaxed max-w-xs mx-auto">
                                {{ locale === 'fr' ? 'Votre message a été transmis avec succès à l\'entreprise. Ils vous recontacteront par e-mail dans les plus brefs délais.' : 'Your message has been successfully transmitted. The company will contact you via email shortly.' }}
                            </p>
                            <button 
                                class="mt-6 px-6 py-2.5 bg-slate-100 text-slate-700 rounded-xl font-bold hover:bg-slate-200 transition-colors text-xs" 
                                @click="showModal = false"
                            >
                                {{ locale === 'fr' ? 'Fermer' : 'Close' }}
                            </button>
                        </div>

                        <h4 class="text-lg font-bold text-slate-900 mb-1">
                            {{ locale === 'fr' ? 'Contacter l\'entreprise' : 'Contact Company' }}
                        </h4>
                        <p class="text-xs text-slate-400 mb-6">
                            {{ locale === 'fr' ? 'Envoyez un e-mail directement à leur représentant.' : 'Send an email directly to their representative.' }}
                        </p>

                        <!-- Error Alert -->
                        <div v-if="formError" class="mb-4 p-3 bg-red-50 border border-red-100 text-red-600 rounded-xl text-xs flex gap-2 items-center">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            {{ formError }}
                        </div>

                        <form @submit.prevent="sendContactEmail" class="space-y-4">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">
                                    {{ locale === 'fr' ? 'Votre nom complet' : 'Your Full Name' }}
                                </label>
                                <input 
                                    v-model="contactForm.name" 
                                    type="text" 
                                    required 
                                    class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none text-xs transition-all" 
                                    placeholder="Ex: Jean Dupont" 
                                />
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">
                                    {{ locale === 'fr' ? 'Votre adresse e-mail' : 'Your Email Address' }}
                                </label>
                                <input 
                                    v-model="contactForm.email" 
                                    type="email" 
                                    required 
                                    class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none text-xs transition-all" 
                                    placeholder="Ex: jean.dupont@gmail.com" 
                                />
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">
                                    {{ locale === 'fr' ? 'Votre message' : 'Your Message' }}
                                </label>
                                <textarea 
                                    v-model="contactForm.message" 
                                    rows="4" 
                                    required 
                                    class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none text-xs transition-all resize-none" 
                                    placeholder="Ex: Bonjour, je souhaiterais collaborer..."
                                ></textarea>
                            </div>
                            
                            <button 
                                type="submit" 
                                :disabled="sending" 
                                class="w-full py-3.5 bg-gradient-to-r from-indigo-500 to-indigo-700 text-white rounded-xl font-bold shadow-md shadow-indigo-500/20 hover:shadow-lg hover:shadow-indigo-500/30 hover:scale-[1.01] active:scale-[0.99] transition-all flex items-center justify-center gap-2 text-xs"
                            >
                                <svg v-if="sending" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>{{ sending ? (locale === 'fr' ? 'Envoi...' : 'Sending...') : (locale === 'fr' ? 'Envoyer le message' : 'Send Message') }}</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </Transition>

        <!-- 10. INFORMATION DETAILS POPUP MODAL -->
        <Transition name="fade">
            <div v-if="infoModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md" @click.self="infoModalOpen = false">
                <div class="bg-gradient-to-br from-white via-white to-indigo-50/5 rounded-3xl shadow-2xl max-w-2xl w-full border border-slate-200/80 overflow-hidden relative animate-scale-up p-8 text-left">
                    <!-- Close Button -->
                    <button class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 text-2xl font-semibold z-10" @click="infoModalOpen = false">×</button>

                    <div class="flex items-center gap-3.5 mb-6 pb-4 border-b border-slate-100">
                        <div class="h-12 w-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-sm">
                            <i class="fa-solid" :class="[
                                infoModalType === 'about' ? 'fa-circle-info' :
                                infoModalType === 'careers' ? 'fa-briefcase' :
                                infoModalType === 'press' ? 'fa-newspaper' :
                                infoModalType === 'contact' ? 'fa-paper-plane' :
                                infoModalType === 'privacy' ? 'fa-user-shield' :
                                infoModalType === 'terms' ? 'fa-file-signature' : 'fa-shield-halved'
                            ]"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-950">{{ infoModalContent.title }}</h3>
                            <p class="text-xs text-slate-400 font-semibold">{{ infoModalContent.subtitle }}</p>
                        </div>
                    </div>

                    <!-- Modal Body / Content -->
                    <div class="text-sm text-slate-650 leading-relaxed space-y-4 whitespace-pre-wrap">
                        <p v-if="infoModalContent.body" class="text-slate-600">{{ infoModalContent.body }}</p>

                        <!-- Contact Form inside the info modal -->
                        <div v-if="infoModalType === 'contact'" class="relative mt-4">
                            <!-- Success state -->
                            <div v-if="generalSuccess" class="py-8 text-center animate-fade-in">
                                <div class="w-16 h-16 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4 border border-emerald-100 shadow-sm">
                                    <svg class="w-8 h-8 animate-scale-up" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-bold text-slate-900 mb-1">
                                    {{ locale === 'fr' ? 'Message envoyé !' : 'Message Sent!' }}
                                </h4>
                                <p class="text-xs text-slate-500 leading-relaxed max-w-xs mx-auto">
                                    {{ locale === 'fr' ? 'Votre message a été transmis avec succès à l\'équipe de support de Property AI.' : 'Your inquiry has been successfully sent to the Property AI team.' }}
                                </p>
                                <button class="mt-6 px-6 py-2.5 bg-slate-100 text-slate-700 rounded-xl font-bold hover:bg-slate-200 transition-colors text-xs" @click="infoModalOpen = false">
                                    {{ locale === 'fr' ? 'Fermer' : 'Close' }}
                                </button>
                            </div>

                            <!-- Form -->
                            <form v-else @submit.prevent="sendGeneralContactEmail" class="space-y-4">
                                <div v-if="generalError" class="p-3 bg-red-50 border border-red-100 text-red-600 rounded-xl text-xs flex gap-2 items-center">
                                    <i class="fa-solid fa-circle-exclamation"></i>
                                    <span>{{ generalError }}</span>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">
                                            {{ locale === 'fr' ? 'Votre nom complet' : 'Your Full Name' }}
                                        </label>
                                        <input v-model="generalForm.name" type="text" required class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none text-xs transition-all" placeholder="Ex: Jean Dupont" />
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">
                                            {{ locale === 'fr' ? 'Votre adresse e-mail' : 'Your Email Address' }}
                                        </label>
                                        <input v-model="generalForm.email" type="email" required class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none text-xs transition-all" placeholder="Ex: jean.dupont@gmail.com" />
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">
                                        {{ locale === 'fr' ? 'Votre message' : 'Your Message' }}
                                    </label>
                                    <textarea v-model="generalForm.message" rows="4" required class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none text-xs transition-all resize-none" placeholder="Ex: Bonjour, je souhaiterais collaborer..."></textarea>
                                </div>
                                <button type="submit" :disabled="generalSending" class="w-full py-3.5 bg-gradient-to-r from-indigo-500 to-indigo-700 text-white rounded-xl font-bold shadow-md shadow-indigo-500/20 hover:shadow-lg hover:shadow-indigo-500/30 transition-all flex items-center justify-center gap-2 text-xs">
                                    <svg v-if="generalSending" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>{{ generalSending ? (locale === 'fr' ? 'Envoi...' : 'Sending...') : (locale === 'fr' ? 'Envoyer le message' : 'Send Message') }}</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>

<style scoped>
@keyframes scroll-partners {
  0% { transform: translateX(0); }
  100% { transform: translateX(-50%); }
}

.partners-marquee-container {
  mask-image: linear-gradient(to right, transparent, white 20%, white 80%, transparent);
  -webkit-mask-image: linear-gradient(to right, transparent, white 20%, white 80%, transparent);
}

.partners-marquee {
  display: flex;
  width: max-content;
  animation: scroll-partners 40s linear infinite;
}

.partners-marquee:hover {
  animation-play-state: paused;
}

.partner-card {
  flex-shrink: 0;
}

/* Animations */
.animate-fade-in {
  animation: fadeIn 0.4s ease-out forwards;
}

.animate-scale-up {
  animation: scaleUp 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes scaleUp {
  from { opacity: 0; transform: scale(0.95); }
  to { opacity: 1; transform: scale(1); }
}

/* Transitions */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>