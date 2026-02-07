<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class audio extends Model
{
    use SoftDeletes;

    protected $table = 'audios';

    protected $fillable = [
        'title',
        'description',
        'audio_file',
        'duration',
        'speaker',
        'series',
        'published_at',
        'featured',
        'play_count',
        'download_count',
        'is_published',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'featured' => 'boolean',
        'is_published' => 'boolean',
        'play_count' => 'integer',
        'download_count' => 'integer',
    ];
}
