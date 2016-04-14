<?php
namespace Pulse\Services\Identity\Adapters;

use Google_Client;
use Google_Service_Plus;
use Dropbox\Client as DropboxClient;
use Pulse\Services\Identity\Account\Account;
use Pulse\Services\Identity\Adapters\Drive\DriveAdapter;
use Pulse\Services\Identity\Adapters\Dropbox;
use Pulse\Services\Identity\Adapters\Dropbox\DropboxAdapter;

class AdapterFactory implements AdapterFactoryInterface
{
    /**
     * Create IdentityAdapter
     * @param  string $adapter
     * @param  string $access_token
     * @return Pulse\Serives\Identity\Adapters\AdapterInterface
     */
    public static function create($adapter, $access_token)
    {
        switch ($adapter) {
            case 'dropbox':
            return self::createDropboxAdapter($access_token);
            break;
            case 'drive':
            return self::createDriveAdapter($access_token);
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
        $service = new Google_Service_Plus($client);
        $accountInfo = app('Pulse\Services\Identity\Account\AccountInterface');

        return new DriveAdapter($service, $accountInfo);
    }

    /**
     * Create Dropbox Adapter
     * @param  string $access_token
     * @return DropboxAdapter
     */
    public static function createDropboxAdapter($access_token)
    {
        $service = new DropboxClient($access_token, config('dropbox.app'));
        $accountInfo = app('Pulse\Services\Identity\Account\AccountInterface');

        return new DropboxAdapter($service, $accountInfo);
    }
}
