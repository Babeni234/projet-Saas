import { ref, computed, provide, inject } from 'vue';
import { useRoute } from 'vue-router';
import { routeTitles } from '../config/navigation';

const LAYOUT_KEY = Symbol('enterprise-layout');

export function provideEnterpriseLayout() {
    const sidebarCollapsed = ref(false);
    const mobileSidebarOpen = ref(false);

    const toggleSidebar = () => {
        sidebarCollapsed.value = !sidebarCollapsed.value;
    };

    const toggleMobileSidebar = () => {
        mobileSidebarOpen.value = !mobileSidebarOpen.value;
    };

    const closeMobileSidebar = () => {
        mobileSidebarOpen.value = false;
    };

    const state = {
        sidebarCollapsed,
        mobileSidebarOpen,
        toggleSidebar,
        toggleMobileSidebar,
        closeMobileSidebar,
    };

    provide(LAYOUT_KEY, state);
    return state;
}

export function useEnterpriseLayout() {
    const state = inject(LAYOUT_KEY);
    if (!state) {
        throw new Error('useEnterpriseLayout must be used within EnterpriseLayout');
    }
    return state;
}

export function usePageTitle() {
    const route = useRoute();
    return computed(() => {
        if (route.meta?.title) return route.meta.title;
        return routeTitles[route.name] || 'Portail entreprise';
    });
}
