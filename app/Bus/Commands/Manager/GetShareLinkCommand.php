<?php
namespace Pulse\Bus\Commands\Manager;

use Pulse\Models\Account;

class GetShareLinkCommand
{

    public $account;

    public $file;

    public $data;


    /**
     * Create GetShareLinkCommand
     * @param Account $account
     * @param string  $file    File
     * @param array   $data    Additional Data
     */
    public function __construct(Account $account, $file, array $data = array())
    {
        $this->account = $account;
        $this->file = $file;
        $this->data = $data;
    }
}
