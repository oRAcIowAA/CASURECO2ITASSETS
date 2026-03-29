import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    50: '#eff6ff',
                    500: '#3b82f6',
                    600: '#2563eb',
                    900: '#1e3a8a', // Casureco Dark Blue
                },
                accent: '#fbbf24', // Gold/Yellow
            },
        },
    },

    plugins: [forms],
};
