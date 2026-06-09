import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './nangue/resources/js/**/*.vue',
        './nangue/resources/css/**/*.css',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                immo: {
                    50: '#ecfdf5',
                    100: '#d1fae5',
                    500: '#10b981',
                    600: '#059669',
                    700: '#047857',
                    800: '#065f46',
                },
            },
            boxShadow: {
                imo: '0 4px 24px -4px rgb(5 150 105 / 0.12), 0 2px 8px -2px rgb(15 23 42 / 0.06)',
                'imo-lg': '0 12px 40px -8px rgb(5 150 105 / 0.15), 0 4px 16px -4px rgb(15 23 42 / 0.08)',
            },
        },
    },

    plugins: [forms],
};
