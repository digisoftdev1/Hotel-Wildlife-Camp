<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HeroSliderImage extends Model
{
    protected $fillable = [
        'hero_section_id',
        'image_path',
        'section_title',
        'heading',
        'description',
        'order',
    ];

    public function homeHeroSection(): BelongsTo
    {
        return $this->belongsTo(HeroSection::class);
    }
}
