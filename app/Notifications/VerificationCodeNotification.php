<?php

namespace App\Notifications;

use App\Models\VerificationCode;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerificationCodeNotification extends Notification
{
    public function __construct(private VerificationCode $verification)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $title = $this->verification->purpose === 'login'
            ? 'Your login verification code'
            : 'Verify your email address';

        return (new MailMessage())
            ->subject($title.' | '.config('app.name'))
            ->view('emails.verification-code', [
                'title' => $title,
                'code' => $this->verification->code,
                'expiresAt' => $this->verification->expires_at,
            ]);
    }
}








