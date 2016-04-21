<?php
namespace Pulse\Bus\Commands\Account;

use Pulse\Models\User;
use Pulse\Models\Provider;
use Illuminate\Http\Request;

class ConnectAccountCommand
{
    /**
     * User
     * @var Pulse\Models\User
     */
    public $user;

    /**
     * Provider
     * @var Pulse\Models\Provider
     */
    public $provider;

    /**
     * Authorization Code
     *
     * @var string
     */
    public $code;

    /**
     * CSRF State Token
     *
     * @var string
     */
    public $state;

    /**
     * Account Name
     *
     * @var string
     */
    public $name;

    /**
     * Constructor
     * @param string   $code     Auth Code
     * @param string   $state    CSRF State
     * @param string   $name     Account Name
     * @param User     $user     User Model
     * @param Provider $provider Provider Model
     */
    public function __construct($code, $state, $name, User $user, Provider $provider)
    {
        $this->code = $code;
        $this->user = $user;
        $this->name = $name;
        $this->state = $state;
        $this->provider = $provider;
    }
}
