<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Playlist extends Model
{
    use SoftDeletes;

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
}
