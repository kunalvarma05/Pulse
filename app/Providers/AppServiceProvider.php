<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if($this->app->environment() === "local"){
            //Service Providers
            $this->app->register('Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider');
            $this->app->register('Laracasts\Generators\GeneratorsServiceProvider');
            $this->app->register('Arcanedev\LogViewer\LogViewerServiceProvider');
            $this->app->register('Barryvdh\Debugbar\ServiceProvider');

            //Aliases
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Debugbar', "Barryvdh\Debugbar\Facade");
        }
    }
}
