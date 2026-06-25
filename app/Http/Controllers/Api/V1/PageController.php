<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PageListResource;
use App\Http\Resources\Api\V1\PageDetailResource;
use App\Services\Api\V1\PageApiService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;

class PageController extends Controller
{
    public function __construct(protected PageApiService $service) {}

    #[OA\Get(
        path: "/pages",
        summary: "List all pages",
        description: "Returns a list of all pages with name and slug",
        tags: ["Pages"],
        responses: [
            new OA\Response(response: 200, description: "List of pages")
        ]
    )]
    public function index(): AnonymousResourceCollection
    {
        return PageListResource::collection($this->service->list());
    }

    #[OA\Get(
        path: "/pages/{slug}",
        summary: "Get page with all sections",
        description: "Returns a complete page with hero section and all dynamic sections with their content filtered by display_fields",
        tags: ["Pages"],
        parameters: [
            new OA\Parameter(name: "slug", in: "path", required: true, description: "Page slug", schema: new OA\Schema(type: "string")),
        ],
        responses: [
            new OA\Response(response: 200, description: "Page with sections"),
            new OA\Response(response: 404, description: "Not Found"),
        ]
    )]
    public function show(string $slug)
    {
        try {
            $page = $this->service->findBySlug($slug);

            if (!$page) {
                return response()->json(['message' => 'Page not found'], 404);
            }

            return new PageDetailResource($page);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Failed to retrieve page',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500);
        }
    }
}