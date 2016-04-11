<?php
namespace Pulse\Services\Authorization\OneDrive;

use GuzzleHttp\Client as Guzzle;
use Stevenmaguire\OAuth2\Client\Provider\Microsoft;
use Pulse\Services\Authorization\AuthorizationInterface;

class Authorization implements AuthorizationInterface
{

    /**
     * OAuth Provider
     * @var Stevenmaguire\OAuth2\Client\Provider\Microsoft
     */
    protected $provider;

    /**
     * Guzzle Client
     * @var GuzzleHttp\Client
     */
    protected $guzzleClient;

    /**
     * Session Store
     * @var \Illuminate\Session\Store
     */
    protected $sessionStore;

    /**
     * State Token Label
     * @var string
     */
    protected $tokenLabel = "onedrive-oauth-state";


    public function __construct()
    {
        $this->provider = new Microsoft([
            'clientId'          => config('onedrive.client_id'),
            'clientSecret'      => config('onedrive.client_secret'),
            'redirectUri'       => config('onedrive.callback_url')
            ]);

        $this->guzzleClient = new Guzzle;

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
        $options = array(
            'scope' => config('onedrive.scope')
            );

        $authUrl = $this->provider->getAuthorizationUrl($options);

        //Save state in the session
        $this->sessionStore->put($this->tokenLabel, $this->provider->getState());

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

        // Try to get an access token (using the authorization code grant)
        $access_token = $this->provider->getAccessToken('authorization_code', [
            'code' => $code
            ]);

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