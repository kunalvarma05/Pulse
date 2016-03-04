<?php

namespace App\Http\Controllers;

use \Auth;
use \Session;
use App\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use Stevenmaguire\OAuth2\Client\Provider\Microsoft;

class OneDriveController extends Controller
{

    private $user;
    private $client;
    private $provider;

    public function __construct(){
        if(!Auth::check()){
            Auth::login(User::first());
        }

        $this->user = Auth::user();

        $this->provider = new Microsoft([
            'clientId'          => env('MICROSOFT_CLIENT_ID'),
            'clientSecret'      => env('MICROSOFT_CLIENT_SECRET'),
            'redirectUri'       => url('auth/callback/onedrive')
            ]);
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

        $authUrl = $this->provider->getAuthorizationUrl();
        Session::put('oauth2state', $this->provider->getState());
        return redirect($authUrl);
    }


    public function auth(Request $request){
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
            $user = $this->provider->getResourceOwner($access_token);
            $user->setImageurl("https://apis.live.net/v5.0/{$user->getId()}/picture?type=large");

            // Use these details to create a new profile
            dd($user);

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
