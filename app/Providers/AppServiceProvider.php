<?php

namespace Pulse\Providers;

use AltThree\Bus\Dispatcher;
use Illuminate\Support\ServiceProvider;
use Pulse\Services\Authorization\Adapters\Dropbox\DropboxCsrfTokenStore;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @param AltThree\Bus\Dispatcher $dispatcher
     * @return void
     */
    public function boot(Dispatcher $dispatcher)
    {
        //Map Commands and Handlers
        $dispatcher->mapUsing(function ($command) {
            return Dispatcher::simpleMapping($command, 'Pulse\Bus', 'Pulse\Bus\Handlers');
        });
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

        $this->app->singleton('Pulse\Services\Authorization\Adapters\Dropbox\DropboxCsrfTokenStoreInterface', function ($app) {
            return new DropboxCsrfTokenStore($app->make('Illuminate\Session\Store'));
        });

        $this->app->bind('Pulse\Services\Manager\Quota\QuotaInterface', 'Pulse\Services\Manager\Quota\Quota');
        $this->app->bind('Pulse\Services\Identity\Account\AccountInterface', 'Pulse\Services\Identity\Account\Account');
    }
}
