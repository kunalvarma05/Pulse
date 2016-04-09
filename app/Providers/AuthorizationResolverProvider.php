<?php

namespace Pulse\Providers;

use Illuminate\Support\ServiceProvider;
use Pulse\Services\Authorization\Resolver;


class AuthorizationResolverProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //Resolver Instance
        $resolver = new Resolver();

        //Resolver Instance Binding
        $this->app->instance('Pulse\Services\Authorization\ResolverInterface', $resolver);

        //Register Authorization Resolvers
        $resolver->registerMultiple(['dropbox' => 'Pulse\Services\Authorization\Dropbox\Authorization']);
    }
}
