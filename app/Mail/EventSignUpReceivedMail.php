<?php

namespace App\Mail;

use App\Events\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventSignUpReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $request;
    protected Event $event;

    /**
     * Create a new message instance.
     *
     * EventMail constructor.
     * @param $request
     * @param  Event  $event
     */
    public function __construct($request, Event $event)
    {
        $this->request = $request;
        $this->event = $event;

        // Textarea should be converted with nl2br
        if(isset($this->request['formMessage'])) $this->request['formMessage'] = nl2br($this->request['formMessage']);

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.eventSignUpReceived')
            ->subject(__('email.eventSignUp.subject.received'))
            ->to(config('site.mailTo'))
            ->with([
                'request' => $this->request,
                'event' => $this->event,
                'subject' => __('email.eventSignUp.subject.received'),
            ]);
    }
}
