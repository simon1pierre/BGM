<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AudiobookPart extends Model
{
    protected $fillable = [
        'audiobook_id',
        'title',
        'audio_file',
        'duration',
        'sort_order',
        'is_published',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_published' => 'boolean',
    ];

    public function audiobook(): BelongsTo
    {
        return $this->belongsTo(audiobook::class, 'audiobook_id');
    }

    public function getAudioUrlAttribute(): ?string
    {
        if (empty($this->audio_file)) {
            return null;
        }

        return asset('storage/'.$this->audio_file);
    }
}
