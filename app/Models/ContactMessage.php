<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'ip_address',
        'user_agent',
        'locale',
        'page_url',
        'is_read',
        'read_at',
        'replied_at',
        'replied_by',
        'reply_subject',
        'reply_body',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'replied_at' => 'datetime',
    ];

    public function repliedByUser()
    {
        return $this->belongsTo(User::class, 'replied_by');
    }
}
