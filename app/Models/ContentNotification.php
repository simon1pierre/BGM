<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentNotification extends Model
{
    protected $fillable = [
        'payload',
        'target_type',
        'target_emails',
        'status',
        'sent_at',
        'created_by',
    ];

    protected $casts = [
        'payload' => 'array',
        'target_emails' => 'array',
        'sent_at' => 'datetime',
    ];
}








