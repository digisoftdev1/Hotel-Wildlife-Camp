<?php

namespace App\Services\Api\V1;

use App\Models\HeroSection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Responsible solely for applying query filters to a HeroSection builder.
 * Single Responsibility: filter logic only.
 */
class HeroSectionQueryFilter
{
    public function apply(Builder $query, Request $request): Builder
    {
        $query = $this->filterByStatus($query, $request);
        $query = $this->filterByPage($query, $request);
        $query = $this->filterByMediaType($query, $request);
        $query = $this->filterBySearch($query, $request);
        $query = $this->applySorting($query, $request);

        return $query;
    }

    private function filterByStatus(Builder $query, Request $request): Builder
    {
        $status = $request->query('status');
        if ($status && in_array($status, ['published', 'draft'])) {
            $query->where('status', $status);
        }
        return $query;
    }

    private function filterByPage(Builder $query, Request $request): Builder
    {
        $pageSlug = $request->query('page');
        if ($pageSlug) {
            $query->whereHas('page', fn (Builder $q) => $q->where('slug', $pageSlug));
        }
        return $query;
    }

    private function filterByMediaType(Builder $query, Request $request): Builder
    {
        $mediaType = $request->query('media_type');
        if ($mediaType && in_array($mediaType, ['image', 'video'])) {
            $query->where('media_type', $mediaType);
        }
        return $query;
    }

    private function filterBySearch(Builder $query, Request $request): Builder
    {
        $search = $request->query('search');
        if ($search) {
            $query->where(function (Builder $q) use ($search) {
                $q->where('section_title', 'like', '%' . $search . '%')
                  ->orWhere('heading', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }
        return $query;
    }

    private function applySorting(Builder $query, Request $request): Builder
    {
        $allowed = ['id', 'created_at', 'updated_at', 'section_title'];
        $sortBy  = $request->query('sort_by', 'created_at');
        $sortDir = $request->query('sort_dir', 'desc');

        $sortBy  = in_array($sortBy, $allowed) ? $sortBy : 'created_at';
        $sortDir = in_array(strtolower($sortDir), ['asc', 'desc']) ? $sortDir : 'desc';

        return $query->orderBy($sortBy, $sortDir);
    }
}
