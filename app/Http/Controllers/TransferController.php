<?php

namespace Pulse\Http\Controllers;

use Session;
use Pulse\Http\Requests;
use Illuminate\Http\Request;
use GuzzleHttp\Client as Guzzle;
use \Dropbox\Client as DropboxClient;
use Kunnu\OneDrive\Client as OneDriveClient;
use \Google_Client;
use \Google_Service_Drive;

class TransferController extends Controller
{
    public function index()
    {
        //Clients
        $dropboxClient = $this->getDropboxClient();
        $oneDriveClient = $this->getOneDriveClient();
        $driveClient = $this->getDriveClient();
        $service = new Google_Service_Drive($driveClient);
        //Get the download link of pulse-logo.png
        $link = $dropboxClient->createTemporaryDirectLink("/pulse-logo.png");

        //Download the pulse-logo.png file's content
        $stream = fopen('php://temp', 'w+');
        $fileToTransfer = $dropboxClient->getFile("/pulse-logo.png", $stream);
        rewind($stream);
        $contents = stream_get_contents($stream);

        $encrypted =

        $name = basename($fileToTransfer['path']);
        $file = $oneDriveClient->uploadFromUrl($link[0], $name);
        $file = new \Google_Service_Drive_DriveFile();
        $file->setTitle($name);
        $result = $service->files->insert($file, array(
          'data' => $contents,
          'mimeType' => 'application/octet-stream',
          'uploadType' => 'multipart'
          ));
        dd($result);
    }

    protected function getDropboxClient()
    {
        if(!Session::has('dbx-access-token'))
        {
            return redirect('connect/dropbox');
        }

        $accessToken = Session::get('dbx-access-token');
        return new DropboxClient($accessToken, config('dropbox.app'));
    }

    protected function getOneDriveClient()
    {
        //Session doesn't have an access token
        if(!Session::has('onedrive-access-token'))
        {
            return redirect('connect/onedrive');
        }

        //Fetch the access token from the session
        $access_token = Session::get('onedrive-access-token');

        //If the access token has expired, refresh it
        if ($access_token->hasExpired()) {
            $access_token = $this->provider->getAccessToken('refresh_token', [
                'refresh_token' => $access_token->getRefreshToken()
                ]);
        }

        return new OneDriveClient($access_token->getToken(), new Guzzle);
    }

    protected function getDriveClient()
    {
        $client_id = config("drive.client_id");
        $client_secret = config("drive.client_secret");
        $callback_url = config("drive.callback_url");

        $client = new Google_Client();
        $client->setClientId($client_id);
        $client->setClientSecret($client_secret);

        if(Session::has('google-access-token')){
            $client->setAccessToken(Session::get('google-access-token'));

            return $client;
        }
    }
}
