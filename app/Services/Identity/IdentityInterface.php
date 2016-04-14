<?php
namespace Pulse\Services\Identity;

interface IdentityInterface
{
    /**
     * Get Account
     * @param  string $account_id Account ID
     * @return Pulse\Serivces\Identity\Account\AccountInterface
     */
    public function getAccount($account_id = null);
}