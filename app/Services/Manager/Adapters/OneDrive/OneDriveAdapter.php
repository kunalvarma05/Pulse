<?php
namespace Pulse\Services\Manager\Adapters\OneDrive;

use Exception;
use Pulse\Utils\Helpers;
use League\OAuth2\Client\Token\AccessToken;
use Kunnu\OneDrive\Client as OneDriveClient;
use Pulse\Services\Manager\File\FileInterface;
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
     * List Children of a given folder path or id
     * @param  string $path Folder path or ID
     * @param  array  $data Additional Data
     * @return Pulse\Services\Manager\File\FileInterface
     */
    public function listChildren($path = null, array $data = array())
    {
        //Root
        if($path === "/")
        {
            $path = null;
        }

        $maxResults = isset($data['maxResults']) ? $data['maxResults'] : 48;
        $children = $this->getService()->listChildren($path, ['top' => $maxResults, 'expand' => "thumbnails(select=medium)"]);
        $items = $children->value;

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
        //File
        $file = $this->getService()->getItem($file);

        //Copy Location not specified
        if($location === "/" || is_null($location)) {
            //Use the original file's parent folder
            if(isset($file->parentReference)) {
                $location = $file->parentReference->id;
            }
            else {
                //Use the drive root
                $driveRoot = $this->getService()->getDriveRoot();
                $location = $driveRoot->id;
            }
        }

        //File Copy Name
        $random = str_random(6);
        $ext = pathinfo($file->name, PATHINFO_EXTENSION);
        $name = isset($data['title']) ? $data['title'] : "({$random}) Copy of {$file->name}";

        //If the title misses an extension,
        //we'll use the original file's extension
        $name = (!pathinfo($name, PATHINFO_EXTENSION)) ? "{$name}.{$ext}" : $name;

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
            dd($e->getMessage());
            return false;
        }

        return false;
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
        $downloadUrl = isset($file->{'@content.downloadUrl'}) ? $file->{'@content.downloadUrl'} : "";
        $fileInfo->setDownloadURL($downloadUrl);

        $isFolder = isset($file->folder) ? true : false;
        $fileInfo->setIsFolder($isFolder);

        $mime = (isset($file->file) && isset($file->file->mimeType)) ? $file->file->mimeType : "";
        $fileInfo->setMimeType($mime);

        $icon = $fileInfo->isFolder() ? "folder" : $mime;

        $fileInfo->setIcon(Helpers::getFileIcon($icon));

        if(isset($file->thumbnails)) {
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