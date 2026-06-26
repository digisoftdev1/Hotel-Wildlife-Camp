<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomBed extends Model
{
    protected $fillable = [
        'room_id',
        'type',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}