<?php
namespace Pulse\Services\Manager\Adapters\Drive;

use Exception;
use Google_Client;
use Google_Exception;
use Google_Http_Request;
use Pulse\Utils\Helpers;
use Google_Service_Drive;
use Google_Service_Drive_About;
use Google_Http_MediaFileUpload;
use Google_Service_Drive_FileList;
use Google_Service_Drive_DriveFile;
use Google_Service_Drive_ParentReference;
use Pulse\Services\Manager\ManagerInterface;
use Pulse\Services\Manager\File\FileInterface;
use Illuminate\Contracts\Filesystem\Filesystem;
use Pulse\Services\Manager\Quota\QuotaInterface;
use Pulse\Services\Manager\Adapters\AbstractAdapter;
use Pulse\Services\Manager\Adapters\AdapterInterface;

class DriveAdapter extends AbstractAdapter
{

    const DRIVE_FOLDER_MIME = "application/vnd.google-apps.folder";

    const MINIMUM_CHUNK_SIZE = 256 * 1024;

    const DEFAULT_CHUNK_SIZE = 10 * 1024 * 1024;

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
     * File System
     * @var Illuminate\Contracts\Filesystem\Filesystem
     */
    private $fileSystem;


    /**
     * Constructor
     * @param Google_Service_Drive $service
     */
    public function __construct(Google_Service_Drive $service, QuotaInterface $quotaInfo, Filesystem $fileSystem)
    {
        $this->service = $service;
        $this->quotaInfo = $quotaInfo;
        $this->fileSystem = $fileSystem;
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
        $about = $this->getService()->about->get();

        if($about)
        {
            $this->makeQuotaInfo($about);

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
            $fileInfo = $this->getService()->files->get($file, $data);
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
        $file = $this->getFileInfo($file, 'id, title, parents');
        $fileCopy = new Google_Service_Drive_DriveFile();

        $title = isset($data['title']) ? $data['title'] : "Copy of " . $file->getTitle();
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
        $fileInfo = new Google_Service_Drive_DriveFile();
        $fileInfo->setTitle($title);
        try {
            //Rename File
            $renamedFile = $this->getService()->files->patch($file, $fileInfo);
            //Make File, FileInterface compatible
            return $this->makeFile($renamedFile);
        } catch (Exception $e) {
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
            $deleteFile = $this->getService()->files->delete($file);
            return ['file' => $file];
        } catch (Exception $e) {
            // @todo
            return false;
        }
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
        //Folder MimeType
        $mimeType = self::DRIVE_FOLDER_MIME;

        //Drive Root
        if(is_null($location) || $location === "/")
        {
            $location = $this->getService()->about->get()->getRootFolderId();
        }

        //Folder MetaData
        $metadata = array('title' => $name, 'mimeType' => $mimeType);

        //Folder
        $folder = new Google_Service_Drive_DriveFile($metadata);

        //If the Parent is set
        if(!is_null($location)) {
            $parent = new Google_Service_Drive_ParentReference();
            $parent->setId($location);
            $folder->setParents([$parent]);
        }

        try{
            //Create Folder
            $createdFolder = $this->getService()->files->insert($folder);
            //Make File, FileInterface compatible
            return $this->makeFile($createdFolder);
        } catch (Exception $e) {
            // @todo
            return false;
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
        //Get File Info
        $file = $this->getFileInfo($file, $data);

        //Fetch the download url
        return $file->getDownloadURL();
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

        //Drive Root
        if(is_null($location) || $location === "/")
        {
            $location = $this->getService()->about->get()->getRootFolderId();
        }

        //Title
        $title = is_null($title) ? basename($file) : $title;

        //Mime Type
        $mimeType = isset($data['mimeType']) ? $data['mimeType'] : mime_content_type($file);

        //File Size
        $fileSize = (int) (isset($data['fileSize']) ? $data['fileSize'] : filesize($file));

        //If a file path is given
        $fileStream = fopen($file, "rb");

        //Upload the file
        $uploadedFile = $this->doFileUpload($fileStream, $title, $mimeType, $fileSize, $location);

        //File was uploaded
        if($uploadedFile) {
            //Make File, FileInterface compatible
            return $this->makeFile($uploadedFile);
        }

        return false;

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
        //If a download url is not provided explicitly
        if(is_null($downloadUrl)) {
            //Fetch the file info
            $fileInfo = $this->getFileInfo($file);
            //Fetch the download url
            $downloadUrl = $fileInfo->getDownloadUrl();
        }

        //If we obtained the download url
        if ($downloadUrl) {
            $request = new Google_Http_Request($downloadUrl, 'GET', null, null);

            $httpRequest = $this->getService()->getClient()
            ->getAuth()->authenticatedRequest($request);

            //Request was successful
            if ($httpRequest->getResponseHttpCode() == 200) {
                //File Contents
                return $httpRequest->getResponseBody();
            } else {
                // An error occurred.
                return null;
            }

        } else {
            // The file doesn't have any content stored on Drive.
            return null;
        }

        return false;
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
            $downloadUrl = empty($exportLinks) ? null : end($exportLinks);
        }
        $fileInfo->setDownloadURL($downloadUrl);

        $fileInfo->setURL($file->getAlternateLink());
        $fileInfo->setIcon(Helpers::getFileIcon($file->getMimeType()));

        $fileInfo->setOwners($file->getOwnerNames());

        return $fileInfo;
    }

    /**
     * Do File Upload
     * @param  resource $fileStream File Stream
     * @param  string $fileName   File Name
     * @param  string $mimeType   Mime Type
     * @param  int $fileSize   File Size
     * @param  string $parentId   Parent ID
     * @return Google_Service_Drive_DriveFile
     */
    protected function doFileUpload($fileStream, $fileName, $mimeType, $fileSize, $parentId = null)
    {
        $service = $this->getService();
        $client = $this->getService()->getClient();

        $file = new Google_Service_Drive_DriveFile();
        $file->setTitle($fileName);
        $file->setMimeType($mimeType);

        // Set the parent folder.
        if (!is_null($parentId)) {
            $parent = new Google_Service_Drive_ParentReference();
            $parent->setId($parentId);
            $file->setParents(array($parent));
        }

        //Chunk size
        $chunkSizeBytes = self::DEFAULT_CHUNK_SIZE;
        //Minimum Chunk Size
        $minimumChunkSize = self::MINIMUM_CHUNK_SIZE;

        //If the File is very small
        //do a simple multipart upload
        if($fileSize <= $minimumChunkSize)
        {
            try {
                $data = stream_get_contents($fileStream);

                $createdFile = $service->files->insert($file, array(
                  'data' => $data,
                  'mimeType' => $mimeType,
                  'uploadType' => 'multipart'
                  ));

                return $createdFile;

            } catch (Exception $e) {
                // @todo
                dd($e);
            }
        }

        // Call the API with the media upload, defer so it doesn't immediately return.
        $client->setDefer(true);
        $request = $service->files->insert($file);

        // Create a media file upload to represent our upload process.
        $media = new Google_Http_MediaFileUpload(
            $client,
            $request,
            $mimeType,
            null,
            true,
            $chunkSizeBytes
            );

        //Set the file size
        $media->setFileSize($fileSize);

        // Upload the various chunks.
        // $status will be false until the process is complete.
        $status = false;
        //Progress
        $progress = 0;

        //Wait for all the file chunks to be uploaded
        while (!$status && !feof($fileStream)) {
            $chunk = fread($fileStream, $chunkSizeBytes);
            $status = $media->nextChunk($chunk);

            if(!$status) {
                //nextChunk() returns 'false' whenever the upload is still in progress
                $progress = ($media->getProgress() / $fileSize) * 100;
            }
        }

        // The final value of $status will be the data from the API for the object
        // that has been uploaded.
        $result = false;

        if ($status != false) {
            $result = $status;
        }

        fclose($fileStream);

        //Uploaded File
        return $result;
    }
}