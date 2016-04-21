<?php
namespace Pulse\Services\Manager\Quota;

interface QuotaInterface
{

    /**
     * Get Allocated Space
     * @return int
     */
    public function getSpaceAlloted($format = false);

    /**
     * Get Used Space
     * @return int
     */
    public function getSpaceUsed($format = false);

    /**
     * Get Remaining Space
     * @return int
     */
    public function getSpaceRemaining($format = false);

    /**
     * Set Allocated Space
     */
    public function setSpaceAlloted($size);

    /**
     * Set Used Space
     */
    public function setSpaceUsed($size);

    /**
     * Set Remaining Space
     */
    public function setSpaceRemaining($size);
}
