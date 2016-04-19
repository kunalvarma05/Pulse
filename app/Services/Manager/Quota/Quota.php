<?php
namespace Pulse\Services\Manager\Quota;

use Pulse\Utils\Helpers;

class Quota implements QuotaInterface
{
    /**
     * Total Space Alloted in bytes
     * @var int
     */
    protected $spaceAlloted;

    /**
     * Total Space Used in bytes
     * @var int
     */
    protected $spaceUsed;

    /**
     * Total Space Remaining in bytes
     * @var int
     */
    protected $spaceRemaining;

    /**
     * Get Allocated Space
     * @return int
     */
    public function getSpaceAlloted($format = false)
    {
        if($format)
            return $this->formatBytes($this->spaceAlloted);
        else
            return $this->spaceAlloted;
    }

    /**
     * Get Used Space
     * @return int
     */
    public function getSpaceUsed($format = false)
    {
        if($format)
            return $this->formatBytes($this->spaceUsed);
        else
            return $this->spaceUsed;
    }

    /**
     * Get Remaining Space
     * @return int
     */
    public function getSpaceRemaining($format = false)
    {
        if($format)
            return $this->formatBytes($this->spaceRemaining);
        else
            return $this->spaceRemaining;
    }

    /**
     * Set Allocated Space
     */
    public function setSpaceAlloted($size)
    {
        $this->spaceAlloted = $size;
    }

    /**
     * Set Used Space
     */
    public function setSpaceUsed($size)
    {
        $this->spaceUsed = $size;
    }

    /**
     * Set Remaining Space
     */
    public function setSpaceRemaining($size)
    {
        $this->spaceRemaining = $size;
    }

    /**
     * Format Bytes to human readable size
     * @param  int  $bytes     Filesize in Bytes
     * @param  integer $precision Return value precision
     * @return string             Human readable filesize
     */
    protected function formatBytes($bytes, $precision = 2)
    {
        return Helpers::formatBytes($bytes, $precision);
    }
}