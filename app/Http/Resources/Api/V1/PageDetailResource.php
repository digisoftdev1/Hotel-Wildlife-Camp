<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'page_name'    => $this->name,
            'slug'         => $this->slug,
            'hero_section' => new HeroSectionSimpleResource($this->heroes?->first()),
            'sections'     => CommonSectionSimpleResource::collection($this->whenLoaded('sections')),
        ];
    }
}
