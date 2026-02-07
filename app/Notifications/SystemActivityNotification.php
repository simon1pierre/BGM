<?php

namespace App\Notifications;

use App\Models\UserActivityLog;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SystemActivityNotification extends Notification
{
    use Queueable;

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

        return (new MailMessage())
            ->subject('System Activity: '.$this->activity->action)
            ->line('Action: '.$this->activity->action)
            ->line('Actor: '.$actor)
            ->line('Time: '.$this->activity->created_at?->toDateTimeString())
            ->line('Details: '.json_encode($this->activity->meta ?? [], JSON_UNESCAPED_SLASHES));
    }
}
