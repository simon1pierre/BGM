<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\HasTranslations;

class Playlist extends Model
{
    use SoftDeletes;
    use HasTranslations;

    protected $fillable = [
        'title',
        'description',
        'type',
        'cover_image',
        'is_published',
        'featured',
        'created_by',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'featured' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(PlaylistItem::class)->orderBy('sort_order');
    }

    public function getTitleAttribute($value)
    {
        return $this->translatedValue('title', $value);
    }

    public function getDescriptionAttribute($value)
    {
        return $this->translatedValue('description', $value);
    }
}


