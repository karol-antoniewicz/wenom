<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

/**
 * Notification class to handle password reset requests.
 * This class is responsible for generating and sending an email with a password reset link.
 */
class RequestPasswordNotification extends Notification
{
    use Queueable;

    /**
     * Constructor to initialize the notification with a token.
     *
     * @param string $token
     */
    public function __construct(public string $token) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return string[]
     */
    public function via(): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification
     *
     * @param User $notifiable
     * @return MailMessage
     */
    public function toMail(User $notifiable): MailMessage
    {
        // Generating a signed URL for password reset, valid for 15 minutes.
        $url = URL::temporarySignedRoute('request_password.reset_form', now()->addMinutes(15), [
            'email' => $notifiable->email,
            'token' => $this->token,
        ]);

        // Configuring the email message.
        return (new MailMessage)
			->subject(config('app.name') . ' Passwort angeben')
			->line('Sie haben für WeNoM - WebNotenManager ein neues Passwort angefordert. Klicken Sie auf diesen link, um das Passwort zu ändern:')
			->action('Neues Passwort eingeben', $url)
			->line('Dieser Link ist 15 Minuten gültig. Während dieser Zeit kann kein neues Passwort angefordert werden.')
			->line('Falls Sie nicht das neue Passwort angefordert haben, können Sie diese mail ignorieren.');
    }
}
