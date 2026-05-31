/** @type {import('tailwindcss').Config} */
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './app/Modules/**/**/*.blade.php',
    ],
    theme: {
        extend: {},
    },
    plugins: [forms, typography],
};
