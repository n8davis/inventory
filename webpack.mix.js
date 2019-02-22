let mix = require('laravel-mix');


mix.setPublicPath( './' );
mix.scripts([
    'public/js/functions.js',
], 'public/js/all.js');
mix.js('resources/assets/js/app.js', 'public/js');
mix.sass('resources/assets/sass/app.scss', 'public/css');
