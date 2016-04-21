<?php
namespace Pulse\Services\Manager;

interface ManagerInterface
{
    /**
     * Get the Quota of the Cloud Account
     * @param  array  $data Additional Data
     * @return Pulse\Services\Manager\Quota\QuotaInterface
     */
    public function getQuota(array $data = array());

    /**
     * Get File
     * @param  string $file   File
     * @param  array  $data   Additional Data
     * @return Pulse\Service\Manager\File\FileInterface
     */
    public function getFileInfo($file, array $data = array());

    /**
     * List Children of a given folder path or id
     * @param  string $path Folder path or ID
     * @param  array  $data Additional Data
     * @return Pulse\Services\Manager\File\FileInterface
     */
    public function listChildren($path = null, array $data = array());

    /**
     * Copy File
     * @param  string $file          File to copy
     * @param  string|null $location Location to copy the file to
     * @param  array       $data     Additional Data
     * @return Pulse\Services\Manager\File\FileInterface
     */
    public function copy($file, $location = null, array $data = array());

    /**
     * Move File
     * @param  string $file          File to move
     * @param  string $location      Location to move the file to
     * @param  array       $data     Additional Data
     * @return Pulse\Services\Manager\File\FileInterface
     */
    public function move($file, $location, array $data = array());

    /**
     * Delete File
     * @param  string $file          File to delete
     * @param  array       $data     Additional Data
     * @return array
     */
    public function delete($file, array $data = array());

    /**
     * Create Folder
     * @param  string $name     Folder Name
     * @param  string $location Folder Location
     * @param  array  $data     Additional Data
     * @return Pulse\Services\Manager\File\FileInterface
     */
    public function createFolder($name, $location = null, array $data = array());

    /**
     * Get Download Link
     * @param  string $file File
     * @param  array  $data Additional Data
     * @return string       Download Link
     */
    public function getDownloadLink($file, array $data = array());

    /**
     * Upload File
     * @param  string $file     File path
     * @param  string          $location Location to upload the file to
     * @param  string          $title    Title of the file
     * @param  array           $data     Additional Data
     * @return Pulse\Services\Manager\File\FileInterface
     */
    public function uploadFile($file, $location = null, $title = null, array $data = array());

    /**
     * Rename File/Folder
     * @param  string $file  File Path
     * @param  string $title New Name
     * @param  array  $data  Additional Data
     * @return Pulse\Services\Manager\File\FileInterface
     */
    public function rename($file, $title, array $data = array());

}