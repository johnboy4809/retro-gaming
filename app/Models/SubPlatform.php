<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubPlatform extends Model
{
    protected $fillable = [
        'platform_id',
        'name',
        'slug',
        'order_index',
        'is_active',
        'screenscraper_id',
    ];

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }
}
