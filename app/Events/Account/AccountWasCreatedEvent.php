<?php
namespace Pulse\Events\Account;

use Pulse\Models\Account;
use Pulse\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AccountWasCreatedEvent extends Event
{
    use SerializesModels;

    /**
     * Account Model
     * @var Pulse\Models\Account
     */
    public $account;

    /**
     * Create a new event instance.
     *
     * @param Pulse/Models/Account $account Created Account
     * @return void
     */
    public function __construct(Account $account)
    {
        $this->account = $account;
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
