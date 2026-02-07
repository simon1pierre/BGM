<?php

namespace App\Jobs;

use App\Mail\EmailCampaignMailable;
use App\Models\EmailCampaign;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class SendEmailCampaignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private int $campaignId)
    {
    }

    public function handle(): void
    {
        $campaign = EmailCampaign::find($this->campaignId);
        if (!$campaign || $campaign->status === 'sent') {
            return;
        }

        Subscriber::query()
            ->where('is_active', true)
            ->whereNotNull('email')
            ->orderBy('id')
            ->chunkById(200, function ($subscribers) use ($campaign): void {
                foreach ($subscribers as $subscriber) {
                    Mail::to($subscriber->email)->send(new EmailCampaignMailable($campaign));
                }
            });

        $campaign->status = 'sent';
        $campaign->sent_at = now();
        $campaign->save();
    }
}
