<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('setting_translations', function (Blueprint $table) {
            if (!Schema::hasColumn('setting_translations', 'about_title')) {
                $table->string('about_title')->nullable()->after('hero_secondary_label');
            }
            if (!Schema::hasColumn('setting_translations', 'about_subtitle')) {
                $table->text('about_subtitle')->nullable()->after('about_title');
            }
            if (!Schema::hasColumn('setting_translations', 'about_body')) {
                $table->text('about_body')->nullable()->after('about_subtitle');
            }
            if (!Schema::hasColumn('setting_translations', 'about_mission_title')) {
                $table->string('about_mission_title')->nullable()->after('about_body');
            }
            if (!Schema::hasColumn('setting_translations', 'about_mission_body')) {
                $table->text('about_mission_body')->nullable()->after('about_mission_title');
            }
            if (!Schema::hasColumn('setting_translations', 'about_vision_title')) {
                $table->string('about_vision_title')->nullable()->after('about_mission_body');
            }
            if (!Schema::hasColumn('setting_translations', 'about_vision_body')) {
                $table->text('about_vision_body')->nullable()->after('about_vision_title');
            }
            if (!Schema::hasColumn('setting_translations', 'about_values')) {
                $table->text('about_values')->nullable()->after('about_vision_body');
            }
            if (!Schema::hasColumn('setting_translations', 'resources_title')) {
                $table->string('resources_title')->nullable()->after('about_values');
            }
            if (!Schema::hasColumn('setting_translations', 'resources_subtitle')) {
                $table->text('resources_subtitle')->nullable()->after('resources_title');
            }
            if (!Schema::hasColumn('setting_translations', 'resources_cta_label')) {
                $table->string('resources_cta_label')->nullable()->after('resources_subtitle');
            }
            if (!Schema::hasColumn('setting_translations', 'contact_title')) {
                $table->string('contact_title')->nullable()->after('resources_cta_label');
            }
            if (!Schema::hasColumn('setting_translations', 'contact_subtitle')) {
                $table->text('contact_subtitle')->nullable()->after('contact_title');
            }
            if (!Schema::hasColumn('setting_translations', 'contact_form_intro')) {
                $table->text('contact_form_intro')->nullable()->after('contact_subtitle');
            }
            if (!Schema::hasColumn('setting_translations', 'events_title')) {
                $table->string('events_title')->nullable()->after('contact_form_intro');
            }
            if (!Schema::hasColumn('setting_translations', 'events_subtitle')) {
                $table->text('events_subtitle')->nullable()->after('events_title');
            }
            if (!Schema::hasColumn('setting_translations', 'events_feature_title')) {
                $table->string('events_feature_title')->nullable()->after('events_subtitle');
            }
            if (!Schema::hasColumn('setting_translations', 'events_feature_date')) {
                $table->string('events_feature_date')->nullable()->after('events_feature_title');
            }
            if (!Schema::hasColumn('setting_translations', 'events_feature_location')) {
                $table->string('events_feature_location')->nullable()->after('events_feature_date');
            }
            if (!Schema::hasColumn('setting_translations', 'events_feature_body')) {
                $table->text('events_feature_body')->nullable()->after('events_feature_location');
            }
            if (!Schema::hasColumn('setting_translations', 'give_title')) {
                $table->string('give_title')->nullable()->after('events_feature_body');
            }
            if (!Schema::hasColumn('setting_translations', 'give_subtitle')) {
                $table->text('give_subtitle')->nullable()->after('give_title');
            }
            if (!Schema::hasColumn('setting_translations', 'give_cta_label')) {
                $table->string('give_cta_label')->nullable()->after('give_subtitle');
            }
        });
    }

    public function down(): void
    {
        Schema::table('setting_translations', function (Blueprint $table) {
            $table->dropColumn([
                'about_title',
                'about_subtitle',
                'about_body',
                'about_mission_title',
                'about_mission_body',
                'about_vision_title',
                'about_vision_body',
                'about_values',
                'resources_title',
                'resources_subtitle',
                'resources_cta_label',
                'contact_title',
                'contact_subtitle',
                'contact_form_intro',
                'events_title',
                'events_subtitle',
                'events_feature_title',
                'events_feature_date',
                'events_feature_location',
                'events_feature_body',
                'give_title',
                'give_subtitle',
                'give_cta_label',
            ]);
        });
    }
};


