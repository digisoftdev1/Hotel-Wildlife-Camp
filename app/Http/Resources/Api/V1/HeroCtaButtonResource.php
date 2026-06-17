<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "HeroCtaButton",
    properties: [
        new OA\Property(property: "button_name", type: "string", example: "Book Now"),
        new OA\Property(property: "navigate_to", type: "string", example: "packages")
    ]
)]
class HeroCtaButtonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'button_name' => $this->button_name,
            'navigate_to' => $this->page?->slug,
        ];
    }
}
