import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'node_modules/suneditor/dist/css/suneditor.min.css',
                'node_modules/pikaday/css/pikaday.css',
                'node_modules/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css',
                'node_modules/filepond/dist/filepond.min.css',
                'node_modules/mapbox-gl/dist/mapbox-gl.css',
                'resources/js/mapbox.js',
                'resources/js/mapbox-draw.js',
                'resources/js/filepond.js',
                'resources/js/moment.js',
                'resources/js/suneditor.js',
                'resources/js/pickaday.js',
                'resources/js/app.js',
                'resources/scss/app.scss',
            ],
            refresh: true,
        }),
    ],
});
