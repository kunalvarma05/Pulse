<?php
namespace Pulse\Services\Manager\Adapters;

use Exception;
use Pulse\Services\Manager\ManagerInterface;
use Pulse\Services\Manager\Adapters\AdapterInterface;

abstract class AbstractAdapter implements AdapterInterface
{

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
        try{
            //Fetch the file info
            $fileInfo = $this->getFileInfo($file);

            //Cannot transfer folders
            if($fileInfo->isFolder()) {
                return false;
            }

            //File Extension
            $extension = pathinfo($fileInfo->getTitle(), PATHINFO_EXTENSION);

            //Get the Title of the File
            $title = is_null($title) ? $fileInfo->getTitle() : $title;

            //If the title misses an extension,
            //we'll use the original file's extension
            $title = (!pathinfo($title, PATHINFO_EXTENSION)) ? "{$title}.{$extension}" : $title;

            //Get the MimeType of the File
            $mimeType = $fileInfo->getMimeType();
            //Get the Size of the File
            $fileSize = $fileInfo->getSize();

            $data = compact('mimeType', 'fileSize');

            //Download and store file temporarily
            $contents = $this->downloadFile($file, $fileInfo->getDownloadUrl());

            if(!$contents) {
                return false;
            }

            $randomStr = str_random(10) . "_" . time();
            $tempFileName = "{$randomStr}";

            //Store File Locally
            $this->getFilesystem()->put($tempFileName, $contents);

            //Temporary Stored File
            $tempFile = storage_path("app/{$tempFileName}");

            //Upload File to New Account
            $transferedFile = $newManager->uploadFile($tempFile, $location, $title, $data);

            //Delete the temporary file
            $this->getFilesystem()->delete($tempFileName);

            //Transfered File
            return $transferedFile;
        } catch (Exception $e) {
            // @todo
            return false;
        }

        return false;
    }
}