<?php
namespace Pulse\Bus\Commands\Action;

use Pulse\Models\User;
use Pulse\Models\Account;

class RecordActionCommand {

    public $key;

    public $user;

    public $account;

    public function __construct($key, User $user, Account $account)
    {
        $this->key = $key;
        $this->user = $user;
        $this->account = $account;
    }


}