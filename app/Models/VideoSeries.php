<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\HasTranslations;

class VideoSeries extends Model
{
    use SoftDeletes;
    use HasTranslations;

    protected $fillable = [
        'title',
        'description',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function videos()
    {
        return $this->hasMany(video::class, 'video_series_id');
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
