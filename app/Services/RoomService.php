<?php

namespace App\Services;

use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Room;
use App\Models\RoomAmenity;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RoomService
{
    public function create(StoreRoomRequest $request): Room
    {
        $validated = $request->validated();

        $room = Room::create([
            'room_name' => $validated['room_name'],
            'slug' => $validated['slug'] ?? null,
            'headline' => $validated['headline'] ?? null,
            'occupancy' => $validated['occupancy'],
            'currency_id' => $validated['currency_id'],
            'price' => $validated['price'],
            'room_size' => $validated['room_size'] ?? null,
            'excerpt' => $validated['excerpt'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'created_by' => auth()->id(),
        ]);

        $this->handleFeaturedImage($request, $room);
        $this->syncAmenities($room, $validated['amenities'] ?? []);
        $this->syncBeds($room, $validated['beds'] ?? []);
        $this->syncSpecialFeatures($room, $validated['special_features'] ?? []);

        return $room;
    }

    /**
     * Update an existing room and all related data.
     * Accepts the typed UpdateRoomRequest for the same reason.
     */
    public function update(Room $room, UpdateRoomRequest $request): Room
    {
        $validated = $request->validated();

        $room->update([
            'room_name' => $validated['room_name'],
            'slug' => $validated['slug'] ?? Str::slug($validated['room_name']),
            'headline' => $validated['headline'] ?? null,
            'occupancy' => $validated['occupancy'],
            'currency_id' => $validated['currency_id'],
            'price' => $validated['price'],
            'room_size' => $validated['room_size'] ?? null,
            'excerpt' => $validated['excerpt'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'updated_by' => auth()->id(),
        ]);

        $this->handleFeaturedImage($request, $room);

        if (isset($validated['amenities'])) {
            $this->syncAmenities($room, $validated['amenities']);
        } else {
            $room->amenities()->detach();
        }

        // Replace beds and special features entirely on update
        $room->beds()->delete();
        $this->syncBeds($room, $validated['beds'] ?? []);

        $room->specialFeatures()->delete();
        $this->syncSpecialFeatures($room, $validated['special_features'] ?? []);

        return $room;
    }

    /**
     * Delete a room along with its associated files and relations.
     */
    public function destroy(Room $room): void
    {
        if ($room->featured_image) {
            Storage::disk('public')->delete($room->featured_image);
        }

        if ($room->gallery) {
            $photos = $room->gallery->photos ?? [];
            foreach ($photos as $photo) {
                $diskPath = ltrim(str_replace('storage/', '', $photo), '/');
                if (Storage::disk('public')->exists($diskPath)) {
                    Storage::disk('public')->delete($diskPath);
                }
            }
            $room->gallery->delete();
        }

        $room->amenities()->detach();
        $room->beds()->delete();
        $room->specialFeatures()->delete();
        $room->delete();
    }


    private function handleFeaturedImage(StoreRoomRequest|UpdateRoomRequest $request, Room $room): void
    {
        if (!$request->hasFile('featured_image')) {
            return;
        }

        if ($room->featured_image) {
            Storage::disk('public')->delete($room->featured_image);
        }

        $path = $request->file('featured_image')
            ->store("rooms/{$room->id}/featured-image", 'public');

        $room->update(['featured_image' => $path]);
    }

    /**
     * Sync amenities using firstOrCreate to avoid duplicates.
     */
    private function syncAmenities(Room $room, array $amenities): void
    {
        if (empty($amenities)) {
            return;
        }

        $amenityIds = [];
        foreach ($amenities as $amenityData) {
            $amenity = RoomAmenity::firstOrCreate(
                ['name' => $amenityData['name']],
                ['icon' => $amenityData['icon'] ?? 'check']
            );
            $amenityIds[] = $amenity->id;
        }

        $room->amenities()->sync($amenityIds);
    }

    /**
     * Create bed records for the given room.
     */
    private function syncBeds(Room $room, array $beds): void
    {
        foreach ($beds as $bedData) {
            $room->beds()->create([
                'type' => $bedData['type'],
                'quantity' => $bedData['quantity'],
            ]);
        }
    }

    /**
     * Create special feature records for the given room.
     */
    private function syncSpecialFeatures(Room $room, array $features): void
    {
        foreach ($features as $feature) {
            $room->specialFeatures()->create(['feature' => $feature]);
        }
    }
}
