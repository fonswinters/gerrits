<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSending;

/**
 * Class SendingMessageToSession
 *
 * Puts mails in the session, used for testing purposes in E2E tests.
 *
 * @package App\Listeners
 */
class MessageToSession
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param MessageSending $event
     * @return void
     */
    public function handle(MessageSending $event)
    {
        if(!session()->pull('enable_mail_intercepts')) return;

        $messageAsArray = [
            'from'       => $event->message->getFrom(),
            'to'         => $event->message->getTo(),
            'subject'    => $event->message->getSubject(),
            'body'       => $event->message->getBody(),
            'data'       => $event->data,
        ];

        session()->push('e2e_mails', $messageAsArray);
    }
}
