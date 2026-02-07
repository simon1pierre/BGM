<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Schedule;
use App\Models\EmailCampaign;
use App\Jobs\SendEmailCampaignJob;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    EmailCampaign::query()
        ->where('status', 'scheduled')
        ->whereNotNull('scheduled_at')
        ->where('scheduled_at', '<=', now())
        ->whereNull('sent_at')
        ->orderBy('id')
        ->each(function (EmailCampaign $campaign): void {
            SendEmailCampaignJob::dispatch($campaign->id);
        });
})->everyMinute();
