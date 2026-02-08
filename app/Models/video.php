<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ContentCategory;
use App\Models\Playlist;
use App\Models\ContentLike;
use App\Models\ContentComment;

class video extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'youtube_url',
        'youtube_id',
        'thumbnail',
        'category_id',
        'speaker',
        'series',
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

    public function playlists()
    {
        return $this->belongsToMany(Playlist::class, 'playlist_items', 'item_id', 'playlist_id')
            ->wherePivot('item_type', 'video')
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
