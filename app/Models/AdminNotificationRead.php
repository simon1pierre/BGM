<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminNotificationRead extends Model
{
    protected $fillable = [
        'user_id',
        'activity_log_id',
        'read_at',
    ];
}








