import { ref, watch, onMounted } from 'vue';

const locale = ref('fr');
let initialized = false;
let watchRegistered = false;

export function useLocale() {
    onMounted(() => {
        if (initialized) {
            return;
        }

        const saved = localStorage.getItem('propertyai-locale');
        if (saved === 'en' || saved === 'fr') {
            locale.value = saved;
        }

        document.documentElement.lang = locale.value;
        initialized = true;
    });

    if (!watchRegistered) {
        watch(locale, (val) => {
            localStorage.setItem('propertyai-locale', val);
            document.documentElement.lang = val;
        });
        watchRegistered = true;
    }

    function setLocale(lang) {
        if (lang === 'en' || lang === 'fr') {
            locale.value = lang;
        }
    }

    return { locale, setLocale };
}
