<?php
namespace Pulse\Services\Manager\Adapters\OneDrive;

use Exception;
use League\OAuth2\Client\Token\AccessToken;
use Kunnu\OneDrive\Client as OneDriveClient;
use Pulse\Services\Manager\Quota\QuotaInterface;
use Pulse\Services\Manager\Adapters\AdapterInterface;

class OneDriveAdapter implements AdapterInterface
{

    /**
     * OneDrive Client
     * @var OneDriveClient
     */
    private $service;

    /**
     * Quota Info
     * @var Pulse\Services\Manager\Quota\QuotaInterface;
     */
    private $quotaInfo;

    /**
     * Constructor
     * @param OneDriveClient $service
     * @param QuotaInterface $quota
     */
    public function __construct(OneDriveClient $service, QuotaInterface $quotaInfo)
    {
        $this->service = $service;
        $this->quotaInfo = $quotaInfo;
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
     * Get the Quota of the Cloud Quota
     * @param  array  $data Additional Data
     * @return Pulse\Services\Manager\Quota\QuotaInterface
     */
    public function getQuota(array $data = array())
    {
        $drive_id = (isset($data['drive_id']) && !is_null($data['drive_id'])) ? $data['drive_id'] : null;

        $drive = $this->getService()->getDrive($drive_id);

        if($drive)
        {
            $this->makeQuotaInfo($drive);

            return $this->quotaInfo;
        }

        return false;
    }

    /**
     * Make Quota Info
     * @param  array $drive
     */
    protected function makeQuotaInfo($drive)
    {
        $this->quotaInfo->setSpaceAlloted($drive->quota->total);
        $this->quotaInfo->setSpaceUsed($drive->quota->used);

        $remaining = $drive->quota->remaining;
        $this->quotaInfo->setSpaceRemaining($remaining);
    }
}