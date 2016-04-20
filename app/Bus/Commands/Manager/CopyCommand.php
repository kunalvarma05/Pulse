<?php
namespace Pulse\Bus\Commands\Manager;

use Pulse\Models\User;
use Pulse\Models\Account;
use Pulse\Models\Provider;

class CopyCommand
{
    public $user;

    public $account;

    public $file;

    public $location = null;

    public $title = null;

    public $data = [];

    /**
     * Create Copy Command
     * @param User     $user
     * @param Account  $account
     * @param string   $file
     * @param string   $location
     * @param string   $title
     * @param array   $data
     */
    public function __construct(User $user, Account $account, $file, $location = null, $title = null, array $data = array())
    {
        $this->user = $user;
        $this->account = $account;
        $this->file = $file;
        $this->location = $location;
        $this->title = $title;
        $this->data = $data;
    }
}