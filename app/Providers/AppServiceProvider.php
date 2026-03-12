<?php

namespace App\Providers;

use App\Models\Setting;
use App\\Models\\Subscriber;
use App\Models\ContactMessage;
use App\Models\Event;
use App\\Models\\EmailCampaign;
use App\Models\MinistryLeader;
use App\Models\User;
use App\\Models\\Video;
use App\\Models\\Audio;
use App\\Models\\Audiobook;
use App\\Models\\Book;
use App\Models\ContentCategory;
use App\Models\Playlist;
use App\Models\VideoSeries;
use App\Models\Devotional;
use App\Models\UserActivityLog;
use App\Notifications\SystemActivityNotification;
use App\Services\Translation\LibreTranslateTranslator;
use App\Services\Translation\NullTranslator;
use App\Services\Translation\TranslatorInterface;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TranslatorInterface::class, function () {
            $provider = (string) config('translation_pipeline.provider', 'null');

            if ($provider === 'libretranslate') {
                return new LibreTranslateTranslator();
            }

            return new NullTranslator();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        
        $settings = null;

        if (Schema::hasTable('settings')) {
            $settings = Setting::currentOrDefault();

            if ($settings) {
                Config::set('app.name', $settings->site_name ?: config('app.name'));

                if ($settings->mail_mailer) {
                    Config::set('mail.default', $settings->mail_mailer);
                }

                if ($settings->mail_host) {
                    Config::set('mail.mailers.smtp.host', $settings->mail_host);
                }

                if ($settings->mail_port) {
                    Config::set('mail.mailers.smtp.port', $settings->mail_port);
                }

                if ($settings->mail_username) {
                    Config::set('mail.mailers.smtp.username', $settings->mail_username);
                }

                if ($settings->mail_password) {
                    Config::set('mail.mailers.smtp.password', $settings->mail_password);
                }

                if ($settings->mail_scheme) {
                    $scheme = strtolower($settings->mail_scheme);
                    if ($scheme === 'tls') {
                        $scheme = 'smtp';
                    } elseif ($scheme === 'ssl') {
                        $scheme = 'smtps';
                    }
                    Config::set('mail.mailers.smtp.scheme', $scheme);
                }

                if ($settings->mail_from_address) {
                    Config::set('mail.from.address', $settings->mail_from_address);
                }

                if ($settings->mail_from_name) {
                    Config::set('mail.from.name', $settings->mail_from_name);
                }
            }
        }

        if (!$settings) {
            $settings = new Setting(Setting::defaults());
        }

        view()->share('siteSettings', $settings);

        View::composer('layouts.admin.partials.nav', function ($view): void {
            $counts = [
                'users' => 0,
                'campaigns' => 0,
                'videos' => 0,
                'audios' => 0,
                'audiobooks' => 0,
                'documents' => 0,
                'categories' => 0,
                'playlists' => 0,
                'video_series' => 0,
                'devotionals' => 0,
                'subscribers' => 0,
                'contacts_unread' => 0,
                'events' => 0,
                'ministry' => 0,
                'trash' => 0,
            ];

            if (Schema::hasTable('users')) {
                $counts['users'] = User::query()->count();
            }
            if (Schema::hasTable('email_campaigns')) {
                $counts['campaigns'] = EmailCampaign::query()->count();
            }
            if (Schema::hasTable('videos')) {
                $counts['videos'] = Video::query()->count();
            }
            if (Schema::hasTable('audios')) {
                $counts['audios'] = Audio::query()->count();
            }
            if (Schema::hasTable('audiobooks')) {
                $counts['audiobooks'] = Audiobook::query()->count();
            }
            if (Schema::hasTable('books')) {
                $counts['documents'] = Book::query()->count();
            }
            if (Schema::hasTable('content_categories')) {
                $counts['categories'] = ContentCategory::query()->count();
            }
            if (Schema::hasTable('playlists')) {
                $counts['playlists'] = Playlist::query()->where('type', 'audio')->count();
            }
            if (Schema::hasTable('video_series')) {
                $counts['video_series'] = VideoSeries::query()->count();
            }
            if (Schema::hasTable('devotionals')) {
                $counts['devotionals'] = Devotional::query()->count();
            }
            if (Schema::hasTable('subscribers')) {
                $counts['subscribers'] = Subscriber::query()->count();
            }
            if (Schema::hasTable('contact_messages')) {
                $counts['contacts_unread'] = ContactMessage::query()->where('is_read', false)->count();
            }
            if (Schema::hasTable('events')) {
                $counts['events'] = Event::query()->count();
            }
            if (Schema::hasTable('ministry_leaders')) {
                $counts['ministry'] = MinistryLeader::query()->count();
            }

            $trashTotal = 0;
            if (Schema::hasTable('users') && Schema::hasColumn('users', 'deleted_at')) $trashTotal += User::onlyTrashed()->count();
            if (Schema::hasTable('videos') && Schema::hasColumn('videos', 'deleted_at')) $trashTotal += Video::onlyTrashed()->count();
            if (Schema::hasTable('audios') && Schema::hasColumn('audios', 'deleted_at')) $trashTotal += Audio::onlyTrashed()->count();
            if (Schema::hasTable('audiobooks') && Schema::hasColumn('audiobooks', 'deleted_at')) $trashTotal += Audiobook::onlyTrashed()->count();
            if (Schema::hasTable('books') && Schema::hasColumn('books', 'deleted_at')) $trashTotal += Book::onlyTrashed()->count();
            if (Schema::hasTable('content_categories') && Schema::hasColumn('content_categories', 'deleted_at')) $trashTotal += ContentCategory::onlyTrashed()->count();
            if (Schema::hasTable('playlists') && Schema::hasColumn('playlists', 'deleted_at')) $trashTotal += Playlist::onlyTrashed()->where('type', 'audio')->count();
            if (Schema::hasTable('video_series') && Schema::hasColumn('video_series', 'deleted_at')) $trashTotal += VideoSeries::onlyTrashed()->count();
            if (Schema::hasTable('devotionals') && Schema::hasColumn('devotionals', 'deleted_at')) $trashTotal += Devotional::onlyTrashed()->count();
            if (Schema::hasTable('subscribers') && Schema::hasColumn('subscribers', 'deleted_at')) $trashTotal += Subscriber::onlyTrashed()->count();
            if (Schema::hasTable('events') && Schema::hasColumn('events', 'deleted_at')) $trashTotal += Event::onlyTrashed()->count();
            if (Schema::hasTable('contact_messages') && Schema::hasColumn('contact_messages', 'deleted_at')) $trashTotal += ContactMessage::onlyTrashed()->count();
            if (Schema::hasTable('email_campaigns') && Schema::hasColumn('email_campaigns', 'deleted_at')) $trashTotal += EmailCampaign::onlyTrashed()->count();
            if (Schema::hasTable('ministry_leaders') && Schema::hasColumn('ministry_leaders', 'deleted_at')) $trashTotal += MinistryLeader::onlyTrashed()->count();
            $counts['trash'] = $trashTotal;

            $view->with('adminNavCounts', $counts);
        });

        if (Schema::hasTable('user_activity_logs')) {
            UserActivityLog::created(function (UserActivityLog $activity): void {
                $settings = Setting::current();
                if (!$settings?->notifications_enabled) {
                    return;
                }

                $notifiableActions = [
                    'email_verified',
                    'login_success',
                    'login_failed',
                    'security_issue',
                ];

                if (!in_array($activity->action, $notifiableActions, true)) {
                    return;
                }

                $to = $settings->notifications_email
                    ?: $settings->contact_email
                    ?: config('mail.from.address');

                if (!$to) {
                    return;
                }

                Notification::route('mail', $to)->notify(new SystemActivityNotification($activity));
            });
        }
    }
}


