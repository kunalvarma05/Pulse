<?php
namespace Pulse\Bus\Handlers\Commands\Manager;

use Pulse\Services\Manager\ManagerFactory;
use Pulse\Bus\Commands\Manager\DeleteCommand;
use Pulse\Services\Authorization\AuthFactory;

class DeleteCommandHandler
{

    public function handle(DeleteCommand $command)
    {
        //Authorization
        $authFactory = AuthFactory::create($command->provider->alias);
        $access_token = $authFactory->refreshAccessToken($command->account->access_token);

        //Manager
        $manager = ManagerFactory::create($command->provider->alias, $access_token);

        //File
        $file = $command->file;
        //Additional Data
        $data = $command->data;

        //Delete File
        return $manager->delete($file);
    }

}