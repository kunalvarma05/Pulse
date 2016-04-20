<?php
namespace Pulse\Bus\Commands\Manager;

use Pulse\Models\User;
use Pulse\Models\Account;
use Pulse\Models\Provider;

class MoveCommand
{
    public $user;

    public $account;

    public $provider;

    public $file;

    public $location;

    public $data = [];

    /**
     * Create Move Command
     * @param User     $user
     * @param Account  $account
     * @param Provider $provider
     * @param string   $file
     * @param string   $location
     * @param array   $data
     */
    public function __construct(User $user, Account $account, Provider $provider, $file, $location, array $data = array())
    {
        $this->user = $user;
        $this->account = $account;
        $this->provider = $provider;
        $this->file = $file;
        $this->location = $location;
        $this->data = $data;
    }
}