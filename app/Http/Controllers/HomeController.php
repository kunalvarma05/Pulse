<?php

namespace App\Http\Controllers;

use \Session;
use Illuminate\Http\Request;

use \App\Http\Requests;
use \App\Http\Controllers\Controller;
use \Dropbox;

class HomeController extends Controller
{

    public function connect(){
        $sessionStore = new MySession(\App::make('Illuminate\Session\Store'), "Dropbox-oauth-key");
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
        //return view('welcome');
    }

    public function auth(Request $request){
        $sessionStore = new MySession(\App::make('Illuminate\Session\Store'), "Dropbox-oauth-key");

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
    }

}
