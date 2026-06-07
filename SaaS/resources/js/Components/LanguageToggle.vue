<script setup>
defineProps({
    locale: {
        type: String,
        required: true,
    },
});

defineEmits(['set-locale']);
</script>

<template>
    <div
        class="flex items-center rounded-full border border-slate-200 bg-slate-50/80 p-0.5 shadow-sm"
        role="group"
        :aria-label="locale === 'fr' ? 'Changer de langue' : 'Change language'"
    >
        <button
            type="button"
            class="rounded-full px-3 py-1.5 text-xs font-semibold transition-all duration-200"
            :class="locale === 'fr' ? 'bg-white text-amber-700 shadow-sm ring-1 ring-slate-200/80' : 'text-slate-500 hover:text-slate-700'"
            @click="$emit('set-locale', 'fr')"
        >
            FR
        </button>
        <button
            type="button"
            class="rounded-full px-3 py-1.5 text-xs font-semibold transition-all duration-200"
            :class="locale === 'en' ? 'bg-white text-amber-700 shadow-sm ring-1 ring-slate-200/80' : 'text-slate-500 hover:text-slate-700'"
            @click="$emit('set-locale', 'en')"
        >
            EN
        </button>
    </div>
</template>
