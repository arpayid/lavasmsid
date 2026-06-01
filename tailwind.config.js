/** @type {import('tailwindcss').Config} */
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './app/Modules/**/*.php',
        './app/Modules/**/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    50: '#EFF6FF',
                    100: '#DBEAFE',
                    200: '#BFDBFE',
                    300: '#93C5FD',
                    400: '#60A5FA',
                    500: '#3B82F6',
                    600: '#2563EB',
                    700: '#1D4ED8',
                    800: '#1E40AF',
                    900: '#1E3A8A',
                },
                success: {
                    50: '#F0FDF4',
                    500: '#22C55E',
                    600: '#16A34A',
                },
                warning: {
                    50: '#FFFBEB',
                    500: '#F59E0B',
                    600: '#D97706',
                },
                danger: {
                    50: '#FEF2F2',
                    500: '#EF4444',
                    600: '#DC2626',
                },
                info: {
                    50: '#ECFEFF',
                    500: '#06B6D4',
                    600: '#0891B2',
                },
            },
            fontFamily: {
                sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
            },
            borderRadius: {
                '2xl': '1rem',
                '3xl': '1.5rem',
            },
            boxShadow: {
                'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
                'medium': '0 5px 30px -5px rgba(0, 0, 0, 0.1)',
                'sidebar': '2px 0 15px -3px rgba(0, 0, 0, 0.1)',
                'dropdown': '0 10px 40px -10px rgba(0, 0, 0, 0.1)',
            },
        },
    },
    plugins: [forms, typography],
};
