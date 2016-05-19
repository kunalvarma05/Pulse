<?php
namespace Pulse\Services\Manager\Adapters;

use Crypt;
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
        try {
            //Fetch the file info
            $fileInfo = $this->getFileInfo($file);

            //Cannot transfer folders
            if ($fileInfo->isFolder()) {
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

            if (!$contents) {
                return false;
            }

            $randomStr = str_random(10) . "_" . time();
            $tempFileName = "{$randomStr}";

            //Store File Locally
            $this->getFilesystem()->put("/temp/" . $tempFileName, $contents);

            //Temporary Stored File
            $tempFile = storage_path("app/temp/{$tempFileName}");

            //Upload File to New Account
            $transferedFile = $newManager->uploadFile($tempFile, $location, $title, $data);

            //Delete the temporary file
            $this->getFilesystem()->delete("/temp/" . $tempFileName);

            //Transfered File
            return $transferedFile;
        } catch (Exception $e) {
            // @todo
            return false;
        }

        return false;
    }

    /**
     * Encrypt File
     * @param  string $file     File Path
     * @param  string $location File Location
     * @param  array  $data     Additional Data
     * @return Pulse\Services\Manager\File\FileInterface
     */
    public function encrypt($file, $location = null, array $data = array())
    {
        try {
            //Fetch the file info
            $fileInfo = $this->getFileInfo($file);

            //Cannot encrypt folders
            if ($fileInfo->isFolder()) {
                return false;
            }

            //File Extension
            $extension = pathinfo($fileInfo->getTitle(), PATHINFO_EXTENSION);

            //Get the Title of the File
            $title = $fileInfo->getTitle();

            //If the title misses an extension,
            //we'll use the original file's extension
            $title = (!pathinfo($title, PATHINFO_EXTENSION)) ? "{$title}.{$extension}" : $title;
            $title = "(encrypted) - {$title}";

            //Get the MimeType of the File
            $mimeType = $fileInfo->getMimeType();
            //Get the Size of the File
            $fileSize = $fileInfo->getSize();

            $data = compact('mimeType', 'fileSize');

            //Download and store file temporarily
            $contents = $this->downloadFile($file, $fileInfo->getDownloadUrl());

            if (!$contents) {
                return false;
            }

            $randomStr = str_random(10) . "_" . time();
            $tempFileName = "{$randomStr}";

            //Encrypt File
            $encryptedContents = Crypt::encrypt($contents);

            //Store File Locally
            $this->getFilesystem()->put("/temp/" . $tempFileName, $encryptedContents);

            //Temporary Stored File
            $tempFile = storage_path("app/temp/{$tempFileName}");


            //Upload File to New Account
            $encryptedFile = $this->uploadFile($tempFile, $location, $title, $data);

            //Delete the temporary file
            $this->getFilesystem()->delete("/temp/" . $tempFileName);

            //Transfered File
            return $encryptedFile;

        } catch (Exception $e) {
            // @todo
            dd($e->getMessage());
        }

        return false;
    }

    /**
     * Decrypt File
     * @param  string $file     File Path
     * @param  string $location File Location
     * @param  array  $data     Additional Data
     * @return Pulse\Services\Manager\File\FileInterface
     */
    public function decrypt($file, $location = null, array $data = array())
    {
        try {
            //Fetch the file info
            $fileInfo = $this->getFileInfo($file);

            //Cannot decrypt folders
            if ($fileInfo->isFolder()) {
                return false;
            }

            //File Extension
            $extension = pathinfo($fileInfo->getTitle(), PATHINFO_EXTENSION);

            //Get the Title of the File
            $title = $fileInfo->getTitle();

            //If the title misses an extension,
            //we'll use the original file's extension
            $title = (!pathinfo($title, PATHINFO_EXTENSION)) ? "{$title}.{$extension}" : $title;
            $title = "(decrypted) - {$title}";

            //Get the MimeType of the File
            $mimeType = $fileInfo->getMimeType();
            //Get the Size of the File
            $fileSize = $fileInfo->getSize();

            $data = compact('mimeType', 'fileSize');

            //Download and store file temporarily
            $contents = $this->downloadFile($file, $fileInfo->getDownloadUrl());

            if (!$contents) {
                return false;
            }

            $randomStr = str_random(10) . "_" . time();
            $tempFileName = "{$randomStr}";

            //Decrypt File
            $decryptedContents = Crypt::decrypt($contents);

            //Store File Locally
            $this->getFilesystem()->put("/temp/" . $tempFileName, $decryptedContents);

            //Temporary Stored File
            $tempFile = storage_path("app/temp/{$tempFileName}");


            //Upload File to New Account
            $decryptedFile = $this->uploadFile($tempFile, $location, $title, $data);

            //Delete the temporary file
            $this->getFilesystem()->delete("/temp/" . $tempFileName);

            //Delete the encrypted file
            $this->delete($file);

            //Transfered File
            return $decryptedFile;
        } catch (Exception $e) {
            // @todo
            return false;
        }

        return false;
    }
}
