<template>
    <svg
        class="h-5 w-5 shrink-0"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="1.75"
        stroke-linecap="round"
        stroke-linejoin="round"
        aria-hidden="true"
    >
        <path v-if="name === 'layout-dashboard'" d="M3 3h8v8H3zM13 3h8v5h-8zM13 10h8v11h-8zM3 13h8v8H3z" />
        <path v-else-if="name === 'building'" d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-4h6v4" />
        <path v-else-if="name === 'hotel'" d="M3 21V7a2 2 0 012-2h14a2 2 0 012 2v14M7 10h.01M12 10h.01M17 10h.01M7 15h.01M12 15h.01M17 15h.01" />
        <path v-else-if="name === 'calculator'" d="M7 3h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2zM8 7h8M8 11h8M8 15h4" />
        <path v-else-if="name === 'wrench'" d="M14.7 6.3a4 4 0 105.4 5.4L12 20l-4-4 6.7-9.7z" />
        <path v-else-if="name === 'chart'" d="M4 19V5M10 19V9M16 19v-6M22 19V3" />
        <path v-else-if="name === 'map'" d="M9 18l-6 3V6l6-3 6 3 6-3v15l-6 3-6-3zM9 3v15M15 6v15" />
        <path v-else-if="name === 'shield'" d="M12 3l8 4v6c0 5-3.5 9-8 10-4.5-1-8-5-8-10V7l8-4z" />
        <path v-else d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
</template>

<script setup>
defineProps({
    name: { type: String, required: true },
});
</script>
