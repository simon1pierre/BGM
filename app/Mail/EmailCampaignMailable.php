<?php

namespace App\Mail;

use App\Models\EmailCampaign;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailCampaignMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public EmailCampaign $campaign)
    {
    }

    public function build()
    {
        return $this->subject($this->campaign->subject)
            ->view('emails.campaign', [
                'campaign' => $this->campaign,
            ]);
    }
}
