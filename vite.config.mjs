import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import { quasar, transformAssetUrls } from "@quasar/vite-plugin";
import path from 'path';

export default defineConfig({
    server: {
        // host: '0.0.0.0',
        // hmr: {
        //     host: 'localhost'
        // }
        hmr: {
            host: "localhost",
            protocol: "ws",
        },
    },
    plugins: [
        laravel({
            input: "resources/js/app.js",
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        quasar({
             sassVariables: path.resolve(__dirname, './resources/src/quasar-variables.sass')
            
        }),
    ],
});
