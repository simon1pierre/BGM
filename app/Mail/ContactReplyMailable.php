<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactReplyMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $replySubject,
        public string $replyBody,
        public ContactMessage $contactMessage
    ) {
    }

    public function build()
    {
        return $this->subject($this->replySubject)
            ->view('emails.contact-reply', [
                'replySubject' => $this->replySubject,
                'replyBody' => $this->replyBody,
                'contactMessage' => $this->contactMessage,
            ]);
    }
}


