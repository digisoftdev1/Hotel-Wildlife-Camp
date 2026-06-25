<?php

namespace App\Services;

use App\Models\RoomGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomGalleryService
{
    /**
     * Upload photos and create (or append to) the gallery record for a room.
     * If a gallery already exists for the room, new photos are merged in
     * rather than creating a duplicate record.
     */
    public function store(int $roomId, Request $request): RoomGallery
    {
        $photoPaths = $this->uploadPhotos($request, $roomId);

        $gallery = RoomGallery::firstOrNew(['room_id' => $roomId]);

        $existing = $gallery->photos ?? [];
        $gallery->photos = array_merge($existing, $photoPaths);
        $gallery->save();

        return $gallery;
    }

    /**
     * Diff the existing photos against the ones the user wants to keep,
     * delete removed files from disk, upload new files, then persist.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update(int $roomId, array $photosToKeep, Request $request): RoomGallery
    {
        $gallery = RoomGallery::where('room_id', $roomId)->firstOrFail();

        $oldPhotos     = $gallery->photos ?? [];
        $photosToDelete = array_diff($oldPhotos, $photosToKeep);

        $this->deleteFiles($photosToDelete);

        $newPhotoPaths = $request->hasFile('photos')
            ? $this->uploadPhotos($request, $roomId)
            : [];

        $gallery->photos = array_values(array_merge($photosToKeep, $newPhotoPaths));
        $gallery->save();

        return $gallery;
    }

    // ─── Private helpers ──────────────────────────────────────────────────────

    /**
     * Store each uploaded photo and return an array of storage paths.
     */
    private function uploadPhotos(Request $request, int $roomId): array
    {
        $paths = [];
        foreach ($request->file('photos') as $photo) {
            $paths[] = $photo->store("rooms/gallery/{$roomId}", 'public');
        }
        return $paths;
    }

    /**
     * Delete a list of file paths from the public disk.
     */
    private function deleteFiles(array $paths): void
    {
        foreach ($paths as $path) {
            Storage::disk('public')->delete($path);
        }
    }
}
