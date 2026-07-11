<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chd extends Model
{
    protected $table = 'chd';

    protected $fillable = [
        'rom',
        'size_bytes',
    ];

    protected $casts = [
        'size_bytes' => 'integer',
    ];
}
