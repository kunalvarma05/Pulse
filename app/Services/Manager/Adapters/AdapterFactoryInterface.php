<?php
namespace Pulse\Services\Manager\Adapters;

interface AdapterFactoryInterface
{
    /**
     * Create ManagerAdapter
     * @param  string $adapter
     * @param  string $access_token
     * @return Pulse\Serives\Manager\Adapters\AdapterInterface
     */
    public static function create($adapter, $access_token);
}
