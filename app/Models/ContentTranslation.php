<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentTranslation extends Model
{
    protected $fillable = [
        'content_type',
        'content_id',
        'locale',
        'title',
        'description',
    ];

    public function content()
    {
        return $this->morphTo();
    }
}
