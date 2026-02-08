<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlaylistItem extends Model
{
    protected $fillable = [
        'playlist_id',
        'item_type',
        'item_id',
        'sort_order',
    ];

    public function playlist()
    {
        return $this->belongsTo(Playlist::class);
    }
}
