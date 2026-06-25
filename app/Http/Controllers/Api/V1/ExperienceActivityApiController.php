<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ExperienceActivityListResource;
use App\Http\Resources\Api\V1\ExperienceActivityDetailResource;
use App\Services\Api\V1\ExperienceActivityApiService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ExperienceActivityApiController extends Controller
{
    public function __construct(protected ExperienceActivityApiService $service) {}

    #[OA\Get(
        path: "/activities",
        summary: "List all published activities",
        description: "Returns a paginated list of published experience activities. Supports filtering by category name.",
        tags: ["Activities"],
        parameters: [
            new OA\Parameter(name: "category", in: "query", required: false, description: "Filter by category name", schema: new OA\Schema(type: "string")),
        ],
        responses: [
            new OA\Response(response: 200, description: "Paginated list of activities")
        ]
    )]
    public function index(Request $request)
    {
        $activities = $this->service->list($request->only(['category']));
        return ExperienceActivityListResource::collection($activities);
    }

    #[OA\Get(
        path: "/activities/{slug}",
        summary: "Get specific activity details",
        description: "Returns all the details of a single published experience activity by its slug.",
        tags: ["Activities"],
        parameters: [
            new OA\Parameter(name: "slug", in: "path", required: true, description: "The slug of the activity", schema: new OA\Schema(type: "string")),
        ],
        responses: [
            new OA\Response(response: 200, description: "Activity details"),
            new OA\Response(response: 404, description: "Activity not found"),
        ]
    )]
    public function show(string $slug)
    {
        $activity = $this->service->findBySlug($slug);

        if (!$activity) {
            return response()->json(['message' => 'Activity not found'], 404);
        }

        return new ExperienceActivityDetailResource($activity);
    }
}
