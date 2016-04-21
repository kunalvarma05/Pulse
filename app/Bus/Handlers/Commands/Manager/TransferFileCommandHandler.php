<?php
namespace Pulse\Bus\Handlers\Commands\Manager;

use Pulse\Services\Manager\ManagerFactory;
use Pulse\Services\Authorization\AuthFactory;
use Pulse\Bus\Commands\Manager\TransferFileCommand;

class TransferFileCommandHandler
{

    /**
     * Handles TransferFileCommand
     * @param  TransferFileCommand $command
     * @return Pulse\Services\Manager\File\FileInterface
     */
    public function handle(TransferFileCommand $command)
    {
        //Original Account Manager
        $manager = $this->makeManager($command->account->provider, $command->account->access_token);
        //New Account Manager
        $newManager = $this->makeManager($command->newAccount->provider, $command->newAccount->access_token);

        //File
        $file = $command->file;
        //Title
        $title = $command->title;
        //Location
        $location = $command->location;
        //Additional Data
        $data = $command->data;

        //Transfer File
        return $manager->transfer($file, $newManager, $location, $title, $data);
    }

    /**
     * Make Manager
     * @param  Pulse\Models\Provider $provider     Account Provider
     * @param  string $access_token Account Access Token
     * @return Pulse\Services\Manager\ManagerInterface
     */
    protected function makeManager($provider, $access_token)
    {
        //Authorization
        $authFactory = AuthFactory::create($provider->alias);
        $access_token = $authFactory->refreshAccessToken($access_token);

        //Manager
        return ManagerFactory::create($provider->alias, $access_token);
    }

}