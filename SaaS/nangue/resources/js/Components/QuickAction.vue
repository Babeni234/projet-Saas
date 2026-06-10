<script setup>
defineProps({
    title: { type: String, required: true },
    description: { type: String, default: '' },
    href: { type: String, default: null },
    variant: { type: String, default: 'default' },
    badge: { type: String, default: '' },
});

const variants = {
    default: '',
    primary: 'imo-quick-action-primary',
    warning: 'imo-quick-action-warning',
};
</script>

<template>
    <a
        v-if="href"
        :href="href"
        class="imo-quick-action group block"
        :class="variants[variant] ?? variants.default"
    >
        <div class="mb-3 flex items-center justify-between">
            <div class="imo-quick-icon">
                <slot name="icon" />
            </div>
            <span
                v-if="badge"
                class="rounded-full bg-gradient-to-r from-emerald-600 to-teal-600 px-2.5 py-0.5 text-xs font-bold text-white shadow-sm"
            >
                {{ badge }}
            </span>
        </div>
        <h3 class="font-bold text-slate-900">{{ title }}</h3>
        <p v-if="description" class="mt-1 text-sm leading-relaxed text-slate-500">{{ description }}</p>
    </a>
    <div
        v-else
        class="imo-quick-action group cursor-pointer"
        :class="variants[variant] ?? variants.default"
    >
        <div class="mb-3 flex items-center justify-between">
            <div class="imo-quick-icon">
                <slot name="icon" />
            </div>
            <span
                v-if="badge"
                class="rounded-full bg-gradient-to-r from-emerald-600 to-teal-600 px-2.5 py-0.5 text-xs font-bold text-white shadow-sm"
            >
                {{ badge }}
            </span>
        </div>
        <h3 class="font-bold text-slate-900">{{ title }}</h3>
        <p v-if="description" class="mt-1 text-sm leading-relaxed text-slate-500">{{ description }}</p>
    </div>
</template>
