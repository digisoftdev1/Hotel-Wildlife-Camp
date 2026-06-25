<?php

namespace App\Services\Api\V1;

use App\Models\ExperienceActivity;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ExperienceActivityApiService
{
    /**
     * Return a paginated list of published activities with optional filters.
     */
    public function list(array $filters): LengthAwarePaginator
    {
        $query = ExperienceActivity::with(['category'])->where('status', 'published');

        if (!empty($filters['category'])) {
            $query->whereHas('category', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['category'] . '%');
            });
        }

        return $query->latest()->paginate(5);
    }

    /**
     * Find a published activity by slug with its relationships.
     */
    public function findBySlug(string $slug): ?ExperienceActivity
    {
        $activity = ExperienceActivity::with(['category'])
            ->where('slug', $slug)
            ->first();

        if (!$activity || $activity->status !== 'published') {
            return null;
        }

        return $activity;
    }
}
