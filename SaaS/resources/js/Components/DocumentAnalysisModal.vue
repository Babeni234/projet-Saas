<script setup>
import { computed } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    result: {
        type: Object,
        default: null,
    },
    locale: {
        type: String,
        default: 'fr',
    },
    labels: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['close']);

const statusConfig = computed(() => {
    const status = props.result?.overall_status ?? 'review_required';

    return {
        compliant: {
            badge: props.labels.analysisCompliant,
            class: 'bg-emerald-100 text-emerald-800 border-emerald-200',
            icon: 'text-emerald-600',
        },
        review_required: {
            badge: props.labels.analysisReview,
            class: 'bg-amber-100 text-amber-800 border-amber-200',
            icon: 'text-amber-600',
        },
        rejected: {
            badge: props.labels.analysisRejected,
            class: 'bg-red-100 text-red-800 border-red-200',
            icon: 'text-red-600',
        },
    }[status] ?? {
        badge: props.labels.analysisReview,
        class: 'bg-amber-100 text-amber-800 border-amber-200',
        icon: 'text-amber-600',
    };
});

const docStatusLabel = computed(() => ({
    verified: props.locale === 'fr' ? 'Validé' : 'Verified',
    needs_review: props.locale === 'fr' ? 'À vérifier' : 'Needs review',
    rejected: props.locale === 'fr' ? 'Rejeté' : 'Rejected',
}));
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="show && result"
                class="fixed inset-0 z-[100] flex items-end justify-center p-4 sm:items-center"
            >
                <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="emit('close')" />

                <Transition
                    enter-active-class="transition duration-300 ease-out"
                    enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                    appear
                >
                    <div
                        v-if="show && result"
                        class="relative z-10 w-full max-w-lg overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl"
                    >
                        <div class="border-b border-slate-100 bg-gradient-to-r from-amber-50 to-white px-6 py-5">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-widest text-amber-600">
                                        PropertyAI · {{ labels.modalPoweredBy }}
                                    </p>
                                    <h3
                                        class="mt-1 text-xl font-semibold text-slate-900"
                                        style="font-family: 'Playfair Display', Georgia, serif"
                                    >
                                        {{ labels.modalTitle }}
                                    </h3>
                                </div>
                                <button
                                    type="button"
                                    class="rounded-lg p-1.5 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                                    @click="emit('close')"
                                >
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="max-h-[70vh] space-y-5 overflow-y-auto px-6 py-5">
                            <div class="flex flex-wrap items-center gap-2">
                                <span
                                    class="rounded-full border px-3 py-1 text-xs font-semibold"
                                    :class="statusConfig.class"
                                >
                                    {{ statusConfig.badge }}
                                </span>
                                <span class="text-xs text-slate-500">
                                    {{ labels.analysisConfidence }}: {{ result.confidence }}%
                                </span>
                                <span class="text-xs text-slate-500">
                                    {{ labels.authenticityScore }}: {{ result.authenticity_score }}%
                                </span>
                            </div>

                            <p class="rounded-xl bg-slate-50 px-4 py-3 text-sm leading-relaxed text-slate-700">
                                {{ result.summary }}
                            </p>

                            <div class="grid gap-3 sm:grid-cols-2">
                                <div class="rounded-xl border border-violet-100 bg-violet-50/50 p-3">
                                    <p class="text-[10px] font-semibold uppercase tracking-wider text-violet-700">Gemini</p>
                                    <p class="mt-1 text-xs text-slate-600">{{ result.ai_providers?.gemini?.model }}</p>
                                    <p class="mt-1 text-[11px] text-violet-800">{{ labels.geminiRole }}</p>
                                </div>
                                <div class="rounded-xl border border-sky-100 bg-sky-50/50 p-3">
                                    <p class="text-[10px] font-semibold uppercase tracking-wider text-sky-700">OpenAI</p>
                                    <p class="mt-1 text-xs text-slate-600">
                                        {{ result.ai_providers?.openai?.used ? result.ai_providers.openai.model : labels.openaiSkipped }}
                                    </p>
                                    <p class="mt-1 text-[11px] text-sky-800">
                                        {{
                                            result.ai_providers?.openai?.used
                                                ? (result.ai_providers.openai.summary || labels.openaiRole)
                                                : labels.openaiSkipped
                                        }}
                                    </p>
                                </div>
                            </div>

                            <div>
                                <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    {{ labels.documentsBreakdown }}
                                </p>
                                <ul class="space-y-2">
                                    <li
                                        v-for="doc in result.documents"
                                        :key="doc.type + doc.filename"
                                        class="rounded-xl border border-slate-100 p-3"
                                    >
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="min-w-0">
                                                <p class="truncate text-sm font-medium text-slate-900">{{ doc.filename }}</p>
                                                <p class="mt-0.5 text-xs text-slate-500">{{ doc.summary }}</p>
                                            </div>
                                            <div class="shrink-0 text-right">
                                                <p
                                                    class="text-xs font-semibold"
                                                    :class="{
                                                        'text-emerald-600': doc.status === 'verified',
                                                        'text-amber-600': doc.status === 'needs_review',
                                                        'text-red-600': doc.status === 'rejected',
                                                    }"
                                                >
                                                    {{ docStatusLabel[doc.status] ?? doc.status }}
                                                </p>
                                                <p class="text-[10px] text-slate-400">{{ doc.authenticity_score }}%</p>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div v-if="result.issues?.length" class="rounded-xl border border-red-100 bg-red-50/60 p-3">
                                <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-red-700">
                                    {{ labels.issuesFound }}
                                </p>
                                <ul class="space-y-1">
                                    <li v-for="(issue, i) in result.issues" :key="i" class="text-xs text-red-700">
                                        • {{ issue }}
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="border-t border-slate-100 bg-slate-50/80 px-6 py-4">
                            <button
                                type="button"
                                class="w-full rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 px-4 py-3 text-sm font-semibold text-white shadow-md transition hover:from-amber-600 hover:to-amber-700"
                                @click="emit('close')"
                            >
                                {{ labels.modalClose }}
                            </button>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>
