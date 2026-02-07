<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserStatusLog extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'changed_by',
        'old_status',
        'new_status',
        'reason',
    ];
}
