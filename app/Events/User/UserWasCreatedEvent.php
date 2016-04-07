<?php
namespace Pulse\Events\User;

use Pulse\Models\User;
use Pulse\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserWasCreatedEvent extends Event
{
    use SerializesModels;

    /**
     * User Model
     * @var Pulse\Models\User
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param Pulse/Models/User $user Created User
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
