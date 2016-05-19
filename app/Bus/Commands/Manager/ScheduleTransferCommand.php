<?php
namespace Pulse\Bus\Commands\Manager;

use Pulse\Models\User;
use Pulse\Models\Account;

class ScheduleTransferCommand
{
    public $user;

    public $account;

    public $newAccount;

    public $file;

    public $location;

    public $scheduled_at;

    public $data;

    /**
     * Create ScheduleTransferCommand
     * @param User    $user
     * @param Account $account
     * @param Account $newAccount
     * @param string  $file
     * @param string  $scheduled_at
     * @param string  $location
     * @param array   $data
     */
    public function __construct(User $user, Account $account, Account $newAccount, $file, $scheduled_at = null, $location = null, array $data = array())
    {
        $this->user = $user;
        $this->account = $account;
        $this->newAccount = $newAccount;
        $this->file = $file;
        $this->scheduled_at = $scheduled_at;
        $this->location = $location;
        $this->data = $data;
    }
}
