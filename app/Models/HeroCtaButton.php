<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HeroCtaButton extends Model
{
    protected $fillable = [
        'hero_section_id',
        'button_name',
        'page_id',
        'order',
    ];

    public function heroSection(): BelongsTo
    {
        return $this->belongsTo(HeroSection::class);
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
