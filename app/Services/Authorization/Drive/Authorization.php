<?php
namespace Pulse\Services\Authorization\Drive;

use \Google_Client;
use \Google_Service_Drive;
use GuzzleHttp\Client as Guzzle;
use Stevenmaguire\OAuth2\Client\Provider\Microsoft;
use Pulse\Services\Authorization\AuthorizationInterface;

class Authorization implements AuthorizationInterface
{

    /**
     * Google Client
     * @var Google_Client
     */
    protected $client;

    /**
     * Session Store
     * @var \Illuminate\Session\Store
     */
    protected $sessionStore;

    /**
     * State Token Label
     * @var string
     */
    protected $tokenLabel = "google-oauth-state";


    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setClientId(config("drive.client_id"));
        $this->client->setClientSecret(config("drive.client_secret"));
        $this->client->setRedirectUri(config("drive.callback_url"));
        $this->client->setAccessType('offline');
        $this->client->setScopes(Google_Service_Drive::DRIVE);

        $this->sessionStore = app('Illuminate\Session\Store');
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
     * @param  League\OAuth2\Client\Token\AccessToken $access_token
     * @return League\OAuth2\Client\Token
     */
    public function refreshAccessToken($access_token)
    {
        //If the access token has expired, refresh it
        if ($access_token->hasExpired()) {
            $access_token = $this->provider->getAccessToken('refresh_token', [
                'refresh_token' => $access_token->getRefreshToken()
                ]);
        }

        return $access_token;
    }

}