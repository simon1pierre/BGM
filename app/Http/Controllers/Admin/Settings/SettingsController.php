<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\SettingTranslation;
use App\Notifications\SystemTestEmailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class SettingsController extends Controller
{
    public function edit()
    {
        $settings = Setting::currentOrDefault();

        return view('Admin.Settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'site_tagline' => ['nullable', 'string', 'max:255'],
            'site_description' => ['nullable', 'string'],
            'site_name_en' => ['nullable', 'string', 'max:255'],
            'site_name_fr' => ['nullable', 'string', 'max:255'],
            'site_name_rw' => ['nullable', 'string', 'max:255'],
            'site_tagline_en' => ['nullable', 'string', 'max:255'],
            'site_tagline_fr' => ['nullable', 'string', 'max:255'],
            'site_tagline_rw' => ['nullable', 'string', 'max:255'],
            'site_description_en' => ['nullable', 'string'],
            'site_description_fr' => ['nullable', 'string'],
            'site_description_rw' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'max:4096'],
            'favicon' => ['nullable', 'image', 'max:1024'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'contact_address' => ['nullable', 'string', 'max:255'],
            'youtube_channel' => ['nullable', 'string', 'max:255'],
            'facebook_url' => ['nullable', 'string', 'max:255'],
            'instagram_url' => ['nullable', 'string', 'max:255'],
            'twitter_url' => ['nullable', 'string', 'max:255'],
            'tiktok_url' => ['nullable', 'string', 'max:255'],
            'whatsapp_url' => ['nullable', 'string', 'max:255'],
            'telegram_url' => ['nullable', 'string', 'max:255'],
            'live_chat_enabled' => ['nullable', 'boolean'],
            'tawk_property_id' => ['nullable', 'string', 'max:120'],
            'tawk_widget_id' => ['nullable', 'string', 'max:120'],
            'notifications_email' => ['nullable', 'email', 'max:255'],
            'notifications_enabled' => ['nullable', 'boolean'],
            'footer_text' => ['nullable', 'string', 'max:255'],
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_subtitle' => ['nullable', 'string'],
            'hero_primary_label' => ['nullable', 'string', 'max:100'],
            'hero_primary_url' => ['nullable', 'string', 'max:255'],
            'hero_secondary_label' => ['nullable', 'string', 'max:100'],
            'hero_secondary_url' => ['nullable', 'string', 'max:255'],
            'about_title' => ['nullable', 'string', 'max:255'],
            'about_subtitle' => ['nullable', 'string'],
            'about_body' => ['nullable', 'string'],
            'about_mission_title' => ['nullable', 'string', 'max:255'],
            'about_mission_body' => ['nullable', 'string'],
            'about_vision_title' => ['nullable', 'string', 'max:255'],
            'about_vision_body' => ['nullable', 'string'],
            'about_values' => ['nullable', 'string'],
            'resources_title' => ['nullable', 'string', 'max:255'],
            'resources_subtitle' => ['nullable', 'string'],
            'resources_cta_label' => ['nullable', 'string', 'max:100'],
            'resources_cta_url' => ['nullable', 'string', 'max:255'],
            'contact_title' => ['nullable', 'string', 'max:255'],
            'contact_subtitle' => ['nullable', 'string'],
            'contact_form_intro' => ['nullable', 'string'],
            'events_title' => ['nullable', 'string', 'max:255'],
            'events_subtitle' => ['nullable', 'string'],
            'events_feature_title' => ['nullable', 'string', 'max:255'],
            'events_feature_date' => ['nullable', 'string', 'max:255'],
            'events_feature_location' => ['nullable', 'string', 'max:255'],
            'events_feature_body' => ['nullable', 'string'],
            'events_feature_url' => ['nullable', 'string', 'max:255'],
            'give_title' => ['nullable', 'string', 'max:255'],
            'give_subtitle' => ['nullable', 'string'],
            'give_cta_label' => ['nullable', 'string', 'max:100'],
            'give_cta_url' => ['nullable', 'string', 'max:255'],
            'footer_text_en' => ['nullable', 'string', 'max:255'],
            'footer_text_fr' => ['nullable', 'string', 'max:255'],
            'footer_text_rw' => ['nullable', 'string', 'max:255'],
            'hero_title_en' => ['nullable', 'string', 'max:255'],
            'hero_title_fr' => ['nullable', 'string', 'max:255'],
            'hero_title_rw' => ['nullable', 'string', 'max:255'],
            'hero_subtitle_en' => ['nullable', 'string'],
            'hero_subtitle_fr' => ['nullable', 'string'],
            'hero_subtitle_rw' => ['nullable', 'string'],
            'hero_primary_label_en' => ['nullable', 'string', 'max:100'],
            'hero_primary_label_fr' => ['nullable', 'string', 'max:100'],
            'hero_primary_label_rw' => ['nullable', 'string', 'max:100'],
            'hero_secondary_label_en' => ['nullable', 'string', 'max:100'],
            'hero_secondary_label_fr' => ['nullable', 'string', 'max:100'],
            'hero_secondary_label_rw' => ['nullable', 'string', 'max:100'],
            'about_title_en' => ['nullable', 'string', 'max:255'],
            'about_title_fr' => ['nullable', 'string', 'max:255'],
            'about_title_rw' => ['nullable', 'string', 'max:255'],
            'about_subtitle_en' => ['nullable', 'string'],
            'about_subtitle_fr' => ['nullable', 'string'],
            'about_subtitle_rw' => ['nullable', 'string'],
            'about_body_en' => ['nullable', 'string'],
            'about_body_fr' => ['nullable', 'string'],
            'about_body_rw' => ['nullable', 'string'],
            'about_mission_title_en' => ['nullable', 'string', 'max:255'],
            'about_mission_title_fr' => ['nullable', 'string', 'max:255'],
            'about_mission_title_rw' => ['nullable', 'string', 'max:255'],
            'about_mission_body_en' => ['nullable', 'string'],
            'about_mission_body_fr' => ['nullable', 'string'],
            'about_mission_body_rw' => ['nullable', 'string'],
            'about_vision_title_en' => ['nullable', 'string', 'max:255'],
            'about_vision_title_fr' => ['nullable', 'string', 'max:255'],
            'about_vision_title_rw' => ['nullable', 'string', 'max:255'],
            'about_vision_body_en' => ['nullable', 'string'],
            'about_vision_body_fr' => ['nullable', 'string'],
            'about_vision_body_rw' => ['nullable', 'string'],
            'about_values_en' => ['nullable', 'string'],
            'about_values_fr' => ['nullable', 'string'],
            'about_values_rw' => ['nullable', 'string'],
            'resources_title_en' => ['nullable', 'string', 'max:255'],
            'resources_title_fr' => ['nullable', 'string', 'max:255'],
            'resources_title_rw' => ['nullable', 'string', 'max:255'],
            'resources_subtitle_en' => ['nullable', 'string'],
            'resources_subtitle_fr' => ['nullable', 'string'],
            'resources_subtitle_rw' => ['nullable', 'string'],
            'resources_cta_label_en' => ['nullable', 'string', 'max:100'],
            'resources_cta_label_fr' => ['nullable', 'string', 'max:100'],
            'resources_cta_label_rw' => ['nullable', 'string', 'max:100'],
            'contact_title_en' => ['nullable', 'string', 'max:255'],
            'contact_title_fr' => ['nullable', 'string', 'max:255'],
            'contact_title_rw' => ['nullable', 'string', 'max:255'],
            'contact_subtitle_en' => ['nullable', 'string'],
            'contact_subtitle_fr' => ['nullable', 'string'],
            'contact_subtitle_rw' => ['nullable', 'string'],
            'contact_form_intro_en' => ['nullable', 'string'],
            'contact_form_intro_fr' => ['nullable', 'string'],
            'contact_form_intro_rw' => ['nullable', 'string'],
            'events_title_en' => ['nullable', 'string', 'max:255'],
            'events_title_fr' => ['nullable', 'string', 'max:255'],
            'events_title_rw' => ['nullable', 'string', 'max:255'],
            'events_subtitle_en' => ['nullable', 'string'],
            'events_subtitle_fr' => ['nullable', 'string'],
            'events_subtitle_rw' => ['nullable', 'string'],
            'events_feature_title_en' => ['nullable', 'string', 'max:255'],
            'events_feature_title_fr' => ['nullable', 'string', 'max:255'],
            'events_feature_title_rw' => ['nullable', 'string', 'max:255'],
            'events_feature_date_en' => ['nullable', 'string', 'max:255'],
            'events_feature_date_fr' => ['nullable', 'string', 'max:255'],
            'events_feature_date_rw' => ['nullable', 'string', 'max:255'],
            'events_feature_location_en' => ['nullable', 'string', 'max:255'],
            'events_feature_location_fr' => ['nullable', 'string', 'max:255'],
            'events_feature_location_rw' => ['nullable', 'string', 'max:255'],
            'events_feature_body_en' => ['nullable', 'string'],
            'events_feature_body_fr' => ['nullable', 'string'],
            'events_feature_body_rw' => ['nullable', 'string'],
            'give_title_en' => ['nullable', 'string', 'max:255'],
            'give_title_fr' => ['nullable', 'string', 'max:255'],
            'give_title_rw' => ['nullable', 'string', 'max:255'],
            'give_subtitle_en' => ['nullable', 'string'],
            'give_subtitle_fr' => ['nullable', 'string'],
            'give_subtitle_rw' => ['nullable', 'string'],
            'give_cta_label_en' => ['nullable', 'string', 'max:100'],
            'give_cta_label_fr' => ['nullable', 'string', 'max:100'],
            'give_cta_label_rw' => ['nullable', 'string', 'max:100'],
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
        $validated['live_chat_enabled'] = (bool) ($validated['live_chat_enabled'] ?? false);

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

        $this->syncTranslations($settings, $request);

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

    private function syncTranslations(Setting $settings, Request $request): void
    {
        $locales = ['en', 'fr', 'rw'];

        foreach ($locales as $locale) {
            $payload = [
                'site_name' => $request->input("site_name_{$locale}"),
                'site_tagline' => $request->input("site_tagline_{$locale}"),
                'site_description' => $request->input("site_description_{$locale}"),
                'footer_text' => $request->input("footer_text_{$locale}"),
                'hero_title' => $request->input("hero_title_{$locale}"),
                'hero_subtitle' => $request->input("hero_subtitle_{$locale}"),
                'hero_primary_label' => $request->input("hero_primary_label_{$locale}"),
                'hero_secondary_label' => $request->input("hero_secondary_label_{$locale}"),
                'about_title' => $request->input("about_title_{$locale}"),
                'about_subtitle' => $request->input("about_subtitle_{$locale}"),
                'about_body' => $request->input("about_body_{$locale}"),
                'about_mission_title' => $request->input("about_mission_title_{$locale}"),
                'about_mission_body' => $request->input("about_mission_body_{$locale}"),
                'about_vision_title' => $request->input("about_vision_title_{$locale}"),
                'about_vision_body' => $request->input("about_vision_body_{$locale}"),
                'about_values' => $request->input("about_values_{$locale}"),
                'resources_title' => $request->input("resources_title_{$locale}"),
                'resources_subtitle' => $request->input("resources_subtitle_{$locale}"),
                'resources_cta_label' => $request->input("resources_cta_label_{$locale}"),
                'contact_title' => $request->input("contact_title_{$locale}"),
                'contact_subtitle' => $request->input("contact_subtitle_{$locale}"),
                'contact_form_intro' => $request->input("contact_form_intro_{$locale}"),
                'events_title' => $request->input("events_title_{$locale}"),
                'events_subtitle' => $request->input("events_subtitle_{$locale}"),
                'events_feature_title' => $request->input("events_feature_title_{$locale}"),
                'events_feature_date' => $request->input("events_feature_date_{$locale}"),
                'events_feature_location' => $request->input("events_feature_location_{$locale}"),
                'events_feature_body' => $request->input("events_feature_body_{$locale}"),
                'give_title' => $request->input("give_title_{$locale}"),
                'give_subtitle' => $request->input("give_subtitle_{$locale}"),
                'give_cta_label' => $request->input("give_cta_label_{$locale}"),
            ];

            $hasAny = collect($payload)->filter(fn ($value) => filled($value))->isNotEmpty();

            if (!$hasAny) {
                SettingTranslation::query()
                    ->where('setting_id', $settings->id)
                    ->where('locale', $locale)
                    ->delete();
                continue;
            }

            SettingTranslation::updateOrCreate(
                [
                    'setting_id' => $settings->id,
                    'locale' => $locale,
                ],
                $payload
            );
        }
    }
}


