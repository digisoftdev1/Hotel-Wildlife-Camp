<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HeroSection extends Model
{
    protected $fillable = [
        'page_id',
        'section_title',
        'heading',
        'description',
        'video_path',
        'media_type',
        'status',
        'order',
        'created_by',
        'updated_by',
    ];

    public function page(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function sliderImages(): HasMany
    {
        return $this->hasMany(HeroSliderImage::class)->orderBy('order');
    }

    public function ctaButtons(): HasMany
    {
        return $this->hasMany(HeroCtaButton::class)->orderBy('order');
    }
}
