<script setup>
import LanguageToggle from '@/Components/LanguageToggle.vue';
import { authTranslations } from '@/i18n/auth';
import { useLocale } from '@/composables/useLocale';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({
    title: {
        type: String,
        default: '',
    },
    subtitle: {
        type: String,
        default: '',
    },
    wide: {
        type: Boolean,
        default: false,
    },
    scrollable: {
        type: Boolean,
        default: false,
    },
});

const { locale, setLocale } = useLocale();
const t = computed(() => authTranslations[locale.value].layout);
</script>

<template>
    <Head>
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link
            href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700|playfair-display:500,600,700&display=swap"
            rel="stylesheet"
        />
    </Head>

    <div
        class="min-h-screen bg-[#FAFAF8] text-slate-900 antialiased"
        style="font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif"
    >
        <div class="flex min-h-screen">
            <!-- Brand panel -->
            <aside
                class="relative hidden w-[45%] overflow-hidden lg:flex lg:flex-col lg:justify-between"
            >
                <div class="absolute inset-0 bg-gradient-to-br from-amber-50 via-[#FAFAF8] to-sky-50" />
                <div class="pointer-events-none absolute -right-24 top-0 h-96 w-96 rounded-full bg-amber-200/40 blur-3xl" />
                <div class="pointer-events-none absolute -left-16 bottom-24 h-80 w-80 rounded-full bg-sky-200/30 blur-3xl" />
                <div
                    class="pointer-events-none absolute inset-0 opacity-30"
                    style="background-image: radial-gradient(circle at 1px 1px, #e2e8f0 1px, transparent 0); background-size: 32px 32px"
                />

                <div class="relative flex flex-col justify-between p-12 xl:p-16">
                    <Link href="/" class="group inline-flex items-center gap-3">
                        <div
                            class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-amber-500 to-amber-700 shadow-lg shadow-amber-500/25 transition-transform duration-300 group-hover:scale-105"
                        >
                            <span class="text-sm font-bold text-white">PA</span>
                        </div>
                        <span class="text-xl font-semibold tracking-tight">
                            Property<span class="text-amber-600">AI</span>
                        </span>
                    </Link>

                    <div class="max-w-md space-y-8">
                        <div class="space-y-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-amber-600">
                                {{ t.eyebrow }}
                            </p>
                            <h2
                                class="text-3xl font-semibold leading-tight tracking-tight text-slate-900 xl:text-4xl"
                                style="font-family: 'Playfair Display', Georgia, serif"
                            >
                                {{ t.headline }}
                            </h2>
                            <p class="text-base leading-relaxed text-slate-600">
                                {{ t.description }}
                            </p>
                        </div>

                        <ul class="space-y-4">
                            <li
                                v-for="item in t.highlights"
                                :key="item"
                                class="flex items-start gap-3 text-sm text-slate-600"
                            >
                                <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-amber-100">
                                    <svg class="h-3 w-3 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                                {{ item }}
                            </li>
                        </ul>
                    </div>

                    <p class="text-xs text-slate-400">
                        © {{ new Date().getFullYear() }} PropertyAI. {{ t.rights }}
                    </p>
                </div>
            </aside>

            <!-- Form panel -->
            <main class="relative flex flex-1 flex-col">
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-b from-amber-50/30 via-transparent to-transparent lg:hidden" />

                <header class="relative flex items-center justify-between gap-4 px-6 py-6 lg:px-12 lg:py-8">
                    <Link href="/" class="group inline-flex items-center gap-2.5 lg:hidden">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-amber-500 to-amber-700 shadow-md">
                            <span class="text-xs font-bold text-white">PA</span>
                        </div>
                        <span class="font-semibold">Property<span class="text-amber-600">AI</span></span>
                    </Link>

                    <div class="flex items-center gap-3 lg:ml-auto">
                        <LanguageToggle :locale="locale" @set-locale="setLocale" />

                        <Link
                            href="/"
                            class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 transition-colors hover:text-amber-700"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            <span class="hidden sm:inline">{{ t.backHome }}</span>
                        </Link>
                    </div>
                </header>

                <div
                    class="relative flex flex-1 px-6 pb-8 lg:px-12 lg:pb-10"
                    :class="scrollable ? 'items-start justify-center pt-2 lg:pt-4' : 'items-center justify-center pb-12'"
                >
                    <div :class="scrollable ? 'w-full max-w-2xl' : wide ? 'w-full max-w-xl' : 'w-full max-w-md'">
                        <div
                            v-if="title"
                            class="mb-6 space-y-2 text-center lg:text-left"
                            :class="scrollable ? 'lg:mb-5' : 'mb-8'"
                        >
                            <h1
                                class="text-3xl font-semibold tracking-tight text-slate-900"
                                style="font-family: 'Playfair Display', Georgia, serif"
                            >
                                {{ title }}
                            </h1>
                            <p v-if="subtitle" class="text-sm leading-relaxed text-slate-500">
                                {{ subtitle }}
                            </p>
                        </div>

                        <div
                            class="rounded-2xl border border-slate-200/80 bg-white/80 shadow-xl shadow-slate-200/40 backdrop-blur-xl"
                            :class="scrollable ? 'max-h-[calc(100vh-10rem)] overflow-y-auto p-6 sm:p-8' : 'p-8 sm:p-10'"
                        >
                            <slot />
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>
