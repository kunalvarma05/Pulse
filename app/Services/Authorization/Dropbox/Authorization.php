<?php
namespace Pulse\Services\Authorization\Dropbox;

use Dropbox\AppInfo;
use Dropbox\WebAuth;
use Dropbox\Exception;
use Dropbox\AppInfoLoadException;
use Dropbox\WebAuthException_Csrf;
use Dropbox\WebAuthException_BadState;
use Dropbox\WebAuthException_Provider;
use Dropbox\WebAuthException_BadRequest;
use Dropbox\WebAuthException_NotApproved;
use Pulse\Services\Authorization\AuthorizationInterface;

class Authorization implements AuthorizationInterface
{
    private $webAuth;
    private $sessionStore;

    public function __construct()
    {
        //Dropbox Session Store
        $this->sessionStore = app('Pulse\Services\Authorization\Dropbox\DropboxCsrfTokenStore');
        //Web Auth
        $this->webAuth = new WebAuth($this->getAppInfo(), "pulseapp", config("dropbox.callback_url"), $this->sessionStore, "en");
    }

    /**
     * Get Authorization URL
     *
     * @param  array $data Additional Data
     * @return string
     */
    public function getAuthorizationUrl(array $data = array())
    {
        return $this->webAuth->start();
    }

    /**
     * Get Access Token
     * @param  string $code  Authorization Code
     * @param  string $state CSRF State
     * @param  array  $data  Additional Data
     * @return string        Access Token
     */
    public function getAccessToken($code, $state, array $data = array())
    {
        try{
            $authCode = array('state' => $state, 'code' => $code);
            list($accessToken, $userId, $urlState) = $this->webAuth->finish($authCode);
            return $accessToken;
        }
        catch (WebAuthException_BadRequest $ex) {
            // @todo Log and handle gracefully.
            return false;
        }
        catch (WebAuthException_BadState $ex) {
            // @todo Log and handle gracefully.
            return false;
        }
        catch (WebAuthException_Csrf $ex) {
            // @todo Log and handle gracefully.
            return false;
        }
        catch (WebAuthException_NotApproved $ex) {
            // @todo Log and handle gracefully.
            return false;
        }
        catch (WebAuthException_Provider $ex) {
            // @todo Log and handle gracefully.
            return false;
        }
        catch (Exception $ex) {
            // @todo Log and handle gracefully.
            return false;
        }
    }

    /**
     * Get Dropbox AppInfo
     * @return Dropbox\AppInfo
     */
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
}