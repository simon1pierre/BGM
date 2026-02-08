<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
