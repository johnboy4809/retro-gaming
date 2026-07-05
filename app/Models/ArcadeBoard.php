<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArcadeBoard extends Model
{
    protected $table = 'arcade_boards';

    protected $fillable = ['driver', 'board'];

    public function mames()
    {
        return $this->hasMany(Mame::class, 'driver', 'driver');
    }
}
