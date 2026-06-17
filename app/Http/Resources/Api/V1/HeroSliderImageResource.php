<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "HeroSliderImage",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "image_url", type: "string", nullable: true, example: "https://example.com/image.jpg"),
        new OA\Property(property: "section_title", type: "string", example: "Slide Title"),
        new OA\Property(property: "heading", type: "string", example: "Slide Heading"),
        new OA\Property(property: "description", type: "string", example: "Slide description"),
        new OA\Property(property: "order", type: "integer", example: 1)
    ]
)]
class HeroSliderImageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'image_url'     => $this->image_path ? asset('storage/' . $this->image_path) : null,
            'section_title' => $this->section_title,
            'heading'       => $this->heading,
            'description'   => $this->description,
            'order'         => $this->order,
        ];
    }
}
