<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class About extends Model
{
    protected $fillable = [
         'common_section_id',
        'established_year',
        'established_description',
        'location',
        'location_description',        

    ];
     public function section()
{
    return $this->belongsTo(PageCommonSection::class, 'common_section_id');
}

 
}