<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'game_type',
        'game_id',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function game()
    {
        return $this->morphTo();
    }
}
