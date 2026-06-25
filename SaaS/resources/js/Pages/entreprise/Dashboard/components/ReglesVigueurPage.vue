<template>
    <div class="flex flex-col gap-8 p-6">
        <!-- Header -->
        <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Lois & Réglementations</h1>
                <p class="text-sm text-slate-500">Cadre juridique de la gestion immobilière et des contrats de bail.</p>
            </div>
            <div>
                <button
                    @click="refreshRegulations"
                    :disabled="loading || refreshing"
                    class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-violet-600 via-violet-700 to-indigo-700 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-violet-200/50 transition-all hover:scale-[1.02] hover:shadow-xl hover:shadow-violet-300/60 active:scale-[0.98] focus:ring-2 focus:ring-violet-500/50 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer"
                >
                    <svg
                        v-if="refreshing"
                        class="h-5 w-5 animate-spin text-white"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <svg
                        v-else
                        class="h-5 w-5 text-white"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.228 5.68M19 19v-5h-5.18" />
                    </svg>
                    {{ refreshing ? 'Mise à jour via l\'IA...' : 'Actualiser via l\'IA' }}
                </button>
            </div>
        </div>

        <!-- Loading state -->
        <div v-if="loading" class="flex flex-col items-center justify-center min-h-[400px] bg-white rounded-2xl border border-slate-200 p-8 shadow-sm gap-4">
            <div class="h-12 w-12 animate-spin rounded-full border-4 border-slate-200 border-t-violet-600"></div>
            <p class="text-sm font-semibold text-slate-500">Récupération des lois et réglementations locales...</p>
        </div>

        <!-- Content Area -->
        <div v-else class="flex flex-col gap-8">
            <!-- Info Banner -->
            <div class="overflow-hidden rounded-2xl border border-violet-100 bg-gradient-to-r from-violet-600 via-indigo-600 to-indigo-700 p-6 text-white shadow-md relative">
                <div class="relative z-10 flex flex-col justify-between gap-4 md:flex-row md:items-center">
                    <div class="space-y-2">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-violet-100 backdrop-blur-md">
                            <svg class="h-4 w-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Pays Détecté : {{ countryName }} ({{ countryCode }})
                        </span>
                        <h2 class="text-xl font-bold">Règles & Lois Locales en Vigueur</h2>
                        <p class="text-sm text-violet-100/90 max-w-2xl">
                            Consultez les codes civils, articles de lois et conventions locales applicables à la gestion de vos baux d'habitation ou commerciaux.
                        </p>
                    </div>
                    <div class="text-right text-xs text-violet-200 backdrop-blur-sm bg-white/5 p-3 rounded-xl border border-white/10 self-start md:self-auto">
                        <div>Source : <span class="font-bold text-white">{{ source }}</span></div>
                        <div class="mt-1">Dernière génération : <span class="font-bold text-white">{{ generatedAt }}</span></div>
                    </div>
                </div>
                <!-- Decorative background elements -->
                <div class="absolute -right-12 -top-12 h-44 w-44 rounded-full bg-violet-400/20 blur-3xl pointer-events-none"></div>
                <div class="absolute -left-12 -bottom-12 h-44 w-44 rounded-full bg-indigo-400/20 blur-3xl pointer-events-none"></div>
            </div>

            <!-- Regulations Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 md:p-10">
                <!-- Parsed HTML Content -->
                <div class="prose prose-slate max-w-none text-slate-700" v-html="parsedContent"></div>

                <!-- Legal Disclaimer -->
                <div class="mt-10 border-t border-slate-100 pt-8">
                    <div class="bg-amber-50/60 border border-amber-200/50 rounded-xl p-4 flex gap-3.5 items-start">
                        <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center text-amber-700 shrink-0">
                            <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="space-y-1">
                            <h4 class="font-bold text-slate-800 text-sm">Avis de non-responsabilité juridique</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Les informations juridiques fournies ci-dessus sont rédigées à titre informatif général pour vous aider dans vos démarches de gestion locative. Elles sont basées sur des sources d'intelligence artificielle ou de documentation de référence et ne constituent en aucun cas des avis juridiques professionnels. Bien que nous nous efforcions de maintenir ces données à jour, les lois évoluent rapidement. Nous vous recommandons de consulter un avocat qualifié ou un conseiller immobilier agréé pour toute décision de nature légale.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';

