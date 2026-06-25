<?php

namespace App\Services\Api\V1;

use App\Models\Room;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class RoomApiService
{
    /**
     * Return a paginated list of published rooms.
     */
    public function list(): LengthAwarePaginator
    {
        return Room::with('currency')
            ->where('status', 'published')
            ->latest()
            ->paginate(5);
    }

    /**
     * Find a published room by slug with all its relationships.
     */
    public function findBySlug(string $slug): ?Room
    {
        $room = Room::with(['currency', 'amenities', 'specialFeatures', 'beds', 'gallery'])
            ->where('slug', $slug)
            ->first();

        if (!$room || $room->status !== 'published') {
            return null;
        }

        return $room;
    }
}
