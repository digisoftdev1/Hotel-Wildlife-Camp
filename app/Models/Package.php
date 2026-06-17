<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Package extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'duration',
        'grade',
        'best_for',
        'includes',
        'excerpt',
        'overview',
        'featured_image',
        'price',
        'currency_id',
        'itinerary',
        'category_id',
        'status',
        'is_featured',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'includes' => 'array',
        'itinerary' => 'array',
        'is_featured' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(ActivityPackageCategory::class, 'category_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function gallery(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(PackageGallery::class);
    }

    public function faqs(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(PackageFaq::class);
    }

    protected static function booted()
    {
        static::deleting(function ($package) {
            // Delete the gallery folder
            $dir = 'packages/gallery/' . ($package->slug ?? Str::slug($package->name));
            if (Storage::disk('public')->exists($dir)) {
                Storage::disk('public')->deleteDirectory($dir);
            }

            // Delete featured image
            if ($package->featured_image) {
                Storage::disk('public')->delete($package->featured_image);
            }
        });
    }
}
