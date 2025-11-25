import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/dashboard/base.css',
            'resources/css/dashboard/dashboard.css',
            'resources/css/dashboard/analyses.css',
            'resources/css/dashboard/reservations.css',
            'resources/css/dashboard/messages.css',
            'resources/js/dashboard/base.js',
            'resources/js/dashboard/dashboard.js',
            'resources/js/dashboard/analyses.js',
            'resources/js/dashboard/reservations.js',
            'resources/js/dashboard/messages.js',
        ],
            refresh: true,
        }),
    ],
});
