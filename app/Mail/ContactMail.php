<?php

namespace App\Mail;

use App\Servicepoints\Models\Servicepoint;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Response;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $request;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;

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
        $mail = config('site.contact_form_mail');
        if(empty($mail)) $mail = config('site.mailTo', 'support@komma.nl');

        return $this->view('emails.contact')
            ->subject(__('email.contact.subject'))
            ->to($mail)
            ->with(['request' => $this->request]);
    }
}
