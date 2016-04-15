<?php
namespace Pulse\Services\Identity\Adapters;

interface AdapterInterface
{
    /**
     * Get Account
     * @return Pulse\Serivces\Identity\Account\AccountInterface
     */
    public function getAccount();
}