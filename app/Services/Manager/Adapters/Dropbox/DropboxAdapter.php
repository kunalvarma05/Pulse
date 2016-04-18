<?php
namespace Pulse\Services\Manager\Adapters\Dropbox;

use Exception;
use Dropbox\Client as DropboxClient;
use Pulse\Services\Manager\Quota\QuotaInterface;
use Pulse\Services\Manager\Adapters\AdapterInterface;

class DropboxAdapter implements AdapterInterface
{

    /**
     * Dropbox Client
     * @var Dropbox\Client
     */
    private $service;

    /**
     * Quota Info
     * @var Pulse\Services\Manager\Quota\QuotaInterface;
     */
    private $quotaInfo;

    /**
     * Constructor
     * @param DropboxClient $service
     */
    public function __construct(DropboxClient $service, QuotaInterface $quotaInfo)
    {
        $this->service = $service;
        $this->quotaInfo = $quotaInfo;
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
     * Get the Quota of the Cloud Account
     * @param  array  $data Additional Data
     * @return Pulse\Services\Manager\Quota\QuotaInterface
     */
    public function getQuota(array $data = array())
    {
        $account = $this->getService()->getAccountInfo();

        if($account) {
            $this->makeQuotaInfo($account);

            return $this->quotaInfo;
        }
    }

    /**
     * Make Quota Info
     * @param  array $account
     */
    protected function makeQuotaInfo(array $account)
    {
        $this->quotaInfo->setSpaceAlloted($account['quota_info']['quota']);
        $this->quotaInfo->setSpaceUsed($account['quota_info']['normal']);

        $remaining = $account['quota_info']['quota'] - $account['quota_info']['normal'];
        $this->quotaInfo->setSpaceRemaining($remaining);
    }

}