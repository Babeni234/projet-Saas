<script setup>
defineProps({
    label: { type: String, required: true },
    value: { type: [String, Number], required: true },
    change: { type: String, default: '' },
    trend: { type: String, default: 'neutral' },
    accent: { type: String, default: 'emerald' },
});

const accents = {
    emerald: 'from-emerald-500 to-teal-500 shadow-emerald-500/25',
    blue: 'from-blue-500 to-indigo-500 shadow-blue-500/25',
    amber: 'from-amber-500 to-orange-500 shadow-amber-500/25',
    violet: 'from-violet-500 to-purple-500 shadow-violet-500/25',
};
</script>

<template>
    <div class="imo-stat-card">
        <div class="relative flex items-start justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">{{ label }}</p>
                <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ value }}</p>
                <p
                    v-if="change"
                    class="mt-2 inline-flex items-center gap-1 text-xs font-semibold"
                    :class="{
                        'imo-stat-trend-up': trend === 'up',
                        'imo-stat-trend-down': trend === 'down',
                        'imo-stat-trend-neutral': trend === 'neutral',
                    }"
                >
                    <span v-if="trend === 'up'">↑</span>
                    <span v-if="trend === 'down'">↓</span>
                    {{ change }}
                </p>
            </div>
            <div
                class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br text-white shadow-lg"
                :class="accents[accent] ?? accents.emerald"
            >
                <slot name="icon">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </slot>
            </div>
        </div>
    </div>
</template>
