<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityPackageCategory extends Model
{
    protected $fillable = [
        'name',
        'description', // description is now optional
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     * Ensure 'description' is nullable.
     */
    protected $casts = [
        'description' => 'string',
    ];

    public function experiences(): HasMany
    {
        return $this->hasMany(ExperienceActivity::class, 'category_id');
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
