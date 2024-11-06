<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtpNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @var string $otp
     */
    public function __construct(private string $otp)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param object $notifiable
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param object $notifiable
     * @return MailMessage
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Ihr Einmalpasswort (OTP) für die Anmeldung')
            ->line('Sehr geehrter Kunde,')
            ->line("Ihr Einmalpasswort (OTP) für die Anmeldung lautet: {$this->otp}")
            ->line('Bitte geben Sie diesen Code innerhalb der nächsten 10 Minuten auf unserer Anmeldeseite ein, um fortzufahren.')
            ->line('Aus Sicherheitsgründen wird dieser Code nur einmalig verwendet und ist danach ungültig.')
            ->line('Sollten Sie diese Anfrage nicht gestellt haben, ignorieren Sie bitte diese E-Mail und setzen Sie sich umgehend mit unserem Kundenservice in Verbindung.');
    }
}
