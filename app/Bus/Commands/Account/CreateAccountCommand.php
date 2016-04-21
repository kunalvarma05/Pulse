<?php
namespace Pulse\Bus\Commands\Account;

use Pulse\Models\User;
use Pulse\Models\Provider;

class CreateAccountCommand
{

    /**
     * User
     *
     * @var Pulse\Models\User
     */
    public $user;

    /**
     * Provider
     *
     * @var Pulse\Models\Provider
     */
    public $provider;

    /**
     * Name
     *
     * @var string
     */
    public $name;

    /**
     * UID
     *
     * @var string
     */
    public $uid;

    /**
     * Access Token
     *
     * @var string
     */
    public $access_token;

    /**
     * Create a new CreateAccountCommand instance
     *
     * @param string $name
     * @param Pulse\Models\User $user
     * @param Pulse\Models\Provider $provider
     * @param string $uid
     * @param string $access_token
     *
     * @return void
     */
    public function __construct(User $user, Provider $provider, $name, $uid, $access_token)
    {
        $this->user = $user;
        $this->provider = $provider;
        $this->name = $name;
        $this->uid = $uid;
        $this->access_token = $access_token;
    }
}
