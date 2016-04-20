<?php
namespace Pulse\Bus\Handlers\Commands\Manager;

use Pulse\Services\Manager\ManagerFactory;
use Pulse\Services\Authorization\AuthFactory;
use Pulse\Bus\Commands\Manager\GetFileInfoCommand;

class GetFileInfoCommandHandler
{

    /**
     * Handle GetFileInfoCommand
     * @param  GetFileInfoCommand $command
     * @return Pulse\Services\Manager\File\FileInfo
     */
    public function handle(GetFileInfoCommand $command)
    {
        //Provider
        $provider = $command->account->provider;

        //Authorization
        $authFactory = AuthFactory::create($provider->alias);
        $access_token = $authFactory->refreshAccessToken($command->account->access_token);

        //Manager
        $manager = ManagerFactory::create($provider->alias, $access_token);

        //File
        $file = $command->file;
        //Data
        $data = $command->data;

        //Get File Info
        return $manager->getFileInfo($file, $data);
    }

}