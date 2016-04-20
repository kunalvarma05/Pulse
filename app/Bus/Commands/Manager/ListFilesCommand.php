<?php
namespace Pulse\Bus\Commands\Manager;

use Pulse\Models\Account;

class ListFilesCommand
{
    public $account;

    public $path;

    public function __construct(Account $account, $path = null)
    {
        $this->account = $account;
        $this->path = $path;
    }
}