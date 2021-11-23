const mix = require("laravel-mix");

const path = require("path");
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

mix.sass("resources/sass/app.scss", "public/css")
    .postCss("resources/css/sb-admin.css", "public/css/style.css")
    .webpackConfig({
        entry: {
            app: "./resources/js/app.js",
            datatables: "./resources/js/datatables.js",
        },
        output: {
            path: path.resolve(__dirname, "public"),
            filename: "js/[name].js",
            library: {
                type: "umd",
                name: "DataTable",
            },
        },
    });
