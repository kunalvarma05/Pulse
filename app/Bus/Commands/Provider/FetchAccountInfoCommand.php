<?php
namespace Pulse\Bus\Commands\Provider;

use Pulse\Models\Provider;

class FetchAccountInfoCommand
{

    /**
     * Provider
     * @var Pulse\Models\Provider
     */
    public $provider;

    /**
     * Access Token
     * @var string
     */
    public $access_token;

    /**
     * Fetch Account Info
     * @param Provider $provider
     * @param string   $access_token
     */
    public function __construct(Provider $provider, $access_token)
    {
        $this->provider = $provider;
        $this->access_token = $access_token;
    }
}
