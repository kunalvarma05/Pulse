<?php
namespace Pulse\Bus\Handlers\Commands\Manager;

use Pulse\Services\Manager\ManagerFactory;
use Pulse\Services\Authorization\AuthFactory;
use Pulse\Bus\Commands\Manager\CreateFolderCommand;

class CreateFolderCommandHandler
{

    /**
     * Handle the CreateFolderCommand
     * @param  CreateFolderCommand $command
     * @return Pulse\Services\Manager\File\FileInterface
     */
    public function handle(CreateFolderCommand $command)
    {
        //Provider
        $provider = $command->account->provider;

        //Authorization
        $authFactory = AuthFactory::create($provider->alias);
        $access_token = $authFactory->refreshAccessToken($command->account->access_token);

        //Manager
        $manager = ManagerFactory::create($provider->alias, $access_token);

        //Folder Name
        $title = $command->title;
        //Location
        $location = $command->location;
        //Additional Data
        $data = $command->data;

        //Move File
        return $manager->createFolder($title, $location, $data);
    }

}