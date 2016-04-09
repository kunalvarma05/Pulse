<?php
namespace Pulse\Services\Authorization;

use Exception;

class Resolver implements ResolverInterface
{
    /**
     * Providers
     * @var array
     */
    protected $providers = array();

    /**
     * Register Authorization Provider
     * @param  string $alias Provider Alias to Resolve
     * @param  string $provider Provider
     * @return Pulse\Services\Authorization\Resolver
     */
    public function register($alias, $provider)
    {
        //Not Registered
        if(!array_key_exists($alias, $this->providers)) {
            $this->providers[$alias] = $provider;
        }

        return $this;
    }

    /**
     * Register Multiple Authorization Provider Together
     * @param  array $providers Provider Alias Collection
     * @return Pulse\Services\Authorization\Resolver
     */
    public function registerMultiple(array $providers)
    {
        foreach ($providers as $alias => $provider) {
            $this->register($alias, $provider);
        }

        return $this;
    }

    /**
     * Resolve Authorization Provider
     * @param  string $alias Provider Alias to Resolve
     * @return Pulse\Services\Authorization\AuthorizationInterface
     */
    public function resolve($alias)
    {
        if(!array_key_exists($alias, $this->providers)) {
            throw new Exception("Cannot resolve provider!");
        }

        $provider = app($this->providers[$alias]);

        if(!$provider instanceof AuthorizationInterface)
        {
            throw new Exception("Invalid provider!");
        }

        //Resolve from the Container
        return $provider;
    }

    /**
     * Return the Registered Providers
     * @return array
     */
    public function getRegisteredProviders()
    {
        return $this->providers;
    }

}