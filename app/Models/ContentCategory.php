<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\Video;
use App\Models\Audio;
use App\Models\Audiobook;
use App\Models\Book;
use App\Models\Concerns\HasTranslations;

class ContentCategory extends Model
{
    use SoftDeletes;
    use HasTranslations;

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
        return $this->hasMany(Video::class, 'category_id');
    }

    public function audios()
    {
        return $this->hasMany(Audio::class, 'category_id');
    }

    public function documents()
    {
        return $this->hasMany(Book::class, 'category_id');
    }

    public function audiobooks()
    {
        return $this->hasMany(Audiobook::class, 'category_id');
    }

    public function getNameAttribute($value)
    {
        return $this->translatedValue('title', $value);
    }

    public function getDescriptionAttribute($value)
    {
        return $this->translatedValue('description', $value);
    }
}










