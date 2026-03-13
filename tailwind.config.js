/** @type {import('tailwindcss').Config} */
import daisyui from 'daisyui';

export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './storage/framework/views/*.php',
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    ],
    theme: {
        extend: {},
    },
    plugins: [daisyui],
    daisyui: {
        themes: ['dark'],
        darkTheme: 'dark',
    },
};
