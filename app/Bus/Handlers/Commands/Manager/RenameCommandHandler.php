<?php
namespace Pulse\Bus\Handlers\Commands\Manager;

use Pulse\Services\Manager\ManagerFactory;
use Pulse\Bus\Commands\Manager\RenameCommand;
use Pulse\Services\Authorization\AuthFactory;

class RenameCommandHandler
{

    /**
     * Handles RenameCommand
     * @param  RenameCommand $command
     * @return Pulse\Services\Manager\File\FileInterface
     */
    public function handle(RenameCommand $command)
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
        //Location
        $title = $command->title;
        //Additional Data
        $data = $command->data;

        //Rename File
        return $manager->rename($file, $title);
    }
}
