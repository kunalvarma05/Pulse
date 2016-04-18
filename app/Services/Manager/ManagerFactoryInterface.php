<?php
namespace Pulse\Services\Manager;

interface ManagerFactoryInterface
{

    /**
     * Create an Manager Service the adapter
     * of the given manager provider.
     * @param  string $provider     Manager Provider
     * @param  string $access_token Access Token
     * @return Pulse\Services\Manager\ManagerInterface
     */
    public static function create($provider, $access_token);

}