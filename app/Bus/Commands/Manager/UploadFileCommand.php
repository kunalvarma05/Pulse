<?php
namespace Pulse\Bus\Commands\Manager;

use Pulse\Models\User;
use Pulse\Models\Account;

class UploadFileCommand
{

    public $user;

    public $account;

    public $file;

    public $location;

    public $title;

    public $data;


    /**
     * Create UploadFileCommand
     * @param User    $user
     * @param Account $account
     * @param string  $file     File
     * @param string  $location Location
     * @param string  $title    Title
     * @param array   $data     Additional Data
     */
    public function __construct(User $user, Account $account, $file, $location = null, $title = null, $data = array())
    {
        $this->user = $user;
        $this->account = $account;
        $this->file = $file;
        $this->location = $location;
        $this->title = $title;
        $this->data = $data;
    }
}
