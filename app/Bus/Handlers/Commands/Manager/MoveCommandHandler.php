<?php
namespace Pulse\Bus\Handlers\Commands\Manager;

use Pulse\Services\Manager\ManagerFactory;
use Pulse\Bus\Commands\Manager\MoveCommand;
use Pulse\Services\Authorization\AuthFactory;

class MoveCommandHandler
{

    /**
     * Handles MoveCommand
     * @param  MoveCommand $command
     * @return Pulse\Services\Manager\File\FileInterface
     */
    public function handle(MoveCommand $command)
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
        $location = $command->location;
        //Additional Data
        $data = $command->data;

        //Move File
        return $manager->move($file, $location);
    }
}
