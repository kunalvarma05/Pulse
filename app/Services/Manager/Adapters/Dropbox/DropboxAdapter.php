<?php
namespace Pulse\Services\Manager\Adapters\Dropbox;

use Exception;
use Pulse\Utils\Helpers;
use Dropbox\Client as DropboxClient;
use Pulse\Services\Manager\File\FileInterface;
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
     * List Children of a given folder path or id
     * @param  string $path Folder path or ID
     * @param  array  $data Additional Data
     * @return Pulse\Services\Manager\File\FileInterface
     */
    public function listChildren($path = null, array $data = array())
    {
        //Root
        if(is_null($path))
        {
            $path = "/";
        }

        $maxResults = isset($data['maxResults']) ? $data['maxResults'] : 48;
        $metadata = $this->getService()->getMetadataWithChildren($path);
        $items = $metadata['contents'];

        //Files not found
        if(empty($items))
        {
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

        if(is_null($location)) {
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
            return false;
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

        if($location === "/") {
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
            return false;
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