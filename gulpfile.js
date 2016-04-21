//Disable Notifier
process.env.DISABLE_NOTIFIER = true;

//Require Laravel Elixir
var elixir = require('laravel-elixir');
//Laravel Elixir Vueify
require('laravel-elixir-vueify');

//Sourcemaps
elixir.config.sourcemaps = false;

//Start Elixir
elixir(function(mix) {
    //Compile Sass File
    mix.sass('app.scss');
    //Browserify
    mix.browserify('main.js');
    //Version
    //mix.version(['css/app.css', 'js/main.js']);
});
