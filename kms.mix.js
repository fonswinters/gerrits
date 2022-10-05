let mix = require('laravel-mix');
let path = require('path');

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
        grid: true
    },
    notifications: {
        onSuccess: false
    }
});

mix.copy('vendor/komma/kms/resources/img', 'www/img/kms');

mix.sass('resources/sass/KMS/kms.scss', 'www/css/kms/kms.css');
mix.js('resources/js/kms.js', 'www/js/kms/kms.js').extract([
    'vue',
    'vue-currency-input',
    'vuex',
    'axios',
    'jquery',
    'jquery-ui',
    'sortablejs',
    'jquery-ui/ui/widgets/datepicker',
    'jquery-ui/ui/widgets/sortable',
    'jquery-ui/ui/widgets/selectmenu',
]).sourceMaps(false, 'inline-source-map')
    .vue({version: 2})
    .version();


mix.copy('node_modules/tinymce/icons', 'www/js/kms/icons');
mix.copy('node_modules/tinymce/skins', 'www/js/kms/skins/');
mix.sass('vendor/komma/kms/resources/sass/tiny_content.scss', 'www/css/tiny_content.css');
mix.copy('vendor/komma/kms/resources/js/tinymce_languages/', 'www/js/tinymce_languages/');
mix.copy('vendor/komma/kms/resources/js/kms/tinymce/skins', 'www/js/kms/skins/');

mix.webpackConfig(require('./webpack.config'));
mix.mergeManifest();
