<?php
namespace Pulse\Services\Identity;

interface IdentityInterface
{
    /**
     * Get Account
     * @return Pulse\Serivces\Identity\Account\AccountInterface
     */
    public function getAccount();
}