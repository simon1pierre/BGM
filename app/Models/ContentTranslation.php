<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentTranslation extends Model
{
    protected $fillable = [
        'content_type',
        'content_id',
        'locale',
        'source_locale',
        'title',
        'description',
        'translation_status',
        'translated_by',
        'quality_score',
        'is_bible_locked',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'quality_score' => 'float',
        'is_bible_locked' => 'boolean',
        'reviewed_at' => 'datetime',
    ];

    public function content()
    {
        return $this->morphTo();
    }
}
