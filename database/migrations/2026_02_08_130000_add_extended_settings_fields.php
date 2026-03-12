<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'site_tagline')) {
                $table->string('site_tagline')->nullable()->after('site_name');
            }
            if (!Schema::hasColumn('settings', 'site_description')) {
                $table->text('site_description')->nullable()->after('site_tagline');
            }
            if (!Schema::hasColumn('settings', 'contact_phone')) {
                $table->string('contact_phone')->nullable()->after('contact_email');
            }
            if (!Schema::hasColumn('settings', 'contact_address')) {
                $table->string('contact_address')->nullable()->after('contact_phone');
            }
            if (!Schema::hasColumn('settings', 'facebook_url')) {
                $table->string('facebook_url')->nullable()->after('youtube_channel');
            }
            if (!Schema::hasColumn('settings', 'instagram_url')) {
                $table->string('instagram_url')->nullable()->after('facebook_url');
            }
            if (!Schema::hasColumn('settings', 'logo')) {
                $table->string('logo')->nullable()->after('secondary_color');
            }
            if (!Schema::hasColumn('settings', 'favicon')) {
                $table->string('favicon')->nullable()->after('logo');
            }
            if (!Schema::hasColumn('settings', 'footer_text')) {
                $table->string('footer_text')->nullable()->after('favicon');
            }
            if (!Schema::hasColumn('settings', 'hero_title')) {
                $table->string('hero_title')->nullable()->after('footer_text');
            }
            if (!Schema::hasColumn('settings', 'hero_subtitle')) {
                $table->text('hero_subtitle')->nullable()->after('hero_title');
            }
            if (!Schema::hasColumn('settings', 'hero_primary_label')) {
                $table->string('hero_primary_label')->nullable()->after('hero_subtitle');
            }
            if (!Schema::hasColumn('settings', 'hero_primary_url')) {
                $table->string('hero_primary_url')->nullable()->after('hero_primary_label');
            }
            if (!Schema::hasColumn('settings', 'hero_secondary_label')) {
                $table->string('hero_secondary_label')->nullable()->after('hero_primary_url');
            }
            if (!Schema::hasColumn('settings', 'hero_secondary_url')) {
                $table->string('hero_secondary_url')->nullable()->after('hero_secondary_label');
            }
            if (!Schema::hasColumn('settings', 'home_featured_video_limit')) {
                $table->unsignedInteger('home_featured_video_limit')->default(3)->after('hero_secondary_url');
            }
            if (!Schema::hasColumn('settings', 'home_recommended_books_limit')) {
                $table->unsignedInteger('home_recommended_books_limit')->default(6)->after('home_featured_video_limit');
            }
            if (!Schema::hasColumn('settings', 'home_recommended_audios_limit')) {
                $table->unsignedInteger('home_recommended_audios_limit')->default(6)->after('home_recommended_books_limit');
            }
            if (!Schema::hasColumn('settings', 'home_recommended_enabled')) {
                $table->boolean('home_recommended_enabled')->default(true)->after('home_recommended_audios_limit');
            }
            if (!Schema::hasColumn('settings', 'per_page_default')) {
                $table->unsignedInteger('per_page_default')->default(9)->after('home_recommended_enabled');
            }
            if (!Schema::hasColumn('settings', 'auto_publish')) {
                $table->boolean('auto_publish')->default(false)->after('per_page_default');
            }
            if (!Schema::hasColumn('settings', 'default_category_video_id')) {
                $table->unsignedBigInteger('default_category_video_id')->nullable()->after('auto_publish');
            }
            if (!Schema::hasColumn('settings', 'default_category_audio_id')) {
                $table->unsignedBigInteger('default_category_audio_id')->nullable()->after('default_category_video_id');
            }
            if (!Schema::hasColumn('settings', 'default_category_document_id')) {
                $table->unsignedBigInteger('default_category_document_id')->nullable()->after('default_category_audio_id');
            }
            if (!Schema::hasColumn('settings', 'comments_auto_approve')) {
                $table->boolean('comments_auto_approve')->default(true)->after('default_category_document_id');
            }
            if (!Schema::hasColumn('settings', 'comments_require_name')) {
                $table->boolean('comments_require_name')->default(false)->after('comments_auto_approve');
            }
            if (!Schema::hasColumn('settings', 'comments_require_email')) {
                $table->boolean('comments_require_email')->default(false)->after('comments_require_name');
            }
            if (!Schema::hasColumn('settings', 'moderation_block_words')) {
                $table->text('moderation_block_words')->nullable()->after('comments_require_email');
            }
            if (!Schema::hasColumn('settings', 'analytics_enabled')) {
                $table->boolean('analytics_enabled')->default(true)->after('moderation_block_words');
            }
            if (!Schema::hasColumn('settings', 'analytics_retention_days')) {
                $table->unsignedInteger('analytics_retention_days')->default(365)->after('analytics_enabled');
            }
            if (!Schema::hasColumn('settings', 'analytics_anonymize_ip')) {
                $table->boolean('analytics_anonymize_ip')->default(false)->after('analytics_retention_days');
            }
            if (!Schema::hasColumn('settings', 'maintenance_mode')) {
                $table->boolean('maintenance_mode')->default(false)->after('analytics_anonymize_ip');
            }
            if (!Schema::hasColumn('settings', 'maintenance_message')) {
                $table->string('maintenance_message')->nullable()->after('maintenance_mode');
            }
            if (!Schema::hasColumn('settings', 'force_admin_2fa')) {
                $table->boolean('force_admin_2fa')->default(false)->after('maintenance_message');
            }
            if (!Schema::hasColumn('settings', 'session_timeout_minutes')) {
                $table->unsignedInteger('session_timeout_minutes')->default(120)->after('force_admin_2fa');
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'site_tagline',
                'site_description',
                'contact_phone',
                'contact_address',
                'facebook_url',
                'instagram_url',
                'favicon',
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
            ]);
        });
    }
};








