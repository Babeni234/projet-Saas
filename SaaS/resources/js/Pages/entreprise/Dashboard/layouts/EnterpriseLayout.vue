<template>
    <div class="enterprise-shell min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 font-sans text-slate-900 antialiased">
        <EnterpriseSidebar />

        <div
            class="flex min-h-screen flex-col transition-[margin] duration-300 ease-out"
            :class="sidebarCollapsed ? 'lg:ml-[72px]' : 'lg:ml-72'"
        >
            <EnterpriseHeader :refreshing="refreshing" @refresh="handleRefresh" />

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                <RouterView v-slot="{ Component, route: activeRoute }">
                    <Transition name="page" mode="out-in">
                        <component
                            :is="Component"
                            :key="activeRoute.fullPath"
                            @refresh="handleRefresh"
                        />
                    </Transition>
                </RouterView>
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { RouterView } from 'vue-router';
import EnterpriseSidebar from './partials/EnterpriseSidebar.vue';
import EnterpriseHeader from './partials/EnterpriseHeader.vue';
import { provideEnterpriseLayout } from '../composables/useEnterpriseLayout';
import { provideEnterpriseProps } from '../composables/useEnterpriseProps';

const { sidebarCollapsed } = provideEnterpriseLayout();
provideEnterpriseProps();

const refreshing = ref(false);

const handleRefresh = async () => {
    refreshing.value = true;
    window.dispatchEvent(new CustomEvent('enterprise:refresh'));
    await new Promise((r) => setTimeout(r, 600));
    refreshing.value = false;
};
</script>
