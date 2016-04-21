<?php
namespace Pulse\Bus\Commands\Manager;

use Pulse\Models\User;
use Pulse\Models\Account;
use Pulse\Models\Provider;

class DeleteCommand
{
    public $user;

    public $account;

    public $file;

    public $data = [];

    /**
     * Create Delete Command
     * @param User     $user
     * @param Account  $account
     * @param string   $file
     * @param array   $data
     */
    public function __construct(User $user, Account $account, $file, array $data = array())
    {
        $this->user = $user;
        $this->account = $account;
        $this->file = $file;
        $this->data = $data;
    }
}
