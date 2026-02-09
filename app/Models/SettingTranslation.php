<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingTranslation extends Model
{
    protected $fillable = [
        'setting_id',
        'locale',
        'site_name',
        'site_tagline',
        'site_description',
        'footer_text',
        'hero_title',
        'hero_subtitle',
        'hero_primary_label',
        'hero_secondary_label',
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
    ];

    public function setting()
    {
        return $this->belongsTo(Setting::class);
    }
}
