<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ContentCategory;
use App\Models\Playlist;
use App\Models\VideoSeries;
use App\Models\ContentLike;
use App\Models\ContentComment;
use App\Models\Concerns\HasTranslations;

class Video extends Model
{
    use SoftDeletes;
    use HasTranslations;

    protected $fillable = [
        'title',
        'description',
        'youtube_url',
        'youtube_id',
        'thumbnail',
        'category_id',
        'speaker',
        'series',
        'video_series_id',
        'published_at',
        'featured',
        'view_count',
        'is_published',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'featured' => 'boolean',
        'is_published' => 'boolean',
        'view_count' => 'integer',
        'video_series_id' => 'integer',
    ];

    protected $appends = [
        'thumbnail_url',
    ];

    public function getThumbnailUrlAttribute(): ?string
    {
        if (!empty($this->thumbnail)) {
            return asset('storage/'.$this->thumbnail);
        }

        if (!empty($this->youtube_id)) {
            return 'https://img.youtube.com/vi/'.$this->youtube_id.'/hqdefault.jpg';
        }

        return null;
    }

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

    public function playlists()
    {
        return $this->belongsToMany(Playlist::class, 'playlist_items', 'item_id', 'playlist_id')
            ->wherePivot('item_type', 'video')
            ->withPivot('sort_order')
            ->orderByPivot('sort_order');
    }

    public function seriesRecord()
    {
        return $this->belongsTo(VideoSeries::class, 'video_series_id');
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








