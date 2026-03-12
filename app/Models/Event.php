<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'event_type',
        'location',
        'venue',
        'starts_at',
        'ends_at',
        'timezone',
        'live_platform',
        'live_url',
        'registration_url',
        'image_path',
        'is_published',
        'is_featured',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
    ];
}








