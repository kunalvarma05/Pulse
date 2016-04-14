<?php
namespace Pulse\Services\Identity\Adapters\Dropbox;

use Exception;
use Dropbox\Client as DropboxClient;
use Pulse\Services\Identity\Account\AccountInterface;
use Pulse\Services\Identity\Adapters\AdapterInterface;

class DropboxAdapter implements AdapterInterface
{

    /**
     * Dropbox Client
     * @var Dropbox\Client
     */
    private $service;

    /**
     * Account Info
     * @var Pulse\Services\Identity\Account\AccountInterface;
     */
    private $accountInfo;

    /**
     * Constructor
     * @param DropboxClient $service
     */
    public function __construct(DropboxClient $service, AccountInterface $accountInfo)
    {
        $this->service = $service;
        $this->accountInfo = $accountInfo;
    }

    /**
     * Get Service
     * @return DropboxClient
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Get Account
     * @param  string $account_id Account ID
     * @return Pulse\Serivces\Identity\Account\AccountInterface
     */
    public function getAccount($account_id = null)
    {
        if(is_null($account_id)) {
            $account_id = "me";
        }

        try {
            $account = $this->getService()->getAccountInfo();

            $this->makeAccountInfo($account);

            return $this->accountInfo;
        } catch (Exception $e) {
            // @todo Better Exception Handling
            return false;
        }

        return false;
    }

    /**
     * Make the Account Info Object
     * @param  array $account
     */
    protected function makeAccountInfo(array $account)
    {
        $this->accountInfo->setId($account['uid']);
        $this->accountInfo->setName($account['display_name']);
        $this->accountInfo->setImage("");
        $this->accountInfo->setEmail($account['email']);
    }

}