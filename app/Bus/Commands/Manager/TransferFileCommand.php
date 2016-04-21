<?php
namespace Pulse\Bus\Commands\Manager;

use Pulse\Models\User;
use Pulse\Models\Account;

class TransferFileCommand
{
    public $user;

    public $account;

    public $newAccount;

    public $file;

    public $location;

    public $title;

    public $data;

    /**
     * Create TransferFileCommand
     * @param User    $user
     * @param Account $account
     * @param Account $newAccount
     * @param string  $file
     * @param string  $location
     * @param string  $title
     * @param array   $data
     */
    public function __construct(User $user, Account $account, Account $newAccount, $file, $location = null, $title = null, array $data = array())
    {
        $this->user = $user;
        $this->account = $account;
        $this->newAccount = $newAccount;
        $this->file = $file;
        $this->location = $location;
        $this->title = $title;
        $this->data = $data;
    }
}
