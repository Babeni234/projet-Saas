<template>
    <EnterpriseLayout />
</template>

<script setup>
import { onMounted, watch } from 'vue';
import { useRouter } from 'vue-router';
import { usePage } from '@inertiajs/vue3';
import EnterpriseLayout from './layouts/EnterpriseLayout.vue';

const page = usePage();
const router = useRouter();

const ensureDashboardRoute = async () => {
    await router.isReady();

    const hash = window.location.hash;
    const hasHashRoute = hash && hash !== '#' && hash !== '#/';

    if (!hasHashRoute) {
        await router.replace({ name: 'dashboard.master' });
        return;
    }

    if (!router.currentRoute.value.matched.length) {
        await router.replace({ name: 'dashboard.master' });
    }
};

const syncInitialRoute = async () => {
    const target = page.props.initialRoute;
    if (!target) return;

    const path = target.startsWith('/') ? target : `/dashboard/${target}`;

    if (router.currentRoute.value.path !== path) {
        await router.replace(path);
    }
};

onMounted(async () => {
    await ensureDashboardRoute();
    await syncInitialRoute();
});

watch(() => page.props.initialRoute, syncInitialRoute);
</script>
