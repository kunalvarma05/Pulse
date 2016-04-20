<?php
namespace Pulse\Services\Manager;

use Pulse\Services\Manager\Adapters\AdapterInterface;

class Manager implements ManagerInterface
{
    /**
     * Manager Adapter
     * @var Pulse\Services\Manager\Adapters\AdapterInterface;
     */
    private $adapter;

    /**
     * Constructor
     * @param AdapterInterface $adapter Manager Adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Get Manager Adapter
     * @return Pulse\Services\Manager\Adapters\AdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * Set Manager Adapter
     * @return Pulse\Services\Manager\Adapters\AdapterInterface
     */
    public function setAdapter(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Get the Quota of the Cloud Account
     * @param  array  $data Additional Data
     * @return Pulse\Services\Manager\Quota\QuotaInterface
     */
    public function getQuota(array $data = array())
    {
        return $this
        ->getAdapter()
        ->getQuota($data);
    }

    /**
     * List Children of a given folder path or id
     * @param  string $path Folder path or ID
     * @param  array  $data Additional Data
     * @return Array (Pulse\Services\Manager\File\FileInterface)
     */
    public function listChildren($path = null, array $data = array())
    {
        return $this
        ->getAdapter()
        ->listChildren($path, $data);
    }

    /**
     * Copy File
     * @param  string $file          File to copy
     * @param  string|null $location Location to copy the file to
     * @param  array       $data     Additional Data
     * @return Pulse\Services\Manager\File\FileInterface
     */
    public function copy($file, $location = null, array $data = array())
    {
        return $this
        ->getAdapter()
        ->copy($file, $location, $data);
    }

    /**
     * Move File
     * @param  string $file          File to move
     * @param  string $location      Location to move the file to
     * @param  array       $data     Additional Data
     * @return Pulse\Services\Manager\File\FileInterface
     */
    public function move($file, $location, array $data = array())
    {
        return $this
        ->getAdapter()
        ->move($file, $location, $data);
    }


}