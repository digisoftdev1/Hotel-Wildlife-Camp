<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomGallery;
use App\Services\RoomGalleryService;
use Illuminate\Http\Request;

class RoomGalleryController extends Controller
{
    public function __construct(private readonly RoomGalleryService $galleryService) {}

    /**
     * Display a listing of all rooms with their gallery data.
     */
    public function index()
    {
        $rooms = Room::select('id', 'room_name', 'featured_image')
            ->with(['gallery' => fn ($q) => $q->select('room_id', 'photos')])
            ->get();

        return view('rooms.roomgallery', compact('rooms'));
    }

    /**
     * Store gallery photos for the given room.
     * Creates the gallery record if it does not yet exist; merges if it does.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id'   => 'required|exists:rooms,id',
            'photos'    => 'required|array|min:1',
            'photos.*'  => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $this->galleryService->store((int) $validated['room_id'], $request);

        return back()->with('success', 'Gallery images uploaded successfully.');
    }

    /**
     * Update the gallery photos for the given room (keep/remove/add).
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'existing_photos'   => 'nullable|array',
            'existing_photos.*' => 'string',
            'photos'            => 'nullable|array',
            'photos.*'          => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $photosToKeep = $validated['existing_photos'] ?? [];

        $this->galleryService->update((int) $id, $photosToKeep, $request);

        return back()->with('success', 'Gallery updated successfully.');
    }

    // ─── Unused resource stubs (kept for route compatibility) ─────────────────

    public function create()  { /* Not used */ }
    public function show(string $id) { /* Not used */ }
    public function edit(string $id) { /* Not used */ }
    public function destroy(string $id) { /* Not used */ }
}