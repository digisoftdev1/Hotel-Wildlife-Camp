<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasOne;


class Room extends Model
{
    protected $fillable = [
        'room_name',
        'slug',
        'headline',
        'occupancy',
        'currency_id',
        'price',
        'room_size',
        'excerpt',
        'description',
        'featured_image',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'occupancy' => 'integer',
        'room_size' => 'integer',
    ];

    protected static function booted()
    {
        static::creating(function ($room) {
            if (empty($room->slug)) {
                $room->slug = Str::slug($room->room_name);
            }
        });
    }

    public function gallery(): HasOne
    {
        return $this->hasOne(RoomGallery::class, 'room_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function amenities()
    {
        return $this->belongsToMany(
            RoomAmenity::class,
            'room_amenity_pivots',
            'room_id',
            'room_amenity_id'
        );
    }
    public function specialFeatures()
    {
        return $this->hasMany(RoomSpecialFeature::class, 'room_id');
    }
    public function beds()
    {
        return $this->hasMany(RoomBed::class, 'room_id');
    }
}