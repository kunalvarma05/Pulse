<?php
namespace Pulse\Bus\Commands\User;

use Pulse\Models\User;

class DeleteUserCommand
{
    /**
     * User to be deleted
     *
     * @var Pulse\Models\User
     */
    public $user;


    /**
     * Create a new DeleteUserCommand instance
     *
     * @param Pulse\Models\User $user
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}