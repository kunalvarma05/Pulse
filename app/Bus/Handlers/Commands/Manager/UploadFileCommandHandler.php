<?php
namespace Pulse\Bus\Handlers\Commands\Manager;

use Pulse\Services\Manager\ManagerFactory;
use Pulse\Services\Authorization\AuthFactory;
use Pulse\Bus\Commands\Manager\UploadFileCommand;

class UploadFileCommandHandler
{

    /**
     * Handle the UploadFileCommand
     * @param  UploadFileCommand $command
     * @return Pulse\Services\Manager\File\FileInterface
     */
    public function handle(UploadFileCommand $command)
    {
        //Provider
        $provider = $command->account->provider;

        //Authorization
        $authFactory = AuthFactory::create($provider->alias);
        $access_token = $authFactory->refreshAccessToken($command->account->access_token);

        //Manager
        $manager = ManagerFactory::create($provider->alias, $access_token);

        //Additional Data
        $data = $command->data;
        //File
        $file = $command->file;
        //Extension
        $ext = isset($data['fileExtension']) ? $data['fileExtension'] : pathinfo($file, PATHINFO_EXTENSION);
        //Location
        $location = $command->location;
        //Title
        $title = $command->title;
        //If the title misses an extension,
        //we'll use the original file's extension
        $title = (!pathinfo($title, PATHINFO_EXTENSION)) ? "{$title}.{$ext}" : $title;

        //Upload File
        return $manager->uploadFile($file, $location, $title, $data);
    }

}