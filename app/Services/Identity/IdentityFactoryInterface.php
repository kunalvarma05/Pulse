<?php
namespace Pulse\Services\Identity;

interface IdentityFactoryInterface
{

    /**
     * Create an Identity Service the adapter
     * of the given identity provider.
     * @param  string $provider     Identity Provider
     * @param  string $access_token Access Token
     * @return Pulse\Services\Identity\IdentityInterface
     */
    public static function create($provider, $access_token);
}
