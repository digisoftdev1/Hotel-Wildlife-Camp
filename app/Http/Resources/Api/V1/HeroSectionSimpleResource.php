<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "HeroSectionSimple",
    properties: [
        new OA\Property(property: "section_id", type: "integer", example: 1),
        new OA\Property(property: "order", type: "integer", example: 1),
        new OA\Property(property: "section_title", type: "string", example: "Welcome"),
        new OA\Property(property: "heading", type: "string", example: "Discover Nepal"),
        new OA\Property(property: "description", type: "string", example: "Explore amazing packages"),
        new OA\Property(property: "media_type", type: "string", enum: ["image", "video"], example: "image"),
        new OA\Property(property: "video_path", type: "string", nullable: true),
        new OA\Property(property: "slider_images", type: "array", items: new OA\Items(type: "object")),
        new OA\Property(property: "cta_buttons", type: "array", items: new OA\Items(ref: "#/components/schemas/HeroCtaButton"))
    ]
)]
class HeroSectionSimpleResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'section_id' => $this->id,
            'order' => $this->order,
            'section_title' => $this->section_title,
            'heading' => $this->heading,
            'description' => $this->description,
            'media_type' => $this->media_type,
            'video_path' => $this->video_path,
            'slider_images' => $this->whenLoaded('sliderImages'),
            'cta_buttons' => HeroCtaButtonResource::collection($this->whenLoaded('ctaButtons')),
        ];
    }
}