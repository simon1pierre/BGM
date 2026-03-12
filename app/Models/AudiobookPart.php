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
        'language',
        'duration',
        'sort_order',
        'is_published',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_published' => 'boolean',
    ];

    public function getLanguageLabelAttribute(): string
    {
        return match ($this->language) {
            'en' => 'English',
            'fr' => 'French',
            default => 'Kinyarwanda',
        };
    }

    public function audiobook(): BelongsTo
    {
        return $this->belongsTo(Audiobook::class, 'audiobook_id');
    }

    public function getAudioUrlAttribute(): ?string
    {
        if (empty($this->audio_file)) {
            return null;
        }

        return asset('storage/'.$this->audio_file);
    }
}


