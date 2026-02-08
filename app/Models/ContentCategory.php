<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\video;
use App\Models\audio;
use App\Models\book;

class ContentCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $category): void {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function videos()
    {
        return $this->hasMany(video::class, 'category_id');
    }

    public function audios()
    {
        return $this->hasMany(audio::class, 'category_id');
    }

    public function documents()
    {
        return $this->hasMany(book::class, 'category_id');
    }
}
