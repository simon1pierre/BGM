<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ContentCategory;
use App\Models\ContentLike;
use App\Models\ContentComment;
use App\Models\Concerns\HasTranslations;

class book extends Model
{
    use SoftDeletes;
    use HasTranslations;

    protected $fillable = [
        'title',
        'description',
        'file_path',
        'cover_image',
        'author',
        'category_id',
        'series',
        'published_at',
        'featured',
        'recommended',
        'download_count',
        'is_published',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'featured' => 'boolean',
        'recommended' => 'boolean',
        'is_published' => 'boolean',
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
        if (!empty($this->cover_image)) {
            return asset('storage/'.$this->cover_image);
        }

        return null;
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
