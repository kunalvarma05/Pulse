<?php
namespace Pulse\Services\Authorization\Adapters;

interface AdapterFactoryInterface
{
    /**
     * Create Adapter
     * @param  string $adapter
     * @return AdapterInterface
     */
    public static function create($adapter);
}
