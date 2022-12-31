let mix = require('laravel-mix');
var LiveReloadPlugin = require('webpack-livereload-plugin');

mix.js('resources/js/intitution.js', 'dist/js')
    .js('resources/js/role.js', 'dist/js')
    .js('resources/js/position.js', 'dist/js')
    .js('resources/js/employee.js', 'dist/js')
    .js('resources/js/base.js', 'dist/js')
    .js('resources/views/layouts/js/app.js', 'assets/js')
    .setPublicPath('public')
    .browserSync('http://yayasan.test');