<?php
namespace Pulse\Services\Manager\Adapters;

interface AdapterInterface
{
    /**
     * Get the Quota of the Cloud Account
     * @param  array  $data Additional Data
     * @return Pulse\Services\Manager\Quota\QuotaInterface
     */
    public function getQuota(array $data = array());
}