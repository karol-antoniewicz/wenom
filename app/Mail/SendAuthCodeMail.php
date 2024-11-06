<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Class SendAuthCodeMail is a Mailable class responsible for creating and sending an email with an authentication code.
 */
class SendAuthCodeMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Email details
     *
     * @var $details
     */
    public $details;

    /**
     * Create a new message instance.
     *
     * @param $details
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return SendAuthCodeMail
     */
    public function build(): SendAuthCodeMail
    {
        // Setting up the email with a subject and the view representing the email's content.
        return $this->subject('Mail from WeNoM')->view('emails.authCode');
    }
}
