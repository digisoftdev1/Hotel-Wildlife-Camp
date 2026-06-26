<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\RoomListResource;
use App\Http\Resources\Api\V1\RoomDetailResource;
use App\Services\Api\V1\RoomApiService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class RoomApiController extends Controller
{
    public function __construct(protected RoomApiService $service) {}

    #[OA\Get(
        path: "/rooms",
        summary: "List all published rooms",
        description: "Returns a list of published rooms.",
        tags: ["Rooms"],
        responses: [
            new OA\Response(response: 200, description: "List of rooms")
        ]
    )]
    public function index(Request $request)
    {
        return RoomListResource::collection($this->service->list());
    }

    #[OA\Get(
        path: "/rooms/{slug}",
        summary: "Get specific room details",
        description: "Returns all the details of a single published room by its slug, including relationships like amenities, special features, beds, and gallery.",
        tags: ["Rooms"],
        parameters: [
            new OA\Parameter(name: "slug", in: "path", required: true, description: "The slug of the room", schema: new OA\Schema(type: "string")),
        ],
        responses: [
            new OA\Response(response: 200, description: "Room details"),
            new OA\Response(response: 404, description: "Room not found"),
        ]
    )]
    public function show(string $slug)
    {
        $room = $this->service->findBySlug($slug);

        if (!$room) {
            return response()->json(['message' => 'Room not found'], 404);
        }

        return new RoomDetailResource($room);
    }
}
