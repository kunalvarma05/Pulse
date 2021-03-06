<?php
namespace Pulse\Services\Authorization;

interface AuthFactoryInterface
{

    /**
     * Create a Authorization Service with
     * Adapter of the specified provider
     * @param  string $provider Adapter Provider
     * @return AuthorizationInterface
     */
    public static function create($provider);
}
