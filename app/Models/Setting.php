<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SettingTranslation;

class Setting extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'site_name',
        'site_tagline',
        'site_description',
        'primary_color',
        'secondary_color',
        'logo',
        'favicon',
        'youtube_channel',
        'facebook_url',
        'instagram_url',
        'contact_email',
        'contact_phone',
        'contact_address',
        'notifications_email',
        'notifications_enabled',
        'footer_text',
        'hero_title',
        'hero_subtitle',
        'hero_primary_label',
        'hero_primary_url',
        'hero_secondary_label',
        'hero_secondary_url',
        'home_featured_video_limit',
        'home_recommended_books_limit',
        'home_recommended_audios_limit',
        'home_recommended_enabled',
        'per_page_default',
        'auto_publish',
        'default_category_video_id',
        'default_category_audio_id',
        'default_category_document_id',
        'comments_auto_approve',
        'comments_require_name',
        'comments_require_email',
        'moderation_block_words',
        'analytics_enabled',
        'analytics_retention_days',
        'analytics_anonymize_ip',
        'maintenance_mode',
        'maintenance_message',
        'force_admin_2fa',
        'session_timeout_minutes',
        'mail_mailer',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_scheme',
        'mail_from_address',
        'mail_from_name',
    ];

    protected $casts = [
        'notifications_enabled' => 'boolean',
        'home_recommended_enabled' => 'boolean',
        'auto_publish' => 'boolean',
        'comments_auto_approve' => 'boolean',
        'comments_require_name' => 'boolean',
        'comments_require_email' => 'boolean',
        'analytics_enabled' => 'boolean',
        'analytics_anonymize_ip' => 'boolean',
        'maintenance_mode' => 'boolean',
        'force_admin_2fa' => 'boolean',
    ];

    public static function current(): ?self
    {
        return static::query()->latest('id')->first();
    }

    public static function currentOrDefault(): self
    {
        $current = static::current();
        $defaults = static::defaults();

        if (!$current) {
            return new static($defaults);
        }

        $data = array_merge($defaults, $current->toArray());
        return new static($data);
    }

    public static function defaults(): array
    {
        return [
            'site_name' => config('app.name'),
            'site_tagline' => null,
            'site_description' => null,
            'primary_color' => '#0f2b5e',
            'secondary_color' => '#f8fafc',
            'logo' => null,
            'favicon' => null,
            'youtube_channel' => null,
            'facebook_url' => null,
            'instagram_url' => null,
            'contact_email' => config('mail.from.address'),
            'contact_phone' => null,
            'contact_address' => null,
            'notifications_email' => config('mail.from.address'),
            'notifications_enabled' => true,
            'footer_text' => null,
            'hero_title' => null,
            'hero_subtitle' => null,
            'hero_primary_label' => null,
            'hero_primary_url' => null,
            'hero_secondary_label' => null,
            'hero_secondary_url' => null,
            'home_featured_video_limit' => 3,
            'home_recommended_books_limit' => 6,
            'home_recommended_audios_limit' => 6,
            'home_recommended_enabled' => true,
            'per_page_default' => 9,
            'auto_publish' => false,
            'default_category_video_id' => null,
            'default_category_audio_id' => null,
            'default_category_document_id' => null,
            'comments_auto_approve' => true,
            'comments_require_name' => false,
            'comments_require_email' => false,
            'moderation_block_words' => null,
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
        ];
    }

    public function translations()
    {
        return $this->hasMany(SettingTranslation::class);
    }

    public function translationFor(?string $locale = null): ?SettingTranslation
    {
        $locale = $locale ?: app()->getLocale();
        return $this->translations()->where('locale', $locale)->first();
    }

    public function translated(string $field): ?string
    {
        $translation = $this->translationFor();
        if ($translation && filled($translation->{$field})) {
            return $translation->{$field};
        }

        return $this->{$field};
    }
}
