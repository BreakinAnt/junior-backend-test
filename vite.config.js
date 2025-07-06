import { defineConfig } from 'vite';
import vue from "@vitejs/plugin-vue"
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite'
import path from "node:path";

export default defineConfig({
    plugins: [
        tailwindcss(),
        vue(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            'ziggy-js': path.resolve('vendor/tightenco/ziggy'),
        },
    },
});
