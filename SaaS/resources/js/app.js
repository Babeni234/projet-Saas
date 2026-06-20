import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import router from './router';
import i18n from './i18n';


import { router as inertiaRouter } from '@inertiajs/vue3';

let currentBrandingName = 'Property AI';

function updateBranding(props) {
    const branding = props?.branding;
    if (branding) {
        if (branding.name) {
            currentBrandingName = branding.name;
        }
        const favicon = document.querySelector("link[rel*='icon']");
        if (favicon && branding.logo) {
            favicon.href = branding.logo;
        }
        const appleIcons = document.querySelectorAll("link[rel='apple-touch-icon']");
        appleIcons.forEach(icon => {
            if (branding.logo) {
                icon.href = branding.logo;
            }
        });
    }
}

createInertiaApp({
    title: (title) => title ? `${title} - ${currentBrandingName}` : currentBrandingName,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        if (props.initialPage?.props) {
            updateBranding(props.initialPage.props);
        }

        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(router)
            .use(i18n)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

inertiaRouter.on('navigate', (event) => {
    if (event.detail?.page?.props) {
        updateBranding(event.detail.page.props);
    }
});

