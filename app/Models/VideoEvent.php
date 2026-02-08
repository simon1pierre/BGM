<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoEvent extends Model
{
    protected $fillable = [
        'video_id',
        'event_type',
        'ip_address',
        'user_agent',
        'referrer',
        'page_url',
        'session_id',
        'device_type',
        'screen_width',
        'screen_height',
        'timezone',
        'language',
        'platform',
        'device_hash',
        'watch_seconds',
        'share_channel',
    ];
}
