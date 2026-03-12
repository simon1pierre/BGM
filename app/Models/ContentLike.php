<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentLike extends Model
{
    protected $fillable = [
        'content_type',
        'content_id',
        'device_hash',
        'ip_address',
        'user_agent',
        'session_id',
    ];

    public function content()
    {
        return $this->morphTo();
    }
}


