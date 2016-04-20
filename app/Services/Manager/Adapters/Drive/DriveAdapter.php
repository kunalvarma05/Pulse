<?php
namespace Pulse\Services\Manager\Adapters\Drive;

use Exception;
use Google_Client;
use Google_Exception;
use Pulse\Utils\Helpers;
use Google_Service_Drive;
use Google_Service_Drive_About;
use Google_Service_Drive_FileList;
use Google_Service_Drive_DriveFile;
use Google_Service_Drive_ParentReference;
use Pulse\Services\Manager\File\FileInterface;
use Pulse\Services\Manager\Quota\QuotaInterface;
use Pulse\Services\Manager\Adapters\AdapterInterface;

class DriveAdapter implements AdapterInterface
{

    const DRIVE_FOLDER_MIME = "application/vnd.google-apps.folder";

    /**
     * Google Plus Service
     * @var Google_Service_Drive
     */
    private $service;

    /**
     * Quota Info
     * @var Pulse\Services\Manager\Quota\QuotaInterface;
     */
    private $quotaInfo;


    /**
     * Constructor
     * @param Google_Service_Drive $service
     */
    public function __construct(Google_Service_Drive $service, QuotaInterface $quotaInfo)
    {
        $this->service = $service;
        $this->quotaInfo = $quotaInfo;
    }

    /**
     * Get Service
     * @return Google_Service_Drive
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
        $about = $this->getService()->about->get();

        if($about)
        {
            $this->makeQuotaInfo($about);

            return $this->quotaInfo;
        }

        return false;
    }

    /**
     * List Children of a given folder path or id
     * @param  string $path Folder path or ID
     * @param  array  $data Additional Data
     * @return Array (Pulse\Services\Manager\File\FileInterface)
     */
    public function listChildren($path = null, array $data = array())
    {
        //Drive Root
        if(is_null($path) || $path === "/")
        {
            $path = $this->getService()->about->get()->getRootFolderId();
        }

        $maxResults = isset($data['maxResults']) ? $data['maxResults'] : 48;
        $orderBy = isset($data['orderBy']) ? $data['orderBy'] : "folder asc,title asc";
        $trashed = isset($data['trashed']) ? 'true' : 'false';
        $owners = (isset($data['owners']) && !empty($data['owners'])) ? $data['owners'] : [];
        $pathSearch = "'{$path}' in parents";
        $ownerSearch = [];

        foreach ($owners as $owner) {
            $ownerSearch[] = "'{$owner}' in owners";
        }

        $ownerSearch = implode(" or ", $ownerSearch);
        $searchQuery = "{$pathSearch} and trashed = {$trashed}";

        if($ownerSearch) {
            $searchQuery .= " and " . $ownerSearch;
        }

        $params = array('maxResults' => $maxResults, 'orderBy' => $orderBy, 'q' => $searchQuery);

        if(isset($data['pageToken'])) {
            $params['pageToken'] = $data['pageToken'];
        }

        //File List
        $items = $this->getService()->files->listFiles($params);

        //File Not Found
        if(empty($items->getItems())) {
            return false;
        }

        $files = $this->makeFileList($items);

        return $files;
    }

    /**
     * Copy File
     * @param  string $file     File ID
     * @param  string|null $location Location to copy the file to
     * @param  array  $data     Additional Data
     * @return Pulse\Services\Manager\File\FileInterface
     */
    public function copy($file, $location = null, array $data = array())
    {
        $file = $this->getFile($file, 'id, title, parents');
        $fileCopy = new Google_Service_Drive_DriveFile();

        $title = isset($data['title']) ? $data['title'] : $file->getTitle() . " - copy";
        $fileCopy->setTitle($title);

        //If the Parent is set
        if(!is_null($location)) {
            $parent = new Google_Service_Drive_ParentReference();
            $parent->setId($location);
            $fileCopy->setParents([$parent]);
        }

        try {
            //Copy the file
            $copiedFile = $this->getService()->files->copy($file->getId(), $fileCopy);
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
        // File
        $file = $this->getService()->files->get($file, ['fields' => 'id, parents']);

        $emptyFileMetadata = new \Google_Service_Drive_DriveFile();

        //Extract IDs of Previous Parents
        $parents = [];
        foreach ($file->getParents() as $parent) {
            $parents[] = $parent->getId();
        }

        //Convert to comma-separated string
        $previousParents = join(',', $parents);

        try {
            //Move the file to the new folder
            $movedFile = $this->getService()->files->patch($file->getId(), $emptyFileMetadata, array(
              'addParents' => $location,
              'removeParents' => $previousParents,
              ));
            //Make File, FileInterface compatible
            return $this->makeFile($movedFile);
        } catch (Exception $e) {
            // @todo
            return false;
        }
    }

    /**
     * Make Quota Info
     * @param  Google_Service_Drive_About $about
     */
    protected function makeQuotaInfo(Google_Service_Drive_About $about)
    {
        $this->quotaInfo->setSpaceAlloted($about->getQuotaBytesTotal());
        $this->quotaInfo->setSpaceUsed($about->getQuotaBytesUsed());

        $remaining = $about->getQuotaBytesTotal() - $about->getQuotaBytesUsed();
        $this->quotaInfo->setSpaceRemaining($remaining);
    }

    /**
     * Make File List
     * @param  Google_Service_Drive_FileList $list
     * @return Array (Pulse\Services\Manager\File\FileInterface)
     */
    protected function makeFileList(Google_Service_Drive_FileList $list)
    {
        $items = $list->getItems();
        $files = [];
        foreach ($items as $file) {
            $files[] = $this->makeFile($file);
        }

        return $files;
    }

    /**
     * Make File Object
     * @param  Google_Service_Drive_DriveFile $file
     * @return Pulse\Services\Manager\File\FileInterface
     */
    protected function makeFile(Google_Service_Drive_DriveFile $file)
    {
        $fileInfo = app('Pulse\Services\Manager\File\FileInterface');

        $fileInfo->setId($file->getId());

        $title = $file->getTitle() ? $file->getTitle() : $file->getOriginalFilename();
        $fileInfo->setTitle($title);

        $fileInfo->setPath($file->getId());
        $fileInfo->setModified($file->getModifiedDate());
        $fileInfo->setSize($file->getFileSize());

        $isFolder = (strtolower($file->getMimeType()) === self::DRIVE_FOLDER_MIME) ? true : false;
        $fileInfo->setIsFolder($isFolder);

        $fileInfo->setMimeType($file->getMimeType());
        $fileInfo->setThumbnailURL($file->getThumbnailLink());

        $downloadUrl = $file->getDownloadUrl();
        if(!$downloadUrl) {
            $exportLinks = $file->getExportLinks();
            $downloadUrl = empty($exportLinks) ? $file->getSelfLink() : end($exportLinks);
        }
        $fileInfo->setDownloadURL($downloadUrl);

        $fileInfo->setURL($file->getSelfLink());
        $fileInfo->setIcon(Helpers::getFileIcon($file->getMimeType()));

        $fileInfo->setOwners($file->getOwnerNames());

        return $fileInfo;
    }

    /**
     * Get File
     * @param  string $fileId File ID
     * @return Pulse\Service\Manager\File\FileInterface
     */
    protected function getFile($fileId, $fields = null)
    {
        try {
            $data = [];

            if($fields)
                $data['fields'] = $fields;

            $file = $this->getService()->files->get($fileId, $data);

            if($file)
                return $this->makeFile($file);
        } catch (Exception $e) {
            // @todo
            return false;
        }

        return false;
    }

}