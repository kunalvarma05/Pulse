<?php
namespace Pulse\Bus\Handlers\Commands\Manager;

use Pulse\Services\Manager\ManagerFactory;
use Pulse\Services\Authorization\AuthFactory;
use Pulse\Bus\Commands\Manager\GetQuotaCommand;

class GetQuotaCommandHandler
{

    /**
     * Handle the GetQuotaCommand
     * @param  GetQuotaCommand $command
     * @return Pulse\Services\Manager\Quota\QuotaInterface
     */
    public function handle(GetQuotaCommand $command)
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

        //Fetch the Quota
        return $manager->getQuota();
    }

}