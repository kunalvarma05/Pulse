<?php
namespace Pulse\Services\Authorization\Adapters\OneDrive;

use Illuminate\Session\SessionInterface;
use League\OAuth2\Client\Token\AccessToken;
use Stevenmaguire\OAuth2\Client\Provider\Microsoft;
use Pulse\Services\Authorization\Adapters\AdapterInterface;

class AuthAdapter implements AdapterInterface
{

    /**
     * OAuth Provider
     * @var Stevenmaguire\OAuth2\Client\Provider\Microsoft
     */
    protected $provider;

    /**
     * Session Store
     * @var \Illuminate\Session\SessionInterface
     */
    protected $sessionStore;

    /**
     * State Token Label
     * @var string
     */
    protected $tokenLabel = "onedrive-oauth-state";


    /**
     * Constructor
     * @param Stevenmaguire\OAuth2\Client\Provider\Microsoft        $provider
     * @param Illuminate\Session\SessionInterface                    $sessionStore
     */
    public function __construct(Microsoft $provider, SessionInterface $sessionStore)
    {
        $this->provider = $provider;

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

        // Try to get an access token (using the authorization code grant)
        $access_token = $this->provider->getAccessToken('authorization_code', [
            'code' => $code
            ]);

        //Remove CSRF State Token from Session
        $this->sessionStore->forget($this->tokenLabel);

        return json_encode($access_token->jsonSerialize());
    }

    /**
     * Refresh Access Token
     * @param  string $access_token
     * @return string
     */
    public function refreshAccessToken($access_token)
    {
        $access_token = new AccessToken(json_decode($access_token, true));

        //If the access token has expired, refresh it
        if ($access_token->hasExpired()) {
            $access_token = $this->provider->getAccessToken('refresh_token', [
                'refresh_token' => $access_token->getRefreshToken()
                ]);
        }

        return json_encode($access_token->jsonSerialize());
    }

}