<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContentNotificationMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $payload)
    {
    }

    public function build()
    {
        return $this->subject($this->payload['subject'])
            ->view('emails.content-notification', $this->payload);
    }
}


