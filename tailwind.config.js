import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                primario: {
                    DEFAULT: '#0A0D40',
                    900: '#0A0D40',
                },
                secundario: {
                    DEFAULT: '#2C4A73',
                    700: '#2C4A73',
                },
                acento: {
                    DEFAULT: '#4B8CA6',
                    500: '#4B8CA6',
                },
                exito: {
                    DEFAULT: '#A0F2AC',
                    400: '#A0F2AC',
                },
                'fondo-suave': {
                    DEFAULT: '#D0F2D3',
                    100: '#D0F2D3',
                },
            },
            fontFamily: {
                sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
        },
    },
    plugins: [forms],
};
