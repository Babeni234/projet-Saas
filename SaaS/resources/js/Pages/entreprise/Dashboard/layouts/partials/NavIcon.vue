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
        <path v-else-if="name === 'credit-card'" d="M2 5h20a2 2 0 012 2v10a2 2 0 01-2 2H2a2 2 0 01-2-2V7a2 2 0 012-2z M2 10h20" />
        <path v-else-if="name === 'book'" d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M4 19.5A2.5 2.5 0 0 0 6.5 22H20M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5z" />
        <path v-else-if="name === 'message-square'" d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
        <path v-else-if="name === 'message-circle'" d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
        <path v-else-if="name === 'users'" d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2 M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8 M23 21v-2a4 4 0 0 0-3-3.87 M16 3.13a4 4 0 0 1 0 7.75" />
        <path v-else d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
</template>

<script setup>
defineProps({
    name: { type: String, required: true },
});
</script>
