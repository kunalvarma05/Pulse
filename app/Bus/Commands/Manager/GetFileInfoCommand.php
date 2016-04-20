<?php
namespace Pulse\Bus\Commands\Manager;

use Pulse\Models\User;
use Pulse\Models\Account;

class GetFileInfoCommand
{
    public $user;

    public $account;

    public $file;

    public $data;

    /**
     * Create GetFileInfoCommand
     * @param Pulse\Models\User    $user
     * @param Pulse\Models\Account $account
     * @param string  $file
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