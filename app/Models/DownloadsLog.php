<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DownloadsLog extends Model
{
    use SoftDeletes;

    protected $table = 'downloads_log';

    protected $fillable = [
        'item_type',
        'item_id',
        'ip_address',
        'downloaded_at',
    ];

    public $timestamps = false;
}


