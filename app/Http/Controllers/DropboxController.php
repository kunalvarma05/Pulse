<?php

namespace Pulse\Http\Controllers;

use Session;
use Redirect;
use \Pulse\Http\Requests;
use Illuminate\Http\Request;
use \Pulse\Http\Controllers\Controller;
//Dropbox
use \Dropbox\AppInfo;
use \Dropbox\WebAuth;
use \Dropbox\Exception;
use \Dropbox\AppInfoLoadException;
use \Dropbox\WebAuthException_Csrf;
use \Dropbox\Client as DropboxClient;
use \Dropbox\WebAuthException_BadState;
use \Dropbox\WebAuthException_Provider;
use \Dropbox\WebAuthException_BadRequest;
use \Dropbox\WebAuthException_NotApproved;

class DropboxController extends Controller
{

    private $user;
    private $webAuth;
    private $sessionStore;

    public function __construct(){
        //Auth User
        $this->user = (!\Auth::check()) ? \Auth::login(\Pulse\Models\User::first()) : \Auth::user();
        //Dropbox Session Store
        $this->sessionStore = app('Pulse\Services\Authorization\Adapters\Dropbox\DropboxCsrfTokenStoreInterface');
        //Web Auth
        $this->webAuth = new WebAuth($this->getAppInfo(), "pulseapp", config("dropbox.callback_url"), $this->sessionStore, "en");
    }

    public function connect(){
        if(Session::has('dbx-access-token')){
            return redirect('api/dropbox');
        }
        return redirect(urldecode($this->webAuth->start()));
    }

    public function auth(Request $request){
        $input = $request->all();
        //return $input;
        $code = $input['code'];
        $state = $input['state'];

        $accessToken = $this->getAccessToken($code, $state);

        Session::put('dbx-access-token', $accessToken, 1000);
        return Redirect::to('devapi/dropbox');

    }

    public function api(Request $request){
        if(!Session::has('dbx-access-token')){
            return redirect('connect/dropbox');
        }

        $accessToken = \Session::get('dbx-access-token');
        $client = new DropboxClient($accessToken, config('dropbox.app'));
        try{
            $file = "/pulse-logo.png";
            $name = pathinfo($file, PATHINFO_FILENAME); //pulse-logo
            $ext = pathinfo($file, PATHINFO_EXTENSION); //.png
            if($ext) {
                dd($name, $ext);
            }
            dd($name);
            $random = str_random(6);
            $copy = "({$random}) Copy of {$name}.{$ext}";

            $dir = pathinfo($file, PATHINFO_DIRNAME); // /

            $location = "{$dir}/{$copy}";
            dd($client->copy($file, $location));
        } catch(\Exception $e) {
            return $e->getMessage();
            //return "Unable to copy file. Maybe a file with same name already exists!";
        }
        //dd($client->getMetadataWithChildren("/"));
        //dd($client->getAccountInfo());
        //dd(base64_encode($d));
        //var_dump($client->getThumbnail("/pulse-logo.png", 'png', 'm'));
        //list($md, $d) = $client->getThumbnail('/pulse-logo.png', 'png', 's');
        //echo "<img src='data:image/png;base64,".base64_encode($d)."'>";
    }

    protected function getAppInfo(){
        try {
            $appInfo = AppInfo::loadFromJson(
                array(
                    "key" => config("dropbox.app"),
                    "secret" => config("dropbox.secret")
                    )
                );

            return $appInfo;
        }
        catch (AppInfoLoadException $ex) {
            fwrite(STDERR, "Error loading <app-info-file>: ".$ex->getMessage()."\n");
            die;
        }
    }

    protected function getAccessToken($code, $state){
        try{
            $authCode = array('state' => $state, 'code' => $code);
            list($accessToken, $userId, $urlState) = $this->webAuth->finish($authCode);
            return $accessToken;
        }
        catch (WebAuthException_BadRequest $ex) {
            abort(404, "Something went wrong!");
        }
        catch (WebAuthException_BadState $ex) {
            // Auth session expired.  Restart the auth process.
            redirect("connect/dropbox");
        }
        catch (WebAuthException_Csrf $ex) {
            abort(403, "Invalid Request!");
        }
        catch (WebAuthException_NotApproved $ex) {
            abort(200, "Ya, right. Thanks for not approving!");
        }
        catch (WebAuthException_Provider $ex) {
            abort(500, $ex->getMessage());
        }
        catch (Exception $ex) {
            abort(500, $ex->getMessage());
        }
    }
}