<?php

namespace Pulse\Providers;

use Illuminate\Support\ServiceProvider;
use Pulse\Services\Authorization\Resolver;


class AuthorizationResolverProvider extends ServiceProvider
{
    /**
     * Resolver
     * @var Pulse\Services\Authorization\Resolver
     */
    protected $resolver;

    /**
     * Pulse Authorization Providers
     * @var array
     */
    protected $authProviders = [
    'dropbox' => 'Pulse\Services\Authorization\Dropbox\Authorization'
    ];

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
        $this->resolver = new Resolver();

        //Register Resolver Alias
        $this->app->alias('Pulse\Services\Authorization\ResolverInterface', 'pulse.auth.resolver');

        //Resolver Instance Binding
        $this->app->instance('Pulse\Services\Authorization\ResolverInterface', $this->resolver);

        //Register Authorization Providers
        $this->registerAuthProviders();
    }

    /**
     * Register Authorization Providers
     * @return void
     */
    protected function registerAuthProviders()
    {
        //Register Authorization Resolvers
        $this->resolver->registerMultiple((array) $this->authProviders);
    }
}
