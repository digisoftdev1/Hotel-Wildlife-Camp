<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PackageListResource;
use App\Http\Resources\Api\V1\PackageDetailResource;
use App\Services\Api\V1\PackageApiService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class PackageApiController extends Controller
{
    public function __construct(protected PackageApiService $service) {}

    #[OA\Get(
        path: "/packages",
        summary: "List all published packages",
        description: "Returns a paginated list of published packages. Supports filtering by category name, difficulty (grade), and best for.",
        tags: ["Packages"],
        parameters: [
            new OA\Parameter(name: "category", in: "query", required: false, description: "Filter by category name", schema: new OA\Schema(type: "string")),
            new OA\Parameter(name: "difficulty", in: "query", required: false, description: "Filter by difficulty (maps to grade)", schema: new OA\Schema(type: "string")),
            new OA\Parameter(name: "best_for", in: "query", required: false, description: "Filter by best for", schema: new OA\Schema(type: "string")),
        ],
        responses: [
            new OA\Response(response: 200, description: "Paginated list of packages")
        ]
    )]
    public function index(Request $request)
    {
        $packages = $this->service->list($request->only(['category', 'difficulty', 'best_for']));
        return PackageListResource::collection($packages);
    }

    #[OA\Get(
        path: "/packages/{slug}",
        summary: "Get specific package details",
        description: "Returns all the details of a single published package by its slug, including relationships like gallery and faqs.",
        tags: ["Packages"],
        parameters: [
            new OA\Parameter(name: "slug", in: "path", required: true, description: "The slug of the package", schema: new OA\Schema(type: "string")),
        ],
        responses: [
            new OA\Response(response: 200, description: "Package details"),
            new OA\Response(response: 404, description: "Package not found"),
        ]
    )]
    public function show(string $slug)
    {
        $package = $this->service->findBySlug($slug);

        if (!$package) {
            return response()->json(['message' => 'Package not found'], 404);
        }

        return new PackageDetailResource($package);
    }
}
