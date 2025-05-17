import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class',

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', 'sans-serif'],
            },
            colors: {
                'deep-black': '#0A0A0A',
                'deeper-gray': {
                    700: '#1E1E1E',
                    800: '#181818',
                    900: '#121212'
                }
            }
        },
    },

    plugins: [forms],
};
