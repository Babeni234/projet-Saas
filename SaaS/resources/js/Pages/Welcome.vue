<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed, watch, onMounted } from 'vue';

defineProps({
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
});

const mobileMenuOpen = ref(false);
const locale = ref('fr');

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
                'Que vous dirigiez une agence internationale, un portfolio d\'hôtels boutique ou des actifs privés — PropertyAI s\'adapte à votre modèle opérationnel.',
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
    { label: t.value.dashboard.aiSavings, value: '€2,1M', change: t.value.dashboard.ytd },
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
    </Head>

    <div
        class="min-h-screen overflow-x-hidden bg-[#FAFAF8] text-slate-900 antialiased"
        style="font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif"
    >
        <!-- Ambient background -->
        <div class="pointer-events-none fixed inset-0 z-0">
            <div class="absolute -right-32 top-0 h-[600px] w-[600px] rounded-full bg-amber-100/60 blur-[120px]" />
            <div class="absolute -left-32 top-1/3 h-[500px] w-[500px] rounded-full bg-sky-100/50 blur-[100px]" />
            <div class="absolute bottom-0 right-1/4 h-[400px] w-[400px] rounded-full bg-rose-50/80 blur-[100px]" />
            <div
                class="absolute inset-0 opacity-[0.35]"
                style="background-image: radial-gradient(circle at 1px 1px, #e2e8f0 1px, transparent 0); background-size: 32px 32px"
            />
        </div>

        <!-- Navigation -->
        <nav class="sticky top-0 z-50 border-b border-slate-200/70 bg-white/75 backdrop-blur-xl backdrop-saturate-150">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-6 py-4 lg:px-8">
                <Link href="/" class="group flex shrink-0 items-center gap-3">
                    <div
                        class="relative flex h-10 w-10 items-center justify-center overflow-hidden rounded-xl bg-gradient-to-br from-amber-500 to-amber-700 shadow-md shadow-amber-500/20 transition-transform duration-300 group-hover:scale-105"
                    >
                        <span class="text-sm font-bold tracking-tight text-white">PA</span>
                    </div>
                    <span class="text-lg font-semibold tracking-tight text-slate-900">Property<span class="text-amber-600">AI</span></span>
                </Link>

                <div class="hidden items-center gap-8 lg:flex">
                    <a href="#platform" class="text-sm text-slate-500 transition-colors duration-200 hover:text-slate-900">{{ t.nav.platform }}</a>
                    <a href="#audiences" class="text-sm text-slate-500 transition-colors duration-200 hover:text-slate-900">{{ t.nav.industries }}</a>
                    <a href="#ai" class="text-sm text-slate-500 transition-colors duration-200 hover:text-slate-900">{{ t.nav.aiEngine }}</a>
                    <a href="#results" class="text-sm text-slate-500 transition-colors duration-200 hover:text-slate-900">{{ t.nav.results }}</a>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Language toggle -->
                    <div
                        class="flex items-center rounded-full border border-slate-200 bg-slate-50/80 p-0.5 shadow-sm"
                        role="group"
                        :aria-label="locale === 'fr' ? 'Changer de langue' : 'Change language'"
                    >
                        <button
                            type="button"
                            class="rounded-full px-3 py-1.5 text-xs font-semibold transition-all duration-200"
                            :class="locale === 'fr' ? 'bg-white text-amber-700 shadow-sm ring-1 ring-slate-200/80' : 'text-slate-500 hover:text-slate-700'"
                            @click="setLocale('fr')"
                        >
                            FR
                        </button>
                        <button
                            type="button"
                            class="rounded-full px-3 py-1.5 text-xs font-semibold transition-all duration-200"
                            :class="locale === 'en' ? 'bg-white text-amber-700 shadow-sm ring-1 ring-slate-200/80' : 'text-slate-500 hover:text-slate-700'"
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
                                :href="route('register')"
                                class="group relative overflow-hidden rounded-full px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-amber-500/20 transition-all duration-300 hover:shadow-lg hover:shadow-amber-500/30"
                            >
                                <span class="absolute inset-0 bg-gradient-to-r from-amber-500 via-amber-600 to-amber-700 transition-opacity duration-300 group-hover:opacity-90" />
                                <span class="relative">{{ t.nav.requestAccess }}</span>
                            </Link>
                        </template>
                    </div>

