<?php
namespace Pulse\Services\Authorization;

use Pulse\Services\Authorization\Adapters\AdapterFactory;

class AuthFactory implements AuthFactoryInterface
{

    /**
     * Create a Authorization Service with
     * Adapter of the specified provider
     * @param  string $provider Adapter Provider
     * @return AuthorizationInterface
     */
    public static function create($provider)
    {
        //Resolve Auth Adapter
        $authAdapter = AdapterFactory::create($provider);

        //Authorization Service
        return new Authorization($authAdapter);
    }
}
