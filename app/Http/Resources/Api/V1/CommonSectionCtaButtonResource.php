<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class CommonSectionCtaButtonResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'button_name' => $this->button_name,
            'navigate_to' => $this->page?->slug,
        ];
    }
}
