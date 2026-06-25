<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'phones',
        'emails',
        'address',
        'map_url',
        'business_hours',
        'status',
    ];

    protected $casts = [
        'phones' => 'array',
        'emails' => 'array',
    ];
}
