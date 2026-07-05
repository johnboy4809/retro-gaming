<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mame extends Model
{
    protected $table = 'mame';

    protected $fillable = [
        'rom',
        'full_name',
        'driver',
        'year',
        'manufacturer',
        'romof',
        'cloneof',
        'use_bios',
        'use_chds',
        'display_rotate',
        'display_width',
        'display_height',
        'display_orientation',
        'sourcefile',
    ];

    protected $casts = [
        'use_bios' => 'boolean',
        'use_chds' => 'boolean',
        'display_width' => 'integer',
        'display_height' => 'integer',
    ];

    protected $appends = ['hardware'];

    public function arcadeBoard()
    {
        return $this->belongsTo(ArcadeBoard::class, 'driver', 'driver');
    }

    public function getHardwareAttribute()
    {
        return $this->arcadeBoard?->board;
    }
}
