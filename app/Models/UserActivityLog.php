<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\AdminNotificationRead;

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

    public function actorUser()
    {
        return $this->belongsTo(User::class, 'actor_user_id');
    }

    public function reads()
    {
        return $this->hasMany(AdminNotificationRead::class, 'activity_log_id');
    }
}








