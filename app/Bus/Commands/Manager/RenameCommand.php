<?php
namespace Pulse\Bus\Commands\Manager;

use Pulse\Models\User;
use Pulse\Models\Account;

class RenameCommand
{
    public $user;

    public $account;

    public $file;

    public $title;

    public $data = [];

    /**
     * Create Rename Command
     * @param User     $user
     * @param Account  $account
     * @param string   $file
     * @param string   $title
     * @param array   $data
     */
    public function __construct(User $user, Account $account, $file, $title, array $data = array())
    {
        $this->user = $user;
        $this->account = $account;
        $this->file = $file;
        $this->title = $title;
        $this->data = $data;
    }
}
