<?php
namespace Pulse\Services\Manager\Adapters\OneDrive;

use Exception;
use Pulse\Utils\Helpers;
use League\OAuth2\Client\Token\AccessToken;
use Kunnu\OneDrive\Client as OneDriveClient;
use Pulse\Services\Manager\ManagerInterface;
use Pulse\Services\Manager\File\FileInterface;
use Illuminate\Contracts\Filesystem\Filesystem;
use Pulse\Services\Manager\Quota\QuotaInterface;
use Pulse\Services\Manager\Adapters\AbstractAdapter;
use Pulse\Services\Manager\Adapters\AdapterInterface;

class OneDriveAdapter extends AbstractAdapter
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
     * File System
     * @var Illuminate\Contracts\Filesystem\Filesystem
     */
    private $fileSystem;

    /**
     * Constructor
     * @param OneDriveClient $service
     * @param QuotaInterface $quota
     */
    public function __construct(OneDriveClient $service, QuotaInterface $quotaInfo, Filesystem $fileSystem)
    {
        $this->service = $service;
        $this->quotaInfo = $quotaInfo;
        $this->fileSystem = $fileSystem;
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
     * Get Filesystem
     * @return Filesystem
     */
    public function getFilesystem()
    {
        return $this->fileSystem;
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

        if ($drive) {
            $this->makeQuotaInfo($drive);

            return $this->quotaInfo;
        }

        return false;
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
            $fileInfo = $this->getService()->getItem($file, false, $data);
            //Make File, FileInterface compatible
            return $this->makeFile($fileInfo);
        } catch (Exception $e) {
            // @todo
            return false;
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
        if ($path === "/") {
            $path = null;
        }

        $maxResults = isset($data['maxResults']) ? $data['maxResults'] : 48;
        $children = $this->getService()->listChildren($path, ['top' => $maxResults, 'expand' => "thumbnails(select=medium)"]);
        $items = $children->value;

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
        //File
        $file = $this->getService()->getItem($file);

        //Copy Location not specified
        if ($location === "/" || is_null($location)) {
            //Use the drive root
            $driveRoot = $this->getService()->getDriveRoot();
            $location = $driveRoot->id;
        }

        //File Copy Name
        $random = str_random(6);
        $ext = pathinfo($file->name, PATHINFO_EXTENSION);

        $name = isset($data['title']) ? $data['title'] : "({$random}) Copy of {$file->name}";

        $newName = empty($ext) ? $name : "{$name}.{$ext}";

        //If the title misses an extension,
        //we'll use the original file's extension
        $name = (!pathinfo($name, PATHINFO_EXTENSION)) ? $newName : $name;

        try {
            //Copy the File
            $copiedFile = $this->getService()->copy($file->id, $location, $name);
            //Make File, FileInterface compatible
            return $this->makeFile($copiedFile);
        } catch (Exception $e) {
            // @todo
            return false;
        }

        return false;
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
        try {
            //Move the file
            $movedFile = $this->getService()->move($file, $location);
            //Make File, FileInterface compatible
            return $this->makeFile($movedFile);
        } catch (Exception $e) {
            // @todo
            return false;
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
        try {
            //Move the file
            $movedFile = $this->getService()->updateMeta($file, ['name' => $title]);
            //Make File, FileInterface compatible
            return $this->makeFile($movedFile);
        } catch (Exception $e) {
            // @todo
            return false;
        }

        return false;
    }

    /**
     * Delete File
     * @param  string $file          File to delete
     * @param  array       $data     Additional Data
     * @return array ['file' => $file]
     */
    public function delete($file, array $data = array())
    {
        try {
            $deleteFile = $this->getService()->delete($file);
            return ['file' => $file];
        } catch (Exception $e) {
            // @todo
            return false;
        }

        return false;
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
        try {
            //Create Folder
            $folder = $this->getService()->createFolder($name, $location, "rename");
            //Make File, FileInterface compatible
            return $this->makeFile($folder);
        } catch (Exception $e) {
            // @todo
            return false;
        }
    }

    /**
     * Get Download Link
     * @param  string $file File
     * @param  array  $data Additional Data
     * @return string       Download Link
     */
    public function getDownloadLink($file, array $data = array())
    {
        //Get File Info
        $file = $this->getFileInfo($file, $data);

        //Fetch the download url
        return $file->getDownloadURL();
    }

    /**
     * Get Share Link
     * @param  string $file File
     * @param  array  $data Additional Data
     * @return string       Share Link
     */
    public function getShareLink($file, array $data = array())
    {
        //Create Sharing Link
        $createShareLink = $this->getService()->createShareLink($file);

        //Sharing link was created
        if($createShareLink && isset($createShareLink->link) && isset($createShareLink->link->webUrl)) {
            return $createShareLink->link->webUrl;
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
            $location = null;
        }

        //Title
        $title = is_null($title) ? basename($file) : $title;

        //Upload the file
        $uploadedFile = $this->getService()->uploadFile($file, $title, $location, "rename");

        //File was uploaded
        if ($uploadedFile) {
            //Make File, FileInterface compatible
            return $this->makeFile($uploadedFile);
        }

        return false;
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
        return $this->getService()->downloadItem($file);
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
     * @param  $file
     * @return Pulse\Services\Manager\File\FileInterface
     */
    protected function makeFile($file)
    {
        $fileInfo = app('Pulse\Services\Manager\File\FileInterface');

        $fileInfo->setId($file->id);

        $fileInfo->setTitle($file->name);
        $path = ($file->parentReference && $file->parentReference->path) ? $file->parentReference->path : $file->id;
        $fileInfo->setPath($path);
        $fileInfo->setModified($file->lastModifiedDateTime);
        $fileInfo->setSize($file->size);

        $fileInfo->setURL($file->webUrl);
        $downloadUrl = isset($file->{'@content.downloadUrl'}) ? $file->{'@content.downloadUrl'} : null;
        $fileInfo->setDownloadURL($downloadUrl);

        $isFolder = isset($file->folder) ? true : false;
        $fileInfo->setIsFolder($isFolder);

        $mime = (isset($file->file) && isset($file->file->mimeType)) ? $file->file->mimeType : "";
        $fileInfo->setMimeType($mime);

        $icon = $fileInfo->isFolder() ? "folder" : $mime;

        $fileInfo->setIcon(Helpers::getFileIcon($icon));

        if (isset($file->thumbnails)) {
            $thumbnails = $file->thumbnails;
            $thumbnail = (isset($thumbnails[0]) && isset($thumbnails[0]->medium)) ? $thumbnails[0]->medium->url : "";
            $fileInfo->setThumbnailURL($thumbnail);
        }

        $owner = (isset($file->createdBy) && isset($file->createdBy->user)) ? $file->createdBy->user->displayName : "";
        $fileInfo->setOwners([$owner]);

        return $fileInfo;
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
