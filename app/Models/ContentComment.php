<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentComment extends Model
{
    protected $fillable = [
        'content_type',
        'content_id',
        'name',
        'email',
        'body',
        'ip_address',
        'user_agent',
        'session_id',
        'is_approved',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    public function content()
    {
        return $this->morphTo();
    }
}
