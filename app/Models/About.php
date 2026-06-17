<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    protected $fillable = [
          'breadcrumb_title',
        'breadcrumb_description',
        'breadcrumb_image',
        'about_title',
        'about_description',
        'about_image',
        'established_year',
        'established_description',
        'location',
        'status',
        'location_description',
        'team_title',
        'team_description',
        'team_image',
        'facilities_title',
        'facilities',
        'keywords'
    ];

    protected $casts = [
        'facilities' => 'array'
    ];
}