<?php

namespace App\Notifications;

use App\\Models\\EmailCampaign;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailCampaignNotification extends Notification
{
    public function __construct(private EmailCampaign $campaign)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject($this->campaign->subject)
            ->line($this->campaign->message);
    }
}


