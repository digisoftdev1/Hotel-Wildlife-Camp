<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;


class RoomAmenityPivot extends Pivot
{
    protected $fillable = [
        'room_id',
        'room_amenity_id',
    ];
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * RoomAmenity relation
     */
    public function amenity()
    {
        return $this->belongsTo(RoomAmenity::class, 'room_amenity_id');
    }
}