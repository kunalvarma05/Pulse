<?php
namespace Pulse\Bus\Handlers\Commands\Manager;

use Pulse\Services\Manager\ManagerFactory;
use Pulse\Services\Authorization\AuthFactory;
use Pulse\Bus\Commands\Manager\GetDownloadLinkCommand;

class GetDownloadLinkCommandHandler
{

    /**
     * Handle GetDownloadLinkCommand
     * @param  GetDownloadLinkCommand $command
     * @return string Download Link
     */
    public function handle(GetDownloadLinkCommand $command)
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
        return $manager->getDownloadLink($file, $data);
    }
}
