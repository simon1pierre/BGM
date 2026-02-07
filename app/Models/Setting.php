<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'site_name',
        'primary_color',
        'secondary_color',
        'logo',
        'youtube_channel',
        'contact_email',
        'notifications_email',
        'notifications_enabled',
        'mail_mailer',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_scheme',
        'mail_from_address',
        'mail_from_name',
    ];

    protected $casts = [
        'notifications_enabled' => 'boolean',
    ];

    public static function current(): ?self
    {
        return static::query()->latest('id')->first();
    }
}
