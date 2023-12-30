import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel([
            'resources/admin/css/app.css',
            'resources/admin/js/app.js',
            /**
             * Editor Resources
             */
            'Editor/resources/js/index.js',
            'Editor/resources/css/gjs.css',
            /**
             * Frontend Resources
             */
            'resources/frontend/css/auth.css',
            'resources/frontend/css/cart.css',
            'resources/frontend/css/custom.css',
            'resources/frontend/css/dev1.css',
            'resources/frontend/css/place-order.css',
            'resources/frontend/css/shipping-address.css',
            'resources/frontend/css/style.css',
            'resources/frontend/css/vendor-payment.css',

            'resources/frontend/js/main.js',
    

        ]),
    ],
    refresh: true,
    build: {
        chunkSizeWarningLimit: 2000,
        outDir: 'public/assets'
    },
});
