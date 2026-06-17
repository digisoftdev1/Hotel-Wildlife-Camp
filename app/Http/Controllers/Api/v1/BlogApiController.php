<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\BlogListResource;
use App\Http\Resources\Api\V1\BlogDetailResource;
use App\Services\Api\V1\BlogApiService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class BlogApiController extends Controller
{
    public function __construct(protected BlogApiService $service) {}

    #[OA\Get(
        path: "/blogs",
        summary: "List all published blogs",
        description: "Returns a paginated list of published blogs. Supports filtering by category name and keywords.",
        tags: ["Blogs"],
        parameters: [
            new OA\Parameter(name: "category", in: "query", required: false, description: "Filter by category name", schema: new OA\Schema(type: "string")),
            new OA\Parameter(name: "keywords", in: "query", required: false, description: "Filter by keywords", schema: new OA\Schema(type: "string")),
        ],
        responses: [
            new OA\Response(response: 200, description: "Paginated list of blogs")
        ]
    )]
    public function index(Request $request)
    {
        $blogs = $this->service->list($request->only(['category', 'keywords']));
        return BlogListResource::collection($blogs);
    }

    #[OA\Get(
        path: "/blogs/{slug}",
        summary: "Get specific blog details",
        description: "Returns all the details of a single published blog by its slug. Handles redirects if the slug has been updated.",
        tags: ["Blogs"],
        parameters: [
            new OA\Parameter(name: "slug", in: "path", required: true, description: "The slug of the blog", schema: new OA\Schema(type: "string")),
        ],
        responses: [
            new OA\Response(response: 200, description: "Blog details"),
            new OA\Response(response: 301, description: "Resource moved permanently"),
            new OA\Response(response: 404, description: "Blog not found"),
        ]
    )]
    public function show(string $slug)
    {
        $result = $this->service->findBySlug($slug);

        if (!$result) {
            return response()->json(['message' => 'Blog not found'], 404);
        }

        $blog = $result['blog'];

        if ($result['redirect']) {
            return response()->json([
                'message' => 'Resource moved permanently.',
                'new_slug' => $blog->slug,
            ], 301, ['Location' => url('/api/v1/blogs/' . $blog->slug)]);
        }

        return new BlogDetailResource($blog);
    }
}