// Reactive state variables
const loading = ref(true);
const refreshing = ref(false);
const rawRegulations = ref('');
const countryName = ref('');
const countryCode = ref('');
const source = ref('');
const generatedAt = ref('');

// Markdown line-by-line parser to format Tailwind HTML cleanly
const renderMarkdown = (md) => {
    if (!md) return '';
    
    const lines = md.replace(/\r\n/g, '\n').split('\n');
    let html = [];
    let inList = false;

    // Helper function to replace markdown bold text **bold**
    const formatInline = (text) => {
        return text.replace(/\*\*(.*?)\*\*/g, '<strong class="font-semibold text-slate-900">$1</strong>');
    };

    for (let line of lines) {
        let trimmed = line.trim();

        // Horizontal Rule
        if (trimmed === '---' || trimmed === '***') {
            if (inList) {
                html.push('</ul>');
                inList = false;
            }
            html.push('<hr class="my-6 border-slate-200" />');
            continue;
        }

        // Headings (H1)
        const h1Match = line.match(/^#\s+(.+)$/);
        if (h1Match) {
            if (inList) {
                html.push('</ul>');
                inList = false;
            }
            html.push(`<h1 class="text-2xl font-extrabold text-slate-900 mt-8 mb-5 pb-3 border-b border-slate-200/60">${formatInline(h1Match[1])}</h1>`);
            continue;
        }

        // Headings (H2)
        const h2Match = line.match(/^##\s+(.+)$/);
        if (h2Match) {
            if (inList) {
                html.push('</ul>');
                inList = false;
            }
            html.push(`<h2 class="text-xl font-bold text-slate-800 mt-7 mb-4">${formatInline(h2Match[1])}</h2>`);
            continue;
        }

        // Headings (H3)
        const h3Match = line.match(/^###\s+(.+)$/);
        if (h3Match) {
            if (inList) {
                html.push('</ul>');
                inList = false;
            }
            html.push(`<h3 class="text-lg font-semibold text-slate-800 mt-5 mb-3">${formatInline(h3Match[1])}</h3>`);
            continue;
        }

        // Bullet Lists
        const listMatch = line.match(/^[-*]\s+(.+)$/);
        if (listMatch) {
            if (!inList) {
                html.push('<ul class="list-disc pl-5 my-3.5 space-y-2 text-slate-600">');
                inList = true;
            }
            html.push(`<li>${formatInline(listMatch[1])}</li>`);
            continue;
        }

        // Blank Line (closes active lists and adds separation)
        if (trimmed === '') {
            if (inList) {
                html.push('</ul>');
                inList = false;
            }
            continue;
        }

        // If list is active but this is standard text, close the list
        if (inList) {
            html.push('</ul>');
            inList = false;
        }

        // Normal Paragraph text
        html.push(`<p class="my-3 text-slate-600 leading-relaxed">${formatInline(line)}</p>`);
    }

    if (inList) {
        html.push('</ul>');
    }

    return html.join('\n');
};

// Computed property to automatically parse the raw Markdown rules
const parsedContent = computed(() => {
    return renderMarkdown(rawRegulations.value);
});

// Fetch regulations from backend
const fetchRegulations = async (forceRefresh = false) => {
    if (forceRefresh) {
        refreshing.value = true;
    } else {
        loading.value = true;
    }

    try {
        const response = await axios.get('/api/regles-vigueur', {
            params: {
                force_refresh: forceRefresh
            }
        });
        
        if (response.data && response.data.success) {
            rawRegulations.value = response.data.regulations || '';
            countryName.value = response.data.country_name || '';
            countryCode.value = response.data.country_code || '';
            source.value = response.data.source || 'IA';
            generatedAt.value = response.data.generated_at || '';
        }
    } catch (error) {
        console.error("Erreur lors de la récupération des réglementations :", error);
        rawRegulations.value = "# Erreur de chargement\n\nImpossible de charger les réglementations juridiques pour votre pays. Veuillez vérifier votre connexion ou réactualiser.";
        source.value = 'Erreur';
        generatedAt.value = 'Non disponible';
    } finally {
        loading.value = false;
        refreshing.value = false;
    }
};

// Handle IA live regeneration action
const refreshRegulations = () => {
    fetchRegulations(true);
};

// Load on mount
onMounted(() => {
    fetchRegulations();
});
</script>
