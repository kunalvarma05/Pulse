<?php
namespace Pulse\Bus\Handlers\Commands\User;

use Pulse\Models\User;
use Pulse\Events\User\UserWasCreatedEvent;
use Pulse\Bus\Commands\User\CreateUserCommand;

class CreateUserCommandHandler
{

    /**
     * Handle the Create User Command
     *
     * @param  Pulse\Bus\Commands\User\CreateUserCommand $command
     * @return Pulse\Models\User
     */
    public function handle(CreateUserCommand $command)
    {
        //Create User
        $user = User::create($command->getUserData());

        //Fire the UserWasCreatedEvent
        event(new UserWasCreatedEvent($user));

        //Return the created user
        return $user;
    }

}