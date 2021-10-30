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

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .copy('node_modules/axios/dist/axios.min.js', 'public/js/axios.min.js')
    .copy('node_modules/axios/dist/axios.min.map', 'public/js/axios.min.map')
    .copy('node_modules/moment/min/moment.min.js', 'public/js/moment.min.js')
    .copy('node_modules/moment/min/moment.min.js.map', 'public/js/moment.min.js.map')
    .copy('node_modules/ldloader/dist/index.min.js', 'public/js/ldloader.min.js')
    .copy('node_modules/ldloader/dist/index.min.css', 'public/css/ldloader.min.css')
