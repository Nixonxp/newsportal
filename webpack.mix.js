const mix = require('laravel-mix');

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

mix.js('resources/admin/js/app.js', 'public/js/admin')
    .sass('resources/admin/sass/app.scss', 'public/css/admin')
    .sourceMaps();

// optimize public template files
mix.scripts([
    'resources/public/js/jquery-2.2.4.min.js',
    'resources/public/js/popper.min.js',
    'resources/public/js/bootstrap.min.js',
    'resources/public/js/plugins.js',
    'resources/public/js/active.js',
    'resources/public/js/custom.js',
    //'resources/public/js/map-active.js',
], 'public/js/public/app.js');

mix.styles([
    'resources/public/css/animate.css',
    'resources/public/css/bootstrap.min.css',
    'resources/public/css/core-style.css',
    'resources/public/css/font-awesome.min.css',
    'resources/public/css/jquery-ui.min.css',
    'resources/public/css/magnific-popup.css',
    'resources/public/css/nouislider.css',
    'resources/public/css/owl.carousel.css',
    'resources/public/css/pe-icon-7-stroke.css',
    'resources/public/css/responsive.css',
    'resources/public/css/style.css',
], 'public/css/public/app.css');
