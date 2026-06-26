<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomAmenity extends Model
{
    protected $fillable = [
        'name',
        'icon',
    ];

    public function rooms()
    {
        return $this->belongsToMany(
            Room::class,
            'room_amenity_pivots',
            'room_amenity_id',
            'room_id'
        );
    }
}