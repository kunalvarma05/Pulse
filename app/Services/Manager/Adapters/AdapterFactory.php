<?php
namespace Pulse\Services\Manager\Adapters;

use Google_Client;
use Google_Service_Drive;
use GuzzleHttp\Client as Guzzle;
use Dropbox\Client as DropboxClient;
use League\OAuth2\Client\Token\AccessToken;
use Kunnu\OneDrive\Client as OneDriveClient;
use Pulse\Services\Manager\Adapters\Drive\DriveAdapter;
use Pulse\Services\Manager\Adapters\Dropbox\DropboxAdapter;
use Pulse\Services\Manager\Adapters\OneDrive\OneDriveAdapter;

class AdapterFactory implements AdapterFactoryInterface
{
    /**
     * Create ManagerAdapter
     * @param  string $adapter
     * @param  string $access_token
     * @return Pulse\Serives\Manager\Adapters\AdapterInterface
     */
    public static function create($adapter, $access_token)
    {
        switch ($adapter) {
            case 'dropbox':
                return static::createDropboxAdapter($access_token);
                break;
            case 'drive':
                return static::createDriveAdapter($access_token);
                break;
            case 'onedrive':
                return static::createOneDriveAdapter($access_token);
                break;

            default:
                throw new \Exception("Please specify a valid adapter!");
                break;
        }
    }

    /**
     * Create Google Drive Adapter
     * @return DriveAdapter
     */
    protected static function createDriveAdapter($access_token)
    {
        $client = new Google_Client();
        $client->setAccessToken($access_token);
        $service = new Google_Service_Drive($client);
        $quotaInfo = app('Pulse\Services\Manager\Quota\QuotaInterface');

        return new DriveAdapter($service, $quotaInfo);
    }

    /**
     * Create Dropbox Adapter
     * @param  string $access_token
     * @return DropboxAdapter
     */
    protected static function createDropboxAdapter($access_token)
    {
        $service = new DropboxClient($access_token, config('dropbox.app'));
        $quotaInfo = app('Pulse\Services\Manager\Quota\QuotaInterface');

        return new DropboxAdapter($service, $quotaInfo);
    }

    /**
     * Create OneDrive Adapter
     * @param  string $access_token
     * @return OneDriveAdapter
     */
    protected static function createOneDriveAdapter($access_token)
    {
        $guzzle = new Guzzle;
        $access_token = new AccessToken(json_decode($access_token, true));
        $service = new OneDriveClient($access_token, $guzzle);
        $quotaInfo = app('Pulse\Services\Manager\Quota\QuotaInterface');

        return new OneDriveAdapter($service, $quotaInfo);
    }
}
