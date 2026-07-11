<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HandheldGame extends Model
{
    protected $table = 'handheld_games';

    protected $fillable = [
        'sub_platform_id',
        'rom',
        'title',
        'size_bytes',
        'release_date',
        'region',
        'crc32',
        'metadata',
        'is_public',
    ];

    protected $casts = [
        'size_bytes' => 'integer',
        'release_date' => 'date',
        'is_public' => 'boolean',
        'metadata' => 'array',
    ];

    protected $appends = ['total_size_bytes'];

    public function subPlatform()
    {
        return $this->belongsTo(SubPlatform::class);
    }
    
    public function chd()
    {
        return $this->hasOne(Chd::class, 'rom', 'rom');
    }

    public function getTotalSizeBytesAttribute()
    {
        $baseSize = (int) ($this->size_bytes ?? 0);
        $chdSize = $this->chd ? (int) $this->chd->size_bytes : 0;
        
        return $baseSize + $chdSize + 5242880;
    }
}
