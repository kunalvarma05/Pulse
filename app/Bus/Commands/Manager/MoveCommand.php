<?php
namespace Pulse\Bus\Commands\Manager;

use Pulse\Models\User;
use Pulse\Models\Account;
use Pulse\Models\Provider;

class MoveCommand
{
    public $user;

    public $account;

    public $file;

    public $location;

    public $data = [];

    /**
     * Create Move Command
     * @param User     $user
     * @param Account  $account
     * @param string   $file
     * @param string   $location
     * @param array   $data
     */
    public function __construct(User $user, Account $account, $file, $location, array $data = array())
    {
        $this->user = $user;
        $this->account = $account;
        $this->file = $file;
        $this->location = $location;
        $this->data = $data;
    }
}
