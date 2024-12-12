import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        "./node_modules/flowbite/**/*.js",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    25: '#F5F5FF',   // Ultra light shade
                    50: '#EBEBFF',   // Lighter than 100
                    100: '#E3E4FF', // Lightest
                    200: '#B8B9FF', // Lighter
                    300: '#8E8FFF', // Light
                    400: '#6A6FFF', // Default
                    500: '#2529D8', // Main Color
                    600: '#1F1FCE', // Darker
                    700: '#1A1ABF', // Darker
                    800: '#1414A9', // Darker
                    900: '#0E0E92', // Darkest
                },
                secondary: {
                    25: '#FAFAFF',  // Ultra light shade
                    50: '#F4F4FF',  // Lighter than 100
                    100: '#E6E7FF', // Lightest
                    200: '#B8B9FF', // Lighter
                    300: '#8A8BFF', // Light
                    400: '#6C6DFF', // Default
                    500: '#6265E4', // Main Color
                    600: '#4F53D0', // Darker
                    700: '#3C42B3', // Darker
                    800: '#2A3197', // Darker
                    900: '#1A1D7B', // Darkest
                },
            },
            width: {
                '4xl': '46rem !important',  // Reduced to 896px
                '5xl': '50rem !important',  // Reduced to 960px
            },
        },
    },

    plugins: [
        forms,
        typography,
        require('flowbite/plugin')({
            datatables: true,
        }),
    ],
};
