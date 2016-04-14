<?php
namespace Pulse\Services\Identity\Adapters;

interface AdapterFactoryInterface
{
    /**
     * Create IdentityAdapter
     * @param  string $adapter
     * @param  string $access_token
     * @return Pulse\Serives\Identity\Adapters\AdapterInterface
     */
    public static function create($adapter, $access_token);
}
