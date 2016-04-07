<?php
namespace Pulse\Listeners\User;

use Illuminate\Mail\Message;
use Illuminate\Contracts\Mail\MailQueue;
use Illuminate\Queue\InteractsWithQueue;
use Pulse\Events\User\UserWasCreatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWelcomeEmailListener
{
    /**
     * MailQueue
     * @var Illuminate\Contracts\Mail\MailQueue
     */
    protected $mailer;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(MailQueue $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Handle the event.
     *
     * @param  UserWasCreatedEvent  $event
     * @return void
     */
    public function handle(UserWasCreatedEvent $event)
    {
        $user = $event->user;
        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'subject' => "Welcome to Pulse, {$user->name}!"
        ];

        $this->mailer->queue(
            'emails.user.welcome', $data, function (Message $message) use ($data) {
                $message->to($data['email'])->subject($data['subject']);
            });
    }
}
