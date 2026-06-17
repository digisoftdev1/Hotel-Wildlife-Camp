<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PageCommonSection extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'page_id',
        'section_identifier',
        'section_type',
        'section_title',
        'slug',
        'heading',
        'description',
        'image_path',
        'display_fields',
        'status',
        'order',
        'created_by',
        'updated_by',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($section) {
            $source = $section->section_identifier ?: $section->section_title;
            if ($source) {
                $section->slug = \Illuminate\Support\Str::slug($source);
            }
        });
    }

    protected $casts = [
        'display_fields' => 'array',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function ctaButtons(): HasMany
    {
        return $this->hasMany(CommonSectionCtaButton::class, 'common_section_id')->orderBy('order');
    }

    public function images(): HasMany
    {
        return $this->hasMany(CommonSectionImage::class, 'common_section_id')->orderBy('order');
    }

}

