<?php
namespace Pulse\Bus\Commands\Manager;

use Pulse\Models\User;
use Pulse\Models\Account;

class CreateFolderCommand
{
    public $user;

    public $account;

    public $title;

    public $location;

    public $data = [];

    /**
     * Create CreateFolderCommand
     * @param User    $user
     * @param Account $account
     * @param string  $title    Folder Name
     * @param string  $location Folder Location
     * @param array   $data     Additional Data
     */
    public function __construct(User $user, Account $account, $title, $location = null, $data = array())
    {
        $this->user = $user;
        $this->account = $account;
        $this->title = $title;
        $this->location = $location;
        $this->data = $data;
    }
}