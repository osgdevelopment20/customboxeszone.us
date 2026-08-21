import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/assets/css/main.css',
                'resources/assets/js/main.js',
            ],
            refresh: true,
            // Make sure assets directory is properly handled
            publicDirectory: 'public',
        }),
    ],
    build: {
        // Enable minification for production builds
        minify: 'terser',
        // Configure Terser options for better minification
        terserOptions: {
            compress: {
                drop_console: true,
                drop_debugger: true,
            },
        },
        // Split chunks for better caching
        rollupOptions: {
            output: {
                // Removed manual chunks for vendor to include jQuery directly in main files
                // Ensure asset filenames include hashes for cache busting
                entryFileNames: 'js/[name]-[hash].js',
                chunkFileNames: 'js/[name]-[hash].js',
                assetFileNames: (assetInfo) => {
                    let extType = assetInfo.name.split('.').at(1);
                    if (/png|jpe?g|svg|gif|tiff|bmp|ico/i.test(extType)) {
                        extType = 'img';
                    } else if (/woff|woff2|eot|ttf|otf/i.test(extType)) {
                        extType = 'fonts';
                    }
                    return `${extType}/[name]-[hash][extname]`;
                },
            },
        },
        // Optimize CSS output
        cssCodeSplit: true,
        // Disable sourcemaps to prevent errors with vendor files
        sourcemap: false,
        // Ensure assets from resources/assets are properly copied
        assetsDir: 'resources/assets',
        // Ensure assets are properly resolved
        assetsInlineLimit: 4096, // 4kb
    },
    // Optimize for production
    optimizeDeps: {
        include: [
            // Include all libraries loaded from node_modules
            'jquery',
            'jquery-migrate',
            'bootstrap',
            // 'modernizr', // Removed to prevent conflicts with RequireJS
            'ev-emitter',
            'desandro-matches-selector',
            'get-size',
            'fizzy-ui-utils',
            'outlayer',
            'masonry-layout',
            'isotope-layout',
            'isotope-layout/js/item',
            'isotope-layout/js/layout-mode',
            'isotope-layout/js/layout-modes/masonry',
            'isotope-layout/js/layout-modes/fit-rows',
            'isotope-layout/js/layout-modes/vertical'
        ],
        exclude: ['modernizr'], // Explicitly exclude modernizr from optimization
    },
    // Define globals to provide CommonJS compatibility
    define: {
        // jQuery and other libraries are now properly exposed in plugins.js
        'module': '({ exports: {} })',
        'module.exports': '{}',
    },
    // Resolve assets from resources/assets
    resolve: {
        alias: {
            '@assets': '/resources/assets',
            '@fonts': '/resources/assets/fonts',
            '@imgs': '/resources/assets/imgs',
            '@icons': '/resources/assets/icons',
            // Add aliases for isotope modules to fix import paths
            'isotope/js/item': 'isotope-layout/js/item',
            'isotope/js/layout-mode': 'isotope-layout/js/layout-mode',
            'isotope/js/layout-modes/masonry': 'isotope-layout/js/layout-modes/masonry',
            'isotope/js/layout-modes/fit-rows': 'isotope-layout/js/layout-modes/fit-rows',
            'isotope/js/layout-modes/vertical': 'isotope-layout/js/layout-modes/vertical',
            // Add aliases for internal isotope dependencies
            './item': 'isotope-layout/js/item',
            '../layout-mode': 'isotope-layout/js/layout-mode',
        },
    },
    // Configure server options
    server: {
        hmr: {
            host: 'localhost',
        },
    },
});
