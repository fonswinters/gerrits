<?php

namespace App\Mail;

use App\Events\Models\Event;
use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event as CalenderEvent;

class EventSignUpResponseMail extends Mailable
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
        $subject = __('email.eventSignUp.subject.response') . ' ' . $this->event->translation->name;

        $calendarData = Calendar::create()
            ->event(CalenderEvent::create($this->event->translation->name)
                ->uniqueIdentifier($this->event->translation->event_id)
                ->name($this->event->translation->name .' - '. config('site.company_name'))
                ->startsAt(new DateTime($this->event->datetime_start))
                ->endsAt(new DateTime($this->event->datetime_end))
                ->address($this->event->translation->location)
                ->addressName($this->event->translation->location)
                ->description($this->event->translation->description)
            )
            ->get();

        return $this->view('emails.eventSignUpResponse')
            ->subject($subject)
            ->attachData($calendarData, 'calendar.ics', [
                'mime' => 'text/calendar;charset=UTF-8;method=REQUEST',
            ])
            ->from(config('mail.from.address'), config('site.company_name'))
            ->to($this->request['email'])
            ->with([
                'request' => $this->request,
                'event' => $this->event,
                'subject' => $subject,
            ]);
    }
}
