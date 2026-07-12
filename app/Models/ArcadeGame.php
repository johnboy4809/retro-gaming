<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArcadeGame extends Model
{
    protected $table = 'arcade_games';

    protected $fillable = [
        'sub_platform_id',
        'rom',
        'title',
        'size_bytes',
        'release_date',
        'region',
        'crc32',
        'metadata',
        'hardware', // Arcade specific
        'is_public',
    ];

    protected $casts = [
        'size_bytes' => 'integer',
        'release_date' => 'date',
        'is_public' => 'boolean',
        'metadata' => 'array',
    ];

    protected $with = ['chd'];
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
        // Add 5MB (5242880 bytes) for metadata/images overhead
        $metaOverhead = 5242880; 
        
        return $baseSize + $chdSize + $metaOverhead;
    }

    public function getHardwareAttribute()
    {
        $meta = is_array($this->metadata) ? $this->metadata : json_decode($this->metadata ?? '{}', true);
        $driver = $meta['driver'] ?? null;
        
        if ($driver) {
            $board = ArcadeBoard::where('driver', $driver)->first();
            return $board ? $board->board : $driver;
        }
        
        return null;
    }
}
