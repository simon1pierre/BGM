<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailCampaign extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'subject',
        'preheader',
        'message',
        'body_html',
        'status',
        'scheduled_at',
        'sent_at',
        'featured_image_url',
        'video_url',
        'audio_url',
        'document_url',
        'cta_text',
        'cta_url',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];
}
