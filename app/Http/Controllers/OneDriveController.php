<?php

namespace Pulse\Http\Controllers;

use \Auth;
use \Session;
use Pulse\Http\Requests;
use Illuminate\Http\Request;
use GuzzleHttp\Client as Guzzle;
use League\Flysystem\Filesystem;
use Stevenmaguire\OAuth2\Client\Provider\Microsoft;
use Kunnu\OneDrive\Client;

class OneDriveController extends Controller
{

    private $user;
    private $client;
    private $provider;
    private $guzzleClient;

    public function __construct(){
        $this->user = (!\Auth::check()) ? \Auth::login(\Pulse\Models\User::first()) : \Auth::user();

        $this->provider = new Microsoft([
            'clientId'          => config('onedrive.client_id'),
            'clientSecret'      => config('onedrive.client_secret'),
            'redirectUri'       => config('onedrive.callback_url')
            ]);

        $this->guzzleClient = new Guzzle;
    }

    public function connect(Request $request){
        //Session has an access token
        if(Session::has('onedrive-access-token')){
            return redirect('api/onedrive');
        }
        // If we don't have an authorization code then get one
        $options = array(
            'scope' => ['wl.basic', 'wl.signin', 'wl.offline_access', 'onedrive.readwrite']
            );

        $authUrl = $this->provider->getAuthorizationUrl($options);
        Session::put('oauth2state', $this->provider->getState());
        return redirect($authUrl);
    }


    public function auth(Request $request){
        return $request->all();
        //No code or state, accessed directly.
        if (!$request->has('code') || !$request->has('state')) {
            return redirect('connect/onedrive');

        // Check given state against previously stored one to mitigate CSRF attack
        } elseif (($request->input('state') !== Session::get('oauth2state'))) {
            Session::forget('oauth2state');
            abort(500, 'Invalid state');
        } else {

            // Try to get an access token (using the authorization code grant)
            $access_token = $this->provider->getAccessToken('authorization_code', [
                'code' => $request->input('code')
                ]);

            //Put the obtained access token in the session
            Session::put('onedrive-access-token', $access_token);

            return redirect('api/onedrive');
        }
    }

    public function api(){
        //Session doesn't have an access token
        if(!Session::has('onedrive-access-token')){
            return redirect('connect/onedrive');
        }

        //Get the Access Token
        $access_token = $this->getAccessToken();

        try {

            // Get the user's details
            //$user = $this->provider->getResourceOwner($access_token);
            //$user->setImageurl("https://apis.live.net/v5.0/{$user->getId()}/picture?type=large");

            $oneDriveClient = new Client($access_token->getToken(), $this->guzzleClient);

            dd($oneDriveClient->getDefaultDrive());

            $file = $oneDriveClient->getItem("7D780E8525603004!1315");
            dd($access_token, $file);

        } catch (Exception $e) {
            // Failed to get user details
            abort(500, 'Oh dear...');
        }
    }

    protected function getAccessToken(){
        //Fetch the access token from the session
        $access_token = Session::get('onedrive-access-token');

        //If the access token has expired, refresh it
        if ($access_token->hasExpired()) {
            $access_token = $this->provider->getAccessToken('refresh_token', [
                'refresh_token' => $access_token->getRefreshToken()
                ]);
        }

        return $access_token;
    }
}
