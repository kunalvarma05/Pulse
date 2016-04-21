<?php
namespace Pulse\Services\Identity\Adapters\Drive;

use Google_Client;
use Google_Exception;
use Google_Service_Plus;
use Google_Service_Plus_Person;
use Pulse\Services\Identity\Account\AccountInterface;
use Pulse\Services\Identity\Adapters\AdapterInterface;

class DriveAdapter implements AdapterInterface
{

    /**
     * Google Plus Service
     * @var Google_Service_Plus
     */
    private $service;

    /**
     * Account Info
     * @var Pulse\Services\Identity\Account\AccountInterface;
     */
    private $accountInfo;

    /**
     * Constructor
     * @param Google_Service_Plus $service
     */
    public function __construct(Google_Service_Plus $service, AccountInterface $accountInfo)
    {
        $this->service = $service;
        $this->accountInfo = $accountInfo;
    }

    /**
     * Get Service
     * @return Google_Service_Plus
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Get Account
     * @return Pulse\Serivces\Identity\Account\AccountInterface
     */
    public function getAccount()
    {
        try {
            $account = $this->getService()->people->get("me");

            $this->makeAccountInfo($account);

            return $this->accountInfo;
        } catch (Google_Exception $e) {
            // @todo Better Google_Exception Handling
            return false;
        }

        return false;
    }

    /**
     * Make the Account Info Object
     * @param  Google_Service_Plus_Person $account
     */
    protected function makeAccountInfo(Google_Service_Plus_Person $account)
    {
        $this->accountInfo->setId($account->getId());
        $this->accountInfo->setName($account->getDisplayName());
        $this->accountInfo->setImage($account->getImage()->getUrl());
        $this->accountInfo->setEmail($account->getEmails()[0]->getValue());
    }
}
