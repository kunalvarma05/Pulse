<?php
namespace Pulse\Http\Controllers;

use Illuminate\Http\Request;
use Pulse\Http\Requests;

use \Google_Client;
use \Google_Service_Drive;
use \Auth;
use \Session;

class DriveController extends Controller
{
    private $client;

    private $user;

    public function __construct(){

        $this->user = (!\Auth::check()) ? \Auth::login(\Pulse\Models\User::first()) : \Auth::user();

        $client_id = config("drive.client_id");
        $client_secret = config("drive.client_secret");
        $callback_url = config("drive.callback_url");

        $this->client = new Google_Client();
        $this->client->setClientId($client_id);
        $this->client->setClientSecret($client_secret);
        $this->client->setRedirectUri($callback_url);
        $this->client->setAccessType('offline');
        $this->client->setScopes(Google_Service_Drive::DRIVE);
    }

    public function connect(){
        if(Session::has('google-access-token')){
            return redirect('api/drive');
        }

        $authUrl = $this->client->createAuthUrl();
        return redirect($authUrl);

    }

    public function auth(Request $request){
        return $request->all();
        if($request->has('code')){
            $code = $request->input('code');
            $this->client->authenticate($code);
            $access_token = $this->client->getAccessToken();
            Session::set('google-access-token', $access_token);

            return redirect('api/drive');
        }
    }

    public function api(){
        $client = $this->getClient();

        if(!$client){
            return redirect('/connect/drive');
        }

        $service = new Google_Service_Drive($client);

        $optParams = array();
        dd($service->about->get());

        $results = $service->files->get("0BxCFmDp5O-sjN1E5OHpwU2RjZDg");
        dd($results);
    }

    protected function getClient(){
        if(Session::has('google-access-token')){
            $this->client->setAccessToken(Session::get('google-access-token'));

            return $this->client;
        }

        return false;
    }
}
