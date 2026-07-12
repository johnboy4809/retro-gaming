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

    public function getRomCountAttribute()
    {
        if (!$this->platform) {
            return 0;
        }

        switch ($this->platform->slug) {
            case 'arcade': return \App\Models\ArcadeGame::where('sub_platform_id', $this->id)->count();
            case 'console': return \App\Models\ConsoleGame::where('sub_platform_id', $this->id)->count();
            case 'home_computer': return \App\Models\ComputerGame::where('sub_platform_id', $this->id)->count();
            case 'handhelds': return \App\Models\HandheldGame::where('sub_platform_id', $this->id)->count();
            default: return 0;
        }
    }
}
