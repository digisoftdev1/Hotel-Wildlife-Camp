<?php

namespace App\Services\Api\V1;

use App\Models\Package;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PackageApiService
{
    /**
     * Return a paginated list of published packages with optional filters.
     */
    public function list(array $filters): LengthAwarePaginator
    {
        $query = Package::with(['category', 'currency'])->where('status', 'active');

        if (!empty($filters['category'])) {
            $query->whereHas('category', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['category'] . '%');
            });
        }

        if (!empty($filters['difficulty'])) {
            $query->where('grade', 'like', '%' . $filters['difficulty'] . '%');
        }

        if (!empty($filters['best_for'])) {
            $query->where('best_for', 'like', '%' . $filters['best_for'] . '%');
        }

        return $query->latest()->paginate(5);
    }

    /**
     * Find an active package by slug with all its relationships.
     */
    public function findBySlug(string $slug): ?Package
    {
        $package = Package::with(['category', 'currency', 'gallery', 'faqs'])
            ->where('slug', $slug)
            ->first();

        if (!$package || $package->status !== 'active') {
            return null;
        }

        return $package;
    }
}
