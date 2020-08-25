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

mix.webpackConfig({
    resolve:{
        extensions:['.js','.vue','.ts'],
        alias:{
            '@': __dirname + '/resources'
        }
    },

});


mix
    .js("resources/js/app.js", "public/js")
    .sass("resources/sass/app.scss", "public/css").sourceMaps()
    // .styles([
    //     'node_modules/iziToast/dist/css/iziToast.min.css',
    //     'node_modules/@mdi/font/css/materialdesignicons.min.css',
    // ],'public/css/ctscustom.min.css')
    .webpackConfig({
    module: {
    rules: [
        {
            test: /\.tsx?$/,
            loader: "ts-loader",
            exclude: /node_modules/
        }

        ]
    }
});



if (mix.inProduction()) {
    mix.version();
}
