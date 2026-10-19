import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './app/Livewire/**/*.php',
        './app/View/Components/**/*.php',
    ],

    theme: {
        extend: {
            /*
             * Plateforme de marque IRICE, révision C (planche XI) : Jost pour les
             * plateformes, titres compris, et pour le texte courant ; Source
             * Serif 4 italique pour les citations et les exergues.
             * Figtree reste en repli : l'espace connecté charge encore cette
             * police (layouts/app.blade.php) tant qu'il n'a pas basculé sur Jost.
             */
            fontFamily: {
                sans: ['Jost', 'Figtree', ...defaultTheme.fontFamily.sans],
                serif: ['"Source Serif 4"', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                // Charte révision C : Encre (texte), Marine (fonds sombres, en-têtes).
                'encre': '#202335',
                'marine': '#0C3342',
                // Dégradé biodiversité : #2871B7 → #188E9D → #0FA36A.
                'biodiv': {
                    'bleu': '#2871B7',
                    'sarcelle': '#188E9D',
                    'vert': '#0FA36A',
                },
                // Palette BPS recalée sur le bleu du dégradé biodiversité
                // (600 = #2871B7, 950 = Marine). Mêmes noms de nuances que
                // l'ancienne palette (bleu Tailwind), pour ne toucher aucune vue.
                'bps': {
                    '50':  '#f2f6fb',
                    '100': '#e1ebf5',
                    '200': '#c3d7eb',
                    '300': '#98bbdc',
                    '400': '#689ccd',
                    '500': '#4483c0',
                    '600': '#2871b7',
                    '700': '#21629a',
                    '800': '#1a527c',
                    '900': '#144463',
                    '950': '#0c3342',
                },
            },
        },
    },

    plugins: [forms],
};
