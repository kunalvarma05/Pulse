<?php
namespace Pulse\Bus\Commands\Manager;

use Pulse\Models\Account;

class GetQuotaCommand
{
    public $account;

    public function __construct(Account $account)
    {
        $this->account = $account;
    }
}