<<<<<<< HEAD
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

            <div
                v-if="mobileMenuOpen"
                class="border-t border-slate-200/70 bg-white/95 px-6 py-6 backdrop-blur-xl lg:hidden"
            >
                <div class="flex flex-col gap-4">
                    <a href="#platform" class="text-sm text-slate-600" @click="mobileMenuOpen = false">{{ t.nav.platform }}</a>
                    <a href="#audiences" class="text-sm text-slate-600" @click="mobileMenuOpen = false">{{ t.nav.industries }}</a>
                    <a href="#ai" class="text-sm text-slate-600" @click="mobileMenuOpen = false">{{ t.nav.aiEngine }}</a>
                    <a href="#results" class="text-sm text-slate-600" @click="mobileMenuOpen = false">{{ t.nav.results }}</a>
                    <div v-if="canLogin" class="flex flex-col gap-3 border-t border-slate-200 pt-4">
                        <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="text-sm font-medium">{{ t.nav.dashboard }}</Link>
                        <template v-else>
                            <Link :href="route('login')" class="text-sm font-medium text-slate-600">{{ t.nav.signIn }}</Link>
                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="rounded-full bg-gradient-to-r from-amber-500 to-amber-600 px-5 py-2.5 text-center text-sm font-semibold text-white shadow-md"
                            >
                                {{ t.nav.requestAccess }}
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero -->
        <section class="relative z-10 mx-auto max-w-7xl px-6 pb-24 pt-16 lg:px-8 lg:pb-32 lg:pt-24">
            <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
                <div class="flex flex-col gap-8">
                    <div
                        class="inline-flex w-fit items-center gap-2.5 rounded-full border border-amber-200/80 bg-white/80 px-4 py-2 shadow-sm backdrop-blur-md"
                    >
                        <span class="relative flex h-2 w-2">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-amber-500 opacity-40" />
                            <span class="relative inline-flex h-2 w-2 rounded-full bg-amber-500" />
                        </span>
                        <span class="text-xs font-medium uppercase tracking-widest text-amber-700">{{ t.hero.badge }}</span>
                    </div>

                    <div class="space-y-6">
                        <h1
                            class="text-4xl font-semibold leading-[1.1] tracking-tight text-slate-900 sm:text-5xl lg:text-6xl"
                            style="font-family: 'Playfair Display', Georgia, serif"
                        >
                            {{ t.hero.titleLine1 }}
                <main class="mt-6">
                    <div class="mb-8 flex justify-center">
                        <Link
                            :href="route('dashboard')"
                            class="px-8 py-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-2xl text-lg font-semibold shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/50 hover:-translate-y-1 transition-all duration-300"
                        >
                            Accéder au Dashboard
                        </Link>
                    </div>
                    <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
                        <a
                            href="https://laravel.com/docs"
                            id="docs-card"
                            class="flex flex-col items-start gap-6 overflow-hidden rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] md:row-span-3 lg:p-10 lg:pb-10 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#FF2D20]"

                        >
                            {{ t.hero.titleLine1 }}
                            <span class="bg-gradient-to-r from-amber-600 via-amber-500 to-amber-700 bg-clip-text text-transparent">
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
                            :href="route('register')"
                            class="group relative inline-flex items-center justify-center gap-2 overflow-hidden rounded-full px-8 py-4 text-sm font-semibold text-white shadow-lg shadow-amber-500/25 transition-all duration-300 hover:shadow-xl hover:shadow-amber-500/30"
                        >
                            <span class="absolute inset-0 bg-gradient-to-r from-amber-500 via-amber-600 to-amber-700 transition-transform duration-300 group-hover:scale-105" />
                            <span class="relative">{{ t.hero.ctaPrimary }}</span>
                            <svg class="relative h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </Link>
                        <a
                            href="#demo"
                            class="inline-flex items-center justify-center gap-2 rounded-full border border-slate-200 bg-white px-8 py-4 text-sm font-semibold text-slate-700 shadow-sm transition-all duration-300 hover:border-amber-300 hover:bg-amber-50/50 hover:text-amber-900"
                        >
                            <svg class="h-4 w-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                            </svg>
                            {{ t.hero.ctaSecondary }}
                        </a>
                    </div>

                    <div class="flex flex-wrap items-center gap-x-6 gap-y-3 pt-2">
                        <div v-for="signal in t.trustSignals" :key="signal" class="flex items-center gap-2 text-xs text-slate-500">
                            <svg class="h-3.5 w-3.5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            {{ signal }}
                        </div>
                    </div>
                </div>

                <!-- Dashboard preview -->
                <div id="demo" class="relative">
                    <div class="absolute -inset-4 rounded-3xl bg-gradient-to-br from-amber-200/40 via-transparent to-sky-200/30 blur-2xl" />
                    <div class="relative rounded-2xl bg-gradient-to-br from-amber-300/50 via-slate-200/50 to-sky-300/40 p-[1px] shadow-2xl shadow-slate-300/30">
                        <div class="overflow-hidden rounded-2xl border border-white/80 bg-white/90 backdrop-blur-2xl">
                            <div class="flex items-center gap-2 border-b border-slate-100 bg-slate-50/80 px-5 py-3">
                                <div class="flex gap-1.5">
                                    <div class="h-2.5 w-2.5 rounded-full bg-red-300/80" />
                                    <div class="h-2.5 w-2.5 rounded-full bg-amber-300/80" />
                                    <div class="h-2.5 w-2.5 rounded-full bg-emerald-300/80" />
                                </div>
                                <span class="ml-2 text-xs text-slate-400">{{ t.dashboard.title }}</span>
                            </div>
                            <div class="space-y-4 p-5">
                                <div class="grid grid-cols-3 gap-3">
                                    <div
                                        v-for="(stat, i) in dashboardStats"
                                        :key="i"
                                        class="rounded-xl border border-slate-100 bg-slate-50/80 p-3 transition-all duration-300 hover:border-amber-200 hover:bg-amber-50/40 hover:shadow-sm"
                                    >
                                        <p class="text-[10px] uppercase tracking-wider text-slate-400">{{ stat.label }}</p>
                                        <p class="mt-1 text-lg font-semibold text-slate-900">{{ stat.value }}</p>
                                        <p class="text-[10px] font-medium text-emerald-600">{{ stat.change }}</p>
                                    </div>
                                </div>
                                <div class="rounded-xl border border-slate-100 bg-white p-4 shadow-sm">
                                    <div class="mb-3 flex items-center justify-between">
                                        <span class="text-xs font-medium text-slate-600">{{ t.dashboard.revenueForecast }}</span>
                                        <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-medium text-amber-700">{{ t.dashboard.live }}</span>
                                    </div>
                                    <div class="flex h-24 items-end gap-1">
                                        <div
                                            v-for="(h, i) in [40, 55, 45, 70, 60, 85, 75, 90, 80, 95, 88, 100]"
                                            :key="i"
                                            class="flex-1 rounded-sm bg-gradient-to-t from-amber-200 to-amber-500 transition-all duration-500 hover:from-amber-300 hover:to-amber-600"
                                            :style="{ height: `${h}%` }"
                                        />
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 rounded-xl border border-emerald-200/80 bg-emerald-50/60 p-3">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-emerald-100">
                                        <svg class="h-4 w-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-emerald-800">{{ t.dashboard.aiInsight }}</p>
                                        <p class="text-[11px] text-emerald-700/80">{{ t.dashboard.insightText }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Social proof strip -->
        <section class="relative z-10 border-y border-slate-200/70 bg-white/60 py-8 backdrop-blur-sm">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <p class="mb-6 text-center text-xs font-medium uppercase tracking-[0.2em] text-slate-400">
                    {{ t.socialProof }}
                </p>
                <div class="flex flex-wrap items-center justify-center gap-x-12 gap-y-4">
                    <span
                        v-for="brand in t.brands"
                        :key="brand"
                        class="text-sm font-semibold tracking-wide text-slate-300 transition-colors duration-300 hover:text-slate-500"
                    >
                        {{ brand }}
                    </span>
                </div>
            </div>
        </section>

        <!-- Audiences -->
        <section id="audiences" class="relative z-10 mx-auto max-w-7xl px-6 py-24 lg:px-8 lg:py-32">
            <div class="mx-auto mb-16 max-w-2xl text-center">
                <p class="mb-4 text-xs font-semibold uppercase tracking-[0.2em] text-amber-600">{{ t.audiences.eyebrow }}</p>
                <h2
                    class="text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl lg:text-5xl"
                    style="font-family: 'Playfair Display', Georgia, serif"
                >
                    {{ t.audiences.title }}
                </h2>
                <p class="mt-4 text-lg text-slate-600">
                    {{ t.audiences.description }}
                </p>
            </div>

            <div id="platform" class="grid gap-6 md:grid-cols-3">
                <div
                    v-for="(audience, index) in t.audiences.items"
                    :key="audience.title"
                    class="group relative rounded-2xl bg-gradient-to-br from-amber-200/60 via-slate-200/40 to-sky-200/40 p-[1px] shadow-sm transition-all duration-500 hover:from-amber-300/70 hover:via-amber-100/50 hover:to-sky-200/50 hover:shadow-lg hover:shadow-amber-100/50"
                >
                    <div class="flex h-full flex-col rounded-2xl bg-white p-8 transition-all duration-500 group-hover:bg-gradient-to-br group-hover:from-white group-hover:to-amber-50/30">
                        <span class="mb-6 text-5xl font-light text-slate-100 transition-colors duration-500 group-hover:text-amber-100">
                            0{{ index + 1 }}
                        </span>
                        <h3 class="mb-3 text-xl font-semibold text-slate-900">{{ audience.title }}</h3>
                        <p class="mb-6 flex-1 text-sm leading-relaxed text-slate-600">{{ audience.description }}</p>
                        <div class="border-t border-slate-100 pt-4">
                            <span class="text-sm font-semibold text-amber-700">{{ audience.metric }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- AI Capabilities -->
        <section id="ai" class="relative z-10 border-t border-slate-200/70 bg-white/50 py-24 lg:py-32">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mb-16 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-xl">
                        <p class="mb-4 text-xs font-semibold uppercase tracking-[0.2em] text-amber-600">{{ t.ai.eyebrow }}</p>
                        <h2
                            class="text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl lg:text-5xl"
                            style="font-family: 'Playfair Display', Georgia, serif"
                        >
                            {{ t.ai.title }}
                        </h2>
                        <p class="mt-4 text-lg text-slate-600">
                            {{ t.ai.description }}
                        </p>
                    </div>
                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="inline-flex w-fit items-center gap-2 text-sm font-semibold text-amber-700 transition-colors duration-200 hover:text-amber-800"
                    >
                        {{ t.ai.explore }}
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </Link>
                </div>

                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="capability in t.ai.capabilities"
                        :key="capability.title"
                        :class="[
                            capability.span,
                            'group relative rounded-2xl bg-gradient-to-br from-slate-200/60 via-white to-amber-100/40 p-[1px] transition-all duration-500 hover:from-amber-200/60 hover:to-sky-100/50 hover:shadow-md hover:shadow-slate-200/60',
                        ]"
                    >
                        <div class="flex h-full flex-col rounded-2xl bg-white/90 p-7 backdrop-blur-sm transition-all duration-500 group-hover:bg-white">
                            <div
                                class="mb-5 flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-amber-100 to-amber-50 ring-1 ring-amber-200/80 transition-transform duration-300 group-hover:scale-110 group-hover:shadow-sm"
                            >
                                <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <h3 class="mb-2 text-lg font-semibold text-slate-900">{{ capability.title }}</h3>
                            <p class="text-sm leading-relaxed text-slate-600">{{ capability.description }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats -->
        <section id="results" class="relative z-10 mx-auto max-w-7xl px-6 py-24 lg:px-8">
            <div class="rounded-2xl bg-gradient-to-br from-amber-200/50 via-slate-100 to-sky-200/40 p-[1px] shadow-xl shadow-slate-200/40">
                <div class="grid gap-px overflow-hidden rounded-2xl bg-slate-100 sm:grid-cols-2 lg:grid-cols-4">
                    <div
                        v-for="stat in t.stats"
                        :key="stat.label"
                        class="group bg-white p-8 text-center transition-all duration-300 hover:bg-amber-50/40"
                    >
                        <p
                            class="bg-gradient-to-b from-amber-600 to-amber-800 bg-clip-text text-4xl font-semibold tracking-tight text-transparent transition-transform duration-300 group-hover:scale-105"
                        >
                            {{ stat.value }}
                        </p>
                        <p class="mt-2 text-sm text-slate-500">{{ stat.label }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section class="relative z-10 border-t border-slate-200/70 bg-[#FAFAF8] py-24 lg:py-32">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mb-16 text-center">
                    <p class="mb-4 text-xs font-semibold uppercase tracking-[0.2em] text-amber-600">{{ t.testimonials.eyebrow }}</p>
                    <h2
                        class="text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl"
                        style="font-family: 'Playfair Display', Georgia, serif"
                    >
                        {{ t.testimonials.title }}
                    </h2>
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <div
                        v-for="testimonial in t.testimonials.items"
                        :key="testimonial.author"
                        class="group rounded-2xl bg-gradient-to-br from-amber-200/40 to-slate-200/30 p-[1px] transition-all duration-500 hover:from-amber-300/50 hover:shadow-lg hover:shadow-amber-100/40"
                    >
                        <div class="rounded-2xl bg-white p-8 shadow-sm">
                            <svg class="mb-4 h-8 w-8 text-amber-200" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10H14.017zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10H0z" />
                            </svg>
                            <p class="mb-6 text-base leading-relaxed text-slate-600 italic">
                                <template v-if="locale === 'fr'">« {{ testimonial.quote }} »</template>
                                <template v-else>"{{ testimonial.quote }}"</template>
                            </p>
                            <div>
                                <p class="font-semibold text-slate-900">{{ testimonial.author }}</p>
                                <p class="text-sm text-slate-500">{{ testimonial.role }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Final CTA -->
        <section class="relative z-10 mx-auto max-w-7xl px-6 pb-24 lg:px-8 lg:pb-32">
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-amber-300/40 via-amber-100/30 to-sky-200/40 p-[1px] shadow-2xl shadow-amber-100/50">
                <div class="relative rounded-3xl bg-gradient-to-br from-white via-amber-50/30 to-white px-8 py-16 text-center backdrop-blur-sm sm:px-16 sm:py-20">
                    <div class="absolute -right-20 -top-20 h-60 w-60 rounded-full bg-amber-100/60 blur-3xl" />
                    <div class="absolute -bottom-20 -left-20 h-60 w-60 rounded-full bg-sky-100/60 blur-3xl" />

                    <div class="relative space-y-6">
                        <h2
                            class="text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl lg:text-5xl"
                            style="font-family: 'Playfair Display', Georgia, serif"
                        >
                            {{ t.cta.title }}
                        </h2>
                        <p class="mx-auto max-w-xl text-lg text-slate-600">
                            {{ t.cta.description }}
                        </p>
                        <div class="flex flex-col items-center justify-center gap-4 pt-4 sm:flex-row">
                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="group relative inline-flex items-center justify-center gap-2 overflow-hidden rounded-full px-10 py-4 text-sm font-semibold text-white shadow-lg shadow-amber-500/25 transition-all duration-300 hover:shadow-xl hover:shadow-amber-500/35"
                            >
                                <span class="absolute inset-0 bg-gradient-to-r from-amber-500 via-amber-600 to-amber-700 transition-transform duration-300 group-hover:scale-105" />
                                <span class="relative">{{ t.cta.primary }}</span>
                            </Link>
                            <a
                                href="#"
                                class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-10 py-4 text-sm font-semibold text-slate-700 shadow-sm transition-all duration-300 hover:border-slate-300 hover:bg-slate-50"
                            >
                                {{ t.cta.secondary }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="relative z-10 border-t border-slate-200/70 bg-white/80 backdrop-blur-xl">
            <div class="mx-auto max-w-7xl px-6 py-16 lg:px-8">
                <div class="grid gap-12 md:grid-cols-2 lg:grid-cols-5">
                    <div class="lg:col-span-2">
                        <div class="mb-4 flex items-center gap-3">
                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-amber-500 to-amber-700 shadow-sm">
                                <span class="text-xs font-bold text-white">PA</span>
                            </div>
                            <span class="text-lg font-semibold text-slate-900">Property<span class="text-amber-600">AI</span></span>
                        </div>
                        <p class="max-w-xs text-sm leading-relaxed text-slate-500">
                            {{ t.footer.tagline }}
                        </p>
                    </div>

                    <div>
                        <h4 class="mb-4 text-sm font-semibold text-slate-900">{{ t.footer.product }}</h4>
                        <ul class="space-y-3 text-sm text-slate-500">
                            <li><a href="#ai" class="transition-colors hover:text-amber-700">{{ t.footer.aiEngine }}</a></li>
                            <li><a href="#platform" class="transition-colors hover:text-amber-700">{{ t.footer.platform }}</a></li>
                            <li><a href="#" class="transition-colors hover:text-amber-700">{{ t.footer.integrations }}</a></li>
                            <li><a href="#" class="transition-colors hover:text-amber-700">{{ t.footer.pricing }}</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="mb-4 text-sm font-semibold text-slate-900">{{ t.footer.company }}</h4>
                        <ul class="space-y-3 text-sm text-slate-500">
                            <li><a href="#" class="transition-colors hover:text-amber-700">{{ t.footer.about }}</a></li>
                            <li><a href="#" class="transition-colors hover:text-amber-700">{{ t.footer.careers }}</a></li>
                            <li><a href="#" class="transition-colors hover:text-amber-700">{{ t.footer.press }}</a></li>
                            <li><a href="#" class="transition-colors hover:text-amber-700">{{ t.footer.contact }}</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="mb-4 text-sm font-semibold text-slate-900">{{ t.footer.legal }}</h4>
                        <ul class="space-y-3 text-sm text-slate-500">
                            <li><a href="#" class="transition-colors hover:text-amber-700">{{ t.footer.privacy }}</a></li>
                            <li><a href="#" class="transition-colors hover:text-amber-700">{{ t.footer.terms }}</a></li>
                            <li><a href="#" class="transition-colors hover:text-amber-700">{{ t.footer.security }}</a></li>
                        </ul>
                    </div>
                </div>

                <div class="mt-12 flex flex-col items-center justify-between gap-4 border-t border-slate-100 pt-8 sm:flex-row">
                    <p class="text-sm text-slate-400">© {{ new Date().getFullYear() }} PropertyAI. {{ t.footer.rights }}</p>
                    <div class="flex items-center gap-5">
                        <a href="#" class="text-slate-400 transition-colors hover:text-amber-700" aria-label="LinkedIn">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 114.128 0 2.063 2.063 0 01-2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                            </svg>
                        </a>
                        <a href="#" class="text-slate-400 transition-colors hover:text-amber-700" aria-label="X">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
