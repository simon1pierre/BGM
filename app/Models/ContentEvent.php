<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentEvent extends Model
{
    protected $fillable = [
        'content_type',
        'content_id',
        'event_type',
        'ip_address',
        'user_agent',
        'referrer',
        'page_url',
        'session_id',
        'visitor_id',
        'reader_session_id',
        'device_type',
        'screen_width',
        'screen_height',
        'timezone',
        'language',
        'platform',
        'device_hash',
        'watch_seconds',
        'page_number',
        'total_pages',
        'progress_percent',
        'share_channel',
        'geo_country',
        'geo_country_code',
        'geo_region',
        'geo_city',
        'geo_continent_code',
        'geo_latitude',
        'geo_longitude',
        'geo_timezone',
        'geo_org',
        'geo_asn',
    ];

    public function content()
    {
        return $this->morphTo();
    }
}








