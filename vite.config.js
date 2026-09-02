import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import { bunny } from "laravel-vite-plugin/fonts";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
            fonts: [
                bunny("Instrument Sans", {
                    weights: [400, 500, 600],
                }),
            ],
        }),

        tailwindcss(),
    ],

    server: {
        host: "0.0.0.0",
        port: 5173,
        strictPort: true,

        hmr: {
<<<<<<< HEAD
            host: "localhost",
            port: 5173,
=======
            host: "thousands-wrestling-brother-able.trycloudflare.com",
            protocol: "wss",
            clientPort: 443,
>>>>>>> 762c2e012b783d85834f6529febc7e391129c068
        },

        watch: {
            ignored: ["**/storage/framework/views/**"],
        },
    },
});