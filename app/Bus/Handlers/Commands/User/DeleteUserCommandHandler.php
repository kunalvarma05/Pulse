<?php
namespace Pulse\Bus\Handlers\Commands\User;

use Pulse\Models\User;
use Pulse\Events\User\UserWasDeletedEvent;
use Pulse\Bus\Commands\User\DeleteUserCommand;

class DeleteUserCommandHandler
{

    /**
     * Handle the Delete User Command
     *
     * @param  Pulse\Bus\Commands\User\DeleteUserCommand $command
     * @return Pulse\Models\User
     */
    public function handle(DeleteUserCommand $command)
    {
        //User
        $user = $command->user;

        //Fire the UserWasDeletedEvent
        event(new UserWasDeletedEvent($user));

        //Delete the User
        $user->delete();
    }
}
