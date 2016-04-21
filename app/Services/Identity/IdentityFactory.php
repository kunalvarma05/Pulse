<?php
namespace Pulse\Services\Identity;

use Pulse\Services\Identity\Adapters\AdapterFactory;

class IdentityFactory implements IdentityFactoryInterface
{

    /**
     * Create an Identity Service the adapter
     * of the given identity provider.
     * @param  string $provider     Identity Provider
     * @param  string $access_token Access Token
     * @return Pulse\Services\Identity\IdentityInterface
     */
    public static function create($provider, $access_token)
    {
        //Create Identity Adapter
        $adapter = AdapterFactory::create($provider, $access_token);

        //Make Identity Service with the Adapter
        return new Identity($adapter);
    }
}
