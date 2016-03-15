<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use \Google_Client;
use \Google_Service_Drive;
use \Auth;
use App\User;
use \Session;

class DriveController extends Controller
{
    private $client;

    private $user;

    public function __construct(){

        if(!Auth::check()){
            Auth::login(User::first());
        }

        $this->user = Auth::user();

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

        $results = $service->files->listFiles($optParams);
        if (count($results->getItems()) == 0) {
            print "No files found.<br>";
        } else {
            dd($results->getItems());
        }

    }

    protected function getClient(){
        if(Session::has('google-access-token')){
            $this->client->setAccessToken(Session::get('google-access-token'));

            return $this->client;
        }

        return false;
    }
}
