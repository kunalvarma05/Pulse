<?php
namespace Pulse\Bus\Handlers\Commands\Provider;

use Pulse\Models\Account;
use Pulse\Services\Identity\IdentityFactory;
use Pulse\Bus\Commands\Provider\FetchAccountInfoCommand;

class FetchAccountInfoCommandHandler
{

    public function handle(FetchAccountInfoCommand $command)
    {
        //Resolve Identity Service
        $identity = IdentityFactory::create(strtolower($command->provider->alias), $command->access_token);

        //Get Account Info
        return $identity->getAccount();
    }

}