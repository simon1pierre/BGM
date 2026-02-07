<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\UserActivityLog;
use App\Notifications\SystemActivityNotification;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (Schema::hasTable('settings')) {
            $settings = Setting::current();

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

        if (Schema::hasTable('user_activity_logs')) {
            UserActivityLog::created(function (UserActivityLog $activity): void {
                $settings = Setting::current();
                if (!$settings?->notifications_enabled) {
                    return;
                }

                $notifiableActions = [
                    'user_created',
                    'user_updated',
                    'user_status_toggled',
                    'user_deleted',
                    'user_restored',
                    'password_reset',
                    'email_verified',
                    'login_success',
                    'login_failed',
                    'security_issue',
                    'subscriber_created',
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
