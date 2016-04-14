<?php
namespace Pulse\Services\Identity\Adapters;

use Google_Client;
use Google_Service_Plus;
use Pulse\Services\Identity\Account\Account;
use Pulse\Services\Identity\Adapters\Drive\DriveAdapter;

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
}
