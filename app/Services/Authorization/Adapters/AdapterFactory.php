<?php
namespace Pulse\Services\Authorization\Adapters;

use \Google_Client;
use Dropbox\AppInfo;
use Dropbox\WebAuth;
use GuzzleHttp\Client as Guzzle;
use Stevenmaguire\OAuth2\Client\Provider\Microsoft;
use Pulse\Services\Authorization\Adapters\Drive\AuthAdapter as DriveAdapter;
use Pulse\Services\Authorization\Adapters\Dropbox\AuthAdapter as DropboxAdapter;
use Pulse\Services\Authorization\Adapters\OneDrive\AuthAdapter as OneDriveAdapter;

class AdapterFactory implements AdapterFactoryInterface
{

    /**
     * Create Adapter
     * @param  string $adapter
     * @return AdapterInterface
     */
    public static function create($adapter)
    {
        switch ($adapter) {
            case 'dropbox':
                return self::createDropboxAdapter();
                break;

            case 'drive':
                return self::createDriveAdapter();
                break;

            case 'onedrive':
                return self::createOneDriveAdapter();
                break;

            default:
                throw new \Exception("Please specify a valid Adapter");
                break;
        }
    }

    /**
     * Create Dropbox Adapter
     * @return AuthAdapter
     */
    protected static function createDropboxAdapter()
    {
        $appInfo = AppInfo::loadFromJson([
                    "key" => config("dropbox.app"),
                    "secret" => config("dropbox.secret")
                ]);

        $sessionStore = app('Pulse\Services\Authorization\Adapters\Dropbox\DropboxCsrfTokenStoreInterface');

        $webAuth = new WebAuth($appInfo, "pulseapp", config("dropbox.callback_url"), $sessionStore, "en");

        return new DropboxAdapter($webAuth);
    }

    /**
     * Create Google Drive Adapter
     * @return AuthAdapter
     */
    protected static function createDriveAdapter()
    {
        $client = new Google_Client();
        $client->setClientId(config("drive.client_id"));
        $client->setClientSecret(config("drive.client_secret"));
        $client->setRedirectUri(config("drive.callback_url"));
        $client->setAccessType('offline');
        $client->setScopes(config("drive.scopes"));

        $sessionStore = app('Illuminate\Session\Store');

        return new DriveAdapter($client, $sessionStore);
    }

    /**
     * Create OneDrive Adapter
     * @return AuthAdapter
     */
    protected static function createOneDriveAdapter()
    {
        $provider = new Microsoft([
            'clientId'          => config('onedrive.client_id'),
            'clientSecret'      => config('onedrive.client_secret'),
            'redirectUri'       => config('onedrive.callback_url')
            ]);

        $sessionStore = app('Illuminate\Session\Store');

        return new OneDriveAdapter($provider, $sessionStore);
    }
}
