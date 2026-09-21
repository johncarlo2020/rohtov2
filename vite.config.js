import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/sass/app.scss", "resources/js/app.js", "resources/js/map.js", "resources/js/station.js","resources/sass/dashboard.scss",],
            refresh: true,
        }),
    ],
});
