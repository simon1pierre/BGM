<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MinistryLeader extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'position',
        'country',
        'role_type',
        'email',
        'phone',
        'photo_path',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
}
