<?php
namespace Pulse\Services\Identity;

interface IdentityInterface
{
    public function getAccount($account_id = null);
}