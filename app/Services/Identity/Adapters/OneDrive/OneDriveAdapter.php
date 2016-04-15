<?php
namespace Pulse\Services\Identity\Adapters\OneDrive;

use Exception;
use League\OAuth2\Client\Token\AccessToken;
use Stevenmaguire\OAuth2\Client\Provider\Microsoft;
use Pulse\Services\Identity\IdentityServiceException;
use Pulse\Services\Identity\Account\AccountInterface;
use Pulse\Services\Identity\Adapters\AdapterInterface;

class OneDriveAdapter implements AdapterInterface
{

    /**
     * OneDrive Client
     * @var Microsoft
     */
    private $service;

    /**
     * Access Token
     * @var string
     */
    private $access_token;

    /**
     * Account Info
     * @var Pulse\Services\Identity\Account\AccountInterface;
     */
    private $accountInfo;

    /**
     * Constructor
     * @param OneDriveClient $service
     */
    public function __construct(Microsoft $service, $access_token, AccountInterface $accountInfo)
    {
        $this->service = $service;
        $this->access_token = $access_token;
        $this->accountInfo = $accountInfo;
    }

    /**
     * Get Service
     * @return OneDriveClient
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Get Access Token
     * @return AccessToken
     */
    public function getAccessToken()
    {
        $access_token = new AccessToken(json_decode($this->access_token, true));
        return $access_token;
    }

    /**
     * Get Account
     * @return Pulse\Serivces\Identity\Account\AccountInterface
     */
    public function getAccount()
    {
        try {
            $access_token = $this->getAccessToken();
            $account = $this->getService()->getResourceOwner($access_token);
            $account->setImageurl("https://apis.live.net/v5.0/{$account->getId()}/picture?type=large");

            $this->makeAccountInfo($account);

            return $this->accountInfo;
        } catch (Exception $e) {
            // @todo Better Exception Handling
            throw new IdentityServiceException($e->getMessage());
        }
    }

    /**
     * Make the Account Info Object
     * @param  MicrosoftResourceOwner $account
     */
    protected function makeAccountInfo($account)
    {
        $this->accountInfo->setId($account->getId());
        $this->accountInfo->setEmail($account->getEmail());
        $this->accountInfo->setImage($account->getImageurl());
        $this->accountInfo->setName($account->getName());
    }

}