<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Schedule;
use App\\Models\\EmailCampaign;
use App\Jobs\SendEmailCampaignJob;
use Illuminate\Support\Facades\Artisan;
use App\\Models\\Audio;
use App\\Models\\Audiobook;
use App\\Models\\Book;
use App\\Models\\Video;
use App\Services\Translation\ContentTranslationPipeline;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('translations:auto-fill {type=all}', function (ContentTranslationPipeline $pipeline): void {
    $type = (string) $this->argument('type');

    $map = [
        'videos' => Video::class,
        'audios' => Audio::class,
        'audiobooks' => Audiobook::class,
        'books' => Book::class,
    ];

    $targets = $type === 'all' ? $map : [$type => $map[$type] ?? null];
    if (in_array(null, $targets, true)) {
        $this->error('Unknown type. Use one of: all, videos, audios, audiobooks, books');
        return;
    }

    foreach ($targets as $label => $class) {
        $count = 0;
        $class::query()->chunkById(100, function ($rows) use ($pipeline, &$count) {
            foreach ($rows as $row) {
                $pipeline->autoFillMissingTranslations($row, ['title', 'description']);
                $count++;
            }
        });

        $this->info("Auto-fill completed for {$label}: {$count} records");
    }
})->purpose('Auto-fill missing content translations using configured translation pipeline');

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

