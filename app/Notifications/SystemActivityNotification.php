<?php

namespace App\Notifications;

use App\Models\UserActivityLog;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SystemActivityNotification extends Notification
{
    public function __construct(private UserActivityLog $activity)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $actor = $this->activity->actor_user_id
            ? optional($this->activity->actorUser)->email ?? 'User #'.$this->activity->actor_user_id
            : 'Guest';

        $labels = [
            'user_created' => 'New user account created',
            'user_updated' => 'User account updated',
            'user_status_toggled' => 'User status changed',
            'user_deleted' => 'User account deleted',
            'user_restored' => 'User account restored',
            'password_reset' => 'Password reset',
            'email_verified' => 'Email verified',
            'login_success' => 'Successful login',
            'login_failed' => 'Failed login attempt',
            'security_issue' => 'Security issue detected',
            'subscriber_created' => 'New newsletter subscriber',
        ];

        $title = $labels[$this->activity->action] ?? 'System activity';

        return (new MailMessage())
            ->subject($title.' | '.config('app.name'))
            ->view('emails.system-activity', [
                'title' => $title,
                'actor' => $actor,
                'time' => $this->activity->created_at?->toDateTimeString(),
                'action' => $this->activity->action,
                'meta' => $this->activity->meta ?? [],
            ]);
    }
}
