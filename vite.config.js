import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import { visualizer } from "rollup-plugin-visualizer";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/sass/app.scss", "resources/sass/dashboard.scss", "resources/js/app.js", "resources/js/aquarium.js",'resources/js/argon-dashboard.js', 'resources/js/station.js'],
            refresh: true,
        }),
    ],
    build: {
        chunkSizeWarningLimit: 1000, // Increase the warning limit to 1MB
        rollupOptions: {
            output: {
                manualChunks(id) {
                    // Creates a vendor chunk for all node_modules dependencies
                    if (id.includes('node_modules')) {
                        // Specifically split phaser into its own chunk
                        if (id.includes('phaser')) {
                            return 'vendor-phaser';
                        }
                        return 'vendor'; // all other node_modules
                    }
                }
            },
            plugins: [
                visualizer({
                    open: false, // Set to false to prevent auto-opening
                    filename: 'dist/stats.html', // Output file
                })
            ]
        }
    }
});
