<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Mail\Mailer as MailerContract;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class configurableMailable
 *
 * A mailable you can extend that allows a different mail server configuration
 *
 * @package App\Mail
 */
abstract class ConfigurableMailable extends Mailable
{
    use Queueable, SerializesModels;

    /** @var array $originalMailConfig */
    private $originalMailConfig;

    /** @var array $mailConfigToUse */
    private $mailConfigToUse;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->originalMailConfig = config('mail');
        $this->mailConfigToUse = $this->originalMailConfig;
    }

    /**
     * Use other mail configuration for this mail.
     *
     * @param string $mailConfigFileName
     * @return $this
     */
    public function useMailConfigFrom($mailConfigFileName = 'mail')
    {
        $newConfig = config($mailConfigFileName);
        if(!is_array($newConfig)) {
            //We could not throw an exception safely enough right here, because that would lead to infinite loops when the exception handler tries to use this.
            \Log::error(get_class().':useMailConfigFrom Could not use the mail config from a file called "'.$mailConfigFileName.'" since that file does not return an array.');
            return $this;
        }

        $this->mailConfigToUse = $newConfig;
        return $this;
    }

    /**
     * @param MailerContract $mailer
     */
    public function send(MailerContract $mailer)
    {
        if(!$this->mailConfigToUse && !$this->originalMailConfig) {
            //We could not send the mail safely because that would break the config. We are not throwing an exception to prevent infinite loops when this mail is used in the exception handler.
            \Log::error(get_class().':send Could not send a mail because the constructor of "'.get_class().'" was not called in the constructor of "'.get_class($this).'"');
            return;
        }

        \Config::set('mail', $this->mailConfigToUse);
        parent::send($mailer);
        \Config::set('mail', $this->originalMailConfig);
    }
}
