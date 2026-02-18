<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AudiencePageEvent extends Model
{
    protected $fillable = [
        'event_type',
        'visitor_id',
        'session_id',
        'device_hash',
        'route_name',
        'page_url',
        'referrer',
        'cta_label',
        'cta_target',
        'scroll_depth',
        'engaged_seconds',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'ip_address',
        'user_agent',
        'device_type',
        'screen_width',
        'screen_height',
        'timezone',
        'language',
        'platform',
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
}
