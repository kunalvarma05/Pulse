<?php
namespace Pulse\Bus\Handlers\Commands\Manager;

use Pulse\Services\Manager\ManagerFactory;
use Pulse\Bus\Commands\Manager\CopyCommand;
use Pulse\Services\Authorization\AuthFactory;

class CopyCommandHandler
{

    public function handle(CopyCommand $command)
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

        //Title
        if($command->title)
            $data['title'] = $command->title;

        //Copy File
        return $manager->copy($file, $location, $data);
    }

}