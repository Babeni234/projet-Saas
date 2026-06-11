<template>
    <div class="enterprise-shell min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 font-sans text-slate-900 antialiased">
        <AgencySidebar />

        <div
            class="flex min-h-screen flex-col transition-[margin] duration-300 ease-out"
            :class="sidebarCollapsed ? 'lg:ml-[72px]' : 'lg:ml-72'"
        >
            <AgencyHeader :refreshing="refreshing" @refresh="handleRefresh" />

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

        <EnterpriseAssistant />
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { RouterView } from 'vue-router';
import AgencySidebar from './partials/AgencySidebar.vue';
import AgencyHeader from './partials/AgencyHeader.vue';
import EnterpriseAssistant from '../../../entreprise/Dashboard/layouts/partials/EnterpriseAssistant.vue';
import { provideEnterpriseLayout } from '../../../entreprise/Dashboard/composables/useEnterpriseLayout';
import { provideEnterpriseProps } from '../../../entreprise/Dashboard/composables/useEnterpriseProps';

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
