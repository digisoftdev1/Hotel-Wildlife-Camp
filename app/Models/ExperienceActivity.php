<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExperienceActivity extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'excerpt',
        'duration',
        'difficulty_level',
        'highlights',
        'overview',
        'category_id',
        'featured_image',
        'status',
        'is_featured',
        'created_by',
        'updated_by',
        'best_time',
    ];

    protected $casts = [
        'highlights' => 'array',
        'is_featured' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ActivityPackageCategory::class, 'category_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
