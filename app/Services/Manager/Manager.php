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
     * Get File
     * @param  string $file   File
     * @param  array  $data   Additional Data
     * @return Pulse\Service\Manager\File\FileInterface
     */
    public function getFileInfo($file, array $data = array())
    {
        return $this
        ->getAdapter()
        ->getFileInfo($file, $data);
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

    /**
     * Delete File
     * @param  string $file          File to delete
     * @param  array       $data     Additional Data
     * @return array
     */
    public function delete($file, array $data = array())
    {
        return $this
        ->getAdapter()
        ->delete($file, $data);
    }

    /**
     * Create Folder
     * @param  string $name     Folder Name
     * @param  string $location Folder Location
     * @param  array  $data     Additional Data
     * @return Pulse\Services\Manager\File\FileInterface
     */
    public function createFolder($name, $location = null, array $data = array())
    {
        return $this
        ->getAdapter()
        ->createFolder($name, $location, $data);
    }

    /**
     * Get Download Link
     * @param  string $file File
     * @param  array  $data Additional Data
     * @return string       Download Link
     */
    public function getDownloadLink($file, array $data = array())
    {
        return $this
        ->getAdapter()
        ->getDownloadLink($file, $data);
    }

    /**
     * Upload File
     * @param  string $file     File path
     * @param  string          $location Location to upload the file to
     * @param  string          $title    Title of the file
     * @param  array           $data     Additional Data
     * @return Pulse\Services\Manager\File\FileInterface
     */
    public function uploadFile($file, $location = null, $title = null, array $data = array())
    {
        return $this
        ->getAdapter()
        ->uploadFile($file, $location, $title, $data);
    }

    /**
     * Rename File/Folder
     * @param  string $file  File Path
     * @param  string $title New Name
     * @param  array  $data  Additional Data
     * @return Pulse\Services\Manager\File\FileInterface
     */
    public function rename($file, $title, array $data = array())
    {
        return $this
        ->getAdapter()
        ->rename($file, $title, $data);
    }

}