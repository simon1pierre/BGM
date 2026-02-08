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
    ];

    public function setting()
    {
        return $this->belongsTo(Setting::class);
    }
}
