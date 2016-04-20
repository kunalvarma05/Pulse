<?php
namespace Pulse\Bus\Handlers\Commands\Manager;

use Pulse\Services\Manager\ManagerFactory;
use Pulse\Services\Authorization\AuthFactory;
use Pulse\Bus\Commands\Manager\ListFilesCommand;

class ListFilesCommandHandler
{

    /**
     * Handle the ListFilesCommand
     * @param  ListFilesCommand $command
     * @return Array (Pulse\Services\Manager\File\FileInterface)
     */
    public function handle(ListFilesCommand $command)
    {
        //Account
        $account = $command->account;

        //Provider
        $provider = $account->provider;

        //Authorization
        $authFactory = AuthFactory::create($provider->alias);
        $access_token = $authFactory->refreshAccessToken($account->access_token);

        //Manager
        $manager = ManagerFactory::create($provider->alias, $access_token);

        //Path
        $path = $command->path;

        //Fetch the Children
        return $manager->listChildren($path);
    }

}