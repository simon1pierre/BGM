<?php

namespace App\Jobs;

use App\Mail\ContentNotificationMailable;
use App\\Models\\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendContentNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private array $payload, private array $emails, private bool $isBulk = true)
    {
    }

    public function handle(): void
    {
        if ($this->isBulk) {
            Subscriber::query()
                ->where('is_active', true)
                ->whereNotNull('email')
                ->orderBy('id')
                ->chunkById(200, function ($subscribers): void {
                    foreach ($subscribers as $subscriber) {
                        Mail::to($subscriber->email)->send(new ContentNotificationMailable($this->payload));
                    }
                });

            return;
        }

        foreach ($this->emails as $email) {
            Mail::to($email)->send(new ContentNotificationMailable($this->payload));
        }
    }
}


