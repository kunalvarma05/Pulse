<?php

namespace App\Http\Controllers;

use \App\Http\Requests;
use Illuminate\Http\Request;
use \App\Http\Controllers\Controller;

class DropboxController extends Controller
{

    public function connect(){
        if(\Cache::has('dbx-access-token')){
            return redirect('api/dropbox');
        }
        $user = (!\Auth::check()) ? \Auth::login(\App\User::first()) : \Auth::user();
        Session::forget('Dropbox-oauth-key');
        $sessionStore = new DropboxSessionStore(\App::make('Illuminate\Session\Store'), "Dropbox-oauth-key");
        try {
            $appInfo = \Dropbox\AppInfo::loadFromJson(
                array(
                    "key" => config("dropbox.connections.main.app"),
                    "secret" => config("dropbox.connections.main.secret")
                    )
                );
        }
        catch (\Dropbox\AppInfoLoadException $ex) {
            fwrite(STDERR, "Error loading <app-info-file>: ".$ex->getMessage()."\n");
            die;
        }

        $webAuth = new \Dropbox\WebAuth($appInfo, "pulseapp", url("/auth/callback/dropbox", [], true), $sessionStore, "en");
        $authorizeUrl = urldecode($webAuth->start());
        return redirect($authorizeUrl);
    }

    public function auth(Request $request){
        $sessionStore = new DropboxSessionStore(\App::make('Illuminate\Session\Store'), "Dropbox-oauth-key");
        Session::set('dbx-token', $sessionStore->get());

        try {
            $appInfo = \Dropbox\AppInfo::loadFromJson(
                array(
                    "key" => config("dropbox.connections.main.app"),
                    "secret" => config("dropbox.connections.main.secret")
                    )
                );
        }
        catch (\Dropbox\AppInfoLoadException $ex) {
            fwrite(STDERR, "Error loading <app-info-file>: ".$ex->getMessage()."\n");
            die;
        }

        $webAuth = new \Dropbox\WebAuth($appInfo, "pulseapp", url("/auth/callback/dropbox"), $sessionStore, "en");

        $input = $request->all();
        $code = $input['code'];
        $state = $input['state'];
        $authCode = array('state' => $state, 'code' => $code);

        list($accessToken, $userId, $urlState) = $webAuth->finish($authCode);

        var_dump([$accessToken, $userId, Session::get('dbx-token'), Session::all()]);

        \Cache::put('dbx-access-token', $accessToken, 1000);

        return \Redirect::to('api/dropbox');

    }

    public function api(Request $request){
        if(!\Cache::has('dbx-access-token')){
            return redirect('connect/dropbox');
        }
        $accessToken = \Cache::get('dbx-access-token');
        $factory = \App::make('GrahamCampbell\Dropbox\DropboxFactory');
        $client = $factory->make(['token' => $accessToken, 'app' => config('dropbox.connections.main.app')]);
        var_dump($client->getMetadataWithChildren('/'));
    }
}