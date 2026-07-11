<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'color',
        'description',
        'order_index',
        'is_active',
    ];

    public function subPlatforms()
    {
        return $this->hasMany(SubPlatform::class);
    }
}
