<?php
namespace Pulse\Services\Manager\Adapters\Drive;

use Google_Client;
use Google_Exception;
use Google_Service_Drive;
use Google_Service_Drive_About;
use Pulse\Services\Manager\Quota\QuotaInterface;
use Pulse\Services\Manager\Adapters\AdapterInterface;

class DriveAdapter implements AdapterInterface
{

    /**
     * Google Plus Service
     * @var Google_Service_Drive
     */
    private $service;

    /**
     * Quota Info
     * @var Pulse\Services\Manager\Quota\QuotaInterface;
     */
    private $quotaInfo;

    /**
     * Constructor
     * @param Google_Service_Drive $service
     */
    public function __construct(Google_Service_Drive $service, QuotaInterface $quotaInfo)
    {
        $this->service = $service;
        $this->quotaInfo = $quotaInfo;
    }

    /**
     * Get Service
     * @return Google_Service_Drive
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Get the Quota of the Cloud Quota
     * @param  array  $data Additional Data
     * @return Pulse\Services\Manager\Quota\QuotaInterface
     */
    public function getQuota(array $data = array())
    {
        $about = $this->getService()->about->get();

        if($about)
        {
            $this->makeQuotaInfo($about);

            return $this->quotaInfo;
        }

        return false;
    }

    /**
     * Make Quota Info
     * @param  Google_Service_Drive_About $about
     */
    protected function makeQuotaInfo(Google_Service_Drive_About $about)
    {
        $this->quotaInfo->setSpaceAlloted($about->getQuotaBytesTotal());
        $this->quotaInfo->setSpaceUsed($about->getQuotaBytesUsed());

        $remaining = $about->getQuotaBytesTotal() - $about->getQuotaBytesUsed();
        $this->quotaInfo->setSpaceRemaining($remaining);
    }
}