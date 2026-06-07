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
        <div class="pointer-events-none fixed inset-0 z-0">
            <div class="absolute -right-32 top-0 h-[600px] w-[600px] rounded-full bg-amber-100/60 blur-[120px]" />
            <div class="absolute -left-32 top-1/3 h-[500px] w-[500px] rounded-full bg-sky-100/50 blur-[100px]" />
            <div class="absolute bottom-0 right-1/4 h-[400px] w-[400px] rounded-full bg-rose-50/80 blur-[100px]" />
            <div
                class="absolute inset-0 opacity-[0.35]"
                style="background-image: radial-gradient(circle at 1px 1px, #e2e8f0 1px, transparent 0); background-size: 32px 32px"
            />
        </div>

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

        <main class="relative z-10 mx-auto max-w-7xl px-6 pb-24 pt-16 lg:px-8 lg:pb-32 lg:pt-24">
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
                            <span class="block bg-gradient-to-r from-amber-600 via-amber-500 to-amber-700 bg-clip-text text-transparent">
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

                <div id="demo-pane" class="relative">
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
                                        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">{{ stat.label }}</p>
                                        <p class="mt-1 text-base font-bold text-slate-800">{{ stat.value }}</p>
                                        <p class="text-[10px] text-emerald-600 font-medium mt-0.5">{{ stat.change }}</p>
                                    </div>
                                </div>
                                <div class="rounded-xl border border-slate-100 bg-slate-50/80 p-4">
                                    <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                                        <span class="text-xs font-semibold text-slate-700">{{ t.dashboard.revenueForecast }}</span>
                                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2 py-0.5 text-[9px] font-medium text-amber-800 ring-1 ring-inset ring-amber-600/20 animate-pulse">
                                            {{ t.dashboard.live }}
                                        </span>
                                    </div>
                                    <div class="mt-3 h-24 w-full bg-gradient-to-t from-slate-100/50 to-slate-200/40 rounded-lg flex items-end px-4 pb-2 justify-between gap-1">
                                        <div class="w-full bg-amber-500/20 h-8 rounded-t-sm" />
                                        <div class="w-full bg-amber-500/30 h-12 rounded-t-sm" />
                                        <div class="w-full bg-amber-500/40 h-16 rounded-t-sm" />
                                        <div class="w-full bg-amber-600 h-20 rounded-t-sm shadow-md shadow-amber-500/20" />
                                    </div>
                                </div>
                                <div class="flex items-start gap-3 rounded-xl border border-amber-100 bg-amber-50/40 p-3.5">
                                    <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-md bg-amber-500 text-white shadow-sm">
                                        <span class="text-[10px] font-bold">✨</span>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-amber-900">{{ t.dashboard.aiInsight }}</p>
                                        <p class="mt-0.5 text-xs text-amber-800/90 leading-normal">{{ t.dashboard.insightText }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>