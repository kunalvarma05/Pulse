<?php
namespace Pulse\Bus\Handlers\Commands\Account;

use Pulse\Events\Account\AccountWasCreatedEvent;
use Pulse\Bus\Commands\Account\CreateAccountCommand;

class CreateAccountCommandHandler
{

    /**
     * Handle the Create Account Command
     *
     * @param  Pulse\Bus\Commands\Account\CreateAccountCommand $command
     * @return Pulse\Models\Account
     */
    public function handle(CreateAccountCommand $command)
    {
        //Create Account
        $account = $command
        ->user
        ->accounts()
        ->create([
            'provider_id' => $command->provider->id,
            'name' => $command->name,
            'uid' => $command->uid,
            'access_token' => $command->access_token,
            ]);

        //Fire the AccountWasCreatedEvent
        event(new AccountWasCreatedEvent($account));

        //Return the created account
        return $account;
    }
}
