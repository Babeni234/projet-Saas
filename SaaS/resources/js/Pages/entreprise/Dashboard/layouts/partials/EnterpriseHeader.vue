<template>
    <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/90 backdrop-blur-lg shadow-sm">
        <div class="flex h-16 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
            <div class="flex min-w-0 items-center gap-3">
                <button
                    type="button"
                    class="inline-flex rounded-xl border border-slate-200 bg-white p-2.5 text-slate-600 shadow-sm hover:bg-slate-50 lg:hidden"
                    @click="toggleMobileSidebar"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" />
                    </svg>
                </button>

                <div class="min-w-0">
                    <nav v-if="breadcrumbs.length" class="mb-0.5 flex flex-wrap items-center gap-1 text-xs text-slate-500">
                        <template v-for="(crumb, i) in breadcrumbs" :key="i">
                            <span v-if="i > 0" class="text-slate-300">/</span>
                            <RouterLink
                                :to="crumb.to"
                                class="hover:text-indigo-600"
                                :class="{ 'font-medium text-slate-800': i === breadcrumbs.length - 1 }"
                            >
                                {{ crumb.label }}
                            </RouterLink>
                        </template>
                    </nav>
                    <h1 class="truncate text-lg font-semibold text-slate-900 sm:text-xl">{{ pageTitle }}</h1>
                </div>
            </div>

            <div class="flex shrink-0 items-center gap-2 sm:gap-3">
                <div class="hidden items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 sm:flex">
                    <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" />
                        <path d="M16 2v4M8 2v4M3 10h18" />
                    </svg>
                    <span class="text-xs font-medium text-slate-600">{{ dateRange }}</span>
                </div>

                <button
                    type="button"
                    class="inline-flex items-center gap-2 rounded-xl border border-indigo-200 bg-indigo-50 px-3 py-2 text-sm font-medium text-indigo-700 shadow-sm transition hover:border-indigo-300 hover:bg-indigo-100"
                    :disabled="refreshing"
                    @click="$emit('refresh')"
                >
                    <svg
                        class="h-4 w-4"
                        :class="{ 'animate-spin': refreshing }"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M4 4v5h5M20 20v-5h-5M20 8A8 8 0 004 16" stroke-linecap="round" />
                    </svg>
                    <span class="hidden sm:inline">Actualiser</span>
                </button>
            </div>
        </div>
    </header>
</template>

<script setup>
import { computed } from 'vue';
import { RouterLink, useRoute } from 'vue-router';
import { useEnterpriseLayout } from '../../composables/useEnterpriseLayout';
import { usePageTitle } from '../../composables/useEnterpriseLayout';

defineProps({
    refreshing: { type: Boolean, default: false },
});

defineEmits(['refresh']);

const route = useRoute();
const pageTitle = usePageTitle();
const { toggleMobileSidebar } = useEnterpriseLayout();

const dateRange = computed(() => {
    const now = new Date();
    const first = new Date(now.getFullYear(), now.getMonth(), 1);
    const last = new Date(now.getFullYear(), now.getMonth() + 1, 0);
    const opts = { month: 'short', year: 'numeric' };
    return `${first.toLocaleDateString('fr-FR', opts)} – ${last.toLocaleDateString('fr-FR', opts)}`;
});

const breadcrumbs = computed(() => route.meta?.breadcrumbs || []);
</script>
