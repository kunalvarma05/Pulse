<?php
namespace Pulse\Services\Manager\Adapters\Dropbox;

use Exception;
use Dropbox\WriteMode;
use Pulse\Utils\Helpers;
use Dropbox\Client as DropboxClient;
use Pulse\Services\Manager\ManagerInterface;
use Pulse\Services\Manager\File\FileInterface;
use Illuminate\Contracts\Filesystem\Filesystem;
use Pulse\Services\Manager\Quota\QuotaInterface;
use Pulse\Services\Manager\Adapters\AbstractAdapter;
use Pulse\Services\Manager\Adapters\AdapterInterface;

class DropboxAdapter extends AbstractAdapter
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
     * File System
     * @var Illuminate\Contracts\Filesystem\Filesystem
     */
    private $fileSystem;


    /**
     * Constructor
     * @param DropboxClient $service
     */
    public function __construct(DropboxClient $service, QuotaInterface $quotaInfo, Filesystem $fileSystem)
    {
        $this->service = $service;
        $this->quotaInfo = $quotaInfo;
        $this->fileSystem = $fileSystem;
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
     * Get Filesystem
     * @return Filesystem
     */
    public function getFilesystem()
    {
        return $this->fileSystem;
    }

    /**
     * Get the Quota of the Cloud Account
     * @param  array  $data Additional Data
     * @return Pulse\Services\Manager\Quota\QuotaInterface
     */
    public function getQuota(array $data = array())
    {
        $account = $this->getService()->getAccountInfo();

        if ($account) {
            $this->makeQuotaInfo($account);

            return $this->quotaInfo;
        }
    }

    /**
     * Get File
     * @param  string $file   File
     * @param  array  $data   Additional Data
     * @return Pulse\Service\Manager\File\FileInterface
     */
    public function getFileInfo($file, array $data = array())
    {
        try {
            //Get the File
            $fileInfo = $this->getService()->getMetadata($file);
            //Make File, FileInterface compatible
            return $this->makeFile($fileInfo);
        } catch (Exception $e) {
            // @todo
            dd($e);
        }

        return false;
    }

    /**
     * List Children of a given folder path or id
     * @param  string $path Folder path or ID
     * @param  array  $data Additional Data
     * @return Pulse\Services\Manager\File\FileInterface
     */
    public function listChildren($path = null, array $data = array())
    {
        //Root
        if (is_null($path)) {
            $path = "/";
        }

        $maxResults = isset($data['maxResults']) ? $data['maxResults'] : 48;
        $metadata = $this->getService()->getMetadataWithChildren($path);
        $items = $metadata['contents'];

        //Files not found
        if (empty($items)) {
            return false;
        }

        //Make the file list
        $files = $this->makeFileList($items);

        return $files;
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
        $name = pathinfo($file, PATHINFO_FILENAME);
        $ext = pathinfo($file, PATHINFO_EXTENSION);

        if (is_null($location)) {
            $path = pathinfo($file, PATHINFO_DIRNAME);
            $location = $path === "/" ? "" : $path;
        }

        $random = str_random(6);
        $copy = isset($data['title']) ? $data['title'] : "({$random}) Copy of {$name}.{$ext}";

        //If the title misses an extension,
        //we'll use the original file's extension
        $copy = (!pathinfo($copy, PATHINFO_EXTENSION)) ? "{$copy}.{$ext}" : $copy;

        $newLocation = "{$location}/{$copy}";

        try {
            //Copy the file
            $copiedFile = $this->getService()->copy($file, $newLocation);
            //Make File, FileInterface compatible
            return $this->makeFile($copiedFile);
        } catch (Exception $e) {
            // @todo
            dd($e);
        }
    }

    /**
     * Move File
     * @param  string $file          File to move
     * @param  string|null $location Location to move the file to
     * @param  array       $data     Additional Data
     * @return Pulse\Services\Manager\File\FileInterface
     */
    public function move($file, $location, array $data = array())
    {
        $newFile = basename($file);

        if ($location === "/") {
            $location = "";
        }

        $newLocation = "{$location}/{$newFile}";

        try {
            //Move the file
            $movedFile = $this->getService()->move($file, $newLocation);
            //Make File, FileInterface compatible
            return $this->makeFile($movedFile);
        } catch (Exception $e) {
            // @todo
            dd($e);
        }
    }

    /**
     * Delete File
     * @param  string $file          File to delete
     * @param  array       $data     Additional Data
     * @return array ['file' => $file]
     */
    public function delete($file, array $data = array())
    {
        //Delete the file
        $deleteFile = $this->getService()->delete($file);
        return ['file' => $file];
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
        if ($location === "/") {
            $location = "";
        }

        $folder = "{$location}/{$name}";

        try {
            //Create Folder
            $createdFolder = $this->getService()->createFolder($folder);
            //Make File, FileInterface compatible
            return $this->makeFile($createdFolder);
        } catch (Exception $e) {
            // @todo
            dd($e);
        }
        return false;
    }

    /**
     * Get Download Link
     * @param  string $file File
     * @param  array  $data Additional Data
     * @return string       Download Link
     */
    public function getDownloadLink($file, array $data = array())
    {
        try {
            //Get Download Link
            $downloadLink = $this->getService()->createTemporaryDirectLink($file);

            if (isset($downloadLink[0])) {
                return $downloadLink[0] . "?dl=1";
            }
        } catch (Exception $e) {
            // @todo
            dd($e);
        }

        return false;
    }

    /**
     * Get Share Link
     * @param  string $file File
     * @param  array  $data Additional Data
     * @return string       Share Link
     */
    public function getShareLink($file, array $data = array())
    {
        try {
            //Get Share Link
            $shareLink = $this->getService()->createShareableLink($file);

            if ($shareLink) {
                return $shareLink;
            }
        } catch (Exception $e) {
            // @todo
            dd($e);
        }

        return false;
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
        if ($location === "/") {
            $location = "";
        }

        //Title
        $title = is_null($title) ? basename($file) : $title;

        $location = "{$location}/{$title}";

        //Mime Type
        $mimeType = isset($data['mimeType']) ? $data['mimeType'] : mime_content_type($file);

        //File Size
        $fileSize = (int) (isset($data['fileSize']) ? $data['fileSize'] : filesize($file));

        //If a file path is given
        $fileStream = fopen($file, "rb");

        //Upload the file
        $uploadedFile = $this->getService()->uploadFile($location, WriteMode::add(), $fileStream);

        //File was uploaded
        if ($uploadedFile) {
            //Make File, FileInterface compatible
            return $this->makeFile($uploadedFile);
        }

        return false;
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
        $location = pathinfo($file, PATHINFO_DIRNAME);

        if ($location === ".") {
            $file = "/" . $file;
            $location = "";
        }

        $newFile = "{$location}/{$title}";

        try {
            //Move the file
            $movedFile = $this->getService()->move($file, $newFile);
            //Make File, FileInterface compatible
            return $this->makeFile($movedFile);
        } catch (Exception $e) {
            // @todo
            dd($e);
        }
    }

    /**
     * Transfer File to Another Provider
     * @param  string $file     File Path
     * @param  Pulse\Services\Manager\ManagerInterface $newManager Manager of the Account to transfer the file to
     * @param  string $location File's new Location on the Provider
     * @param  string $title    New Title of the Transfered File
     * @param  array  $data     Additional Data
     * @return Pulse\Services\Manager\File\FileInterface
     */
    public function transfer($file, ManagerInterface $newManager, $location = null, $title = null, array $data = array())
    {
        return parent::transfer($file, $newManager, $location, $title, $data);
    }

    /**
     * Download File
     * @param  string $file File
     * @param  string $downloadUrl Explicitly Provided Download URL
     * @param  array  $data Additional Data
     * @return string Downloaded File Contents
     */
    public function downloadFile($file, $downloadUrl = null, array $data = array())
    {
        if (is_null($downloadUrl)) {
            $downloadUrl = $this->getDownloadLink($file);
        }

        try {
            $stream = fopen($downloadUrl, "rb");

            if ($stream) {
                $contents = stream_get_contents($stream);
                fclose($stream);
                return $contents;
            }

            return false;
        } catch (Exception $e) {
            // @todo
            dd($e);
        }

        return false;
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


    /**
     * Make File List
     * @param  array $list
     * @return Array (Pulse\Services\Manager\File\FileInterface)
     */
    protected function makeFileList(array $list)
    {
        $files = [];
        foreach ($list as $file) {
            $files[] = $this->makeFile($file);
        }

        return $files;
    }

    /**
     * Make File Object
     * @param  array $file
     * @return Pulse\Services\Manager\File\FileInterface
     */
    protected function makeFile(array $file)
    {
        $fileInfo = app('Pulse\Services\Manager\File\FileInterface');

        $fileInfo->setId($file['path']);

        $title = basename($file['path']);
        $fileInfo->setTitle($title);

        $fileInfo->setPath($file['path']);
        $fileInfo->setModified($file['modified']);
        $fileInfo->setSize($file['bytes']);

        $isFolder = (isset($file['is_dir']) && $file['is_dir']) ? true : false;
        $fileInfo->setIsFolder($isFolder);

        $mime = isset($file['mime_type']) ? $file['mime_type'] : "";
        $fileInfo->setMimeType($mime);

        $icon = $fileInfo->isFolder() ? "folder" : $mime;

        $fileInfo->setIcon(Helpers::getFileIcon($icon));

        $fileInfo->setOwners("");

        return $fileInfo;
    }
}
