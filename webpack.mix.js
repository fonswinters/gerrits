/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

let mix = require('laravel-mix');
require('laravel-mix-merge-manifest');
require('laravel-mix-bundle-analyzer');

// mix.bundleAnalyzer();

mix.browserSync({
    proxy: 'localhost:8000',
    port: 8001,
    files: [
        'www/css/**/*',
        'resources/views/**/*',
        'resources/lang/**/*',
        'resources/js/**/*',
    ]
});

mix.options({
    publicPath: 'www',
    processCssUrls: false,
    autoprefixer: {
        grid: false
    },
    notifications: {
        onSuccess: false
    }
});

mix.copy('resources/img', 'www/img');

mix.sass('resources/sass/style.scss', 'css/style.css')
    .sourceMaps(false, 'inline-source-map')
    .version();

mix.js('resources/js/app.js', 'js/app.js')
    .vue({version: 2})
    .extract(['body-scroll-lock', 'vue', 'vuex', 'axios', 'hammerjs', '@sentry'])
    .sourceMaps(false, 'inline-source-map')
    .version();

mix.webpackConfig(require('./webpack.config'));
mix.mergeManifest();