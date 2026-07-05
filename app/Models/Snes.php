<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Snes extends Model
{
    protected $table = 'snes';

    protected $fillable = [
        'rom',
        'region',
        'release_date',
        'size_mb',
        'crc32',
    ];
}
