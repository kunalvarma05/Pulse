<?php
namespace Pulse\Services\Manager;

use Pulse\Services\Manager\Adapters\AdapterFactory;

class ManagerFactory implements ManagerFactoryInterface
{

    /**
     * Create an Manager Service the adapter
     * of the given manager provider.
     * @param  string $provider     Manager Provider
     * @param  string $access_token Access Token
     * @return Pulse\Services\Manager\ManagerInterface
     */
    public static function create($provider, $access_token)
    {
        //Create Manager Adapter
        $adapter = AdapterFactory::create($provider, $access_token);

        //Make Manager Service with the Adapter
        return new Manager($adapter);
    }

}