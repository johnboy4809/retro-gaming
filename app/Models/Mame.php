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
        'size',
    ];

    protected $casts = [
        'use_bios' => 'boolean',
        'use_chds' => 'boolean',
        'display_width' => 'integer',
        'display_height' => 'integer',
        'size' => 'float',
    ];

    protected $appends = ['hardware', 'total_size'];

    public function arcadeBoard()
    {
        return $this->belongsTo(ArcadeBoard::class, 'driver', 'driver');
    }

    public function chd()
    {
        return $this->hasOne(Chd::class, 'rom', 'rom');
    }

    public function getHardwareAttribute()
    {
        return $this->arcadeBoard?->board;
    }

    public function getTotalSizeAttribute()
    {
        $baseSize = (float) ($this->size ?? 0);
        $chdSize = (float) ($this->relationLoaded('chd') && $this->chd ? $this->chd->size : ($this->chd?->size ?? 0));
        return $baseSize + $chdSize;
    }
}
