<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ContentCategory;
use App\\Models\\Book;
use App\Models\AudiobookPart;
use App\Models\Concerns\HasTranslations;

class Audiobook extends Model
{
    use SoftDeletes;
    use HasTranslations;

    protected $table = 'audiobooks';

    protected $fillable = [
        'title',
        'description',
        'audio_file',
        'thumbnail',
        'duration',
        'category_id',
        'book_id',
        'narrator',
        'series',
        'published_at',
        'featured',
        'recommended',
        'is_prayer_audio',
        'play_count',
        'download_count',
        'is_published',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'featured' => 'boolean',
        'recommended' => 'boolean',
        'is_prayer_audio' => 'boolean',
        'is_published' => 'boolean',
        'play_count' => 'integer',
        'download_count' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(ContentCategory::class, 'category_id');
    }

    public function linkedBook()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function parts()
    {
        return $this->hasMany(AudiobookPart::class, 'audiobook_id')
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function publishedParts()
    {
        return $this->hasMany(AudiobookPart::class, 'audiobook_id')
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function resolvePlayableAudioFile(): ?string
    {
        if (!empty($this->audio_file)) {
            return $this->audio_file;
        }

        if ($this->relationLoaded('publishedParts')) {
            return $this->publishedParts->first()?->audio_file;
        }

        return $this->publishedParts()->value('audio_file');
    }

    public function getTitleAttribute($value)
    {
        return $this->translatedValue('title', $value);
    }

    public function getDescriptionAttribute($value)
    {
        return $this->translatedValue('description', $value);
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if (!empty($this->thumbnail)) {
            return asset('storage/'.$this->thumbnail);
        }

        return null;
    }
}


