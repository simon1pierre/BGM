<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Notifications\SystemTestEmailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class SettingsController extends Controller
{
    public function edit()
    {
        $settings = Setting::current();

        if (!$settings) {
            $settings = new Setting([
                'site_name' => config('app.name'),
                'site_tagline' => null,
                'site_description' => null,
                'contact_email' => config('mail.from.address'),
                'contact_phone' => null,
                'contact_address' => null,
                'youtube_channel' => null,
                'facebook_url' => null,
                'instagram_url' => null,
                'notifications_email' => config('mail.from.address'),
                'notifications_enabled' => true,
                'home_featured_video_limit' => 3,
                'home_recommended_books_limit' => 6,
                'home_recommended_audios_limit' => 6,
                'home_recommended_enabled' => true,
                'per_page_default' => 9,
                'auto_publish' => false,
                'comments_auto_approve' => true,
                'comments_require_name' => false,
                'comments_require_email' => false,
                'analytics_enabled' => true,
                'analytics_retention_days' => 365,
                'analytics_anonymize_ip' => false,
                'maintenance_mode' => false,
                'maintenance_message' => null,
                'force_admin_2fa' => false,
                'session_timeout_minutes' => 120,
                'mail_mailer' => config('mail.default'),
                'mail_host' => config('mail.mailers.smtp.host'),
                'mail_port' => config('mail.mailers.smtp.port'),
                'mail_username' => config('mail.mailers.smtp.username'),
                'mail_password' => null,
                'mail_scheme' => config('mail.mailers.smtp.scheme'),
                'mail_from_address' => config('mail.from.address'),
                'mail_from_name' => config('mail.from.name'),
            ]);
        }

        return view('Admin.Settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'site_tagline' => ['nullable', 'string', 'max:255'],
            'site_description' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'max:4096'],
            'favicon' => ['nullable', 'image', 'max:1024'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'contact_address' => ['nullable', 'string', 'max:255'],
            'youtube_channel' => ['nullable', 'url', 'max:255'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'notifications_email' => ['nullable', 'email', 'max:255'],
            'notifications_enabled' => ['nullable', 'boolean'],
            'footer_text' => ['nullable', 'string', 'max:255'],
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_subtitle' => ['nullable', 'string'],
            'hero_primary_label' => ['nullable', 'string', 'max:100'],
            'hero_primary_url' => ['nullable', 'string', 'max:255'],
            'hero_secondary_label' => ['nullable', 'string', 'max:100'],
            'hero_secondary_url' => ['nullable', 'string', 'max:255'],
            'home_featured_video_limit' => ['nullable', 'integer', 'min:1', 'max:12'],
            'home_recommended_books_limit' => ['nullable', 'integer', 'min:1', 'max:12'],
            'home_recommended_audios_limit' => ['nullable', 'integer', 'min:1', 'max:12'],
            'home_recommended_enabled' => ['nullable', 'boolean'],
            'per_page_default' => ['nullable', 'integer', 'min:2', 'max:50'],
            'auto_publish' => ['nullable', 'boolean'],
            'default_category_video_id' => ['nullable', 'integer'],
            'default_category_audio_id' => ['nullable', 'integer'],
            'default_category_document_id' => ['nullable', 'integer'],
            'comments_auto_approve' => ['nullable', 'boolean'],
            'comments_require_name' => ['nullable', 'boolean'],
            'comments_require_email' => ['nullable', 'boolean'],
            'moderation_block_words' => ['nullable', 'string'],
            'analytics_enabled' => ['nullable', 'boolean'],
            'analytics_retention_days' => ['nullable', 'integer', 'min:1', 'max:3650'],
            'analytics_anonymize_ip' => ['nullable', 'boolean'],
            'maintenance_mode' => ['nullable', 'boolean'],
            'maintenance_message' => ['nullable', 'string', 'max:255'],
            'force_admin_2fa' => ['nullable', 'boolean'],
            'session_timeout_minutes' => ['nullable', 'integer', 'min:5', 'max:1440'],
            'mail_mailer' => ['nullable', 'string', 'max:50'],
            'mail_host' => ['nullable', 'string', 'max:255'],
            'mail_port' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'mail_username' => ['nullable', 'string', 'max:255'],
            'mail_password' => ['nullable', 'string', 'max:255'],
            'mail_scheme' => ['nullable', 'string', 'max:50'],
            'mail_from_address' => ['nullable', 'email', 'max:255'],
            'mail_from_name' => ['nullable', 'string', 'max:255'],
        ]);

        $settings = Setting::current() ?? new Setting();

        $validated['notifications_enabled'] = (bool) ($validated['notifications_enabled'] ?? false);
        $validated['home_recommended_enabled'] = (bool) ($validated['home_recommended_enabled'] ?? false);
        $validated['auto_publish'] = (bool) ($validated['auto_publish'] ?? false);
        $validated['comments_auto_approve'] = (bool) ($validated['comments_auto_approve'] ?? false);
        $validated['comments_require_name'] = (bool) ($validated['comments_require_name'] ?? false);
        $validated['comments_require_email'] = (bool) ($validated['comments_require_email'] ?? false);
        $validated['analytics_enabled'] = (bool) ($validated['analytics_enabled'] ?? false);
        $validated['analytics_anonymize_ip'] = (bool) ($validated['analytics_anonymize_ip'] ?? false);
        $validated['maintenance_mode'] = (bool) ($validated['maintenance_mode'] ?? false);
        $validated['force_admin_2fa'] = (bool) ($validated['force_admin_2fa'] ?? false);

        if (!empty($validated['mail_scheme'])) {
            $scheme = strtolower($validated['mail_scheme']);
            if ($scheme === 'tls') {
                $validated['mail_scheme'] = 'smtp';
            } elseif ($scheme === 'ssl') {
                $validated['mail_scheme'] = 'smtps';
            }
        }

        if (blank($validated['mail_password'] ?? null)) {
            unset($validated['mail_password']);
        }

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('settings', 'public');
        }
        if ($request->hasFile('favicon')) {
            $validated['favicon'] = $request->file('favicon')->store('settings', 'public');
        }

        $settings->fill($validated);
        $settings->save();

        return redirect()->route('admin.settings.edit')->with('status', 'Settings updated.');
    }

    public function testEmail()
    {
        $settings = Setting::current();
        $to = $settings?->notifications_email
            ?: $settings?->contact_email
            ?: config('mail.from.address');

        if (!$to) {
            return redirect()->route('admin.settings.edit')->with('status', 'No notification email is configured.');
        }

        Notification::route('mail', $to)->notify(new SystemTestEmailNotification());

        return redirect()->route('admin.settings.edit')->with('status', 'Test email sent.');
    }
}
