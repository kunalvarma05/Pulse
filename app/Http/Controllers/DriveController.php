<?php
namespace Pulse\Http\Controllers;

use Illuminate\Http\Request;
use Pulse\Http\Requests;

use \Google_Client;
use \Google_Service_Plus;
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
        $this->client->setScopes([Google_Service_Drive::DRIVE, 'profile', 'email']);
    }

    public function connect(){
        if($this->getClient()){
            return redirect('devapi/drive');
        }

        $authUrl = $this->client->createAuthUrl();
        return redirect($authUrl);

    }

    public function auth(Request $request){
        //return $request->all();
        if($request->has('code')){
            $code = $request->input('code');
            $this->client->authenticate($code);
            $access_token = $this->client->getAccessToken();
            $this->client->setAccessToken($access_token);
            Session::set('google-access-token', $access_token);

            return redirect('devapi/drive');
        }
    }

    public function api(){
        $client = $this->getClient();

        if(!$client){
            return redirect('/connect/drive');
        }

        // $googlePlus = new Google_Service_Plus($client);
        // $user = $googlePlus->people->get("me");
        // dd($user);

        // dd([$client->getAccessToken(), $client->verifyIdToken()->getAttributes()]);

        $service = new Google_Service_Drive($client);
        //dd($service->about->get());

        $results = $this->listChildren($service)->getItems();
        $file = $results[5];

        foreach ($results as $key => $value) {
            echo "Title:" . $value['title'] . " <br>id: " . $value['id'] . "<hr>";
        }
    }

    protected function getClient(){
        if(Session::has('google-access-token')){
            $this->client->setAccessToken(Session::get('google-access-token'));

            if($this->client->isAccessTokenExpired()) {
                return false;
            }

            return $this->client;
        }

        return false;
    }

    public function listChildren($service, $location = null, $optParams = array())
    {
        if(is_null($location))
        {
            $location = $service->about->get()->getRootFolderId();
        }
        $maxResults = isset($optParams['maxResults']) ? $optParams['maxResults'] : 18;
        $orderBy = "folder asc,title asc";
        $trashed = isset($optParams['trashed']) ? 'true' : 'false';
        $owners = (isset($optParams['owners']) && !empty($optParams['owners'])) ? $optParams['owners'] : [];
        $locationSearch = "'{$location}' in parents";
        $ownerSearch = [];

        foreach ($owners as $owner) {
            $ownerSearch[] = "'{$owner}' in owners";
        }

        $ownerSearch = implode(" or ", $ownerSearch);
        $searchQuery = "{$locationSearch} and trashed = {$trashed}";

        if($ownerSearch) {
            $searchQuery .= " and " . $ownerSearch;
        }

        $params = array('maxResults' => $maxResults, 'orderBy' => $orderBy, 'q' => $searchQuery);
        $newParams = array_merge($optParams, $params);

        return $service->files->listFiles($newParams);
    }
}
