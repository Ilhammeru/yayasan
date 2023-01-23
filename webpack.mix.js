let mix = require('laravel-mix');

require('laravel-mix-polyfill');

mix.setPublicPath('public')
    .js('resources/js/intitution.js', 'dist/js')
    .js('resources/js/role.js', 'dist/js')
    .js('resources/js/position.js', 'dist/js')
    .js('resources/js/employee.js', 'dist/js')
    .js('resources/js/base.js', 'dist/js')
    .js('resources/js/permission.js', 'dist/js')
    .js('resources/js/user.js', 'dist/js')
    .js('resources/js/income.js', 'dist/js')
    .js('resources/js/incomeType.js', 'dist/js')
    .js('resources/js/incomeMethod.js', 'dist/js')
    .js('resources/js/mainIncome.js', 'dist/js')
    .js('resources/js/master.js', 'dist/js')
    .js('resources/views/layouts/js/app.js', 'assets/js')
    .webpackConfig({
        mode: 'development',
        optimization: {
            sideEffects: false
        }
    })
    // .browserSync('http://yayasan.test')
    .version();