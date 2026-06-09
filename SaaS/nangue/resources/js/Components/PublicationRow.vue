<script setup>
defineProps({
    publication: { type: Object, required: true },
});

const statusClass = {
    active: 'imo-badge-success',
    pending: 'imo-badge-warning',
    expired: 'imo-badge-muted',
};
</script>

<template>
    <tr>
        <td>
            <p class="font-semibold text-slate-900">{{ publication.title }}</p>
            <p class="text-xs text-slate-400">{{ publication.reference }}</p>
        </td>
        <td class="text-sm font-medium text-slate-600">{{ publication.type }}</td>
        <td>
            <span :class="statusClass[publication.status] ?? 'imo-badge-muted'">
                {{ publication.statusLabel }}
            </span>
        </td>
        <td class="text-sm font-semibold text-slate-700">{{ publication.views }}</td>
        <td class="text-sm text-slate-500">{{ publication.date }}</td>
        <td class="text-right">
            <button type="button" class="text-sm font-bold text-emerald-600 transition hover:text-emerald-800">
                Gérer →
            </button>
        </td>
    </tr>
</template>
