<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Page extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'parent_id'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function children()
    {
        return $this->hasMany(Page::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    public function heroes()
    {
        return $this->hasMany(HeroSection::class);
    }

    public function sections()
    {
        return $this->hasMany(PageCommonSection::class);
    }

    public function publishedHero()
    {
        return $this->heroes()->where('status', 'published')->first();
    }

    public function publishedSections()
    {
        return $this->sections()
            ->where('status', 'published')
            ->orderBy('id', 'asc')
            ->get();
    }
}