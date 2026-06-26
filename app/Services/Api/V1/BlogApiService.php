<?php

namespace App\Services\Api\V1;

use App\Models\Blog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BlogApiService
{
    /**
     * Return a paginated list of published blogs with optional filters.
     */
    public function list(array $filters): LengthAwarePaginator
    {
        $query = Blog::with('category')->where('status', 'published');

        if (!empty($filters['category'])) {
            $query->whereHas('category', function ($q) use ($filters) {
                $q->where('category_name', 'like', '%' . $filters['category'] . '%');
            });
        }

        if (!empty($filters['keywords'])) {
            $query->whereJsonContains('keywords', $filters['keywords']);
        }

        return $query->latest()->paginate(10);
    }

    /**
     * Find a published blog by slug, handling redirect cases.
     * Returns null if not found, or an array ['blog' => Blog, 'redirect' => bool].
     */
    public function findBySlug(string $slug): ?array
    {
        $result = Blog::findBySlugOrRedirect($slug);

        if (!$result) {
            return null;
        }

        $blog = $result['blog'];

        if (!$result['redirect'] && $blog->status !== 'published') {
            return null;
        }

        if (!$result['redirect']) {
            $blog->load('category');
        }

        return $result;
    }
}
