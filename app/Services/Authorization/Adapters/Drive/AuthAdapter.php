<?php
namespace Pulse\Services\Authorization\Adapters\Drive;

use \Google_Client;
use \Google_Service_Drive;
use GuzzleHttp\Client as Guzzle;
use Illuminate\Session\SessionInterface;
use Stevenmaguire\OAuth2\Client\Provider\Microsoft;
use Pulse\Services\Authorization\Adapters\AdapterInterface;

class AuthAdapter implements AdapterInterface
{

    /**
     * Google Client
     * @var Google_Client
     */
    protected $client;

    /**
     * Session Store
     * @var \Illuminate\Session\SessionInterface
     */
    protected $sessionStore;

    /**
     * State Token Label
     * @var string
     */
    protected $tokenLabel = "google-oauth-state";

    /**
     * Constructor
     * @param Google_Client    $client
     * @param SessionInterface $sessionStore
     */
    public function __construct(Google_Client $client, SessionInterface $sessionStore)
    {
        $this->client = $client;

        $this->sessionStore = $sessionStore;
    }

    /**
     * Get Authorization URL
     *
     * @param  array $data Additional Data
     * @return string
     */
    public function getAuthorizationUrl(array $data = array())
    {
        $state = str_random(32);
        $this->client->setState($state);

        $authUrl = $this->client->createAuthUrl();

        //Save state in the session
        $this->sessionStore->put($this->tokenLabel, $state);

        return $authUrl;
    }

    /**
     * Get Access Token
     * @param  string $code  Authorization Code
     * @param  string $state CSRF State
     * @param  array  $data  Additional Data
     * @return string        Access Token
     */
    public function getAccessToken($code, $state, array $data = array())
    {
        //Access Token is already present
        if(isset($data['access_token'])) {
            return $this->refreshAccessToken($data['access_token']);
        }

        //Check Session State with the provided state
        //Prevents against CSRF Attacks
        if($state !== $this->sessionStore->get($this->tokenLabel))
        {
            //Probably a CSRF Attack
            throw new \Exception("Invalid OAuth State Token");
        }

        $this->client->authenticate($code);
        $access_token = $this->client->getAccessToken();

        //Remove CSRF State Token from Session
        $this->sessionStore->forget($this->tokenLabel);

        return (string) $access_token;
    }

    /**
     * Refresh Access Token
     * @param  string $access_token
     * @return string JSON Encoded
     */
    public function refreshAccessToken($access_token)
    {
        //No Access Token Set
        if(!$this->client->getAccessToken()) {
            $this->client->setAccessToken($access_token);
        }

        //If the access token has expired, refresh it
        if ($this->client->isAccessTokenExpired()) {
            $this->client->refreshToken($this->client->getRefreshToken());
        }

        return $this->client->getAccessToken();

    }

}