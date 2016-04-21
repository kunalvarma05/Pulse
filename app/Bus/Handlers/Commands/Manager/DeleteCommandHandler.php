<?php
namespace Pulse\Bus\Handlers\Commands\Manager;

use Pulse\Services\Manager\ManagerFactory;
use Pulse\Bus\Commands\Manager\DeleteCommand;
use Pulse\Services\Authorization\AuthFactory;

class DeleteCommandHandler
{

    /**
     * Handle Delete Command
     * @param  DeleteCommand $command
     * @return object
     */
    public function handle(DeleteCommand $command)
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
        //Additional Data
        $data = $command->data;

        //Delete File
        return $manager->delete($file);
    }
}
