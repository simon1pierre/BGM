<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Devotional extends Model
{
    use SoftDeletes;
    use HasTranslations;

    protected $fillable = [
        'title',
        'excerpt',
        'body',
        'cover_image',
        'scripture_reference',
        'author',
        'published_at',
        'featured',
        'is_published',
        'sort_order',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'featured' => 'boolean',
        'is_published' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected $appends = [
        'cover_image_url',
    ];

    public function getTitleAttribute($value)
    {
        return $this->translatedValue('title', $value);
    }

    public function getExcerptAttribute($value)
    {
        return $this->translatedValue('excerpt', $value);
    }

    public function getBodyAttribute($value)
    {
        return $this->translatedValue('body', $value);
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        $value = $this->attributes['cover_image'] ?? null;
        if (empty($value)) {
            return null;
        }

        if (preg_match('/^https?:\/\//i', $value)) {
            return $value;
        }

        if (str_starts_with($value, 'admin/') || str_starts_with($value, 'images/') || str_starts_with($value, 'landingpage/')) {
            return asset($value);
        }

        return asset('storage/'.$value);
    }
}


