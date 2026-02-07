<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserActivityLog extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'actor_user_id',
        'target_user_id',
        'action',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];
}
