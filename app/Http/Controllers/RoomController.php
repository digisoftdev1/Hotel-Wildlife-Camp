<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Currency;
use App\Models\Room;
use App\Models\RoomAmenity;
use App\Services\RoomService;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    public function __construct(private readonly RoomService $roomService) {}

    /**
     * Display a listing of all rooms.
     */
    public function index()
    {
        $rooms = Room::with(['currency:id,sign'])
            ->select('id', 'room_name', 'headline', 'currency_id', 'status', 'price', 'occupancy', 'featured_image')
            ->get();

        return view('rooms.allrooms', compact('rooms'));
    }

    /**
     * Show the form for creating a new room.
     */
    public function create()
    {
        $currencies = Currency::select('id', 'sign')->get();

        return view('rooms.addroom', compact('currencies'));
    }

    /**
     * Store a newly created room in storage.
     */
    public function store(StoreRoomRequest $request)
    {
        DB::beginTransaction();

        try {
            $room = $this->roomService->create($request);

            DB::commit();

            return redirect()
                ->route('rooms.edit', $room)
                ->with('success', 'Room created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Failed to create room: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified room.
     */
    public function edit(string $id)
    {
        $room         = Room::with(['amenities', 'beds', 'specialFeatures', 'currency'])->findOrFail($id);
        $allAmenities = RoomAmenity::all();
        $currencies   = Currency::select('id', 'sign')->get();

        $roomAmenities = $room->amenities->map(fn ($a) => [
            'id'   => $a->id,
            'name' => $a->name,
            'icon' => $a->icon,
        ]);

        $roomBeds = $room->beds->map(fn ($b) => [
            'id'       => $b->id,
            'type'     => $b->type,
            'quantity' => $b->quantity,
        ]);

        $roomSpecialFeatures = $room->specialFeatures->pluck('feature');

        return view('rooms.addroom', [
            'room'               => $room,
            'allAmenities'       => $allAmenities,
            'currencies'         => $currencies,
            'roomAmenities'      => $roomAmenities,
            'roomBeds'           => $roomBeds,
            'roomSpecialFeatures'=> $roomSpecialFeatures,
        ]);
    }

    /**
     * Update the specified room in storage.
     */
    public function update(UpdateRoomRequest $request, Room $room)
    {
        DB::beginTransaction();

        try {
            $this->roomService->update($room, $request);

            DB::commit();

            return redirect()
                ->route('rooms.edit', $room)
                ->with('success', 'Room updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Failed to update room: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified room and all associated data from storage.
     */
    public function destroy(Room $room)
    {
        DB::beginTransaction();

        try {
            $this->roomService->destroy($room);

            DB::commit();

            return redirect()
                ->route('rooms.index')
                ->with('success', 'Room deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Failed to delete room: ' . $e->getMessage());
        }
    }
}