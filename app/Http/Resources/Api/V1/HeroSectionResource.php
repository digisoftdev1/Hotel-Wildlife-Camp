<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "HeroSection",
    title: "HeroSection",
    properties: [
        new OA\Property(property: "section_id", type: "integer", example: 1),
        new OA\Property(property: "order", type: "integer", example: 1),
        new OA\Property(property: "section_title", type: "string", example: "Welcome"),
        new OA\Property(property: "heading", type: "string", example: "Discover Nepal"),
        new OA\Property(property: "description", type: "string", example: "Explore amazing packages"),
        new OA\Property(property: "media_type", type: "string", enum: ["image", "video"], example: "image"),
        new OA\Property(property: "video_url", type: "string", nullable: true, example: "https://example.com/video.mp4"),
        new OA\Property(property: "status", type: "string", enum: ["published", "draft"], example: "published"),
        new OA\Property(property: "page", ref: "#/components/schemas/Page"),
        new OA\Property(property: "slider_images", type: "array", items: new OA\Items(ref: "#/components/schemas/HeroSliderImage")),
        new OA\Property(property: "cta_buttons", type: "array", items: new OA\Items(ref: "#/components/schemas/HeroCtaButton"))
    ]
)]
class HeroSectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'section_id'    => $this->id,
            'order'         => $this->order,
            'section_title' => $this->section_title,
            'heading'       => $this->heading,
            'description'   => $this->description,
            'media_type'    => $this->media_type,
            'video_url'     => $this->video_path ? asset('storage/' . $this->video_path) : null,
            'status'        => $this->status,
            'page'          => new PageResource($this->whenLoaded('page')),
            'slider_images' => HeroSliderImageResource::collection($this->whenLoaded('sliderImages')),
            'cta_buttons'   => HeroCtaButtonResource::collection($this->whenLoaded('ctaButtons')),
            'created_at'    => $this->created_at?->toISOString(),
            'updated_at'    => $this->updated_at?->toISOString(),
        ];
    }
}
