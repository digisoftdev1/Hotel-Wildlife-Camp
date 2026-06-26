<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Page",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "name", type: "string", example: "Home"),
        new OA\Property(property: "slug", type: "string", example: "home"),
        new OA\Property(property: "parent", ref: "#/components/schemas/Page", nullable: true)
    ]
)]
class PageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'     => $this->id,
            'name'   => $this->name,
            'slug'   => $this->slug,
            'parent' => $this->when($this->parent_id !== null, fn () => [
                'id'   => $this->parent?->id,
                'name' => $this->parent?->name,
                'slug' => $this->parent?->slug,
            ]),
        ];
    }
}
