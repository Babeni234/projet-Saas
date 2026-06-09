<script setup>
defineProps({
    tenant: { type: Object, required: true },
});
</script>

<template>
    <div class="imo-tenant-row">
        <div class="flex items-center gap-3">
            <div class="imo-avatar-tenant">
                {{ tenant.initials }}
            </div>
            <div>
                <p class="font-semibold text-slate-900">{{ tenant.name }}</p>
                <p class="text-xs text-slate-500">{{ tenant.property }}</p>
            </div>
        </div>
        <div class="text-right">
            <p class="text-sm font-bold text-slate-900">{{ tenant.rent }}</p>
            <p
                class="text-xs font-semibold"
                :class="tenant.paymentStatus === 'à jour' ? 'text-emerald-600' : 'text-amber-600'"
            >
                {{ tenant.paymentStatus }}
            </p>
        </div>
    </div>
</template>
