<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RomMetadata extends Model
{
    protected $table = 'rom_metadata';

    protected $fillable = [
        'rom',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];
}
