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
            'user_created' => 'Account registered!!',
            'user_updated' => 'Account updated!!',
            'user_status_toggled' => 'User status changed',
            'user_deleted' => 'Account removed!!',
            'user_restored' => 'Account restored!!',
            'password_reset' => 'Password changed!!',
            'email_verified' => 'Email verified!!',
            'login_success' => 'Successful login!!',
            'login_failed' => 'Failed login attempt!!',
            'security_issue' => 'Security issue detected!!',
            'subscriber_created' => 'new Subscriber!!',
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








