<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArcadeBoard extends Model
{
    protected $table = 'arcade_boards';

    protected $fillable = ['driver', 'board'];

    public function arcadeGames()
    {
        return $this->hasMany(ArcadeGame::class, 'driver', 'driver');
    }
}
