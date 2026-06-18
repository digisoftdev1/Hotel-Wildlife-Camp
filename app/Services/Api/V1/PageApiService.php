<?php

namespace App\Services\Api\V1;

use App\Models\Page;
use Illuminate\Database\Eloquent\Collection;

class PageApiService
{
    /**
     * Return all pages (name + slug only).
     */
    public function list(): Collection
    {
        return Page::select('name', 'slug')->get();
    }

    /**
     * Find a page by slug with all heroes and sections eagerly loaded.
     */
    public function findBySlug(string $slug): ?Page
    {
        return Page::where('slug', $slug)
            ->with([
                'heroes' => fn($q) => $q->with(['sliderImages', 'ctaButtons.page'])->orderBy('order'),
                'sections' => fn($q) => $q->with(['ctaButtons.page', 'images','about'])->orderBy('order'),
            ])
            ->first();
    }
}