<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomSpecialFeature extends Model
{
    protected $fillable = [
        'room_id',
        'feature',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}