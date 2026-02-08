<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ContentCategory;
use App\Models\Playlist;
use App\Models\ContentLike;
use App\Models\ContentComment;
use App\Models\Concerns\HasTranslations;

class audio extends Model
{
    use SoftDeletes;
    use HasTranslations;

    protected $table = 'audios';

    protected $fillable = [
        'title',
        'description',
        'audio_file',
        'thumbnail',
        'duration',
        'category_id',
        'speaker',
        'series',
        'published_at',
        'featured',
        'recommended',
        'play_count',
        'download_count',
        'is_published',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'featured' => 'boolean',
        'recommended' => 'boolean',
        'is_published' => 'boolean',
        'play_count' => 'integer',
        'download_count' => 'integer',
    ];


    public function category()
    {
        return $this->belongsTo(ContentCategory::class, 'category_id');
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

    public function playlists()
    {
        return $this->belongsToMany(Playlist::class, 'playlist_items', 'item_id', 'playlist_id')
            ->wherePivot('item_type', 'audio')
            ->withPivot('sort_order')
            ->orderByPivot('sort_order');
    }

    public function likes()
    {
        return $this->morphMany(ContentLike::class, 'content');
    }

    public function comments()
    {
        return $this->morphMany(ContentComment::class, 'content');
    }
}
