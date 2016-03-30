//Disable Notifier
process.env.DISABLE_NOTIFIER = true;

//Require Laravel Elixir
var elixir = require('laravel-elixir');
//Laravel Elixir Vueify
require('laravel-elixir-vueify');

//No Sourcemaps required
elixir.config.sourcemaps = false;

//Start Elixir
elixir(function(mix) {
    //Compile Sass File
    mix.sass('app.scss');
    mix.version('css/app.css');

    //Concatenate JS Files
    //mix.scripts(['jquery.js', 'bootstrap.js'], 'public/js/app.js');

    //Browserify
    mix.browserify('main.js');
});
