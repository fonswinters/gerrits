<?php

namespace App\Mail;

use App\Servicepoints\Models\Servicepoint;
use App\Vacancies\Models\Vacancy;
use Illuminate\Bus\Queueable;
use Illuminate\Http\UploadedFile;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VacancyMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $request;

    /** @var UploadedFile[] $files */
    protected ?array $files;

    private Vacancy $vacancy;

    /**
     * Create a new message instance.
     *
     * @param         $request
     * @param         $files
     * @param Vacancy $vacancy
     */
    public function __construct($request, $files, Vacancy $vacancy)
    {
        $this->request = $request;
        $this->files = $files;
        $this->vacancy = $vacancy;

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

        $mail = $this->view('emails.vacancy')
            ->subject(__('email.vacancy.subject'))
            ->to($mail)
            ->with(['request' => $this->request]);

        if(is_array($this->files)) {
            foreach ($this->files as $file) {
                $mail->attachData($file->get(), $file->getClientOriginalName());
            }
        }

        return $mail;
    }
}
