<?php
namespace Pulse\Services\Authorization;

use Pulse\Services\Authorization\AuthorizationInterface;

interface ResolverInterface
{

    /**
     * Register Authorization Provider
     * @param  string $alias Provider Alias to Resolve
     * @param  string $provider Provider
     * @return Pulse\Services\Authorization\Resolver
     */
    public function register($alias, $provider);

    /**
     * Register Multiple Authorization Provider Together
     * @param  array $providers Provider Alias Collection
     * @return Pulse\Services\Authorization\Resolver
     */
    public function registerMultiple(array $providers);

    /**
     * Resolve Authorization Provider
     * @param  string $alias Provider Alias to Resolve
     * @return Pulse\Services\Authorization\AuthorizationInterface
     */
    public function resolve($alias);

    /**
     * Return the Registered Providers
     * @return array
     */
    public function getRegisteredProviders();

}