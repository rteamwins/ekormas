const mix = require("laravel-mix");

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

mix
  .js("resources/js/app.js", "public/js")
  .extract([
    "vue",
    "uikit",
    "vuex",
    "vuex-persist",
    "vuejs-paginate",
    "vue2-org-tree",
    "vue-sweetalert2",
    "vue-qrcode-reader",
    "vue-qrcode",
    "vue-apexcharts",
    "moment",
    "apexcharts"
  ])
  .version()
  .sass("resources/sass/app.scss", "public/css");
mix.browserSync("localhost:8055");
mix.disableNotifications();
