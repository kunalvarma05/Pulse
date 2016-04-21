<?php
namespace Pulse\Bus\Commands\Provider;

use Pulse\Models\User;
use Pulse\Models\Provider;

class GenerateAccessTokenCommand
{

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
     * Provider
     *
     * @var Pulse\Models\Provider
     */
    public $provider;

    /**
     * Create a new GenerateAccessTokenCommand instance
     *
     * @param string $code
     * @param string $state
     * @param Pulse\Models\Provider $provider
     *
     * @return void
     */
    public function __construct($code, $state, Provider $provider)
    {
        $this->code = $code;
        $this->state = $state;
        $this->provider = $provider;
    }
}
