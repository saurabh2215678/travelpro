const mix = require("laravel-mix");
const themeName = process.env.THEME || "grandindiatour"; 
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.setResourceRoot(`/assets/${themeName}/`);
mix.js(`resources/js/themes/${themeName}/app.js`, "public/js")
    .vue()
    .postCss(`resources/js/themes/${themeName}/assets/css/app.css`, `public/assets/${themeName}/css`, [
        //
    ])
    .extract([
      'vue',
      'axios',
      '@coreui/coreui',
      '@coreui/vue',
      '@fancyapps/ui',
      '@fullcalendar/core',
      '@fullcalendar/daygrid',
      '@fullcalendar/interaction',
      '@fullcalendar/vue3',
      '@inertiajs/inertia',
      '@inertiajs/inertia-vue3',
      '@inertiajs/progress',
      '@mayasabha/ckeditor4-vue3',
      '@vueform/multiselect',
      '@vueform/slider',
      'lottie-vuejs',
      'md5',
      'moment',
      'v-calendar',
      'vee-validate',
      'vue-form',
      'vue-inline-svg',
      'vue-loader',
      'vue-multiselect',
      'vue-search-select',
      'vue-star-rating',
      'vue-toast-notification',
      'vue3-popper',
      'vue3-slide-up-down',
      'vue3-styled-components',
      'yup'
    ]);

mix.vue()
    .postCss(`resources/js/themes/${themeName}/assets/css/media.css`, `public/assets/${themeName}/css`, [
        //
    ]);
mix.copyDirectory(`resources/js/themes/${themeName}/assets/images`, `public/assets/${themeName}/images`);
mix.webpackConfig({
    optimization: {
        splitChunks: {
            chunks: 'all',
            minSize: 80000,
            maxSize: 170000,
            maxAsyncRequests: 30,
            maxInitialRequests: 30,
        },
    },
	output: {
    filename: '[name].js?id=[chunkhash]',
    chunkFilename: 'js/[name].[chunkhash:6].js',
  },
});